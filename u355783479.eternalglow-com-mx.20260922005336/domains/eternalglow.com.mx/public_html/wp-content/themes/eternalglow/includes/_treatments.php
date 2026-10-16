<?php
// The clinic's catalogue as the home and /tratamientos/ show it: one row per service page.
// Names and one-line descriptions come from the client's design prototype.

// The clinic's price flyers (received 2026-10-12) are the official list: the catalogue has the
// treatments on them and nothing else, at their prices. Service pages for treatments that are not
// on the flyers still exist, but nothing here links to them.
function eg_show_prices(){
	return true;
}

// The catalogue row that prices a service page. Lip botox is charged by the unit, so the clinic's list
// has no row for it: its page takes the price of "Botox por unidad".
function eg_treatment_for($slug){
	$priced_as = array('botox-en-los-labios' => 'botox-por-unidad');
	$treatments = eg_treatments();
	$key = isset($priced_as[$slug]) ? $priced_as[$slug] : $slug;
	return isset($treatments[$key]) ? $treatments[$key] : null;
}

// Attributes for a link to a treatment: main.ts opens the booking sheet with them instead of following the link.
// The href stays, so the treatment's page is still reachable from the sheet, without JS and by search engines.
function eg_sheet_attrs($item, $cat = null){
	if($cat === null){
		$cats = eg_treatment_categories();
		$cat = isset($item->cat, $cats[$item->cat]) ? $cats[$item->cat][0] : '';
	}
	return ' data-sheet data-name="'.esc_attr($item->name).'" data-cat="'.esc_attr($cat).'" data-desc="'.esc_attr($item->desc).'" data-price="'.esc_attr(eg_show_prices() ? $item->price : '').'"';
}

function eg_treatment_categories(){
	return array(
		'botox' => array('Botox', 'Toxina botulínica'),
		'rellenos' => array('Rellenos', 'Ácido hialurónico'),
		'bio' => array('Bioestimuladores', 'Inyectables regenerativos'),
		'piel' => array('Piel y aparatología', 'Tecnología y peelings'),
	);
}

