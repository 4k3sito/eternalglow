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

// Pesos per dollar for the USD figures on the English hubs: Banco de México FIX, rounded (18.65 on 2026-10-27).
// Update it when the rate moves by more than about 5%.
define('EG_USD_RATE', 18.7);

function eg_landing($slug){
	$all = eg_landings();
	return isset($all[$slug]) ? $all[$slug] : null;
}

// When a hub page last changed. Its prices and questions live in this file, not in the post, so a landing can carry
// an 'updated' date for edits that never touch the post's own modified date (bump it when those change).
function eg_page_modified(){
	$landing = is_page() ? eg_landing(get_post()->post_name) : null;
	// 'updated' is a calendar day in the clinic's timezone; noon keeps it on that day whatever the offset.
	$updated = !empty($landing['updated']) ? (new DateTimeImmutable($landing['updated'].' 12:00', wp_timezone()))->getTimestamp() : 0;
	return max((int)get_the_modified_date('U'), $updated);
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
	// Prices are charged in MXN; English pages show a rounded dollar figure beside them (see EG_USD_RATE).
	$usd = function($mxn){
		return '$'.number_format(round($mxn / EG_USD_RATE)).' USD';
	};
	// Botox hub groups. 'zonas' takes every Botox treatment not listed in the other two, so a new one still shows up.
	$botox_groups = array(
		'paquetes' => array('botox-por-unidad', 'baby-botox-cdmx', 'full-face-botox-60-unidades'),
		'medico' => array('botox-para-bruxismo-en-maseteros-cdmx', 'botox-trapecio-trap-tox', 'botox-para-hiperhidrosis-en-cdmx'),
	);
	$botox_group = function($group) use ($by_cat, $botox_groups){
		$listed = array_merge(...array_values($botox_groups));
		return array_values(array_filter($by_cat('botox'), function($item) use ($group, $botox_groups, $listed){
			return isset($botox_groups[$group]) ? in_array($item->slug, $botox_groups[$group], true) : !in_array($item->slug, $listed, true);
		}));
	};
	$syringes = array();
	foreach(array(1 => 6499, 2 => 11000, 3 => 16000, 4 => 20000, 5 => 24000, 6 => 28000) as $n => $price){
		$syringes[] = eg_price_row($n == 1 ? '1 jeringa' : "$n jeringas", "$n mL de ácido hialurónico", '$'.number_format($price));
	}
	$syringes_en = array();
	foreach(array(1 => 6499, 2 => 11000, 3 => 16000, 4 => 20000, 5 => 24000, 6 => 28000) as $n => $price){
		$syringes_en[] = eg_price_row($n == 1 ? '1 syringe' : "$n syringes", "$n mL of hyaluronic acid", '$'.number_format($price));
	}
	$lips = clone $treatments['relleno-de-labios'];
	$lips->name = 'Relleno de labios · 1 jeringa (1 mL)';
	$lips->url = eg_booking_url();
	$lips_en = clone $lips;
	$lips_en->name = 'Lip filler · 1 syringe (1 mL)';
	$lips_en->desc = 'Volume, definition and a natural shape.';

	// The catalogue is written in Spanish: English pages keep its prices and links and swap the copy.
	$english = function($rows) use ($treatments){
		$items = array();
		foreach($rows as $slug => $copy){
			$item = clone $treatments[$slug];
			list($item->name, $item->desc) = $copy;
			// Optional third value: an English page to link instead of the Spanish one.
			if(isset($copy[2])){
				$item->url = home_url($copy[2]);
			}
			$items[] = $item;
		}
		return $items;
	};

	$landings = array(
		'botox-cdmx' => array(
			'updated' => '2026-10-20',
			// Three short tables instead of one list of 16: packages, areas of the face, medical and body uses.
			'prices' => array(
				array('Paquetes de Botox', 'Toxina botulínica · MXN', $botox_group('paquetes')),
				array('Botox por zona', 'Rostro y cuello · MXN', $botox_group('zonas')),
				array('Botox médico y corporal', 'MXN', $botox_group('medico')),
			),
			// Approved by the user on 2026-10-20. Same shape as 'sections': a title and paragraphs (links and <strong> allowed).
			'risks' => array('Riesgos y contraindicaciones del Botox', array(
				'Bien aplicado por un médico, los efectos secundarios suelen ser leves y temporales, pero no es para todos.',
				'<strong>No se recomienda si:</strong> estás embarazada o en lactancia; tienes una enfermedad neuromuscular (como miastenia gravis); hay infección o inflamación en la zona; tuviste una reacción alérgica a la toxina botulínica.',
				'<strong>Efectos posibles:</strong> enrojecimiento, un moretón pequeño o dolor de cabeza leve los primeros días. Con poca frecuencia baja un poco el párpado o la ceja, de forma temporal.',
				'Avisa en tu valoración si tomas anticoagulantes o antibióticos aminoglucósidos.',
			)),
			'faq_title' => 'Preguntas frecuentes sobre Botox',
			'faq' => array(
				array('¿Cuánto cuesta el Botox en CDMX?', 'En Eternal Glow el Botox cuesta $165 MXN por unidad. El Baby Botox (30 unidades) cuesta $4,499 y el Full Face (60 unidades) $8,499. Por zona: entrecejo $1,750, patas de gallo $2,150 y frente $2,750.'),
				array('¿Cuántas unidades de Botox necesito?', 'Depende de la zona y de la fuerza de tus músculos, por eso se define en la valoración médica. Como referencia, el Baby Botox usa 30 unidades y el Full Face 60.'),
				array('¿Cuándo se ven los resultados del Botox?', 'Los primeros cambios se notan entre 48 y 72 horas después de la aplicación, y el efecto completo, a los 14 días.'),
				array('¿Cuánto dura el efecto del Botox?', 'El efecto dura de 3 a 5 meses. Varía según la zona, la dosis y tu metabolismo.'),
				array('¿El Baby Botox incluye retoque?', 'Sí. El protocolo de la clínica contempla un retoque de Baby Botox a las 2 semanas. El número de unidades y la indicación dependen de tu valoración.'),
				array('¿Hay tiempo de recuperación?', 'No. Es un procedimiento rápido y puedes retomar tus actividades el mismo día. En la valoración te explicamos los cuidados posteriores.'),
				array('¿Aplican Botox en los labios?', 'Sí. Para el código de barras se aplica <a href="/servicio/botox-en-los-labios/">Botox en los labios</a>, que se cobra por unidad, y para mostrar más labio superior sin relleno existe el <a href="/servicio/botox-lip-flip/">Lip Flip</a> ($1,250).'),
			),
		),
		// English version of /botox-cdmx/ (child of /en/, so eg_lang() is 'en'). Same facts and prices as the Spanish hub.
		'botox-mexico-city' => array(
			'updated' => '2026-10-27',
			'image' => array('2026/03/botox-para-lineas-de-expresion-cdmx.png', 'Botox treatment for expression lines at Eternal Glow, Mexico City'),
			'hero' => array(
				'Botulinum toxin · Roma Norte, Mexico City',
				'Botox in Mexico City',
				'Softer expression lines with a natural result, applied by an aesthetic physician. From $165 MXN (about '.$usd(165).') per unit, with English spoken at the clinic.',
			),
			'sections' => array(
				array('What is Botox?', array(
					'Botox (botulinum toxin) temporarily relaxes the muscles that cause expression lines, mainly on the forehead, between the brows and around the eyes. It is applied with micro injections.',
					'The first changes show 48 to 72 hours after the treatment, the full effect at 14 days, and it lasts 3 to 5 months. It is quick and you can go back to your plans the same day.',
					'We work with reference brands of botulinum toxin, Botox and Xeomin, and the doctor tells you which one and how many units before the treatment.',
				)),
				array('How much is Botox in Mexico City compared with the US?', array(
					'Botox costs $165 MXN per unit at Eternal Glow, about '.$usd(165).'. Baby Botox (30 units) is $4,499 MXN, about '.$usd(4499).', and Full Face (60 units) is $8,499 MXN, about '.$usd(8499).'.',
					'US clinics usually charge $10 to $25 USD per unit, according to 2026 price guides, so 30 units there cost roughly $300 to $750 USD and 60 units $600 to $1,500 USD. The table below puts both side by side.',
					'You pay in Mexican pesos. The dollar figures use about '.EG_USD_RATE.' pesos per dollar (Banco de México, October 2026) and move with the exchange rate. The price per unit is the same in every area: what changes is how many units your face needs.',
				)),
				array('Is Botox in Mexico City safe?', array(
					'Botox is a prescription medicine, and how safe it is depends on two things: who applies it and which product goes into the syringe. At Eternal Glow every treatment is done by Dr. Bruno Ocampo, a licensed physician (professional license 13493041, listed in the Mexican Ministry of Education’s national registry).',
					'The clinic’s advertising is authorized by COFEPRIS, Mexico’s federal health regulator (permit 2509152002A00185).',
					'Wherever you get Botox, ask for the injector’s medical license, the brand and the number of units. Be wary of a price per unit far below the usual range: it often means a diluted or unbranded product.',
				)),
				array('Planning your visit', array(
					'Every treatment starts with a medical consultation: the doctor looks at your face, listens to what you want and decides with you the areas and the number of units. You can <a href="'.esc_url(eg_booking_url()).'">book online</a> or message us on WhatsApp in English.',
					'We see patients by appointment at Valladolid 56, Roma Norte, a short walk from Condesa and Juárez, Monday to Friday from 11:30 to 18:00 and Saturday from 11:30 to 17:00.',
					'Timing: the full effect shows at 14 days, so book at least two weeks before an event or a photo. Baby Botox includes a touch up after 2 weeks, so plan your trip to be in the city then, or ask about it in your consultation.',
					'Aftercare: for 4 hours do not lie down, press or massage the area, and for 24 hours avoid intense exercise, saunas and heat. Botox can be combined with <a href="/en/fillers-mexico-city/">fillers</a> in the same plan.',
				)),
			),
			'prices' => array(
				// Same three groups as the Spanish hub.
				array('Botox packages', 'Botulinum toxin · MXN', $english(array(
					'botox-por-unidad' => array('Botox per unit', 'A tailored dose for one area: forehead, frown lines or lip lines.'),
					'baby-botox-cdmx' => array('Baby botox (30 units)', 'Micro doses for forehead, frown lines and eyes. Softens without freezing your expression.'),
					'full-face-botox-60-unidades' => array('Full face botox (60 units)', 'Complete treatment of the upper and lower face for a rested look.'),
				))),
				array('Botox by area', 'Face and neck · MXN', $english(array(
					'botox-frente' => array('Forehead botox', 'Softens the horizontal lines on the forehead.'),
					'botox-en-entrecejo' => array('Frown lines botox', 'Relaxes the lines between the brows that make you look angry or tired.'),
					'botox-para-patas-de-gallo-cdmx' => array('Crow’s feet botox', 'Softens the lines beside the eyes while keeping a natural smile.'),
					'botox-en-cuello-cdmx' => array('Neck botox', 'Relaxes the platysmal bands and defines the jawline.'),
					'botox-lip-flip' => array('Botox lip flip', 'A subtle turn of the upper lip to show more of it without filler.'),
					'botox-sonrisa-gingival' => array('Gummy smile botox', 'Reduces how much gum shows when you smile.'),
					'botox-foxy-eyes-cdmx' => array('Foxy eyes botox', 'Gently lifts the tail of the brow for a more open look.'),
					'botox-bunny-lines' => array('Bunny lines botox', 'Softens the lines on the sides of the nose.'),
					'botox-para-menton-en-cdmx' => array('Chin botox', 'Relaxes a dimpled chin and balances the profile.'),
					'botox-dao' => array('DAO botox (downturned mouth corners)', 'Relaxes the muscle that pulls the corners of the mouth down.'),
				))),
				array('Medical and body botox', 'MXN', $english(array(
					'botox-para-bruxismo-en-maseteros-cdmx' => array('Masseter botox for bruxism', 'Eases teeth clenching and jaw pain and slims the face.'),
					'botox-trapecio-trap-tox' => array('Trap botox (Trap-Tox / Barbie Botox)', 'Refines the neck and shoulder line and relieves muscle tension.'),
					'botox-para-hiperhidrosis-en-cdmx' => array('Botox for hyperhidrosis (underarms, hands or feet)', 'Controls excessive sweating of the underarms, hands or feet.'),
				))),
				// US ranges: $10-$25 USD per unit in 2026 US price guides, times the units.
				array('Mexico City vs the US', 'Eternal Glow price · approximate USD', array(
					eg_price_row('Botox per unit', 'Typical US clinic: $10 to $25 USD per unit.', '$165 MXN ≈ '.$usd(165)),
					eg_price_row('Baby Botox (30 units)', 'Typical US clinic: $300 to $750 USD for 30 units.', '$4,499 MXN ≈ '.$usd(4499)),
					eg_price_row('Full Face Botox (60 units)', 'Typical US clinic: $600 to $1,500 USD for 60 units.', '$8,499 MXN ≈ '.$usd(8499)),
				)),
			),
			'risks' => array('Botox risks and contraindications', array(
				'When a physician applies it, side effects are usually mild and temporary, but it is not for everyone.',
				'<strong>It is not recommended if:</strong> you are pregnant or breastfeeding; you have a neuromuscular disease (such as myasthenia gravis); there is an infection or inflammation in the area; you have had an allergic reaction to botulinum toxin.',
				'<strong>Possible effects:</strong> redness, a small bruise or a mild headache in the first days. Less often the eyelid or the brow drops slightly, temporarily.',
				'Tell the doctor at your consultation if you take anticoagulants or aminoglycoside antibiotics.',
			)),
			'faq_title' => 'Botox questions',
			'faq' => array(
				array('How much does Botox cost in Mexico City?', 'At Eternal Glow Botox costs $165 MXN per unit (about '.$usd(165).'). Baby Botox (30 units) is $4,499 MXN and Full Face (60 units) is $8,499 MXN. By area: frown lines $1,750, crow’s feet $2,150 and forehead $2,750 MXN.'),
				array('How many units of Botox do I need?', 'It depends on the area and the strength of your muscles, so the doctor decides it in your consultation. As a reference, Baby Botox uses 30 units and Full Face 60.'),
				array('When will I see the results?', 'The first changes show 48 to 72 hours after the treatment, and the full effect at 14 days.'),
				array('How long does Botox last?', 'The effect lasts 3 to 5 months. It varies with the area, the dose and your metabolism.'),
				array('Does Baby Botox include a touch up?', 'Yes. The clinic’s protocol includes a Baby Botox touch up after 2 weeks. The number of units depends on your consultation.'),
				array('Is there any downtime?', 'No. It is a quick procedure and you can go back to your plans the same day. We explain the aftercare in your consultation.'),
				array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up. You can also book online or message us on WhatsApp in English.'),
			),
		),
		// English version of /sculptra-cdmx/. Same facts and prices as the Spanish hub.
		'sculptra-mexico-city' => array(
			'updated' => '2026-10-27',
			'image' => array('2026/06/piel-despues-tratamiento-sculptra-cdmx.png', 'Skin after a Sculptra treatment at Eternal Glow, Mexico City'),
			'hero' => array(
				'Collagen biostimulator · Roma Norte, Mexico City',
				'Sculptra in Mexico City',
				'Firmer skin and restored volume that build up gradually as your own collagen grows, without changing your expression. $14,999 MXN (about '.$usd(14999).') per vial, with English spoken at the clinic.',
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
					'Each session takes 30 to 45 minutes. Sculptra is placed in the deep layers of the skin with a cannula or a fine needle, and the product can be prepared with a local anaesthetic to make you more comfortable.',
				)),
				array('How much is Sculptra in Mexico City compared with the US?', array(
					'Sculptra costs $14,999 MXN per vial at Eternal Glow, about '.$usd(14999).'. The packages lower the price per vial: 2 vials are $28,000 MXN, about '.$usd(28000).', and 3 vials are $33,000 MXN, about '.$usd(33000).', or roughly '.$usd(11000).' per vial.',
					'US clinics usually charge $600 to $1,200 USD per vial, according to 2026 price guides, so a 3 vial plan there costs roughly $1,800 to $3,600 USD. The table below puts both side by side.',
					'You pay in Mexican pesos. The dollar figures use about '.EG_USD_RATE.' pesos per dollar (Banco de México, October 2026) and move with the exchange rate.',
				)),
				array('Is Sculptra in Mexico City safe?', array(
					'Sculptra is a medical device that has to be prepared and placed in the right layer of the skin, so who applies it matters as much as the product. At Eternal Glow every treatment is done by Dr. Bruno Ocampo, a licensed physician (professional license 13493041, listed in the Mexican Ministry of Education’s national registry).',
					'The clinic’s advertising is authorized by COFEPRIS, Mexico’s federal health regulator (permit 2509152002A00185).',
					'Wherever you get Sculptra, ask for the injector’s medical license and how many vials go into your plan, and be wary of a price per vial far below the usual range.',
				)),
				array('Planning your visit', array(
					'Every plan starts with a medical consultation. You can <a href="'.esc_url(eg_booking_url()).'">book online</a> or message us on WhatsApp in English. We see patients by appointment at Valladolid 56, Roma Norte, a short walk from Condesa and Juárez, Monday to Friday from 11:30 to 18:00 and Saturday from 11:30 to 17:00.',
					'Sculptra is a plan, not a single visit: most patients need two or three sessions a few weeks apart. If you travel to Mexico City, plan two or three trips or a longer stay, and agree on the dates with the doctor in your first consultation.',
					'Aftercare: massage the area for 5 minutes, 5 times a day, for 5 days, as the doctor shows you. Cold can be applied, protected, during the first 24 hours, and for 24 to 48 hours avoid intense exercise, saunas, steam and heat. Postpone facials, lasers and other facial procedures until the doctor clears them.',
					'The volume you see on the first days fades, and the real result builds up over the following months, so do not plan Sculptra for an event next week. It can be combined with <a href="/en/fillers-mexico-city/">fillers</a> or <a href="/en/botox-mexico-city/">Botox</a> in the same plan.',
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
				// US ranges: $600-$1,200 USD per vial in 2026 US price guides, times the vials.
				array('Mexico City vs the US', 'Eternal Glow price · approximate USD', array(
					eg_price_row('Sculptra · 1 vial', 'Typical US clinic: $600 to $1,200 USD per vial.', '$14,999 MXN ≈ '.$usd(14999)),
					eg_price_row('Sculptra · 2 vials', 'Typical US clinic: $1,200 to $2,400 USD for 2 vials.', '$28,000 MXN ≈ '.$usd(28000)),
					eg_price_row('Sculptra · 3 vials', 'Typical US clinic: $1,800 to $3,600 USD for 3 vials.', '$33,000 MXN ≈ '.$usd(33000)),
				)),
			),
			// Same facts as the risks section of /sculptra-cdmx/.
			'risks' => array('Sculptra risks and contraindications', array(
				'Like any injectable, Sculptra can have side effects. The most common are temporary and appear around the injection points: swelling, redness, tenderness, mild pain, bruising or itching. Small irregularities or nodules under the skin can also appear; the technique, the preparation of the product, the depth of placement and the follow up are what keep that risk low.',
				'<strong>It may not be right for you if:</strong> you are hypersensitive to its components; you have a history of severe allergies; you tend to form keloids or hypertrophic scars; there is an active infection or inflammation in the area.',
				'Tell the doctor about your medicines, illnesses, recent procedures, pregnancy or breastfeeding, and any allergy to anaesthetics such as lidocaine. Contact the clinic if you notice persistent nodules, changes of colour or any unexpected symptom.',
			)),
			'faq_title' => 'Sculptra questions',
			'faq' => array(
				array('How much does Sculptra cost in Mexico City?', 'At Eternal Glow Sculptra costs $14,999 MXN per vial (about '.$usd(14999).'); 2 vials cost $28,000 and 3 vials $33,000 MXN. For the body, 5 vials cost $52,999 and 10 vials $99,999 MXN. The number of vials and sessions is set in your consultation.'),
				array('Is Sculptra hyaluronic acid?', 'No. Sculptra is poly-L-lactic acid. Hyaluronic acid adds volume right away, while Sculptra stimulates new collagen gradually. They can be combined in the same plan.'),
				array('When will I see the results?', 'Some patients notice changes during the first month, and the improvement keeps developing over the following months as collagen increases.'),
				array('Why does the initial volume go away?', 'The volume of the first days comes mostly from the liquid used to prepare the product and from swelling. It fades, and then the real biostimulation starts.'),
				array('How many sessions do I need?', 'Many patients need two or three sessions a few weeks apart. The exact number depends on your skin, the loss of support and how you respond.'),
				array('How long do the results last?', 'In some patients the effect lasts up to about two years. It depends on the protocol, metabolism, age, habits and skin quality.'),
				array('Does it hurt?', 'You may feel pressure, small pricks or tenderness. The product can be prepared with a local anaesthetic to make you more comfortable.'),
				array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up. You can also book online or message us on WhatsApp in English.'),
			),
		),
		// English version of /rellenos-cdmx/. Same facts and prices as the Spanish hub.
		'fillers-mexico-city' => array(
			'updated' => '2026-10-21',
			'image' => array('2026/05/tratamiento-de-pomulos-con-acido-hialuronico.png', 'Hyaluronic acid applied to the cheeks at Eternal Glow, Mexico City'),
			'hero' => array(
				'Hyaluronic acid · Roma Norte, Mexico City',
				'Dermal fillers in Mexico City',
				'Volume, definition and balance with hyaluronic acid, applied by an aesthetic physician. Each area costs $6,499 MXN per syringe, with English spoken at the clinic.',
			),
			'sections' => array(
				array('What is a hyaluronic acid filler?', array(
					'Hyaluronic acid is a substance your body makes naturally that holds water in the skin. As a filler it is a gel injected to restore volume, define contours or soften folds.',
					'The result shows from the moment of the treatment and the body absorbs the product over time. At Eternal Glow we work with reference brands such as Restylane and Yvoire.',
				)),
				array('How does the visit work?', array(
					'Everything starts with a medical consultation: the doctor looks at your face, listens to what you want and suggests the areas and the amount of product.',
					'Most treatments take less than an hour and you can go back to your plans the same day.',
				)),
			),
			'prices' => array(
				array('Price per area', 'Per 1 mL syringe · MXN', $english(array(
					'relleno-de-labios' => array('Lip filler', 'Volume, definition and a natural shape.', '/en/lip-filler-mexico-city/'),
					'relleno-de-ojeras-con-acido-hialuronico' => array('Under-eye filler', 'Corrects the tear trough and the shadow under the eyes with a cannula.'),
					'relleno-de-pomulos-con-acido-hialuronico' => array('Cheek filler', 'Restores projection and support to the mid face.'),
					'relleno-de-menton' => array('Chin filler', 'Definition and balance of the profile.'),
					'relleno-de-surcos-nasogenianos-en-cdmx' => array('Nasolabial fold filler', 'Softens the lines from nose to mouth while keeping a natural look.'),
					'rinomodelacion-en-cdmx' => array('Non-surgical rhinoplasty', 'Corrects a nasal hump and lifts the tip without surgery.'),
					'relleno-de-fosa-temporal-cdmx' => array('Temple filler', 'Fills hollow temples and refreshes the facial contour.'),
					'marcaje-mandibular-con-acido-hialuronico-cdmx' => array('Jawline filler', 'Defines the angle and line of the jaw for a sculpted profile.'),
					'armonizacion-facial-con-acido-hialuronico' => array('Facial harmonization', 'A treatment tailored to your goals.'),
				))),
				array('Packages by amount', 'For a global, balanced result', $syringes_en),
			),
			'risks' => array('Filler risks and contraindications', array(
				'When a physician applies it, side effects are usually mild and temporary, but it is not for everyone.',
				'<strong>It is not recommended if:</strong> you are pregnant or breastfeeding; there is an infection, active cold sores or inflammation in the area; you have an active autoimmune disease; you have had an allergic reaction to hyaluronic acid or lidocaine.',
				'<strong>Possible effects:</strong> swelling, redness, tenderness or a small bruise for a few days. Less often small lumps appear, which the doctor corrects. The most serious complication, although rare, is the product blocking a blood vessel; that is why it is applied by a physician who knows facial anatomy and keeps hyaluronidase at hand.',
				'Tell the doctor at your consultation if you take anticoagulants or aspirin, or if you often get cold sores.',
			)),
			'faq_title' => 'Filler questions',
			'faq' => array(
				array('How much does a hyaluronic acid filler cost in Mexico City?', 'At Eternal Glow each area costs $6,499 MXN per 1 mL syringe. As a package, 2 syringes cost $11,000, 3 syringes $16,000 and 4 syringes $20,000 MXN. The number of syringes is set in your consultation.'),
				array('How long does a filler last?', 'It depends on the area, the product and your metabolism. In general it lasts 6 to 18 months: less in areas that move a lot, such as the lips, and more in the cheeks or jaw.'),
				array('Which areas can be treated?', 'Lips, under eyes, cheeks, chin, nasolabial folds, nose (non-surgical rhinoplasty), temples and jawline. When several areas are treated together we call it facial harmonization.'),
				array('Can a filler be reversed?', 'Yes. Hyaluronic acid can be dissolved with an enzyme called hyaluronidase when the doctor considers it necessary.'),
				array('What is the difference between fillers and Botox?', 'A filler restores volume and defines contours. <a href="/en/botox-mexico-city/">Botox</a> relaxes the muscles that cause expression lines. They can be combined in the same plan.'),
				array('Is there any downtime?', 'You can go back to your plans the same day. Some swelling or a small bruise for a few days is normal.'),
				array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up. You can also book online or message us on WhatsApp in English.'),
			),
		),
		// English version of /relleno-de-labios-cdmx/. Same facts and prices as the Spanish page.
		'lip-filler-mexico-city' => array(
			'updated' => '2026-10-22',
			'image' => array('2026/05/rejuvenecimiento-labial-cdmx-resultados.jpg', 'Illustrative before and after of lips treated with hyaluronic acid filler'),
			'hero' => array(
				'Hyaluronic acid · Roma Norte, Mexico City',
				'Lip filler in Mexico City',
				'Volume, definition and a natural shape with hyaluronic acid. $6,499 MXN per 1 mL syringe, applied by an aesthetic physician in Roma Norte, with English spoken at the clinic.',
			),
			'sections' => array(
				array('What is lip filler?', array(
					'It is hyaluronic acid applied to the lips to add volume, define their outline or correct asymmetries. The product is a gel that the body absorbs over time.',
					'We work in stages and with measured amounts: the goal is lips that look in proportion with your face.',
				)),
				array('Who is it for?', array(
					'For anyone who wants more volume, a sharper outline, to correct an asymmetry or to restore the volume lips lose with age.',
					'If you only want to show a little more of the upper lip without adding volume, the alternative is the Botox lip flip (see our <a href="/en/botox-mexico-city/">Botox page</a>).',
				)),
				array('How does the visit work?', array(
					'It starts with a medical consultation: the doctor looks at the shape of your lips, your profile and what you want to achieve, and suggests the amount of product.',
					'The treatment takes less than an hour and you can go back to your plans the same day.',
				)),
			),
			'prices' => array(
				array('Price', 'Hyaluronic acid · MXN', array($lips_en)),
				array('Related treatments', 'For lips and profile', $english(array(
					'botox-lip-flip' => array('Botox lip flip', 'A subtle turn of the upper lip to show more of it without filler.'),
					'relleno-de-menton' => array('Chin filler', 'Definition and balance of the profile.'),
					'armonizacion-facial-con-acido-hialuronico' => array('Facial harmonization', 'A treatment tailored to your goals.'),
				))),
			),
			'risks' => array('Lip filler risks and contraindications', array(
				'When a physician applies it, side effects are usually mild and temporary, but it is not for everyone.',
				'<strong>It is not recommended if:</strong> you are pregnant or breastfeeding; you have active cold sores, an infection or inflammation on the lips; you have an active autoimmune disease; you have had an allergic reaction to hyaluronic acid or lidocaine.',
				'<strong>Possible effects:</strong> lips swell more than other areas in the first days, and there may be tenderness or a small bruise. Less often small lumps appear, which the doctor corrects. The most serious complication, although rare, is the product blocking a blood vessel; that is why it is applied by a physician who knows facial anatomy and keeps hyaluronidase at hand.',
				'Tell the doctor at your consultation if you take anticoagulants or aspirin, or if you often get cold sores.',
			)),
			'faq_title' => 'Lip filler questions',
			'faq' => array(
				array('How much does lip filler cost in Mexico City?', 'At Eternal Glow lip filler costs $6,499 MXN per 1 mL syringe of hyaluronic acid. The amount you need is set in your consultation.'),
				array('How long does lip filler last?', 'It usually lasts 6 to 12 months. Lips move a lot, so the product is absorbed sooner than in other areas; the product and your metabolism also play a part.'),
				array('Will my lips look natural?', 'Yes. We apply measured amounts and work in stages to respect the shape of your lips and the proportions of your face.'),
				array('How long does the swelling last?', 'It is normal for the lips to swell during the first days and for a small bruise to appear. The result is easier to judge once the swelling goes down.'),
				array('What is the difference between lip filler and a lip flip?', 'Filler adds volume with hyaluronic acid. The lip flip uses Botox to relax the muscle and show more of the upper lip, without volume. For the vertical lines above the lip, Botox is applied per unit.'),
				array('Can it be reversed?', 'Yes. Hyaluronic acid can be dissolved with hyaluronidase when the doctor considers it necessary.'),
				array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up. You can also book online or message us on WhatsApp in English.'),
			),
		),
		// English version of /hilos-tensores-cdmx/. Same facts and prices as the Spanish hub (its WPBakery copy and FAQ).
		'pdo-threads-mexico-city' => array(
			'updated' => '2026-10-22',
			'image' => array('2025/09/hilos-tensores-cdmx-resultados-reales.jpg', 'Before and after PDO threads at Eternal Glow, Mexico City'),
			'hero' => array(
				'Lifting without surgery · Roma Norte, Mexico City',
				'PDO threads in Mexico City',
				'Lift, redefine and rejuvenate without surgery. PDO threads from $1,100 MXN per thread, placed by an aesthetic physician in Roma Norte, with English spoken at the clinic.',
			),
			'sections' => array(
				array('What are PDO threads?', array(
					'PDO (polydioxanone) threads are a minimally invasive technique that repositions and supports facial tissue. They are placed under the skin with micro cannulas to tighten, lift and stimulate collagen in specific areas, with no cuts.',
					'The lifting effect shows from the treatment and keeps improving as collagen grows: firmness, elasticity and texture get better over the following days, and results last 1 to 1.5 years.',
				)),
				array('How does the treatment work?', array(
					'It starts with a medical consultation: the doctor assesses the degree of sagging and the areas that need lifting, and designs a plan for your facial structure and the result you want.',
					'Under local anaesthetic the threads are placed with a cannula or a fine needle along the lifting vectors. The session takes 30 to 60 minutes and you go home the same day.',
					'Afterwards there may be mild swelling or a temporary feeling of tightness. Avoid sudden movements, intense exercise and facial massages for the first 5 days.',
				)),
			),
			'prices' => array(
				array('PDO thread prices', 'Price per thread · MXN', array(
					eg_price_row('1 to 4 threads', 'Price for each thread', '$1,500 / thread'),
					eg_price_row('5 to 8 threads', 'Price for each thread', '$1,300 / thread'),
					eg_price_row('9 or more threads', 'Price for each thread', '$1,100 / thread'),
				)),
			),
			'risks' => array('PDO thread risks and contraindications', array(
				'When a physician places them, side effects are usually mild and temporary, but they are not for everyone.',
				'<strong>They are not recommended if:</strong> you are pregnant or breastfeeding; there is an infection or inflammation in the area; you have an active autoimmune disease or a bleeding disorder; you tend to form keloid scars. If the sagging is very marked, the doctor will tell you whether threads are the right option.',
				'<strong>Possible effects:</strong> swelling, bruising, tenderness or a feeling of tightness in the first days. Less often small dimples or irregularities show on the skin, or the end of a thread can be felt; the doctor checks and corrects them. Infection is rare, which is why the aftercare of the first 5 days matters.',
				'Tell the doctor at your consultation if you take anticoagulants or aspirin.',
			)),
			'faq_title' => 'PDO thread questions',
			'faq' => array(
				array('How much do PDO threads cost in Mexico City?', 'At Eternal Glow the price is per thread: $1,500 MXN each from 1 to 4 threads, $1,300 from 5 to 8 threads and $1,100 from 9 threads.'),
				array('How many threads do I need?', 'It depends on the area and the degree of sagging. The doctor sets the number of threads in your consultation, after examining your face.'),
				array('Which areas can be treated?', 'The most common areas are the brows (brow lift), foxy eyes, cheeks, nasolabial folds, marionette lines, jawline, facial contour and neck.'),
				array('How long do the results last?', 'The lifting effect shows from the treatment and results last 1 to 1.5 years, while the threads keep stimulating collagen.'),
				array('Is it surgery?', 'No. It is an outpatient, minimally invasive procedure: PDO threads are placed with micro cannulas, without cuts.'),
				array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up. You can also book online or message us on WhatsApp in English.'),
			),
		),
		'hilos-tensores-cdmx' => array(
			'updated' => '2026-10-22',
			'prices' => array(
				array('Precio de hilos tensores', 'Precio por hilo · MXN', array(
					eg_price_row('1 a 4 hilos', 'Precio por cada hilo', '$1,500 / hilo'),
					eg_price_row('5 a 8 hilos', 'Precio por cada hilo', '$1,300 / hilo'),
					eg_price_row('9 o más hilos', 'Precio por cada hilo', '$1,100 / hilo'),
				)),
			),
			'risks' => array('Riesgos y contraindicaciones de los hilos tensores', array(
				'Bien colocados por un médico, los efectos secundarios suelen ser leves y temporales, pero no son para todos.',
				'<strong>No se recomiendan si:</strong> estás embarazada o en lactancia; hay infección o inflamación en la zona; tienes una enfermedad autoinmune activa o un trastorno de la coagulación; tienes tendencia a cicatrices queloides. Si la flacidez es muy marcada, el médico te dirá si los hilos son la mejor opción.',
				'<strong>Efectos posibles:</strong> inflamación, moretones, sensibilidad o una sensación de tirantez los primeros días. Con poca frecuencia se notan pequeños hundimientos o irregularidades en la piel, o se siente la punta de un hilo; el médico los revisa y corrige. La infección es rara, por eso importan los cuidados de los primeros 5 días.',
				'Avisa en tu valoración si tomas anticoagulantes o aspirina.',
			)),
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
			'updated' => '2026-10-21',
			'image' => array('2026/05/tratamiento-de-pomulos-con-acido-hialuronico.png', 'Aplicación de ácido hialurónico en pómulos en Eternal Glow, CDMX'),
			'hero' => array(
				'Ácido hialurónico · Roma Norte, CDMX',
				'Rellenos faciales con ácido hialurónico en CDMX',
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
			// Approved by the user on 2026-10-21.
			'risks' => array('Riesgos y contraindicaciones de los rellenos', array(
				'Bien aplicado por un médico, los efectos secundarios suelen ser leves y temporales, pero no es para todos.',
				'<strong>No se recomienda si:</strong> estás embarazada o en lactancia; hay infección, herpes activo o inflamación en la zona; tienes una enfermedad autoinmune activa; tuviste una reacción alérgica al ácido hialurónico o a la lidocaína.',
				'<strong>Efectos posibles:</strong> inflamación, enrojecimiento, sensibilidad o un moretón pequeño durante unos días. Con poca frecuencia aparecen pequeños bultos que el médico corrige. La complicación más seria, aunque rara, es que el producto obstruya un vaso sanguíneo; por eso la aplicación la hace un médico que conoce la anatomía facial y tiene hialuronidasa a la mano.',
				'Avisa en tu valoración si tomas anticoagulantes o aspirina, o si sueles tener herpes labial.',
			)),
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
			'updated' => '2026-10-22',
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
			'risks' => array('Riesgos y contraindicaciones del relleno de labios', array(
				'Bien aplicado por un médico, los efectos secundarios suelen ser leves y temporales, pero no es para todos.',
				'<strong>No se recomienda si:</strong> estás embarazada o en lactancia; tienes herpes labial activo, una infección o inflamación en los labios; tienes una enfermedad autoinmune activa; tuviste una reacción alérgica al ácido hialurónico o a la lidocaína.',
				'<strong>Efectos posibles:</strong> los labios se inflaman más que otras zonas los primeros días, y puede haber sensibilidad o un moretón pequeño. Con poca frecuencia aparecen pequeños bultos que el médico corrige. La complicación más seria, aunque rara, es que el producto obstruya un vaso sanguíneo; por eso la aplicación la hace un médico que conoce la anatomía facial y tiene hialuronidasa a la mano.',
				'Avisa en tu valoración si tomas anticoagulantes o aspirina, o si sueles tener herpes labial.',
			)),
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
