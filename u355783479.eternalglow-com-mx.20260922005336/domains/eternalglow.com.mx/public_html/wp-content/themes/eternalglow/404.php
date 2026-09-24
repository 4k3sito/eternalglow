<?php get_header(); ?>

		<div class='section py-40 py-md-80'>
			<div class='entry'>
				<div class='container'>
					<div class='entry-content'>
						
						<div class="row g-24 text-center">
							<div class="col-md-7">
								<div class="bg-white p-40 rounded-24 shadow-sm d-flex align-items-center justify-content-center h-100">
									<div class="d-grid gap-24">
										<h1 class="mb-0">ERROR <span class="text-primary">404</span></h1>
										<div>Oops parece que no está lo que buscas.</div>
										<div>
											<a class="btn-arrow btn-arrow-primary" href="<?php base_url(); ?>">
												<span class="btn-arrow-text">
													<span>Regresar</span>
												</span>
												<span class="btn-arrow-icon">
													<img src="<?php images_url('arrow-right-up.svg'); ?>" alt="" />
												</span>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<img src="<?php images_url('404.png'); ?>" alt="" class="rounded-24" />
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		
<?php get_footer(); ?>