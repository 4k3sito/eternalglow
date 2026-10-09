		</main>
		<?php 
    		$logo = get_theme_logo(2);
			$texts = get_theme_texts();
			$links = get_theme_links();
			$contact = get_theme_contact();
		?>
		<footer>
			<?php if(!is_404()){ ?>
				<div class="container py-40 py-md-80" id="contacto">
					<?php get_template_part('contact-form'); ?>
				</div>
			<?php } ?>
			<div class='container'>
				<div class='footer text-body p-40 fs-14 bg-white rounded-24 shadow-sm text-md-left text-center'>
					<div>
						<div class="row g-32 justify-content-between">
							<div class="col-md-auto">
								<div class='d-grid gap-40 text-center'>
									<div>
										<a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php _p(esc_attr(get_bloginfo('name'))); ?>">
											<img src='<?php _p($logo->url); ?>' alt='<?php _p($logo->alt); ?>' class='height-60' />
										</a>
									</div>
									<div>
										<?php get_template_part('social-icons'); ?>
									</div>
								</div>
							</div>
							<div class="col-md-auto">
								<?php dynamic_sidebar('footer-sidebar'); ?>
							</div>
							<div class="col-md-auto">
								<?php dynamic_sidebar('footer-sidebar-2'); ?>
							</div>
							<div class="col-md-auto">
								<?php dynamic_sidebar('footer-sidebar-3'); ?>
							</div>
						</div>
					</div>
					<hr class="my-40" />
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center w-100">
	                    <div class="text-md-left text-center">
							<?php if ( is_page('tratamientos') || is_singular('service') ) { ?>
								<?php _p($texts->copyright); ?>
								<a href="https://mktboom.com/" target="_blank" rel="noopener noreferrer">
									Agencia de publicidad y marketing digital
								</a>
							<?php } else { ?>
								<?php _p($texts->copyright); ?> <?php _p($texts->powered_by); ?>
							<?php } ?>
	                    </div>
	                    <div class="text-md-right text-center mt-12 mt-md-0">
		                    <strong>Permiso de publicidad COFEPRIS:</strong> 2509152002A00185
							<?php if($links->privacy){ ?>
								· <a href="<?php _p(esc_url($links->privacy)); ?>">Aviso de privacidad</a>
							<?php } ?>
	                    </div>
                    </div>
				</div>
			</div>
		</footer> 
		<div id='toasts' class='toasts toast-container'></div>
		<?php // Phones: the booking button stays one thumb away on every page (hidden while another booking button is on screen, see stickyBar in main.ts). ?>
		<div class="lp-bar" id="lp-bar">
			<a class="btn btn-primary" href="<?php _p(esc_url(eg_booking_url())); ?>" target="_blank" rel="noopener" data-cta="barra"><?php _p(eg_lang() == 'en' ? 'Book your consultation' : 'Agenda tu valoración'); ?></a>
			<?php if(get_whatsapp_url()){ ?>
				<a class="btn btn-outline-primary" href="<?php _p(esc_url(get_whatsapp_url())); ?>" target="_blank" rel="noopener" aria-label="WhatsApp" data-cta="barra"><?php _p(eg_icon('whatsapp-logo')); ?></a>
			<?php } ?>
		</div>
		<?php if(get_whatsapp_url()){ ?>
			<a class="lp-wa-float" href="<?php _p(esc_url(get_whatsapp_url())); ?>" target="_blank" rel="noopener" aria-label="WhatsApp" data-cta="flotante">
				<?php _p(eg_icon('whatsapp-logo')); ?>
			</a>
		<?php } ?>

		<?php wp_footer(); ?>
		
		<?php scripts('before_body'); ?>

	</body>
</html>