<?php
// Service pages (post type "service") were built with Divi Builder, whose licence belongs to the agency. This rewrites
// their [et_pb_*] shortcodes as plain HTML that the theme styles (.lp-sv-* in _home.scss), so Divi can be removed.
// Run with: wp eval-file seo/divi-to-html.php  (safe to run twice: posts without [et_pb_ are left alone)
// - Only six modules are used: text, blurb, cta, toggle, image, button, inside section/row/column.
// - The first section's lone image is the coral title banner the theme already strips (eg_drop_service_banner).
// - Modules hidden on desktop (disabled_on "…|…|on") are the phone-only copies of a photo: dropped.
// - A cta module's button_text without button_url is never rendered by Divi, so it is not carried over.
// - The original content goes to post meta _eg_divi_content and to a JSON backup in uploads/seo-backups.
// Uses $wpdb->update, not wp_update_post: kses and the post-save hooks are not wanted here.

if (!defined('ABSPATH')) { $_SERVER['SERVER_NAME'] = 'localhost'; $_SERVER['HTTP_HOST'] = 'localhost:8080'; require '/var/www/html/wp-load.php'; }
global $wpdb;

const EG_DIVI_TAG = '/\[(\/?)(et_pb_[a-z_]+)((?:\s+[a-z0-9_]+="[^"]*")*)\s*\]/';

// Divi stores quotes and brackets inside attribute values as %22, %91 and %93.
function eg_divi_attr($attrs, $key){
    $value = isset($attrs[$key]) ? $attrs[$key] : '';
    return str_replace(array('%22', '%91', '%93'), array('"', '[', ']'), $value);
}

