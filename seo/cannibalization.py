"""Search queries that split their impressions between several pages, read from the CSVs of seo/gsc_report.py.

Uso: python3 seo/cannibalization.py [--min 50] [--share 0.15]
Run gsc_report.py first so seo/gsc-data/ holds the period you want to compare.
"""
import argparse
import collections
import csv
from pathlib import Path

DATA = Path(__file__).parent / "gsc-data"
SITE = "https://eternalglow.com.mx"


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--min", type=int, default=50, help="impresiones mínimas de la búsqueda")
    ap.add_argument("--share", type=float, default=0.15, help="parte mínima de impresiones por página")
    args = ap.parse_args()

    by_query = collections.defaultdict(list)
    for r in csv.DictReader(open(DATA / "query_page.csv", encoding="utf-8")):
        page = r["page"].replace(SITE, "") or "/"
        by_query[r["query"]].append((page, int(r["clicks"]), int(r["impressions"]), float(r["position"])))
    cluster = {r["query"]: r["cluster"] for r in csv.DictReader(open(DATA / "queries.csv", encoding="utf-8"))}

    # Impressions per cluster and page: which page Google treats as the answer for each topic.
    pages = collections.defaultdict(collections.Counter)
    clicks = collections.defaultdict(collections.Counter)
    for query, rows in by_query.items():
        for page, cl, im, _ in rows:
            pages[cluster.get(query, "?")][page] += im
            clicks[cluster.get(query, "?")][page] += cl
    print("== Impresiones por tema y página")
    for name, counter in sorted(pages.items(), key=lambda x: -sum(x[1].values())):
        total = sum(counter.values())
        print(f"\n[{name}] {total} impresiones, {sum(clicks[name].values())} clics")
        for page, im in counter.most_common(6):
            print(f"  {im * 100 // total:3d}%  {im:6d} impr {clicks[name][page]:4d} clics  {page}")

    # Ignores the brand: sitelinks put several pages on "eternal glow" by design.
    found = []
    for query, rows in by_query.items():
        total = sum(r[2] for r in rows)
        big = sorted((r for r in rows if r[2] >= args.share * total), key=lambda r: -r[2])
        if total >= args.min and len(big) >= 2 and cluster.get(query) != "marca":
            found.append((total, query, big))
    print(f"\n== Búsquedas repartidas entre 2 o más páginas (>= {args.min} impr, >= {args.share:.0%} cada una)")
    for total, query, big in sorted(found, reverse=True):
        print(f"{total:5d}  {query} [{cluster.get(query, '?')}]")
        for page, cl, im, pos in big:
            print(f"         {im:5d} impr {cl:3d} clics pos {pos:5.1f}  {page}")
    print(f"\n{len(found)} búsquedas")


if __name__ == "__main__":
    main()
