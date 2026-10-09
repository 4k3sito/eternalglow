<?php
// SEO per treatment: one title, description and focus keyword for every service and page.
// Run with: wp eval-file seo/treatments.php
// Titles follow one pattern ({keyword} en CDMX | {benefit or brand}) and stay within 60 characters;
// descriptions within 155. A null description keeps the one already saved.
// Hub pages own the generic keyword ("botox cdmx"); services target their long tail only.

global $wpdb;

$rows = array(
    // slug => array(title, description, focus keyword)
    'tratamientos' => array('Tratamientos de Medicina Estética en CDMX | Eternal Glow', 'Botox, rellenos con ácido hialurónico, Sculptra, hilos tensores, HIFU y más en Roma Norte, CDMX. Conoce cada tratamiento y agenda tu valoración.', 'tratamientos de medicina estética en CDMX'),
    'nosotros' => array('Dr. Bruno Ocampo, Médico Estético en CDMX | Eternal Glow', 'Conoce al Dr. Bruno Ocampo: médico cirujano con especialidad en medicina estética y antienvejecimiento. Atiende en Roma Norte, CDMX.', 'médico estético en CDMX'),
    'contacto' => array('Contacto y Ubicación en Roma Norte, CDMX | Eternal Glow', 'Valladolid 56, Roma Norte, CDMX. Lunes a viernes de 11:30 a 18:00 y sábados de 11:30 a 17:00, con cita previa. Agenda en línea o por WhatsApp.', 'clínica estética Roma Norte'),
    'botox-cdmx' => array('Botox en CDMX | Clínica en Roma Norte | Eternal Glow', 'Botox en CDMX (Roma Norte) aplicado por médico estético. Suaviza líneas de frente, entrecejo y patas de gallo con resultado natural. Agenda tu valoración.', 'Botox en CDMX,botox cdmx,clínicas botox cdmx'),
    'hilos-tensores-cdmx' => array('Hilos Tensores en CDMX | Lifting sin Cirugía | Eternal Glow', 'Hilos tensores PDO en CDMX (Roma Norte) para reposicionar y definir el óvalo facial sin cirugía. Conoce el procedimiento y agenda tu valoración.', 'Hilos tensores en CDMX'),

    'armonizacion-facial-con-acido-hialuronico' => array('Armonización Facial 3 a 4 Jeringas en CDMX | Eternal Glow', null, 'armonización facial con ácido hialurónico'),
    'armonizacion-facial-full-face-cdmx' => array('Armonización Facial Full Face en CDMX | Eternal Glow', null, 'armonización facial full face'),
    'baby-botox-cdmx' => array('Baby Botox en CDMX | Resultado Natural y Preventivo', 'Baby Botox en CDMX: microdosis de toxina botulínica para suavizar líneas finas y prevenir arrugas con resultado natural. Agenda tu valoración.', 'baby botox'),
    'botox-en-cuello-cdmx' => array('Botox en Cuello en CDMX | Rejuvenece sin Cirugía', null, 'botox en cuello'),
    'botox-en-entrecejo' => array('Botox en Entrecejo en CDMX | Suaviza el Ceño Fruncido', null, 'botox en entrecejo'),
    'botox-en-los-labios' => array('Botox en los Labios en CDMX | Rejuvenecimiento Labial', null, 'botox en los labios'),
    'botox-foxy-eyes-cdmx' => array('Botox Foxy Eyes en CDMX | Eleva la Mirada', null, 'botox foxy eyes'),
    'botox-lip-flip' => array('Botox Lip Flip en CDMX | Labio Superior sin Relleno', 'Botox Lip Flip en CDMX para definir el labio superior y mejorar la sonrisa con un efecto natural, sin rellenos. Agenda tu valoración en Roma Norte.', 'botox lip flip'),
    'botox-para-bruxismo-en-maseteros-cdmx' => array('Botox para Bruxismo en Maseteros en CDMX | Eternal Glow', 'Botox para bruxismo en maseteros en CDMX: relaja la tensión mandibular, alivia el dolor y afina el contorno del rostro. Valoración en Roma Norte.', 'botox para bruxismo en maseteros'),
    'botox-para-hiperhidrosis-en-cdmx' => array('Botox para Hiperhidrosis en CDMX | Sudoración Excesiva', 'Botox para hiperhidrosis en CDMX: toxina botulínica para controlar la sudoración excesiva en axilas. Agenda tu valoración en Roma Norte.', 'botox para hiperhidrosis'),
    'botox-para-menton-en-cdmx' => array('Botox para Mentón en CDMX | Mentón Empedrado', null, 'botox para mentón'),
    'botox-para-patas-de-gallo-cdmx' => array('Botox para Patas de Gallo en CDMX | Eternal Glow', null, 'botox para patas de gallo'),
    'botox-por-unidad' => array('Botox por Unidad en CDMX | Precio y Valoración', null, 'botox por unidad'),
    'botox-trapecio-trap-tox' => array('Botox Trapecio (Traptox) en CDMX | Estiliza Hombros', null, 'botox trapecio'),
    'full-face-botox-60-unidades' => array('Full Face Botox 60 Unidades en CDMX | Eternal Glow', null, 'full face botox 60 unidades'),
    'hifu-facial-en-cdmx' => array('HIFU Facial en CDMX | Lifting sin Cirugía | Eternal Glow', null, 'HIFU facial'),
    'hollywood-peel-en-cdmx' => array('Hollywood Peel en CDMX | Precio y Beneficios', 'Hollywood Peel en CDMX: láser con carbón activado para una piel más limpia, uniforme y luminosa. Conoce precio, beneficios y resultados.', 'Hollywood Peel'),
    'marcaje-mandibular-con-acido-hialuronico-cdmx' => array('Marcaje Mandibular con Ácido Hialurónico en CDMX', null, 'marcaje mandibular con ácido hialurónico'),
    'microneedling-en-cdmx' => array('Microneedling en CDMX | Dermapen Facial | Eternal Glow', null, 'microneedling en CDMX'),
    'peeling-quimico-facial-en-cdmx' => array('Peeling Químico Facial en CDMX | Precio y Resultados', null, 'peeling químico facial'),
    'relleno-de-fosa-temporal-cdmx' => array('Relleno de Fosa Temporal en CDMX | Ácido Hialurónico', null, 'relleno de fosa temporal'),
    'relleno-de-ojeras-con-acido-hialuronico' => array('Relleno de Ojeras con Ácido Hialurónico en CDMX', 'Relleno de ojeras con ácido hialurónico en CDMX para mejorar ojeras hundidas, surco lagrimal y aspecto cansado. Resultado natural en Roma Norte.', 'relleno de ojeras con ácido hialurónico'),
    'relleno-de-pomulos-con-acido-hialuronico' => array('Relleno de Pómulos con Ácido Hialurónico en CDMX', null, 'relleno de pómulos con ácido hialurónico'),
    'relleno-de-surcos-nasogenianos-en-cdmx' => array('Relleno de Surcos Nasogenianos en CDMX | Eternal Glow', 'Suaviza las líneas de la sonrisa con relleno de surcos nasogenianos con ácido hialurónico en CDMX. Resultado natural y valoración personalizada.', 'relleno de surcos nasogenianos'),
    'rinomodelacion-en-cdmx' => array('Rinomodelación en CDMX | Perfilado Nasal sin Cirugía', null, 'rinomodelación en CDMX'),
    'tratamiento-pdrn-salmon' => array('Tratamiento PDRN Salmón en CDMX | Eternal Glow', null, 'tratamiento PDRN salmón'),
    'tratamiento-radiesse-en-cdmx' => array('Radiesse en CDMX | Bioestimulador de Colágeno', 'Radiesse en CDMX: bioestimulador de colágeno para mejorar firmeza y redefinir el contorno facial. Conoce el tratamiento y agenda tu valoración.', 'tratamiento Radiesse'),
    'tratamiento-rejuran-en-cdmx' => array('Tratamiento Rejuran en CDMX | Polinucleótidos', 'Tratamiento Rejuran en CDMX con polinucleótidos para mejorar hidratación, textura y luminosidad de la piel. Valoración en Roma Norte.', 'tratamiento Rejuran'),
);

