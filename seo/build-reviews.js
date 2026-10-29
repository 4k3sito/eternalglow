// Builds the theme's includes/_reviews.php from the Google reviews export (OpenSEO, 2026-10-28).
// Usage: node seo/build-reviews.js seo/gbp-data/google-reviews-2026-10-28.json <theme>/includes/_reviews.php
const fs = require('fs');
const [src, out] = process.argv.slice(2);
const all = JSON.parse(fs.readFileSync(src, 'utf8')).reviews;

// [name on Google, hub, chip ES, chip EN, photos, featured on the home]
const picks = [
	['mike De', 'botox', 'Botox', 'Botox'],
	['Sara Steinkruger', 'botox', 'Botox', 'Botox'],
	['Tiffany Hsu', 'botox', 'Botox', 'Botox'],
	['Alan Keith', 'botox', 'Baby botox', 'Baby botox'],
	['Lizzy GI', 'botox', 'Botox para bruxismo', 'Botox for teeth grinding'],
	['Brandon Pofer', 'botox', 'Botox', 'Botox'],
	['Melissa Cooper', 'rellenos', 'Relleno de ojeras', 'Under eye filler', ['melissa-c-relleno-ojeras.jpg'], true],
	['Paola G', 'rellenos', 'Rinomodelación', 'Liquid rhinoplasty', ['paola-g-rinomodelacion.jpg'], true],
	['Ma.Fernanda Hernández Ramos', 'rellenos', 'Rinomodelación', 'Liquid rhinoplasty', ['mafernanda-h-rinomodelacion-1.jpg', 'mafernanda-h-rinomodelacion-2.jpg']],
	['Diego Robledo Valdespino', 'rellenos', 'Rinomodelación', 'Liquid rhinoplasty', ['diego-r-rinomodelacion.jpg']],
	['Myriam Awad', 'rellenos', 'Ácido hialurónico', 'Hyaluronic acid filler'],
	['Kimberly Morales', 'rellenos', 'Relleno de mentón', 'Chin filler'],
	['Victoria Skye', 'labios', 'Corrección de relleno en labio', 'Lip filler correction', ['victoria-s-labios-antes.jpg', 'victoria-s-labios-despues.jpg'], true],
	['jursica08', 'labios', 'Relleno de labios', 'Lip filler'],
	['Yevheniia Pieshkova', 'labios', 'Relleno de labios', 'Lip filler'],
	['Dominique López Fuentes', 'labios', 'Relleno de labios', 'Lip filler'],
	['Caren Guadalupe Vazquez ruiz', 'labios', 'Relleno de labios', 'Lip filler'],
	['Horacio Macedo', 'labios', 'Relleno de labios', 'Lip filler'],
	['Jamie Crutchfield', 'hilos', 'Hilos tensores', 'PDO threads'],
	['Chelsea Mae', 'hilos', 'Hilos tensores', 'PDO threads'],
	['Ahlem Alegrado', 'hilos', 'Hilos tensores', 'PDO threads'],
	['Kimberly Morales', 'hilos', 'Hilos tensores', 'PDO threads'],
	['Margarita Mateo Julian', 'hilos', 'Hilos tensores y botox', 'PDO threads and Botox'],
	['Laura Montañez Garduño', 'hilos', 'Hilos tensores', 'PDO threads'],
	['E T', 'sculptra', 'Sculptra', 'Sculptra'],
	['elizabeth hanon', 'sculptra', 'Sculptra', 'Sculptra'],
	['Vane Rot', 'sculptra', 'Sculptra', 'Sculptra'],
	['Fabiola Alanis', 'sculptra', 'Sculptra', 'Sculptra'],
];

