"""Arma en GTM-KQFHDQ2M un workspace en borrador con Clarity y los eventos de interacción.

Uso: seo/.venv/bin/python seo/gtm_build.py
Idempotente: crea o actualiza por nombre. No publica (el token no tiene ese scope); se revisa con
Preview y se publica desde la interfaz de GTM.

GA4 sigue cargándose por gtag directo desde WPCode. Por eso las etiquetas GA4 de aquí llaman a
window.gtag en lugar de usar etiquetas "Evento GA4" nativas, que inicializarían un segundo Google
tag para el mismo ID y duplicarían page_view.
"""
from __future__ import annotations

import sys
from typing import Any

from google_oauth import GoogleApiError, GoogleClient, get_credentials

GTM = "https://tagmanager.googleapis.com/tagmanager/v2"
GA_ADMIN = "https://analyticsadmin.googleapis.com/v1beta"

CONTAINER_PUBLIC_ID = "GTM-KQFHDQ2M"
GA4_PROPERTY = "properties/558031673"
GA4_ID = "G-Z4TJ46TF1X"
CLARITY_ID = "yydi8xyvq0"
WORKSPACE = "Medición EG - Clarity + interacción"
ALL_PAGES = "2147479553"  # id fijo del activador integrado "All Pages"

# Custom HTML en ES5: el compilador de GTM rechaza sintaxis moderna.
CLARITY_HTML = f"""<script type="text/javascript">
(function(c,l,a,r,i,t,y){{
c[a]=c[a]||function(){{(c[a].q=c[a].q||[]).push(arguments)}};
t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
}})(window, document, "clarity", "script", "{CLARITY_ID}");
</script>"""

# Los listeners solo empujan al dataLayer; qué herramienta recibe cada evento lo deciden los activadores.
# Prefijo eg_ obligatorio: gtag('event', X) también entra al dataLayer y GTM lo ve como evento X;
# si el evento interno se llamara igual que el de GA4, la etiqueta puente se re-dispararía en bucle.
LISTENERS_HTML = """<script>
(function () {
  if (window.__egListeners) return;
  window.__egListeners = true;
  var dl = window.dataLayer = window.dataLayer || [];

  // "toggle" no burbujea: se escucha en fase de captura.
  document.addEventListener('toggle', function (e) {
    var d = e.target;
    if (!d || d.tagName !== 'DETAILS' || !d.open) return;
    var s = d.querySelector('summary');
    dl.push({ event: 'eg_faq_open', faq_question: s ? s.textContent.replace(/\\s+/g, ' ').trim().slice(0, 100) : '' });
  }, true);

  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('a[hreflang]');
    if (!a || a.getAttribute('aria-current') === 'true') return;
    dl.push({ event: 'eg_cambio_idioma', idioma: a.getAttribute('lang') || a.getAttribute('hreflang') });
  }, true);

  // Solo cuando CF7 entregó el correo; no se envía ningún dato del formulario.
  document.addEventListener('wpcf7mailsent', function () {
    dl.push({ event: 'eg_form_enviado' });
  });
})();
</script>"""

CONTACT_TYPE_JS = """function () {
  var h = {{Click URL}} || '';
  if (h.indexOf('agendapro.com') > -1) return 'click_agendar_cita';
  if (h.indexOf('wa.me/') > -1 || h.indexOf('api.whatsapp.com') > -1) return 'click_whatsapp';
  if (h.indexOf('tel:') === 0) return 'click_telefono';
  return '';
}"""


def p(key: str, value: str, kind: str = "template") -> dict[str, str]:
    return {"type": kind, "key": key, "value": value}


def condition(op: str, left: str, right: str) -> dict[str, Any]:
    return {"type": op, "parameter": [p("arg0", left), p("arg1", right)]}


def html_tag(name: str, html: str, triggers: list[str]) -> dict[str, Any]:
    return {
        "name": name, "type": "html", "firingTriggerId": triggers,
        "parameter": [p("html", html), p("supportDocumentWrite", "false", "boolean")],
    }


def dlv(name: str, key: str) -> dict[str, Any]:
    return {"name": name, "type": "v", "parameter": [p("dataLayerVersion", "2", "integer"), p("name", key)]}


def ga4_bridge(event: str, param: str, variable: str) -> str:
    return (f"<script>if (window.gtag) gtag('event', '{event}', "
            f"{{ send_to: '{GA4_ID}', {param}: {{{{{variable}}}}} }});</script>")


class Workspace:
    """Upsert por nombre sobre un workspace de GTM, para poder re-ejecutar el script sin duplicar."""

    def __init__(self, client: GoogleClient, path: str) -> None:
        self.c, self.path = client, path

    def upsert(self, kind: str, body: dict[str, Any]) -> str:
        id_field = {"variables": "variableId", "triggers": "triggerId", "tags": "tagId"}[kind]
        existing = {o["name"]: o for o in self.c.paginate(f"{GTM}/{self.path}/{kind}", kind[:-1])}
        if (old := existing.get(body["name"])) is not None:
            obj = self.c.put(f"{GTM}/{old['path']}", body)
            print(f"  actualizado {kind[:-1]}: {body['name']}")
        else:
            obj = self.c.post(f"{GTM}/{self.path}/{kind}", body)
            print(f"  creado {kind[:-1]}: {body['name']}")
        return obj[id_field]


