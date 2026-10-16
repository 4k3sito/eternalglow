"""Inventario de solo lectura: qué contenedores GTM, propiedades GA4 y sitios de Search Console ve la cuenta.

Uso: seo/.venv/bin/python seo/google_inventory.py
No modifica nada; sirve para confirmar IDs y estado antes de tocar el contenedor.
"""
from __future__ import annotations

import sys
from collections.abc import Callable

from google_oauth import GoogleApiError, GoogleClient, get_credentials

GTM = "https://tagmanager.googleapis.com/tagmanager/v2"
GA_ADMIN = "https://analyticsadmin.googleapis.com/v1beta"
GA_DATA = "https://analyticsdata.googleapis.com/v1beta"
GSC = "https://www.googleapis.com/webmasters/v3"

TARGET_CONTAINER = "GTM-KQFHDQ2M"
TARGET_STREAM = "G-Z4TJ46TF1X"
CONVERSION_EVENTS = ["click_agendar_cita", "click_whatsapp", "click_telefono", "form_success"]


def gtm(c: GoogleClient) -> None:
    print("\n## Google Tag Manager")
    for acc in c.paginate(f"{GTM}/accounts", "account"):
        print(f"- Cuenta: {acc['name']} ({acc['accountId']})")
        for cont in c.paginate(f"{GTM}/{acc['path']}/containers", "container"):
            mark = "  <-- el del sitio" if cont["publicId"] == TARGET_CONTAINER else ""
            print(f"  - {cont['publicId']} {cont['name']} {cont.get('usageContext')}{mark}")
            if not mark:
                continue
            try:
                live = c.get(f"{GTM}/{cont['path']}/versions:live")
                print(f"    Versión publicada: {live.get('containerVersionId')} "
                      f"({len(live.get('tag', []))} etiquetas, {len(live.get('trigger', []))} activadores)")
            except GoogleApiError:
                print("    Versión publicada: ninguna (contenedor vacío)")
            for ws in c.paginate(f"{GTM}/{cont['path']}/workspaces", "workspace"):
                tags = c.paginate(f"{GTM}/{ws['path']}/tags", "tag")
                print(f"    Workspace '{ws['name']}': {len(tags)} etiquetas")
                for t in tags:
                    print(f"      · {t['name']} [{t['type']}]")


def ga4(c: GoogleClient) -> None:
    print("\n## Google Analytics 4")
    for acc in c.paginate(f"{GA_ADMIN}/accountSummaries", "accountSummaries"):
        print(f"- Cuenta: {acc.get('displayName')} ({acc['account']})")
        for prop in acc.get("propertySummaries", []):
            pid = prop["property"]
            streams = c.paginate(f"{GA_ADMIN}/{pid}/dataStreams", "dataStreams")
            ids = [s.get("webStreamData", {}).get("measurementId") for s in streams]
            mark = "  <-- el del sitio" if TARGET_STREAM in ids else ""
            print(f"  - {prop.get('displayName')} ({pid}) streams={ids}{mark}")
            if not mark:
                continue
            keys = [k["eventName"] for k in c.paginate(f"{GA_ADMIN}/{pid}/keyEvents", "keyEvents")]
            print(f"    Eventos clave: {keys or 'ninguno'}")
            report = c.post(f"{GA_DATA}/{pid}:runReport", {
                "dateRanges": [{"startDate": "28daysAgo", "endDate": "yesterday"}],
                "dimensions": [{"name": "eventName"}],
                "metrics": [{"name": "eventCount"}, {"name": "totalUsers"}],
                "dimensionFilter": {"filter": {"fieldName": "eventName",
                                               "inListFilter": {"values": CONVERSION_EVENTS + ["page_view", "session_start"]}}},
            })
            print("    Últimos 28 días (evento: conteo / usuarios):")
            for row in report.get("rows", []):
                name = row["dimensionValues"][0]["value"]
                count, users = (m["value"] for m in row["metricValues"])
                print(f"      {name}: {count} / {users}")


def search_console(c: GoogleClient) -> None:
    print("\n## Search Console")
    for site in c.get(f"{GSC}/sites").get("siteEntry", []):
        print(f"- {site['siteUrl']} ({site['permissionLevel']})")


def main() -> int:
    client = GoogleClient(get_credentials())
    failed = False
    sections: list[Callable[[GoogleClient], None]] = [gtm, ga4, search_console]
    for section in sections:
        try:
            section(client)
        except GoogleApiError as exc:  # una API sin habilitar no debe ocultar el resto del inventario
            failed = True
            print(f"  ERROR: {exc}", file=sys.stderr)
    return 1 if failed else 0


if __name__ == "__main__":
    sys.exit(main())
