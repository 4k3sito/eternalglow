<?php
	// Hand-picked Google reviews (eg_patient_reviews): on each hub, the ones about its treatment; on the home, the
	// before-and-after ones. Each card names the treatment and shows the patient's own photos when the review has them.
	$items = $args['items'];
	if(!$items){
		return;
	}
	$t = eg_home_strings();
	$en = eg_lang() == 'en';
	$stars = '<span class="lp-stars" aria-hidden="true">'.str_repeat(eg_icon('star'), 5).'</span>';
?>

<section class="lp-section<?php _p(!empty($args['band']) ? ' lp-band' : ''); ?>" id="<?php _p(esc_attr($args['id'])); ?>" aria-labelledby="<?php _p(esc_attr($args['id'])); ?>-title">
	<div class="lp-wrap">
		<div class="lp-head">
			<div>
				<p class="lp-eyebrow"><?php _p(esc_html($args['eyebrow'])); ?></p>
				<h2 id="<?php _p(esc_attr($args['id'])); ?>-title"><?php _p(esc_html($args['title'])); ?></h2>
			</div>
		</div>
		<ul class="lp-reviews lp-patient-reviews" tabindex="0">
			<?php foreach($items as $review){ ?>
				<?php $date = new DateTimeImmutable($review->date); ?>
				<li>
					<figure>
						<?php if($review->photos){ ?>
							<?php // In the order the patient posted them (for a before and after, the before comes first). ?>
							<div class="lp-review-photos<?php _p(count($review->photos) > 1 ? ' is-pair' : ''); ?>">
								<?php foreach($review->photos as $i => $photo){ ?>
									<img src="<?php images_url('resenas/'.$photo); ?>" alt="<?php _p(esc_attr(sprintf($en ? '%s, photo shared by %s in their Google review' : '%s, foto compartida por %s en su reseña de Google', $review->chip, $review->name))); ?>" loading="lazy" decoding="async" />
								<?php } ?>
							</div>
						<?php } ?>
						<span class="lp-review-chip"><?php _p(esc_html($review->chip)); ?></span>
						<?php _p($stars); ?>
						<blockquote>“<?php _p(esc_html($review->text)); ?>”</blockquote>
						<figcaption>
							<span class="lp-avatar" data-initial="<?php _p(esc_attr(mb_strtoupper(mb_substr($review->name, 0, 1)))); ?>" aria-hidden="true"></span>
							<span>
								<?php _p(esc_html($review->name)); ?>
								<small><?php _p(esc_html($en ? $date->format('F Y') : wp_date('F \\d\\e Y', $date->getTimestamp()))); ?><?php _p($review->translated ? ' · '.esc_html($t['reviews']['translated']) : ''); ?></small>
							</span>
						</figcaption>
					</figure>
				</li>
			<?php } ?>
		</ul>
		<?php if(!empty($args['note'])){ ?>
			<p class="lp-fine"><?php _p(esc_html($args['note'])); ?></p>
		<?php } ?>
	</div>
</section>
