"""Alinea el Perfil de Negocio de Google con los datos oficiales (docs/directorios.md).

Uso:
    seo/.venv/bin/python seo/gbp_profile.py           # muestra los cambios sin aplicarlos
    seo/.venv/bin/python seo/gbp_profile.py --apply   # respalda el perfil y aplica

No toca nombre, dirección ni categoría principal: cambiarlos puede mandar el perfil a re-verificación.
Respaldo en ~/backups-deploy/<fecha>-gbp/.
"""
from __future__ import annotations

import argparse
import json
import sys
from datetime import datetime
from pathlib import Path
from typing import Any

from gbp_metrics import INFO_URL, LOCATION
from google_oauth import GoogleApiError, GoogleClient, get_credentials

BACKUP_ROOT = Path.home() / "backups-deploy"

PHONE = "55 5404 9467"
# El UTM separa en GA4 las visitas del perfil de las de búsqueda orgánica.
WEBSITE = "https://eternalglow.com.mx/?utm_source=google&utm_medium=organic&utm_campaign=gbp"
BOOKING = "https://eternalglowmexico.site.agendapro.com/mx/sucursal/340833"
WHATSAPP = "https://wa.me/525554049467"

# Precios en MXN, los mismos del sitio (seo/prices.php). Los servicios sin precio fijo en el sitio se quedan sin precio.
PRICES = {
    "Botox por unidad": 165,
    "Baby Botox": 4499,
    "Full Face Botox": 8499,
    "Botox Foxy Eyes": 1250,
    "Botox Lip Flip": 1250,
    "Botox para bruxismo / maseteros": 8499,
    "Botox para hiperhidrosis en manos": 8499,
    "Botox para hiperhidrosis en axilas": 8499,
    "Traptox / Botox en trapecio": 8499,
    "Rinomodelación sin cirugía": 6499,
    "Relleno de ojeras / Under Eye Filler": 6499,
    "Relleno de pómulos / Cheekbone Filler": 6499,
    "Ácido hialurónico en labios / Lip Filler": 6499,
    "Relleno de mentón": 6499,
    "Relleno de surcos nasogenianos": 6499,
    "Marcaje mandibular": 6499,
    "Relleno de región temporal": 6499,
    "Hilos espiculados/tensores 1 pieza": 1500,
    "Hilos espiculados/tensores 5 piezas": 5 * 1300,
    "Hilos espiculados/tensores 9 piezas": 9 * 1100,
    "job_type_id:sculptra_treatments": 14999,
    "Tratamiento Radiesse": 8499,
    "HIFU facial": 7000,
    "Hollywood Carbon Peel / Picoláser": 1499,
    "Peeling químico facial": 2000,
    "Microneedling / Dermapen": 2499,
}

ATTRIBUTES = [
    {"name": "attributes/url_whatsapp", "valueType": "URL", "uriValues": [{"uri": WHATSAPP}]},
    {"name": "attributes/url_appointment", "valueType": "URL", "uriValues": [{"uri": BOOKING}]},
    {"name": "attributes/preferred_messaging_service", "valueType": "ENUM", "values": ["whatsapp"]},
]


def service_key(item: dict[str, Any]) -> str:
    if "freeFormServiceItem" in item:
        return item["freeFormServiceItem"]["label"]["displayName"]
    return item["structuredServiceItem"]["serviceTypeId"]


def with_prices(items: list[dict[str, Any]]) -> list[dict[str, Any]]:
    out = []
    for item in items:
        item = dict(item)
        if (price := PRICES.get(service_key(item))) is not None:
            item["price"] = {"currencyCode": "MXN", "units": str(price)}
        out.append(item)
    return out


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--apply", action="store_true", help="aplicar los cambios (sin esto solo los muestra)")
    args = parser.parse_args()

    client = GoogleClient(get_credentials())
    mask = "title,phoneNumbers,websiteUri,serviceItems,categories,profile,regularHours,storefrontAddress"
    loc = client.get(f"{INFO_URL}/{LOCATION}", {"readMask": mask})
    attrs = client.get(f"{INFO_URL}/{LOCATION}/attributes")

    patch = {
        "phoneNumbers": {"primaryPhone": PHONE},  # el 55 3646 0497 es un número viejo (confirmado el 2026-10-20)
        "websiteUri": WEBSITE,
        "serviceItems": with_prices(loc.get("serviceItems", [])),
    }
    missing = set(PRICES) - {service_key(i) for i in loc.get("serviceItems", [])}
    if missing:
        print(f"AVISO: servicios con precio que ya no están en el perfil: {sorted(missing)}", file=sys.stderr)

    current = {a["name"]: a for a in attrs.get("attributes", [])}
    print(f"Teléfonos: {loc.get('phoneNumbers')} → {patch['phoneNumbers']}")
    print(f"Sitio: {loc.get('websiteUri')} → {WEBSITE}")
    priced = sum(1 for i in patch["serviceItems"] if "price" in i)
    print(f"Servicios con precio: {sum(1 for i in loc.get('serviceItems', []) if 'price' in i)} → {priced} de {len(patch['serviceItems'])}")
    for a in ATTRIBUTES:
        old = current.get(a["name"], {})
        print(f"{a['name']}: {old.get('uriValues') or old.get('values')} → {a.get('uriValues') or a.get('values')}")

    if not args.apply:
        print("\nSin cambios (usa --apply).")
        return 0

    backup = BACKUP_ROOT / f"{datetime.now():%Y%m%d-%H%M%S}-gbp"
    backup.mkdir(parents=True)
    (backup / "location.json").write_text(json.dumps(loc, ensure_ascii=False, indent=1))
    (backup / "attributes.json").write_text(json.dumps(attrs, ensure_ascii=False, indent=1))
    print(f"\nRespaldo en {backup}")

    client.request("PATCH", f"{INFO_URL}/{LOCATION}", params={"updateMask": ",".join(patch)}, json=patch)
    client.request(
        "PATCH", f"{INFO_URL}/{LOCATION}/attributes",
        params={"attributeMask": ",".join(a["name"] for a in ATTRIBUTES)},
        json={"name": f"{LOCATION}/attributes", "attributes": ATTRIBUTES},
    )
    print("Perfil actualizado. Google puede tardar unos minutos (o revisar los cambios) antes de mostrarlos.")
    return 0


if __name__ == "__main__":
    try:
        sys.exit(main())
    except GoogleApiError as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        sys.exit(1)
