# Auditoría SEO de eternalglow.com.mx

- **Fecha**: 2026-10-20
- **Alcance**: auditoría completa con 10 agentes (técnico con hreflang, contenido/E-E-A-T, schema, sitemap, rendimiento, visual, GEO, agentes, local, SXO) más `seo/site_audit.py` (40 URLs del sitemap con inspección de Search Console). Los hallazgos dudosos se verificaron en el servidor con WP-CLI.
- **No medido**: Core Web Vitals de campo (PageSpeed devolvió el error de límite de uso sin API key), backlinks, el estado real del perfil de Google Business, el Local Pack real de Google México y si ya hay perfiles en directorios.

## Puntuación: 68/100

Nada impide la indexación. Lo que más resta es la confianza en un sitio de salud (YMYL), el schema con dos entidades de negocio y hubs que no coinciden con la intención de búsqueda.

| Categoría | Peso | Puntos |
|---|---|---|
| Técnico | 22% | 74 |
| Contenido / E-E-A-T | 23% | 62 |
| On-page / intención (SXO) | 20% | 65 (SXO 58) |
| Schema | 10% | 62 |
| Rendimiento (laboratorio) | 10% | 80 |
| Búsqueda con IA (GEO) | 10% | 67 |
| Imágenes | 5% | 75 |

Fuera del cálculo: SEO local 68, visual 82, preparación para agentes 80.

## Alta (esta semana)

1. **Dos entidades de negocio en el schema.** El plugin *Schema & Structured Data for WP* guarda un JSON-LD propio por página (`saswp_custom_schema_field` en `eg_postmeta`) en Sculptra y en al menos 10 servicios.
   - Ese schema publica otro teléfono (`+525536460497`), otras coordenadas (19.4274797,-99.1660649 frente a 19.4200327,-99.1681248) y otro nombre ("Eternal Glow CDMX Roma Norte").
   - También incluye `aggregateRating` y `review` de la clínica sobre sí misma, que Google no acepta.
   - Arreglo: una sola entidad `#clinic` referenciada por `@id` y quitar el schema de SASWP.
   - Cómo sabremos que falló: el Rich Results Test sigue mostrando dos negocios.
2. **Nombre del perfil de Google Business con palabras clave.** Hoy es "Eternal Glow CDMX Roma Norte (English speaking team) - Botox | Hilos tensores PDO | Sculptra | PDRN Salmón | Fillers & more".
   - Va contra las pautas de Google y puede provocar una suspensión. Además, PDRN ya no se ofrece.
   - Arreglo: dejar el nombre en "Eternal Glow" y llevar los servicios a las categorías. Lo hace el cliente.
3. **TTFB muy alto sin caché.**
   - Con caché las páginas responden en 0.2 s. Sin caché, las `/servicio/` tardan de 2.4 a 11.4 s.
   - `service-sitemap.xml` tardó una vez más de 90 s y en el reintento respondió en 0.4 s.
   - El deploy purga la caché, así que Googlebot puede llegar justo cuando está vacía.
   - Arreglo: activar la precarga de WP Super Cache y dispararla al final de `seo/deploy.sh`.
   - Indicador: el TTFB de una `/servicio/` justo después de un deploy.
4. **Hub de Botox (`/botox-cdmx/`).**
   - Se contradice: la intro dice que el efecto empieza en 48–72 h y dura hasta 5 meses; el bloque del procedimiento dice que aparece a los 3–7 días y es completo a los 14.
   - Promete "garantizan resultados", lo que choca con las directrices de evaluadores de Google y con COFEPRIS.
   - No tiene una sección de riesgos y contraindicaciones.
   - El title no lleva precio, a diferencia de Sculptra, Rellenos y EN.
   - La tabla de precios queda debajo de un bloque genérico, "Nuestros Servicios".
   - Arreglo: title "Botox en CDMX desde $165/unidad | Roma Norte | Eternal Glow", precios justo debajo del H1 y un solo dato clínico, igual en ES y EN.
5. **"rellenos cdmx" tiene intención de comida.** Todo el top son chiles rellenos, chilaquiles y pan de muerto.
   - Arreglo: enfocar el H1 y el title en "Rellenos faciales con ácido hialurónico en CDMX".
   - Medir en GSC "ácido hialurónico cdmx" y "rellenos faciales cdmx".
   - El precio de $6,499 está por debajo de los $7,500 de Doctoralia.
6. **El médico no aparece en los hubs ni en las páginas `/en/`.** Hoy solo sale en la home, en `/nosotros/` y en las `/servicio/`.
   - Arreglo: un bloque con nombre, foto y enlace a `/nosotros/`, más "Revisado médicamente el…" y `reviewedBy` en el schema. La cédula se agrega cuando la entregue el cliente.

