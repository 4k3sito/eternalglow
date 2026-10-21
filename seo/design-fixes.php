<?php
// Fixes from the 2026-10-20 design critique. Run with: wp eval-file seo/design-fixes.php  (safe to run twice)
// - The privacy checkbox of the contact forms (CF7 #54 and its English copy) came ticked: consent has to be given
//   by the visitor, so the acceptance tag loses default:on.
// - /botox-cdmx/ showed a before/after picture carrying another account's watermark: it leaves the [jss-patients]
//   gallery of every page. The attachment is looked up by file name, since IDs differ between local and prod.
// - /botox-cdmx/ only showed its Google rating inside the map embed: [eg_rating] goes under the hero's button.
// Runs after content-fixes.php in deploy.sh. Uses $wpdb->update, not wp_update_post: kses would strip the shortcodes.

global $wpdb;

const EG_WATERMARKED = '2025/09/botox-arrugas-frente-mujer-antes-despues.jpg';

// 1. Privacy checkbox unticked by default.
$forms = array_filter(array(54, (int)get_option('eg_contact_form_en')));
foreach ($forms as $id) {
    $form = wpcf7_contact_form($id);
    if (!$form) { echo "ERROR: no existe el formulario $id\n"; exit(1); }
    $markup = $form->prop('form');
    $fixed = preg_replace('/(\[acceptance\s[^\]]*?)\s+default:on/', '$1', $markup);
    if ($fixed === $markup) { echo "OK: formulario $id ya estaba sin marcar\n"; continue; }
    $form->set_properties(array('form' => $fixed));
    if (!$form->save()) { echo "ERROR: no se guardó el formulario $id\n"; exit(1); }
    echo "OK: formulario $id sin casilla premarcada\n";
}

// 2. Watermarked picture out of the patient galleries.
$att = (int)$wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s", EG_WATERMARKED));
$drop = function ($m) use ($att) {
    $ids = array_values(array_diff(array_map('intval', explode(',', $m[2])), array($att)));
    return $m[1] . implode(',', $ids) . $m[3];
};
$re = '/(\[jss-patients\s[^\]]*?images=")([0-9,\s]*)(")/';
if ($att) {
    $posts = $wpdb->get_results("SELECT ID, post_name, post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_content LIKE '%[jss-patients%'");
    foreach ($posts as $post) {
        $content = preg_replace_callback($re, $drop, $post->post_content);
        if ($content === null) { echo "ERROR: regex {$post->post_name}: " . preg_last_error_msg() . "\n"; exit(1); }
        if ($content === $post->post_content) continue;
        $wpdb->update($wpdb->posts, array('post_content' => $content), array('ID' => $post->ID));
        clean_post_cache($post->ID);
        echo "OK: foto con marca de agua fuera de {$post->post_name}\n";
    }
} else {
    echo "OK: no existe el adjunto " . EG_WATERMARKED . ", nada que quitar\n";
}

// 3. Google rating under the hero's call to action on /botox-cdmx/ (its hero is WPBakery content, not a theme landing).
// [eg_rating] is the theme's shortcode (includes/_home.php), so the count stays in sync with Trustindex.
$rating = '[jss-empty-space height="24,,"][eg_rating align="center"]';
$botox = $wpdb->get_row("SELECT ID, post_content FROM $wpdb->posts WHERE post_name = 'botox-cdmx' AND post_type = 'page' AND post_status = 'publish'");
if (!$botox) { echo "ERROR: no existe botox-cdmx\n"; exit(1); }
if (strpos($botox->post_content, '[eg_rating') !== false) {
    echo "OK: botox-cdmx ya tenía la calificación\n";
} else {
    // The first [jss-button-arrow] is the hero's "Agendar tu cita"; the shortcode has no closing tag.
    if (strpos($botox->post_content, '[/jss-button-arrow]') !== false) { echo "ERROR: [jss-button-arrow] ahora tiene cierre, no aplico nada\n"; exit(1); }
    $content = preg_replace('/\[jss-button-arrow\s[^\]]*\]/', '$0' . $rating, $botox->post_content, 1, $n);
    if ($content === null || $n !== 1) { echo "ERROR: no encuentro el botón del hero de botox-cdmx\n"; exit(1); }
    $wpdb->update($wpdb->posts, array('post_content' => $content), array('ID' => $botox->ID));
    clean_post_cache($botox->ID);
    echo "OK: calificación en el hero de botox-cdmx\n";
}

// Self-check.
if (substr_count(get_post_field('post_content', $botox->ID, 'raw'), '[eg_rating') !== 1) { echo "ERROR: botox-cdmx no tiene exactamente una calificación\n"; exit(1); }
foreach ($forms as $id) {
    if (preg_match('/\[acceptance\s[^\]]*default:on/', wpcf7_contact_form($id)->prop('form'))) { echo "ERROR: el formulario $id sigue premarcado\n"; exit(1); }
}
if ($att) {
    $left = $wpdb->get_col("SELECT post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_content LIKE '%[jss-patients%'");
    foreach ($left as $content) {
        preg_match_all($re, $content, $all);
        foreach ($all[2] as $list) {
            if (in_array($att, array_map('intval', explode(',', $list)), true)) { echo "ERROR: la foto con marca de agua sigue en una galería\n"; exit(1); }
        }
    }
}

wp_cache_flush();
echo "OK: design-fixes aplicado\n";