// Self-check: fail before writing anything if a title or description is too long for the results page.
foreach ($rows as $slug => $row) {
    if (mb_strlen($row[0]) > 60) { echo "TITLE > 60 ($slug): " . mb_strlen($row[0]) . "\n"; exit(1); }
    if ($row[1] !== null && mb_strlen($row[1]) > 155) { echo "DESCRIPTION > 155 ($slug): " . mb_strlen($row[1]) . "\n"; exit(1); }
}

$backup = array();
$done = 0;
$long = array();
foreach ($rows as $slug => $row) {
    $id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type IN ('service', 'page') AND post_status = 'publish'", $slug));
    if (!$id) { echo "No existe: $slug\n"; continue; }
    $meta = array('rank_math_title' => $row[0], 'rank_math_focus_keyword' => $row[2]);
    if ($row[1] !== null) $meta['rank_math_description'] = $row[1];
    foreach ($meta as $k => $v) {
        $backup[$slug][$k] = get_post_meta($id, $k, true);
        update_post_meta($id, $k, wp_slash($v));
    }
    // Kept descriptions are reported when they would be cut off in the results page.
    $desc = get_post_meta($id, 'rank_math_description', true);
    if (mb_strlen(trim($desc)) > 155) $long[] = "$slug (" . mb_strlen(trim($desc)) . ")";
    clean_post_cache($id);
    $done++;
}

$dir = WP_CONTENT_DIR . '/uploads/seo-backups';
wp_mkdir_p($dir);
if (!file_exists("$dir/.htaccess")) file_put_contents("$dir/.htaccess", "Require all denied\n");
file_put_contents("$dir/treatments-" . gmdate('Ymd-His') . '.json', wp_json_encode($backup));

if (function_exists('rocket_clean_domain')) rocket_clean_domain();
echo "OK: $done de " . count($rows) . " actualizados\n";
if ($long) echo "Descripciones conservadas de más de 155 caracteres: " . implode(', ', $long) . "\n";
