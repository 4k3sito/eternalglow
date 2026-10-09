<?php
// Home copy in both languages. Photos are upload file names (see eg_img()).
// ponytail: a PHP array is enough for one translated page; move to Polylang when there are more than 3 pages in English.

$treatment_photos = array(
	'2026/04/baby-botox-en-cdmx-valoracion-personalizada.png',
	'2026/06/rejuvenecimiento-facial-acido-hialuronico-mujer-cdmx.png',
	'2026/06/aplicacion-sculptra-facial-cdmx.png',
	'2026/06/perfilamiento-facial-acido-hialuronico-natural.png',
	'2026/08/tratamiento-hifu-para-flacidez-facial.png',
);
$treatment_urls = array('/botox-cdmx/', '/tratamientos/#botox-fillers', '/sculptra-cdmx/', '/hilos-tensores-cdmx/', '/tratamientos/#aparatologia-y-tratamientos');

// Before and after photos by tab key.
$results = array(
	array('botox', '2025/09/botox-arrugas-frente-hombre-antes-despues.png'),
	array('sculptra', '2025/09/clinicas-con-sculptra-cdmx-antes-despues.png'),
	array('hilos', '2025/09/hilos-tensores-rejuvenecimiento-facial-cdmx-1.jpg'),
	array('botox', '2025/09/botox-ciudad-de-mexico-antes-despues-mujer-frente.png'),
	array('sculptra', '2025/09/tratamientos-con-sculptra-en-cdmx-antes-despues-hombre.jpg'),
	array('hilos', '2025/09/hilos-tensores-en-cdmx-antes-despues-1.jpg'),
	array('botox', '2025/09/botox-para-hombres-ciudad-de-mexico-antes-despues.png'),
	array('sculptra', '2025/09/sculptra-en-la-cara-cdmx-antes-despues.png'),
	array('hilos', '2025/09/hilos-tensores-cdmx-resultados-reales.jpg'),
);

$brands = array('Sculptra', 'Radiesse', 'Rejuran', 'Merz Aesthetics');

