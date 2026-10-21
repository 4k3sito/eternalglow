<?php
// Nosotros: the doctor's name as the page headline, in the site's serif, instead of a picture of his signature,
// and his cédula profesional at the end of his credentials.
// Run with: wp eval-file seo/nosotros.php  (safe to run twice)
// Written with $wpdb->update because wp_update_post would run kses and strip shortcode markup under WP-CLI.

global $wpdb;

$page = get_page_by_path('nosotros', OBJECT, 'page');
if (!$page) { echo "ERROR: no encuentro la pagina nosotros\n"; exit(1); }

$amir = '<li>Médico certificado en armonización facial por AMIR.</li>';
$changes = array(
	'titulo' => array(
		'[jss-heading text="Conoce a" tag="h2" text_align=",left,center"][jss-empty-space height="8,,"][jss-image image="51" text_align=",left,center" width="467,,"]',
		'[jss-text class="lp-eyebrow" text_align=",left,center"]<p>Conoce a</p>[/jss-text][jss-heading text="Dr. Bruno {{Ocampo}}" tag="h1" class="lp-display" text_align=",left,center"][jss-empty-space height="24,,"]',
	),
	'cedula' => array($amir, $amir . "\n<li>Cédula profesional 13493041.</li>"),
);

$content = $page->post_content;
foreach ($changes as $name => $pair) {
	list($old, $new) = $pair;
	if (strpos($content, $new) !== false) { echo "OK: $name ya estaba aplicado\n"; continue; }
	if (strpos($content, $old) === false) { echo "ERROR: el contenido de nosotros cambio ($name), no aplico nada\n"; exit(1); }
	$content = str_replace($old, $new, $content);
	echo "OK: $name\n";
}

if ($content !== $page->post_content) {
	$dir = WP_CONTENT_DIR . '/uploads/seo-backups';
	wp_mkdir_p($dir);
	if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
	file_put_contents("$dir/nosotros-" . gmdate('Ymd-His') . '.json', wp_json_encode(array('post_content' => $page->post_content)));

	$wpdb->update($wpdb->posts, array('post_content' => $content, 'post_modified' => current_time('mysql'), 'post_modified_gmt' => current_time('mysql', 1)), array('ID' => $page->ID));
	clean_post_cache($page->ID);
	if (function_exists('rocket_clean_post')) rocket_clean_post($page->ID);
	echo "OK: nosotros actualizado\n";
}

// Self-check against what is stored now.
$stored = get_post_field('post_content', $page->ID, 'raw');
foreach ($changes as $name => $pair) {
	if (strpos($stored, $pair[1]) === false) { echo "FALLO: falta $name en nosotros\n"; exit(1); }
}
