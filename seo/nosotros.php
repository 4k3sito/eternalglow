<?php
// Nosotros: the doctor's name as the page headline, in the site's serif, instead of a picture of his signature.
// Run with: wp eval-file seo/nosotros.php  (safe to run twice)
// Written with $wpdb->update because wp_update_post would run kses and strip shortcode markup under WP-CLI.

global $wpdb;

$page = get_page_by_path('nosotros', OBJECT, 'page');
if (!$page) { echo "ERROR: no encuentro la pagina nosotros\n"; return; }

$old = '[jss-heading text="Conoce a" tag="h2" text_align=",left,center"][jss-empty-space height="8,,"][jss-image image="51" text_align=",left,center" width="467,,"]';
$new = '[jss-text class="lp-eyebrow" text_align=",left,center"]<p>Conoce a</p>[/jss-text][jss-heading text="Dr. Bruno {{Ocampo}}" tag="h1" class="lp-display" text_align=",left,center"][jss-empty-space height="24,,"]';

if (strpos($page->post_content, $new) !== false) { echo "OK: ya estaba aplicado\n"; return; }
if (strpos($page->post_content, $old) === false) { echo "ERROR: el contenido de nosotros cambio, no aplico nada\n"; return; }

$dir = WP_CONTENT_DIR . '/uploads/seo-backups';
wp_mkdir_p($dir);
if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
file_put_contents("$dir/nosotros-" . gmdate('Ymd-His') . '.json', wp_json_encode(array('post_content' => $page->post_content)));

$wpdb->update($wpdb->posts, array('post_content' => str_replace($old, $new, $page->post_content), 'post_modified' => current_time('mysql'), 'post_modified_gmt' => current_time('mysql', 1)), array('ID' => $page->ID));
clean_post_cache($page->ID);
if (function_exists('rocket_clean_post')) rocket_clean_post($page->ID);
echo "OK: nosotros actualizado\n";
