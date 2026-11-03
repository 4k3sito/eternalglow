# Estudio SEO 01: OpenSEO y datos reales

Fecha: 2026-11-02. Objetivo: plan para subir CTR y tráfico de eternalglow.com.mx.

**Cómo leer este documento.** **[M]** es dato medido, con su fuente. **[E]** es estimación propia, con la fórmula a la vista. **[I]** es inferencia: explica lo medido, pero no está comprobada. Ningún volumen de búsqueda de este documento es real: no tenemos esa fuente (ver sección 1).

**Ventanas de GSC** (`dataState=final`, propiedad `https://eternalglow.com.mx/`):
- **Pre**: 23 sep a 12 oct (20 días).
- **Post**: 13 a 31 oct (19 días; el 1 nov aún no es dato final).

## Resumen

1. **El CTR no bajó; nunca subió.** Pasó de 2.19% a 2.15% con 64% más clics por día [M]. Sin marca es ~1.0% en ambos periodos [M]. El tráfico creció por aparecer en más búsquedas.
2. **La mayor palanca es la intención de precio.** Sin Sculptra suma 3,585 impresiones visibles, 21 clics y 0.59% de CTR; Sculptra precio da 3.27% [M]. Armonización + precio: **0 clics de 784** [M].
3. **Hollywood Peel no debería ser la prioridad 1** del reporte al cliente. Aun estando en posición 1.6 tenía 0.45% de CTR [M]. La "caída" costó pocos clics y no es reciente: el patrón de encendido y apagado viene desde agosto.
4. **La URL `/?utm_...campaign=gbp` distorsiona las lecturas de la home.** Desde el 23 oct se lleva sus impresiones locales. Por eso `/` sola aparece en posición 24.3. También genera falsos positivos en canibalización.
5. **El hub de Botox sigue sin ganar**: posición 31.8 frente a la home en 7 para "botox cdmx" [M]. Lo que lo separa de Sculptra es profundidad: 1,543 contra 3,634 palabras [M].
6. **Impacto estimado del plan:** +60 a +150 clics al mes sobre ~690, es decir +9% a +22% [E].

## 1. OpenSEO

