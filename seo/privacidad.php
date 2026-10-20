<?php
// Aviso de privacidad (LFPDPPP). Run with: wp eval-file seo/privacidad.php  (safe to run twice)
// The site collects name, email, phone and skin/treatment answers (Contact Form 7 #54, shown on every page by the
// theme's contact-form.php) but /aviso-de-privacidad/ returned 404 and the theme's privacy link was empty.
// Two passes on purpose: the first run creates the page as a DRAFT, because the legal text needs the clinic's
// razón social and a lawyer's review. Once someone publishes it in wp-admin, the next run wires it up:
// theme footer link, WordPress privacy page setting and a required checkbox in form #54.

const EG_PRIVACY_SLUG = 'aviso-de-privacidad';
const EG_PRIVACY_FORM = 54;
const EG_PRIVACY_TAG = 'aviso-privacidad';

function eg_privacy_template(): string {
    $contact = get_theme_contact();
    $email = esc_html($contact->email1);
    $phone = esc_html($contact->phone1);
    // [CORCHETES] = datos que solo la clínica puede dar. El script no publica mientras queden en el texto.
    return <<<HTML
<p><strong>Última actualización:</strong> [FECHA]</p>

<h2>Responsable</h2>
<p>[RAZÓN SOCIAL O NOMBRE DEL TITULAR] (en adelante, "Eternal Glow"), con domicilio en Valladolid 56, colonia Roma Norte, alcaldía Cuauhtémoc, 06700, Ciudad de México, es responsable del tratamiento de tus datos personales conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares.</p>

<h2>Datos que recabamos</h2>
<ul>
<li>Identificación y contacto: nombre, correo electrónico y teléfono.</li>
<li>Datos sensibles de salud: el estado de tu piel, tu rutina de cuidado, tratamientos estéticos previos, lo que te gustaría mejorar y la información de tu historia clínica en la valoración.</li>
</ul>
<p>Tratamos tus datos sensibles solo con tu consentimiento expreso, que nos das al marcar la casilla del formulario o al firmar tu historia clínica.</p>

<h2>Para qué los usamos</h2>
<p>Finalidades necesarias:</p>
<ul>
<li>Contactarte para agendar tu valoración y tus citas.</li>
<li>Hacer tu valoración médica, integrar tu expediente clínico y darte el tratamiento y su seguimiento.</li>
<li>Cumplir obligaciones legales y sanitarias.</li>
</ul>
<p>Finalidades adicionales, que puedes rechazar sin afectar tu atención:</p>
<ul>
<li>Enviarte promociones, recordatorios e información de tratamientos.</li>
<li>Encuestas de satisfacción.</li>
</ul>
<p>Si no quieres que usemos tus datos para las finalidades adicionales, escríbenos a {$email}.</p>

<h2>Transferencias</h2>
<p>No vendemos ni compartimos tus datos con terceros para sus propios fines. Solo los compartimos con autoridades cuando la ley lo exige, y con proveedores que nos dan servicio (agenda en línea, correo, alojamiento del sitio) bajo obligación de confidencialidad.</p>

<h2>Derechos ARCO y revocación del consentimiento</h2>
<p>Puedes acceder a tus datos, rectificarlos, cancelarlos u oponerte a su uso, y revocar tu consentimiento, enviando tu solicitud a {$email} con tu nombre, un medio para responderte, una copia de tu identificación y la descripción de lo que solicitas. Te responderemos en un máximo de 20 días hábiles.</p>

<h2>Cookies y tecnologías similares</h2>
<p>Este sitio usa cookies de Google Analytics, Google Tag Manager y Microsoft Clarity para medir visitas y mejorar la página. Puedes desactivarlas desde la configuración de tu navegador.</p>

<h2>Cambios a este aviso</h2>
<p>Publicaremos cualquier cambio en esta misma página.</p>

<h2>Contacto</h2>
<p>Correo: {$email} · Teléfono: {$phone}</p>
HTML;
}

$page = get_page_by_path(EG_PRIVACY_SLUG);

if (!$page) {
    $id = wp_insert_post(array(
        'post_type' => 'page',
        'post_status' => 'draft',
        'post_name' => EG_PRIVACY_SLUG,
        'post_title' => 'Aviso de privacidad',
        'post_content' => eg_privacy_template(),
    ), true);
    if (is_wp_error($id)) { echo 'ERROR: ' . $id->get_error_message() . "\n"; exit(1); }
    echo "Creado el borrador #$id. Completa los [CORCHETES], que lo revise un abogado, publícalo y vuelve a correr este script.\n";
    return;
}

if ($page->post_status !== 'publish') {
    echo "El aviso #{$page->ID} sigue en '{$page->post_status}'. Publícalo y vuelve a correr este script.\n";
    return;
}

if (preg_match('/\[[A-ZÁÉÍÓÚÑ ]{4,}\]/u', $page->post_content)) {
    echo "ERROR: el aviso publicado todavía tiene datos entre [CORCHETES]\n";
    exit(1);
}

$url = get_permalink($page);

// Same defaults as get_theme_links(): when the option is empty the theme reads every key, not just privacy.
$links = array_merge(array('header_btn_text' => '', 'header_btn_link' => '', 'privacy' => '', 'terms' => ''), (array)get_option('theme_links', array()));
$links['privacy'] = $url;
update_option('theme_links', $links);
update_option('wp_page_for_privacy_policy', $page->ID);

// CF7 renders from the _form meta, not post_content. The acceptance tag blocks sending until it is checked.
$form = (string)get_post_meta(EG_PRIVACY_FORM, '_form', true);
if ($form === '') { echo "ERROR: no existe el formulario " . EG_PRIVACY_FORM . "\n"; exit(1); }
if (strpos($form, '[acceptance ' . EG_PRIVACY_TAG) === false) {
    $field = '<div class="form-group">[acceptance ' . EG_PRIVACY_TAG . '] Acepto el <a href="' . esc_url($url) . '" target="_blank" rel="noopener">Aviso de privacidad</a> y el tratamiento de mis datos de salud para agendar mi valoración. [/acceptance]</div>' . "\n";
    $updated = preg_replace('/(\[submit\b)/', $field . '$1', $form, 1, $count);
    if (!$count) { echo "ERROR: el formulario no tiene [submit]\n"; exit(1); }
    update_post_meta(EG_PRIVACY_FORM, '_form', $updated);
}

// Self-check, like the other seo/ scripts.
$ok = get_theme_links()->privacy === $url
    && strpos((string)get_post_meta(EG_PRIVACY_FORM, '_form', true), '[acceptance ' . EG_PRIVACY_TAG) !== false;
if (!$ok) { echo "ERROR: la verificación falló\n"; exit(1); }
echo "Listo: link del footer, página de privacidad de WordPress y casilla del formulario apuntan a $url\n";
