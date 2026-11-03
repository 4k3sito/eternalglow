# Fotos del Dr. Bruno Ocampo

Recibidas del cliente el 2026-11-02. Se guardan las tres opciones; el sitio usa la **03**.

| Archivo | Uso |
|---|---|
| `dr-bruno-01-sonrisa.png` / `.webp` | Opción 1, primer plano sonriendo. No se usa. |
| `dr-bruno-02-seria.png` / `.webp` | Opción 2, scrubs azules, gesto serio. No se usa. |
| `dr-bruno-03-oficial.png` / `.webp` | **La elegida.** Sonrisa, bata y estetoscopio. |

Los PNG son los originales (1024×1536, salvo la 02 que es 1086×1448). Los WebP son la copia optimizada.

En el tema, la 03 se recorta a 900×1166 y se sirve como:

- `assets/images/dr-bruno-ocampo-2026.webp` — bloque del doctor en home y hubs, y schema.
- `assets/images/cutouts/dr-bruno-ocampo-nosotros-2026.webp` — retrato de `/nosotros/` (el archivo conserva el nombre "cutout" por el filtro de `includes/_home.php`, aunque ya es una foto con fondo).

Los archivos sin `-2026` se conservan para las páginas CACHEADAS que todavía apuntan a la foto vieja; se pueden borrar cuando expire la caché de 4 meses del `.htaccess`.

Si se cambia la foto, actualizar los dos archivos con `-2026`, el `width`/`height` de `template-parts/doctor.php` (900×1166) y el schema (`get_images_url`).
