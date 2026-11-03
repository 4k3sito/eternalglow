# Estudio 04: lectura de diseño de las páginas de Eternal Glow

Fecha: 2026-11-02. Insumo: 23 capturas de producción en `screenshots/` (1440×900 escritorio, 390×844 móvil a 2×), tomadas el mismo día. Complementa el estudio de Mobbin (02) y el de competidores (03): aquí se revisa **lo que ve el usuario al llegar**, no el patrón ideal.

**Cómo leerlo.** **[M]** es lo que se ve en la captura o en el código del tema. **[I]** es interpretación de diseño. **[E]** es un efecto estimado, con la lógica a la vista. Las capturas son sólo del primer pantallazo: no cubren scroll, formularios enviados ni el flujo dentro de AgendaPro.

## Resumen: cinco cosas que están costando clics o citas

1. **El hero móvil de tres hubs empuja precio y CTA fuera de la primera pantalla** [M]. Botox, Hilos y Sculptra muestran titular, foto grande y texto; el precio no aparece y el botón queda debajo del pliegue. Rellenos y Labios ya hacen lo correcto.
2. **La fila de credenciales se desborda en móvil y corta al doctor** [M]. En home ES/EN, la tercera columna ("Dr. Bruno Ocampo / Cédula profesional…") se sale del viewport y queda inalcanzable: el navegador recorta con `overflow-x: hidden`. Es el diferenciador que el estudio 03 pide explotar.
3. **El precio no es un elemento escaneable en los hubs** [M]. En Botox, Sculptra y Rellenos vive dentro de un párrafo; en Hilos no aparece en el primer pantallazo. Quien llega de "hilos tensores precio" (683 impresiones, 0.44% de CTR) no ve un número.
4. **La barra fija móvil es genérica** [M]. Dice "Reservar / WhatsApp" en todas las páginas, sin tratamiento ni precio. Es el último punto de decisión y no lleva el contexto que sí lleva WhatsApp.
5. **El contador de reseñas está viejo: 198 en el sitio contra 213 en Maps** [M]. En una comparación donde el rival muestra 785, el número equivocado resta y además se ve en todas las páginas.

Ninguna de las cinco requiere rediseño: son cambios de orden, de jerarquía y de datos.

## Lo que ya funciona y no hay que tocar

| Elemento | Evidencia | Por qué conservarlo |
|---|---|---|
| Sistema tipográfico y paleta | Home, hubs y servicio | Se lee como clínica boutique, no como landing genérica. Es el activo de marca. |
| Hero de Rellenos y Labios en móvil | `rellenos-mobile`, `labios-mobile` | H1 → beneficio con precio → dos CTA → calificación → foto. Es el orden que deberían copiar los demás hubs. |
| Páginas `/en/` | `en-home`, `en-botox`, `en-sculptra` | Precio, CTA y prueba social en el primer pantallazo. El mejor CTR del sitio (7.52% en `/en/`). |
| Ficha de precio en páginas de servicio | `servicio-armonizacion`, `servicio-bruxismo` | "Precio / Desde $X" arriba, antes del texto. Responde la intención de precio de inmediato. |
| Barra de prueba en escritorio | Home, Rellenos, Labios | 5.0, COFEPRIS y cédula en una línea. Da confianza sin discurso. |
| Secciones de riesgos y contraindicaciones | Hubs | Ventaja de confianza frente a Juvenalia y FaceUp (03). No esconderlas. |
| Dos CTA (agenda + WhatsApp) | Toda la muestra | WhatsApp pesa en México; el estudio 02 recomienda no quitarlo. |

## Hallazgos, por prioridad

### P0-1. La fila de credenciales se corta en móvil

**Qué se ve.** En `home-mobile-fold.png` y `en-home-mobile-fold.png`, la tercera columna empieza en "Dr. Bruno Ocampo" y termina fuera de la imagen; "Cédula profesional 13493041" también queda cortada. En la home en español, "198 reseñas en Google" se parte en cuatro renglones dentro de una columna de ~150 px.

