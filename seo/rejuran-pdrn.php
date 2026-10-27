<?php
// Rejuran answers the PDRN de salmón searches. The PDRN page was the 4th page by clicks
// (41 clicks, 3,510 impressions from 2026-07-24 to 2026-10-24) and redirects here since ~2026-10-21 (seo/prices.php),
// but in its first 28 days Rejuran got almost no PDRN queries: the page never says, near the top, what to do if you came for PDRN.
// - Adds a "¿Rejuran es PDRN de salmón?" section after the first one (what PDRN is, how Rejuran is applied, how long it lasts, aftercare, price).
// - Puts the prices in the "¿Cuánto cuesta…?" section, which only listed what changes the price.
// - Fixes a line left over from the PDRN page ("determinar si el PDRN es adecuado").
// The title and meta description are in seo/prices.php. Prices are the ones in that description and in the theme's includes/_treatments.php.
// Run once by hand, like privacidad.php: wp eval-file seo/rejuran-pdrn.php  (safe to run twice)
// Uses $wpdb->update, not wp_update_post: WP-CLI runs without a user and kses would strip markup.

global $wpdb;

$slug = 'tratamiento-rejuran-en-cdmx';
$post = $wpdb->get_row($wpdb->prepare("SELECT ID, post_content FROM $wpdb->posts WHERE post_name = %s AND post_type = 'service' AND post_status = 'publish'", $slug));
if (!$post) { echo "ERROR: no existe $slug\n"; exit(1); }
$content = $post->post_content;
$before = $content;

$cta = '<p class="lp-sv-action"><a class="btn btn-primary lp-btn-arrow" href="https://wa.link/an0g5x" target="_blank" rel="noopener" data-cta="servicio-whatsapp">Agendar mi cita</a></p>';
$pdrn_h2 = '<h2>¿Rejuran es PDRN de salmón? Diferencias y precio</h2>';
$pdrn_section = '<section class="lp-sv-section">' . $pdrn_h2
    . '<p>Si buscabas PDRN de salmón, esta parte es para ti. En Eternal Glow ya no aplicamos PDRN genérico: el tratamiento con derivados de ADN de salmón que ofrecemos es <strong>Rejuran PN</strong>, un producto de marca formulado con polinucleótidos purificados.</p>'
    . '<ul class="lp-sv-checks">'
    . '<li><strong>Qué los distingue:</strong> “PDRN de salmón” es una categoría genérica que incluye muchas marcas y formulaciones, desde inyectables hasta sueros cosméticos. Rejuran PN es un producto específico, basado en polinucleótidos y aplicado con un protocolo médico.</li>'
    . '<li><strong>Cómo se aplica:</strong> Con microinyecciones en las zonas seleccionadas, después de una valoración médica. No es un suero ni un facial cosmético.</li>'
    . '<li><strong>Cuánto dura:</strong> La mejoría es progresiva y depende del estado de tu piel, del número de sesiones y del mantenimiento. En la valoración te decimos cuántas sesiones necesitas y cada cuánto conviene repetirlas.</li>'
    . '<li><strong>Cuidados:</strong> No tocar ni maquillar la zona, evitar el calor y el ejercicio intenso y usar protector solar. Más abajo están todos los cuidados después del tratamiento.</li>'
    . '<li><strong>Precio:</strong> Rejuran en CDMX desde $4,499 MXN; Rejuran Healer inyectable, $6,999 MXN por sesión. El precio final depende de la cantidad de producto y del número de sesiones, y te lo confirmamos antes de empezar.</li>'
    . '</ul>' . $cta . '</section>';

// Right after the first section ("¿Qué es Rejuran PN?"): the second one opens with this photo.
$anchor = '<section class="lp-sv-section"><figure class="lp-sv-media"><img src="https://eternalglow.com.mx/wp-content/uploads/2026/07/aplicacion-rejuran-pn-zona-temporal.png"';
if (strpos($content, $pdrn_h2) === false) {
    if (substr_count($content, $anchor) !== 1) { echo "ERROR: no encuentro una sola vez la segunda sección de $slug\n"; exit(1); }
    $content = str_replace($anchor, $pdrn_section . $anchor, $content);
}

// Exact phrases replaced in place (a no-op once applied, so the script stays rerunnable).
$price_p = '<p>El precio de Rejuran PN en CDMX depende del producto utilizado, la cantidad necesaria, las zonas tratadas y el número de sesiones que formen parte del protocolo.</p>';
$copy = array(
    '<h2>¿Cuánto cuesta el tratamiento Rejuran PN en CDMX?</h2>' . $price_p =>
        '<h2>¿Cuánto cuesta el tratamiento Rejuran PN en CDMX?</h2><p>Rejuran en CDMX cuesta desde <strong>$4,499 MXN</strong>; Rejuran Healer inyectable, <strong>$6,999 MXN por sesión</strong>.</p>' . $price_p,
    'determinar si el PDRN es adecuado' => 'determinar si Rejuran es adecuado',
);
foreach ($copy as $old => $new) {
    if (strpos($content, $new) === false) $content = str_replace($old, $new, $content);
}

$changed = 0;
if ($content !== $before) {
    $dir = WP_CONTENT_DIR . '/uploads/seo-backups';
    wp_mkdir_p($dir);
    if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
    file_put_contents("$dir/rejuran-pdrn-" . gmdate('Ymd-His') . '.json', wp_json_encode(array($slug => $before)));
    $wpdb->update($wpdb->posts, array('post_content' => $content), array('ID' => $post->ID));
    clean_post_cache($post->ID);
    $changed = 1;
}

// Self-check against what is stored now.
$now = $wpdb->get_var($wpdb->prepare("SELECT post_content FROM $wpdb->posts WHERE ID = %d", $post->ID));
if (substr_count($now, $pdrn_h2) !== 1) { echo "ERROR: la sección PDRN no está una sola vez\n"; exit(1); }
foreach (array('desde <strong>$4,499 MXN</strong>', 'determinar si Rejuran es adecuado', '¿Rejuran PN es lo mismo que PDRN Salmón?') as $good) {
    if (strpos($now, $good) === false) { echo "ERROR: falta '$good'\n"; exit(1); }
}
if (strpos($now, 'determinar si el PDRN es adecuado') !== false) { echo "ERROR: sigue la frase de la página PDRN\n"; exit(1); }
if (substr_count($now, '<section') !== substr_count($now, '</section>')) { echo "ERROR: secciones sin cerrar\n"; exit(1); }

wp_cache_flush();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: $changed pagina actualizada\n";
