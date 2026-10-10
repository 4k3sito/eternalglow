<?php
// The clinic's catalogue as the home and /tratamientos/ show it: one row per service page.
// Names and one-line descriptions come from the client's design prototype.

// Prices, session length and how long results last also come from the prototype, where they were
// sample data. They stay hidden until the clinic confirms them; then return true here.
function eg_show_prices(){
	return false;
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
	// slug of the service page, name, category, price (MXN), minutes, results last, description, price unit
	$rows = array(
		array('botox-por-unidad', 'Botox por unidad', 'botox', 180, 15, '3–4 meses', 'Dosis a la medida para tratar una zona puntual con precisión.', ' / unidad'),
		array('baby-botox-cdmx', 'Baby botox (30 unidades)', 'botox', 4900, 30, '3–4 meses', 'Microdosis en frente, entrecejo y ojos. Suaviza sin congelar la expresión.'),
		array('full-face-botox-60-unidades', 'Full face botox (60 unidades)', 'botox', 8900, 45, '4 meses', 'Tratamiento completo del tercio superior e inferior para un rostro descansado.'),
		array('botox-en-entrecejo', 'Botox en entrecejo', 'botox', 3200, 20, '3–4 meses', 'Relaja las líneas del ceño que dan aspecto de enojo o cansancio.'),
		array('botox-para-patas-de-gallo-cdmx', 'Botox patas de gallo', 'botox', 3200, 20, '3–4 meses', 'Suaviza las líneas laterales de los ojos manteniendo una sonrisa natural.'),
		array('botox-foxy-eyes-cdmx', 'Botox foxy eyes', 'botox', 3800, 20, '3–4 meses', 'Eleva sutilmente la cola de la ceja para una mirada más abierta.'),
		array('botox-en-cuello-cdmx', 'Botox en cuello', 'botox', 5900, 30, '4 meses', 'Relaja las bandas del platisma y define la línea mandibular.'),
		array('botox-trapecio-trap-tox', 'Botox trapecio (traptox)', 'botox', 7500, 30, '4–6 meses', 'Estiliza la silueta de cuello y hombros y alivia la tensión muscular.'),
		array('botox-lip-flip', 'Botox Lip Flip', 'botox', 2200, 15, '2–3 meses', 'Eversión sutil del labio superior para mostrar más volumen sin relleno.'),
		array('botox-en-los-labios', 'Botox en los labios', 'botox', 2600, 15, '2–3 meses', 'Suaviza el código de barras alrededor de la boca.'),
		array('botox-para-menton-en-cdmx', 'Botox para mentón', 'botox', 2400, 15, '3–4 meses', 'Relaja el mentón en piel de naranja y armoniza el perfil.'),
		array('botox-para-bruxismo-en-maseteros-cdmx', 'Botox para bruxismo en maseteros', 'botox', 6200, 30, '4–6 meses', 'Reduce el apretamiento dental, el dolor mandibular y afina el rostro.'),
		array('botox-para-hiperhidrosis-en-cdmx', 'Botox hiperhidrosis (sudoración)', 'botox', 9500, 45, '6–9 meses', 'Controla la sudoración excesiva en axilas, manos o pies.'),
		array('relleno-de-ojeras-con-acido-hialuronico', 'Relleno de ojeras', 'rellenos', 6900, 45, '9–12 meses', 'Corrige el surco lagrimal y la sombra bajo los ojos con cánula.'),
		array('relleno-de-pomulos-con-acido-hialuronico', 'Relleno de pómulos', 'rellenos', 7500, 45, '12–18 meses', 'Devuelve proyección y soporte al tercio medio del rostro.'),
		array('relleno-de-surcos-nasogenianos-en-cdmx', 'Relleno de surcos nasogenianos', 'rellenos', 6500, 40, '9–12 meses', 'Suaviza las líneas de nariz a boca sin perder naturalidad.'),
		array('relleno-de-fosa-temporal-cdmx', 'Relleno de fosa temporal', 'rellenos', 7900, 45, '12–18 meses', 'Rellena el hundimiento de las sienes y rejuvenece el contorno.'),
		array('marcaje-mandibular-con-acido-hialuronico-cdmx', 'Marcaje mandibular', 'rellenos', 12500, 60, '12–18 meses', 'Define el ángulo y la línea de la mandíbula para un perfil esculpido.'),
		array('rinomodelacion-en-cdmx', 'Rinomodelación', 'rellenos', 7200, 40, '12 meses', 'Corrige giba y punta de la nariz sin cirugía.'),
		array('armonizacion-facial-con-acido-hialuronico', 'Armonización facial (3 a 4 jeringas)', 'rellenos', 21900, 90, '12–18 meses', 'Plan integral para equilibrar proporciones en varias zonas.'),
		array('armonizacion-facial-full-face-cdmx', 'Armonización facial full face (5 a 6 ml)', 'rellenos', 29900, 120, '12–18 meses', 'Rediseño completo y progresivo del rostro con ácido hialurónico.'),
		array('tratamiento-sculptra-en-cdmx', 'Sculptra', 'bio', 11500, 45, '18–24 meses', 'Ácido poli-L-láctico que estimula colágeno y devuelve firmeza gradual.', ' / vial'),
		array('tratamiento-radiesse-en-cdmx', 'Radiesse', 'bio', 10900, 45, '12–18 meses', 'Hidroxiapatita de calcio para soporte inmediato y bioestimulación.', ' / jeringa'),
		array('tratamiento-rejuran-en-cdmx', 'Rejuran (PN)', 'bio', 8900, 45, '6–9 meses', 'Polinucleótidos que reparan la piel, mejoran textura, poros y luminosidad.'),
		array('tratamiento-pdrn-salmon', 'PDRN salmón', 'bio', 6500, 40, '4–6 meses', 'Regenerador celular para piel apagada, cicatrices y post-procedimiento.'),
		array('hifu-facial-en-cdmx', 'HIFU facial', 'piel', 9900, 60, '12 meses', 'Ultrasonido focalizado que tensa óvalo y papada sin agujas.'),
		array('hollywood-peel-en-cdmx', 'Hollywood Carbon Peel (picoláser)', 'piel', 2900, 40, 'Inmediato', 'Piel luminosa, poros cerrados y tono uniforme en una sesión.'),
		array('microneedling-en-cdmx', 'Microneedling', 'piel', 2500, 60, '4–6 semanas', 'Microperforaciones que activan colágeno para marcas y textura.'),
		array('peeling-quimico-facial-en-cdmx', 'Peeling químico facial', 'piel', 1900, 40, '4 semanas', 'Renueva la piel, aclara manchas y suaviza líneas finas.'),
	);
	$list = array();
	foreach($rows as $r){
		$list[$r[0]] = (object)array(
			'slug' => $r[0],
			'name' => $r[1],
			'cat' => $r[2],
			'price' => '$'.number_format($r[3]).(isset($r[7]) ? $r[7] : ''),
			'minutes' => $r[4].' min',
			'lasts' => $r[5],
			'desc' => $r[6],
			// The long Sculptra service page was merged into the hub.
			'url' => home_url($r[0] == 'tratamiento-sculptra-en-cdmx' ? '/sculptra-cdmx/' : "/servicio/{$r[0]}/"),
		);
	}
	return $list;
}