**Causa [M].** `assets/scss/_home.scss`, `.lp-proof`:

```scss
.lp-proof{
  display: grid;
  grid-template-columns: repeat(3, auto);
  ...
  strong{ white-space: nowrap; }
}
```

No hay breakpoint para móvil. Con tres columnas `auto`, separadores de 16–28 px y `white-space: nowrap`, el ancho mínimo supera los 390 px. `main.scss` recorta con `overflow-x: hidden`, así que no hay scroll horizontal: la información simplemente desaparece.

**Arreglo.** Debajo de 576 px, dos columnas (rating + COFEPRIS) y el doctor a todo lo ancho; permitir que `strong` parta:

```scss
@media (max-width: 575.98px){
  .lp-proof{
    grid-template-columns: 1fr 1fr;
    strong{ white-space: normal; }
    li:nth-child(3){
      grid-column: 1 / -1;
      padding-left: 0;
      margin-left: 0;
      box-shadow: 0 -1px 0 var(--lp-line);
    }
  }
}
```

**Impacto [E].** Recupera la cédula y el nombre del médico en el 100% de las visitas móviles (89% de las impresiones de GBP son móviles). Es el argumento de confianza que el estudio 03 propone como posicionamiento. Es un cambio de 10 líneas de SCSS.

### P0-2. En tres hubs, el primer pantallazo móvil no tiene precio ni CTA

**Qué se ve [M].**

| Página | Orden en móvil | ¿Precio en el fold? | ¿CTA en el fold? |
|---|---|---|---|
| `/botox-cdmx/` | H1 → foto circular grande → párrafo | No (sólo dentro del párrafo) | No |
| `/hilos-tensores-cdmx/` | H1 → foto circular grande → párrafo | **No aparece en la página** | No |
| `/sculptra-cdmx/` | H1 → foto → párrafo → CTA | No | Queda tapado por la barra fija |
| `/rellenos-cdmx/` | H1 → párrafo con precio → CTA → rating → foto | Sí | Sí |
| `/relleno-de-labios-cdmx/` | Igual que Rellenos | Sí | Sí |
| `/en/botox-mexico-city/`, `/en/sculptra-mexico-city/` | H1 → párrafo con precio → CTA → rating → foto | Sí | Sí |

**Causa [M].** Los hubs ES se arman con shortcodes de WPBakery en `seo/*.php`. En Sculptra, por ejemplo, el bloque móvil inserta la imagen **antes** del texto:

```
[jss-heading H1]
[jss-image responsive="d-block d-md-none"]
[jss-text párrafo]
[jss-button-arrow CTA]
```

En Rellenos y Labios la imagen móvil va después del CTA. No es un problema de CSS: es el orden del contenido.

**Arreglo.** Mover el bloque `jss-image ... d-block d-md-none` después del `jss-button-arrow` en Botox, Hilos y Sculptra (y en el resto de hubs con el mismo patrón). Alternativa sólo-CSS, si no se quiere tocar contenido: ocultar la foto en móvil (`d-none d-md-block`) hasta que exista una versión compacta. La primera opción es mejor: conserva la foto y arregla la jerarquía.

**Ojo:** estos cambios van en scripts de contenido que reescriben `post_content`. Requieren confirmación aparte y respaldo, como dice `CLAUDE.md`.

**Impacto [E].** En Botox e Hilos, el usuario deja de recorrer ~700 px antes de ver una acción. En Sculptra, el CTA deja de quedar bajo la barra. Es la corrección de mayor efecto por hora de trabajo del plan.

### P0-3. El precio no se ve como dato en los hubs

