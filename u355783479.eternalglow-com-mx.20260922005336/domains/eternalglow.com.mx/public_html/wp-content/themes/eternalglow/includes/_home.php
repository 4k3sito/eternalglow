<?php
// Editorial redesign helpers: language, icons, photos by file name, Google reviews, hubs and JSON-LD.

// English pages are /en/ and its child pages (e.g. /en/botox-mexico-city/).
function eg_lang(){
	if(!is_page()){
		return 'es';
	}
	$page = get_queried_object();
	return $page->post_name == 'en' || ($page->post_parent && get_post_field('post_name', $page->post_parent) == 'en') ? 'en' : 'es';
}

// Spanish path => English path of the same page; drives hreflang and the ES/EN switch.
function eg_translations(){
	return array('/' => '/en/', '/botox-cdmx/' => '/en/botox-mexico-city/', '/sculptra-cdmx/' => '/en/sculptra-mexico-city/', '/rellenos-cdmx/' => '/en/fillers-mexico-city/', '/relleno-de-labios-cdmx/' => '/en/lip-filler-mexico-city/');
}

// The current page in the other language, or that language's home when it has no translation.
function eg_alternate_path(){
	$path = trailingslashit(strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
	$pairs = eg_translations();
	if(eg_lang() == 'en'){
		$es = array_search($path, $pairs, true);
		return $es !== false ? $es : '/';
	}
	return isset($pairs[$path]) ? $pairs[$path] : '/en/';
}

// Contact details for the current language: on English pages the hours, the note and the form come from
// eg_home_strings() and the English CF7 form (option eg_contact_form_en, created by seo/contact-form-en.php).
function eg_contact(){
	$contact = clone get_theme_contact();
	if(eg_lang() == 'en'){
		$t = eg_home_strings()['footer'];
		$contact->schedules = $t['schedules'];
		$contact->schedules_note = $t['schedules_note'];
		$form = (int)get_option('eg_contact_form_en');
		if($form){
			$contact->form = $form;
		}
	}
	return $contact;
}

function eg_booking_url(){
	return get_theme_links()->header_btn_link;
}

// The two booking buttons used across the site. $where goes to analytics as cta_location.
function eg_cta_book($where, $label = null){
	$label = $label ?: eg_home_strings()['cta'];
	return '<a class="btn btn-primary" href="'.esc_url(eg_booking_url()).'" target="_blank" rel="noopener" data-cta="'.esc_attr($where).'">'.eg_icon('calendar-check').esc_html($label).'</a>';
}

function eg_cta_whatsapp($where, $label = null){
	$url = get_whatsapp_url();
	$label = $label ?: eg_home_strings()['whatsapp'];
	return $url ? '<a class="btn lp-btn-wa" href="'.esc_url($url).'" target="_blank" rel="noopener" data-cta="'.esc_attr($where).'">'.eg_icon('whatsapp-fill').esc_html($label).'</a>' : '';
}

// Phosphor icon from assets/images/icons, inlined so it takes the text color.
function eg_icon($name){
	return '<span class="lp-icon" aria-hidden="true">'.get_svg("icons/{$name}.svg").'</span>';
}

// <img> with srcset for an upload, looked up by file name: attachment IDs differ between local and prod.
function eg_img($file, $size = 'large', $attr = array()){
	global $wpdb;
	static $ids = array();
	if(!isset($ids[$file])){
		$ids[$file] = (int)$wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s", $file));
	}
	return $ids[$file] ? wp_get_attachment_image($ids[$file], $size, false, $attr) : '';
}

// Hub pictures: the old pink circle cut-outs are served from assets/images/cutouts instead, the same subject on a
// flat shape in the site's palette (made with seo/cutouts.py). Keyed by upload file name, so no content changes;
// the hash-named files are the phone copies of the same pictures.
function eg_cutout_attrs($attr, $attachment){
	static $map = array(
		'hilos-tensores-cdmx-rejuvenecimiento-facial.png' => 'hilos-tensores-cdmx-rejuvenecimiento-facial',
		'689c149abd0bf.png' => 'hilos-tensores-cdmx-rejuvenecimiento-facial',
		'hilos-tensores-en-cara-cdmx.png' => 'hilos-tensores-en-cara-cdmx',
		'689c149abd0c1.png' => 'hilos-tensores-en-cara-cdmx',
		'hilos-tensores-nariz-cdmx.png' => 'hilos-tensores-nariz-cdmx',
		'689c149abd0c4.png' => 'hilos-tensores-nariz-cdmx',
		'hilos-tensores-ciudad-de-mexico.png' => 'hilos-tensores-ciudad-de-mexico',
		'botox-cdmx-resultados-naturales-eternal-glow.png' => 'botox-cdmx-resultados-naturales-eternal-glow',
		'689b9b16ab085.png' => 'botox-cdmx-resultados-naturales-eternal-glow',
		'especialistas-en-toxina-botulinica-aplicacion-facial.png' => 'especialistas-en-toxina-botulinica-aplicacion-facial',
		'689b9b16ab088.png' => 'especialistas-en-toxina-botulinica-aplicacion-facial',
		'botox-cerca-de-mi-para-hombres-aplicacion-estetica.png' => 'botox-cerca-de-mi-para-hombres-aplicacion-estetica',
		'689b9b16ab08b.png' => 'botox-cerca-de-mi-para-hombres-aplicacion-estetica',
		'botox-mexico-city-resultados-visibles-hombre-mujer.png' => 'botox-mexico-city-resultados-visibles-hombre-mujer',
		// Not redrawn, only kept whole: the doctor's head stands out of its circle.
		'clinica-medico-estetica-especialista-en-cdmx.png' => 'dr-bruno-ocampo-nosotros',
	);
	$file = basename((string)get_attached_file($attachment->ID));
	if(isset($map[$file])){
		$attr['src'] = get_template_directory_uri()."/assets/images/cutouts/{$map[$file]}.webp";
		$attr['class'] = trim((isset($attr['class']) ? $attr['class'] : '').' lp-cutout');
		unset($attr['srcset'], $attr['sizes']);
	}
	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'eg_cutout_attrs', 10, 2);

// Service pages open with a coral banner that repeats the page title in another typeface; it is the one image
// that does not match the site's palette, and the title, price and photos below already cover it. Removed from
// the output (not hidden with CSS) so the browser does not download it.
function eg_drop_service_banner($content){
	if(!is_singular('service')){
		return $content;
	}
	return preg_replace('#<div class="et_pb_module et_pb_image et_pb_image_0[^"]*">\s*<span class="et_pb_image_wrap ?">\s*<img[^>]*>\s*</span>\s*</div>#', '', $content, 1);
}
add_filter('the_content', 'eg_drop_service_banner', 20);

// Before and after photos uploaded without alt text get one from their file name, which says the treatment
// and who is in the picture. An alt written in the media library always wins.
// ponytail: three treatments by keyword; write the alt in the media library for anything this does not cover.
function eg_alt_fallback($attr, $attachment){
	if(!empty($attr['alt'])){
		return $attr;
	}
	$file = basename((string)get_attached_file($attachment->ID));
	if(!preg_match('/antes-despues|resultados/', $file) || !preg_match('/botox|sculptra|hilos/', $file, $m)){
		return $attr;
	}
	$names = array('botox' => 'Botox', 'sculptra' => 'Sculptra', 'hilos' => 'hilos tensores');
	$who = strpos($file, 'hombre') !== false ? ' en hombre' : (strpos($file, 'mujer') !== false ? ' en mujer' : '');
	$attr['alt'] = "Antes y después de {$names[$m[0]]}{$who} en Eternal Glow, CDMX";
	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'eg_alt_fallback', 10, 2);

// Rating, total and recent reviews saved by the Trustindex plugin. Null when the plugin has no data.
function eg_google_reviews($limit = 3, $lang = 'es'){
	global $wpdb;
	$table = $wpdb->prefix.'trustindex_google_reviews';
	$page = get_option('trustindex-google-page-details');
	if(!$page || $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) !== $table){
		return null;
	}
	$rows = $wpdb->get_results("SELECT user, text, rating FROM $table WHERE hidden = 0 AND rating >= 5 AND CHAR_LENGTH(text) BETWEEN 60 AND 600 ORDER BY date DESC LIMIT 40");
	$items = array();
	foreach($rows as $row){
		// ponytail: stopword check to tell English from Spanish; store a language column if reviews in other languages appear.
		$is_en = (bool)preg_match('/\b(the|and|was|very|with)\b/i', $row->text);
		if($is_en !== ($lang == 'en')){
			continue;
		}
		$name = preg_split('/\s+/', trim($row->user));
		$row->user = $name[0].(isset($name[1]) ? ' '.mb_substr($name[1], 0, 1).'.' : '');
		// Full text on purpose: the card clamps it with CSS (see .lp-reviews blockquote), which never cuts a word.
		$items[] = $row;
		if(count($items) >= $limit){
			break;
		}
	}
	return (object)array(
		'score' => number_format((float)$page['rating_score'], 1),
		'count' => (int)$page['rating_number'],
		'url' => $page['review_url'],
		'items' => $items,
	);
}

