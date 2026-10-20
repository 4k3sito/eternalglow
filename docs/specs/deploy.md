# Deploy en el pipeline

- **Estado**: implementado; primer deploy `--theme-only` OK el 2026-10-20
- **Fecha**: 2026-10-20
- **Tareas en Notion**: https://app.notion.com/p/d9f8b1aad67b4d4b8e1a3c331a494cd0

## Objetivo
Que llegar a `main` termine en producción con un paso de aprobación, sin una receta duplicada y sin riesgo de pisar contenido editado en wp-admin.

## Fuera de alcance
- Deploy de plugins, core de WordPress o `uploads/`.
- Staging: no existe un entorno intermedio en GoDaddy.

## Enfoque
- **Una sola receta**: `seo/deploy.sh`. GitHub Actions arma el mismo alias SSH (`godaddy-eternalglow`) y la corre tal cual. Así, manual y CI no se separan con el tiempo.
- **Tema y contenido van separados.** Los `seo/*.php` reescriben `post_content` con `$wpdb`, así que repetirlos en cada push borraría lo que la clínica edita en wp-admin. Push a `main` → `--theme-only`; el contenido solo corre con `workflow_dispatch` y la casilla marcada.
- **Aprobación humana**: el environment `production` tiene Required reviewers y solo acepta la rama `main`.
- **Respaldo antes de tocar nada** (tablas `eg_posts`, `eg_postmeta` y `eg_options`, y el tema). Se conservan los últimos 10 en el servidor.
- **El deploy falla** si un self-check falla o si alguna URL clave no responde 200/301.
- **Después de purgar, calienta la caché**: pide el sitemap y cada una de sus URLs, una a la vez (~1.5 min para 40 URLs). Sin esto, las primeras visitas, Googlebot incluido, esperan 1–2 s, y un sitemap frío llegó a tardar 90 s.
- **SSH**: llave dedicada a CI y `known_hosts` fijo en un secret (sin `ssh-keyscan`, para no aceptar a ciegas la clave del servidor).

## Criterios de aceptación
- [x] Con `DEPLOY_MODE` vacío, el workflow se salta.
- [ ] Un push a `main` que toca el tema espera aprobación y luego corre `--theme-only` en verde.
- [ ] Run workflow con `run_content_scripts` corre los 7 scripts en orden.
- [x] Existe `~/backups-deploy/<timestamp>-deploy/` con `db.sql` (eg_posts, eg_postmeta, eg_options) y `theme.tgz` después de cada deploy.
- [x] Un argumento distinto de `--theme-only` aborta sin tocar nada.
- [ ] Una URL caída hace fallar el job.

## Riesgos y dudas abiertas
- GoDaddy podría limitar SSH desde las IPs de GitHub. Si el primer deploy no conecta, revisar cPanel > SSH Access.
- Cada respaldo ocupa unos 11 MB (7.5 MB de base y 3 MB de tema), así que 10 copias son unos 110 MB.
- El CI compila el tema pero no comprueba que el `dist/` versionado esté al día: se despliega el `dist/` del commit.
- El tema se sube sin `--delete` (hay que conservar los `app.*.css/js` viejos), así que un archivo borrado del repo sigue en el servidor. El rollback de tema sí aparta la carpeta completa.
- Los hooks de Rank Math que disparan los scripts pueden escribir en tablas `eg_rank_math_*`, que el respaldo no incluye.
- La verificación acepta cualquier 301, aunque redirija a otra página.
