<?php
// SEO for the redesigned home: titles, the English page, menu and redirect clean-up.
// Run with: wp eval-file seo/home.php  (safe to run twice)
// The home content itself is a PHP template in the theme (front-page.php), not post content.

global $wpdb;

$home_id = (int)get_option('page_on_front');
$services = get_page_by_path('servicios', OBJECT, 'page');
$treatments = get_page_by_path('tratamientos', OBJECT, 'page');
if (!$home_id || !$treatments) { echo "ERROR: falta la portada o la pagina tratamientos\n"; return; }

$dir = WP_CONTENT_DIR . '/uploads/seo-backups';
wp_mkdir_p($dir);
if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
$backup = array();

// 1. Home title and description: local keyword first, then the brand.
$meta = array(
    'rank_math_title'         => 'Clínica Estética en Roma Norte, CDMX | Eternal Glow',
    'rank_math_description'   => 'Clínica de medicina estética en Roma Norte, CDMX. Botox, rellenos, Sculptra e hilos tensores con el Dr. Bruno Ocampo. Agenda tu valoración en línea.',
    'rank_math_focus_keyword' => 'clínica de medicina estética en CDMX,clínica estética Roma Norte',
);
foreach ($meta as $k => $v) {
    $backup['home'][$k] = get_post_meta($home_id, $k, true);
    update_post_meta($home_id, $k, wp_slash($v));
}

// 2. English home at /en/ (rendered by the theme's page-en.php).
$en = get_page_by_path('en', OBJECT, 'page');
$en_id = $en ? $en->ID : wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'en', 'post_title' => 'Eternal Glow (English)', 'post_content' => ''));
update_post_meta($en_id, 'rank_math_title', 'Aesthetic Clinic in Roma Norte, Mexico City | Eternal Glow');
update_post_meta($en_id, 'rank_math_description', wp_slash('Aesthetic medicine clinic in Roma Norte, Mexico City. Botox, fillers, Sculptra and PDO threads with Dr. Bruno Ocampo. English speaking team. Book online.'));
update_post_meta($en_id, 'rank_math_focus_keyword', 'aesthetic clinic mexico city,botox mexico city');

// 2b. English Botox hub at /en/botox-mexico-city/: no content in WordPress, the theme renders it from includes/_landings.php.
$botox_en = get_page_by_path('en/botox-mexico-city', OBJECT, 'page');
$botox_en_id = $botox_en ? $botox_en->ID : wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'botox-mexico-city', 'post_parent' => $en_id, 'post_title' => 'Botox in Mexico City', 'post_content' => ''));
if (!$botox_en_id || is_wp_error($botox_en_id)) { echo "ERROR: no pude crear /en/botox-mexico-city/\n"; exit(1); }
update_post_meta($botox_en_id, 'rank_math_title', 'Botox in Mexico City | From $165 per Unit | Eternal Glow');
update_post_meta($botox_en_id, 'rank_math_description', wp_slash('Botox in Mexico City from $165 MXN per unit. Baby Botox $4,499, Full Face $8,499. Natural results by an aesthetic physician in Roma Norte. English spoken.'));
update_post_meta($botox_en_id, 'rank_math_focus_keyword', 'botox mexico city,botox in mexico city');

// 2c. English Sculptra hub at /en/sculptra-mexico-city/, rendered like 2b.
$sculptra_en = get_page_by_path('en/sculptra-mexico-city', OBJECT, 'page');
$sculptra_en_id = $sculptra_en ? $sculptra_en->ID : wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'sculptra-mexico-city', 'post_parent' => $en_id, 'post_title' => 'Sculptra in Mexico City', 'post_content' => ''));
if (!$sculptra_en_id || is_wp_error($sculptra_en_id)) { echo "ERROR: no pude crear /en/sculptra-mexico-city/\n"; exit(1); }
update_post_meta($sculptra_en_id, 'rank_math_title', 'Sculptra in Mexico City | $14,999 per Vial | Eternal Glow');
update_post_meta($sculptra_en_id, 'rank_math_description', wp_slash('Sculptra in Mexico City: $14,999 MXN per vial, 2 vials $28,000, 3 vials $33,000. Collagen biostimulator in Roma Norte. English spoken.'));
update_post_meta($sculptra_en_id, 'rank_math_focus_keyword', 'sculptra mexico city,sculptra in mexico city');

