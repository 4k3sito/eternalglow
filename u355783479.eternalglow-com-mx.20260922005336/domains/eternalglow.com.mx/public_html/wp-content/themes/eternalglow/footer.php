		</main>
		<?php 
			$texts = get_theme_texts();
			$links = get_theme_links();
			$contact = get_theme_contact();
			$social = get_theme_social();
			$t = eg_home_strings();
			$whatsapp = get_whatsapp_url();
		?>
		<?php if(!is_404()){ ?>
			<div class="lp-wrap lp-contact" id="contacto">
				<?php get_template_part('contact-form'); ?>
			</div>
		<?php } ?>
		<footer class="lp-footer">
			<div class="lp-wrap">
				<div class="lp-footer-cols">
					<div>
						<p class="lp-footer-brand">Eternal Glow</p>
						<p><?php _p(esc_html($t['footer']['tagline'])); ?></p>
						<?php get_template_part('social-icons'); ?>
					</div>
					<div>
						<p class="lp-footer-label"><?php _p(esc_html($t['footer']['visit'])); ?></p>
						<p><?php _p(nl2br(esc_html($contact->address))); ?></p>
					</div>
					<div>
						<p class="lp-footer-label"><?php _p(esc_html($t['footer']['hours'])); ?></p>
						<p><?php _p(nl2br(esc_html($contact->schedules))); ?><br><?php _p(esc_html($contact->schedules_note)); ?></p>
					</div>
					<div>
						<p class="lp-footer-label"><?php _p(esc_html($t['footer']['contact'])); ?></p>
						<p>
							<?php if($whatsapp){ ?>
								<a href="<?php _p(esc_url($whatsapp)); ?>" target="_blank" rel="noopener" data-cta="footer">WhatsApp · <?php _p(esc_html($contact->phone1)); ?></a><br>
							<?php } ?>
							<a href="tel:<?php _p(esc_attr($contact->phone1_plain)); ?>"><?php _p(esc_html($contact->phone1)); ?></a><br>
							<?php if($social->instagram){ ?>
								<a href="<?php _p(esc_url($social->instagram)); ?>" target="_blank" rel="noopener">Instagram</a>
							<?php } ?>
						</p>
					</div>
				</div>
				<div class="lp-footer-legal">
					<span>
						<?php if ( is_page('tratamientos') || is_singular('service') ) { ?>
							<?php _p($texts->copyright); ?> Eternal Glow ·
							<a href="https://mktboom.com/" target="_blank" rel="noopener noreferrer">Agencia de publicidad y marketing digital</a>
						<?php } else { ?>
							<?php _p($texts->copyright); ?> Eternal Glow · <?php _p($texts->powered_by); ?>
						<?php } ?>
					</span>
					<span>
						Permiso de publicidad COFEPRIS: 2509152002A00185
						<?php if($links->privacy){ ?>
							· <a href="<?php _p(esc_url($links->privacy)); ?>"><?php _p(esc_html($t['footer']['privacy'])); ?></a>
						<?php } ?>
					</span>
				</div>
			</div>
		</footer> 
		<div id='toasts' class='toasts toast-container'></div>
		<?php // Booking sheet: main.ts fills it from the treatment link that was clicked (see eg_sheet_attrs()). ?>
		<dialog class="lp-sheet" id="lp-sheet" aria-labelledby="lp-sheet-title" data-wa-text="<?php _p(esc_attr($t['sheet']['wa'])); ?>">
			<form method="dialog"><button class="lp-sheet-close" aria-label="<?php _p(esc_attr($t['sheet']['close'])); ?>">&times;</button></form>
			<p class="lp-eyebrow" data-sheet-cat></p>
			<h2 id="lp-sheet-title" data-sheet-name></h2>
			<p data-sheet-desc></p>
			<dl data-sheet-pricebox>
				<dt><?php _p(esc_html($t['sheet']['price'])); ?></dt>
				<dd data-sheet-price></dd>
			</dl>
			<p class="lp-sheet-includes"><?php _p(eg_icon('seal-check')); ?> <?php _p(esc_html($t['sheet']['includes'])); ?></p>
			<div class="lp-actions">
				<?php _p(eg_cta_book('ficha')); ?>
				<?php _p(eg_cta_whatsapp('ficha')); ?>
			</div>
			<p class="lp-fine"><?php _p(esc_html($t['sheet']['note'])); ?></p>
			<a class="lp-link" data-sheet-more href="#"><?php _p(esc_html($t['sheet']['more'])); ?> <?php _p(eg_icon('arrow-right')); ?></a>
		</dialog>
		<?php // Phones: booking stays one thumb away on every page (hidden while another booking button is on screen, see stickyBar in main.ts). ?>
		<div class="lp-bar" id="lp-bar">
			<?php _p(eg_cta_book('barra', $t['cta_bar'])); ?>
			<?php _p(eg_cta_whatsapp('barra', 'WhatsApp')); ?>
		</div>
		<?php if($whatsapp){ ?>
			<a class="lp-wa-float" href="<?php _p(esc_url($whatsapp)); ?>" target="_blank" rel="noopener" aria-label="WhatsApp" data-cta="flotante">
				<?php _p(eg_icon('whatsapp-fill')); ?>
			</a>
		<?php } ?>

		<?php wp_footer(); ?>
		
		<?php scripts('before_body'); ?>

	</body>
</html>
