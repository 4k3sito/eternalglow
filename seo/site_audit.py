"""Auditoría on-page de las URLs del sitemap, cruzada con la inspección de URL de Search Console.

Uso: seo/.venv/bin/python seo/site_audit.py [--no-gsc]
Escribe seo/gsc-data/audit.csv e imprime los problemas por URL.
"""
from __future__ import annotations

import argparse
import csv
import json
import re
import sys
from collections import Counter
from html import unescape
from pathlib import Path

import requests

SITE = "https://eternalglow.com.mx"
OUT = Path(__file__).resolve().parent / "gsc-data" / "audit.csv"
UA = {"User-Agent": "Mozilla/5.0 (compatible; eg-audit/1.0)"}


def sitemap_urls() -> list[str]:
    idx = requests.get(f"{SITE}/sitemap_index.xml", headers=UA, timeout=30).text
    urls: list[str] = []
    for sm in re.findall(r"<loc>([^<]+)</loc>", idx):
        urls += re.findall(r"<loc>([^<]+)</loc>", requests.get(sm, headers=UA, timeout=30).text)
    return [u for u in urls if not re.search(r"\.(jpe?g|png|webp|gif)$", u)]


def text_of(html: str) -> str:
    body = re.sub(r"(?is)<(script|style|noscript|header|footer|nav)[^>]*>.*?</\1>", " ", html)
    return unescape(re.sub(r"<[^>]+>", " ", body))


def audit(url: str) -> dict[str, object]:
    r = requests.get(url, headers=UA, timeout=30, allow_redirects=False)
    h = r.text
    first = lambda rx: (m.group(1).strip() if (m := re.search(rx, h, re.I | re.S)) else "")  # noqa: E731
    title = unescape(first(r"<title>(.*?)</title>"))
    desc = unescape(first(r'<meta name="description" content="([^"]*)"'))
    canon = first(r'<link rel="canonical" href="([^"]*)"')
    robots = first(r'<meta name="robots" content="([^"]*)"')
    h1s = [re.sub(r"<[^>]+>", "", x).strip() for x in re.findall(r"(?is)<h1[^>]*>(.*?)</h1>", h)]
    imgs = re.findall(r"(?is)<img\b[^>]*>", h)
    no_alt = [i for i in imgs if not re.search(r'\balt="[^"]+', i)]
    hreflang = re.findall(r'hreflang="([^"]+)"\s+href', h)
    types: set[str] = set()
    for block in re.findall(r'(?is)<script type="application/ld\+json"[^>]*>(.*?)</script>', h):
        try:
            types |= set(re.findall(r'"@type"\s*:\s*"([^"]+)"', json.dumps(json.loads(block))))
        except json.JSONDecodeError:
            types.add("JSON-LD-INVÁLIDO")
    internal = {re.sub(r"[#?].*", "", u) for u in re.findall(r'href="(https://eternalglow\.com\.mx/[^"]*)"', h)}
    return {
        "url": url.replace(SITE, ""), "status": r.status_code, "ttfb": round(r.elapsed.total_seconds(), 2),
        "title": title, "title_len": len(title), "desc_len": len(desc), "canonical_ok": canon == url,
        "robots": robots, "h1": len(h1s), "h1_text": " | ".join(h1s)[:80], "words": len(text_of(h).split()),
        "imgs": len(imgs), "imgs_no_alt": len(no_alt), "hreflang": ",".join(hreflang),
        "schema": ",".join(sorted(types)), "internal_links": len(internal),
    }


def problems(row: dict[str, object]) -> list[str]:
    p = []
    if row["status"] != 200: p.append(f"status {row['status']}")
    if not 30 <= int(row["title_len"]) <= 60: p.append(f"title {row['title_len']}c")
    if not 70 <= int(row["desc_len"]) <= 160: p.append(f"description {row['desc_len']}c")
    if not row["canonical_ok"]: p.append("canonical ≠ URL")
    if "noindex" in str(row["robots"]): p.append("noindex")
    if row["h1"] != 1: p.append(f"{row['h1']} H1")
    if int(row["words"]) < 300: p.append(f"{row['words']} palabras")
    if int(row["imgs_no_alt"]): p.append(f"{row['imgs_no_alt']}/{row['imgs']} img sin alt")
    if "JSON-LD-INVÁLIDO" in str(row["schema"]): p.append("JSON-LD inválido")
    return p


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--no-gsc", action="store_true")
    args = ap.parse_args()
    urls = sitemap_urls()
    rows = [audit(u) for u in urls]

    if not args.no_gsc:
        from google_oauth import GoogleClient, get_credentials
        c = GoogleClient(get_credentials())
        for row in rows:
            res = c.post("https://searchconsole.googleapis.com/v1/urlInspection/index:inspect",
                         {"inspectionUrl": SITE + str(row["url"]), "siteUrl": SITE + "/", "languageCode": "es"})
            st = res["inspectionResult"]["indexStatusResult"]
            row["gsc"] = st.get("coverageState", "")
            row["google_canonical_ok"] = st.get("googleCanonical", SITE + str(row["url"])) == SITE + str(row["url"])
            row["last_crawl"] = st.get("lastCrawlTime", "")[:10]

    OUT.parent.mkdir(exist_ok=True)
    with OUT.open("w", newline="", encoding="utf-8") as fh:
        w = csv.DictWriter(fh, fieldnames=list(rows[0]))
        w.writeheader(); w.writerows(rows)

    titles = Counter(r["title"] for r in rows)
    print(f"{len(rows)} URLs del sitemap\n")
    for row in rows:
        p = problems(row)
        if titles[row["title"]] > 1: p.append("title duplicado")
        if row.get("gsc") and "indexada" not in str(row["gsc"]).lower(): p.append(f"GSC: {row['gsc']}")
        if row.get("google_canonical_ok") is False: p.append("Google eligió otro canonical")
        print(f"{'OK ' if not p else '!! '}{row['url']:<55} {row['words']:>5}p ttfb {row['ttfb']}s  {'; '.join(p)}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
