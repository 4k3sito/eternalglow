# Estado del proyecto
Actualizado: 2026-10-22

## Ahora
Atacando las tareas de la auditoría SEO del 2026-10-20 (`docs/reports/2026-10-20-auditoria-seo.md`, 68/100). Altas cerradas (el nombre del perfil de Google Business ya es "Eternal Glow", verificado por API el 2026-10-23); siguen las Medias.

## Hecho recientemente
- Títulos de Botox en los labios y Armonización facial (2026-10-27, `seo/prices.php`, ya corrido en producción): el de labios estaba en posición 2.1 para "botox en los labios" con CTR de 0.8%; el de armonización ahora lleva "ácido hialurónico", su búsqueda principal. Medir el CTR en el reporte del 3 al 10 nov.
- HSTS subido a `max-age=31536000` en producción (2026-10-27, dos días antes de lo previsto, a pedido del usuario; HTTPS revisado: redirecciones http/www a https y sin contenido mixto). Respaldo en `~/backups-deploy/20261027-121559-hsts/`.
- API de Google Business aprobada (2026-10-23): `seo/gbp_metrics.py` (métricas a `seo/gbp-data/`) y `seo/gbp_profile.py` (alinea el perfil con `docs/directorios.md`). Perfil corregido: teléfono y WhatsApp viejos fuera, WhatsApp como chat principal, citas a AgendaPro, sitio con `utm_campaign=gbp`, 26 servicios con precio; respaldo en `~/backups-deploy/20261023-204955-gbp/`. Lectura preliminar del rediseño: `docs/reports/2026-10-23-impacto-rediseno.md` (GSC +63% clics, posición 9.0 → 7.5; GA4 no comparable por el cambio de medición del 16 oct).
- Respuestas rápidas bajo el H1 de Full Face Botox y Botox por unidad (2026-10-22, PR #24, run 39817466657), con el mismo bloque de Hollywood Peel (`includes/_service-answers.php`): las dos estaban en el top 6 de "botox full face que incluye" y "precio de botox por unidad" con casi ningún clic. Medir en el reporte del 3 al 10 nov.
- Duración del Botox unificada en 3 a 5 meses, como en `/botox-cdmx/` (decisión del usuario, 2026-10-22, PR #24 y #25): `seo/content-fixes.php` corrigió el FAQ visible y el schema FAQ de SASWP (`saswp_custom_schema_field`) de `botox-por-unidad`, `full-face-botox-60-unidades`, `botox-en-cuello-cdmx` y `botox-foxy-eyes-cdmx`. Ya corrido en producción; respaldos en `~/backups-deploy/20261022-*-content-fixes/`.
- Mapa de keywords y canibalización (2026-10-22, PR #23): `docs/reports/2026-10-22-mapa-keywords.md` y `seo/cannibalization.py`. Línea base: la home se queda con las búsquedas genéricas de cada hub. Endolifting descartado (no está en la lista de tratamientos).
- Riesgos y contraindicaciones en relleno de labios e hilos tensores, ES/EN (2026-10-22, PR #21, run 39799393550): con esto los 4 hubs de inyectables y Sculptra tienen su H2 de riesgos. Texto aprobado por el usuario.
- `.htaccess` de producción (2026-10-22, PR #22 sincroniza la copia del repo; `seo/deploy.sh` no lo sube): el bloque de WP Rocket se llama ahora `Static files` y ya no envía `X-Powered-By: WP Rocket` (se quedan gzip, Expires y CORS de fuentes, que dan la caché de 1 año a CSS/JS); `X-Powered-By` de PHP oculto; bloque `Security headers` con HSTS `max-age=300`, `nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy` y `Permissions-Policy`. Respaldos en `~/backups-deploy/20261022-07*-htaccess/`.
- Divi y ManageWP fuera del todo (2026-10-22): plugins borrados, 24 opciones `et_*`/`mwp_*` (licencia de `angloodigital`, llaves de ManageWP) y 412 filas de postmeta `_et_*` borradas; respaldo en `~/backups-deploy/20261022-052536-divi-purge/`. El original de cada servicio sigue en el meta `_eg_divi_content`. El tema ya no tiene código para Divi.
- Enlaces internos a los hubs con la frase exacta (2026-10-22, `eg_hubs()`): tarjetas y línea "Guías completas" en la home ES/EN, frase al final de cada `/servicio/` y enlaces en los encabezados de `/tratamientos/`. Medir con `seo/gsc_report.py` (3–10 nov) si `/botox-cdmx/` y `/rellenos-cdmx/` le ganan a la home en "botox cdmx" y "rellenos cdmx".
- Pie de página sin créditos de Anglood ni mktboom (PR #15).
- Fuera Divi (PR #14, desplegado el 2026-10-22; Divi y `worker` desactivados ese día). Nota: `wp` falla en producción por un choque de Mustache con el plugin post-to-google-my-business; usar `--skip-plugins=post-to-google-my-business`. `seo/divi-to-html.php` convierte las 27 páginas `/servicio/` (y 2 borradores) de shortcodes de Divi a HTML con clases `lp-sv-*`; mismo texto verificado palabra por palabra, de 100–200 KB a 9–30 KB de contenido. Probado en local con Divi desactivado. Tras el deploy: desactivar `divi-builder`, y después de unos días borrarlo junto con la licencia de `angloodigital`, el filtro `eg_drop_service_banner` y los estilos `#et-boc` de `_home.scss`.
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
- Categoría principal del perfil cambiada a mano a "Spa médico" (2026-10-23, Clínica especializada pasa a secundaria); Google ya lo publicó (verificado por API). A las 2–4 semanas, medir impresiones con `seo/gbp_metrics.py`.

- Medias de la auditoría que quedan: hubs en inglés a ~900 palabras (USD, "Mexico vs US cost", "Is it safe?", "Planning your visit"); schema (BreadcrumbList, ItemList en `/tratamientos/`, `reviewedBy`/`dateModified` en hubs, `price` en Offer, `Physician` como Person, `@id` entre idiomas); Clarity después de `load` y logos de marca a WebP; CSP en report-only (falta decidir a dónde van los reportes).
- Del 3 al 10 nov: `seo/gsc_report.py` y luego `seo/cannibalization.py`; comparar contra `docs/reports/2026-10-22-mapa-keywords.md` (hubs contra home, `/en/` contra home, clics de Full Face y Botox por unidad).

## Decisiones
- `seo/deploy.sh` es la única receta de deploy; CI la reutiliza.
- Push a `main` despliega solo el tema; los scripts de contenido solo se corren a mano (pisan ediciones de wp-admin).

## Bloqueos
- Fotos antes/después y datos del aviso de privacidad dependen del cliente.
