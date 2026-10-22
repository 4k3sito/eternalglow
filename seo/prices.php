<?php
// Official prices in the search snippets, and the service pages the clinic's price list retires.
// Run after seo/treatments.php (it overwrites some of its titles): wp eval-file seo/prices.php  (safe to run twice)
// The figures are the clinic's flyers of 2026-10-12, the same ones as the theme's includes/_treatments.php:
// when a price changes there, change it here and run this again.

global $wpdb;

$rows = array(
    // slug => array(title or null to keep it, description)
    'botox-cdmx' => array(null, 'Botox en CDMX (Roma Norte) con médico estético: $165 por unidad, Baby Botox $4,499 y Full Face $8,499. Resultado natural. Agenda tu valoración.'),
    'hilos-tensores-cdmx' => array('Hilos Tensores en CDMX | Desde $1,100 por Hilo', 'Hilos tensores PDO en CDMX desde $1,100 por hilo: de 1 a 4 hilos, $1,500 c/u; de 5 a 8, $1,300 c/u. Lifting sin cirugía en Roma Norte.'),

    'armonizacion-facial-con-acido-hialuronico' => array('Armonización Facial en CDMX | Desde $6,499 | Eternal Glow', 'Armonización facial con ácido hialurónico en CDMX desde $6,499 por jeringa; 3 jeringas por $16,000 y 4 por $20,000. Valoración médica en Roma Norte.'),
    'baby-botox-cdmx' => array('Baby Botox en CDMX | 30 Unidades por $4,499 | Eternal Glow', 'Baby Botox en CDMX: 30 unidades por $4,499. Microdosis de toxina botulínica para suavizar líneas finas con resultado natural. Agenda tu valoración.'),
    'botox-en-los-labios' => array('Botox en los Labios en CDMX | Precio por Unidad: $165', 'Botox en los labios en CDMX para suavizar el código de barras. Se cobra por unidad ($165) y la dosis se define en valoración médica en Roma Norte.'),
    'botox-foxy-eyes-cdmx' => array('Botox Foxy Eyes en CDMX | $1,250 | Eleva la Mirada', 'Botox Foxy Eyes en CDMX por $1,250: eleva sutilmente la cola de la ceja para una mirada más abierta y natural. Agenda tu valoración en Roma Norte.'),
    'botox-lip-flip' => array(null, 'Botox Lip Flip en CDMX por $1,250: define el labio superior y mejora la sonrisa con un efecto natural, sin rellenos. Agenda tu valoración en Roma Norte.'),
    'botox-para-bruxismo-en-maseteros-cdmx' => array('Botox para Bruxismo en Maseteros en CDMX | $8,499', 'Botox para bruxismo en maseteros en CDMX por $8,499: relaja la tensión mandibular, alivia el dolor y afina el contorno del rostro. Roma Norte.'),
    'botox-para-hiperhidrosis-en-cdmx' => array(null, 'Botox para hiperhidrosis en CDMX: $8,499 por zona (axilas, manos o pies) para controlar la sudoración excesiva. Agenda tu valoración en Roma Norte.'),
    'botox-por-unidad' => array('Botox por Unidad en CDMX | Precio: $165 por Unidad', 'Precio de Botox por unidad en CDMX: $165. Dosis a la medida para frente, entrecejo, patas de gallo o labios, definida en valoración médica.'),
    'botox-trapecio-trap-tox' => array('Botox Trapecio (Traptox) en CDMX | $8,499 | Eternal Glow', 'Traptox o Botox en trapecio en CDMX por $8,499: relaja la tensión muscular y estiliza cuello y hombros. Aplicado por médico estético en Roma Norte.'),
    'full-face-botox-60-unidades' => array('Full Face Botox en CDMX | 60 Unidades por $8,499', 'Full Face Botox en CDMX: 60 unidades por $8,499 para frente, entrecejo, patas de gallo y más zonas. Resultado natural con médico estético.'),
    'hifu-facial-en-cdmx' => array('HIFU Facial en CDMX | $7,000 por Sesión | Eternal Glow', 'HIFU facial Ultramage 7D en CDMX: $7,000 por sesión o 2 sesiones por $12,999. Reafirma rostro, cuello y papada sin cirugía. Agenda en Roma Norte.'),
    // "Carbon peel" too: Search Console shows "hollywood carbon peel", "carbon peel" and "peeling de carbono precio".
    'hollywood-peel-en-cdmx' => array('Hollywood Peel (Carbon Peel) en CDMX | $1,499 por Sesión', 'Hollywood Peel o carbon peel en CDMX: láser con carbón activado para poros, grasa y piel apagada. $1,499 por sesión o 3 por $3,999, en Roma Norte.'),
    'marcaje-mandibular-con-acido-hialuronico-cdmx' => array('Marcaje Mandibular en CDMX | Desde $6,499 | Eternal Glow', 'Marcaje mandibular con ácido hialurónico en CDMX desde $6,499: define el ángulo y la línea de la mandíbula con resultado natural. Roma Norte.'),
    'microneedling-en-cdmx' => array('Microneedling en CDMX | Desde $2,499 | Eternal Glow', 'Microneedling en CDMX desde $2,499 por sesión; con Rejuran, $3,000. Mejora textura, poros y cicatrices de acné. Agenda tu valoración en Roma Norte.'),
    'peeling-quimico-facial-en-cdmx' => array('Peeling Químico Facial en CDMX | Precio: $2,000', 'Peeling químico facial en CDMX por $2,000: mejora manchas, textura y luminosidad de la piel. Conoce resultados y cuidados. Agenda en Roma Norte.'),
    'relleno-de-ojeras-con-acido-hialuronico' => array('Relleno de Ojeras en CDMX | $6,499 | Ácido Hialurónico', 'Relleno de ojeras con ácido hialurónico en CDMX por $6,499: mejora ojeras hundidas, surco lagrimal y aspecto cansado. Resultado natural.'),
    'rinomodelacion-en-cdmx' => array('Rinomodelación en CDMX | $6,499 | Perfilado sin Cirugía', 'Rinomodelación en CDMX con ácido hialurónico por $6,499: mejora el perfil nasal, suaviza la giba leve y levanta la punta sin cirugía.'),
    'tratamiento-radiesse-en-cdmx' => array('Radiesse en CDMX | $8,499 por Jeringa | Eternal Glow', 'Radiesse en CDMX: $8,499 por jeringa, 2 por $15,999 o 3 por $22,999. Bioestimulador de colágeno para firmeza y contorno facial. Roma Norte.'),
    'tratamiento-rejuran-en-cdmx' => array('Rejuran en CDMX | Desde $4,499 | Polinucleótidos', 'Rejuran en CDMX desde $4,499; Rejuran Healer inyectable, $6,999 por sesión. Polinucleótidos para hidratación, textura y luminosidad de la piel.'),
);

