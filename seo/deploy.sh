#!/usr/bin/env bash
# Deploys the theme and runs the SEO scripts on production. Run from the repo root: bash seo/deploy.sh
# Take a backup first (wp db export + tar of the theme into ~/backups-deploy/<timestamp>/).
# Old dist/app.*.css|js stay on the server: pages in the WP Rocket cache reference them until the purge.
set -euo pipefail

HOST=godaddy-eternalglow
THEMES=u355783479.eternalglow-com-mx.20260922005336/domains/eternalglow.com.mx/public_html/wp-content/themes

[ -d "$THEMES/eternalglow" ] || { echo "Corre este script desde la raíz del repo"; exit 1; }

echo "1/4 Subiendo el tema..."
tar -czf - --exclude=node_modules -C "$THEMES" eternalglow | ssh "$HOST" 'tar -xzf - -C ~/public_html/wp-content/themes'

echo "2/4 Subiendo los scripts de SEO..."
tar -czf - seo/home.php seo/treatments.php seo/sculptra-cdmx.php seo/prices.php seo/landings.php seo/nosotros.php seo/content-fixes.php | ssh "$HOST" 'mkdir -p ~/seo-deploy && tar -xzf - -C ~/seo-deploy'

echo "3/4 Corriendo los scripts (el orden importa: prices.php va después de treatments.php y content-fixes.php al final, porque sculptra-cdmx.php reescribe el hub)..."
# Each script exits 1 on a failed self-check: stop there instead of running the rest on top of it.
ssh "$HOST" 'cd ~/public_html && for f in home treatments sculptra-cdmx prices landings nosotros content-fixes; do echo "== $f"; wp eval-file ~/seo-deploy/seo/$f.php 2>/dev/null || { echo "FALLO: $f"; exit 1; }; done'

echo "4/4 Purgando cachés..."
# WP Rocket was removed from production on 2026-10-15; only purge it if it comes back.
ssh "$HOST" 'cd ~/public_html && wp cache flush 2>/dev/null && wp eval "if (function_exists(\"rocket_clean_domain\")) { rocket_clean_domain(); rocket_clean_minify(); }" 2>/dev/null'

echo "Verificando..."
for path in / /en/ /botox-cdmx/ /hilos-tensores-cdmx/ /tratamientos/ /rellenos-cdmx/ /relleno-de-labios-cdmx/ /sculptra-cdmx/ /servicio/hollywood-peel-en-cdmx/ /servicio/tratamiento-sculptra-en-cdmx/ /servicio/tratamiento-pdrn-salmon/; do
	printf '%s -> ' "$path"
	curl -s -o /dev/null -m 30 -w '%{http_code} %{redirect_url}\n' "https://eternalglow.com.mx$path"
done
