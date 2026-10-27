# Eternal Glow — tareas unificadas

Consolidado de las 16 sesiones de Claude del proyecto (24 sep – 19 oct 2026). Estado al 19 oct 2026.
Columnas pensadas para una base de datos de Notion: Tarea · Estado · Responsable · Prioridad · Área.

## Pendientes

| Tarea | Estado | Responsable | Prioridad | Área |
|---|---|---|---|---|
| Aviso de privacidad: `/aviso-de-privacidad/` da 404. `seo/privacidad.php` crea el borrador; faltan razón social, fecha y revisión legal. Debe mencionar GA4 y Clarity (LFPDPPP) | Bloqueado (cliente) | Cliente + Claude | Alta | Legal |
| Indexación EN (21 oct): Botox y Sculptra ya estaban indexadas; se pidió indexar `/en/fillers-mexico-city/` y `/en/lip-filler-mexico-city/` ("Google no reconoce esta URL", sin sitemap que las refiera). Revisar en unos días y si las `/en/` están en el sitemap | Esperando a Google | Claude | Alta | SEO |
| Correr `seo/gsc_report.py` entre el 3 y el 10 de nov para medir el rediseño y las páginas en inglés | Programado | Claude | Media | SEO |
| Cédula profesional del Dr. Bruno Ocampo (ficha `Physician` en schema, E-E-A-T) | Bloqueado (cliente) | Cliente | Alta | Contenido |
| Fotos de antes y después con consentimiento de los pacientes | Bloqueado (cliente) | Cliente | Media | Contenido |
| Reseñas en Google (pedirlas por WhatsApp después de cada cita) y optimizar el perfil: categoría, servicios con precio, fotos semanales | Por hacer | Cliente | Alta | Local |
| Perfiles en directorios que ocupan el top 3: Doctoralia, marketplace de AgendaPro, Yelp, Top Doctors. Kit listo en `docs/directorios.md` (datos, descripciones ES/EN, precios, UTM, pasos); falta crear las cuentas y luego sumar las URLs al `sameAs` | Pospuesto (22 oct) | Usuario / Cliente | Media | Local |
| Revisar si AgendaPro permite GA4 o GTM en su página de reservas, para medir la cita confirmada | Por hacer | Usuario | Baja | Medición |
| Excluir el tráfico interno en GA4 (IPs de la clínica y tuya) | Pospuesto (22 oct, el usuario no lo quiere por ahora) | Claude (con IPs del usuario) | Baja | Medición |
| Decidir el merge de `rediseno-editorial` a `main` (rama ya subida a GitHub) | Por hacer | Usuario | Media | Repo |
| En la próxima recompilación de `dist/`: pasar a SCSS los arreglos temporales de `_perf.php` (fuente de respaldo y aspect-ratio de los recortes) | Por hacer | Claude | Baja | Rendimiento |
| Imágenes nuevas: generar su copia `.webp`, porque ya no hay plugin que lo haga | Recurrente | Claude | Baja | Rendimiento |
| Comercial: propuesta mensual de SEO a $12,500 (precio de lista $15,000 menos $2,500 por tener el mantenimiento) | Por definir | Usuario | Media | Comercial |

## Hecho (resumen)

