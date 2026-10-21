<?php
// Turns on WP Super Cache's preload mode. Run with: wp eval-file seo/supercache-preload.php  (safe to run twice)
// One-off, not part of deploy.sh: it changes plugin settings, not content.
// WP cron was off (DISABLE_WP_CRON, no system cron) from 2026-03 to 2026-10-21, so wp_cache_gc never ran and
// cached pages lived until a deploy purged them. With the system cron back on, garbage collection would delete
// every cached page after $cache_max_time (24 h) and leave it cold for the next visitor (2-11 s TTFB).
// Preload mode skips supercache files in garbage collection and refreshes every published post once a day instead.

const EG_PRELOAD_INTERVAL = 1440; // minutes

if (!function_exists('wp_cache_setting') || !function_exists('wpsc_reset_preload_settings')) {
    echo "ERROR: WP Super Cache no está activo\n";
    exit(1);
}

wp_cache_setting('wp_cache_preload_on', 1);
wp_cache_setting('wp_cache_preload_posts', 'all');
wp_cache_setting('wp_cache_preload_interval', EG_PRELOAD_INTERVAL);
wp_cache_setting('wp_cache_preload_taxonomies', 0);
wp_cache_setting('wp_cache_preload_email_volume', 'none');
wp_cache_setting('wp_cache_preload_email_me', 0);

// Same as saving the preload form in wp-admin: the first refresh runs one interval from now, since deploy.sh
// already warmed the cache. Also clears a leftover stop_preload.txt, which would cancel it.
if (!wp_next_scheduled('wp_cache_full_preload_hook')) {
    wpsc_reset_preload_settings();
    wp_schedule_single_event(time() + EG_PRELOAD_INTERVAL * 60, 'wp_cache_full_preload_hook');
    echo "OK: precarga programada\n";
} else {
    echo "OK: la precarga ya estaba programada\n";
}

// Self-check against the config file itself: that is what the plugin reads on every request.
global $wp_cache_config_file;
$config = file_get_contents($wp_cache_config_file);
foreach (array('wp_cache_preload_on = 1;', "wp_cache_preload_posts = 'all';", 'wp_cache_preload_interval = ' . EG_PRELOAD_INTERVAL . ';') as $line) {
    if (strpos($config, '$' . $line) === false) { echo "ERROR: falta \$$line en $wp_cache_config_file\n"; exit(1); }
}
if (!wp_next_scheduled('wp_cache_full_preload_hook')) { echo "ERROR: no quedó programada la precarga\n"; exit(1); }
echo "OK: modo de precarga activo, cada " . EG_PRELOAD_INTERVAL . " min\n";
