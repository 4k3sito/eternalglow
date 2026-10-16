<?php
// Content fixes the client reported on 2026-10-13. Run with: wp eval-file seo/content-fixes.php  (safe to run twice)
// - /botox-cdmx/ showed the hilos tensores procedure steps (flacidez, elevación, 5 días sin movimientos bruscos).
// - /hilos-tensores-cdmx/ repeated its aftercare text under "Aplicación" and had the "Post-tramiento" typo.
// - The "Nuestros servicios" cards on the three hubs offered limpiezas profundas, microcorrientes and mesoterapia,
//   which are not on the clinic's price list, and pointed to /tratamientos/ anchors that no longer exist.
// The steps and cards live URL-encoded JSON in the `texts` attribute of [jss-procedure] and [jss-services]
// (see the theme's includes/jss-templates), so plain text search in the database does not find them.
// Uses $wpdb->update, not wp_update_post: WP-CLI runs without a user and kses would strip the shortcodes.

global $wpdb;

$procedures = array(
    // slug => steps (title, text); the step images already on the page are kept.
    'botox-cdmx' => array(
        array('Valoración', 'El médico revisa tu expresión en reposo y en movimiento, tus antecedentes y lo que quieres suavizar. Define las zonas y las unidades necesarias para un resultado natural.'),
        array('Aplicación', 'Se aplican microinyecciones con aguja muy fina en los músculos elegidos. La sesión dura de 10 a 20 minutos y puedes volver a tus actividades el mismo día.'),
        array('Post-tratamiento', 'No te acuestes ni presiones o masajees la zona durante 4 horas, y evita ejercicio intenso, sauna y calor durante 24 horas. El efecto aparece entre 3 y 7 días y es completo a los 14.'),
    ),
    'hilos-tensores-cdmx' => array(
        array('Valoración', 'Analizamos el grado de flacidez y las zonas que necesitan elevación. Diseñamos un plan personalizado según la estructura facial y el resultado que deseas lograr.'),
        array('Aplicación', 'Con anestesia local, se colocan los hilos bajo la piel con cánula o aguja fina siguiendo los vectores de elevación definidos. La sesión dura entre 30 y 60 minutos.'),
        array('Post-tratamiento', 'Puede haber ligera inflamación o sensación de tirantez temporal. Recomendamos evitar movimientos bruscos, ejercicio intenso y masajes faciales durante los primeros 5 días.'),
    ),
);

// Same three cards on every hub, matching the categories of the clinic's price list (theme includes/_treatments.php).
$services = array(
    array('Botox y rellenos', 'Toxina botulínica y ácido hialurónico para suavizar líneas, devolver volumen y definir el contorno con un resultado natural.', 'botox'),
    array('Bioestimuladores', 'Sculptra, Radiesse y Rejuran estimulan tu propio colágeno para mejorar la firmeza y la calidad de la piel de forma gradual.', 'bio'),
    array('Piel y aparatología', 'HIFU, Hollywood Peel, microneedling, peeling químico y carboxiterapia para reafirmar y mejorar textura y luminosidad.', 'piel'),
);
$service_hubs = array('botox-cdmx', 'sculptra-cdmx', 'hilos-tensores-cdmx');

// WPBakery stores the attribute as rawurlencode(json); json_decode(urldecode()) in the templates reads it back.
function eg_encode_texts(array $items): string {
    return rawurlencode(json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

// Rewrites the `texts` attribute of the first [$tag] in $content; $build gets the decoded items and returns new ones.
function eg_rewrite_texts(string $content, string $tag, callable $build, string $slug): string {
    $re = '/(\[' . preg_quote($tag, '/') . '\s[^\]]*?texts=")([^"]*)(")/';
    if (!preg_match($re, $content, $m)) { echo "ERROR: $slug no tiene [$tag]\n"; exit(1); }
    $old = json_decode(urldecode($m[2]), true);
    if (!is_array($old) || count($old) !== 3) { echo "ERROR: $slug [$tag] no tiene 3 elementos\n"; exit(1); }
    $new = eg_encode_texts($build($old));
    return preg_replace($re, '${1}' . str_replace('$', '\$', $new) . '${3}', $content, 1);
}

$changed = 0;
foreach (array_unique(array_merge(array_keys($procedures), $service_hubs)) as $slug) {
    $post = $wpdb->get_row($wpdb->prepare("SELECT ID, post_content FROM $wpdb->posts WHERE post_name = %s AND post_type = 'page' AND post_status = 'publish'", $slug));
    if (!$post) { echo "ERROR: no existe $slug\n"; exit(1); }
    $content = $post->post_content;

    if (isset($procedures[$slug])) {
        $steps = $procedures[$slug];
        $content = eg_rewrite_texts($content, 'jss-procedure', function ($old) use ($steps) {
            $out = array();
            foreach ($steps as $i => $s) $out[] = array('image' => $old[$i]['image'] ?? '', 'title' => $s[0], 'text' => $s[1]);
            return $out;
        }, $slug);
    }
    if (in_array($slug, $service_hubs, true)) {
        $content = eg_rewrite_texts($content, 'jss-services', function ($old) use ($services) {
            $out = array();
            foreach ($services as $i => $s) $out[] = array(
                'image' => $old[$i]['image'] ?? '', 'title' => $s[0], 'text' => $s[1], 'btn_text' => 'Ver más',
                'url' => 'url:' . rawurlencode(home_url('/tratamientos/#' . $s[2])),
            );
            return $out;
        }, $slug);
    }

    if ($content === $post->post_content) continue;
    $wpdb->update($wpdb->posts, array('post_content' => $content), array('ID' => $post->ID));
    clean_post_cache($post->ID);
    $changed++;
}

// Self-check on the rewritten attributes only: Sculptra's aftercare legitimately says to postpone limpiezas profundas.
function eg_texts_of(string $slug, string $tag): string {
    preg_match('/\[' . $tag . '\s[^\]]*?texts="([^"]*)"/', get_page_by_path($slug)->post_content, $m);
    return urldecode($m[1] ?? '');
}
foreach ($service_hubs as $slug) {
    foreach (array('microcorrientes', 'Limpiezas', 'mesoterapia', 'aparatologia-y-tratamientos') as $bad) {
        if (stripos(eg_texts_of($slug, 'jss-services'), $bad) !== false) { echo "ERROR: $slug todavía dice '$bad'\n"; exit(1); }
    }
}
foreach (array_keys($procedures) as $slug) {
    if (stripos(eg_texts_of($slug, 'jss-procedure'), 'tramiento"') !== false) { echo "ERROR: $slug todavía dice 'Post-tramiento'\n"; exit(1); }
}
if (stripos(eg_texts_of('botox-cdmx', 'jss-procedure'), 'flacidez') !== false) { echo "ERROR: botox-cdmx todavía habla de flacidez\n"; exit(1); }

wp_cache_flush();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: $changed paginas actualizadas\n";