// Service pages for treatments that are not on the clinic's list any more: each one goes to the closest
// treatment that is, with a 301, and to draft so it leaves the sitemap and the related lists.
$retired = array(
    // PDRN is no longer offered; Rejuran is the polynucleotide treatment the clinic keeps.
    'tratamiento-pdrn-salmon' => 'tratamiento-rejuran-en-cdmx',
    // The list has one "Armonización facial", priced by the syringe and by package.
    'armonizacion-facial-full-face-cdmx' => 'armonizacion-facial-con-acido-hialuronico',
);

// Self-check: fail before writing anything if a title or description is too long for the results page.
foreach ($rows as $slug => $row) {
    if ($row[0] !== null && mb_strlen($row[0]) > 60) { echo "TITLE > 60 ($slug): " . mb_strlen($row[0]) . "\n"; exit(1); }
    if (mb_strlen($row[1]) > 155) { echo "DESCRIPTION > 155 ($slug): " . mb_strlen($row[1]) . "\n"; exit(1); }
}

$backup = array();
$done = 0;
foreach ($rows as $slug => $row) {
    $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type IN ('service', 'page') AND post_status = 'publish'", $slug));
    if (!$id) { echo "No existe: $slug\n"; continue; }
    $meta = array('rank_math_description' => $row[1]);
    if ($row[0] !== null) $meta['rank_math_title'] = $row[0];
    foreach ($meta as $k => $v) {
        $backup['meta'][$slug][$k] = get_post_meta($id, $k, true);
        update_post_meta($id, $k, wp_slash($v));
    }
    clean_post_cache($id);
    $done++;
}

$table = $wpdb->prefix . 'rank_math_redirections';
$redirects = 0;
foreach ($retired as $slug => $target) {
    $from = "servicio/$slug";
    $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE sources LIKE %s", '%' . $wpdb->esc_like($from) . '%'));
    if (!$exists) {
        $wpdb->insert($table, array(
            'sources'     => serialize(array(array('ignore' => '', 'pattern' => $from, 'comparison' => 'exact'))),
            'url_to'      => home_url("/servicio/$target/"),
            'header_code' => 301,
            'status'      => 'active',
            'created'     => current_time('mysql'),
            'updated'     => current_time('mysql'),
        ));
        $redirects++;
    }
    $post = get_page_by_path($slug, OBJECT, 'service');
    if ($post && $post->post_status == 'publish') {
        $backup['status'][$slug] = $post->post_status;
        $wpdb->update($wpdb->posts, array('post_status' => 'draft'), array('ID' => $post->ID));
        clean_post_cache($post->ID);
    }
}
if ($redirects) $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rank_math_redirections_cache");

$dir = WP_CONTENT_DIR . '/uploads/seo-backups';
wp_mkdir_p($dir);
if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
file_put_contents("$dir/prices-" . gmdate('Ymd-His') . '.json', wp_json_encode($backup));

wp_cache_flush();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: $done de " . count($rows) . " snippets con precio, $redirects redirecciones nuevas\n";
