		</main>
		<?php 
    		$logo = get_theme_logo(2);
			$texts = get_theme_texts();
			$links = get_theme_links();
			$contact = get_theme_contact();
		?>
		<footer>
			<?php if(!is_404()){ ?>
				<div class="container py-40 py-md-80">
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
										<a href="<?php echo esc_url(home_url('/')); ?>">
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
	                    </div>
                    </div>
				</div>
			</div>
		</footer> 
		<div id='toasts' class='toasts toast-container'></div>
		<button id='go-top' class='go-top scroll-to' data-scroll='0'>
			<i class="fa-solid fa-chevron-up"></i>
		</button>  

		<?php wp_footer(); ?>
		
		<?php scripts('before_body'); ?>

	</body>
</html>