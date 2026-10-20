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
| Lint | `php -l` sobre `seo/` y el tema, `shellcheck seo/deploy.sh` (lo corre CI); no hay linter de JS/SCSS |
| Tests | TODO (no hay tests; los `seo/*.php` tienen self-checks que salen con 1 si fallan) |
| Build | `cd $THEME && npm run collect-tokens && npm run build-prod` |

## Deploy
Receta única: `seo/deploy.sh` (respaldo → tema → scripts de contenido → purga de cachés → calentar la caché con las URLs del sitemap → verificación de URLs). La usan igual GitHub Actions y la terminal. Spec: `docs/specs/deploy.md`.

- **Destino**: GoDaddy cPanel por SSH (alias `godaddy-eternalglow`), WordPress en `~/public_html`.
- **Automático** (`DEPLOY_MODE=script` en GitHub): cada merge a `main` que toque el tema o `seo/deploy.sh` corre `seo/deploy.sh --theme-only`, después de que lo apruebes en el environment `production`.
- **Contenido** (`seo/*.php`): nunca es automático, porque reescribe `post_content` y pisa lo editado en wp-admin. Se lanza en Actions > Deploy > Run workflow con la casilla `run_content_scripts`, o a mano con `bash seo/deploy.sh`.
- **Respaldo**: automático en el paso 0 a `~/backups-deploy/<timestamp>-deploy/` (`db.sql` con `eg_posts`, `eg_postmeta` y `eg_options`, más `theme.tgz`, con permisos 600). Se conservan los últimos 10: si reintentas muchas veces sobre un estado roto, el último respaldo bueno puede salir de la lista.
- **Rollback**: primero ubica el respaldo con `ssh godaddy-eternalglow 'ls -1t ~/backups-deploy'` y pon su nombre en `B`.
  - Solo tema (el caso normal, sirve tras `--theme-only`). Aparta el tema actual para que no queden plantillas nuevas: `ssh godaddy-eternalglow 'B=20261020-120000-deploy; cd ~/public_html/wp-content/themes && mv eternalglow eternalglow.bad-$(date +%s) && tar -xzf ~/backups-deploy/$B/theme.tgz && cd ~/public_html && wp cache flush; rm -rf wp-content/cache/supercache/eternalglow.com.mx'`
  - Tema y base: solo si corrieron los scripts de contenido. **Borra todo lo editado en wp-admin desde el respaldo**, incluidas las opciones de plugins. Antes del comando anterior: `ssh godaddy-eternalglow 'B=20261020-120000-deploy; cd ~/public_html && wp db import ~/backups-deploy/$B/db.sql'`
- **Falla el deploy** si un script de contenido falla su self-check o si alguna URL de la verificación no responde 200/301.

### Flujo en el pipeline (`/ship`)
1. PR → CI verde → revisión → tu "sí" → merge a `main`.
2. Se dispara el workflow Deploy, que espera tu aprobación en GitHub (checkpoint 3). Apruebas y queda en verde.
3. Si el cambio es de contenido: Run workflow con `run_content_scripts`.
4. Tarea de Notion → Hecha / Deploy con el link del run. Si el deploy falla → Bloqueada y rollback.

## Notion
- **Base de tareas**: https://app.notion.com/p/d9f8b1aad67b4d4b8e1a3c331a494cd0
- **Data source**: `collection://40bc9cf7-510e-4f81-aa9f-a59bd0904aae`

## Convenciones del repo
- El repo es un respaldo completo del hosting (`u355783479.../public_html`); solo se edita el tema, `seo/` y `local-env/`.
- Cambios de contenido: script en `seo/*.php` con `$wpdb->update` (no `wp_update_post`: kses rompe shortcodes) y self-check al final.
- Al agregar un script a `seo/`, sumarlo a `seo/deploy.sh` respetando el orden: prices después de treatments y content-fixes al final, porque `sculptra-cdmx.php` reescribe el hub. `contact-form-en.php` y `privacidad.php` existen pero no están en `deploy.sh`.
- `dist/` se versiona; al recompilar, dejar los `app.*.css/js` viejos en el servidor.

## No tocar
- `.env`, `seo/.secrets/`, `*.sql`, `wp-config.php` (ignorados; nunca commitear).
- Core de WordPress y plugins dentro del respaldo; `wp-content/uploads/` salvo que la tarea lo pida.
- `node_modules/`, cachés de WP.