**Qué es.** Proyecto real: [`every-app/open-seo`](https://github.com/every-app/open-seo). Es una alternativa open source a Semrush y Ahrefs, con licencia MIT, 26.1k estrellas y versión v0.1.22 del 31 oct 2026, respaldada por Y Combinator [M: GitHub y openseo.so].

**Qué da.** Investigación de keywords (volumen, ideas), seguimiento de rankings, datos de competidores, backlinks, auditoría de sitio, visibilidad en IA, grid local de Maps y servidor MCP para agentes. Los datos de Google Search Console son gratis en el producto [M: README y pricing]. Una reseña independiente dice que casi todo viene de DataForSEO y que el crawler de auditoría y las conexiones GSC/GA son propios ([ryandoser.com](https://ryandoser.com/openseo-review/)).

**Qué requiere.**

| Vía | Costo | Requisito |
|---|---|---|
| Alojado (openseo.so) | $10 USD/mes con $10 de uso incluidos; prueba con $0.50 de crédito; 28% de margen sobre DataForSEO | Crear cuenta |
| Self-host (Docker o Cloudflare) | Gratis, más lo que se pague a DataForSEO | **API key de DataForSEO de pago** |

Referencia de su calculadora: 100 búsquedas de keywords ≈ $5.40 USD [M: openseo.so/pricing].

**¿Se puede usar aquí hoy? No.**
- `.env` solo tiene una variable, `GOOGLE_METRICAS_WEBPAGE_API`; no hay ninguna tipo `DATAFORSEO` [M].
- Las herramientas MCP de DataForSEO no están disponibles en esta sesión [M].
- No instalé ni pagué nada, como se pidió.

**Qué se reprodujo y qué no.**

| Módulo OpenSEO | Sustituto usado | Estado |
|---|---|---|
| Datos de GSC | API de GSC directa (lo mismo que OpenSEO lee) | Completo |
| Auditoría on-page | Fetch de 24 URLs: título, meta, H1, schema, precios | Parcial |
| Indexación | URL Inspection API, 10 URLs | Completo |
| Competidores | 7 búsquedas con Firecrawl y 2 mapas de enlaces | Parcial: **no es la SERP real de Google** |
| Búsquedas locales | Keywords de GBP (`seo/gbp-data/`) | Parcial |
| Volumen, dificultad | Nada | **Sin dato** |
| Backlinks | Nada | **Sin dato** |
| Presencia de AI Overviews, grid de Maps | Nada | **Sin dato** |

**Recomendación:** pagar un mes del plan alojado ($10 USD) antes de invertir en contenido nuevo. Es decisión del usuario. Consultas para gastar esos créditos: volumen de los términos de la sección 6, backlinks de eternalglow.com.mx contra Juvenalia Brío y Trapassi, y si hay AI Overview en "botox en los labios", "baby botox" y "hollywood peel".

## 2. Línea base

| Métrica [M: GSC, dimensión fecha] | Pre (20 d) | Post (19 d) | Cambio |
|---|---|---|---|
| Clics | 279 (14.0/día) | 436 (22.9/día) | +64% por día |
| Impresiones | 12,722 (636/día) | 20,255 (1,066/día) | +68% por día |
| CTR | 2.19% | 2.15% | Igual |
| Posición | 8.9 | 8.2 | +0.7 |

- **CTR sin marca** (consultas visibles): 0.96% a 1.00%; sin Hollywood, 1.07% a 1.00% [M].
- **Dispositivo post:** móvil posición 7.2, escritorio 11.6 [M].
- **País post:** México 1.8% de CTR (313 clics, posición 7.9); EE. UU. 7.2% (103 clics, posición 10.0) [M].

**Límites de los datos.**
- **Anonimización.** Las tablas por consulta solo ven **33% de los clics y 54% de las impresiones** (146 de 436 clics; 11,006 de 20,255 impresiones) [M]. Por eso los CTR por consulta son una muestra. Con menos de ~100 impresiones, un 0% no prueba nada; por eso agrupo.
- **Adónde llegan los clics de EE. UU.: no se sabe.** `página × país` solo recupera 14 de los 103 clics [M].
- Este documento usa cortes distintos a los del reporte del 2 nov, por eso las cifras no coinciden del todo. Son consistentes (+64% contra +65%).

## 3. Páginas y consultas

### Páginas por impresiones

Post [M: GSC, dimensión página]; la columna Pre es impresiones / posición.

| Página | Clics | Impr. | CTR | Pos. | Pre | Lectura |
|---|---|---|---|---|---|---|
| `/` | 144 | 3,956 | 3.64% | 12.1 | 4,637 / 7.8 | Sumar con la fila UTM |
| `/sculptra-cdmx/` | 70 | 2,573 | 2.72% | 6.3 | 456 / 15.1 | **El modelo a copiar** |
| `/hilos-tensores-cdmx/` | 21 | 1,594 | 1.32% | 7.5 | 569 / 10.1 | CTR bajo por precio |
| `/?utm…gbp` | 55 | 1,503 | 3.66% | 4.3 | 0 | Tráfico de GBP |
| `/servicio/armonizacion-facial…` | 6 | 1,380 | **0.43%** | 7.9 | 321 / 14.6 | **Peor caso** |
| `/servicio/rinomodelacion-en-cdmx/` | 16 | 1,264 | 1.27% | 9.0 | 175 / 32.6 | Subió fuerte |
| `/servicio/botox-en-los-labios/` | 8 | 1,003 | 0.80% | 5.0 | 353 / 7.2 | Título en prueba |
| `/servicio/hollywood-peel-en-cdmx/` | 9 | 780 | 1.15% | 9.8 | 2,012 / 5.0 | Ver 3.5 |
| `/servicio/baby-botox-cdmx/` | 2 | 552 | **0.36%** | 7.0 | 632 / 8.1 | CTR muy bajo |
| `/servicio/marcaje-mandibular…` | 6 | 544 | 1.10% | 8.6 | 315 / 13.1 | |
| `/servicio/full-face-botox-60…` | 7 | 541 | 1.29% | 5.4 | 473 / 5.8 | Mejorando |
| `/botox-cdmx/` | 4 | 498 | 0.80% | 16.5 | 238 / 24.7 | **Hub sin ganar** |
| `/servicio/tratamiento-pdrn-salmon/` | 4 | 490 | 0.82% | 5.4 | 997 / 6.2 | Reposicionada a Rejuran |
| `/servicio/microneedling-en-cdmx/` | 6 | 428 | 1.40% | 5.7 | 140 / 12.7 | |
| `/en/botox-mexico-city/` | 4 | 309 | 1.29% | 8.2 | nueva | |
| `/en/` | 17 | 226 | 7.52% | 5.0 | nueva | Mejor CTR del sitio sin marca |

Dos páginas con cero clics en el periodo: `/servicio/peeling-quimico-facial-en-cdmx/` (277 impr., pos. 12.6) y `/tratamientos/` (180 impr., pos. 6.3) [M].

### Consultas por impresiones

Post [M: GSC, consultas visibles].

| Consulta | Clics | Impr. | CTR | Pos. | Página | Impr. pre |
|---|---|---|---|---|---|---|
| sculptra precio | 31 | 668 | 4.6% | 4.6 | hub | 13 |
| botox en los labios | 2 | 259 | 0.8% | 2.3 | servicio | 23 |
| hilos tensores | 0 | 231 | 0% | 6.0 | **home** | 104 |
| armonización facial precio | 0 | 204 | 0% | 8.7 | servicio | 7 |
| sculptra | 1 | 148 | 0.7% | 4.5 | hub | 133 |
| baby botox | 1 | 140 | 0.7% | 4.1 | servicio | 364 |
| hilos tensores cara precio | 1 | 119 | 0.8% | 12.1 | hub | 54 |
| restylane | 0 | 113 | 0% | 6.0 | home | 92 |
| sculptra precio en méxico | 0 | 109 | 0% | 5.0 | hub | 45 |
| hilos tensores precio | 0 | 108 | 0% | 7.1 | hub | 0 |
| microneedling precio | 2 | 107 | 1.9% | 4.3 | servicio | 29 |
| armonización facial precios | 0 | 106 | 0% | 8.3 | servicio | 6 |
| lip fillers in mexico | 0 | 105 | 0% | 42.1 | home | 28 |
| botox mexico city | 1 | 96 | 1.0% | 8.6 | **home** | 62 |
| botox cdmx | 1 | 92 | 1.1% | 14.7 | **home** | 113 |

### CTR por tema

Post, consultas visibles; agrupación propia con regex [M, con esa salvedad].

| Tema | Impr. | Clics | CTR | Lectura |
|---|---|---|---|---|
| Sculptra | 1,816 | 44 | **2.42%** | 88% va al hub |
| Botox | 2,858 | 20 | 0.70% | Repartido en 11+ páginas |
| Rellenos (incluye armonización y rino) | 2,518 | 16 | 0.64% | |
| Hilos | 1,364 | 3 | **0.22%** | El peor de los temas grandes |
| Peel | 508 | 4 | 0.79% | |

### Impresiones altas y CTR bajo

Estas son las que dan más tráfico si se corrigen. Las consultas de precio están en 3.4.

| Oportunidad | Dato [M] | Causa probable [I] |
|---|---|---|
| Hilos, consultas sin precio | "hilos tensores" 231 impr., 0 clics, en la home | La home no responde a esa intención |
| Baby Botox | 0.36% en página, pos. 7.0 | Título solo con precio; "baby botox" busca qué es |
| Botox en los labios | 0.8% en pos. 2.3 | Consulta informativa. Posible AI Overview, sin dato |
| Facial genérico | 0 clics de 279 impresiones, pos. 6.7 | La clínica ya no ofrece limpiezas (`seo/content-fixes.php`). **No perseguir** |

**CTR por posición (sin marca, post) [M]:** pos. 1–1.99: 1.5% · 2–2.99: 0.68% · 3–3.99: 1.86% · 4–4.99: 2.52% · 5–6.99: 0.8% · 7–9.99: 0.51% · 10–14.99: 0.31%. Las primeras posiciones rinden menos que la 4. Dos razones [I]:
- Son posiciones del paquete local, que atraen consultas de servicios que la clínica no da.
- La fila 4–4.99 la infla "sculptra precio".

Por eso **no uso curvas de CTR externas**. Mi referencia es interna: Sculptra precio, 3.27% en posición 5.7.

### Consultas de precio

Post, total de 4,813 impresiones visibles y 1.27% de CTR [M: GSC].

| Grupo (precio/costo/cuánto) | Impr. | Clics | CTR | Pos. |
|---|---|---|---|---|
| **Sculptra** | 1,225 | 40 | **3.27%** | 5.7 |
| Rinomodelación / nariz | 590 | 8 | 1.36% | 9.6 |
| Botox | 705 | 4 | 0.57% | 13.7 |
| Hilos | 683 | 3 | 0.44% | 8.4 |
| **Armonización** | 784 | **0** | **0%** | 8.4 |

**Por qué Sculptra sí funciona [I].** Es el único título con la palabra **"Precio"** junto a la cifra, su página tiene 3,634 palabras, y es la única con esa profundidad entre los hubs.

**Armonización tiene un problema de expectativa [I].** Quien busca ese precio quiere el costo total. El título muestra "$6,499", que es el de una jeringa. En otros resultados se ven "$15,000 a $45,000" y "$22,000 a $50,000" [M: fragmentos de Firecrawl]. El título tampoco dice "precio".

**La misma página en la misma posición puede rendir muy distinto:** "sculptra precio" da 4.6% y "sculptra precio en méxico" 0 de 109 [M]. Probablemente quien escribe "en méxico" no está en CDMX [I], el mismo patrón de Hollywood Peel. No es corregible con el título y conviene descontarlo de las expectativas.

### Hollywood Peel: rebajar prioridad

Coincido con la lectura de CTR estructural, y los datos la refuerzan:

- **El CTR fue bajo incluso en la cima.** En los 11 días en posición ≤3.5 (1 ago a 7 oct): 1,991 impresiones, 9 clics, **0.45%**, posición media 1.6 [M].
- **Lo que se perdió es poco.** Del 1 ago al 7 oct, 3,476 impresiones dieron 13 clics en la consulta exacta [M], unos 6 clics al mes [E].
- **No es un patrón nuevo.** Por semana: 473, 354, 112, 574, 184, 43, 614, 743, 31, 37, 22 impresiones [M]. Ya había caído entre el 24 y 28 sep (pos. 18–65) y volvió. Sí es la baja más larga hasta ahora: 3+ semanas.
- **No fue el rediseño:** la caída es del 8 oct y el rediseño salió el 13 oct [M]. En `seo/` y en `includes/` del tema, los commits del 3 al 13 oct son de Sculptra y del rediseño (sin desplegar aún). Ninguno es de Hollywood [M: git log].
- **Indexación sana:** indexada, canónica correcta, rastreada el 26 oct [M: URL Inspection].
- **Lo único concreto:** para esa consulta la página cayó a 24.7 y la home quedó en 6.2 con 23 impresiones desde el 8 oct [M]. Antes la página iba en 5.4. La causa sigue sin confirmar.

**Acción:** no invertir más ahora. Revisar a 4 semanas; si no vuelve, un enlace interno extra desde `/tratamientos/`. Las búsquedas con intención real ("hollywood peel cdmx") siguen en posición 3.5 y traen clics [M].

### Posición 4 a 15 (quick wins)

Las de precio ya salen en 3.4. Quedan estas [M: GSC post].

| Consulta | Impr. | Pos. | Página | Acción |
|---|---|---|---|---|
| botox mexico city | 96 | 8.6 | home | Mover a `/en/botox-mexico-city/` |
| botox cdmx | 92 | 14.7 | home | Ampliar hub |
| baby botox cdmx | 66 | 13.7 | servicio | Enlaces + título |
| foxy eyes con botox | 56 | 5.8 | servicio | Título, baja prioridad |
| botox cerca de mi | 42 | 13.1 | home | GBP |
| lifting facial cdmx | 39 | 14.3 | home | Página nueva (sección 6) |
| botox maseteros | 34 | 5.0 | servicio | Sinónimo en título |
| armonizacion facial | 32 | 13.6 | servicio | Lo cubre el título nuevo |
| botox roma norte | 25 | 7.8 | home | GBP + mención en hub |

### Canibalización: mapa de keywords contra hoy

Baseline: `docs/reports/2026-10-22-mapa-keywords.md`. No corrí `seo/cannibalization.py` tal cual porque lee `seo/gsc-data/`, que mezcla 90 días. Repliqué su lógica sobre la ventana post, y mis regex de tema son algo más amplias que las del script.

| Tema | Hub antes → ahora | Home antes → ahora | Veredicto |
|---|---|---|---|
| Sculptra | 49% → **88%** | 39% → 5% | **Ganado** |
| Hilos | 66% → 58% | 33% → 27% | Parcial: "hilos tensores" sigue en home |
| Botox | 5% → 7% (pos. 31.8) | 33% → 17% | **No ganado** |
| Rellenos | 0% → 202 impr. en pos. 9.4 | — | Ya aparece; aún pequeño |

- **El reporte del 2 nov dice que `/rellenos-cdmx/` tuvo 35 impresiones en posición 31.8.** Por página son 202 en posición 9.4 [M]. La diferencia viene de medir por consulta visible o por página.
- **Búsquedas repartidas:** 28 con ≥30 impresiones, pero **varias son falsos positivos** por la URL UTM. Reales: "botox cdmx" (home 7, hub 34, `/servicio/botox-para-menton` 27), "botox precio cdmx" (4 páginas en 14–36), "botox labios", "lip filler mexico city" (home 4, `/en/` 13).
- **Decisión pendiente del mapa:** decía "si la home sigue ganando, quitarle peso". Para Botox recomiendo **primero ampliar el hub**. Quitar texto a la home arriesga el paquete local, y la diferencia real es de contenido.

### La URL UTM de GBP

El 23 oct, el mismo día que se puso `utm_campaign=gbp` en el perfil, apareció esa URL en GSC [M].

| Semana | `/` | `?utm` | Suma |
|---|---|---|---|
| 12 oct | 61 clics, pos. 9.3 | — | 61, pos. 9.3 |
| 19 oct | 68, pos. 12.0 | 10, pos. 4.6 | 78, pos. 10.8 |
| 26 oct | **20, pos. 24.3** | 45, pos. 4.2 | 65, pos. 10.8 |

- Google separó el tráfico del perfil local del orgánico [I, por la coincidencia de fecha].
- Hoy se ve el orgánico puro de la home, que es **débil**: posición 24 en la última semana. La posición local es 1–6 en muchas búsquedas.
- **Conclusión práctica:** gran parte de lo que parecía orgánico de la home era paquete local. No quitar el UTM: permite medir GBP en GA4.
- `seo/gsc_report.py` y `seo/cannibalization.py` deberían normalizar esa URL. Es un cambio pendiente: sin eso, la medición del 3 al 10 nov saldrá con esta distorsión.

### Inglés

- **Páginas `/en/`:** 31 clics, 1,099 impresiones, 2.82% de CTR, posición 12.3 [M]. `/en/` lidera con 17 clics.
- **EE. UU. convierte a clic mejor que México** (7.2% contra 1.8%), pero 89 de sus 103 clics vienen de consultas anónimas [M].
- **Hreflang correcto** en `/` y `/en/botox-mexico-city/`: es-MX, en y x-default [M].
- **Google aún prefiere la home en español** para búsquedas en inglés: "botox mexico city" (home 8; UTM 1), "lip filler mexico city" (home 4; `/en/` 13), "sculptra mexico city" (home 7) [M].
- **Fuera de alcance:** "lip fillers in mexico" (105 impr., pos. 42). Ahí mandan Bookimed y clínicas de Tijuana [M: Firecrawl]. Tampoco perseguir "thread lift near me": pos. 30–85, y son búsquedas locales desde otros países [I].

## 4. Reescritura de títulos y metas

**Aplicar como un solo lote**, después de capturar la lectura del 3 al 10 nov, y medir 14 días contra los datos de este documento.

**Dónde se cambia:** servicios y `hilos-tensores-cdmx`/`botox-cdmx` en `seo/prices.php`. Es script de contenido: pide confirmación aparte. Si cambia precio o texto de un hub, subir también `'updated'` en `includes/_landings.php`.

| # | Página | Actual | Propuesto (longitud) |
|---|---|---|---|
| 1 | Armonización | Armonización Facial con Ácido Hialurónico en CDMX \| $6,499 | **Armonización Facial en CDMX: Precio desde $6,499 por Jeringa** (60) |
| 2 | Hilos hub | Hilos Tensores en CDMX \| Desde $1,100 por Hilo | **Hilos Tensores en CDMX: Precio desde $1,100 por Hilo** (52) |
| 3 | Baby Botox | Baby Botox en CDMX \| 30 Unidades por $4,499 \| Eternal Glow | **Baby Botox en CDMX: Precio, 30 Unidades por $4,499** (50) |
| 4 | Rinomodelación | Rinomodelación en CDMX \| $6,499 \| Perfilado sin Cirugía | **Rinomodelación con Ácido Hialurónico en CDMX: Precio $6,499** (59) |
| 5 | Botox hub | Botox en CDMX desde $165/unidad \| Roma Norte \| Eternal Glow | **Botox en CDMX: Precio desde $165 por Unidad \| Eternal Glow** (58) |
| 6 | `/tratamientos/` | Tratamientos de Medicina Estética en CDMX \| Eternal Glow | **Precios de Medicina Estética en CDMX \| Eternal Glow** (51) |
| 7 | Marcaje | Marcaje Mandibular en CDMX \| Desde $6,499 \| Eternal Glow | **Marcaje Mandibular en CDMX: Precio desde $6,499 por Jeringa** (59) |
| 8 | Peeling químico | Peeling Químico Facial en CDMX \| Precio: $2,000 | **Peeling Químico Facial en CDMX: Precio $2,000 por Sesión** (56); confirmar que el precio es por sesión |

**Metas propuestas:**

1. **Armonización** (148): Precio de armonización facial con ácido hialurónico en CDMX: $6,499 por jeringa, 2 por $11,000, 3 por $16,000 y 4 por $20,000. Médico en Roma Norte.
2. **Hilos** (mantener formato): Precio de hilos tensores PDO en CDMX: $1,500 c/u de 1 a 4 hilos, $1,300 de 5 a 8 y $1,100 desde 9. Lifting sin cirugía en Roma Norte.
3. **Baby Botox** (136): Baby Botox en CDMX: 30 unidades por $4,499. Qué es, para quién sirve y cuánto dura esta microdosis, con valoración médica en Roma Norte.
4. **Rinomodelación** (140): Precio de rinomodelación con ácido hialurónico en CDMX: $6,499. Mejora el perfil nasal y levanta la punta sin cirugía. Médico en Roma Norte.
5. **Botox hub** (145): Precio de Botox en CDMX: $165 por unidad, Baby Botox $4,499 y Full Face $8,499. Aplicado por médico estético en Roma Norte. Agenda tu valoración.
6. **Tratamientos** (141): Precios de Botox, rellenos, Sculptra, hilos tensores, HIFU y más en Roma Norte, CDMX. Compara cada tratamiento y agenda tu valoración médica.

**Precios verificados** contra `seo/armonizacion.php`, `includes/_landings.php` y la página en vivo.

**Notas sobre armonización:** el título sacrifica "ácido hialurónico" porque las consultas de precio (784) pesan más que esa frase; el H1 y la meta lo conservan. El título vigente tiene días, pero el formato ya se probó: el cambio del 27 oct dejó el CTR igual (0.43% a 0.45%) [M].

**No tocar por ahora:**
- **Sculptra:** es el grupo de control.
- **Full Face:** su CTR pasó de 0.79% a 1.73% tras la respuesta rápida del 22 oct, con pocos clics (2 a 5) [M].
- **Botox en los labios:** cambió el 27 oct. Va 0.87% a 0.52%, con 1 clic y su posición bajó de 4.5 a 7.3 [M]. Muy pocos datos: revisar el 17 nov.
- **Botox por unidad:** 0 clics de 161; la respuesta rápida del 22 oct no se nota aún, y su posición bajó de 5.9 a 8.0 [M]. El problema es de posición.
- **Hollywood Peel:** ver 3.5.

## 5. Schema y resultados enriquecidos

- **Lo que funciona: fragmentos de producto (precio en el resultado).** `PRODUCT_SNIPPETS`: 0 impresiones antes del 13 oct y 573 impresiones con 10 clics después [M]. En las 5 páginas con más presencia, el CTR dentro del fragmento es 1.98% contra 0.64% fuera (10/504 contra 11/1,732) [M].
  - **Salvedad:** son consultas distintas; no es un experimento. Aun así es la señal más fuerte de CTR que encontré.
  - **Acción:** comparar por qué lo tienen Hollywood, Rellenos, Full Face, Baby Botox, Botox por unidad y `/relleno-de-labios-cdmx/`, y **no** Sculptra, Hilos, Armonización, Rinomodelación ni `/botox-cdmx/`. Usar la Prueba de resultados enriquecidos, que no pude correr. Igualar el `Offer` de las que faltan.
  - **Riesgo:** no marcar servicios médicos como `Product` solo para forzarlo; va contra el sentido de ese tipo.
- **Estrellas:** no agregar `AggregateRating` propio. Google no muestra reseñas propias de un negocio local, y ya se quitó en el PR #5.
- **FAQPage:** URL Inspection solo detecta Breadcrumbs en las 10 URLs [M]. Dejar el FAQ (ayuda a la lectura y a respuestas de IA), sin esperar resultados enriquecidos de ahí.
- **Inspección:** 10 de 10 URLs indexadas, sin errores en el sitemap [M]. No hay problema técnico que explique el CTR.

## 6. Brechas de contenido

Comparé con Juvenalia Brío (Condesa), Trapassi, Aesthetic Center, María Linda, Doctoralia, Multiestética y Bookimed [M: Firecrawl, **no es la SERP de Google**]. Las impresiones de GBP son el acumulado de `seo/gbp-data/search_keywords.csv` (rango por defecto del script: 18 meses); **no son volumen de búsqueda**.

| Prioridad | Brecha | Evidencia | Propuesta |
|---|---|---|---|
| 1 | **Precios por zona de Botox** | Botox precio: 705 impr., pos. 13.7. Competidores cotizan por zona (Aesthetic Center: 1 zona $2,800; Trapassi: `/costo-botox-cdmx`). Eternal Glow, por unidad | Tabla por zona en `/botox-cdmx/`. **Requiere** que la clínica dé unidades por zona |
| 2 | **Hub de Botox delgado** | 1,543 palabras contra 3,634 de Sculptra [M]. Sin `MedicalProcedure` ni `MedicalCondition` | Llevarlo al formato de Sculptra |
| 3 | **Hubs de Rellenos e Hilos delgados** | 1,415 y 1,382 palabras [M] | Igual. Hilos: "cuántos hilos necesito" y ejemplos de costo total |
| 4 | **Lifting sin cirugía** | GBP: "lifting facial" 474, "ultherapy" 125, "hifu" 158. GSC: "lifting facial cdmx" pos. 14.3. Sin página | Comparativa: "Lifting Facial sin Cirugía en CDMX: Hilos, HIFU o Sculptra" (58). Validar volumen |
| 5 | **Armonización en inglés** | "facial balancing mexico city": 26 impr., pos. 3.4, 24 desde EE. UU., 1 clic, en home | `/en/facial-balancing-mexico-city/`. Título: "Facial Balancing in Mexico City \| From $6,499 per Syringe" (ajustar a ≤60) |
| 6 | **Página de precios** | `/precios/` da 404 [M]. Juvenalia tiene `/precios` y 13 subpáginas | **No crear otra.** `/tratamientos/` ya tiene precios: basta el título 6. Evita canibalizar |
| 7 | **Sinónimos** | GBP: "dermapen" 108 | Mencionar en microneedling. "restylane" ya está en los hubs [M] |

**Descartados:**
- Limpiezas e hidrofacial, y mesoterapia: la clínica no los ofrece.
- Endolifting: ya descartado.
- Skinboosters, Profhilo y Dysport: solo si la clínica los ofrece. No lo pude confirmar.

## 7. Enlaces y GBP

**Enlaces.** No hay datos de backlinks; cualquier meta sería inventada.
- **Directorios:** Doctoralia, Multiestética, Yelp y Bookimed aparecen en resultados de precio y de "near me" [M: Firecrawl]. Doctoralia, Yelp y Top Doctors ya están en `docs/directorios.md` sin crear. **Agregar Multiestética**, y Bookimed para EE. UU. solo si el costo tiene sentido.
- **Internos:** `/botox-cdmx/` debería recibir enlace con frase exacta desde sus 11+ páginas de Botox hacia "precio de Botox en CDMX". Ya existe la base con `eg_hubs()`; ajustar solo si el hub sigue atrás tras ampliarlo.

**GBP.**
- **Corrección importante sobre las llamadas.** El reporte del 2 nov las atribuye posiblemente a WhatsApp (23 oct). Pero por semana van 16 (5 oct), 7 (12 oct), 5 (19 oct), 3 (26 oct, incompleta) [M: GBP]. **La baja empezó antes del cambio**, aunque el rango histórico es 4–16, así que puede ser ruido. `BUSINESS_CONVERSATIONS` es 0 en todo el periodo [M], así que GBP no ayuda a saber si pasaron a WhatsApp.
- **Hipótesis descartada:** las búsquedas de "faciales" no vienen de la categoría "Spa médico". Subieron desde el 13 oct: 3/día, luego 12/día antes del cambio y 16/día después [M].
- **Búsquedas locales:** "sculptra" 1,431, "ácido hialurónico" 1,363, "botox" 875, "lifting facial" 474 [M]. Usar esos términos en los nombres y descripciones de servicios del perfil. "ácido hialurónico" compite con Sculptra por el primer lugar y es la vía de rellenos.
- **Posición local en EN:** "botox mexico city" sale en 1.2 por la URL UTM [M]. Una descripción de negocio bilingüe puede reforzarlo [I].
- **Esperado:** fotos y reseñas por WhatsApp, como dice el reporte al cliente.

## 8. Plan priorizado e impacto

**Base:** 436 clics en 19 días ≈ **690 al mes** [E]. Factor mensual: 30/19 = 1.58. Solo cuenta consultas visibles, y cada fila excluye lo contado arriba, por lo que no hay doble conteo entre filas.

| # | Acción | Esfuerzo | Fórmula | Clics/mes [E] |
|---|---|---|---|---|
| 1 | Títulos y metas (sección 4) | Horas | 5,660 impr./mes de precio sin Sculptra. CTR de 0.59% a 1.2–2.0% | **+35 a +80** |
| 2 | Hub Botox (contenido + tabla por zona) | Días | 3,400 impr./mes sin precio. CTR de 0.74% a 1.2–2.0% | **+16 a +43** |
| 3 | Inglés: `/en/` sobre la home, facial balancing | Días | Consultas EN 1,775 impr./mes. CTR de 1.78% a 2.3–3.5% | **+10 a +30** |
| | **Total** | | | **+60 a +150 (+9% a +22%)** |

No incluidos, porque no se pueden estimar sin volumen: Fragmentos de producto, página de lifting, directorios y GBP.

- **Techo del punto 1:** con 3.27% (Sculptra) serían +150. No lo uso porque esas consultas están en posición 10.3 y no en 5.7.
- **Riesgo del punto 1:** los títulos no resuelven AI Overviews ni la expectativa de precio. Si en 14 días el grupo no pasa de 1%, el problema no es el título.

**Orden sugerido:**
1. Normalizar la URL UTM en `seo/gsc_report.py` y `seo/cannibalization.py`, antes de la lectura del 3 al 10 nov.
2. Cerrar esa lectura y correr el lote de títulos.
3. Revisar por qué faltan Fragmentos de producto en 5 páginas.
4. Comprar un mes de OpenSEO y validar volumen.
5. Ampliar Botox, luego Hilos y Rellenos.
6. Página de lifting y armonización en inglés.

**Prioridades que cambio respecto al reporte del 2 nov:** Hollywood Peel baja de 1 a seguimiento. "Rellenos casi no aparece" pasa a "aparece poco y sube".

## 9. Qué no se pudo hacer

- **OpenSEO:** sin API key de DataForSEO. Por tanto, sin volumen, dificultad, backlinks, grid de Maps ni detección de AI Overviews.
- **SERP real de Google:** Firecrawl no la reproduce, y las posiciones de competidores son orientativas.
- **Prueba de resultados enriquecidos:** no corrida.
- **Causa de la caída de Hollywood Peel:** no confirmada.
- **Citas o ingresos:** sin dato. Las estimaciones son clics, no pacientes.
- **Scripts del repo:** no corrí `gsc_report.py` ni `cannibalization.py` tal cual porque sobrescriben `seo/gsc-data/` y reescriben el token. Usé un script temporal de solo lectura en `/tmp`, con las mismas llamadas a la API. Nada se escribió en el repo aparte de este archivo.
