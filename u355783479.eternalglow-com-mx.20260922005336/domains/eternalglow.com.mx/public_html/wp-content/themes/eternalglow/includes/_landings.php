<?php
// Hub pages by slug: what template-parts/landing.php adds to them. Prices come from includes/_treatments.php
// (the clinic's official list) so they are written once.
// - Pages with a 'hero' have no content in WordPress: the whole page is rendered from here.
// - Pages without one (Botox, hilos tensores) keep their WPBakery content; prices and questions go after it.
// Answers stay within what the clinic's pages and price list already state. Durations are given as ranges.

// One row of a price table that is not a catalogue treatment (a package, a tier).
function eg_price_row($name, $text, $price, $url = null){
	return (object)array('name' => $name, 'desc' => $text, 'price' => $price, 'url' => $url ?: eg_booking_url());
}

function eg_landing($slug){
	$all = eg_landings();
	return isset($all[$slug]) ? $all[$slug] : null;
}

function eg_landings(){
	static $landings = null;
	if($landings !== null){
		return $landings;
	}
	$by_cat = function($cat){
		return array_values(array_filter(eg_treatments(), function($item) use ($cat){
			return $item->cat == $cat;
		}));
	};
	$treatments = eg_treatments();
	$syringes = array();
	foreach(array(1 => 6499, 2 => 11000, 3 => 16000, 4 => 20000, 5 => 24000, 6 => 28000) as $n => $price){
		$syringes[] = eg_price_row($n == 1 ? '1 jeringa' : "$n jeringas", "$n mL de ácido hialurónico", '$'.number_format($price));
	}
	$lips = clone $treatments['relleno-de-labios'];
	$lips->name = 'Relleno de labios · 1 jeringa (1 mL)';
	$lips->url = eg_booking_url();

	$landings = array(
		'botox-cdmx' => array(
			'prices' => array(
				array('Precios de Botox', 'Toxina botulínica · MXN', $by_cat('botox')),
			),
			'faq_title' => 'Preguntas frecuentes sobre Botox',
			'faq' => array(
				array('¿Cuánto cuesta el Botox en CDMX?', 'En Eternal Glow el Botox cuesta $165 MXN por unidad. El Baby Botox (30 unidades) cuesta $4,499 y el Full Face (60 unidades) $8,499. Por zona: entrecejo $1,750, patas de gallo $2,150 y frente $2,750.'),
				array('¿Cuántas unidades de Botox necesito?', 'Depende de la zona y de la fuerza de tus músculos, por eso se define en la valoración médica. Como referencia, el Baby Botox usa 30 unidades y el Full Face 60.'),
				array('¿Cuándo se ven los resultados del Botox?', 'Los resultados comienzan a notarse entre 48 y 72 horas después de la aplicación.'),
				array('¿Cuánto dura el efecto del Botox?', 'El efecto dura de 3 a 5 meses. Varía según la zona, la dosis y tu metabolismo.'),
				array('¿El Baby Botox incluye retoque?', 'Sí. El protocolo de la clínica contempla un retoque de Baby Botox a las 2 semanas. El número de unidades y la indicación dependen de tu valoración.'),
				array('¿Hay tiempo de recuperación?', 'No. Es un procedimiento rápido y puedes retomar tus actividades el mismo día. En la valoración te explicamos los cuidados posteriores.'),
				array('¿Aplican Botox en los labios?', 'Sí. Para el código de barras se aplica <a href="/servicio/botox-en-los-labios/">Botox en los labios</a>, que se cobra por unidad, y para mostrar más labio superior sin relleno existe el <a href="/servicio/botox-lip-flip/">Lip Flip</a> ($1,250).'),
			),
		),
		'hilos-tensores-cdmx' => array(
			'prices' => array(
				array('Precio de hilos tensores', 'Precio por hilo · MXN', array(
					eg_price_row('1 a 4 hilos', 'Precio por cada hilo', '$1,500 / hilo'),
					eg_price_row('5 a 8 hilos', 'Precio por cada hilo', '$1,300 / hilo'),
					eg_price_row('9 o más hilos', 'Precio por cada hilo', '$1,100 / hilo'),
				)),
			),
			'faq_title' => 'Preguntas frecuentes sobre hilos tensores',
			'faq' => array(
				array('¿Cuánto cuestan los hilos tensores en CDMX?', 'En Eternal Glow el precio es por hilo: $1,500 MXN cada uno de 1 a 4 hilos, $1,300 de 5 a 8 hilos y $1,100 a partir de 9 hilos.'),
				array('¿Cuántos hilos tensores necesito?', 'Depende de la zona y del grado de flacidez. El médico define el número de hilos en la valoración, después de revisar tu rostro.'),
				array('¿En qué zonas se aplican los hilos tensores?', 'Las zonas más comunes son cejas (brow lift), foxy eyes, mejillas, surco nasogeniano, líneas de marioneta, línea mandibular, óvalo facial y cuello.'),
				array('¿Cuánto duran los resultados?', 'El efecto lifting se ve desde la aplicación y los resultados duran entre 1 y 1.5 años, mientras los hilos siguen estimulando colágeno.'),
				array('¿Es una cirugía?', 'No. Es un procedimiento ambulatorio y mínimamente invasivo: los hilos PDO se colocan con microcánulas, sin cortes.'),
			),
		),
		'rellenos-cdmx' => array(
			'hero' => array(
				'Ácido hialurónico · Roma Norte, CDMX',
				'Rellenos con ácido hialurónico en CDMX',
				'Volumen, definición y armonía con ácido hialurónico, aplicado por médico estético. Cada zona cuesta $6,499 MXN por jeringa y el plan se define en tu valoración.',
			),
			'sections' => array(
				array('¿Qué es un relleno con ácido hialurónico?', array(
					'El ácido hialurónico es una sustancia que el cuerpo produce de forma natural y que retiene agua en la piel. Como relleno es un gel que se inyecta para reponer volumen, definir contornos o suavizar surcos.',
					'El resultado se ve desde la aplicación y el cuerpo lo reabsorbe con el tiempo. En Eternal Glow trabajamos con marcas de referencia como Restylane e Yvoire.',
				)),
				array('¿Cómo es la cita?', array(
					'Todo empieza con una valoración médica: el doctor revisa tu rostro, escucha lo que buscas y te propone las zonas y la cantidad de producto.',
					'La mayoría de las aplicaciones toma menos de una hora y puedes retomar tus actividades el mismo día.',
				)),
			),
			'prices' => array(
				array('Precio por zona', 'Por jeringa de 1 mL · MXN', $by_cat('rellenos')),
				array('Paquetes por cantidad', 'Para un resultado global y armónico', $syringes),
			),
			'faq_title' => 'Preguntas frecuentes sobre rellenos',
			'faq' => array(
				array('¿Cuánto cuesta un relleno con ácido hialurónico en CDMX?', 'En Eternal Glow cada zona cuesta $6,499 MXN por jeringa de 1 mL. Por paquete, 2 jeringas cuestan $11,000, 3 jeringas $16,000 y 4 jeringas $20,000. El número de jeringas se define en la valoración.'),
				array('¿Cuánto dura un relleno con ácido hialurónico?', 'Depende de la zona, del producto y de tu metabolismo. En general dura entre 6 y 18 meses: menos en zonas con mucho movimiento, como los labios, y más en pómulos o mandíbula.'),
				array('¿Qué zonas se pueden tratar?', 'Labios, ojeras, pómulos, mentón, surcos nasogenianos, nariz (rinomodelación), región temporal y línea mandibular. Cuando se tratan varias zonas hablamos de armonización facial.'),
				array('¿Se puede revertir un relleno?', 'Sí. El ácido hialurónico puede disolverse con una enzima llamada hialuronidasa cuando el médico lo considera necesario.'),
				array('¿Qué diferencia hay entre relleno y Botox?', 'El relleno repone volumen y define contornos. El <a href="/botox-cdmx/">Botox</a> relaja los músculos que marcan las líneas de expresión. Se pueden combinar en un mismo plan.'),
				array('¿Hay tiempo de recuperación?', 'Puedes retomar tus actividades el mismo día. Es normal tener algo de inflamación o un moretón pequeño durante unos días.'),
			),
		),
		'relleno-de-labios-cdmx' => array(
			'hero' => array(
				'Ácido hialurónico · Roma Norte, CDMX',
				'Relleno de labios en CDMX',
				'Volumen, definición y forma natural con ácido hialurónico. $6,499 MXN por jeringa de 1 mL, aplicado por médico estético en Roma Norte.',
			),
			'sections' => array(
				array('¿Qué es el relleno de labios?', array(
					'Es la aplicación de ácido hialurónico en los labios para darles volumen, definir su contorno o corregir asimetrías. El producto es un gel que el cuerpo reabsorbe con el tiempo.',
					'Trabajamos por etapas y con cantidades medidas: el objetivo es que tus labios se vean proporcionados con tu rostro.',
				)),
				array('¿Para quién es?', array(
					'Para quien busca más volumen, un contorno más definido, corregir una asimetría o recuperar el volumen que los labios pierden con los años.',
					'Si lo que quieres es mostrar un poco más el labio superior sin agregar volumen, la alternativa es el <a href="/servicio/botox-lip-flip/">Botox Lip Flip</a>.',
				)),
				array('¿Cómo es la cita?', array(
					'Empieza con una valoración médica: el doctor revisa la forma de tus labios, tu perfil y lo que quieres lograr, y te propone la cantidad de producto.',
					'La aplicación toma menos de una hora y puedes retomar tus actividades el mismo día.',
				)),
			),
			'prices' => array(
				array('Precio', 'Ácido hialurónico · MXN', array($lips)),
				array('Tratamientos relacionados', 'Para labios y perfil', array($treatments['botox-lip-flip'], $treatments['relleno-de-menton'], $treatments['armonizacion-facial-con-acido-hialuronico'])),
			),
			'faq_title' => 'Preguntas frecuentes sobre relleno de labios',
			'faq' => array(
				array('¿Cuánto cuesta el relleno de labios en CDMX?', 'En Eternal Glow el relleno de labios cuesta $6,499 MXN por jeringa de 1 mL de ácido hialurónico. La cantidad que necesitas se define en la valoración.'),
				array('¿Cuánto dura el relleno de labios?', 'Suele durar entre 6 y 12 meses. Los labios se mueven mucho, por eso el producto se reabsorbe antes que en otras zonas; también influyen el producto y tu metabolismo.'),
				array('¿Los labios quedan naturales?', 'Sí. Aplicamos cantidades medidas y por etapas para respetar la forma de tus labios y las proporciones de tu rostro.'),
				array('¿Cuánto tarda en bajar la inflamación?', 'Es normal que los labios se inflamen los primeros días y que aparezca algún moretón pequeño. El resultado se aprecia mejor cuando la inflamación baja.'),
				array('¿Qué diferencia hay entre relleno de labios y Lip Flip?', 'El relleno agrega volumen con ácido hialurónico. El <a href="/servicio/botox-lip-flip/">Lip Flip</a> usa Botox para relajar el músculo y mostrar más labio superior, sin volumen. Para el código de barras se usa <a href="/servicio/botox-en-los-labios/">Botox en los labios</a>.'),
				array('¿Se puede revertir?', 'Sí. El ácido hialurónico puede disolverse con hialuronidasa cuando el médico lo considera necesario.'),
			),
		),
	);
	return $landings;
}
