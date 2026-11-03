# Plan de ataque: más CTR, más tráfico y más citas

Eternal Glow · 2026-11-02 · Une los estudios 01 (SEO/OpenSEO), 02 (Mobbin), 03 (competencia) y 04 (capturas).

**Regla de lectura.** **[M]** es dato medido y con fuente en los estudios. **[E]** es estimación con la fórmula a la vista. **[I]** es hipótesis. Las cifras de clics son de Search Console; no hay dato de citas ni de ingresos.

---

## 1. Resumen para decidir

**Dónde estamos.** El tráfico ya creció: 436 clics en 19 días (22.9/día), +64% por día contra el periodo anterior. El CTR no se movió: 2.15%, y sin marca es 1.0%. La posición mejoró a 8.2. La home perdió su brillo orgánico al separarse el tráfico de GBP (posición 24.3 en la última semana) y el hub de Botox sigue en 31.8 para "botox cdmx" [M: 01].

**El diagnóstico en una frase.** No falta tráfico: falta que el resultado se gane el clic y que la página responda la intención de precio en el primer pantallazo. La prueba está en Sculptra: es el único hub profundo (3,634 palabras), el único con "precio" en el título y el único con 3.27% de CTR en consultas de precio. Todo lo demás es copiar ese modelo [M/I: 01].

**Las cinco palancas.**

| # | Palanca | Qué mueve | Evidencia |
|---|---|---|---|
| 1 | **CTR en Google** | Títulos, metas, fragmentos de producto | 5,660 impresiones/mes de precio sin Sculptra a 0.59% [M] |
| 2 | **Precio en la página** | Que el número esté arriba y sea total, no "desde" | Armonización: 784 impresiones, 0 clics [M] |
| 3 | **Confianza local** | Reseñas, GBP, credenciales visibles | 213 reseñas contra 785 y 589 de los vecinos [M] |
| 4 | **Fricción de agenda** | Contexto hasta AgendaPro y barra móvil | Todos los "Agendar" van a la misma URL [M] |
| 5 | **Profundidad y autoridad** | Botox, Hilos, Rellenos, inglés | Botox 1,543 palabras contra 3,634 de Sculptra [M] |

**La meta a 90 días.**

| Métrica | Hoy | 30 días | 60 días | 90 días |
|---|---|---|---|---|
| Clics/mes | ~690 [E] | 710–730 | 740–780 | **750–840** |
| CTR | 2.15% [M] | 2.25–2.35% | 2.4–2.55% | **2.5–2.8%** |
| Clic a "Agendar" | sin línea base | +10–15% | +20–30% | **+25–40%** |
| Reseñas Google | 213 [M] | 225 | 245 | **260–270** |
| Llamadas GBP | 3–7/semana en las últimas 3 semanas [M] | 5–6 | 6–8 | **8–10** |

Los clics salen del plan del estudio 01 (+60 a +150/mes, +9% a +22%). El resto es meta de trabajo, no promesa: se mide contra la tendencia de 4 semanas, porque no hay año anterior comparable en GSC.

**Inversión.** $10 USD por un mes de OpenSEO (hasta $25 si se suman consultas sueltas a DataForSEO) y, si la clínica quiere, producción de foto y revisión legal de antes/después. **No hay gasto en anuncios en este plan.**

---

## 2. Punto de partida

| Dato | Valor | Fuente |
|---|---|---|
| Clics | 436 en 19 días (22.9/día) | GSC, 13–31 oct [M] |
| Impresiones | 20,255 (1,066/día) | GSC [M] |
| CTR | 2.15% (sin marca ~1.0%) | GSC [M] |
| Posición media | 8.2 | GSC [M] |
| Móvil / escritorio | posición 7.2 / 11.6 | GSC [M] |
| México / EE. UU. | CTR 1.8% / 7.2% | GSC [M] |
| GBP | 27,743 impresiones, 901 clics (3.2%), 0 reservas | GBP, 2 ago–30 oct [M] |
| Reseñas | 5.0 · 213; ritmo 3.2/mes (jun–oct) | Maps + GBP [M] |
| Páginas indexadas | 10 de 10, sin errores | URL Inspection [M] |
| Citas | **sin dato** | — |

**Lo que no se puede medir todavía:** citas completadas en AgendaPro, ingresos, tasa de conversión real del sitio. El plan arranca midiendo eso, no estimándolo.

---

## 3. Estrategia: el embudo completo

