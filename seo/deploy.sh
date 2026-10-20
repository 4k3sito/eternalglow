#!/usr/bin/env bash
# Deploys the theme and runs the SEO scripts on production. Run from the repo root: bash seo/deploy.sh [--theme-only]
# --theme-only skips the seo/*.php content scripts: they rewrite post_content and would undo edits made in wp-admin.
# Used both by hand and by .github/workflows/deploy.yml, which defines the same SSH host alias.
# Old dist/app.*.css|js stay on the server: cached pages reference them until the purge.
set -euo pipefail

HOST=godaddy-eternalglow
THEMES=u355783479.eternalglow-com-mx.20260922005336/domains/eternalglow.com.mx/public_html/wp-content/themes
THEME_ONLY=false
# Reject anything else: a typo must not silently run the content scripts.
case "${1:-}" in "") ;; --theme-only) THEME_ONLY=true ;; *) echo "Uso: bash seo/deploy.sh [--theme-only]"; exit 2 ;; esac

[ -d "$THEMES/eternalglow" ] || { echo "Corre este script desde la raíz del repo"; exit 1; }

echo "0/4 Respaldo en ~/backups-deploy/..."
# eg_options too: seo/home.php writes rank-math-options-sitemap there.
# ponytail: keeps the last 10 deploy backups; move them off the server if older rollbacks are ever needed.
ssh "$HOST" 'set -e; umask 077; d=~/backups-deploy/$(date +%Y%m%d-%H%M%S)-deploy; mkdir -p "$d"; cd ~/public_html
	wp db export --tables=eg_posts,eg_postmeta,eg_options "$d/db.sql" >/dev/null
	tar -czf "$d/theme.tgz" -C wp-content/themes eternalglow
	echo "   $d"
	ls -1dt ~/backups-deploy/*-deploy | tail -n +11 | xargs -r rm -rf'

echo "1/4 Subiendo el tema..."
tar -czf - --exclude=node_modules -C "$THEMES" eternalglow | ssh "$HOST" 'tar -xzf - -C ~/public_html/wp-content/themes'

if [ "$THEME_ONLY" = true ]; then
	echo "2-3/4 Scripts de contenido omitidos (--theme-only)"
else
	# privacidad.php and contact-form-en.php are one-off scripts, run by hand: not part of every deploy.
	echo "2/4 Subiendo los scripts de SEO..."
	tar -czf - seo/home.php seo/treatments.php seo/sculptra-cdmx.php seo/prices.php seo/landings.php seo/nosotros.php seo/content-fixes.php | ssh "$HOST" 'mkdir -p ~/seo-deploy && tar -xzf - -C ~/seo-deploy'

	echo "3/4 Corriendo los scripts (el orden importa: prices.php va después de treatments.php y content-fixes.php al final, porque sculptra-cdmx.php reescribe el hub)..."
	# Each script exits 1 on a failed self-check: stop there instead of running the rest on top of it.
	ssh "$HOST" 'cd ~/public_html && for f in home treatments sculptra-cdmx prices landings nosotros content-fixes; do echo "== $f"; wp eval-file ~/seo-deploy/seo/$f.php || { echo "FALLO: $f"; exit 1; }; done'
fi

echo "4/4 Purgando cachés..."
# Page cache is WP Super Cache in mod_rewrite mode since 2026-10-16: the seo/*.php scripts write with $wpdb,
# which fires no save hooks, so its files must be deleted here or Apache keeps serving the old HTML.
# Each step runs even if the previous one fails (a stale page cache is the worst outcome); the step still fails at the end.
ssh "$HOST" 'cd ~/public_html || exit 1; rc=0
	wp cache flush || { echo "FALLO purga: wp cache flush"; rc=1; }
	wp eval "\\RankMath\\Sitemap\\Cache::invalidate_storage();" || { echo "FALLO purga: sitemap de Rank Math"; rc=1; }
	rm -rf wp-content/cache/supercache/eternalglow.com.mx || rc=1
	exit $rc'

echo "Verificando..."
failed=0
for path in / /en/ /en/botox-mexico-city/ /en/sculptra-mexico-city/ /en/fillers-mexico-city/ /en/lip-filler-mexico-city/ /botox-cdmx/ /hilos-tensores-cdmx/ /tratamientos/ /rellenos-cdmx/ /relleno-de-labios-cdmx/ /sculptra-cdmx/ /servicio/hollywood-peel-en-cdmx/ /servicio/tratamiento-sculptra-en-cdmx/ /servicio/tratamiento-pdrn-salmon/; do
	result=$(curl -s -o /dev/null -m 30 -w '%{http_code} %{redirect_url}' "https://eternalglow.com.mx$path" || true)
	printf '%s -> %s\n' "$path" "$result"
	case "${result%% *}" in 200|301) ;; *) failed=1 ;; esac
done
[ "$failed" = 0 ] || { echo "FALLO: alguna URL no respondió 200/301. Rollback: ver CLAUDE.md > Deploy."; exit 1; }
echo "Deploy OK"
