<?php
// Short answers at the top of a service page, by slug. Google's AI Overview cited /servicio/hollywood-peel-en-cdmx/
// for "hollywood peel" on 2-7 Oct 2026 (position ~1 on mobile, 0.7% CTR) and dropped it after: the page had the
// answers, but spread over long sections. These restate what the page already says, one or two sentences each.
// Prices come from the catalogue (includes/_treatments.php); packages are written out and match seo/prices.php.

function eg_service_answers($slug){
	$item = eg_treatment_for($slug);
	$price = $item ? $item->price : '';
	$answers = array(
		'hollywood-peel-en-cdmx' => array(
			array('¿Qué es el Hollywood Peel?', 'Un tratamiento facial no invasivo que combina una mascarilla de carbón activado con láser para mejorar la luminosidad, la textura, el exceso de grasa y la apariencia de los poros.'),
			array('¿Cuánto cuesta en CDMX?', "$price, o 3 sesiones por $3,999 (MXN), en Eternal Glow, Roma Norte. Incluye valoración médica."),
			array('¿Cuántas sesiones necesito?', 'Una sesión aporta luminosidad, por ejemplo antes de un evento. Para trabajar textura, grasa o poros se recomienda un protocolo de varias sesiones separadas por algunas semanas.'),
			array('¿Tiene recuperación?', 'Suele ser breve y sin descamación intensa. Puede haber enrojecimiento, tirantez o calor leve en las primeras horas; evita ejercicio intenso, sauna y calor de 24 a 48 horas.'),
			array('¿Puedo hacerlo antes de un evento?', 'Sí, pero no por primera vez el mismo día: prográmalo con anticipación para ver cómo responde tu piel.'),
		),
	);
	return isset($answers[$slug]) ? $answers[$slug] : array();
}

// Placed after the page's opening section (title and intro), so the answers sit right under the H1.
function eg_service_answers_block($content){
	if(!is_singular('service') || !in_the_loop() || !is_main_query()){
		return $content;
	}
	$answers = eg_service_answers(get_post()->post_name);
	if(!$answers){
		return $content;
	}
	$html = '<section class="lp-sv-section lp-sv-quick" aria-labelledby="respuestas-rapidas"><h2 id="respuestas-rapidas">Respuestas rápidas</h2><dl>';
	foreach($answers as $item){
		$html .= '<div><dt>'.esc_html($item[0]).'</dt><dd>'.esc_html($item[1]).'</dd></div>';
	}
	$html .= '</dl></section>';
	$at = strpos($content, '</section>');
	return $at === false ? $html.$content : substr_replace($content, $html, $at + strlen('</section>'), 0);
}

add_filter('the_content', 'eg_service_answers_block', 30);