```
Google (CTR)  →  página (precio + confianza)  →  clic a agendar  →  cita en AgendaPro
    ↑                    ↑                              ↑
 títulos/metas      fold móvil, precio,           contexto del tratamiento,
 snippets           credenciales, reseñas        barra fija, ficha
```

Cada sprint ataca un tramo. No se toca el mismo elemento dos veces sin medir.

**Principio de diseño.** El orden que ya funciona en Rellenos, Labios y `/en/`:

> titular → beneficio con precio → CTA → prueba (5.0, COFEPRIS, cédula) → foto

Los hubs que no convierten (Botox, Hilos, Sculptra) invierten ese orden en móvil: foto grande antes de precio y CTA.

---

## 4. Sprint 0 — medir y tapar fugas (3 al 9 de noviembre)

Objetivo: que la lectura del 10 de noviembre sea confiable y que ningún arreglo barato espere.

| # | Acción | Detalle | Dueño | Esfuerzo | KPI |
|---|---|---|---|---|---|
| 0.1 | Normalizar la URL UTM de GBP | `seo/gsc_report.py` y `seo/cannibalization.py` deben sumar `/?utm…gbp` con `/` | Dev | 2 h | Reporte sin falso positivo |
| 0.2 | Arreglar `.lp-proof` en móvil | 2 columnas + doctor a todo lo ancho; ver 04, P0-1 | Dev | 1 h | Cédula visible en 390 px |
| 0.3 | Actualizar 198 → 213 | Opción `trustindex-google-page-details`; definir refresco mensual | Ops | 30 min | Número igual a Maps |
| 0.4 | Reordenar el hero móvil | Botox, Hilos y Sculptra: foto después del CTA (04, P0-2) | Contenido | 4 h | Precio y CTA en el fold |
| 0.5 | Ficha de precio en hubs | "Desde $X" arriba del CTA; Hilos y Armonización con paquetes | Contenido | 4 h | 4 hubs con precio escaneable |
| 0.6 | Barra fija con contexto | Tratamiento + precio + "Agendar valoración"; WhatsApp como icono | Dev | 3 h | `cta_click` con `barra` |
| 0.7 | "Reservar en línea" en Contacto | Junto a WhatsApp; arreglar campos del formulario | Dev | 2 h | CTA en el fold |
| 0.8 | Etiquetar la salida a AgendaPro | UTM en `eg_booking_url()` y pedir a la clínica el export de citas | Dev + Ops | 1 h | Citas por semana |
| 0.9 | Línea base del 3 al 10 de noviembre | Correr GSC y cannibalization con la URL normalizada | Analista | 1 h | Tabla base firmada |

**Criterio de cierre:** 0.1, 0.2, 0.3 y 0.8 no dependen de la clínica. 0.4 y 0.5 tocan `post_content` y **requieren confirmación aparte** (regla del repo). 0.6 y 0.7 son tema.

> **Por qué la prisa:** el corte del 3 al 10 de noviembre es la última lectura limpia antes de Buen Fin. Si se corre después, la promo contamina el CTR.

---

## 5. Sprint 1 — CTR y confianza (10 al 23 de noviembre)

Objetivo: subir el clic en Google y cerrar la brecha de prueba social.

| # | Acción | Detalle | Dueño | Esfuerzo | KPI |
|---|---|---|---|---|---|
| 1.1 | Lote de títulos y metas | 8 páginas del estudio 01, sección 4. **Entra el 18 nov**, después de Buen Fin, para no mezclar efectos | Contenido | 3 h | CTR por página a 14/28 días |
| 1.2 | Fragmentos de producto | Igualar `Offer` en las 5 páginas que no lo tienen; comparar con las 5 que sí | Dev | 3 h | Impresiones de producto |
| 1.3 | Buen Fin / MSI | Barra con vigencia y meses sin intereses, **sólo si la clínica lo aprueba** | Diseño + Ops | 4 h | Clics y citas 13–17 nov |
| 1.4 | Campaña de reseñas | Enlace o QR al salir de cada cita; meta 15/mes; sin incentivos | Ops | continuo | Reseñas nuevas/mes |
| 1.5 | GBP: chat y reservas | Activar los dos; descripción bilingüe; nombres de servicios con las búsquedas reales | Ops | 2 h | Llamadas y rutas |
| 1.6 | Tratamientos: fila con botón | Cambiar chevron por "Agendar" que abre la ficha; fade en filtros | Dev | 4 h | `cta_click` en fila |
| 1.7 | Contacto: formulario | `select`/`input` cortos; un solo textarea | Dev | 2 h | Envíos completados |

