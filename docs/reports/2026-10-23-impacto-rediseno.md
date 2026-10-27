# Impacto del rediseño (lectura preliminar, 2026-10-23)

El rediseño salió el 13 oct. Se comparan los 9 días posteriores (13–21 oct, último día con datos en Search Console) contra los 9 anteriores (4–12 oct). Nueve días son pocos: esto es una primera señal. La medición formal sigue en pie para el 3–10 nov con `seo/gsc_report.py`.

## Search Console (la fuente confiable)

| | Antes | Después | Cambio |
|---|---|---|---|
| Clics | 121 | 197 | +63% |
| Impresiones | 5,854 | 8,900 | +52% |
| CTR | 2.1% | 2.2% | = |
| Posición media | 9.0 | 7.5 | 1.5 lugares arriba |
| Móvil, clics / posición | 98 / 8.1 | 136 / 6.3 | +39% |
| Escritorio, clics / posición | 23 / 12.4 | 59 / 11.3 | +157% |
| Páginas `/en/`, clics | 0 | 15 | nuevas |

Páginas que más subieron:
- `/sculptra-cdmx/`: de 1 a 28 clics, de posición 15.1 a 6.4 (la fusión de las dos páginas de Sculptra funcionó).
- `/hilos-tensores-cdmx/`: de 2 a 9 clics, de posición 10.8 a 7.4.
- Servicios de rellenos (marcaje mandibular, armonización, ojeras, rinomodelación, labios): pasaron de la página 2–3 a la 1 (posición 4–10).
- `/en/`: 11 clics en su primera semana.

Lo que bajó:
- `/servicio/hollywood-peel-en-cdmx/`: de posición 3.9 a 10.3. La caída ya venía de antes del rediseño (ver la tarea del 22 oct, PR #17).
- Búsquedas genéricas como "botox cdmx" (posición 17 → 13) y "sculptra cdmx" (22 → 18) suben, pero siguen fuera del top 10.

## GA4 (no comparable todavía)

Las sesiones van de 131 a 538 y los eventos clave de 18 a 102 (`click_agendar_cita` 9 → 52, `click_whatsapp` 9 → 50). **No hay que reportar estas cifras como resultado:** la medición cambió el 16 oct (GTM propio, `gtag` una sola vez) y el tráfico interno no está excluido, así que las pruebas del rediseño cuentan como visitas. Las sesiones orgánicas de GA4 se cuadruplicaron, mientras que los clics de Search Console subieron 63%; esa diferencia es medición, no tráfico. GA4 sirve como línea base desde el 16 oct en adelante.

## Perfil de Google Business

Clics al sitio desde el perfil: 82 → 79. Impresiones: 2,807 → 2,984. Rutas: 29 → 48. El perfil casi no depende del sitio, así que no sirve para medir el rediseño. Desde el 23 oct el enlace del perfil lleva `utm_campaign=gbp`, con lo que GA4 separa esas visitas de las orgánicas.