return array(
	'es' => array(
		'nav' => array(),
		'cta' => 'Agenda tu valoración',
		'whatsapp' => 'Hablar por WhatsApp',
		'more' => 'Conoce más',
		'hero' => array(
			'eyebrow' => 'Clínica de medicina estética en Roma Norte, CDMX',
			'title' => 'Medicina estética que se ve como tú.',
			'text' => 'Resultados naturales, criterio médico y un plan hecho para tu rostro. Te atiende el Dr. Bruno Ocampo en Roma Norte.',
			'proof' => '%s de 5 en Google con %s reseñas',
			'place' => 'Clínica boutique · Valladolid 56, Roma Norte',
		),
		'brands' => array('title' => 'Trabajamos con productos de referencia internacional', 'items' => $brands),
		'treatments' => array(
			'eyebrow' => 'Nuestros tratamientos',
			'title' => 'Soluciones médicas para cada etapa de tu piel.',
			'all' => 'Ver todos los tratamientos',
			'items' => array(
				array('Botox en CDMX', 'Suaviza líneas de expresión en frente, entrecejo y patas de gallo sin perder movimiento.'),
				array('Rellenos con ácido hialurónico', 'Labios, pómulos, ojeras y mentón con volumen medido y proporciones naturales.'),
				array('Sculptra en CDMX', 'Bioestimulador que activa tu propio colágeno y devuelve firmeza de forma gradual.'),
				array('Hilos tensores en CDMX', 'Efecto de tensión y definición del óvalo facial sin cirugía.'),
				array('HIFU y calidad de piel', 'Ultrasonido focalizado, peelings y microneedling para textura, tono y firmeza.'),
			),
		),
		'method' => array(
			'eyebrow' => 'Nuestra metodología',
			'title' => 'Una experiencia diseñada alrededor de ti.',
			'text' => 'Cada tratamiento parte de una valoración médica y un plan personalizado, con enfoque en resultados naturales y a largo plazo.',
			'steps' => array(
				array('Valoración médica', 'Revisamos tus objetivos, tu historial y las necesidades de tu piel.'),
				array('Plan personalizado', 'Definimos un protocolo para ti, con zonas, producto y número de sesiones.'),
				array('Tratamiento', 'El médico aplica el tratamiento en clínica y te explica cada paso.'),
				array('Seguimiento', 'Revisamos tu evolución y ajustamos el plan para mantener los resultados.'),
			),
		),
		'doctor' => array(
			'eyebrow' => 'Sobre el doctor',
			'role' => 'Medicina estética y antienvejecimiento',
			'quote' => 'Medicina estética con criterio médico.',
			'text' => 'Mi enfoque es simple: ayudarte a verte y sentirte mejor con tratamientos seguros, personalizados y con resultados naturales.',
			'link' => 'Conoce al Dr. Ocampo',
			'facts' => array(
				array('Médico cirujano', 'Universidad La Salle'),
				array('Especialidad', 'Medicina estética, nutrición y antienvejecimiento, Universidad de Madrid'),
				array('Certificaciones', 'Merz Aesthetics y armonización facial por AMIR'),
				array('Permiso COFEPRIS', '2509152002A00185'),
			),
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
		'reviews' => array(
			'eyebrow' => 'Testimonios',
			'title' => 'Lo que dicen nuestros pacientes.',
			'meta' => 'en Google con %s reseñas',
			'link' => 'Ver más reseñas',
		),
		'faq_head' => array('eyebrow' => 'Preguntas frecuentes', 'title' => 'Antes de tu primera cita.'),
		'faq' => array(
			array('¿Dónde está la clínica Eternal Glow?', 'Estamos en Valladolid 56, colonia Roma Norte, alcaldía Cuauhtémoc, 06700 Ciudad de México. Atendemos de lunes a viernes de 11:30 a 18:00 y sábados de 11:30 a 17:00, siempre con cita previa.'),
			array('¿Cómo agendo una valoración?', 'Puedes agendar en línea en menos de un minuto con el botón Agenda tu valoración, o escribirnos por WhatsApp. Eliges el día y la hora que te acomoden.'),
			array('¿Qué pasa en la valoración?', 'El médico revisa tu rostro, tu historial y lo que quieres lograr. Después te propone un plan con zonas, producto y número de sesiones. Tú decides si lo inicias ese día o más adelante.'),
			array('¿Los resultados se ven naturales?', 'Sí. Trabajamos con dosis medidas y por etapas para conservar tu expresión. El objetivo es que te veas descansada o descansado, no diferente.'),
			array('¿Qué tratamientos ofrecen?', 'Aplicamos <a href="/botox-cdmx/">Botox</a>, rellenos con ácido hialurónico, <a href="/sculptra-cdmx/">Sculptra</a>, Radiesse, <a href="/hilos-tensores-cdmx/">hilos tensores</a>, HIFU, peelings y microneedling. Puedes ver la lista completa en <a href="/tratamientos/">tratamientos</a>.'),
			array('¿Cuánto tiempo toma y cuándo puedo volver a mis actividades?', 'La mayoría de las aplicaciones toma menos de una hora y puedes retomar tus actividades el mismo día. En la valoración te explicamos los cuidados de tu tratamiento.'),
			array('¿Cuánto cuestan los tratamientos?', 'El costo depende del tratamiento, la zona y la cantidad de producto. Lo confirmamos en la valoración, antes de aplicar cualquier cosa.'),
			array('¿Atienden en inglés?', 'Sí. Nuestro equipo atiende en español y en inglés, tanto en la valoración como en el seguimiento.'),
		),
		'final' => array(
			'eyebrow' => 'Tu próximo paso',
			'title' => 'Tu tratamiento comienza con una valoración personalizada.',
			'text' => 'Conoce las opciones adecuadas para ti, resuelve tus dudas y recibe un plan médico hecho a tu medida.',
			'list' => array('Valoración médica', 'Plan personalizado', 'Tratamiento seguro', 'Acompañamiento continuo'),
		),
		'treatment_photos' => $treatment_photos,
		'treatment_urls' => $treatment_urls,
	),
	'en' => array(
		'nav' => array('#tratamientos' => 'Treatments', '#metodologia' => 'Our approach', '#resultados' => 'Results', '#doctor' => 'The doctor', '#contacto' => 'Contact'),
		'cta' => 'Book your consultation',
		'whatsapp' => 'Chat on WhatsApp',
		'more' => 'Learn about it',
		'hero' => array(
			'eyebrow' => 'Aesthetic medicine clinic in Roma Norte, Mexico City',
			'title' => 'Aesthetic medicine that still looks like you.',
			'text' => 'Natural results, medical judgment and a plan built for your face. Dr. Bruno Ocampo sees you in Roma Norte, in English or Spanish.',
			'proof' => '%s out of 5 on Google from %s reviews',
			'place' => 'Boutique clinic · Valladolid 56, Roma Norte',
		),
		'brands' => array('title' => 'We work with internationally recognized products', 'items' => $brands),
		'treatments' => array(
			'eyebrow' => 'Our treatments',
			'title' => 'Medical solutions for every stage of your skin.',
			'all' => 'See all treatments',
			'items' => array(
				array('Botox in Mexico City', 'Softens forehead lines, frown lines and crow’s feet while keeping your expression.'),
				array('Hyaluronic acid fillers', 'Lips, cheeks, under eyes and chin with measured volume and natural proportions.'),
				array('Sculptra in Mexico City', 'A biostimulator that activates your own collagen and restores firmness gradually.'),
				array('PDO threads in Mexico City', 'Lift and definition for the jawline without surgery.'),
				array('HIFU and skin quality', 'Focused ultrasound, peels and microneedling for texture, tone and firmness.'),
			),
		),
		'method' => array(
			'eyebrow' => 'Our approach',
			'title' => 'An experience designed around you.',
			'text' => 'Every treatment starts with a medical consultation and a personal plan, focused on natural results that last.',
			'steps' => array(
				array('Medical consultation', 'We review your goals, your history and what your skin needs.'),
				array('Personal plan', 'We define a protocol for you, with areas, product and number of sessions.'),
				array('Treatment', 'The doctor performs the treatment in the clinic and explains each step.'),
				array('Follow up', 'We review your progress and adjust the plan to maintain your results.'),
			),
		),
		'doctor' => array(
			'eyebrow' => 'About the doctor',
			'role' => 'Aesthetic and antiaging medicine',
			'quote' => 'Aesthetic medicine with medical judgment.',
			'text' => 'My approach is simple: help you look and feel better with safe, personal treatments and natural results.',
			'link' => 'Meet Dr. Ocampo',
			'facts' => array(
				array('Medical degree', 'Universidad La Salle'),
				array('Specialty', 'Aesthetic medicine, nutrition and antiaging, Universidad de Madrid'),
				array('Certifications', 'Merz Aesthetics and facial harmonization by AMIR'),
				array('COFEPRIS permit', '2509152002A00185'),
			),
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
		'reviews' => array(
			'eyebrow' => 'Reviews',
			'title' => 'What our patients say.',
			'meta' => 'on Google from %s reviews',
			'link' => 'Read more reviews',
		),
		'faq_head' => array('eyebrow' => 'Frequently asked questions', 'title' => 'Before your first visit.'),
		'faq' => array(
			array('Where is the Eternal Glow clinic?', 'We are at Valladolid 56, Roma Norte, Cuauhtémoc, 06700 Mexico City. We are open Monday to Friday from 11:30 am to 6:00 pm and Saturdays from 11:30 am to 5:00 pm, by appointment only.'),
			array('How do I book a consultation?', 'You can book online in under a minute with the Book your consultation button, or message us on WhatsApp. You pick the day and time that suit you.'),
			array('Do you speak English?', 'Yes. Our team works in English and Spanish, during the consultation and the follow up.'),
			array('What happens during the consultation?', 'The doctor reviews your face, your history and what you want to achieve. Then he proposes a plan with areas, product and number of sessions. You decide whether to start that day or later.'),
			array('Will the results look natural?', 'Yes. We work with measured doses and in stages to keep your expression. The goal is for you to look rested, not different.'),
			array('Which treatments do you offer?', 'We offer Botox, hyaluronic acid fillers, Sculptra, Radiesse, PDO threads, HIFU, peels and microneedling.'),
			array('How long does it take and when can I get back to my day?', 'Most treatments take under an hour and you can return to your activities the same day. During the consultation we explain the aftercare for your treatment.'),
			array('How much do treatments cost?', 'The cost depends on the treatment, the area and the amount of product. We confirm it during the consultation, before anything is applied.'),
		),
		'final' => array(
			'eyebrow' => 'Your next step',
			'title' => 'Your treatment starts with a personal consultation.',
			'text' => 'Learn which options suit you, get your questions answered and receive a medical plan made for you.',
			'list' => array('Medical consultation', 'Personal plan', 'Safe treatment', 'Ongoing follow up'),
		),
		'treatment_photos' => $treatment_photos,
		'treatment_urls' => $treatment_urls,
	),
);