**Por qué el lote de títulos espera al 18:** Buen Fin mueve el CTR de todo el sitio. Si los títulos entran el 10 y la promo el 13, no se sabrá qué funcionó. Sculptra no cambia de título: es el grupo de control del CTR (sus ajustes de móvil y precio son de otra prueba).

**Meta del sprint:** CTR global +0.10 a +0.15 puntos; 8 a 12 reseñas nuevas; cero páginas con el contador viejo.

---

## 6. Sprint 2 — profundidad y autoridad (24 de noviembre al 14 de diciembre)

Objetivo: que los hubs dejen de ser páginas delgadas y que el inglés deje de perder contra la home.

| # | Acción | Detalle | Dueño | Esfuerzo | KPI |
|---|---|---|---|---|---|
| 2.1 | Hub de Botox al nivel de Sculptra | Tabla de precio por zona (requiere dato de la clínica), `MedicalProcedure`, FAQ, 1,000+ palabras nuevas | Contenido | 2–3 días | Posición de "botox cdmx" |
| 2.2 | Hub de Hilos | "¿Cuántos hilos necesito?", ejemplos de costo total, antes/después | Contenido | 2 días | CTR de Hilos |
| 2.3 | Hub de Rellenos | Profundidad y marca del producto confirmada | Contenido | 1–2 días | CTR de Rellenos |
| 2.4 | Página de lifting sin cirugía | Hilos vs HIFU vs Sculptra; validar volumen antes | Contenido | 1 día | Impresiones nuevas |
| 2.5 | Inglés: `facial balancing` | `/en/facial-balancing-mexico-city/`; enlaces internos para que `/en/` gane a la home ES | Contenido + Dev | 1 día | Posición EN |
| 2.6 | OpenSEO, un mes | $10 USD: volumen de los términos del 01, backlinks de Juvenalia/Trapassi, AI Overviews | Analista | 1 día | Decisiones con volumen |
| 2.7 | Directorios | Multiestética; Bookimed sólo si el costo tiene sentido | Ops | 2 h | Referidos |

**Meta del sprint:** Botox en el top 10 de "botox cdmx" o al menos CTR >1.2%; Hilos >0.8%; 2 páginas nuevas indexadas.

---

## 7. Sprint 3 — prueba social y cierre (15 de diciembre al 15 de enero)

Objetivo: convertir la confianza que ya existe en citas, y dejar el sistema en piloto automático.

| # | Acción | Detalle | Dueño | Esfuerzo | KPI |
|---|---|---|---|---|---|
| 3.1 | Antes y después con contexto | Tratamiento, jeringas, tiempo, "resultados varían", reseña en la misma tarjeta | Diseño + Contenido | 3 días | `cta_click` tras el carrusel |
| 3.2 | Consentimiento y revisión legal | Fotos de pacientes y publicidad médica (COFEPRIS) | Clínica | externo | Luz verde por escrito |
| 3.3 | FAQ por momento de decisión | Precio, primera visita, dolor, resultados; primera abierta | Contenido | 1 día | Tiempo en página |
| 3.4 | Doctor con CTA en hubs | "Te atiende el Dr. Bruno Ocampo" + foto + "Agendar" | Dev | 2 h | `cta_click` en doctor |
| 3.5 | Prueba A/B de etiqueta | "Agendar valoración" vs "Reservar en línea" | Dev | 1 h | `cta_click` |
| 3.6 | Testimonios en video | Sólo con consentimiento y producción | Clínica | semanas | Citas asistidas |

**Meta del sprint:** +10% en la tasa de clic a agendar; 260+ reseñas; reporte trimestral con línea base completa.

---

## 8. Backlog priorizado (ICE)

ICE = Impacto × Confianza × Facilidad, cada uno de 1 a 5. Máximo 125. Ordena el arranque, no sustituye la secuencia por dependencias.

