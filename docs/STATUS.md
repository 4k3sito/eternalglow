# Estado del proyecto
Actualizado: 2026-10-20

## Ahora
Atacando las tareas de la auditoría SEO del 2026-10-20 (`docs/reports/2026-10-20-auditoria-seo.md`, 68/100).

## Hecho recientemente
- Cédula profesional del Dr. Ocampo (13493041) en el bloque del médico (home y 9 hubs ES/EN), en el schema Physician y en `seo/nosotros.php`. Verificada en el Registro Nacional de Profesionistas (SEP). Pendiente: desplegar (tema + `run_content_scripts` para /nosotros/).
- Crítica de diseño del sitio con referencias de Mobbin (2026-10-20). Cambios rápidos aplicados en código: `seo/design-fixes.php` (casilla de privacidad sin premarcar en los formularios ES/EN, foto con marca de agua fuera de la galería de Botox, calificación de Google bajo el CTA del hero de /botox-cdmx/), calificación en el hero de los hubs con hero propio, y precios de Botox en 3 grupos (ES/EN). Pendiente: desplegar con `run_content_scripts`.
- Auditoría SEO completa (68/100); 12 tareas nuevas en Notion.
- Schema unificado en una sola entidad `#clinic` (PR #5): fuera el MedicalBusiness de SASWP con el teléfono viejo y las reseñas propias.
- Deploy por GitHub Actions (PR #3); primer deploy `--theme-only` OK el 2026-10-20, run 39391632264.
- Pipeline instalado (PR #1); `rediseno-editorial` mergeada a `main` (PR #2).
- 20 pendientes migrados de `tareas-eternalglow.md` a Notion.
- SEO: páginas en inglés de Sculptra, Botox, rellenos y relleno de labios; footer y formulario en inglés en `/en/`.

## Siguiente
- TTFB sin caché (2.4–11 s en /servicio/): precarga de WP Super Cache tras el deploy.
- Resto de Altas de la auditoría: hub de Botox, reenfoque de Rellenos, médico visible en hubs.

## Decisiones
- `seo/deploy.sh` es la única receta de deploy; CI la reutiliza.
- Push a `main` despliega solo el tema; los scripts de contenido solo se corren a mano (pisan ediciones de wp-admin).

## Bloqueos
- Fotos antes/después y datos del aviso de privacidad dependen del cliente.