// English for the reviews written in Spanish (Google only translates the other way).
const en = {
	'mike De': 'I had gone to another clinic before and felt the Botox didn’t work for me. I booked with Dr. Bruno and everything changed: he noticed I hadn’t been given the right treatment, and the one he did was 100% effective. Highly recommended and very professional; the receptionist is very kind and friendly. The best practice in Roma Norte and Mexico City.',
	'Lizzy GI': 'I’m very happy with the care. At first I went for Botox for my teeth grinding, but the result was so good that I came back, and my compliments: I look so natural and, best of all, it was painless! Dr. Bruno Ocampo is the best I know for aesthetic treatments. I recommend him without a second thought.',
	'Paola G': 'The doctor is very kind and really funny. I had a full facial harmonization with him, including nose, lips, other areas of the face and Botox. I love the results. I look amazing! 100% recommended.',
	'Ma.Fernanda Hernández Ramos': 'Great work! Always very kind and he followed up well. I had a liquid rhinoplasty and he injected until I liked it; I loved it from the front and from the side.',
	'Diego Robledo Valdespino': 'Great place and the best care from Dr. Bruno! I would definitely come back. I had Botox and a liquid rhinoplasty 😁',
	'Dominique López Fuentes': 'I had lip filler with Dr. Bruno and loved it!! Very natural and excellent care. He uses the best, highest quality fillers ♥️',
	'Caren Guadalupe Vazquez ruiz': 'My sister and I went to have hyaluronic acid in our lips and the results were impressive! It didn’t hurt at all and I loved the way Dr. Bruno explains things. Hands down the best clinic!',
	'Horacio Macedo': '100 percent recommended, the best!! He also has amazing hands, left my lips looking so pretty, and he is super kind!!!',
	'Margarita Mateo Julian': 'I went for Botox and PDO threads and it was honestly amazing! The doctor explains the whole procedure, uses quality products, and it was painless! I loved the results! I can’t wait to go back.',
	'Laura Montañez Garduño': 'Very good care and really nice work. I liked the change in my face with the threads, and the place is very pleasant 😊👍',
	'elizabeth hanon': 'Loved it… I finished my 3 doses of Sculptra, SO HAPPY, he has a magic touch. And the Botox is the best!!!!',
	'Vane Rot': 'I had Sculptra there and it’s the best in the world! I’m so happy!!',
	'Fabiola Alanis': 'I loved the service. I had SCULPTRA.',
};

const clean = s => (s || '').replace(/\s+/g, ' ').trim();
const short = n => { const p = n.trim().split(/\s+/); return p[0] + (p[1] ? ' ' + p[1][0].toUpperCase() + '.' : ''); };
const q = s => "'" + s.replace(/\\/g, '\\\\').replace(/'/g, "\\'") + "'";

const rows = picks.map(([name, hub, chipEs, chipEn, photos = [], home = false]) => {
	const r = all.find(x => x.profile_name === name);
	if (!r) throw new Error('not found: ' + name);
	const lang = r.original_language || 'es';
	const original = clean(r.original_review_text) || clean(r.review_text);
	const textEs = lang === 'es' ? original : clean(r.review_text);
	const textEn = lang === 'en' ? original : en[name];
	if (!textEn) throw new Error('missing English: ' + name);
	return `\t\tarray('hub' => ${q(hub)}, 'name' => ${q(short(name))}, 'date' => ${q(r.timestamp.slice(0, 10))}, 'lang' => ${q(lang)}, 'chip' => array(${q(chipEs)}, ${q(chipEn)}), 'es' => ${q(textEs)}, 'en' => ${q(textEn)}, 'photos' => array(${photos.map(q).join(', ')}), 'home' => ${home}),`;
});

fs.writeFileSync(out, `<?php
// Google reviews that name a treatment, picked by hand from the full export (2026-10-28) and shown on the
// matching hub; the ones with 'home' are the before-and-after photos on the home. Generated by
// seo/build-reviews.js: edit the picks there and run it again instead of editing this file.
// 'lang' is the language the patient wrote in; the other one is a translation and is labelled as such.
// Photos are the patients' own (from their review), in assets/images/resenas.
function eg_reviews(){
	return array(
${rows.join('\n')}
	);
}
`);
console.log(rows.length + ' reviews');
