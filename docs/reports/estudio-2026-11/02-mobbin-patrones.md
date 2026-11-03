# Estudio Mobbin: patrones de UI que llevan a agendar

Eternal Glow, 2026-11-02. Parte del plan para subir CTR y agendado.

## Cómo leer este estudio

- **Mobbin muestra qué hacen los productos, no cuánto convierten.** No trae datos de conversión. El impacto de este documento es juicio mío a partir de la repetición del patrón y de la fricción que quita; hay que validarlo con los eventos `cta_click` de GA4.
- **Mobbin no tiene clínicas estéticas.** Los ejemplos más cercanos son telesalud (Hims/Hers, Function, Superpower), clínicas premium (Biograph), citas médicas (Zocdoc, CVS) y reservas de belleza (Fresha, Booksy).
- **Casi todo lo móvil viene de apps iOS.** Las capturas web son de escritorio. Lo que se traslada al sitio móvil es el patrón, no el layout exacto.

## Punto de partida: lo que el sitio ya tiene

Revisé el tema y los enlaces del home en producción. Mucho de lo básico ya está, así que el plan **no** debe rehacerlo:

| Ya existe | Dónde |
|---|---|
| Barra fija móvil con Agendar + WhatsApp | `.lp-bar`, `footer.php` |
| Ficha (`<dialog>`) al tocar un tratamiento, con precio, "Incluye valoración médica" y WhatsApp con el nombre del tratamiento | `#lp-sheet`, `bookingSheet()` en `main.ts` |
| Filas de precio, FAQ y cierre con CTA en los hubs | `template-parts/landing.php` |
| Reseñas por tratamiento, antes y después, sección del doctor | `patient-reviews.php`, `home.php`, `doctor.php` |
| Medición por ubicación del CTA | `data-cta` → evento `cta_click` |

**El hueco principal:** todos los botones de Agendar del home apuntan a la misma URL de AgendaPro (`eternalglowmexico.site.agendapro.com/mx/sucursal/340833`). WhatsApp sí conserva el tratamiento; Agendar lo pierde, y la paciente sale a otro dominio a elegir de nuevo. Los mejores flujos del estudio hacen lo contrario: arrastran el contexto hasta el final.

## Ranking por impacto en agendado vs. esfuerzo

Impacto y esfuerzo son estimados (A alto, M medio, B bajo). El orden pondera impacto sobre esfuerzo.

| # | Patrón | Impacto | Esfuerzo | Estado hoy | Tema |
|---|---|---|---|---|---|
| 1 | Reserva con el tratamiento ya elegido | A | B-M | Falta en Agendar | Flujo |
| 2 | Barra móvil con contexto: tratamiento, "desde $X" y etiqueta específica | A | B | Barra genérica | Barra fija |
| 3 | "Qué pasa después de agendar" y política de cambios, antes de salir a AgendaPro | A | B | Falta | Flujo |
| 4 | Botón Agendar visible en cada fila de precio, con duración | M-A | B | La fila es enlace con flecha | Precios |
| 5 | Antes y después con plazo, tratamiento y reseña en la misma tarjeta | A | M | Parcial | Antes y después |
| 6 | Hero con hechos de confianza bajo el CTA | M | B | Parcial (barra de prueba) | Hero |
| 7 | FAQ agrupada por momento de decisión, con salida a Agendar | M | B | FAQ lista plana | FAQ |
| 8 | Credenciales del doctor verificables y CTA junto a la biografía | M | B | Por revisar | Equipo |
| 9 | Desglose de calificación tipo Zocdoc y calificación visible cerca del CTA | M | M | Parcial | Reseñas |
| 10 | Testimonios en video de pacientes | M | A | Falta | Reseñas |
| 11 | Footer con bloque de cierre | B-M | B | Por revisar | Footer |
| 12 | Menú móvil con accesos por objetivo | B-M | M | Por revisar | Navegación |
| 13 | Horarios reales incrustados en el sitio | A | A | Falta | Flujo |

**Primera tanda (1 a 4):** son cambios de tema, sin fotos ni contenido clínico nuevo, y atacan el último tramo antes de agendar. **Medir antes:** tasa de `cta_click` por `cta_location`. Hoy no hay forma de ver desde el sitio si la reserva se completó en AgendaPro.