**Qué se ve [M].**
- Botox: "Cuesta desde $165 MXN por unidad: Baby Botox… $4,499 y Full Face… $8,499" dentro de un párrafo de 6 líneas.
- Rellenos y Labios: mismo patrón, precio dentro del párrafo.
- Hilos: no hay precio en el primer pantallazo; el hub lo tiene en la tabla, más abajo.
- Páginas de servicio: sí hay ficha "Precio / Desde $6,499 MXN", pero el H1 va **después** de la ficha.

**Por qué importa [M].** Las consultas de precio sin Sculptra suman 5,660 impresiones/mes y 0.59% de CTR; Hilos es el tema grande peor medido (0.22%). El estudio 01 concluye que la palanca es la intención de precio.

**Arreglo.** Un componente único, arriba del CTA en todos los hubs:

```
Desde $1,100 por hilo · 1 a 4 hilos $1,500 c/u
Agendar valoración →
```

- Hilos: "Desde $1,100 por hilo" + "¿Cuántos necesito?".
- Botox: "Baby Botox $4,499 · Full Face $8,499 · $165/unidad".
- Sculptra: "Vial $14,999 · 3 viales $33,000".
- Armonización: rango por jeringa (2 por $11,000, 3 por $16,000, 4 por $20,000), no sólo "$6,499".

En servicio, mover el H1 arriba de la ficha y dejar la ficha inmediatamente después: se conserva el precio en el primer pantallazo sin romper la jerarquía del documento.

**Impacto [E].** Es el cambio que ataca el 0% de clics de "armonización facial precio" (784 impresiones) y el 0.44% de Hilos. Se mide con CTR de GSC y con `cta_click` de la ficha.

### P1-4. La barra fija móvil no lleva contexto

**Qué se ve [M].** `footer.php` imprime `eg_cta_book('barra', $t['cta_bar'])` con la etiqueta "Reservar" y un WhatsApp con "WhatsApp". No hay tratamiento, precio ni duración. La barra se ve en Botox, Sculptra, Hilos y Tratamientos; en las capturas de Rellenos y Labios no aparece, probablemente porque se activa al hacer scroll [I].

**Arreglo.**
1. Pasar a la barra el tratamiento de la página (`eg_treatment_for()` / `eg_service_hub()`) y su precio.
2. Etiqueta principal "Agendar valoración"; línea pequeña "Sculptra · desde $14,999" cuando exista precio.
3. Mantener WhatsApp como icono de 44 px con `aria-label`, no como segundo botón del mismo peso (Mobbin 02: dos acciones iguales son raras).
4. Medir `cta_location=barra` antes y después.

**Impacto [E].** Sube la tasa de clic en el último punto de decisión; no se puede estimar sin línea base de `cta_click`.

### P1-5. Reseñas: 198 contra 213

**Qué se ve [M].** Home, Rellenos, Labios, EN y hubs muestran "5.0 · 198 reseñas en Google". Maps tiene 213. El dato sale de la opción `trustindex-google-page-details`, que el tema lee en `includes/_home.php`; no está en los PHP ni en los scripts, por eso no se actualiza con el deploy.

**Arreglo.** Generar el número con `seo/gbp_reviews.py` desde la Reviews API (`includes/google-reviews.json`) y dejar Trustindex como respaldo mientras se verifica; después, desactivar el plugin. La API ya devuelve 5.0 y 213.

**Impacto [E].** Credibilidad inmediata y coherencia con la ficha de Google. Es el dato que el usuario compara con los 785 de Juvenalia.

### P1-6. Tratamientos: la fila parece "ver más", no "agendar"

**Qué se ve [M].** En `tratamientos-desktop` y `-mobile`, cada fila termina en precio + chevron `›`; no hay botón. En móvil, los filtros ("Todos 35 / Botox 16 / Rellenos 9 / …") se cortan a la derecha sin degradado ni indicio de scroll. El enlace "Guía de Botox en CDMX" queda alineado a la derecha, separado del título de la sección.

