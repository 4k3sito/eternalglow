<?php
	// The doctor block: on the home, and on every hub page with the "medical care / updated" line the /servicio/ pages carry.
	// No "reviewed by" claim: nothing records that the doctor reviews the copy.
	$t = eg_home_strings();
	$en = eg_lang() == 'en';
	// In the clinic's timezone. English uses DateTime::format, since wp_date would translate the month to Spanish.
	$modified = (new DateTimeImmutable('@'.eg_page_modified()))->setTimezone(wp_timezone());
?>

<section class="lp-section" id="doctor" aria-labelledby="doctor-title">
	<div class="lp-wrap lp-doctor">
		<div class="lp-doctor-media">
			<img src="<?php images_url('dr-bruno-ocampo.webp'); ?>" alt="Dr. Bruno Ocampo, <?php _p(esc_attr($t['doctor']['role'])); ?>" width="768" height="995" loading="lazy" />
		</div>
		<div>
			<p class="lp-eyebrow"><?php _p(esc_html($t['doctor']['eyebrow'])); ?></p>
			<h2 id="doctor-title">Dr. Bruno Ocampo</h2>
			<blockquote>“<?php _p(esc_html($t['doctor']['quote'])); ?>”</blockquote>
			<ul class="lp-facts">
				<?php foreach($t['doctor']['facts'] as $fact){ ?>
					<li><?php _p(eg_icon($fact[0])); ?> <?php _p(esc_html($fact[1])); ?></li>
				<?php } ?>
			</ul>
			<?php if(!empty($args['byline'])){ ?>
				<p class="lp-doctor-byline">
					<?php _p(esc_html($t['doctor']['care'])); ?>: Dr. Bruno Ocampo, Eternal Glow, Roma Norte.
					<?php _p(esc_html($t['doctor']['updated'])); ?> <time datetime="<?php _p($modified->format('Y-m-d')); ?>"><?php _p(esc_html($en ? $modified->format('F j, Y') : wp_date('j \\d\\e F \\d\\e Y', $modified->getTimestamp()))); ?></time>.
				</p>
			<?php } ?>
			<?php if(!$en){ ?>
				<a class="lp-link" href="<?php _p(esc_url(home_url('/nosotros/'))); ?>"><?php _p(esc_html($t['doctor']['link'])); ?> <?php _p(eg_icon('arrow-right')); ?></a>
			<?php } ?>
		</div>
	</div>
	<?php // The doctor at work and the treatment room, from patients' Google reviews. ?>
	<div class="lp-wrap">
		<ul class="lp-doctor-gallery">
			<?php foreach($t['doctor']['gallery'] as $photo){ ?>
				<li><img src="<?php images_url('resenas/'.$photo[0]); ?>" alt="<?php _p(esc_attr($photo[1])); ?>" width="900" height="1200" loading="lazy" decoding="async" /></li>
			<?php } ?>
		</ul>
	</div>
</section>
