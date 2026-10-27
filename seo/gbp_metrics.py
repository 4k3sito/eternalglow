"""Baja las métricas del Perfil de Negocio de Google (GBP) a CSV.

Uso:
    seo/.venv/bin/python seo/gbp_metrics.py            # autoriza la 1a vez, luego reutiliza el token
    seo/.venv/bin/python seo/gbp_metrics.py --months 6

Credenciales: ver google_oauth.py (seo/.secrets/, ignorado por git).
Requiere que Google haya aprobado el proyecto para las APIs de GBP; si no, la cuota es 0 y responde 429.
Salida en seo/gbp-data/: daily_metrics.csv y search_keywords.csv
"""
from __future__ import annotations

import argparse
import csv
import sys
from datetime import date, timedelta
from pathlib import Path
from typing import Any

from google_oauth import GoogleApiError, GoogleClient, get_credentials

OUT_DIR = Path(__file__).resolve().parent / "gbp-data"

ACCOUNTS_URL = "https://mybusinessaccountmanagement.googleapis.com/v1/accounts"
INFO_URL = "https://mybusinessbusinessinformation.googleapis.com/v1"
PERF_URL = "https://businessprofileperformance.googleapis.com/v1"
# La cuenta administra otros perfiles (p. ej. TechMech); solo interesa Eternal Glow.
LOCATION = "locations/3792563091370806835"

DAILY_METRICS = [
    "BUSINESS_IMPRESSIONS_MOBILE_SEARCH",
    "BUSINESS_IMPRESSIONS_DESKTOP_SEARCH",
    "BUSINESS_IMPRESSIONS_MOBILE_MAPS",
    "BUSINESS_IMPRESSIONS_DESKTOP_MAPS",
    "CALL_CLICKS",
    "WEBSITE_CLICKS",
    "BUSINESS_DIRECTION_REQUESTS",
    "BUSINESS_CONVERSATIONS",
    "BUSINESS_BOOKINGS",
]


def date_params(prefix: str, d: date) -> list[tuple[str, int]]:
    return [(f"{prefix}.year", d.year), (f"{prefix}.month", d.month), (f"{prefix}.day", d.day)]


def fetch_daily(client: GoogleClient, location: str, start: date, end: date) -> list[dict[str, Any]]:
    params: list[tuple[str, Any]] = [("dailyMetrics", m) for m in DAILY_METRICS]
    params += date_params("dailyRange.startDate", start) + date_params("dailyRange.endDate", end)
    data = client.get(f"{PERF_URL}/{location}:fetchMultiDailyMetricsTimeSeries", params)

    # La API omite los días con valor 0, así que se parte de una tabla completa en ceros.
    days = [start + timedelta(n) for n in range((end - start).days + 1)]
    table: dict[date, dict[str, int]] = {d: dict.fromkeys(DAILY_METRICS, 0) for d in days}
    for multi in data.get("multiDailyMetricTimeSeries", []):
        for series in multi.get("dailyMetricTimeSeries", []):
            metric = series["dailyMetric"]
            for dv in series.get("timeSeries", {}).get("datedValues", []):
                d = date(dv["date"]["year"], dv["date"]["month"], dv["date"]["day"])
                if d in table:
                    table[d][metric] = int(dv.get("value", 0))
    return [{"date": d.isoformat(), **table[d]} for d in days]


def fetch_keywords(client: GoogleClient, location: str, start: date, end: date) -> list[dict[str, Any]]:
    params = {
        "monthlyRange.startMonth.year": start.year,
        "monthlyRange.startMonth.month": start.month,
        "monthlyRange.endMonth.year": end.year,
        "monthlyRange.endMonth.month": end.month,
        "pageSize": 100,
    }
    rows = []
    for item in client.paginate(f"{PERF_URL}/{location}/searchkeywords/impressions/monthly", "searchKeywordsCounts", params):
        value = item.get("insightsValue", {})
        # Google oculta los conteos bajos: devuelve "threshold" (p. ej. <15) en lugar de "value".
        rows.append({
            "keyword": item["searchKeyword"],
            "impressions": value.get("value", ""),
            "below_threshold": value.get("threshold", ""),
        })
    return rows


def write_csv(path: Path, rows: list[dict[str, Any]]) -> None:
    if not rows:
        return
    with path.open("w", newline="", encoding="utf-8") as fh:
        writer = csv.DictWriter(fh, fieldnames=list(rows[0]))
        writer.writeheader()
        writer.writerows(rows)


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--months", type=int, default=18, help="meses hacia atrás (máx. 18, lo que guarda Google)")
    args = parser.parse_args()

    end = date.today() - timedelta(days=2)  # los últimos ~2 días aún no están consolidados
    start = end - timedelta(days=30 * min(args.months, 18))

    client = GoogleClient(get_credentials())
    accounts = list(client.paginate(ACCOUNTS_URL, "accounts"))
    if not accounts:
        raise GoogleApiError("La cuenta autorizada no administra ningún Perfil de Negocio.")

    OUT_DIR.mkdir(exist_ok=True)
    daily_rows: list[dict[str, Any]] = []
    keyword_rows: list[dict[str, Any]] = []
    for account in accounts:
        locations = client.paginate(
            f"{INFO_URL}/{account['name']}/locations", "locations",
            {"readMask": "name,title,storefrontAddress,websiteUri", "pageSize": 100},
        )
        for loc in locations:
            if loc["name"] != LOCATION:
                continue
            print(f"- {loc.get('title')} ({loc['name']}) en {account.get('accountName')}")
            tag = {"location": loc.get("title", loc["name"])}
            daily_rows += [{**tag, **r} for r in fetch_daily(client, loc["name"], start, end)]
            keyword_rows += [{**tag, **r} for r in fetch_keywords(client, loc["name"], start, end)]

    write_csv(OUT_DIR / "daily_metrics.csv", daily_rows)
    write_csv(OUT_DIR / "search_keywords.csv", keyword_rows)
    print(f"{len(daily_rows)} filas diarias y {len(keyword_rows)} búsquedas ({start} → {end}) en {OUT_DIR}")
    return 0


if __name__ == "__main__":
    try:
        sys.exit(main())
    except GoogleApiError as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        sys.exit(1)