**Arreglo.**
- Cambiar el chevron por un botón pequeño "Agendar" que abra la misma ficha `data-sheet` (ya existe), sin tocar el flujo.
- Añadir duración a `eg_treatments()` y mostrarla bajo el precio.
- Poner un degradado/fade al final de la fila de filtros y `scroll-snap` suave.
- Mover "Guía de Botox en CDMX" al encabezado de la sección, no a una línea propia.

**Impacto [E].** Convierte una página de catálogo (180 impresiones, 0 clics en el periodo) en una página de decisión. Bajo esfuerzo, medible con `data-cta`.

### P2-7. Contacto no ofrece reservar en línea

**Qué se ve [M].** En `contacto-desktop` y `-mobile`, el primer pantallazo es título, mapa y tarjeta de datos; el único CTA es "Escríbenos por WhatsApp". No hay "Reservar en línea", aunque existe AgendaPro. El formulario titula "Obtén tu análisis facial digital sin costo" y usa campos tipo textarea para preguntas de una línea ("¿Cómo sientes tu piel?"). En móvil hay un hueco grande entre "Contacto" y el mapa.

**Arreglo.**
- Añadir "Reservar en línea" junto a WhatsApp, arriba de la tarjeta.
- Cambiar los cuatro campos abiertos por `select` o `input` cortos; dejar un solo textarea para "¿Qué te gustaría mejorar?".
- Subir horario, teléfono y dirección antes del mapa; bajar la altura del mapa en móvil.
- Revisar el hueco bajo el título (probablemente un hero vacío o un `margin` de WPBakery).

**Impacto [E].** Contacto suele ser la segunda página de cierre; hoy manda a WhatsApp a quien ya decidió reservar.

### P2-8. Dos sistemas de hero conviviendo

**Qué se ve [M].**

| Familia | Páginas | Hero |
|---|---|---|
| A | Home, Botox, Hilos, Sculptra | Titular serif enorme (46–92 px), foto circular, mucho aire |
| B | Rellenos, Labios, EN | Titular más contenido, foto rectangular, CTA y rating arriba, foto después |

La familia A es más editorial; la B muestra antes el precio y la acción, que es lo que pide el estudio 01 [I]. No hay que borrar A: hay que **adoptar el orden de B** (texto → precio → CTA → prueba → foto) y conservar la tipografía grande sólo en la home.

**Impacto [I].** Menos variabilidad entre páginas, menos decisiones de diseño por página y un fold móvil predecible.

### P2-9. Nosotros: credenciales bien, retrato con recorte visible

**Qué se ve [M].** `nosotros-desktop`: lista de formación, cédula y CTA junto al texto, exactamente lo que pide Mobbin 02. Pero el retrato es un recorte sobre círculo rosa con bordes irregulares en el pelo y el cuello; la calidad de imagen no está a la altura del resto del sitio.

**Arreglo.** Foto profesional con fondo real (no recorte), misma proporción en home y nosotros, y una línea "Consulta en español e inglés" —dato que ya existe en el hero y que el público expat busca—. Mantener cédula y COFEPRIS visibles.

### P2-10. Servicio: la ficha de precio promete más de lo que puede

**Qué se ve [M].** `servicio-armonizacion` muestra "Precio / Desde $6,499 MXN" y debajo "El plan y la cantidad se definen en tu valoración médica". Para armonización, el usuario que busca precio quiere el costo total; el estudio 01 documenta 0 clics de 784 impresiones y competidores que muestran "$15,000 a $45,000".

**Arreglo.** En la ficha, tabla corta de paquetes (1, 2, 3, 4 jeringas) con el total, y una nota de que el plan se confirma en valoración. Mismo tratamiento para Hilos (por número de hilos) y Sculptra (por viales).

### P3-11. Detalles que suman poco pero se ven

