<?php
// Home copy in both languages, following the client's design prototype. Sample data in the prototype
// (rating, reviews, credentials, phone, hours, free consultation, interest-free months) was replaced
// with what the site can back up; see includes/_treatments.php for prices.
// ponytail: a PHP array is enough for one translated page; move to Polylang when there are more than 3 pages in English.

// Before and after photos: tab key, upload file name (see eg_img()), alt text in Spanish and in English.
// The alt is set here because half of these uploads have none in the media library.
$results = array(
	array('botox', '2025/09/botox-arrugas-frente-hombre-antes-despues.png', 'Antes y después de Botox en las arrugas de la frente de un hombre, Eternal Glow CDMX', 'Before and after Botox for forehead lines on a man, Eternal Glow Mexico City'),
	array('sculptra', '2025/09/clinicas-con-sculptra-cdmx-antes-despues.png', 'Antes y después de la aplicación de Sculptra en Eternal Glow, Roma Norte CDMX', 'Before and after Sculptra at Eternal Glow, Roma Norte, Mexico City'),
	array('hilos', '2025/09/hilos-tensores-rejuvenecimiento-facial-cdmx-1.jpg', 'Antes y después de hilos tensores para rejuvenecimiento facial en CDMX', 'Before and after PDO threads for facial rejuvenation in Mexico City'),
	array('botox', '2025/09/botox-ciudad-de-mexico-antes-despues-mujer-frente.png', 'Antes y después de Botox en la frente de una mujer en Ciudad de México', 'Before and after Botox on a woman’s forehead in Mexico City'),
	array('sculptra', '2025/09/tratamientos-con-sculptra-en-cdmx-antes-despues-hombre.jpg', 'Antes y después de un tratamiento con Sculptra en un hombre, CDMX', 'Before and after a Sculptra treatment on a man, Mexico City'),
	array('hilos', '2025/09/hilos-tensores-en-cdmx-antes-despues-1.jpg', 'Antes y después de hilos tensores en CDMX con mayor definición facial', 'Before and after PDO threads in Mexico City, with more facial definition'),
	array('botox', '2025/09/botox-para-hombres-ciudad-de-mexico-antes-despues.png', 'Antes y después de Botox para hombres en Ciudad de México', 'Before and after Botox for men in Mexico City'),
	array('sculptra', '2025/09/sculptra-en-la-cara-cdmx-antes-despues.png', 'Antes y después de Sculptra en la cara, Eternal Glow CDMX', 'Before and after Sculptra on the face, Eternal Glow Mexico City'),
	array('hilos', '2025/09/hilos-tensores-cdmx-resultados-reales.jpg', 'Resultado real de hilos tensores en Eternal Glow, CDMX', 'Real PDO threads result at Eternal Glow, Mexico City'),
);

// Logo files in assets/images/brands with the height each one needs to look the same size.
$brands = array(
	array('sculptra', 'Sculptra', 40),
	array('radiesse', 'Radiesse', 20),
	array('restylane', 'Restylane', 36),
	array('rejuran', 'Rejuran', 30),
	array('yvoire-d', 'Yvoire', 22),
	array('botox', 'Botox', 34),
	array('xeomin', 'Xeomin', 24),
);

// Service slugs of the six cards under "Los más solicitados".
$popular = array('baby-botox-cdmx', 'full-face-botox-60-unidades', 'relleno-de-ojeras-con-acido-hialuronico', 'marcaje-mandibular-con-acido-hialuronico-cdmx', 'tratamiento-sculptra-en-cdmx', 'hollywood-peel-en-cdmx');

