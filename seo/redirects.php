<?php
/**
 * Rank Math redirects that collapse two-hop chains reported by Search Console ("Página con redirección").
 * Without them, WordPress follows _wp_old_slug / canonical guessing first and Rank Math adds a second 301:
 *   /servicio/sculptra/ -> /servicio/tratamiento-sculptra-en-cdmx/ -> /sculptra-cdmx/
 *   /servicio/          -> /servicios/                             -> /tratamientos/
 * Idempotent. Run: wp eval-file seo/redirects.php
 */

global $wpdb;
$site  = 'https://eternalglow.com.mx';
$table = $wpdb->prefix . 'rank_math_redirections';
$redirects = array(
    'servicio/sculptra' => $site . '/sculptra-cdmx/',
    'servicio'          => $site . '/tratamientos/',
);

$created = 0;
foreach ($redirects as $from => $to) {
    $sources = serialize(array(array('ignore' => '', 'pattern' => $from, 'comparison' => 'exact')));
    // Exact match on the serialized sources: LIKE '%servicio%' would also hit the other rows.
    $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE sources = %s", $sources));
    if ($exists) continue;
    $wpdb->insert($table, array(
        'sources'     => $sources,
        'url_to'      => $to,
        'header_code' => 301,
        'status'      => 'active',
        'created'     => current_time('mysql'),
        'updated'     => current_time('mysql'),
    ));
    $created++;
}
if ($created) $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}rank_math_redirections_cache");

// Self-check: every redirect is present, active and points where it should.
$fail = false;
foreach ($redirects as $from => $to) {
    $sources = serialize(array(array('ignore' => '', 'pattern' => $from, 'comparison' => 'exact')));
    $row = $wpdb->get_row($wpdb->prepare("SELECT url_to, header_code, status FROM $table WHERE sources = %s", $sources));
    if (!$row || $row->url_to !== $to || (int) $row->header_code !== 301 || $row->status !== 'active') {
        echo "FALLO: redireccion /$from/ -> $to\n";
        $fail = true;
    }
}
if ($fail) exit(1);
echo "OK: redirecciones listas ($created creadas, " . (count($redirects) - $created) . " ya existian)\n";
