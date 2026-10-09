<?php
// Editorial redesign helpers: language, icons, photos by file name, Google reviews, hubs and JSON-LD.

function eg_lang(){
	return is_page('en') ? 'en' : 'es';
}

function eg_booking_url(){
	return get_theme_links()->header_btn_link;
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
		$row->text = wp_html_excerpt($row->text, 240, '…');
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
// Pages whose hand-written schema (saswp custom field) already describes the business are left alone.
function eg_schema(){
	if(is_singular() && !is_front_page() && preg_match('/Medical(Business|Clinic)/', (string)get_post_meta(get_the_ID(), 'saswp_custom_schema_field', true))){
		return;
	}
	$graph = array(eg_clinic_schema());
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
		$graph[] = array('@type' => 'FAQPage', 'mainEntity' => $faq);
	}
	if(is_singular('service')){
		$post = get_post();
		$hub = eg_service_hub($post);
		$graph[] = array(
			'@type' => 'MedicalProcedure',
			'name' => get_the_title($post),
			'url' => get_permalink($post),
			'procedureType' => 'https://schema.org/NoninvasiveProcedure',
			'provider' => $clinic,
		);
		$graph[] = eg_breadcrumb_schema(array(
			array('Inicio', home_url('/')),
			array($hub->title, $hub->url),
			array(get_the_title($post), get_permalink($post)),
		));
	}
	echo '<script type="application/ld+json">'.wp_json_encode(array('@context' => 'https://schema.org', '@graph' => $graph), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'</script>'."\n";
}

add_action('wp_head', 'eg_schema');

function eg_hreflang(){
	if(is_front_page() || is_page('en')){
		echo '<link rel="alternate" hreflang="es-MX" href="'.esc_url(home_url('/')).'">'."\n";
		echo '<link rel="alternate" hreflang="en" href="'.esc_url(home_url('/en/')).'">'."\n";
		echo '<link rel="alternate" hreflang="x-default" href="'.esc_url(home_url('/')).'">'."\n";
	}
}

add_action('wp_head', 'eg_hreflang', 1);

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

// On the home the theme's graph replaces the schema plugin's Organization and WebSite nodes.
function eg_home_schema_only(){
	if(is_front_page() || is_page('en')){
		remove_action('wp_head', 'saswp_schema_markup_output');
	}
}

add_action('wp', 'eg_home_schema_only');

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
