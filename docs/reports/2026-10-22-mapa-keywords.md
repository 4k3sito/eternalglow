# Mapa de keywords y canibalización

Fecha: 2026-10-22. Datos: Search Console del 18 jul al 16 oct de 2026 (`seo/gsc-data/`, generado el 17 oct). Script: `seo/cannibalization.py`.

**Límite de los datos.** El rediseño de los hubs fue el 13 oct y las páginas `/en/` son posteriores, así que este periodo casi no los incluye. Sirve como línea base: el reporte del 3 al 10 nov dirá si los hubs ya le ganaron a la home.

## Hallazgo principal: la home se queda con las búsquedas de cada tratamiento

| Tema | Impresiones | Home | Hub | Página del hub |
|---|---|---|---|---|
| Botox | 9,325 | 33% (111 clics) | 5% (1 clic) | `/botox-cdmx/` |
| Sculptra | 3,184 | 39% | 49% | `/sculptra-cdmx/` |
| Hilos | 2,626 | 33% | 66% | `/hilos-tensores-cdmx/` |
| Rellenos | 2,574 | 49% (43 clics) | 0% | `/rellenos-cdmx/` |

- La home aparece en posición 3–7 para "botox", "sculptra", "hilos tensores", "botox cdmx", "botox mexico city" y "lip filler mexico city". Los hubs, cuando aparecen, están en 20–60.
- Las búsquedas en inglés también caen en la home en español, porque las `/en/` no existían o no estaban indexadas.
- Lo que ya se hizo: enlaces internos a cada hub con la frase exacta (PR #16, 22 oct) y las páginas `/en/` con hreflang.

**Qué esperar del 3 al 10 nov:** que "botox cdmx", "rellenos cdmx" (o "rellenos faciales cdmx"), "hilos tensores cdmx" y "sculptra cdmx" pasen de la home a su hub, y "botox mexico city" a `/en/botox-mexico-city/`. Si la home sigue ganando, el siguiente paso es quitarle peso a esos términos en la home (textos de sus tarjetas y del FAQ) en vez de agregar más enlaces.

## Mapa: qué página es dueña de cada intención

| Intención | Búsquedas de ejemplo | Página dueña | Hoy la recibe |
|---|---|---|---|
| Marca | eternal glow, eternal glow cdmx | `/` | `/` ✔ |
| Clínica estética genérica | clinicas esteticas cdmx, medicina estetica cdmx | `/` | `/` (pos 7–38) |
| Botox en CDMX y precios | botox cdmx, botox precio cdmx, mejores lugares para botox | `/botox-cdmx/` | `/` y `/servicio/botox-por-unidad/` |
| Precio por unidad | precio de botox por unidad | `/servicio/botox-por-unidad/` | ✔ (pos 4.1, 0 clics de 156) |
| Baby Botox | baby botox, baby botox cdmx | `/servicio/baby-botox-cdmx/` | ✔ |
| Full face | botox full face, full face botox, botox full face que incluye | `/servicio/full-face-botox-60-unidades/` | ✔ (pos 5–6, 1 clic de ~600) |
| Botox en inglés | botox mexico city, botox near me | `/en/botox-mexico-city/` | `/` |
| Sculptra | sculptra, sculptra cdmx, dónde aplican sculptra | `/sculptra-cdmx/` | `/` y el hub, los dos en pos ~23 |
| Hilos | hilos tensores cdmx, hilo tensor cdmx | `/hilos-tensores-cdmx/` | repartido con `/` |
| Rellenos | ácido hialurónico cdmx, rellenos faciales cdmx | `/rellenos-cdmx/` | `/` y 4 `/servicio/` |
| Rinomodelación | rinomodelacion cdmx, rinomodelacion precio | `/servicio/rinomodelacion-en-cdmx/` | ✔ pero en pos 24–57 |
| Labios (inglés) | lip filler mexico city, dermal filler for lips in mexico city | `/en/lip-filler-mexico-city/` | `/` (pos 2–5, 17 clics) |
| Hollywood Peel | hollywood peel, hollywood peel cdmx | `/servicio/hollywood-peel-en-cdmx/` | ✔ (ya tratado en PR #17) |

La canibalización entre `/sculptra-cdmx/` y `/servicio/tratamiento-sculptra-en-cdmx/` ya está resuelta: la segunda redirige 301 al hub. "botox cdmx" en `/servicio/botox-para-menton-en-cdmx/` (pos 41) es ruido; no hace falta tocarlo.

## Acciones

1. **Respuestas rápidas en Full Face y Botox por unidad.** Son las dos páginas con más impresiones en el top 6 y casi sin clics. La búsqueda "botox full face que incluye" (102 impr) pide justo lo que hoy está más abajo. Usar el bloque de `includes/_service-answers.php` (el mismo de Hollywood Peel): qué zonas incluye, cuánto dura, precio. Lo más probable es que hoy Google conteste con un resumen de IA; el bloque busca que esa respuesta cite a la clínica.
2. **Esperar al reporte del 3 al 10 nov** antes de mover más la home. Correr `seo/gsc_report.py` y después `seo/cannibalization.py` y comparar contra la tabla de arriba.
3. **Huecos sin página** (la home aparece en el top 6 sin ser la respuesta):
   - "endolifting facial": 201 impr, pos 4.6. Descartado: no está en la lista de tratamientos de la clínica.
   - "restylane": 237 impr, pos 5.9. Es búsqueda de marca del producto; una mención en `/rellenos-cdmx/` basta.
4. **Rinomodelación** está en pos 24–57 con intención clara de precio. Candidata a ampliar contenido (riesgos, antes y después, FAQ de precio) después de los hubs.
