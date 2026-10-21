<?php
	// What includes/_landings.php adds to a hub page: an optional hero and text, price tables, questions and the closing call.
	$landing = $args['landing'];
	$t = eg_home_strings();
	$links = array('a' => array('href' => array()));
?>

<?php if(!empty($landing['hero']) || !empty($landing['prices'])){ ?>
	<section class="lp-section lp-catalog lp-landing">
		<div class="lp-wrap">
			<?php if(!empty($landing['hero'])){ ?>
				<div class="lp-landing-hero">
				<div class="lp-catalog-head">
					<p class="lp-eyebrow"><?php _p(esc_html($landing['hero'][0])); ?></p>
					<h1><?php _p(esc_html($landing['hero'][1])); ?></h1>
					<p><?php _p(esc_html($landing['hero'][2])); ?></p>
					<div class="lp-actions">
						<?php _p(eg_cta_book('landing')); ?>
						<?php _p(eg_cta_whatsapp('landing')); ?>
					</div>
					<?php _p(eg_rating_line()); ?>
				</div>
				<?php if(!empty($landing['image'])){ ?>
					<?php // 16:9 photos cover a 4:3 box, so they are drawn about 1.2 times wider than the box. ?>
					<?php _p(eg_img($landing['image'][0], 'full', array('alt' => $landing['image'][1], 'fetchpriority' => 'high', 'loading' => 'eager', 'sizes' => '(min-width: 992px) 760px, 120vw'))); ?>
				<?php } ?>
				</div>
			<?php } ?>

			<?php if(!empty($landing['sections'])){ ?>
				<?php foreach($landing['sections'] as $section){ ?>
					<div class="lp-prose">
						<h2><?php _p(esc_html($section[0])); ?></h2>
						<?php foreach($section[1] as $paragraph){ ?>
							<p><?php _p(wp_kses($paragraph, $links)); ?></p>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>

			<?php if(eg_show_prices()){ ?>
				<?php foreach($landing['prices'] as $table){ ?>
					<div class="lp-group">
						<div class="lp-group-head">
							<h2><?php _p(esc_html($table[0])); ?></h2>
							<span><?php _p(esc_html($table[1])); ?></span>
						</div>
						<ul>
							<?php foreach($table[2] as $item){ ?>
								<li>
									<a href="<?php _p(esc_url($item->url)); ?>"<?php _p(eg_sheet_attrs($item)); ?>>
										<span>
											<span class="lp-row-name"><?php _p(esc_html($item->name)); ?></span>
											<span class="lp-row-text"><?php _p(esc_html($item->desc)); ?></span>
										</span>
										<strong><?php _p(esc_html($item->price)); ?></strong>
										<?php _p(eg_icon('caret-right')); ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</section>
<?php } ?>

<?php if(!empty($landing['risks'])){ ?>
	<section class="lp-section" id="<?php _p(eg_lang() == 'en' ? 'risks' : 'riesgos'); ?>" aria-labelledby="risks-title">
		<div class="lp-wrap">
			<div class="lp-prose">
				<h2 id="risks-title"><?php _p(esc_html($landing['risks'][0])); ?></h2>
				<?php foreach($landing['risks'][1] as $paragraph){ ?>
					<p><?php _p(wp_kses($paragraph, $links + array('strong' => array()))); ?></p>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } ?>

<?php get_template_part('template-parts/doctor', null, array('byline' => true)); ?>

<section class="lp-section lp-band" id="faq" aria-labelledby="faq-title">
	<div class="lp-wrap lp-faq">
		<h2 id="faq-title"><?php _p(esc_html($landing['faq_title'])); ?></h2>
		<div class="jss-text">
			<?php foreach($landing['faq'] as $item){ ?>
				<details>
					<summary><h3 class="mb-0"><?php _p(esc_html($item[0])); ?></h3></summary>
					<p class="mt-16 mb-0"><?php _p(wp_kses($item[1], $links)); ?></p>
				</details>
			<?php } ?>
		</div>
	</div>
</section>

<section class="lp-section lp-final" aria-labelledby="final-title">
	<div class="lp-wrap">
		<h2 id="final-title"><?php _p(wp_kses($t['final']['title'], array('em' => array()))); ?></h2>
		<div>
			<div class="lp-actions">
				<?php _p(eg_cta_book('final')); ?>
				<?php _p(eg_cta_whatsapp('final')); ?>
			</div>
			<p class="lp-fine"><?php _p(esc_html($t['final']['note'])); ?></p>
		</div>
	</div>
</section>