function eg_treatments(){
	// key (the service page slug when there is one), name, category, price (MXN), description, price unit, true for a "from" price
	$rows = array(
		array('botox-por-unidad', 'Botox por unidad', 'botox', 165, 'Dosis a la medida para una zona puntual: frente, entrecejo o labios (código de barras).', 'unidad'),
		array('baby-botox-cdmx', 'Baby botox (30 unidades)', 'botox', 4499, 'Microdosis en frente, entrecejo y ojos. Suaviza sin congelar la expresión.'),
		array('full-face-botox-60-unidades', 'Full face botox (60 unidades)', 'botox', 8499, 'Tratamiento completo del tercio superior e inferior para un rostro descansado.'),
		array('botox-frente', 'Botox en frente', 'botox', 2750, 'Suaviza las líneas horizontales de la frente.'),
		array('botox-en-entrecejo', 'Botox en entrecejo', 'botox', 1750, 'Relaja las líneas del ceño que dan aspecto de enojo o cansancio.'),
		array('botox-para-patas-de-gallo-cdmx', 'Botox patas de gallo', 'botox', 2150, 'Suaviza las líneas laterales de los ojos manteniendo una sonrisa natural.'),
		array('botox-en-cuello-cdmx', 'Botox en cuello', 'botox', 3150, 'Relaja las bandas del platisma y define la línea mandibular.'),
		array('botox-lip-flip', 'Botox Lip Flip', 'botox', 1250, 'Eversión sutil del labio superior para mostrar más volumen sin relleno.'),
		array('botox-sonrisa-gingival', 'Botox para sonrisa gingival', 'botox', 1250, 'Reduce la encía que se muestra al sonreír.'),
		array('botox-foxy-eyes-cdmx', 'Botox foxy eyes', 'botox', 1250, 'Eleva sutilmente la cola de la ceja para una mirada más abierta.'),
		array('botox-para-bruxismo-en-maseteros-cdmx', 'Botox para bruxismo en maseteros', 'botox', 8499, 'Reduce el apretamiento dental, el dolor mandibular y afina el rostro.'),
		array('botox-trapecio-trap-tox', 'Botox trapecio (Trap-Tox / Barbie Botox)', 'botox', 8499, 'Estiliza la silueta de cuello y hombros y alivia la tensión muscular.'),
		array('botox-para-hiperhidrosis-en-cdmx', 'Botox hiperhidrosis (axilar, palmar o plantar)', 'botox', 8499, 'Controla la sudoración excesiva en axilas, manos o pies.', 'zona'),
		array('botox-bunny-lines', 'Botox bunny lines', 'botox', 1250, 'Suaviza las líneas a los lados de la nariz.'),
		array('botox-para-menton-en-cdmx', 'Botox para mentón', 'botox', 1250, 'Relaja el mentón en piel de naranja y armoniza el perfil.'),
		array('botox-dao', 'Botox DAO (comisuras descendidas)', 'botox', 1250, 'Relaja el músculo que tira hacia abajo de las comisuras de la boca.'),
		array('relleno-de-labios', 'Relleno de labios', 'rellenos', 6499, 'Volumen, definición y forma natural.'),
		array('relleno-de-ojeras-con-acido-hialuronico', 'Relleno de ojeras', 'rellenos', 6499, 'Corrige el surco lagrimal y la sombra bajo los ojos con cánula.'),
		array('relleno-de-pomulos-con-acido-hialuronico', 'Relleno de pómulos', 'rellenos', 6499, 'Devuelve proyección y soporte al tercio medio del rostro.'),
		array('relleno-de-menton', 'Relleno de mentón', 'rellenos', 6499, 'Definición y armonía del perfil.'),
		array('relleno-de-surcos-nasogenianos-en-cdmx', 'Relleno de surcos nasogenianos', 'rellenos', 6499, 'Suaviza las líneas de nariz a boca sin perder naturalidad.'),
		array('rinomodelacion-en-cdmx', 'Rinomodelación', 'rellenos', 6499, 'Corrige giba y punta de la nariz sin cirugía.'),
		array('relleno-de-fosa-temporal-cdmx', 'Relleno de región temporal', 'rellenos', 6499, 'Rellena el hundimiento de las sienes y rejuvenece el contorno.'),
		array('marcaje-mandibular-con-acido-hialuronico-cdmx', 'Marcaje mandibular', 'rellenos', 6499, 'Define el ángulo y la línea de la mandíbula para un perfil esculpido.', null, true),
		array('armonizacion-facial-con-acido-hialuronico', 'Armonización facial', 'rellenos', 6499, 'Tratamiento personalizado según tus objetivos.', null, true),
		array('tratamiento-sculptra-en-cdmx', 'Sculptra', 'bio', 14999, 'Ácido poli-L-láctico que estimula colágeno y devuelve firmeza gradual.', 'vial'),
		array('tratamiento-radiesse-en-cdmx', 'Radiesse', 'bio', 8499, 'Hidroxiapatita de calcio para soporte inmediato y bioestimulación.', 'jeringa'),
		array('tratamiento-rejuran-en-cdmx', 'Rejuran', 'bio', 4499, 'Polinucleótidos que reparan la piel, mejoran textura, poros y luminosidad.', null, true),
		array('hifu-facial-en-cdmx', 'HIFU Ultramage 7D', 'piel', 7000, 'Ultrasonido focalizado que tensa óvalo y papada sin agujas.', 'sesión'),
		array('hollywood-peel-en-cdmx', 'Hollywood Carbon Peel', 'piel', 1499, 'Piel luminosa, poros cerrados y tono uniforme en una sesión.', 'sesión'),
		array('carboxiterapia-facial', 'Carboxiterapia facial', 'piel', 799, 'Para una piel más sana y luminosa.', 'sesión'),
		array('carboxiterapia-corporal', 'Carboxiterapia corporal por zona', 'piel', 999, 'Tratamiento corporal por zona.', 'sesión'),
		array('microneedling-en-cdmx', 'Microneedling', 'piel', 2499, 'Microperforaciones que activan colágeno para marcas y textura.', null, true),
		array('peeling-quimico-facial-en-cdmx', 'Peeling químico', 'piel', 2000, 'Renueva la piel, aclara manchas y suaviza líneas finas.'),
	);
	// On the flyers but without a service page: the row goes to its own page or hub when there is one, else to booking.
	$no_page = array(
		'botox-frente' => '/botox-cdmx/',
		'botox-sonrisa-gingival' => '/botox-cdmx/',
		'botox-bunny-lines' => '/botox-cdmx/',
		'botox-dao' => '/botox-cdmx/',
		'relleno-de-labios' => '/relleno-de-labios-cdmx/',
		'relleno-de-menton' => '/rellenos-cdmx/',
		'carboxiterapia-facial' => null,
		'carboxiterapia-corporal' => null,
	);
	$en = eg_lang() == 'en';
	$units = array('unidad' => 'unit', 'jeringa' => 'syringe', 'sesión' => 'session', 'zona' => 'area');
	$list = array();
	foreach($rows as $r){
		$unit = isset($r[5]) ? $r[5] : null;
		$price = (empty($r[6]) ? '' : ($en ? 'From ' : 'Desde ')).'$'.number_format($r[3]);
		if($unit){
			$price .= ' / '.($en && isset($units[$unit]) ? $units[$unit] : $unit);
		}
		if(array_key_exists($r[0], $no_page)){
			$url = $no_page[$r[0]] ? home_url($no_page[$r[0]]) : eg_booking_url();
		}else{
			// The long Sculptra service page was merged into the hub.
			$url = home_url($r[0] == 'tratamiento-sculptra-en-cdmx' ? '/sculptra-cdmx/' : "/servicio/{$r[0]}/");
		}
		$list[$r[0]] = (object)array(
			'slug' => $r[0],
			'name' => $r[1],
			'cat' => $r[2],
			'price' => $price,
			'desc' => $r[4],
			'url' => $url,
		);
	}
	return $list;
}