| # | Acción | I | C | F | ICE | Sprint |
|---|---|---|---|---|---|---|
| 1 | Arreglar `.lp-proof` móvil | 4 | 5 | 5 | **100** | 0 |
| 2 | Reordenar hero móvil (3 hubs) | 5 | 4 | 4 | **80** | 0 |
| 3 | Precio visible arriba del CTA | 5 | 4 | 4 | **80** | 0 |
| 4 | Normalizar URL UTM | 3 | 5 | 5 | **75** | 0 |
| 5 | Actualizar 198 → 213 | 3 | 5 | 5 | **75** | 0 |
| 6 | Barra móvil con contexto | 4 | 4 | 4 | **64** | 0 |
| 7 | Contacto con reserva en línea | 4 | 4 | 4 | **64** | 0 |
| 8 | Títulos y metas (8 páginas) | 5 | 3 | 4 | **60** | 1 |
| 9 | Reseñas 15/mes | 5 | 4 | 3 | **60** | 1 |
| 10 | OpenSEO un mes | 3 | 4 | 5 | **60** | 2 |
| 11 | Fragmentos de producto | 3 | 4 | 3 | **36** | 1 |
| 12 | GBP chat/reservas | 3 | 3 | 4 | **36** | 1 |
| 13 | Tratamientos con botón | 3 | 3 | 4 | **36** | 1 |
| 14 | Buen Fin/MSI | 4 | 3 | 3 | **36** | 1 |
| 15 | EN facial balancing | 4 | 3 | 3 | **36** | 2 |
| 16 | Botox hub profundo | 5 | 3 | 2 | **30** | 2 |
| 17 | Doctor con CTA | 2 | 3 | 5 | **30** | 3 |
| 18 | Hilos profundo | 4 | 3 | 2 | **24** | 2 |
| 19 | Antes/después con contexto | 4 | 3 | 2 | **24** | 3 |
| 20 | FAQ por decisión | 2 | 3 | 4 | **24** | 3 |
| 21 | Directorios | 2 | 3 | 4 | **24** | 2 |
| 22 | Página de lifting | 3 | 2 | 2 | **12** | 2 |

**Lectura:** las cinco primeras son de horas, no de semanas, y tres son bugs. Si sólo se hace una cosa esta semana, que sea la fila de credenciales y el hero móvil.

---

## 9. Medición

### Tablero semanal (15 minutos, lunes)

| Métrica | Fuente | Dueño | Umbral de alerta |
|---|---|---|---|
| Clics, impresiones, CTR, posición | GSC (`seo/gsc_report.py`) | Analista | CTR −0.2 pp en 2 semanas |
| Clics por página y consulta | GSC, URL normalizada | Analista | Caída >20% en una página |
| `cta_click` por `cta_location` | GA4 | Dev | Caída >15% |
| Clics y llamadas GBP | `seo/gbp-data/` | Ops | 2 semanas seguidas abajo |
| Reseñas nuevas | Maps | Ops | <8 en el mes |
| Citas | AgendaPro (export manual) | Clínica | Sin dato = bloqueo |

### Ventanas de evaluación

- **Base:** 23 sep–12 oct y 13–31 oct (estudio 01).
- **Corte limpio:** 3–10 nov, antes de Buen Fin.
- **Títulos:** entran 18 nov; primera lectura 2 dic (14 días), segunda 16 dic (28 días).
- **Hubs nuevos:** 28 días después de indexar; no juzgar antes.
- **Regla de muestra:** menos de 100 impresiones no prueba nada. Se agrupa por tema.

### Criterios para cambiar de rumbo

| Si pasa esto | Entonces |
|---|---|
| El lote de títulos no sube el CTR a 1% en 14 días | No es el título: hay AI Overview o expectativa de precio. Revisar SERP y página. |
| La campaña de reseñas no llega a 8/mes | Cambiar el momento y el canal de la solicitud; no subir incentivos. |
| El arreglo móvil no mueve `cta_click` | El problema no es el fold: es la confianza o la oferta. Revisar la ficha. |
| Botox no sube tras el hub | Competir por zona/precio, no por volumen de texto. |

---

## 10. Presupuesto

| Concepto | Costo | Cuándo | Nota |
|---|---|---|---|
| OpenSEO alojado, 1 mes | $10 USD (~190 MXN) | Nov | Incluye $10 de uso; decisión del usuario |
| DataForSEO (si se self-hostea) | $5–15 USD | Opcional | Sólo si OpenSEO se queda |
| Tarjetas QR de reseñas | $500–1,000 MXN | Nov | Impresión local |
| Foto profesional del doctor | $3,000–8,000 MXN [E] | Dic | Cotización pendiente |
| Revisión legal de antes/después | $2,000–5,000 MXN [E] | Dic | COFEPRIS; cotización pendiente |
| **Anuncios** | **$0** | — | Fuera de alcance |

---

## 11. Decisiones que necesitamos de la clínica

Sin estas respuestas, varias acciones quedan a medias:

