// Crawls the site and writes every tag, class, id and attribute name/value found in the rendered
// HTML to purge-tokens.txt. PurgeCSS reads that file because WPBakery and Contact Form 7 content lives
// in the database, so its markup (e.g. class "p-md-40", <fieldset>, type="email") is in no theme file.
// Usage: npm run collect-tokens [-- baseUrl]   (default: https://eternalglow.com.mx)
import { writeFileSync } from 'node:fs';

const base = new URL(process.argv[2] ?? 'https://eternalglow.com.mx');
const MAX_PAGES = 300; // ponytail: hard cap, raise it if the site ever grows past this
const skip = /\.(jpe?g|png|gif|webp|svg|pdf|xml|css|js|ico|mp4)$|\/wp-(json|admin|content|includes)\/|\/feed\/|[?#]/i;
const token = /^[A-Za-z][\w-]{0,60}$/;

const queue = [base.href];
const seen = new Set(queue);
const tokens = new Set();
const add = s => { for (const t of s.split(/\s+/)) if (token.test(t)) tokens.add(t); };

while (queue.length && seen.size <= MAX_PAGES) {
  const url = queue.shift();
  const res = await fetch(url, { headers: { 'User-Agent': 'eternalglow-purge-crawler' } });
  if (!res.ok || !res.headers.get('content-type')?.includes('text/html')) {
    console.warn(`skip ${res.status} ${url}`);
    continue;
  }
  const html = (await res.text()).replace(/<script[\s\S]*?<\/script>/gi, '');
  for (const [, tag, attrs] of html.matchAll(/<([a-z][\w-]*)([^>]*)>/gi)) {
    tokens.add(tag.toLowerCase());
    for (const [, name, , , value = ''] of attrs.matchAll(/([\w:-]+)(\s*=\s*(["'])(.*?)\3)?/g)) {
      add(name);
      if (value.length < 200) add(value);
    }
  }
  for (const [, href] of html.matchAll(/href\s*=\s*["']([^"']+)["']/gi)) {
    let next;
    try { next = new URL(href, url); } catch { continue; }
    next.hash = '';
    if (next.host !== base.host || skip.test(next.pathname + next.search) || seen.has(next.href)) continue;
    seen.add(next.href);
    queue.push(next.href);
  }
  await new Promise(r => setTimeout(r, 300)); // polite pacing, shared hosting
}

if (tokens.size < 200) throw new Error(`Only ${tokens.size} tokens found at ${base.href}; refusing to write an unsafe safelist.`);
writeFileSync(new URL('../purge-tokens.txt', import.meta.url), [...tokens].sort().join('\n') + '\n');
console.log(`${seen.size} URLs, ${tokens.size} tokens -> purge-tokens.txt`);
