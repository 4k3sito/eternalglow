# eternalglow

> Reglas globales del pipeline: se cargan desde `~/.claude/CLAUDE.md`. Este archivo las complementa para este repo; si hay conflicto, gana este.

## Proyecto
- **Qué es**: sitio WordPress de la clínica estética Eternal Glow (https://eternalglow.com.mx) y su trabajo de SEO local/inglés (hubs Sculptra, Botox, rellenos; páginas `/en/`).
- **Tipo**: web/app + SEO/contenido

## Idioma
Por defecto el global (español para reportes, tareas y docs; inglés para código y commits). Cambios para este repo: ninguno.

## Stack
- WordPress (PHP 8.3, MariaDB, tablas con prefijo `eg_`), WPBakery, Rank Math, CF7.
- Tema propio `eternalglow`: SCSS + TypeScript con webpack 5, PostCSS (PurgeCSS contra `purge-tokens.txt`), Bootstrap 5, jQuery.
- Contenido SEO como scripts `seo/*.php` que se corren con `wp eval-file` (escriben con `$wpdb`).
- Scripts Python en `seo/` para GSC, GA4, GTM y Google Business (`google_oauth.py`, credenciales en `seo/.secrets/` y `.env`).
- Local: Docker (`eg-db` mariadb en :3307, `eg-web` en :8080).

## Comandos
`$THEME` = `u355783479.eternalglow-com-mx.20260922005336/domains/eternalglow.com.mx/public_html/wp-content/themes/eternalglow`

| Acción | Comando |
|---|---|
| Instalar | `cd $THEME && npm ci` |
| Desarrollo | `docker start eg-db eg-web` → http://localhost:8080 · `cd $THEME && npm run watch` |
| Lint | TODO (no hay linter configurado) |
| Tests | TODO (no hay tests; los `seo/*.php` tienen self-checks que salen con 1 si fallan) |
| Build | `cd $THEME && npm run collect-tokens && npm run build-prod` |

## Deploy
- **Modo**: manual con `bash seo/deploy.sh` (tar por SSH a `godaddy-eternalglow` + WP-CLI + purga de cachés). En GitHub `DEPLOY_MODE` = `disabled` hasta decidir; si se activa, `rsync` (ver `.github/workflows/deploy.yml`), que solo sube el tema.
- **Destino**: GoDaddy cPanel, `~/public_html` (WordPress), tema en `~/public_html/wp-content/themes/eternalglow`.
- **Rollback**: respaldo previo en `~/backups-deploy/<timestamp>/` (`wp db export --tables=eg_posts,eg_postmeta` + tar de lo que se reemplaza); restaurar desde ahí.
- El deploy lo dispara `.github/workflows/deploy.yml` al llegar a `main` y requiere aprobación del environment `production`.

## Notion
- **Base de tareas**: https://app.notion.com/p/d9f8b1aad67b4d4b8e1a3c331a494cd0
- **Data source**: `collection://40bc9cf7-510e-4f81-aa9f-a59bd0904aae`

## Convenciones del repo
- El repo es un respaldo completo del hosting (`u355783479.../public_html`); solo se edita el tema, `seo/` y `local-env/`.
- Cambios de contenido: script en `seo/*.php` con `$wpdb->update` (no `wp_update_post`: kses rompe shortcodes) y self-check al final.
- Al agregar un script a `seo/`, sumarlo a `seo/deploy.sh` respetando el orden (prices después de treatments, content-fixes al final).
- `dist/` se versiona; al recompilar, dejar los `app.*.css/js` viejos en el servidor.

## No tocar
- `.env`, `seo/.secrets/`, `*.sql`, `wp-config.php` (ignorados; nunca commitear).
- Core de WordPress y plugins dentro del respaldo; `wp-content/uploads/` salvo que la tarea lo pida.
- `node_modules/`, cachés de WP.