- **Tipografía móvil.** H1 a 46 px hace titulares de 6 líneas ("Hilos tensores en CDMX: Eleva, redefine y rejuvenece sin cirugía"). Bajar a `clamp(34px, 9vw, 46px)` en ≤575 px y el cuerpo a 17 px gana una línea de fold.
- **Rating repetido.** "198 reseñas" aparece en el hero, en el hub y en el cierre. Un solo número correcto en todos.
- **Filtros de Tratamientos.** Ya cubierto en P1-6.
- **Botones de 44 px.** La barra fija y el WhatsApp flotante cumplen; revisar los chevrones de las filas, que son el único control de 16 px.

## Ficha por página

Nota de 1 a 5: 5 = se entiende y se puede agendar sin esfuerzo. Es juicio de diseño sobre el primer pantallazo, no una métrica.

| Página | Claridad | Confianza | Agendar | Nota | Comentario |
|---|---|---|---|---|---|
| Home ES | 4 | 5 | 4 | **4.0** | Mejor hero del sitio en escritorio; en móvil pierde la cédula por el desborde. |
| Home EN | 4 | 4 | 5 | **4.3** | Precio y CTA claros; mismo bug de credenciales y "198". |
| Botox hub | 3 | 4 | 2 | **2.7** | Precio escondido en párrafo; en móvil no hay CTA en el fold. |
| Sculptra hub | 4 | 4 | 3 | **3.5** | El mejor contenido; el CTA móvil queda bajo la barra y el precio no está arriba. |
| Hilos hub | 3 | 3 | 2 | **2.5** | Sin precio en el primer pantallazo; el peor CTR del sitio. |
| Rellenos hub | 5 | 4 | 5 | **4.5** | El modelo a copiar: precio, CTA, rating y foto en orden. |
| Labios hub | 4 | 4 | 5 | **4.3** | Precio y CTA claros; la comparación antes/después comunica bien. |
| Tratamientos | 4 | 3 | 2 | **2.8** | Catálogo útil, pero las filas no invitan a agendar y los filtros se cortan. |
| Servicio (armonización) | 4 | 4 | 4 | **3.8** | Ficha de precio excelente; el "desde" sin paquetes no responde el costo total. |
| Servicio (bruxismo) | 4 | 4 | 4 | **4.0** | Precio y H1 claros; falta CTA en el fold. |
| Contacto | 3 | 4 | 2 | **2.7** | Sólo WhatsApp; formulario con campos que parecen textarea. |
| Nosotros | 5 | 5 | 4 | **4.5** | Credenciales y cédula; retrato con recorte mejorable. |

**Promedio simple: 3.6/5.** El sitio no tiene un problema de marca ni de contenido: tiene un problema de **orden en el primer pantallazo móvil** y de **precio como dato**.

## Qué probar (A/B y medición)

| Prueba | Hipótesis | Métrica | Ventana |
|---|---|---|---|
| Fila de credenciales corregida | Sube la confianza y el clic en el CTA del hero | `cta_click` en `hero` | 14 días |
| Precio visible arriba del CTA | Sube el CTR de GSC y el clic en la ficha | CTR GSC + `cta_click` | 28 días |
| Barra con "Sculptra · desde $X" | Sube el clic de la barra | `cta_click` en `barra` | 14 días |
| Fila de Tratamientos con botón | Sube el clic en filas y la ficha | `cta_click` en `fila` | 14 días |
| Etiqueta "Agendar valoración" vs "Reservar" | "Valoración" baja la presión en servicios médicos | `cta_click` | 14 días |

## Límites

- **Sólo primer pantallazo.** No se revisó scroll, menú móvil abierto, footer, ficha `#lp-sheet` ni el flujo dentro de AgendaPro.
- **Capturas, no dispositivo real.** El desborde de `.lp-proof` se confirmó en el CSS; aun así conviene verificarlo en un teléfono antes de cerrar el bug.
- **Sin datos de conversión.** Las notas y los impactos son juicio de diseño; la validación es `cta_click` y citas en AgendaPro.
- **El contador "198"** viene de Trustindex; no se cambió nada en producción.
- **No se propone un rediseño.** El estudio 02 ya advierte que rehacer lo que funciona es el error caro.
