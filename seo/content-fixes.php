<?php
// Content fixes the client reported on 2026-10-13. Run with: wp eval-file seo/content-fixes.php  (safe to run twice)
// - /botox-cdmx/ showed the hilos tensores procedure steps (flacidez, elevación, 5 días sin movimientos bruscos).
// - /hilos-tensores-cdmx/ repeated its aftercare text under "Aplicación" and had the "Post-tramiento" typo.
// - The "Nuestros servicios" cards on the three hubs offered limpiezas profundas, microcorrientes and mesoterapia,
//   which are not on the clinic's price list, and pointed to /tratamientos/ anchors that no longer exist.
// The steps and cards live URL-encoded JSON in the `texts` attribute of [jss-procedure] and [jss-services]
// (see the theme's includes/jss-templates), so plain text search in the database does not find them.
// - 2026-10-20 audit, /botox-cdmx/: the intro promised guaranteed results, the onset was given as both 48-72 h and
//   3-7 days, and two template sections ("Belleza natural…", "Nuestros Servicios") sat between the copy and the prices.
//   The new wording was approved by the user on 2026-10-20.
// Uses $wpdb->update, not wp_update_post: WP-CLI runs without a user and kses would strip the shortcodes.

global $wpdb;

$procedures = array(
    // slug => steps (title, text); the step images already on the page are kept.
    'botox-cdmx' => array(
        array('Valoración', 'El médico revisa tu expresión en reposo y en movimiento, tus antecedentes y lo que quieres suavizar. Define las zonas y las unidades necesarias para un resultado natural.'),
        array('Aplicación', 'Se aplican microinyecciones con aguja muy fina en los músculos elegidos. La sesión dura de 10 a 20 minutos y puedes volver a tus actividades el mismo día.'),
        array('Post-tratamiento', 'No te acuestes ni presiones o masajees la zona durante 4 horas, y evita ejercicio intenso, sauna y calor durante 24 horas. Los primeros cambios se notan entre 48 y 72 horas y el efecto es completo a los 14 días.'),
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
$service_hubs = array('sculptra-cdmx', 'hilos-tensores-cdmx');

// Botox hub copy: exact phrases replaced in place (str_replace is a no-op once applied, so the script stays rerunnable).
$botox_copy = array(
    'contamos con especialistas certificados que garantizan resultados seguros y naturales.' =>
        'lo aplica el Dr. Bruno Ocampo, médico estético, después de una valoración. Cuesta desde $165 MXN por unidad: Baby Botox (30 unidades) $4,499 y Full Face (60 unidades) $8,499. El resultado varía en cada paciente.',
    'sus efectos comienzan a notarse entre 48 a 72 horas después, con resultados visibles por hasta 5 meses.' =>
        'los primeros cambios se notan entre 48 y 72 horas después, el efecto completo se ve a los 14 días y dura de 3 a 5 meses.',
    'Resultados visibles a partir de las 48 a 72 horas' => 'Primeros cambios entre 48 y 72 horas, efecto completo a los 14 días',
);
// Whole outer [vc_row] sections dropped from the Botox hub, found by their heading. [vc_row_inner] does not end the match.
$botox_drop = array('Belleza natural para cada tipo de piel', 'Nuestros {{Servicios}}');

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

    if ($slug === 'botox-cdmx') {
        $content = str_replace(array_keys($botox_copy), array_values($botox_copy), $content);
        foreach ($botox_drop as $heading) {
            // The tempered part refuses to cross any [vc_row or [/vc_row, so the match never spans two rows.
            $content = preg_replace('/\[vc_row(?:\s[^\]]*)?\](?:(?!\[\/?vc_row[\s\]]).)*?' . preg_quote($heading, '/') . '.*?\[\/vc_row\]/s', '', $content, 1);
            // preg_replace returns null on a PCRE failure: writing that would empty the hub.
            if ($content === null) { echo "ERROR: regex botox-cdmx: " . preg_last_error_msg() . "\n"; exit(1); }
        }
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
$botox = get_page_by_path('botox-cdmx')->post_content;
foreach (array('garantizan', 'hasta 5 meses', 'entre 3 y 7', 'Belleza natural para cada tipo de piel', 'Nuestros {{Servicios}}') as $bad) {
    if (stripos($botox . eg_texts_of('botox-cdmx', 'jss-procedure'), $bad) !== false) { echo "ERROR: botox-cdmx todavía dice '$bad'\n"; exit(1); }
}
foreach (array('Dr. Bruno Ocampo', 'a los 14 días', 'Primeros cambios entre 48 y 72 horas', '[jss-procedure', 'Pacientes {{satisfechos}}') as $good) {
    if (strpos($botox, $good) === false) { echo "ERROR: botox-cdmx perdió '$good'\n"; exit(1); }
}
if (preg_match_all('/\[vc_row[\s\]]/', $botox) !== substr_count($botox, '[/vc_row]')) { echo "ERROR: botox-cdmx quedó con filas sin cerrar\n"; exit(1); }

wp_cache_flush();
if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: $changed paginas actualizadas\n";