// 2d. English fillers hub at /en/fillers-mexico-city/, rendered like 2b.
$fillers_en = get_page_by_path('en/fillers-mexico-city', OBJECT, 'page');
$fillers_en_id = $fillers_en ? $fillers_en->ID : wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'fillers-mexico-city', 'post_parent' => $en_id, 'post_title' => 'Dermal Fillers in Mexico City', 'post_content' => ''));
if (!$fillers_en_id || is_wp_error($fillers_en_id)) { echo "ERROR: no pude crear /en/fillers-mexico-city/\n"; exit(1); }
update_post_meta($fillers_en_id, 'rank_math_title', 'Dermal Fillers in Mexico City | $6,499 per Syringe');
update_post_meta($fillers_en_id, 'rank_math_description', wp_slash('Hyaluronic acid fillers in Mexico City: $6,499 MXN per syringe for lips, under eyes, cheeks, chin or jaw. Packages from 2 syringes. English spoken.'));
update_post_meta($fillers_en_id, 'rank_math_focus_keyword', 'fillers mexico city,lip fillers mexico city,dermal fillers mexico city');

// 2e. English lip filler page at /en/lip-filler-mexico-city/, rendered like 2b.
$lips_en = get_page_by_path('en/lip-filler-mexico-city', OBJECT, 'page');
$lips_en_id = $lips_en ? $lips_en->ID : wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_name' => 'lip-filler-mexico-city', 'post_parent' => $en_id, 'post_title' => 'Lip Filler in Mexico City', 'post_content' => ''));
if (!$lips_en_id || is_wp_error($lips_en_id)) { echo "ERROR: no pude crear /en/lip-filler-mexico-city/\n"; exit(1); }
update_post_meta($lips_en_id, 'rank_math_title', 'Lip Filler in Mexico City | $6,499 per Syringe');
update_post_meta($lips_en_id, 'rank_math_description', wp_slash('Lip filler in Mexico City: $6,499 MXN per 1 mL syringe of hyaluronic acid. Volume and definition with a natural result in Roma Norte. English spoken.'));
update_post_meta($lips_en_id, 'rank_math_focus_keyword', 'lip filler mexico city,lip fillers mexico city,lip fillers in mexico');

// 3. /servicios/ is an empty page that sat in the main menu: point the menu to /tratamientos/ and redirect it.
if ($services) {
    $items = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_menu_item_object_id' AND meta_value = %d AND post_id IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'nav_menu_item')", $services->ID));
    foreach ($items as $item) update_post_meta($item, '_menu_item_object_id', $treatments->ID);
    $backup['menu_items_servicios'] = $items;
    update_post_meta($services->ID, 'rank_math_robots', array('noindex'));

    $table = $wpdb->prefix . 'rank_math_redirections';
    $exists = $wpdb->get_var("SELECT id FROM $table WHERE sources LIKE '%\"servicios\"%'");
    if (!$exists) {
        $wpdb->insert($table, array(
            'sources'     => serialize(array(array('ignore' => '', 'pattern' => 'servicios', 'comparison' => 'exact'))),
            'url_to'      => home_url('/tratamientos/'),
            'header_code' => 301,
            'status'      => 'active',
            'created'     => current_time('mysql'),
            'updated'     => current_time('mysql'),
        ));
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rank_math_redirections_cache");
    }
}

// 4. Custom menu links saved with the production domain become site-relative paths.
$custom = $wpdb->get_results("SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_menu_item_url' AND meta_value LIKE 'https://eternalglow.com.mx/%'");
foreach ($custom as $row) {
    $backup['menu_urls'][$row->meta_id] = $row->meta_value;
    $wpdb->update($wpdb->postmeta, array('meta_value' => home_url(substr($row->meta_value, strlen('https://eternalglow.com.mx')))), array('meta_id' => $row->meta_id));
}

// 5. Services belong in the sitemap.
$sitemap = get_option('rank-math-options-sitemap');
if (is_array($sitemap) && (!isset($sitemap['pt_service_sitemap']) || $sitemap['pt_service_sitemap'] !== 'on')) {
    $sitemap['pt_service_sitemap'] = 'on';
    update_option('rank-math-options-sitemap', $sitemap);
}

file_put_contents("$dir/home-" . gmdate('Ymd-His') . '.json', wp_json_encode($backup));
wp_cache_flush();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: portada $home_id, /en/ $en_id, menu y redireccion de /servicios/ listos\n";