// Shortcodes to a tree: array('tag', 'attrs', 'children', 'body'), where body is the raw HTML of a leaf module.
function eg_divi_parse($content){
    if (!preg_match_all(EG_DIVI_TAG, $content, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
        throw new RuntimeException('sin shortcodes de Divi');
    }
    $root = array('tag' => 'root', 'attrs' => array(), 'children' => array(), 'body' => '');
    $stack = array(&$root);
    $last = 0;
    foreach ($m as $match) {
        $offset = $match[0][1];
        $top = &$stack[count($stack) - 1];
        $top['body'] .= substr($content, $last, $offset - $last);
        $last = $offset + strlen($match[0][0]);
        if ($match[1][0] === '/') {
            if ($top['tag'] !== $match[2][0]) {
                throw new RuntimeException("cierre {$match[2][0]} dentro de {$top['tag']} en el byte $offset");
            }
            array_pop($stack);
            unset($top);
            continue;
        }
        $attrs = array();
        if (preg_match_all('/([a-z0-9_]+)="([^"]*)"/', $match[3][0], $pairs, PREG_SET_ORDER)) {
            foreach ($pairs as $pair) $attrs[$pair[1]] = $pair[2];
        }
        $top['children'][] = array('tag' => $match[2][0], 'attrs' => $attrs, 'children' => array(), 'body' => '');
        $stack[] = &$top['children'][count($top['children']) - 1];
        unset($top);
    }
    if (count($stack) !== 1) throw new RuntimeException('shortcodes sin cerrar');
    if (trim(substr($content, $last)) !== '') throw new RuntimeException('texto después del último shortcode');
    return $root;
}

// Divi's text bodies carry ChatGPT paste leftovers (data-start, empty spans) and the old coral colour in inline
// styles. Keep the structure and the links, drop every other attribute and unwrap spans.
function eg_divi_clean($html){
    $html = trim($html);
    if ($html === '') return '';
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8"?><div id="eg-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    $root = $doc->getElementById('eg-root');
    $keep = array('p', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li', 'h1', 'h2', 'h3', 'h4', 'a', 'br');
    // Only wrappers are unwrapped. Anything else (img, iframe, table...) would lose content the word check cannot see.
    $unwrap = array('span', 'div', 'font');
    $walk = function ($node) use (&$walk, $keep, $unwrap) {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if (!($child instanceof DOMElement)) continue;
            $walk($child);
            $tag = strtolower($child->tagName);
            if (in_array($tag, $unwrap, true)) {
                while ($child->firstChild) $node->insertBefore($child->firstChild, $child);
                $node->removeChild($child);
                continue;
            }
            if (!in_array($tag, $keep, true)) throw new RuntimeException("etiqueta <$tag> sin conversión");
            foreach (iterator_to_array($child->attributes) as $attr) {
                if ($tag !== 'a' || !in_array($attr->name, array('href', 'target', 'rel'), true)) $child->removeAttribute($attr->name);
            }
            if ($tag === 'a' && $child->hasAttribute('href')) $child->setAttribute('href', esc_url($child->getAttribute('href')));
        }
    };
    $walk($root);
    $out = '';
    foreach ($root->childNodes as $child) $out .= $doc->saveHTML($child);
    // No line breaks: once Divi is off, wpautop runs on this content and would turn them into <br> or <p>.
    $out = preg_replace('/\s*\n\s*/', ' ', $out);
    // Paragraphs left empty by the unwrapped spans.
    return trim(preg_replace('/<p>(\s|&nbsp;|\xC2\xA0)*<\/p>/u', '', $out));
}

// Strip the outer <p> of one-paragraph bodies (blurb list items, card text) so they sit inline.
function eg_divi_inline($html){
    return preg_match('/^<p>((?:(?!<p>).)*)<\/p>$/s', $html, $m) ? $m[1] : $html;
}

function eg_divi_hidden_on_desktop($attrs){
    $parts = explode('|', isset($attrs['disabled_on']) ? $attrs['disabled_on'] : '');
    return isset($parts[2]) && $parts[2] === 'on';
}

function eg_divi_image($attrs){
    $src = eg_divi_attr($attrs, 'src');
    if ($src === '') return '';
    // Divi saved absolute production URLs: rebase them on this site's uploads so the attachment is found locally too.
    if (preg_match('#/wp-content/uploads/(.+)$#', $src, $m)) $src = wp_get_upload_dir()['baseurl'] . '/' . $m[1];
    // wp-image-N lets WordPress add srcset, sizes, width/height and lazy loading when it renders the content.
    $id = attachment_url_to_postid($src);
    $meta = $id ? wp_get_attachment_metadata($id) : null;
    $size = $meta && !empty($meta['width']) ? sprintf(' width="%d" height="%d"', $meta['width'], $meta['height']) : '';
    $class = $id ? sprintf(' class="wp-image-%d"', $id) : '';
    return sprintf('<figure class="lp-sv-media"><img src="%s" alt="%s"%s%s></figure>', esc_url($src), esc_attr(eg_divi_attr($attrs, 'alt')), $class, $size);
}

// One module to HTML. Lists, cards and questions are wrapped by eg_divi_modules(), which groups neighbours.
// Attributes this converter does not carry over. None is used today; if one shows up, fail instead of dropping it.
const EG_DIVI_UNSUPPORTED = array('et_pb_blurb' => array('title', 'url', 'image'), 'et_pb_cta' => array('button_url'), 'et_pb_image' => array('url'));

function eg_divi_module($node){
    foreach (isset(EG_DIVI_UNSUPPORTED[$node['tag']]) ? EG_DIVI_UNSUPPORTED[$node['tag']] : array() as $key) {
        if (trim(eg_divi_attr($node['attrs'], $key)) !== '') throw new RuntimeException("{$node['tag']} con $key, sin conversión");
    }
    $body = eg_divi_clean($node['body']);
    switch ($node['tag']) {
        case 'et_pb_text':
            return $body;
        case 'et_pb_blurb':
            return '<li>' . eg_divi_inline($body) . '</li>';
        case 'et_pb_cta':
            $title = trim(eg_divi_attr($node['attrs'], 'title'));
            return '<div class="lp-sv-card">' . ($title !== '' ? '<h3>' . esc_html($title) . '</h3>' : '') . $body . '</div>';
        case 'et_pb_toggle':
            return '<details><summary><h3>' . esc_html(trim(eg_divi_attr($node['attrs'], 'title'))) . '</h3></summary><div>' . $body . '</div></details>';
        case 'et_pb_image':
            return eg_divi_hidden_on_desktop($node['attrs']) ? '' : eg_divi_image($node['attrs']);
        case 'et_pb_button':
            $url = eg_divi_attr($node['attrs'], 'button_url');
            $text = trim(eg_divi_attr($node['attrs'], 'button_text'));
            if ($url === '' || $text === '') return '';
            // Every button today opens a wa.link chat; only those are tagged for analytics and open in a new tab.
            $chat = preg_match('#^https://(wa\.link|wa\.me|api\.whatsapp\.com)/#', $url) ? ' target="_blank" rel="noopener" data-cta="servicio-whatsapp"' : '';
            return sprintf('<p class="lp-sv-action"><a class="btn btn-primary lp-btn-arrow" href="%s"%s>%s</a></p>', esc_url($url), $chat, esc_html($text));
    }
    throw new RuntimeException("módulo sin conversión: {$node['tag']}");
}

function eg_divi_modules($modules){
    $wrap = array('et_pb_blurb' => array('<ul class="lp-sv-checks">', '</ul>'), 'et_pb_cta' => array('<div class="lp-sv-cards">', '</div>'), 'et_pb_toggle' => array('<div class="lp-sv-faq jss-text">', '</div>')); // jss-text: the site's accordion style
    $out = '';
    $open = null;
    foreach ($modules as $node) {
        if ($node['children']) throw new RuntimeException("{$node['tag']} con módulos dentro");
        $group = isset($wrap[$node['tag']]) ? $node['tag'] : null;
        if ($open !== $group) {
            if ($open) $out .= $wrap[$open][1];
            if ($group) $out .= $wrap[$group][0];
            $open = $group;
        }
        $out .= eg_divi_module($node);
    }
    return $out . ($open ? $wrap[$open][1] : '');
}

// Returns array(html, whether the title banner was dropped).
function eg_divi_to_html($content){
    $tree = eg_divi_parse($content);
    $out = '';
    $banner = false;
    foreach ($tree['children'] as $i => $section) {
        if ($section['tag'] !== 'et_pb_section') throw new RuntimeException("{$section['tag']} fuera de una sección");
        $modules = array();
        foreach ($section['children'] as $row) {
            foreach ($row['children'] as $column) $modules = array_merge($modules, $column['children']);
        }
        if ($i === 0 && count($modules) === 1 && $modules[0]['tag'] === 'et_pb_image') {
            $banner = true;
            continue;
        }
        $html = '';
        foreach ($section['children'] as $row) {
            if ($row['tag'] !== 'et_pb_row') throw new RuntimeException("{$row['tag']} dentro de una sección");
            $columns = array_map(function ($column) { return eg_divi_modules($column['children']); }, $row['children']);
            $columns = array_values(array_filter($columns, 'strlen'));
            if (count($columns) > 1) {
                $html .= '<div class="lp-sv-cols"><div>' . implode('</div><div>', $columns) . '</div></div>';
            } elseif ($columns) {
                $html .= $columns[0];
            }
        }
        if ($html !== '') $out .= '<section class="lp-sv-section">' . $html . '</section>';
    }
    return array($out, $banner);
}

// Words a visitor can read, as a sorted bag: computed from the source without the converter's parser, so the
// self-check does not trust the code it is checking.
function eg_divi_words($html){
    $text = html_entity_decode(wp_strip_all_tags(str_replace('<', ' <', $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $words = preg_split('/[\s\x{00A0}]+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
    // SORT_STRING: the default flag compares numeric strings ("10", "1.0") as numbers and is not a total order.
    sort($words, SORT_STRING);
    return $words;
}

function eg_divi_source_words($content){
    $extra = '';
    // Titles and button labels live in attributes. cta button_text is left out: Divi shows no button without a URL,
    // and a cta with a URL stops the converter (EG_DIVI_UNSUPPORTED).
    preg_match_all('/\[(et_pb_cta|et_pb_toggle)\s[^\]]*?\btitle="([^"]*)"/', $content, $titles);
    preg_match_all('/\[et_pb_button\s[^\]]*?\bbutton_text="([^"]*)"/', $content, $buttons);
    foreach (array_merge($titles[2], $buttons[1]) as $value) $extra .= ' ' . eg_divi_attr(array('v' => $value), 'v');
    return eg_divi_words(preg_replace(EG_DIVI_TAG, ' ', $content) . $extra);
}

// Every status but trash: a private or scheduled service would otherwise show raw shortcodes once Divi is gone.
const EG_DIVI_STATUSES = "post_status NOT IN ('trash', 'auto-draft', 'inherit')";
$posts = $wpdb->get_results("SELECT ID, post_name, post_status, post_content FROM $wpdb->posts WHERE post_type = 'service' AND " . EG_DIVI_STATUSES . " AND post_content LIKE '%[et\\_pb\\_%' ORDER BY ID");
if (!$posts) { echo "OK: ningún servicio con Divi, nada que convertir\n"; return; }

$backup = array();
$converted = array();
foreach ($posts as $post) {
    try {
        list($html, $banner) = eg_divi_to_html($post->post_content);
    } catch (RuntimeException $e) {
        echo "ERROR: {$post->post_name}: {$e->getMessage()}\n";
        exit(1);
    }
    // Self-check before writing: same words, no shortcode left, every visible photo kept.
    if (eg_divi_words($html) !== eg_divi_source_words($post->post_content)) {
        // Bags, not sets: a word can be missing once and still appear elsewhere.
        $a = array_count_values(eg_divi_source_words($post->post_content));
        $b = array_count_values(eg_divi_words($html));
        $diff = array();
        foreach ($a + $b as $word => $_) {
            $n = (isset($b[$word]) ? $b[$word] : 0) - (isset($a[$word]) ? $a[$word] : 0);
            if ($n) $diff[] = sprintf('%s%+d', $word, $n);
        }
        echo "ERROR: {$post->post_name}: el texto no coincide: " . implode(' ', array_slice($diff, 0, 20)) . "\n";
        exit(1);
    }
    if (strpos($html, '[et_pb_') !== false || strpos($html, '[/et_pb_') !== false) { echo "ERROR: {$post->post_name}: quedó un shortcode\n"; exit(1); }
    preg_match_all('/\[et_pb_image\s[^\]]*\]/', $post->post_content, $images);
    $visible = count(array_filter($images[0], function ($tag) { return !preg_match('/disabled_on="[^"|]*\|[^"|]*\|on"/', $tag); })) - (int)$banner;
    if (substr_count($html, '<img ') !== $visible) { echo "ERROR: {$post->post_name}: " . substr_count($html, '<img ') . " fotos, esperaba $visible\n"; exit(1); }
    $converted[$post->ID] = array($post, $html);
}

$dir = WP_CONTENT_DIR . '/uploads/seo-backups';
wp_mkdir_p($dir);
if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
foreach ($converted as $id => list($post)) {
    $backup[$id] = array('post_name' => $post->post_name, 'post_content' => $post->post_content, '_et_pb_use_builder' => get_post_meta($id, '_et_pb_use_builder', true));
}
$file = "$dir/divi-to-html-" . gmdate('Ymd-His') . '.json';
if (!file_put_contents($file, wp_json_encode($backup))) { echo "ERROR: no pude escribir el respaldo $file\n"; exit(1); }
echo "OK: respaldo en $file\n";

foreach ($converted as $id => list($post, $html)) {
    // add_post_meta with $unique: a second run never overwrites the first copy of the Divi original.
    if (!add_post_meta($id, '_eg_divi_content', wp_slash($post->post_content), true) && get_post_meta($id, '_eg_divi_content', true) === '') {
        echo "ERROR: {$post->post_name}: no se guardó el original en _eg_divi_content\n";
        exit(1);
    }
    if ($wpdb->update($wpdb->posts, array('post_content' => $html), array('ID' => $id)) === false) { echo "ERROR: {$post->post_name}: no se guardó\n"; exit(1); }
    // Off: Divi would otherwise keep wrapping the page in its builder layout while it is still installed.
    update_post_meta($id, '_et_pb_use_builder', 'off');
    clean_post_cache($id);
    printf("OK: %s (%s) %d KB → %d KB\n", $post->post_name, $post->post_status, strlen($post->post_content) / 1024, strlen($html) / 1024);
}

// Final check from the database.
$left = (int)$wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'service' AND " . EG_DIVI_STATUSES . " AND post_content LIKE '%[et\\_pb\\_%'");
if ($left) { echo "ERROR: quedan $left servicios con Divi\n"; exit(1); }

wp_cache_flush();
// Run by hand outside deploy.sh, so the page cache (WP Super Cache) is purged here.
if (function_exists('wp_cache_clear_cache')) wp_cache_clear_cache();
echo 'OK: divi-to-html aplicado a ' . count($converted) . " servicios\n";