**No recomiendo:**

- Cuestionario largo tipo Hims antes de agendar. Tiene sentido para recetar a distancia; aquí agrega pasos y "Incluye valoración médica" ya cumple esa función.
- Tablas comparativas de planes (ej. [Patch](https://mobbin.com/sites/sections/4efafbed-700d-4f4c-b3f9-93d0689d0c8c)). Comparan paquetes, y aquí se compran tratamientos sueltos.
- Precio tachado de ancla (ej. [Function](https://mobbin.com/sites/sections/f5d5c740-7af0-4718-9893-3b8e80e39eed)). Rompe el tono médico y sólo sirve si el precio de referencia es real.
- Pago o anticipo obligatorio al reservar (ej. [Fresha](https://mobbin.com/flows/0ae100c9-f9bb-4a81-8837-772fd8ac330c)). Puede bajar inasistencias, pero también agendados en primera visita. Es decisión de la clínica.

## Patrones por tema

### 1. Hero con CTA de reserva

Ejemplos:

- [Superpower](https://mobbin.com/sites/sections/db484778-34d1-4a38-af04-5593bee2f342) (`mobbin/01-hero-superpower-web.webp`): rostro de una persona, dos CTA y tres hechos abajo, incluido "Starts at $199/year".
- [Fresha](https://mobbin.com/flows/a8f3a89f-faeb-4924-b855-1b356463d43d): el titular dice lo que puedes hacer ("Book local beauty and wellness services") y lleva un contador de citas agendadas hoy.
- [Hims](https://mobbin.com/sites/sections/2aac84d2-0606-4ed6-a558-71871c6ee181): CTA único con franja superior de promoción.

**Patrón:** promesa corta, un CTA dominante, una foto de persona y prueba inmediata debajo. El precio de entrada aparece ya en el hero en los que venden directo.

**Aplicación:** en `front-page`, conservar el hero actual y sumar debajo del CTA una línea de tres hechos: calificación real de Google, "Valoración médica incluida" y "Desde $X" del tratamiento más buscado. En los hubs, el titular repite la búsqueda ("Sculptra en CDMX") y "desde $X" queda encima del botón, porque esas visitas llegan de Google con intención concreta. Un contador sólo vale si es real; sin datos, se omite.

### 2. Flujo de reserva de cita

Ejemplos:

- [Zocdoc](https://mobbin.com/flows/fcd6461e-af19-4ca9-9864-8b21c286888c) (`mobbin/02-flujo-slots-zocdoc-ios.webp`) y [otro flujo de Zocdoc](https://mobbin.com/flows/26c741b6-0231-4040-b5e9-890f0b6db100): los horarios se ven en el perfil del doctor, "Secure Booking" arriba, y al confirmar ofrece añadir al calendario y "Prepare for appointment".
- [CVS](https://mobbin.com/screens/6ffbe483-99bf-4f36-b97b-8b7c00a1ce90) (`mobbin/03-flujo-boton-con-hora-cvs-ios.webp`): el botón lleva la decisión: "Book 9:10 AM".
- [Alan](https://mobbin.com/screens/9d51db29-b746-455b-837b-e85767403b33) (`mobbin/04-resumen-cita-alan-ios.webp`): resumen con persona, fecha, precio y "Free cancellation up to 48 hours in advance" antes de confirmar.
- [Fresha web](https://mobbin.com/flows/0ae100c9-f9bb-4a81-8837-772fd8ac330c): migas Services > Professional > Time > Confirm y resumen fijo con el total. En [iOS](https://mobbin.com/screens/e7f0bf07-0aa9-402e-9b35-182a0345ac82) ofrece lista de espera si no hay horario.
- [ClassPass](https://mobbin.com/flows/f75f704d-9854-4d95-bbf4-f732e2e57b58): reservar se resuelve en una hoja inferior, con la regla de cancelación a la vista.
- [Hims](https://mobbin.com/flows/8f5bcd73-a9f5-4001-b7dd-ff4d27af5230): pantalla "Almost done, here's what's next" con los pasos siguientes y el tiempo de respuesta.

**Patrón:** el contexto nunca se pierde (qué, con quién, cuándo, cuánto), hay pocos pasos visibles, y la política de cancelación aparece antes de confirmar.

**Aplicación:**

1. **Enlace con el tratamiento preseleccionado.** Revisar si AgendaPro permite enlazar a un servicio concreto. Si se puede, agregar en `eg_booking_url($treatment)` el mapeo por tratamiento y pasarlo a `eg_cta_book()` y a la ficha. Si no se puede, llevar el contexto en la ficha y dar prioridad a WhatsApp con el tratamiento ya escrito, que hoy sí funciona.
2. **Tres pasos en `#lp-sheet` y en los hubs:** "Eliges horario > Valoración con el doctor > Decides con él tu plan". Añadir duración, si se paga o no al reservar, y la política de cambios. Estos textos los debe confirmar la clínica.
3. **Avisar la salida.** La etiqueta del botón puede ser "Ver horarios", porque abre otro dominio en pestaña nueva.
4. **Horarios incrustados (#13).** Sólo si AgendaPro ofrece API o widget y la primera tanda no basta.

### 3. Páginas de servicio con precios

Ejemplos:

- [Hers](https://mobbin.com/screens/a5c9602d-b496-4234-aec3-d2b9a5462de4) (`mobbin/07-sticky-cta-hers-ios.webp`): "Starting at $199/mo", acordeones con cómo se usa y efectos secundarios, y "Start Consultation" fijo.
- [Airbnb Services](https://mobbin.com/flows/dc8c5061-45bc-4733-90ec-10dbf5876213): opciones con precio y duración, calificaciones del proveedor, política de cancelación y "From $31 / Reserve" siempre visible.
- [Shangri-La Circle](https://mobbin.com/screens/08a2bb0b-62ff-4136-8ea1-aee00e22f71b): acordeón por tratamiento con duración, precio y Book Now fijo.

**Patrón:** precio arriba, detalle plegado y riesgos dichos sin esconderlos, con el CTA siempre a la vista. La palabra "consulta" baja la presión de "comprar" en productos médicos.

**Aplicación:** en `single-service.php` y `landing.php`, mantener precio y duración en el primer pantallazo y plegar con `<details>` el contenido largo, dejándolo en el HTML para no perder SEO. La sección de riesgos que ya existe es una ventaja de confianza: conviene enlazarla desde el inicio de la página, no ocultarla. Probar "Agendar valoración" frente al texto actual.

### 4. Tablas de precios

Ejemplos:

- [Booksy](https://mobbin.com/flows/65274fd8-a3d6-430e-a0f0-5756ea3590ee) (`mobbin/06-lista-precios-booksy-ios.webp`): nombre, descripción corta, precio "desde", duración, botón Book en cada fila, sección "Popular Services" y buscador.
- [Fresha](https://mobbin.com/screens/3206f2b1-cf79-4213-ab4a-7cb71e3d1bb8) (`mobbin/05-lista-servicios-sticky-fresha-ios.webp`): tarjetas con duración, "from US$", botón Book y CTA fijo general.
- [Walmart](https://mobbin.com/screens/658fbc2d-814d-40e5-a621-b091cb3bbe9e): "From $26.88" con duración estimada.

**Patrón:** lista, no matriz. Cada fila es una decisión completa con su propia acción. El "desde" evita prometer un precio cerrado.

**Aplicación:** en `.lp-group`, hoy la fila termina en precio y flecha (`caret-right`), que se lee como "ver más". Cambiar la flecha por un botón pequeño "Agendar" que abra la misma ficha `data-sheet`, sin tocar el flujo, y agregar duración a `eg_treatments()`. Etiquetar "Más solicitado" en dos filas como máximo, según ventas reales. Vale un A/B simple midiendo con `data-cta`, y hay que añadir la clase nueva a `purge-tokens.txt` si PurgeCSS la quita.

### 5. Prueba social y reseñas

Ejemplos:

- [Zocdoc, perfil del doctor](https://mobbin.com/flows/4f3d0323-b3f5-4b95-8874-4d1e365041e8): "What patients are saying", con calificación total, desglose por tiempo de espera y trato, y frases como "97% of patients gave this doctor 5 stars".
- [Function](https://mobbin.com/sites/sections/2a0a617b-da38-4782-88bb-4ef73ffa1cf0): video de paciente más dos reseñas titulares.
- [Superpower](https://mobbin.com/sites/sections/0189e9ce-1d76-47e4-af1e-ba341224bd35): nombre, edad, cita y "4.6 out of 5 Trustpilot" arriba.

**Patrón:** la nota de una fuente externa va arriba, seguida de historias concretas con nombre, edad y tema. Las mejores reseñas tienen titular.

**Aplicación:** en `patient-reviews.php`, mostrar arriba la nota y el total reales de Google con enlace al perfil, y en cada tarjeta nombre o iniciales, tratamiento y titular corto tomado de la reseña. Filtrar por hub (ya están por tratamiento). Si Google da suficientes reseñas, resumir temas como "trato" o "resultado natural" sólo con conteos reales. El video (#10) depende de consentimiento y producción.

### 6. Antes y después

Ejemplos:

- [Hers](https://mobbin.com/screens/dc7b6682-eff4-4591-be82-a6d5d9fd6f62) (`mobbin/08-antes-despues-hers-ios.webp`): "Month 0 / Month 6", "Verified review" y el plazo escrito: "3 to 6 months".
- [Hims](https://mobbin.com/sites/sections/6db670d9-394c-47ce-8963-900f20f3fb75) (`mobbin/09-resenas-resultados-hims-web.webp`): carrusel con resultado en una etiqueta, dos fotos, mes de cada foto y cita de la paciente.
- [Yazio](https://mobbin.com/screens/7f05927d-90a9-4fc4-be2b-633648fb1664): nombre, edad y las dos fotos en pares. [Photoroom](https://mobbin.com/screens/581c0f2a-743c-4464-bb1a-c5ac3c2c2625) usa un deslizador, pero es una herramienta de edición.

**Patrón:** fotos lado a lado con tiempo y persona. Lo que convence es el **plazo** junto a la foto, no el efecto visual. Es el tema con menos muestra: la búsqueda devolvió mucho ruido.

**Aplicación:** cada tarjeta lleva tratamiento, cantidad (ej. jeringas), tiempo desde la aplicación y "Resultados varían". Poner dos fotos estáticas lado a lado: pesan poco, se entienden sin tocar y funcionan sin JS, mientras que un deslizador añade JS y compite con el scroll. Poner primero el caso del hub que se visita, con botón "Agendar valoración" al final del carrusel, cargar con `loading="lazy"` y dimensiones fijas para no dañar CLS. **Antes de ampliar:** confirmar consentimiento de cada paciente y revisar con asesoría la regulación mexicana de publicidad de servicios médicos sobre estas fotos. No la verifiqué.

### 7. FAQ

Ejemplos:

- [The Performance Lab](https://mobbin.com/sites/sections/2bf2b42f-fb25-4dde-b866-b15924a8fab5) (`mobbin/10-faq-performance-lab-web.webp`): categorías en acordeón con conteo (Getting started, Pricing, Consultation, Results) y preguntas como "What happens during the first visit?".
- [Biograph](https://mobbin.com/sites/sections/02ee01b7-e944-4a5f-8a25-9dc921a562a1): pregunta abierta, respuestas extensas y "Who leads Biograph's medical team?".
- [Superpower](https://mobbin.com/sites/sections/8eaf961f-6352-4edb-9a6b-7064307693f5): pestañas por tema con la primera respuesta abierta.

**Patrón:** la FAQ responde objeciones de compra, no definiciones, y deja la primera abierta para indicar que se puede interactuar.

**Aplicación:** en `lp-faq`, ordenar por decisión: Precio, Primera visita, Dolor y seguridad, Resultados y duración. Usar `<details>`, primera abierta, una por tema en móvil si se vuelve larga. El texto debe seguir en el HTML para el schema FAQ y la búsqueda con IA. Cerrar con "¿Otra duda? Escríbenos" (WhatsApp) y Agendar.

### 8. Barra CTA fija en móvil

Ejemplos:

- [Hers](https://mobbin.com/screens/a5c9602d-b496-4234-aec3-d2b9a5462de4) y [Fresha](https://mobbin.com/screens/3206f2b1-cf79-4213-ab4a-7cb71e3d1bb8): un CTA de ancho completo.
- [Tripadvisor](https://mobbin.com/screens/a9de1a29-e996-4624-8091-168a99e4d0b7), [ClassPass](https://mobbin.com/flows/f75f704d-9854-4d95-bbf4-f732e2e57b58) y [Airbnb](https://mobbin.com/flows/dc8c5061-45bc-4733-90ec-10dbf5876213): precio a la izquierda, acción a la derecha.
- [Booking.com](https://mobbin.com/screens/11ba6160-8d59-4194-b6aa-ef5ef1340160): precio total junto al botón.

**Patrón:** en página de servicio, la barra muestra **precio + acción**. Dos botones iguales son raros: suele haber una acción principal.

**Aplicación:** es el cambio más barato. `.lp-bar` ya existe y ya se oculta. En hubs y servicios, pasarle datos por página: "Sculptra · desde $X", botón principal "Agendar" e icono de WhatsApp sólo con icono (con `aria-label`) y al menos 44 px. En home y páginas genéricas, se queda como hoy. Por el peso real de WhatsApp en México, **no hay que quitarlo**: probar con A/B si el icono baja los clics totales. Medir `cta_click` con `cta_location=barra`.

### 9. Navegación móvil

Ejemplos:

- [ClassPass](https://mobbin.com/flows/41b95cfe-b2ba-44dd-87ed-1e28eb0091bc): explorar por categoría con fotos.
- [Hims web](https://mobbin.com/flows/e195cc54-140b-4097-a402-b5fb60582ae1): cajón lateral con tarjeta de inicio y "Top treatments".
- [Zocdoc](https://mobbin.com/screens/a100b9fb-afbb-4af0-94c3-5e9bfe0ac078): Appointments y Book a la vista en la barra de pestañas.

**Patrón:** navegar por objetivo, con los tratamientos destacados arriba y no una lista de páginas institucionales. Evidencia limitada: casi todo es navegación de app con cuenta.

**Aplicación:** no agregar barra de pestañas, porque compite con `.lp-bar`. En el menú móvil actual, poner arriba tres accesos con foto o icono (Sculptra, Botox, Rellenos), luego "Todos los tratamientos y precios", y fijar abajo Agendar y el selector ES/EN. Recordar que `.lp-bar` queda bajo el menú abierto (z-index 1040 vs. 1045).

### 10. Footer de conversión

Ejemplos:

- [Ease](https://mobbin.com/sites/sections/d02f972e-777c-4277-86f2-0d158b7d6ccf) (`mobbin/12-footer-ease-web.webp`): bloque grande con frase de cierre y CTA de ancho completo sobre los enlaces.
- [Sequence](https://mobbin.com/sites/sections/91ab3ff8-d285-4695-8ffd-15c86b43b467): tarjeta en el footer con Book a call y calificación.
- [Superpower Health Gap](https://mobbin.com/sites/sections/29f16f47-5d3f-43b7-a885-bee6eb9b2ead): sello de certificación visible en el footer.

**Patrón:** el footer funciona como segunda oportunidad: frase corta, CTA y una señal de confianza, antes de los enlaces.

**Aplicación:** en `footer.php`, agregar un bloque con "Agenda tu valoración", Agendar, WhatsApp, dirección con enlace a mapa, horario y la nota de Google. En móvil, teléfono y dirección tocables, con cuidado de no duplicar lo que ya hay en el cierre de hubs. Bajo impacto en páginas que ya terminan en CTA. Mantener el `padding-bottom` que evita que `.lp-bar` lo tape.

### 11. Equipo y doctor: confianza médica

Ejemplos:

- [Biograph](https://mobbin.com/sites/sections/1c90aa5d-fe51-456e-8c9c-584661cd3f4c) (`mobbin/11-equipo-biograph-web.webp`): retratos uniformes, nombre con grados y cargo por clínica.
- [The Performance Lab](https://mobbin.com/sites/sections/c74d5ee3-7a45-4f29-b4c5-096f1d13ee9e): biografía con credenciales y "Book a consultation" a su lado.
- [Zocdoc](https://mobbin.com/flows/4f3d0323-b3f5-4b95-8874-4d1e365041e8): educación, certificaciones e idiomas en una lista legible.
- [Airbnb](https://mobbin.com/flows/dc8c5061-45bc-4733-90ec-10dbf5876213): años de experiencia y tres logros concretos.

**Patrón:** credenciales comprobables en texto plano, foto profesional sin stock, y una acción cerca: la confianza se convierte en reserva en ese mismo momento.

**Aplicación:** en `template-parts/doctor.php`, mostrar lista corta de formación, cédula profesional si la clínica la autoriza, años y volumen real de procedimientos, e idiomas ("Consulta en inglés", muy útil para expats). Poner CTA junto al texto con `data-cta="doctor"` y repetir una versión compacta en cada hub: "Te atiende el Dr. Bruno Ocampo", con foto. Sumar `Physician` en el schema de `/nosotros/` si aún no está.

## Inglés y expats

Mobbin no aporta un patrón específico. Por lo que se repite arriba, en `/en/` conviene mostrar pronto "English-speaking doctor", usar el mismo botón contextual y aclarar la moneda (MXN) junto al precio. Zocdoc lista idiomas como dato del doctor. Validar en AgendaPro si la página de reserva sale en inglés: si no, para este público WhatsApp puede ser mejor CTA principal.

## Referencias descargadas

En `docs/reports/estudio-2026-11/mobbin/`:

| Archivo | Fuente |
|---|---|
| `01-hero-superpower-web.webp` | [Superpower](https://mobbin.com/sites/sections/db484778-34d1-4a38-af04-5593bee2f342) |
| `02-flujo-slots-zocdoc-ios.webp` | [Zocdoc](https://mobbin.com/flows/fcd6461e-af19-4ca9-9864-8b21c286888c) |
| `03-flujo-boton-con-hora-cvs-ios.webp` | [CVS](https://mobbin.com/screens/6ffbe483-99bf-4f36-b97b-8b7c00a1ce90) |
| `04-resumen-cita-alan-ios.webp` | [Alan](https://mobbin.com/screens/9d51db29-b746-455b-837b-e85767403b33) |
| `05-lista-servicios-sticky-fresha-ios.webp` | [Fresha](https://mobbin.com/screens/3206f2b1-cf79-4213-ab4a-7cb71e3d1bb8) |
| `06-lista-precios-booksy-ios.webp` | [Booksy](https://mobbin.com/flows/65274fd8-a3d6-430e-a0f0-5756ea3590ee) |
| `07-sticky-cta-hers-ios.webp` | [Hers](https://mobbin.com/screens/a5c9602d-b496-4234-aec3-d2b9a5462de4) |
| `08-antes-despues-hers-ios.webp` | [Hers](https://mobbin.com/screens/dc7b6682-eff4-4591-be82-a6d5d9fd6f62) |
| `09-resenas-resultados-hims-web.webp` | [Hims](https://mobbin.com/sites/sections/6db670d9-394c-47ce-8963-900f20f3fb75) |
| `10-faq-performance-lab-web.webp` | [The Performance Lab](https://mobbin.com/sites/sections/2bf2b42f-fb25-4dde-b866-b15924a8fab5) |
| `11-equipo-biograph-web.webp` | [Biograph](https://mobbin.com/sites/sections/1c90aa5d-fe51-456e-8c9c-584661cd3f4c) |
| `12-footer-ease-web.webp` | [Ease](https://mobbin.com/sites/sections/d02f972e-777c-4277-86f2-0d158b7d6ccf) |

## Límites y pendientes

- **No encontrado en Mobbin:** One Medical (la búsqueda devolvió cero), Ro (salió Hims en su lugar). Calm no se buscó porque no agenda citas.
- **Sin datos de conversión:** el ranking es una hipótesis priorizada, no un resultado medido.
- **Por verificar:** si AgendaPro admite enlaces por servicio, widget o API, y cómo se ve su reserva en móvil e inglés. Tampoco revisé el contenido del menú móvil, el footer en pantalla ni los textos del doctor: los "por revisar" del ranking vienen de eso. La lista de CTA de Agendar se revisó sólo en el home.
- **Decisiones de la clínica:** política de cancelación, anticipo y datos de cédula y reseñas.
- **Sin revisión legal:** publicidad médica y uso de fotos de pacientes en México.
