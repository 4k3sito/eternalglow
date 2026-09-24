<?php 
	$social = get_theme_social(); 
	$icons = [
        'facebook' => array(
			'icon' => '<i class="fa-brands fa-facebook-f"></i>',
			'url' => $social->facebook,
		),
        'twitter' => array(
			'icon' => '<i class="fa-brands fa-x-twitter"></i>',
			'url' => $social->twitter,
		),
        'youtube' => array(
			'icon' => '<i class="fa-brands fa-youtube"></i>',
			'url' => $social->youtube,
		),
        'instagram' => array(
			'icon' => '<i class="fa-brands fa-instagram"></i>',
			'url' => $social->instagram,
		),
        'pinterest' => array(
			'icon' => '<i class="fa-brands fa-pinterest-p"></i>',
			'url' => $social->pinterest,
		),
        'linkedin' => array(
			'icon' => '<i class="fa-brands fa-linkedin-in"></i>',
			'url' => $social->linkedin,
		),
        'tiktok' => array(
			'icon' => '<i class="fa-brands fa-tiktok"></i>',
			'url' => $social->tiktok,
		),
        'spotify' => array(
			'icon' => '<i class="fa-brands fa-spotify"></i>',
			'url' => $social->spotify,
		),
    ];
?>
<?php if($social->has_social){ ?>
	<div class='social-icons'>
		<div class='row g-16 justify-content-center'>
			<?php foreach($icons as $icon){ ?>
				<?php if($icon['url']){ ?>
					<div class='col-auto'>
						<a class="btn btn-outline-body rounded p-0 ratio ratio-1x1 width-40 fs-20" href='<?php _p($icon['url']); ?>' target='_blank'>
							<span class="d-flex justify-content-center align-items-center">
								<?php _p($icon['icon']); ?>
							</span>
						</a>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
<?php } ?>