return array(
	'es' => array(
		'nav' => array('/tratamientos/' => 'Tratamientos', '/botox-cdmx/' => 'Botox', '/rellenos-cdmx/' => 'Rellenos', '/sculptra-cdmx/' => 'Sculptra', '/hilos-tensores-cdmx/' => 'Hilos tensores', '/#resenas' => 'Reseñas', '/nosotros/' => 'Nosotros'),
		'cta' => 'Reservar en línea',
		'cta_short' => 'Agendar cita',
		'cta_bar' => 'Reservar',
		'sheet' => array('price' => 'Precio', 'includes' => 'Incluye valoración médica', 'note' => 'Reserva con tu horario preferido o escríbenos y te ayudamos a elegir.', 'more' => 'Conocer el tratamiento', 'close' => 'Cerrar', 'wa' => '¡Hola! Vi su página web y me gustaría agendar: %s.'),
		'whatsapp' => 'Agendar por WhatsApp',
		'hero' => array(
			'eyebrow' => 'Medicina estética · Roma Norte, CDMX',
			'title' => 'Resultados <em>naturales</em>, en manos que te escuchan.',
			'text' => 'Una clínica pequeña a propósito. Cada tratamiento empieza con una valoración médica sin prisa y un plan pensado para tu rostro.',
			'note' => 'Con cita previa · Atención en español e inglés',
			'rating' => '%s · %s reseñas en Google',
			'trust' => array(array('seal-check', 'Permiso COFEPRIS 2509152002A00185'), array('stethoscope', 'Atención del Dr. Bruno Ocampo')),
			// Not tied to AgendaPro availability, so it only claims what is always true.
			'chip' => array('Agenda en línea', 'Las 24 h'),
		),
		'marquee' => array('Baby botox', 'Relleno de ojeras', 'Hilos tensores', 'Sculptra', 'Marcaje mandibular', 'HIFU facial', 'Rejuran', 'Carbon Peel', 'Lip Flip', 'Rinomodelación'),
		'cats' => array(
			'title' => 'Explora por categoría',
			'all' => 'Ver los %d tratamientos',
			'count' => '%d tratamientos',
			'names' => array(),
		),
		'brands' => array('title' => 'Trabajamos con productos de referencia internacional', 'items' => $brands),
		'popular' => array(
			'eyebrow' => 'Los más solicitados',
			'title' => 'Favoritos de nuestras pacientes',
			'more' => 'Ver tratamiento',
			'items' => $popular,
			'copy' => array(),
		),
		'steps' => array(
			'title' => 'Agendar es sencillo. Lo demás, lo cuidamos nosotros.',
			'items' => array(
				array('Elige tu horario', 'Reserva en línea en segundos o escríbenos por WhatsApp si tienes dudas antes de decidir.'),
				array('Valoración médica', 'Analizamos tu rostro, escuchamos lo que buscas y te proponemos un plan honesto.'),
				array('Tratamiento y seguimiento', 'Te explicamos los cuidados de tu tratamiento y te acompañamos después.'),
			),
		),
		'reviews' => array(
			'eyebrow' => 'Reseñas en Google',
			'title' => 'Lo que dicen nuestras pacientes',
			'meta' => '%s reseñas en Google',
			'link' => 'Ver más reseñas',
		),
		'doctor' => array(
			'eyebrow' => 'Sobre el doctor',
			'role' => 'médico estético',
			'quote' => 'Medicina estética con criterio médico.',
			'link' => 'Conoce al Dr. Ocampo',
			'facts' => array(
				array('graduation-cap', 'Médico cirujano, Universidad La Salle'),
				array('certificate', 'Especialidad en medicina estética, nutrición y antienvejecimiento, Universidad de Madrid'),
				array('seal-check', 'Certificado por Merz Aesthetics y en armonización facial por AMIR'),
			),
		),
		'hilos' => array(
			'eyebrow' => 'Lifting sin cirugía',
			'title' => 'Hilos tensores',
			'alt' => 'Antes y después de un tratamiento con hilos tensores en Eternal Glow, CDMX',
			'text' => 'Una técnica mínimamente invasiva que reposiciona y sostiene los tejidos faciales. Trabajamos con hilos PDO para definir el óvalo facial sin pasar por quirófano.',
			'stats' => array(array('$1,500', 'Por hilo · 1 a 4 hilos'), array('$1,300', 'Por hilo · 5 a 8 hilos'), array('$1,100', 'Por hilo · 9 o más')),
			'link' => 'Conocer hilos tensores',
		),
		'results' => array(
			'eyebrow' => 'Resultados reales',
			'title' => 'Sin perder lo que te hace tú.',
			'tabs' => array('all' => 'Todos', 'botox' => 'Botox', 'sculptra' => 'Sculptra', 'hilos' => 'Hilos tensores'),
			'before' => 'Antes',
			'after' => 'Después',
			'note' => 'Resultados individuales pueden variar.',
			'items' => $results,
		),
		'faq_title' => 'Preguntas frecuentes',
		'faq' => array(
			array('¿Dónde está la clínica Eternal Glow?', 'Estamos en Valladolid 56, colonia Roma Norte, alcaldía Cuauhtémoc, 06700 Ciudad de México. Atendemos de lunes a viernes de 11:30 a 18:00 y sábados de 11:30 a 17:00, siempre con cita previa.'),
			array('¿Cómo agendo una valoración?', 'Puedes reservar en línea en menos de un minuto o escribirnos por WhatsApp. Eliges el día y la hora que te acomoden.'),
			array('¿Qué pasa en la valoración?', 'El médico revisa tu rostro, tu historial y lo que quieres lograr. Después te propone un plan con zonas, producto y número de sesiones. Tú decides si lo inicias ese día o más adelante.'),
			array('¿Los resultados se ven naturales?', 'Sí. Trabajamos con dosis medidas y por etapas para conservar tu expresión. El objetivo es que te veas descansada o descansado, no diferente.'),
			array('¿Qué tratamientos ofrecen?', 'Aplicamos <a href="/botox-cdmx/">Botox</a>, <a href="/rellenos-cdmx/">rellenos con ácido hialurónico</a>, <a href="/sculptra-cdmx/">Sculptra</a>, Radiesse, <a href="/hilos-tensores-cdmx/">hilos tensores</a>, HIFU, peelings y microneedling. Puedes ver la lista completa en <a href="/tratamientos/">tratamientos</a>.'),
			array('¿Cuánto tiempo toma y cuándo puedo volver a mis actividades?', 'La mayoría de las aplicaciones toma menos de una hora y puedes retomar tus actividades el mismo día. En la valoración te explicamos los cuidados de tu tratamiento.'),
			array('¿Cuánto cuestan los tratamientos?', 'Los precios publicados son de referencia. El total depende de la zona a tratar y la cantidad de producto; el doctor te lo confirma en tu valoración antes de empezar.'),
			array('¿Atienden en inglés?', 'Sí. Nuestro equipo atiende en español y en inglés, tanto en la valoración como en el seguimiento.'),
		),
		'final' => array(
			'title' => 'Tu valoración es el <em>primer paso</em>.',
			'note' => 'Con cita previa · Valladolid 56, Roma Norte',
		),
		'footer' => array('tagline' => 'Clínica boutique de medicina estética.', 'visit' => 'Visítanos', 'hours' => 'Horario', 'contact' => 'Contacto', 'privacy' => 'Aviso de privacidad'),
	),
	'en' => array(
		'nav' => array('#tratamientos' => 'Treatments', '#doctor' => 'The doctor', '#resultados' => 'Results', '#resenas' => 'Reviews', '#faq' => 'FAQ'),
		'cta' => 'Book online',
		'cta_short' => 'Book a visit',
		'cta_bar' => 'Book',
		'sheet' => array('price' => 'Price', 'includes' => 'Includes a medical consultation', 'note' => 'Book the time you prefer or message us and we will help you choose.', 'more' => 'About this treatment', 'close' => 'Close', 'wa' => 'Hello! I saw your website and would like to book: %s.'),
		'whatsapp' => 'Book on WhatsApp',
		'hero' => array(
			'eyebrow' => 'Aesthetic medicine · Roma Norte, Mexico City',
			'title' => '<em>Natural</em> results, from people who listen.',
			'text' => 'A small clinic on purpose. Every treatment starts with an unhurried medical consultation and a plan made for your face.',
			'note' => 'By appointment · English and Spanish spoken',
			'rating' => '%s · %s Google reviews',
			'trust' => array(array('seal-check', 'COFEPRIS permit 2509152002A00185'), array('stethoscope', 'Care by Dr. Bruno Ocampo')),
			'chip' => array('Book online', '24/7'),
		),
		'marquee' => array('Baby botox', 'Under eye filler', 'PDO threads', 'Sculptra', 'Jawline contouring', 'HIFU', 'Rejuran', 'Carbon Peel', 'Lip Flip', 'Liquid rhinoplasty'),
		'cats' => array(
			'title' => 'Browse by category',
			'all' => 'See all %d treatments',
			'count' => '%d treatments',
			'names' => array(
				'botox' => array('Botox', 'Botulinum toxin'),
				'rellenos' => array('Fillers', 'Hyaluronic acid'),
				'bio' => array('Biostimulators', 'Regenerative injectables'),
				'piel' => array('Skin and devices', 'Technology and peels'),
			),
		),
		'brands' => array('title' => 'We work with internationally recognized products', 'items' => $brands),
		'popular' => array(
			'eyebrow' => 'Most requested',
			'title' => 'Our patients’ favorites',
			'more' => 'See treatment',
			'items' => $popular,
			'copy' => array(
				'baby-botox-cdmx' => array('Baby botox (30 units)', 'Micro doses for forehead, frown lines and eyes. Softens without freezing your expression.'),
				'full-face-botox-60-unidades' => array('Full face botox (60 units)', 'Complete treatment of the upper and lower face for a rested look.'),
				'relleno-de-ojeras-con-acido-hialuronico' => array('Under eye filler', 'Corrects the tear trough and the shadow under the eyes with a cannula.'),
				'marcaje-mandibular-con-acido-hialuronico-cdmx' => array('Jawline contouring', 'Defines the angle and line of the jaw for a sculpted profile.'),
				'tratamiento-sculptra-en-cdmx' => array('Sculptra', 'Poly-L-lactic acid that stimulates collagen and restores firmness gradually.'),
				'hollywood-peel-en-cdmx' => array('Hollywood Carbon Peel', 'Brighter skin, tighter pores and an even tone in one session.'),
			),
		),
		'steps' => array(
			'title' => 'Booking is simple. We take care of the rest.',
			'items' => array(
				array('Pick your time', 'Book online in seconds or message us on WhatsApp if you have questions first.'),
				array('Medical consultation', 'We study your face, listen to what you want and propose an honest plan.'),
				array('Treatment and follow up', 'We explain the aftercare for your treatment and stay with you afterwards.'),
			),
		),
		'reviews' => array(
			'eyebrow' => 'Google reviews',
			'title' => 'What our patients say',
			'meta' => '%s Google reviews',
			'link' => 'Read more reviews',
		),
		'doctor' => array(
			'eyebrow' => 'About the doctor',
			'role' => 'aesthetic physician',
			'quote' => 'Aesthetic medicine with medical judgment.',
			'link' => 'Meet Dr. Ocampo',
			'facts' => array(
				array('graduation-cap', 'Medical degree, Universidad La Salle'),
				array('certificate', 'Specialty in aesthetic medicine, nutrition and antiaging, Universidad de Madrid'),
				array('seal-check', 'Certified by Merz Aesthetics and in facial harmonization by AMIR'),
			),
		),
		'hilos' => array(
			'eyebrow' => 'Lifting without surgery',
			'title' => 'PDO threads',
			'alt' => 'Before and after a PDO threads treatment at Eternal Glow, Mexico City',
			'text' => 'A minimally invasive technique that repositions and supports facial tissue. We work with PDO threads to define the jawline without an operating room.',
			'stats' => array(array('$1,500', 'Per thread · 1 to 4 threads'), array('$1,300', 'Per thread · 5 to 8 threads'), array('$1,100', 'Per thread · 9 or more')),
			'link' => 'About PDO threads',
		),
		'results' => array(
			'eyebrow' => 'Real results',
			'title' => 'Without losing what makes you, you.',
			'tabs' => array('all' => 'All', 'botox' => 'Botox', 'sculptra' => 'Sculptra', 'hilos' => 'PDO threads'),
			'before' => 'Before',
			'after' => 'After',
			'note' => 'Individual results may vary.',
			'items' => $results,
		),
		'faq_title' => 'Frequently asked questions',
		'faq' => array(
			array('Where is the Eternal Glow clinic?', 'We are at Valladolid 56, Roma Norte, Cuauhtémoc, 06700 Mexico City. We are open Monday to Friday from 11:30 am to 6:00 pm and Saturdays from 11:30 am to 5:00 pm, by appointment only.'),
			array('How do I book a consultation?', 'You can book online in under a minute or message us on WhatsApp. You pick the day and time that suit you.'),
			array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up.'),
			array('What happens during the consultation?', 'The doctor reviews your face, your history and what you want to achieve. Then he proposes a plan with areas, product and number of sessions. You decide whether to start that day or later.'),
			array('Will the results look natural?', 'Yes. We work with measured doses and in stages to keep your expression. The goal is for you to look rested, not different.'),
			array('Which treatments do you offer?', 'We offer Botox, hyaluronic acid fillers, Sculptra, Radiesse, PDO threads, HIFU, peels and microneedling.'),
			array('How long does it take and when can I get back to my day?', 'Most treatments take under an hour and you can return to your activities the same day. During the consultation we explain the aftercare for your treatment.'),
			array('How much do treatments cost?', 'Published prices are a reference. The total depends on the area treated and the amount of product; the doctor confirms it during your consultation, before anything is applied.'),
		),
		'final' => array(
			'title' => 'Your consultation is the <em>first step</em>.',
			'note' => 'By appointment · Valladolid 56, Roma Norte',
		),
		'footer' => array('tagline' => 'Boutique aesthetic medicine clinic.', 'visit' => 'Visit us', 'hours' => 'Hours', 'contact' => 'Contact', 'privacy' => 'Privacy notice'),
	),
);
