<?php
// /servicio/armonizacion-facial-con-acido-hialuronico/ was written as the "3-4 jeringas" package, next to a
// "5-6 ml" full face page that seo/prices.php retired into it. The clinic now sells one facial harmonization,
// priced per 1 mL syringe ($6,499) and by package (2, 3 or 4 syringes, the same list as the theme's
// includes/_landings.php), so the title, the H1 and the headings drop "3-4 jeringas", and the "3-4 jeringas vs 5-6 ml"
// section and FAQ become the price per amount. Approved by the user on 2026-10-28.
// Run with: wp eval-file seo/armonizacion.php  (safe to run twice: each phrase is replaced only while it is there;
// EG_DRY=1 wp eval-file seo/armonizacion.php checks it without writing)
// Uses $wpdb->update, not wp_update_post: WP-CLI runs without a user and kses would change the markup.

global $wpdb;

$slug = 'armonizacion-facial-con-acido-hialuronico';
$title = 'Armonización facial con ácido hialurónico';
$prices = 'Cada jeringa de 1 mL cuesta $6,499, y por paquete el precio baja: 2 jeringas $11,000, 3 jeringas $16,000 y 4 jeringas $20,000.';
$faq_answer = 'Cada jeringa de 1 mL de ácido hialurónico cuesta $6,499. Por paquete: 2 jeringas $11,000, 3 jeringas $16,000 y 4 jeringas $20,000. La cantidad se define en la valoración.';

// Page copy: old => new. The mentions of 3 or 4 syringes as one option among others stay.
$content_swaps = array(
    '<h1>Armonización facial con ácido hialurónico 3-4 jeringas en CDMX</h1>' => '<h1>Armonización facial con ácido hialurónico en CDMX</h1>',
    '<h2>¿Qué es la armonización facial con ácido hialurónico 3-4 jeringas?</h2>' => '<h2>¿Qué es la armonización facial con ácido hialurónico?</h2>',
    '<strong>armonización facial con ácido hialurónico</strong> 3-4 jeringas es un tratamiento' => '<strong>armonización facial con ácido hialurónico</strong> es un tratamiento',
    '<strong>armonización facial con ácido hialurónico</strong> 3-4 jeringas es un procedimiento' => '<strong>armonización facial con ácido hialurónico</strong> es un procedimiento',
    'La armonización facial con ácido hialurónico 3-4 jeringas está pensada' => 'La armonización facial con ácido hialurónico está pensada',
    '<strong>La armonización facial con ácido hialurónic</strong>o 3-4 jeringas está recomendada' => '<strong>La armonización facial con ácido hialurónico</strong> está recomendada',
    '<h2>¿Qué zonas se pueden trabajar con 3-4 jeringas?</h2>' => '<h2>¿Qué zonas se pueden trabajar?</h2>',
    '<h2>Armonización facial 3-4 jeringas vs 5-6 ml</h2>' => '<h2>¿Cuántas jeringas necesito y cuánto cuestan?</h2>',
    '<p><strong>La diferencia principal entre una armonización facial de 3-4 jeringas y una de 5-6 ml</strong> está en el nivel de corrección, la cantidad de zonas que pueden tratarse y el grado de transformación esperado.</p>' =>
        '<p><strong>La cantidad de jeringas depende</strong> del nivel de corrección, la cantidad de zonas que pueden tratarse y el grado de cambio esperado. ' . $prices . '</p>',
    '<p><strong>La armonización facial de 5-6 ml suele recomendarse</strong> cuando se busca' => '<p><strong>Una cantidad mayor suele recomendarse</strong> cuando se busca',
    '<strong>Si estás buscando armonización facial con ácido hialurónico</strong> 3-4 jeringas en CDMX,' => '<strong>Si estás buscando armonización facial con ácido hialurónico</strong> en CDMX,',
    '<h3>7. ¿Cuál es la diferencia entre 3-4 jeringas y 5-6 ml?</h3>' => '<h3>7. ¿Cuánto cuesta la armonización facial?</h3>',
    '<p>La armonización de 3-4 jeringas suele enfocarse en una mejora más sutil o inicial, trabajando zonas estratégicas. La de 5-6 ml permite un abordaje más integral, ideal para mayor pérdida de volumen o más zonas a tratar.</p>' =>
        '<p>' . $faq_answer . '</p>',
);

