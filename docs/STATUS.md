# Estado del proyecto
Actualizado: 2026-10-21

## Ahora
Atacando las tareas de la auditoría SEO del 2026-10-20 (`docs/reports/2026-10-20-auditoria-seo.md`, 68/100).

## Hecho recientemente
- Fuera Divi (2026-10-21, rama `feat/divi-to-html`, pendiente de deploy): `seo/divi-to-html.php` convierte las 27 páginas `/servicio/` (y 2 borradores) de shortcodes de Divi a HTML con clases `lp-sv-*`; mismo texto verificado palabra por palabra, de 100–200 KB a 9–30 KB de contenido. Probado en local con Divi desactivado. Tras el deploy: desactivar `divi-builder`, y después de unos días borrarlo junto con la licencia de `angloodigital`, el filtro `eg_drop_service_banner` y los estilos `#et-boc` de `_home.scss`.
- WP cron reactivado (2026-10-21): llevaba apagado desde marzo (`DISABLE_WP_CRON` sin cron del sistema), así que Wordfence, Rank Math y el resto de tareas programadas no corrían. Ahora un cron de cPanel llama a `wp-cron.php` cada 15 min. Antes se activó el modo de precarga de WP Super Cache (`seo/supercache-preload.php`, refresco diario) para que la limpieza no deje las páginas frías. TTFB de las `/servicio/`: 0.27 s (la auditoría medía 2.4–11 s). Respaldo de la config y del crontab en `~/backups-deploy/20261020-232708-cron/`.
- Cédula profesional del Dr. Ocampo (13493041) en el bloque del médico (home y 9 hubs ES/EN), en el schema Physician y en `seo/nosotros.php`. Verificada en el Registro Nacional de Profesionistas (SEP). Desplegada el 2026-10-21 (PR #11, run 39552153333).
- Crítica de diseño del sitio con referencias de Mobbin (2026-10-20). Cambios rápidos aplicados en código: `seo/design-fixes.php` (casilla de privacidad sin premarcar en los formularios ES/EN, foto con marca de agua fuera de la galería de Botox, calificación de Google bajo el CTA del hero de /botox-cdmx/), calificación en el hero de los hubs con hero propio, y precios de Botox en 3 grupos (ES/EN). Desplegada el 2026-10-21 con `run_content_scripts` (run 39552153333).
- Auditoría SEO completa (68/100); 12 tareas nuevas en Notion.
- Schema unificado en una sola entidad `#clinic` (PR #5): fuera el MedicalBusiness de SASWP con el teléfono viejo y las reseñas propias.
- Deploy por GitHub Actions (PR #3); primer deploy `--theme-only` OK el 2026-10-20, run 39391632264.
- Pipeline instalado (PR #1); `rediseno-editorial` mergeada a `main` (PR #2).
- 20 pendientes migrados de `tareas-eternalglow.md` a Notion.
- SEO: páginas en inglés de Sculptra, Botox, rellenos y relleno de labios; footer y formulario en inglés en `/en/`.

## Siguiente
- Resto de Altas de la auditoría: hub de Botox, reenfoque de Rellenos, médico visible en hubs.

## Decisiones
- `seo/deploy.sh` es la única receta de deploy; CI la reutiliza.
- Push a `main` despliega solo el tema; los scripts de contenido solo se corren a mano (pisan ediciones de wp-admin).

## Bloqueos
- Fotos antes/después y datos del aviso de privacidad dependen del cliente.