- Rediseño editorial en producción (13 oct), con precios oficiales, hubs de Botox, Sculptra, rellenos y labios, y versión móvil.
- Footer y formulario en inglés en las páginas `/en/` (CF7 #744, horario, COFEPRIS, WhatsApp en inglés).
- Páginas en inglés publicadas: Botox, Sculptra, rellenos y relleno de labios, con hreflang, FAQ y schema. La clínica es bilingüe.
- Medición: GTM propio (`GTM-KQFHDQ2M`) con Clarity, eventos de FAQ e idioma, GA4 con 4 eventos clave y dimensiones; GTM de la agencia retirado.
- Rendimiento: caché de páginas (WP Super Cache en modo experto, TTFB de 1.05 s a 0.22 s), WebP restaurado, GIF a WebP fijo, reCAPTCHA diferido, CLS de 0.16 a 0.004, `gtag` cargado una sola vez.
- Correo del sitio (21 oct): WP Mail SMTP con Google Workspace (API de Gmail, OAuth con `akesit0@`), remitente `Eternal Glow <no-reply@eternalglow.com.mx>` como alias verificado en "Enviar como"; envío confirmado. Formularios CF7 #54 y #744 (EN) envían solo a `akesit0@eternalglow.com.mx` (se quitaron `eternalglow.medicalaesthetic@`, `seoanglood@` y `fporras2593@`); envío real del formulario confirmado.
- Acceso `dev` del 15 oct aclarado (21 oct): el usuario confirmó que él borró WP Rocket e Imagify; no fue un acceso indebido.
- Cuentas ajenas fuera (21 oct): correo de admin de WP y alertas de Wordfence pasaron de `dev.mrgeorge@` a `akexanderr123@`; Search Console solo tiene a `akexanderr123@` como propietario; el único usuario de WP (`dev`) usa `akexanderr123@`. Correo público del sitio (pie de página y schema `#clinic`, `theme_contact[email1]`) cambiado a `contacto@eternalglow.com.mx`, alias de `akesit0@` en Workspace y verificado en "Enviar como" de Gmail; respaldo de la opción en `~/backups-deploy/20261021-195848-theme_contact.json`. El 21 oct también se cambió en el schema de SASWP (inicio, `sculptra-cdmx` y el servicio de Sculptra) y se borraron las opciones huérfanas de WP Rocket, MonsterInsights y la telemetría de StellarWP, que tenían correos ajenos; respaldo en `~/backups-deploy/20261021-235737-emails/`.
- Enlaces internos a los hubs con la frase exacta (22 oct, PR #16): home ES/EN, las 26 `/servicio/` y `/tratamientos/`. Se mide en el reporte de GSC del 3 al 10 nov. Ese día también: pie de página sin créditos de Anglood ni mktboom (PR #15), y la foto rota de `/servicio/botox-en-los-labios/` cambiada por `aplicacion-botox-en-los-labios-cdmx.png` (respaldo en `~/backups-deploy/20261022-014935-lips-photo/`).
- Hollywood Peel (22 oct, PR #17): title y description con "carbon peel", bloque de respuestas rápidas bajo el H1 (`includes/_service-answers.php`) y reindexación pedida en Search Console. El 2 al 7 oct estuvo en posición ~1 en móvil con 0.7% de CTR (lo más probable: cita en el resumen con IA) y cayó a 20–60 sin cambios nuestros; revisarlo en el reporte del 3 al 10 nov.
- Página en inglés de hilos tensores `/en/pdo-threads-mexico-city/` (22 oct, PR #18): mismos datos y precios que la española, FAQ con schema, hreflang, en el menú y en "Full guides" de `/en/`, en el sitemap; indexación pedida.
- API key de Google rotada (22 oct): `GOOGLE_METRICAS_WEBPAGE_API` (proyecto "Eternal Glow", 369780453202) es una clave nueva restringida solo a PageSpeed Insights API, que se habilitó en el proyecto; la clave expuesta se borró. PageSpeed responde 200.
- Divi y ManageWP borrados del todo (22 oct): las 27 páginas de servicio pasaron a HTML propio (PR #14), los plugins y sus opciones (licencia de `angloodigital`, llaves de ManageWP) y metadatos se borraron con respaldo en `~/backups-deploy/20261022-052536-divi-purge/`, y el tema quedó sin código para Divi.
- API de Google Business aprobada (23 oct): `seo/gbp_metrics.py` baja 18 meses de métricas diarias y búsquedas de Eternal Glow a `seo/gbp-data/`. Filtra solo ese perfil; los demás de la cuenta (TechMech) se ignoran. Ese día `seo/gbp_profile.py` corrigió el perfil: fuera el teléfono viejo 55 3646 0497 (también en el enlace de WhatsApp), WhatsApp como chat principal, enlace de citas a AgendaPro, sitio con `utm_campaign=gbp` y precio en 26 de los 68 servicios. Respaldo en `~/backups-deploy/20261023-204955-gbp/`. Lectura preliminar del rediseño en `docs/reports/2026-10-23-impacto-rediseno.md`.
- Scripts en `seo/`: OAuth de Google, inventario, reporte de Search Console, auditoría del sitio, constructor de GTM y despliegue.