// The page that owns the main keyword for a service, so every service links up to it.
function eg_service_hub($post){
	$hubs = array(
		'/botox/' => array('Botox en CDMX', 'botox-cdmx'),
		'/relleno|rinomodelacion|marcaje|armonizacion/' => array('Rellenos en CDMX', 'rellenos-cdmx'),
		'/sculptra/' => array('Sculptra en CDMX', 'sculptra-cdmx'),
		'/hilos/' => array('Hilos tensores en CDMX', 'hilos-tensores-cdmx'),
	);
	foreach($hubs as $pattern => $hub){
		if(preg_match($pattern, $post->post_name)){
			return (object)array('title' => $hub[0], 'url' => home_url("/{$hub[1]}/"));
		}
	}
	return (object)array('title' => 'Tratamientos', 'url' => home_url('/tratamientos/'));
}

function eg_clinic_schema(){
	$contact = get_theme_contact();
	$social = get_theme_social();
	$hours = function($days, $closes){
		return array('@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $days, 'opens' => '11:30', 'closes' => $closes);
	};
	return array(
		'@type' => 'MedicalClinic',
		'@id' => home_url('/#clinic'),
		'name' => 'Eternal Glow',
		'url' => home_url('/'),
		'image' => get_theme_logo(1)->url,
		'telephone' => str_replace(' ', '', $contact->phone1),
		'email' => $contact->email1,
		'address' => array(
			'@type' => 'PostalAddress',
			'streetAddress' => 'Valladolid 56, Roma Norte',
			'addressLocality' => 'Cuauhtémoc',
			'addressRegion' => 'CDMX',
			'postalCode' => '06700',
			'addressCountry' => 'MX',
		),
		'geo' => array('@type' => 'GeoCoordinates', 'latitude' => 19.4200327, 'longitude' => -99.1681248),
		'hasMap' => 'https://www.google.com/maps/place/?q=place_id:ChIJS7Rw2Ef_0YURsG6Y2CrptYA',
		'openingHoursSpecification' => array(
			$hours(array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'), '18:00'),
			$hours(array('Saturday'), '17:00'),
		),
		'areaServed' => array('Roma Norte', 'Condesa', 'Juárez', 'Ciudad de México'),
		'availableLanguage' => array('es', 'en'),
		'sameAs' => array_values(array_filter(array($social->facebook, $social->instagram, $social->tiktok))),
	);
}

function eg_breadcrumb_schema($trail){
	$items = array();
	foreach($trail as $i => $crumb){
		$items[] = array('@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb[0], 'item' => $crumb[1]);
	}
	return array('@type' => 'BreadcrumbList', 'itemListElement' => $items);
}

// One graph per page, with the clinic node that the other nodes point to by @id.
// The schema plugin's own output is off (eg_schema_only); its per-post nodes come in through eg_saswp_nodes().
function eg_schema(){
	$graph = array(eg_clinic_schema());
	$saswp = is_singular() ? eg_saswp_nodes(get_the_ID()) : array();
	$saswp_types = eg_schema_types($saswp);
	$clinic = array('@id' => home_url('/#clinic'));
	if(is_front_page() || is_page('en')){
		$t = eg_home_strings();
		$graph[] = array('@type' => 'WebSite', '@id' => home_url('/#website'), 'url' => home_url('/'), 'name' => 'Eternal Glow', 'inLanguage' => eg_lang() == 'en' ? 'en' : 'es-MX', 'publisher' => $clinic);
		$graph[] = array(
			'@type' => 'Physician',
			'@id' => home_url('/#dr-bruno-ocampo'),
			'name' => 'Dr. Bruno Ocampo',
			'medicalSpecialty' => 'Medicina estética',
			'url' => home_url('/nosotros/'),
			'worksFor' => $clinic,
			'alumniOf' => 'Universidad La Salle',
		);
		$faq = array();
		foreach($t['faq'] as $item){
			$faq[] = array('@type' => 'Question', 'name' => $item[0], 'acceptedAnswer' => array('@type' => 'Answer', 'text' => wp_strip_all_tags($item[1])));
		}
		if(!in_array('FAQPage', $saswp_types, true)){
			$graph[] = array('@type' => 'FAQPage', 'mainEntity' => $faq);
		}
	}
	$landing = is_page() ? eg_landing(get_post()->post_name) : null;
	if($landing && !in_array('FAQPage', $saswp_types, true)){
		$faq = array();
		foreach($landing['faq'] as $item){
			$faq[] = array('@type' => 'Question', 'name' => $item[0], 'acceptedAnswer' => array('@type' => 'Answer', 'text' => wp_strip_all_tags($item[1])));
		}
		$graph[] = array('@type' => 'FAQPage', 'mainEntity' => $faq);
	}
	if(is_singular('service')){
		$post = get_post();
		$hub = eg_service_hub($post);
		if(!in_array('MedicalProcedure', $saswp_types, true)){
			$graph[] = array(
				'@type' => 'MedicalProcedure',
				'name' => get_the_title($post),
				'url' => get_permalink($post),
				'procedureType' => 'https://schema.org/NoninvasiveProcedure',
				'provider' => $clinic,
			);
		}
		if(!in_array('BreadcrumbList', $saswp_types, true)){
			$graph[] = eg_breadcrumb_schema(array(
				array('Inicio', home_url('/')),
				array($hub->title, $hub->url),
				array(get_the_title($post), get_permalink($post)),
			));
		}
	}
	$graph = array_merge($graph, $saswp);
	// JSON_HEX_TAG: post meta ends up here, and a "</script>" inside a string must not close the block.
	echo '<script type="application/ld+json">'.wp_json_encode(array('@context' => 'https://schema.org', '@graph' => $graph), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG).'</script>'."\n";
}

add_action('wp_head', 'eg_schema');

function eg_hreflang(){
	$path = trailingslashit(strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
	$pairs = eg_translations();
	$es = isset($pairs[$path]) ? $path : array_search($path, $pairs, true);
	if($es === false || is_404()){
		return;
	}
	echo '<link rel="alternate" hreflang="es-MX" href="'.esc_url(home_url($es)).'">'."\n";
	echo '<link rel="alternate" hreflang="en" href="'.esc_url(home_url($pairs[$es])).'">'."\n";
	echo '<link rel="alternate" hreflang="x-default" href="'.esc_url(home_url($es)).'">'."\n";
}

add_action('wp_head', 'eg_hreflang', 1);

// Rank Math takes og:locale from the site language (es_MX), which is wrong on the English pages.
add_filter('rank_math/opengraph/facebook/og_locale', function($locale){
	return eg_lang() == 'en' ? 'en_US' : $locale;
});

// Rank Math adds "Escrito por <user>" and reading time as twitter:label/data, so link previews on X, Slack and
// WhatsApp show the WordPress login of whoever last saved the page instead of the clinic.
add_filter('rank_math/opengraph/slack_enhanced_data', '__return_empty_array');

// The home is a PHP template, so the page builders' CSS and JS are dead weight there.
// Divi's Google Fonts (Playfair, Urbanist) go everywhere: the editorial look has its own two families.
function eg_home_dequeue(){
	wp_dequeue_style('et-builder-googlefonts-cached');
	if(!is_front_page() && !is_page('en')){
		return;
	}
	global $wp_styles, $wp_scripts;
	foreach(array($wp_styles, $wp_scripts) as $assets){
		foreach($assets->registered as $handle => $asset){
			if(preg_match('/^(divi-builder|et-)/', $handle) || (is_string($asset->src) && preg_match('#/(js_composer|divi-builder|schema-and-structured-data-for-wp)/#', $asset->src))){
				$assets->dequeue($handle);
			}
		}
	}
}

// The theme prints the only JSON-LD graph: the schema plugin's output carried a second business entity.
function eg_schema_only(){
	remove_action('wp_head', 'saswp_schema_markup_output');
	remove_action('wp_footer', 'saswp_schema_markup_output');
}

add_action('wp', 'eg_schema_only');

add_action('wp_enqueue_scripts', 'eg_home_dequeue', 999);
add_action('wp_footer', 'eg_home_dequeue', 1);

function eg_home_strings(){
	static $strings = null;
	if($strings === null){
		$all = require get_template_path().'/includes/_home-strings.php';
		$strings = $all[eg_lang()];
	}
	return $strings;
}
