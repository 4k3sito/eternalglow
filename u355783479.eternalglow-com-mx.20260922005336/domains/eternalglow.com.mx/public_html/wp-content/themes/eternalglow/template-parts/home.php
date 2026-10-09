<?php
	// The whole home, shared by front-page.php (Spanish) and page-en.php (English). Copy lives in includes/_home-strings.php.
	$t = eg_home_strings();
	$whatsapp = get_whatsapp_url();
	$reviews = eg_google_reviews(3, eg_lang());
	$stars = str_repeat(eg_icon('star'), 5);
	// data-cta tells analytics which button on the page was used.
	$cta = function($where) use ($t){
		return '<a class="btn btn-primary lp-btn-arrow" href="'.esc_url(eg_booking_url()).'" target="_blank" rel="noopener" data-cta="'.$where.'">'.esc_html($t['cta']).'</a>';
	};
?>

<section class="lp-hero" aria-labelledby="hero-title">
	<div class="lp-hero-text">
		<h1 id="hero-title">
			<span class="lp-eyebrow"><?php _p(esc_html($t['hero']['eyebrow'])); ?></span>
			<?php _p(esc_html($t['hero']['title'])); ?>
		</h1>
		<p class="lp-lead"><?php _p(esc_html($t['hero']['text'])); ?></p>
		<div class="lp-actions">
			<?php _p($cta('hero')); ?>
		</div>
		<?php if($reviews){ ?>
			<a class="lp-proof" href="<?php _p(esc_url($reviews->url)); ?>" target="_blank" rel="noopener">
				<span class="lp-stars"><?php _p($stars); ?></span>
				<?php _p(esc_html(sprintf($t['hero']['proof'], $reviews->score, $reviews->count))); ?>
			</a>
		<?php } ?>
		<p class="lp-place"><?php _p(esc_html($t['hero']['place'])); ?></p>
	</div>
	<div class="lp-hero-media">
		<?php _p(eg_img('2025/09/clinica-estetica-cdmx-eternal-glow-recepcion.jpg', 'full', array('fetchpriority' => 'high', 'loading' => 'eager', 'decoding' => 'async', 'sizes' => '(min-width: 992px) 55vw, 100vw'))); ?>
	</div>
</section>

<section class="lp-brands" aria-label="<?php _p(esc_attr($t['brands']['title'])); ?>">
	<div class="container">
		<p class="lp-eyebrow"><?php _p(esc_html($t['brands']['title'])); ?></p>
		<ul>
			<?php foreach($t['brands']['items'] as $brand){ ?>
				<li><?php _p(esc_html($brand)); ?></li>
			<?php } ?>
		</ul>
	</div>
</section>