1. **¿La valoración es gratuita?** El sitio dice "incluye valoración médica" y "análisis facial digital sin costo\*", pero los competidores cobran $999–$1,500 abonables. Si es gratis, es el diferenciador más fuerte y debe estar en el hero y en el título.
2. **Unidades de Botox por zona**, para la tabla de precios que pide el mercado.
3. **Marca del relleno** por producto (Restylane, Juvéderm, etc.), para quitar la duda del precio bajo.
4. **Política de cambios y cancelación**, y si se pide anticipo.
5. **Buen Fin y MSI:** qué tratamientos, qué descuento, qué vigencia.
6. **Consentimiento de fotos** antes/después y revisión legal de publicidad médica.
7. **Quién solicita la reseña** y con qué enlace o QR.
8. **Acceso al export de citas de AgendaPro** y si permite enlaces por servicio.
9. **¿Se pueden correr scripts de contenido?** Reescriben `post_content` y pisan ediciones de wp-admin.
10. **Chat y reservas de GBP:** activarlos o confirmar por qué están en cero.

---

## 12. Riesgos

| Riesgo | Probabilidad | Impacto | Mitigación |
|---|---|---|---|
| AI Overviews se comen el clic informativo ("botox en los labios") | Alta | Medio | Medir; no invertir más ahí; apostar a precio y local |
| Diciembre baja la demanda estética | Alta | Medio | Leer contra tendencia de 4 semanas, no contra noviembre |
| Los títulos pierden posiciones | Media | Medio | Sculptra como control; respaldo y rollback por página |
| Los scripts pisan ediciones de wp-admin | Media | Alto | Respaldo, confirmación y correr en ventana acordada |
| La campaña de reseñas se lee como incentivo | Baja | Alto | Sin descuentos ni regalos; sólo pedir después de la cita |
| La promo de Buen Fin contamina la medición | Alta | Medio | Títulos el 18, no el 10 |
| No hay dato de citas | Alta | Alto | Export semanal de AgendaPro desde el sprint 0 |

---

## 13. Qué no hacer

- **No rediseñar.** El sistema visual funciona; el problema es el orden del fold y el precio.
- **No perseguir "faciales", limpiezas ni hidrofacial.** La clínica no los ofrece; 279 impresiones y 0 clics ya lo dicen.
- **No pelear "lip fillers in mexico"** (posición 42; mandan Bookimed y Tijuana).
- **No crear `/precios/`.** Canibaliza `/tratamientos/`, que ya tiene precios.
- **No agregar `AggregateRating` propio.** Google no muestra estrellas de reseñas propias en negocios locales.
- **No copiar "#1" ni "la mejor".** Riesgo legal y de credibilidad.
- **No comprar backlinks.**
- **No cambiar títulos y promo el mismo día.**
- **No prometer ingresos.** Sin citas en AgendaPro, todo es clic.

---

## 14. Calendario de una vista

| Semana | Qué pasa | Quién |
|---|---|---|
| 3–9 nov | Sprint 0: bugs, medición, base del 3–10 | Dev, analista |
| 10–17 nov | Sprint 1: reseñas, GBP, Tratamientos, Contacto; **Buen Fin 13–17** | Ops, dev |
| 18–23 nov | Títulos y metas; fragmentos de producto | Contenido, dev |
| 24 nov–14 dic | Sprint 2: Botox, Hilos, Rellenos, EN, OpenSEO | Contenido |
| 15 dic–15 ene | Sprint 3: antes/después, FAQ, doctor, A/B | Diseño, dev, clínica |
| Cada lunes | Tablero de 15 min | Analista |
| 2 y 16 dic | Lectura de títulos a 14 y 28 días | Analista |
| 15 ene | Reporte trimestral y decisión del siguiente ciclo | Todos |

---

## 15. Archivos de este estudio

| Documento | Contenido |
|---|---|
| `01-seo-openseo.md` | Datos reales de GSC, OpenSEO, títulos, schema, brechas |
| `02-mobbin-patrones.md` | Patrones de UI para agendar, con referencias |
| `03-competidores-google.md` | Juvenalia, Aesthetic Doctors, Renuve, precios, GBP |
| `04-capturas-diseno.md` | Lectura de las 23 capturas y arreglos concretos |
| `screenshots/` | Capturas de producción ES/EN |
| `competidores/` | Capturas de los 7 competidores |
| `mobbin/` | 12 referencias de interfaz |

**Límite general:** no hay volumen de búsqueda real, ni backlinks, ni dato de citas. Las decisiones de contenido se validan con el mes de OpenSEO y con AgendaPro; las de diseño, con `cta_click`.
