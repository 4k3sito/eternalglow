<?php
// Hub pages by slug: what template-parts/landing.php adds to them. Prices come from includes/_treatments.php
// (the clinic's official list) so they are written once.
// - Pages with a 'hero' have no content in WordPress: the whole page is rendered from here ('image' sits beside the hero).
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

	// The catalogue is written in Spanish: English pages keep its prices and links and swap the copy.
	$english = function($rows) use ($treatments){
		$items = array();
		foreach($rows as $slug => $copy){
			$item = clone $treatments[$slug];
			list($item->name, $item->desc) = $copy;
			$items[] = $item;
		}
		return $items;
	};

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
		// English version of /botox-cdmx/ (child of /en/, so eg_lang() is 'en'). Same facts and prices as the Spanish hub.
		'botox-mexico-city' => array(
			'image' => array('2026/03/botox-para-lineas-de-expresion-cdmx.png', 'Botox treatment for expression lines at Eternal Glow, Mexico City'),
			'hero' => array(
				'Botulinum toxin · Roma Norte, Mexico City',
				'Botox in Mexico City',
				'Softer expression lines with a natural result, applied by an aesthetic physician. From $165 MXN per unit, with English spoken at the clinic.',
			),
			'sections' => array(
				array('What is Botox?', array(
					'Botox (botulinum toxin) temporarily relaxes the muscles that cause expression lines, mainly on the forehead, between the brows and around the eyes. It is applied with micro injections.',
					'Results start to show 48 to 72 hours after the treatment and last 3 to 5 months. It is quick and you can go back to your plans the same day.',
				)),
				array('How does the visit work?', array(
					'Every treatment starts with a medical consultation: the doctor looks at your face, listens to what you want and decides with you the areas and the number of units.',
					'We see patients by appointment at Valladolid 56, Roma Norte, a short walk from Condesa and Juárez.',
				)),
			),
			'prices' => array(
				array('Botox prices', 'Botulinum toxin · MXN', $english(array(
					'botox-por-unidad' => array('Botox per unit', 'A tailored dose for one area: forehead, frown lines or lip lines.'),
					'baby-botox-cdmx' => array('Baby botox (30 units)', 'Micro doses for forehead, frown lines and eyes. Softens without freezing your expression.'),
					'full-face-botox-60-unidades' => array('Full face botox (60 units)', 'Complete treatment of the upper and lower face for a rested look.'),
					'botox-frente' => array('Forehead botox', 'Softens the horizontal lines on the forehead.'),
					'botox-en-entrecejo' => array('Frown lines botox', 'Relaxes the lines between the brows that make you look angry or tired.'),
					'botox-para-patas-de-gallo-cdmx' => array('Crow’s feet botox', 'Softens the lines beside the eyes while keeping a natural smile.'),
					'botox-en-cuello-cdmx' => array('Neck botox', 'Relaxes the platysmal bands and defines the jawline.'),
					'botox-lip-flip' => array('Botox lip flip', 'A subtle turn of the upper lip to show more of it without filler.'),
					'botox-sonrisa-gingival' => array('Gummy smile botox', 'Reduces how much gum shows when you smile.'),
					'botox-foxy-eyes-cdmx' => array('Foxy eyes botox', 'Gently lifts the tail of the brow for a more open look.'),
					'botox-para-bruxismo-en-maseteros-cdmx' => array('Masseter botox for bruxism', 'Eases teeth clenching and jaw pain and slims the face.'),
					'botox-trapecio-trap-tox' => array('Trap botox (Trap-Tox / Barbie Botox)', 'Refines the neck and shoulder line and relieves muscle tension.'),
					'botox-para-hiperhidrosis-en-cdmx' => array('Botox for hyperhidrosis (underarms, hands or feet)', 'Controls excessive sweating of the underarms, hands or feet.'),
					'botox-bunny-lines' => array('Bunny lines botox', 'Softens the lines on the sides of the nose.'),
					'botox-para-menton-en-cdmx' => array('Chin botox', 'Relaxes a dimpled chin and balances the profile.'),
					'botox-dao' => array('DAO botox (downturned mouth corners)', 'Relaxes the muscle that pulls the corners of the mouth down.'),
				))),
			),
			'faq_title' => 'Botox questions',
			'faq' => array(
				array('How much does Botox cost in Mexico City?', 'At Eternal Glow Botox costs $165 MXN per unit. Baby Botox (30 units) is $4,499 MXN and Full Face (60 units) is $8,499 MXN. By area: frown lines $1,750, crow’s feet $2,150 and forehead $2,750 MXN.'),
				array('How many units of Botox do I need?', 'It depends on the area and the strength of your muscles, so the doctor decides it in your consultation. As a reference, Baby Botox uses 30 units and Full Face 60.'),
				array('When will I see the results?', 'Results start to show 48 to 72 hours after the treatment.'),
				array('How long does Botox last?', 'The effect lasts 3 to 5 months. It varies with the area, the dose and your metabolism.'),
				array('Does Baby Botox include a touch up?', 'Yes. The clinic’s protocol includes a Baby Botox touch up after 2 weeks. The number of units depends on your consultation.'),
				array('Is there any downtime?', 'No. It is a quick procedure and you can go back to your plans the same day. We explain the aftercare in your consultation.'),
				array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up. You can also book online or message us on WhatsApp in English.'),
			),
		),
		// English version of /sculptra-cdmx/. Same facts and prices as the Spanish hub.
		'sculptra-mexico-city' => array(
			'image' => array('2026/06/piel-despues-tratamiento-sculptra-cdmx.png', 'Skin after a Sculptra treatment at Eternal Glow, Mexico City'),
			'hero' => array(
				'Collagen biostimulator · Roma Norte, Mexico City',
				'Sculptra in Mexico City',
				'Firmer skin and restored volume that build up gradually as your own collagen grows, without changing your expression. $14,999 MXN per vial.',
			),
			'sections' => array(
				array('What is Sculptra?', array(
					'Sculptra is an injectable made of poly-L-lactic acid. It is not a filler: instead of adding volume on the spot, it stimulates your skin to produce its own collagen, so firmness, density and support improve over the following weeks and months.',
					'That is why the result looks natural. Your features do not change overnight; the improvement builds up as your body responds.',
				)),
				array('Who is it for?', array(
					'Women and men who notice loss of firmness, mild to moderate sagging, hollow cheeks, a less defined facial contour or lost volume after weight loss, and who prefer a gradual result to an obvious change.',
					'There is no set age to start. The doctor decides in your consultation, based on your skin, your medical history and what you want to achieve.',
				)),
				array('How does the treatment work?', array(
					'It starts with a medical consultation, where we define the areas, the number of vials and the number of sessions. Many patients need two or three sessions a few weeks apart.',
					'Each session takes 30 to 45 minutes. Sculptra is placed in the deep layers of the skin with a cannula or a fine needle. Afterwards you massage the area 5 times a day for 5 days, and avoid intense exercise on the first day.',
				)),
			),
			'prices' => array(
				array('Sculptra prices', 'Poly-L-lactic acid · MXN', array(
					eg_price_row('Sculptra · 1 vial', 'Collagen biostimulator for the face', $treatments['tratamiento-sculptra-en-cdmx']->price),
					eg_price_row('Sculptra · 2 vials', 'Package for the face', '$28,000'),
					eg_price_row('Sculptra · 3 vials', 'Package for the face', '$33,000'),
					eg_price_row('Body Sculptra · 5 vials', 'Body treatment; areas set in your consultation', '$52,999'),
					eg_price_row('Body Sculptra · 10 vials', 'Body treatment; areas set in your consultation', '$99,999'),
				)),
			),
			'faq_title' => 'Sculptra questions',
			'faq' => array(
				array('How much does Sculptra cost in Mexico City?', 'At Eternal Glow Sculptra costs $14,999 MXN per vial; 2 vials cost $28,000 and 3 vials $33,000 MXN. For the body, 5 vials cost $52,999 and 10 vials $99,999 MXN. The number of vials and sessions is set in your consultation.'),
				array('Is Sculptra hyaluronic acid?', 'No. Sculptra is poly-L-lactic acid. Hyaluronic acid adds volume right away, while Sculptra stimulates new collagen gradually. They can be combined in the same plan.'),
				array('When will I see the results?', 'Some patients notice changes during the first month, and the improvement keeps developing over the following months as collagen increases.'),
				array('Why does the initial volume go away?', 'The volume of the first days comes mostly from the liquid used to prepare the product and from swelling. It fades, and then the real biostimulation starts.'),
				array('How many sessions do I need?', 'Many patients need two or three sessions a few weeks apart. The exact number depends on your skin, the loss of support and how you respond.'),
				array('How long do the results last?', 'In some patients the effect lasts up to about two years. It depends on the protocol, metabolism, age, habits and skin quality.'),
				array('Does it hurt?', 'You may feel pressure, small pricks or tenderness. The product can be prepared with a local anaesthetic to make you more comfortable.'),
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
			'image' => array('2026/05/tratamiento-de-pomulos-con-acido-hialuronico.png', 'Aplicación de ácido hialurónico en pómulos en Eternal Glow, CDMX'),
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
			'image' => array('2026/05/rejuvenecimiento-labial-cdmx-resultados.jpg', 'Imagen ilustrativa de labios antes y después de un relleno con ácido hialurónico'),
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
