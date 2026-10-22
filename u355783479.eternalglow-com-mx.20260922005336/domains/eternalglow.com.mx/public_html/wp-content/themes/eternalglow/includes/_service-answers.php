<?php
// Short answers at the top of a service page, by slug. Google's AI Overview cited /servicio/hollywood-peel-en-cdmx/
// for "hollywood peel" on 2-7 Oct 2026 (position ~1 on mobile, 0.7% CTR) and dropped it after: the page had the
// answers, but spread over long sections. These restate what the page already says, one or two sentences each.
// Prices come from the catalogue (includes/_treatments.php); packages are written out and match seo/prices.php.
// Full Face and Botox por unidad (2026-10-22): top 6 for "botox full face que incluye" and "precio de botox por unidad"
// with almost no clicks (docs/reports/2026-10-22-mapa-keywords.md). Timings are the hub's (/botox-cdmx/).

function eg_service_answers($slug){
	$item = eg_treatment_for($slug);
	$price = $item ? $item->price : '';
	// "$165 / unidad" -> "$165", to write the unit into the sentence.
	$per_unit = strtok(eg_treatment_for('botox-por-unidad')->price, ' ');
	$baby = eg_treatment_for('baby-botox-cdmx')->price;
	$full = eg_treatment_for('full-face-botox-60-unidades')->price;
	$timing = 'Los primeros cambios se notan entre 48 y 72 horas, el efecto completo a los 14 días y dura de 3 a 5 meses.';
	$answers = array(
		'hollywood-peel-en-cdmx' => array(
			array('¿Qué es el Hollywood Peel?', 'Un tratamiento facial no invasivo que combina una mascarilla de carbón activado con láser para mejorar la luminosidad, la textura, el exceso de grasa y la apariencia de los poros.'),
			array('¿Cuánto cuesta en CDMX?', "$price, o 3 sesiones por $3,999 (MXN), en Eternal Glow, Roma Norte. Incluye valoración médica."),
			array('¿Cuántas sesiones necesito?', 'Una sesión aporta luminosidad, por ejemplo antes de un evento. Para trabajar textura, grasa o poros se recomienda un protocolo de varias sesiones separadas por algunas semanas.'),
			array('¿Tiene recuperación?', 'Suele ser breve y sin descamación intensa. Puede haber enrojecimiento, tirantez o calor leve en las primeras horas; evita ejercicio intenso, sauna y calor de 24 a 48 horas.'),
			array('¿Puedo hacerlo antes de un evento?', 'Sí, pero no por primera vez el mismo día: prográmalo con anticipación para ver cómo responde tu piel.'),
		),
		'full-face-botox-60-unidades' => array(
			array('¿Qué incluye el Full Face Botox?', '60 unidades de toxina botulínica tipo A repartidas en varias zonas del rostro en una misma sesión, como frente, entrecejo y patas de gallo. El médico define los puntos en tu valoración.'),
			array('¿Cuánto cuesta en CDMX?', "$price MXN por las 60 unidades en Eternal Glow, Roma Norte."),
			array('¿En qué se diferencia del Botox por zona?', "El Botox por zona trata un área, como la frente o el entrecejo. El Full Face trabaja varias zonas en un mismo protocolo para un resultado más uniforme. Para una sola zona está el Botox por unidad, a $per_unit MXN cada una."),
			array('¿Cuándo se ven los resultados y cuánto duran?', $timing),
			array('¿Tiene recuperación?', 'No. Puedes retomar tus actividades el mismo día; solo evita tocar, presionar o masajear la cara en las primeras horas.'),
		),
		'botox-por-unidad' => array(
			array('¿Cuánto cuesta el Botox por unidad en CDMX?', "$per_unit MXN por unidad en Eternal Glow, Roma Norte. Pagas solo las unidades que necesitas, definidas en tu valoración médica."),
			array('¿Cuántas unidades necesito?', "Depende de la zona, la fuerza del músculo y la profundidad de las líneas; el médico lo define en la valoración. Como referencia, el Baby Botox usa 30 unidades ($baby MXN) y el Full Face 60 ($full MXN)."),
			array('¿En qué zonas se aplica?', 'Sobre todo en frente, entrecejo y patas de gallo, y en los labios (código de barras). Otras zonas se valoran en consulta.'),
			array('¿Cuánto dura la sesión y el efecto?', "La sesión toma de 10 a 30 minutos. $timing"),
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
