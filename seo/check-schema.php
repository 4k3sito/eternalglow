<?php
// Read-only check of the theme's filter over the schema plugin's per-post JSON-LD (includes/_schema-saswp.php).
// Usage on the server:
//   after a deploy:     wp eval-file check-schema.php
//   before a deploy:    wp eval-file check-schema.php path/to/new/_schema-saswp.php --skip-themes
// (--skip-themes, or the active theme's older copy of the functions would be tested instead). Exits 1 on any failure.

if (isset($args[0])) {
    if (function_exists('eg_saswp_nodes')) { echo "FALLO: el tema activo ya define eg_saswp_nodes; corre con --skip-themes\n"; exit(1); }
    require $args[0];
} elseif (!function_exists('eg_saswp_nodes')) {
    require get_template_directory() . '/includes/_schema-saswp.php';
}

$business = array('MedicalBusiness', 'MedicalClinic', 'LocalBusiness', 'Organization', 'WebSite');
$clinic = home_url('/#clinic');

// Walks a node tree and lists what the old business entity used to carry; the clinic may only appear as a bare @id.
function eg_check_node($node, $business, &$problems, $path = '') {
    if (!is_array($node)) return;
    if (array_intersect((array)($node['@type'] ?? ''), $business)) $problems[] = "negocio en $path";
    foreach (array('aggregateRating', 'review', 'recognizingAuthority') as $key) if (isset($node[$key])) $problems[] = "$key en $path";
    if (isset($node['@id']) && is_string($node['@id']) && preg_match('/#(medicalbusiness|organization)$/i', $node['@id'])) $problems[] = "ref {$node['@id']}";
    foreach ($node as $key => $value) eg_check_node($value, $business, $problems, "$path/$key");
}

function eg_check_ids($node, &$defined, &$refs) {
    if (!is_array($node)) return;
    if (isset($node['@id']) && is_string($node['@id'])) {
        if (count($node) === 1) $refs[] = $node['@id']; else $defined[] = $node['@id'];
    }
    foreach ($node as $value) eg_check_ids($value, $defined, $refs);
}

$fail = 0;

// Synthetic cases: the clinic collapses to #clinic, a manufacturer Organization is kept as is.
$sample = eg_saswp_clean(array(
    '@type' => 'Service',
    'provider' => array('@type' => 'Organization', 'name' => 'Eternal Glow'),
    'brand' => array('@type' => 'Organization', 'name' => 'Galderma', '@id' => 'https://www.galderma.com/#organization'),
    'aggregateRating' => array('@type' => 'AggregateRating', 'ratingValue' => 5),
));
$expect = array(
    'provider -> #clinic' => ($sample['provider'] ?? null) === array('@id' => $clinic),
    'brand se conserva' => ($sample['brand']['name'] ?? '') === 'Galderma',
    'sin aggregateRating' => !isset($sample['aggregateRating']),
);
foreach ($expect as $label => $ok) { if (!$ok) { echo "FALLO caso sintetico: $label\n"; $fail++; } }

global $wpdb;
$ids = $wpdb->get_col("SELECT pm.post_id FROM $wpdb->postmeta pm JOIN $wpdb->posts p ON p.ID = pm.post_id WHERE pm.meta_key = 'saswp_custom_schema_field' AND pm.meta_value <> '' AND p.post_status = 'publish'");
foreach ($ids as $id) {
    $nodes = eg_saswp_nodes((int)$id);
    $problems = array();
    eg_check_node($nodes, $business, $problems);
    $json = wp_json_encode($nodes, JSON_UNESCAPED_SLASHES);
    if (strpos($json, '5536460497') !== false) $problems[] = 'telefono viejo';
    // Every bare {"@id": x} reference must point to a node defined on the page, or to the theme's #clinic.
    $defined = array($clinic); $refs = array();
    eg_check_ids($nodes, $defined, $refs);
    foreach (array_diff(array_unique($refs), $defined) as $ref) $problems[] = "ref colgante $ref";
    printf("%-6s %-50s %s%s\n", $problems ? 'FALLO' : 'ok', get_post_field('post_name', $id), implode(',', eg_schema_types($nodes)), $problems ? '  <- ' . implode('; ', array_unique($problems)) : '');
    if ($problems) $fail++;
}
echo $fail ? "FALLO: $fail problemas\n" : 'OK: ' . count($ids) . " posts publicados sin entidad duplicada\n";
if ($fail) exit(1);
