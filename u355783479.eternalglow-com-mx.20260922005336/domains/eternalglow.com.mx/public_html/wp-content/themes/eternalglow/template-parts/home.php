<?php
	// The whole home, shared by front-page.php (Spanish) and page-en.php (English).
	// Copy lives in includes/_home-strings.php and the catalogue in includes/_treatments.php.
	$t = eg_home_strings();
	$en = eg_lang() == 'en';
	$reviews = eg_google_reviews(6, eg_lang());
	$stars = '<span class="lp-stars" aria-hidden="true">'.str_repeat(eg_icon('star'), 5).'</span>';
	$treatments = eg_treatments();
	$cats = eg_treatment_categories();
	$counts = array_count_values(wp_list_pluck($treatments, 'cat'));
	$allowed = array('em' => array());
?>

<section class="lp-hero" aria-labelledby="hero-title">
	<div class="lp-wrap">
		<div class="lp-hero-text">
			<h1 id="hero-title">
				<span class="lp-eyebrow lp-eyebrow-line"><?php _p(esc_html($t['hero']['eyebrow'])); ?></span>
				<?php _p(wp_kses($t['hero']['title'], $allowed)); ?>
			</h1>
			<p class="lp-lead"><?php _p(esc_html($t['hero']['text'])); ?></p>
			<div>
				<div class="lp-actions">
					<?php _p(eg_cta_book('hero')); ?>
					<?php _p(eg_cta_whatsapp('hero')); ?>
				</div>
				<p class="lp-fine"><?php _p(esc_html($t['hero']['note'])); ?></p>
			</div>
			<ul class="lp-trust">
				<?php if($reviews){ ?>
					<li><a href="<?php _p(esc_url($reviews->url)); ?>" target="_blank" rel="noopener"><?php _p($stars); ?> <?php _p(esc_html(sprintf($t['hero']['rating'], $reviews->score, $reviews->count))); ?></a></li>
				<?php } ?>
				<?php foreach($t['hero']['trust'] as $item){ ?>
					<li><?php _p(eg_icon($item[0])); ?> <?php _p(esc_html($item[1])); ?></li>
				<?php } ?>
			</ul>
		</div>
		<?php // sizes: the 16:9 photo covers a 4:5 box, so it is drawn about 2.2 times wider than the box. Asking for the box width gives a blurry file. ?>
		<div class="lp-hero-media">
			<?php _p(eg_img('2026/06/piel-despues-tratamiento-sculptra-cdmx.png', 'full', array('fetchpriority' => 'high', 'loading' => 'eager', 'decoding' => 'async', 'sizes' => '(min-width: 992px) 1250px, 225vw'))); ?>
			<p class="lp-chip"><span><?php _p(esc_html($t['hero']['chip'][0])); ?></span><?php _p(esc_html($t['hero']['chip'][1])); ?></p>
		</div>
	</div>
</section>