// The page's own JSON-LD (SASWP custom schema): the same names and the same FAQ answer, in plain text.
$schema_swaps = array(
    '"Armonización facial con ácido hialurónico 3-4 jeringas en CDMX"' => '"Armonización facial con ácido hialurónico en CDMX"',
    '"Armonización facial con ácido hialurónico 3-4 jeringas"' => '"Armonización facial con ácido hialurónico"',
    '"¿Cuál es la diferencia entre 3-4 jeringas y 5-6 ml?"' => '"¿Cuánto cuesta la armonización facial?"',
    '"La armonización de 3-4 jeringas suele enfocarse en una mejora más sutil o inicial, trabajando zonas estratégicas. La de 5-6 ml permite un abordaje más integral, ideal para mayor pérdida de volumen o más zonas a tratar."' =>
        '"' . $faq_answer . '"',
);

// Each swap must find its old phrase, or its new one when this already ran; anything else means the page changed.
function eg_apply_swaps(string $text, array $swaps, string $what): string {
    foreach ($swaps as $old => $new) {
        if (strpos($text, $old) !== false) {
            $text = str_replace($old, $new, $text);
        } elseif (strpos($text, $new) === false) {
            echo "ERROR: $what no tiene: " . mb_substr(strip_tags($old), 0, 80) . "\n";
            exit(1);
        }
    }
    return $text;
}

$post = $wpdb->get_row($wpdb->prepare("SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_name = %s AND post_type = 'service' AND post_status = 'publish'", $slug));
if (!$post) { echo "ERROR: no existe el servicio $slug\n"; exit(1); }
$schema = (string) get_post_meta($post->ID, 'saswp_custom_schema_field', true);

$content = eg_apply_swaps($post->post_content, $content_swaps, 'el contenido');
$new_schema = $schema === '' ? '' : eg_apply_swaps($schema, $schema_swaps, 'el schema');
if ($new_schema !== '' && json_decode(preg_replace('#</?script[^>]*>#', '', $new_schema)) === null) { echo "ERROR: el schema quedó como JSON inválido\n"; exit(1); }

foreach (array('5-6 ml', 'hialurónico 3-4 jeringas', 'hialurónic</strong>o') as $bad) {
    if (strpos($content . $new_schema, $bad) !== false) { echo "ERROR: todavía dice '$bad'\n"; exit(1); }
}

// EG_DRY=1 checks every phrase and prints the new headings without writing anything.
if (getenv('EG_DRY')) {
    preg_match_all('#<h[1-3]>(.*?)</h[1-3]>#', $content, $m);
    echo "DRY: titulo '$title'\n" . implode("\n", $m[1]) . "\n";
    exit(0);
}

$changed = 0;
if ($post->post_title !== $title || $content !== $post->post_content || $new_schema !== $schema) {
    $dir = WP_CONTENT_DIR . '/uploads/seo-backups';
    wp_mkdir_p($dir);
    if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
    file_put_contents("$dir/armonizacion-" . gmdate('Ymd-His') . '.json', wp_json_encode(array('post_title' => $post->post_title, 'post_content' => $post->post_content, 'saswp_custom_schema_field' => $schema)));
    $wpdb->update($wpdb->posts, array('post_title' => $title, 'post_content' => $content), array('ID' => $post->ID));
    if ($new_schema !== $schema) {
        $wpdb->update($wpdb->postmeta, array('meta_value' => $new_schema), array('post_id' => $post->ID, 'meta_key' => 'saswp_custom_schema_field'));
    }
    clean_post_cache($post->ID);
    $changed = 1;
}

wp_cache_flush();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: $changed pagina actualizada\n";
