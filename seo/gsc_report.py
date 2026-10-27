"""Reporte de Search Console para priorizar el trabajo de SEO.

Uso: seo/.venv/bin/python seo/gsc_report.py [--days 90]
Escribe seo/gsc-data/*.csv (ignorado por git) e imprime un resumen:
tendencia diaria, clusters por tratamiento, canibalización y búsquedas cerca de la página 1.
"""
from __future__ import annotations

import argparse
import csv
import re
import sys
from collections import defaultdict
from datetime import date, timedelta
from pathlib import Path
from typing import Any
from urllib.parse import quote

from google_oauth import GoogleApiError, GoogleClient, get_credentials

# La propiedad de dominio se creó en oct-2026; la de prefijo https tiene el historial desde julio.
DEFAULT_SITE = "https://eternalglow.com.mx/"
API_ROOT = "https://www.googleapis.com/webmasters/v3/sites"
API = ""  # se fija en main() según --site
OUT_DIR = Path(__file__).resolve().parent / "gsc-data"
REDESIGN = date(2026, 10, 13)

# Orden importa: la primera que coincide gana ("botox" antes que "labios" para que "botox labios" sea Botox).
CLUSTERS = [
    ("botox", re.compile(r"botox|toxina|b[oó]tox|baby botox|traptox|lip flip|foxy", re.I)),
    ("sculptra", re.compile(r"sculptra|escultra|sculptura", re.I)),
    ("rellenos", re.compile(r"relleno|filler|[aá]cido hialur|hialur[oó]nico|labios|ojeras|rinomodel|mandib|ment[oó]n", re.I)),
    ("hilos", re.compile(r"hilos?|pdo|threads?", re.I)),
    ("marca", re.compile(r"eternal|glow", re.I)),
]


def cluster_of(query: str) -> str:
    return next((name for name, rx in CLUSTERS if rx.search(query)), "otros")


def fetch(c: GoogleClient, start: date, end: date, dims: list[str]) -> list[dict[str, Any]]:
    rows, offset = [], 0
    while True:
        data = c.post(f"{API}/searchAnalytics/query", {
            "startDate": start.isoformat(), "endDate": end.isoformat(), "dimensions": dims,
            "rowLimit": 25000, "startRow": offset, "dataState": "all",
        })
        batch = data.get("rows", [])
        rows += [{**dict(zip(dims, r["keys"])), "clicks": r["clicks"], "impressions": r["impressions"],
                  "ctr": r["ctr"], "position": r["position"]} for r in batch]
        if len(batch) < 25000:
            return rows
        offset += 25000


def write_csv(name: str, rows: list[dict[str, Any]]) -> None:
    if rows:
        with (OUT_DIR / name).open("w", newline="", encoding="utf-8") as fh:
            w = csv.DictWriter(fh, fieldnames=list(rows[0]))
            w.writeheader()
            w.writerows(rows)