<?php // The same list twice, so the strip loops without a gap. Decorative: the treatments are listed again below. ?>
<div class="lp-marquee" aria-hidden="true">
	<div>
		<?php for($i = 0; $i < 2; $i++){ ?>
			<ul>
				<?php foreach($t['marquee'] as $name){ ?>
					<li><?php _p(esc_html($name)); ?></li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
</div>

<section class="lp-section" id="tratamientos" aria-labelledby="cats-title">
	<div class="lp-wrap">
		<div class="lp-head">
			<h2 id="cats-title"><?php _p(esc_html($t['cats']['title'])); ?></h2>
			<a class="lp-link" href="<?php _p(esc_url(home_url('/tratamientos/'))); ?>"><?php _p(esc_html(sprintf($t['cats']['all'], count($treatments)))); ?> <?php _p(eg_icon('arrow-right')); ?></a>
		</div>
		<ul class="lp-cats">
			<?php foreach($cats as $key => $cat){ ?>
				<?php $name = isset($t['cats']['names'][$key]) ? $t['cats']['names'][$key] : $cat; ?>
				<li>
					<a href="<?php _p(esc_url(home_url('/tratamientos/#'.$key))); ?>">
						<span class="lp-cat-top"><span class="lp-eyebrow"><?php _p(esc_html(sprintf($t['cats']['count'], $counts[$key]))); ?></span><?php _p(eg_icon('arrow-up-right')); ?></span>
						<span>
							<span class="lp-cat-name"><?php _p(esc_html($name[0])); ?></span>
							<span class="lp-cat-sub"><?php _p(esc_html($name[1])); ?></span>
						</span>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
</section>

<section class="lp-brands" aria-label="<?php _p(esc_attr($t['brands']['title'])); ?>">
	<div class="lp-wrap">
		<p><?php _p(esc_html($t['brands']['title'])); ?></p>
		<ul>
			<?php foreach($t['brands']['items'] as $brand){ ?>
				<li><img src="<?php images_url("brands/{$brand[0]}.png"); ?>" alt="<?php _p(esc_attr($brand[1])); ?>" height="<?php _p($brand[2]); ?>" style="height: <?php _p($brand[2]); ?>px" loading="lazy" /></li>
			<?php } ?>
		</ul>
	</div>
</section>

<section class="lp-section lp-band" aria-labelledby="popular-title">
	<div class="lp-wrap">
		<div class="lp-head">
			<div>
				<p class="lp-eyebrow"><?php _p(esc_html($t['popular']['eyebrow'])); ?></p>
				<h2 id="popular-title"><?php _p(esc_html($t['popular']['title'])); ?></h2>
			</div>
		</div>
		<ul class="lp-popular">
			<?php foreach($t['popular']['items'] as $slug){ ?>
				<?php
					$item = $treatments[$slug];
					$copy = isset($t['popular']['copy'][$slug]) ? $t['popular']['copy'][$slug] : array($item->name, $item->desc);
					$cat = isset($t['cats']['names'][$item->cat]) ? $t['cats']['names'][$item->cat] : $cats[$item->cat];
				?>
				<li>
					<a href="<?php _p(esc_url($item->url)); ?>"<?php _p(eg_sheet_attrs((object)array('name' => $copy[0], 'desc' => $copy[1], 'price' => $item->price), $cat[0])); ?>>
						<span class="lp-card-top">
							<span><?php _p(esc_html($cat[0])); ?></span>
						</span>
						<span>
							<h3><?php _p(esc_html($copy[0])); ?></h3>
							<span class="lp-card-text"><?php _p(esc_html($copy[1])); ?></span>
						</span>
						<span class="lp-card-foot">
							<?php if(eg_show_prices()){ ?><strong><?php _p(esc_html($item->price)); ?></strong><?php } ?>
							<span class="lp-link"><?php _p(esc_html($t['popular']['more'])); ?> <?php _p(eg_icon('arrow-right')); ?></span>
						</span>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
</section>

<section class="lp-section" id="metodologia" aria-labelledby="steps-title">
	<div class="lp-wrap">
		<h2 id="steps-title" class="lp-words lp-steps-title"><?php _p(esc_html($t['steps']['title'])); ?></h2>
		<ol class="lp-steps">
			<?php foreach($t['steps']['items'] as $step){ ?>
				<li>
					<h3><?php _p(esc_html($step[0])); ?></h3>
					<p><?php _p(esc_html($step[1])); ?></p>
				</li>
			<?php } ?>
		</ol>
	</div>
</section>

<?php if($reviews && $reviews->items){ ?>
	<section class="lp-section lp-band" id="resenas" aria-labelledby="reviews-title">
		<div class="lp-wrap">
			<div class="lp-head">
				<div>
					<p class="lp-eyebrow"><?php _p(esc_html($t['reviews']['eyebrow'])); ?></p>
					<h2 id="reviews-title"><?php _p(esc_html($t['reviews']['title'])); ?></h2>
				</div>
				<a class="lp-score" href="<?php _p(esc_url($reviews->url)); ?>" target="_blank" rel="noopener" aria-label="<?php _p(esc_attr($t['reviews']['link'])); ?>">
					<strong><?php _p($reviews->score); ?></strong>
					<span><?php _p($stars); ?><span><?php _p(esc_html(sprintf($t['reviews']['meta'], $reviews->count))); ?></span></span>
				</a>
			</div>
			<ul class="lp-reviews" tabindex="0">
				<?php foreach($reviews->items as $review){ ?>
					<li>
						<figure>
							<?php _p($stars); ?>
							<blockquote>“<?php _p(esc_html($review->text)); ?>”</blockquote>
							<?php // The initial is drawn from data-initial by CSS so the text content holds the name only once. ?>
							<figcaption><span class="lp-avatar" data-initial="<?php _p(esc_attr(mb_strtoupper(mb_substr($review->user, 0, 1)))); ?>" aria-hidden="true"></span><?php _p(esc_html($review->user)); ?></figcaption>
						</figure>
					</li>
				<?php } ?>
			</ul>
		</div>
	</section>
<?php } ?>

<?php get_template_part('template-parts/doctor'); ?>

<section class="lp-section lp-dark" aria-labelledby="hilos-title">
	<div class="lp-wrap lp-hilos">
		<div>
			<p class="lp-eyebrow"><?php _p(esc_html($t['hilos']['eyebrow'])); ?></p>
			<h2 id="hilos-title"><?php _p(esc_html($t['hilos']['title'])); ?></h2>
			<p><?php _p(esc_html($t['hilos']['text'])); ?></p>
			<?php if(eg_show_prices()){ ?>
				<dl>
					<?php foreach($t['hilos']['stats'] as $stat){ ?>
						<div><dd><?php _p(esc_html($stat[0])); ?></dd><dt><?php _p(esc_html($stat[1])); ?></dt></div>
					<?php } ?>
				</dl>
			<?php } ?>
			<a class="btn lp-btn-light" href="<?php _p(esc_url(home_url('/hilos-tensores-cdmx/'))); ?>"><?php _p(esc_html($t['hilos']['link'])); ?> <?php _p(eg_icon('arrow-right')); ?></a>
		</div>
		<div class="lp-hilos-media">
			<div class="lp-lift" aria-hidden="true"><?php for($i = 0; $i < 3; $i++){ ?><span><?php _p(eg_icon('caret-double-up')); ?></span><?php } ?></div>
			<?php _p(eg_img('2025/09/hilos-tensores-para-la-cara-cerca-de-mi.jpg', 'medium_large', array('sizes' => '(min-width: 992px) 600px, 100vw', 'alt' => $t['hilos']['alt']))); ?>
		</div>
	</div>
</section>

<section class="lp-section" id="resultados" aria-labelledby="results-title">
	<div class="lp-wrap">
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
						<?php _p(eg_img($item[1], 'medium_large', array('sizes' => '(min-width: 992px) 380px, 80vw', 'alt' => $item[$en ? 3 : 2]))); ?>
						<figcaption><?php _p(esc_html($t['results']['tabs'][$item[0]])); ?></figcaption>
					</figure>
				</li>
			<?php } ?>
		</ul>
		<p class="lp-fine"><?php _p(esc_html($t['results']['note'])); ?></p>
	</div>
</section>

<section class="lp-section lp-band" id="faq" aria-labelledby="faq-title">
	<div class="lp-wrap lp-faq">
		<h2 id="faq-title"><?php _p(esc_html($t['faq_title'])); ?></h2>
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

<section class="lp-section lp-final" aria-labelledby="final-title">
	<div class="lp-wrap">
		<h2 id="final-title"><?php _p(wp_kses($t['final']['title'], $allowed)); ?></h2>
		<div>
			<div class="lp-actions">
				<?php _p(eg_cta_book('final')); ?>
				<?php _p(eg_cta_whatsapp('final')); ?>
			</div>
			<p class="lp-fine"><?php _p(esc_html($t['final']['note'])); ?></p>
		</div>
	</div>
</section>