def find_container(c: GoogleClient) -> str:
    for acc in c.paginate(f"{GTM}/accounts", "account"):
        for cont in c.paginate(f"{GTM}/{acc['path']}/containers", "container"):
            if cont["publicId"] == CONTAINER_PUBLIC_ID:
                return cont["path"]
    raise GoogleApiError(f"La cuenta no ve el contenedor {CONTAINER_PUBLIC_ID}")


def get_workspace(c: GoogleClient, container: str) -> str:
    for ws in c.paginate(f"{GTM}/{container}/workspaces", "workspace"):
        if ws["name"] == WORKSPACE:
            return ws["path"]
    ws = c.post(f"{GTM}/{container}/workspaces",
                {"name": WORKSPACE, "description": "Generado por seo/gtm_build.py. Revisar con Preview antes de publicar."})
    return ws["path"]


def build(c: GoogleClient) -> str:
    ws_path = get_workspace(c, find_container(c))
    print(f"Workspace: {WORKSPACE}")
    enabled = {v["type"] for v in c.paginate(f"{GTM}/{ws_path}/built_in_variables", "builtInVariable")}
    if "clickUrl" not in enabled:
        c.post(f"{GTM}/{ws_path}/built_in_variables?type=clickUrl")
        print("  habilitada variable integrada: Click URL")

    ws = Workspace(c, ws_path)
    ws.upsert("variables", dlv("DLV - faq_question", "faq_question"))
    ws.upsert("variables", dlv("DLV - idioma", "idioma"))
    ws.upsert("variables", {"name": "JS - tipo de contacto", "type": "jsm", "parameter": [p("javascript", CONTACT_TYPE_JS)]})

    t_faq = ws.upsert("triggers", {"name": "CE - faq_open", "type": "customEvent",
                                   "customEventFilter": [condition("equals", "{{_event}}", "eg_faq_open")]})
    t_lang = ws.upsert("triggers", {"name": "CE - cambio_idioma", "type": "customEvent",
                                    "customEventFilter": [condition("equals", "{{_event}}", "eg_cambio_idioma")]})
    t_eg = ws.upsert("triggers", {"name": "CE - eventos EG", "type": "customEvent",
                                  "customEventFilter": [condition("matchRegex", "{{_event}}", "^eg_(faq_open|cambio_idioma|form_enviado)$")]})
    t_contact = ws.upsert("triggers", {
        "name": "Clic - contacto (AgendaPro, WhatsApp, tel)", "type": "linkClick",
        "waitForTags": p("", "false", "boolean"), "checkValidation": p("", "false", "boolean"),
        "filter": [condition("matchRegex", "{{Click URL}}", r"agendapro\.com|wa\.me/|api\.whatsapp\.com|^tel:")],
    })

    # Clarity after the load event (audit 2026-10-20): on All Pages it competed with the home's LCP on mobile.
    # A contact click before load is not tagged in Clarity; GA4 still counts it.
    t_loaded = ws.upsert("triggers", {"name": "Window Loaded - todas las páginas", "type": "windowLoaded"})
    ws.upsert("tags", html_tag("Clarity - base", CLARITY_HTML, [t_loaded]))
    ws.upsert("tags", html_tag("Listeners - FAQ, idioma, formulario", LISTENERS_HTML, [ALL_PAGES]))
    ws.upsert("tags", html_tag("GA4 - faq_open (vía gtag)", ga4_bridge("faq_open", "faq_question", "DLV - faq_question"), [t_faq]))
    ws.upsert("tags", html_tag("GA4 - cambio_idioma (vía gtag)", ga4_bridge("cambio_idioma", "idioma", "DLV - idioma"), [t_lang]))
    # Etiqueta las sesiones de Clarity con las conversiones para filtrar grabaciones de quien sí contactó.
    ws.upsert("tags", html_tag(
        "Clarity - eventos de conversión",
        "<script>if (window.clarity) clarity('event', {{Event}} === 'gtm.linkClick' ? {{JS - tipo de contacto}} : {{Event}}.replace(/^eg_/, ''));</script>",
        [t_contact, t_eg],
    ))
    return ws_path


def ga4_custom_dimensions(c: GoogleClient) -> None:
    """Sin dimensión personalizada, GA4 recibe el parámetro pero no deja verlo en reportes."""
    wanted = {"faq_question": "Pregunta FAQ", "idioma": "Idioma elegido", "link_text": "Texto del enlace"}
    existing = {d["parameterName"] for d in c.paginate(f"{GA_ADMIN}/{GA4_PROPERTY}/customDimensions", "customDimensions")}
    for param, label in wanted.items():
        if param in existing:
            continue
        c.post(f"{GA_ADMIN}/{GA4_PROPERTY}/customDimensions",
               {"parameterName": param, "displayName": label, "scope": "EVENT"})
        print(f"  GA4: dimensión personalizada '{label}' ({param})")


def main() -> int:
    c = GoogleClient(get_credentials())
    try:
        ws_path = build(c)
        # quick_preview compila el workspace: detecta errores de JS o referencias rotas sin publicar nada.
        preview = c.post(f"{GTM}/{ws_path}:quick_preview")
        if preview.get("compilerError"):
            print("ERROR: el workspace no compila; revisa el panel de GTM.", file=sys.stderr)
            return 1
        print("Compila sin errores.")
        ga4_custom_dimensions(c)
    except GoogleApiError as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
