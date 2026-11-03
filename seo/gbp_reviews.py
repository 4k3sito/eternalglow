"""Baja las reseñas del Perfil de Negocio de Google y genera el cache del tema.

Uso:
    seo/.venv/bin/python seo/gbp_reviews.py
    seo/.venv/bin/python seo/gbp_reviews.py --theme /ruta/al/tema

Requiere la Reviews API v4 (mybusiness.googleapis.com) habilitada en el proyecto y el
mismo token de seo/google_oauth.py. Escribe dos archivos:

  - seo/gbp-data/google-reviews-api.json: crudo normalizado, ignorado por git.
  - <tema>/includes/google-reviews.json: cache que lee el sitio (sí se versiona).

El tema muestra las reseñas de 5 estrellas con texto de 60 a 600 caracteres. Para las
que Google tradujo, guarda el original en `es` y la traducción en `en`; para las demás,
detecta el idioma. Así el carrusel no depende de Trustindex.
"""
from __future__ import annotations

import argparse
import json
import re
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

from gbp_metrics import ACCOUNTS_URL, INFO_URL, LOCATION
from google_oauth import GoogleApiError, GoogleClient, get_credentials

REVIEWS_URL = "https://mybusiness.googleapis.com/v4"
STARS = {"FIVE": 5, "FOUR": 4, "THREE": 3, "TWO": 2, "ONE": 1}
FALLBACK_PLACE_ID = "ChIJS7Rw2Ef_0YURsG6Y2CrptYA"
REPO = Path(__file__).resolve().parent.parent
DEFAULT_THEME = next(REPO.glob("u355783479*/domains/eternalglow.com.mx/public_html/wp-content/themes/eternalglow"), None)
RAW_OUT = Path(__file__).resolve().parent / "gbp-data" / "google-reviews-api.json"

# Stopwords enough to tell Spanish from English in a review of 60+ characters.
EN = re.compile(r"\b(the|and|was|very|with|this|that|for|you|have|experience|treatment|doctor|from|they)\b", re.I)
ES = re.compile(r"\b(el|la|los|las|muy|con|para|que|por|una|un|del|doctor|tratamiento|experiencia|fue|me)\b", re.I)


def detect_language(text: str) -> str:
    en, es = len(EN.findall(text)), len(ES.findall(text))
    if es > en:
        return "es"
    if en > es:
        return "en"
    return "es" if re.search(r"[áéíóúñ¿¡]", text, re.I) else "en"


def split_comment(comment: str) -> tuple[str, str]:
    """Return (English translation, original text). Google appends "(Original)" to translations."""
    text = (comment or "").strip()
    if text.startswith("(Translated by Google)"):
        text = text[len("(Translated by Google)"):].strip()
        if "(Original)" in text:
            translated, original = text.split("(Original)", 1)
            return translated.strip(), original.strip()
    return "", text


def useful(text: str) -> bool:
    return 60 <= len(text) <= 600


def find_parent(client: GoogleClient) -> str:
    """The account that owns LOCATION, as accounts/{id}/locations/{id}."""
    for account in client.paginate(ACCOUNTS_URL, "accounts"):
        try:
            locations = client.paginate(
                f"{INFO_URL}/{account['name']}/locations", "locations",
                {"readMask": "name", "pageSize": 100},
            )
        except GoogleApiError:
            continue
        if any(loc.get("name") == LOCATION for loc in locations):
            return f"{account['name']}/{LOCATION}"
    raise GoogleApiError(f"No encontré {LOCATION} en las cuentas autorizadas.")


def fetch_reviews(client: GoogleClient, parent: str) -> tuple[list[dict[str, Any]], dict[str, Any]]:
    rows: list[dict[str, Any]] = []
    first: dict[str, Any] = {}
    token = None
    while True:
        params: dict[str, Any] = {"pageSize": 50}
        if token:
            params["pageToken"] = token
        data = client.get(f"{REVIEWS_URL}/{parent}/reviews", params)
        if not first:
            first = data
        rows += data.get("reviews", [])
        token = data.get("nextPageToken")
        if not token:
            return rows, first


def normalize(raw: list[dict[str, Any]]) -> list[dict[str, Any]]:
    out: list[dict[str, Any]] = []
    seen: set[str] = set()
    for row in raw:
        if STARS.get(row.get("starRating")) != 5:
            continue
        review_id = row.get("reviewId") or row.get("name") or ""
        if review_id in seen:
            continue
        seen.add(review_id)
        author = (row.get("reviewer") or {}).get("displayName", "").strip()
        if not author or author.lower() == "a google user":
            continue
        translation, original = split_comment(row.get("comment", ""))
        es = en = ""
        if translation:
            if useful(original) and detect_language(original) == "es":
                es = original
            if useful(translation):
                en = translation
        else:
            lang = detect_language(original)
            if useful(original):
                if lang == "es":
                    es = original
                else:
                    en = original
        if not es and not en:
            continue
        out.append({
            "id": review_id,
            "author": author,
            "rating": STARS[row["starRating"]],
            "date": (row.get("createTime") or "")[:10],
            "es": es,
            "en": en,
        })
    out.sort(key=lambda r: r["date"], reverse=True)
    return out


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--theme", type=Path, default=DEFAULT_THEME, help="ruta al tema eternalglow")
    parser.add_argument("--raw", type=Path, default=RAW_OUT, help="salida cruda (ignorada por git)")
    args = parser.parse_args()
    if not args.theme or not (args.theme / "includes").is_dir():
        print("ERROR: no encuentro el tema; pasa --theme", file=sys.stderr)
        return 1

    client = GoogleClient(get_credentials())
    parent = find_parent(client)
    meta = client.get(f"{INFO_URL}/{LOCATION}", {"readMask": "metadata"})
    place_id = (meta.get("metadata") or {}).get("placeId") or FALLBACK_PLACE_ID
    raw, first = fetch_reviews(client, parent)
    reviews = normalize(raw)

    score = float(first.get("averageRating") or 5)
    count = int(first.get("totalReviewCount") or len(raw))
    cache = {
        "updated": datetime.now(timezone.utc).isoformat(timespec="seconds"),
        "score": score,
        "count": count,
        "url": f"https://www.google.com/maps/place/?q=place_id:{place_id}",
        "reviews": reviews,
    }

    args.raw.parent.mkdir(parents=True, exist_ok=True)
    args.raw.write_text(json.dumps({
        "updated": cache["updated"],
        "averageRating": score,
        "totalReviewCount": count,
        "reviews": raw,
    }, ensure_ascii=False, indent=2), encoding="utf-8")

    target = args.theme / "includes" / "google-reviews.json"
    tmp = target.with_suffix(".json.tmp")
    tmp.write_text(json.dumps(cache, ensure_ascii=False, indent=2), encoding="utf-8")
    tmp.replace(target)

    es = sum(1 for r in reviews if r["es"])
    en = sum(1 for r in reviews if r["en"])
    print(f"{count} reseñas, {score} de promedio; {len(raw)} descargadas, {len(reviews)} útiles (ES {es} / EN {en}).")
    print(f"Escrito {target}")
    return 0


if __name__ == "__main__":
    try:
        sys.exit(main())
    except GoogleApiError as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        sys.exit(1)