def short(url: str) -> str:
    return url.replace("https://eternalglow.com.mx", "") or "/"


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--days", type=int, default=90)
    ap.add_argument("--site", default=DEFAULT_SITE)
    args = ap.parse_args()
    global API
    API = f"{API_ROOT}/{quote(args.site, safe='')}"
    end = date.today() - timedelta(days=1)
    start = end - timedelta(days=args.days)
    c = GoogleClient(get_credentials())
    OUT_DIR.mkdir(exist_ok=True)

    daily = fetch(c, start, end, ["date"])
    qp = fetch(c, start, end, ["query", "page"])
    write_csv("daily.csv", daily)
    write_csv("query_page.csv", qp)
    last = max((r["date"] for r in daily), default="?")
    print(f"Periodo {start} → {end} (último día con datos: {last})\n")

    print("## Semanas (clics / impresiones / posición media ponderada)")
    weeks: dict[str, list[float]] = defaultdict(lambda: [0, 0, 0])
    for r in daily:
        d = date.fromisoformat(r["date"])
        wk = (d - timedelta(days=d.weekday())).isoformat()
        w = weeks[wk]
        w[0] += r["clicks"]; w[1] += r["impressions"]; w[2] += r["position"] * r["impressions"]
    for wk, (cl, im, pw) in sorted(weeks.items())[-14:]:
        mark = "  <- rediseño" if date.fromisoformat(wk) <= REDESIGN < date.fromisoformat(wk) + timedelta(days=7) else ""
        print(f"  {wk}: {cl:>4.0f} / {im:>6.0f} / {pw / im if im else 0:>5.1f}{mark}")

    # Agregados por query (sumando páginas) y por cluster.
    by_q: dict[str, dict[str, Any]] = {}
    pages_by_q: dict[str, list[tuple[str, float, float]]] = defaultdict(list)
    for r in qp:
        q = by_q.setdefault(r["query"], {"query": r["query"], "clicks": 0, "impressions": 0, "pw": 0.0})
        q["clicks"] += r["clicks"]; q["impressions"] += r["impressions"]; q["pw"] += r["position"] * r["impressions"]
        pages_by_q[r["query"]].append((short(r["page"]), r["impressions"], r["position"]))
    for q in by_q.values():
        q["position"] = q.pop("pw") / q["impressions"]
        q["cluster"] = cluster_of(q["query"])

    print("\n## Clusters (sin marca): clics / impresiones / CTR / posición")
    cl_tot: dict[str, list[float]] = defaultdict(lambda: [0, 0, 0])
    for q in by_q.values():
        t = cl_tot[q["cluster"]]
        t[0] += q["clicks"]; t[1] += q["impressions"]; t[2] += q["position"] * q["impressions"]
    for name, (cl, im, pw) in sorted(cl_tot.items(), key=lambda x: -x[1][1]):
        print(f"  {name:<9} {cl:>5.0f} / {im:>7.0f} / {cl / im * 100 if im else 0:>5.2f}% / {pw / im if im else 0:>5.1f}")

    focus = [q for q in by_q.values() if q["cluster"] in ("botox", "sculptra", "rellenos")]
    print("\n## Top 25 búsquedas de Botox, Sculptra y rellenos por impresiones")
    for q in sorted(focus, key=lambda x: -x["impressions"])[:25]:
        pages = sorted(pages_by_q[q["query"]], key=lambda p: -p[1])
        main_page = pages[0][0]
        print(f"  {q['query'][:42]:<42} {q['impressions']:>5} imp {q['clicks']:>3} cl pos {q['position']:>5.1f}  "
              f"{len(pages)} pág → {main_page}")

    print("\n## Canibalización: búsquedas foco (≥30 imp) donde 2+ páginas reciben ≥15% de las impresiones")
    cann = []
    for q in focus:
        if q["impressions"] < 30:
            continue
        sig = [p for p in pages_by_q[q["query"]] if p[1] >= 0.15 * q["impressions"]]
        if len(sig) >= 2:
            cann.append((q, sig))
    for q, sig in sorted(cann, key=lambda x: -x[0]["impressions"])[:15]:
        desc = ", ".join(f"{p} ({i}, pos {pos:.0f})" for p, i, pos in sorted(sig, key=lambda s: -s[1]))
        print(f"  {q['query'][:40]:<40} {desc}")

    print("\n## Cerca de la página 1: posición 8–20, ≥40 impresiones (todas las búsquedas sin marca)")
    near = [q for q in by_q.values() if 8 <= q["position"] <= 20 and q["impressions"] >= 40 and q["cluster"] != "marca"]
    for q in sorted(near, key=lambda x: -x["impressions"])[:20]:
        main_page = max(pages_by_q[q["query"]], key=lambda p: p[1])[0]
        print(f"  {q['query'][:42]:<42} {q['impressions']:>5} imp pos {q['position']:>5.1f} → {main_page}")

    print("\n## Páginas: top 20 por impresiones")
    by_p: dict[str, list[float]] = defaultdict(lambda: [0, 0, 0])
    for r in qp:
        t = by_p[short(r["page"])]
        t[0] += r["clicks"]; t[1] += r["impressions"]; t[2] += r["position"] * r["impressions"]
    for p, (cl, im, pw) in sorted(by_p.items(), key=lambda x: -x[1][1])[:20]:
        print(f"  {p[:55]:<55} {im:>6.0f} imp {cl:>4.0f} cl pos {pw / im:>5.1f}")

    write_csv("queries.csv", sorted(by_q.values(), key=lambda x: -x["impressions"]))
    return 0


if __name__ == "__main__":
    try:
        sys.exit(main())
    except GoogleApiError as exc:
        print(f"ERROR: {exc}", file=sys.stderr)
        sys.exit(1)
