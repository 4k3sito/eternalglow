<?php
// Creates the fillers hub and the lip filler page, and sets their search snippets.
// Run with: wp eval-file seo/landings.php  (safe to run twice)
// The pages have no content in WordPress: the theme renders them from includes/_landings.php.

$pages = array(
    // slug => array(page title, SEO title, description, focus keyword)
    'rellenos-cdmx' => array('Rellenos con ácido hialurónico', 'Rellenos con Ácido Hialurónico en CDMX | Desde $6,499', 'Rellenos con ácido hialurónico en CDMX: $6,499 por jeringa para labios, ojeras, pómulos, mentón o mandíbula. Paquetes desde 2 jeringas. Roma Norte.', 'rellenos con ácido hialurónico en CDMX,rellenos cdmx'),
    'relleno-de-labios-cdmx' => array('Relleno de labios', 'Relleno de Labios en CDMX | $6,499 | Eternal Glow', 'Relleno de labios con ácido hialurónico en CDMX por $6,499 la jeringa de 1 mL. Volumen y definición con resultado natural. Agenda en Roma Norte.', 'relleno de labios en CDMX,relleno de labios cdmx'),
);

foreach ($pages as $slug => $row) {
    if (mb_strlen($row[1]) > 60) { echo "TITLE > 60 ($slug): " . mb_strlen($row[1]) . "\n"; exit(1); }
    if (mb_strlen($row[2]) > 155) { echo "DESCRIPTION > 155 ($slug): " . mb_strlen($row[2]) . "\n"; exit(1); }
}

$made = 0;
foreach ($pages as $slug => $row) {
    $page = get_page_by_path($slug, OBJECT, 'page');
    $id = $page ? $page->ID : wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $row[0], 'post_content' => ''));
    if (!$id || is_wp_error($id)) { echo "ERROR: no pude crear $slug\n"; continue; }
    if (!$page) $made++;
    update_post_meta($id, 'rank_math_title', wp_slash($row[1]));
    update_post_meta($id, 'rank_math_description', wp_slash($row[2]));
    update_post_meta($id, 'rank_math_focus_keyword', wp_slash($row[3]));
    clean_post_cache($id);
}

wp_cache_flush();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: " . count($pages) . " paginas listas, $made nuevas\n";
