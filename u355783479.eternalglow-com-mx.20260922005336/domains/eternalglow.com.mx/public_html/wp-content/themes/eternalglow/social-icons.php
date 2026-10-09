<?php 
	$social = get_theme_social(); 
	$icons = [
        'facebook' => array(
			'icon' => eg_icon('facebook-logo'),
			'url' => $social->facebook,
		),
        'twitter' => array(
			'icon' => eg_icon('x-logo'),
			'url' => $social->twitter,
		),
        'youtube' => array(
			'icon' => eg_icon('youtube-logo'),
			'url' => $social->youtube,
		),
        'instagram' => array(
			'icon' => eg_icon('instagram-logo'),
			'url' => $social->instagram,
		),
        'pinterest' => array(
			'icon' => eg_icon('pinterest-logo'),
			'url' => $social->pinterest,
		),
        'linkedin' => array(
			'icon' => eg_icon('linkedin-logo'),
			'url' => $social->linkedin,
		),
        'tiktok' => array(
			'icon' => eg_icon('tiktok-logo'),
			'url' => $social->tiktok,
		),
        'spotify' => array(
			'icon' => eg_icon('spotify-logo'),
			'url' => $social->spotify,
		),
    ];
?>
<?php if($social->has_social){ ?>
	<div class='social-icons'>
		<div class='row g-16 justify-content-center'>
			<?php foreach($icons as $name => $icon){ ?>
				<?php if($icon['url']){ ?>
					<div class='col-auto'>
						<a class="btn btn-outline-body rounded p-0 ratio ratio-1x1 width-40 fs-20" href='<?php _p($icon['url']); ?>' target='_blank' rel='noopener' aria-label='<?php _p(ucfirst($name)); ?>'>
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