<section class="lp-section" id="tratamientos" aria-labelledby="treatments-title">
	<div class="container">
		<div class="lp-head">
			<div>
				<p class="lp-eyebrow"><?php _p(esc_html($t['treatments']['eyebrow'])); ?></p>
				<h2 id="treatments-title"><?php _p(esc_html($t['treatments']['title'])); ?></h2>
			</div>
			<a class="lp-link" href="<?php _p(esc_url(home_url('/tratamientos/'))); ?>"><?php _p(esc_html($t['treatments']['all'])); ?> <?php _p(eg_icon('arrow-right')); ?></a>
		</div>
		<ul class="lp-treatments">
			<?php foreach($t['treatments']['items'] as $i => $item){ ?>
				<li>
					<a href="<?php _p(esc_url(home_url($t['treatment_urls'][$i]))); ?>" class="image-hover-scale">
						<span class="lp-photo"><?php _p(eg_img($t['treatment_photos'][$i], 'medium_large', array('sizes' => '(min-width: 992px) 20vw, (min-width: 576px) 45vw, 80vw', 'alt' => $item[0]))); ?></span>
						<h3><?php _p(esc_html($item[0])); ?></h3>
						<p><?php _p(esc_html($item[1])); ?></p>
						<span class="lp-link"><?php _p(esc_html($t['more'])); ?> <?php _p(eg_icon('arrow-right')); ?></span>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
</section>

<section class="lp-section lp-band" id="metodologia" aria-labelledby="method-title">
	<div class="container">
		<div class="lp-head">
			<div>
				<p class="lp-eyebrow"><?php _p(esc_html($t['method']['eyebrow'])); ?></p>
				<h2 id="method-title" class="lp-words"><?php _p(esc_html($t['method']['title'])); ?></h2>
			</div>
			<p><?php _p(esc_html($t['method']['text'])); ?></p>
		</div>
		<ol class="lp-steps">
			<?php foreach($t['method']['steps'] as $step){ ?>
				<li>
					<h3><?php _p(esc_html($step[0])); ?></h3>
					<p><?php _p(esc_html($step[1])); ?></p>
				</li>
			<?php } ?>
		</ol>
	</div>
</section>

<section class="lp-doctor" id="doctor" aria-labelledby="doctor-title">
	<div class="lp-doctor-media">
		<?php _p(eg_img('2026/07/tratamiento-pdrn-salmon-en-cdmx.png', 'large', array('sizes' => '(min-width: 992px) 42vw, 100vw', 'alt' => 'Dr. Bruno Ocampo, '.$t['doctor']['role']))); ?>
	</div>
	<div class="lp-doctor-text">
		<p class="lp-eyebrow"><?php _p(esc_html($t['doctor']['eyebrow'])); ?></p>
		<h2 id="doctor-title">Dr. Bruno Ocampo</h2>
		<p class="lp-role"><?php _p(esc_html($t['doctor']['role'])); ?></p>
		<blockquote><?php _p(esc_html($t['doctor']['quote'])); ?></blockquote>
		<p><?php _p(esc_html($t['doctor']['text'])); ?></p>
		<a class="btn btn-outline-primary" href="<?php _p(esc_url(home_url('/nosotros/'))); ?>"><?php _p(esc_html($t['doctor']['link'])); ?></a>
	</div>
	<dl class="lp-facts">
		<?php foreach($t['doctor']['facts'] as $fact){ ?>
			<div>
				<dt><?php _p(esc_html($fact[0])); ?></dt>
				<dd><?php _p(esc_html($fact[1])); ?></dd>
			</div>
		<?php } ?>
	</dl>
</section>

<section class="lp-section" id="resultados" aria-labelledby="results-title">
	<div class="container">
		<div class="lp-head">
			<div>
				<p class="lp-eyebrow"><?php _p(esc_html($t['results']['eyebrow'])); ?></p>
				<h2 id="results-title"><?php _p(esc_html($t['results']['title'])); ?></h2>
			</div>
			<div class="lp-tabs" role="group" aria-label="<?php _p(esc_attr($t['results']['eyebrow'])); ?>">
				<?php foreach($t['results']['tabs'] as $key => $label){ ?>
					<button type="button" data-filter="<?php _p($key); ?>" aria-pressed="<?php _p($key == 'all' ? 'true' : 'false'); ?>"><?php _p(esc_html($label)); ?></button>
				<?php } ?>
			</div>
		</div>
		<ul class="lp-results" tabindex="0">
			<?php foreach($t['results']['items'] as $item){ ?>
				<li data-category="<?php _p($item[0]); ?>">
					<figure data-before="<?php _p(esc_attr($t['results']['before'])); ?>" data-after="<?php _p(esc_attr($t['results']['after'])); ?>">
						<?php _p(eg_img($item[1], 'medium_large', array('sizes' => '(min-width: 992px) 30vw, 80vw'))); ?>
						<figcaption><?php _p(esc_html($t['results']['tabs'][$item[0]])); ?></figcaption>
					</figure>
				</li>
			<?php } ?>
		</ul>
		<p class="lp-note"><?php _p(esc_html($t['results']['note'])); ?></p>
	</div>
</section>

<?php if($reviews && $reviews->items){ ?>
	<section class="lp-section lp-band" id="resenas" aria-labelledby="reviews-title">
		<div class="container">
			<div class="lp-head lp-head-reviews">
				<div>
					<p class="lp-eyebrow"><?php _p(esc_html($t['reviews']['eyebrow'])); ?></p>
					<h2 id="reviews-title"><?php _p(esc_html($t['reviews']['title'])); ?></h2>
				</div>
				<p class="lp-score">
					<strong><?php _p($reviews->score); ?></strong>
					<span class="lp-stars"><?php _p($stars); ?></span>
					<?php _p(esc_html(sprintf($t['reviews']['meta'], $reviews->count))); ?>
				</p>
				<a class="btn btn-outline-primary" href="<?php _p(esc_url($reviews->url)); ?>" target="_blank" rel="noopener"><?php _p(esc_html($t['reviews']['link'])); ?></a>
			</div>
			<ul class="lp-reviews">
				<?php foreach($reviews->items as $review){ ?>
					<li>
						<blockquote><?php _p(esc_html($review->text)); ?></blockquote>
						<p><span class="lp-stars"><?php _p($stars); ?></span> <?php _p(esc_html($review->user)); ?></p>
					</li>
				<?php } ?>
			</ul>
		</div>
	</section>
<?php } ?>

<section class="lp-section" id="faq" aria-labelledby="faq-title">
	<div class="container lp-faq">
		<div>
			<p class="lp-eyebrow"><?php _p(esc_html($t['faq_head']['eyebrow'])); ?></p>
			<h2 id="faq-title"><?php _p(esc_html($t['faq_head']['title'])); ?></h2>
		</div>
		<div class="jss-text">
			<?php foreach($t['faq'] as $item){ ?>
				<details>
					<summary><h3 class="mb-0"><?php _p(esc_html($item[0])); ?></h3></summary>
					<p class="mt-16 mb-0"><?php _p(wp_kses($item[1], array('a' => array('href' => array())))); ?></p>
				</details>
			<?php } ?>
		</div>
	</div>
</section>

<section class="lp-final" aria-labelledby="final-title">
	<div class="lp-final-text">
		<p class="lp-eyebrow"><?php _p(esc_html($t['final']['eyebrow'])); ?></p>
		<h2 id="final-title"><?php _p(esc_html($t['final']['title'])); ?></h2>
		<p><?php _p(esc_html($t['final']['text'])); ?></p>
		<ul>
			<?php foreach($t['final']['list'] as $item){ ?>
				<li><?php _p(esc_html($item)); ?></li>
			<?php } ?>
		</ul>
		<div class="lp-actions">
			<?php _p($cta('final')); ?>
			<?php if($whatsapp){ ?>
				<a class="btn btn-outline-primary" href="<?php _p(esc_url($whatsapp)); ?>" target="_blank" rel="noopener" data-cta="final"><?php _p(eg_icon('whatsapp-logo')); ?> <?php _p(esc_html($t['whatsapp'])); ?></a>
			<?php } ?>
		</div>
	</div>
	<div class="lp-final-media">
		<?php _p(eg_img('2026/06/tratamiento-facial-estimulacion-colageno.png', 'large', array('sizes' => '(min-width: 992px) 50vw, 100vw'))); ?>
	</div>
</section>