## Media (este mes)

- **Encabezados de seguridad.** No hay HSTS, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy` ni `Permissions-Policy`.
  - Arreglo: añadirlos en `.htaccess`. HSTS va primero con un `max-age` corto.
  - CSP primero en modo report-only, porque GTM, Clarity y WPBakery usan scripts inline.
- **Restos de WP Rocket.** El plugin está desinstalado, pero su bloque sigue en `.htaccess` (líneas 47–187) y envía `X-Powered-By: WP Rocket/3.23.3.3`.
  - Arreglo: quitarlo con un respaldo previo y probar el sitio.
- **Hubs en inglés (~370 palabras de texto principal).** El SERP de "botox mexico city" es de turismo médico.
  - Arreglo: precio en USD junto a MXN ($165 MXN ≈ $9 USD por unidad).
  - Agregar una tabla "Mexico vs US cost", una sección "Is it safe?" (producto original, cédula, COFEPRIS) y otra "Planning your visit".
  - Llevarlos a unas 900 palabras o más.
- **Riesgos y contraindicaciones** como H2 en Botox, Rellenos, Labios e Hilos, con `/sculptra-cdmx/` como modelo.
- **Rendimiento de la home en móvil (laboratorio).** El LCP es de 4.0–4.6 s y el elemento LCP es texto del header (render delay de unos 900 ms). El TBT varía entre 130 y 2160 ms.
  - Cargar Clarity después de `load`.
  - El plugin **Divi Builder** está activo junto con WPBakery y carga `scripts.min.js` (86 KiB sin usar). Revisar si se usa y, si no, desactivarlo.
  - Convertir los logos de marca (restylane, xeomin, rejuran) de PNG a WebP.
- **Schema.**
  - BreadcrumbList en `/botox-cdmx/`, `/rellenos-cdmx/` y `/tratamientos/`.
  - `price` en las Offer de las `/servicio/`.
  - `ItemList` o `CollectionPage` en `/tratamientos/`.
  - `Physician` como `["Person","Physician"]`, enlazado desde `Service.provider`.
  - El `url` del MedicalClinic en `/en/` y el @id de WebSite repetido entre idiomas.
- **Indexación de las páginas en inglés.** Google no reconoce `/en/fillers-mexico-city/` ni `/en/lip-filler-mexico-city/`. Ya hay una tarea en Notion para pedir la indexación.

## Baja (backlog)

- IndexNow con el módulo de Rank Math.
- 301 a la versión con barra final: `/botox-cdmx` y `/en` responden 200. El canonical es correcto.
- `llms.txt` bilingüe.
- `sameAs` hacia Google Business y los directorios cuando existan.
- 9 nodos interactivos sin nombre accesible en la home y en `/botox-cdmx/`.
- En `/botox-cdmx/` móvil, la barra fija inferior tapa el CTA principal y el H1 queda pegado al header.
- 16 tap targets de menos de 44 px en la home móvil (enlaces del footer).
- Fecha visible "Actualizado el…" y `dateModified` en los hubs.

## Descartado tras verificar

- **"Faltan 3 servicios en el sitemap"**: falso. `tratamiento-sculptra-en-cdmx` redirige 301 a `/sculptra-cdmx/`, y PDRN y armonización full face están en borrador.
- **"Imágenes sin alt"** en todas las páginas: son íconos decorativos (`arrow-right.svg`, `clock.svg`, etc.) con `alt=""`, que es lo correcto.
- **"Las /servicio/ no enlazan a su hub"**: falso. Llevan "Botox en CDMX", "Rellenos" y "Sculptra" en el menú y el breadcrumb.
- **"Agregar AggregateRating"**: no. Son reseñas de la clínica sobre sí misma, y hay que quitarlas.
- **"Quitar gtag porque GTM ya envía GA4"**: no. GA4 va solo por gtag; meterlo en GTM duplicaría el conteo.
- **"Conviven dos cachés"**: WP Rocket está desinstalado. Solo queda su bloque en `.htaccess`.

## Ya conocido (sigue pendiente)

Cédula del Dr. Bruno Ocampo, fotos de antes y después, aviso de privacidad (`/aviso-de-privacidad/` da 404 y la home no enlaza a privacidad), perfiles en Doctoralia, Top Doctors, AgendaPro y Yelp, y reseñas y optimización del perfil de Google Business.

## Teléfono

El usuario confirmó el 2026-10-20 que el número real de la clínica (también el de WhatsApp) es `+52 55 5404 9467`. El `+52 55 3646 0497` que publica el schema de SASWP es incorrecto, así que el punto 1 de Alta es el primero que hay que atender.
