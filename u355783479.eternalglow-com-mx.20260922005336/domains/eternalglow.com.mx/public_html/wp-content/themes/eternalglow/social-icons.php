<?php 
	$social = get_theme_social(); 
	$icons = [
        'facebook' => array(
			'label' => 'Facebook',
			'icon' => eg_icon('facebook-logo'),
			'url' => $social->facebook,
		),
        'twitter' => array(
			'label' => 'X',
			'icon' => eg_icon('x-logo'),
			'url' => $social->twitter,
		),
        'youtube' => array(
			'label' => 'YouTube',
			'icon' => eg_icon('youtube-logo'),
			'url' => $social->youtube,
		),
        'instagram' => array(
			'label' => 'Instagram',
			'icon' => eg_icon('instagram-logo'),
			'url' => $social->instagram,
		),
        'pinterest' => array(
			'label' => 'Pinterest',
			'icon' => eg_icon('pinterest-logo'),
			'url' => $social->pinterest,
		),
        'linkedin' => array(
			'label' => 'LinkedIn',
			'icon' => eg_icon('linkedin-logo'),
			'url' => $social->linkedin,
		),
        'tiktok' => array(
			'label' => 'TikTok',
			'icon' => eg_icon('tiktok-logo'),
			'url' => $social->tiktok,
		),
        'spotify' => array(
			'label' => 'Spotify',
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
						<a class="btn btn-outline-body rounded p-0 ratio ratio-1x1 width-40 fs-20" href='<?php _p($icon['url']); ?>' target='_blank' rel='noopener' aria-label='<?php _p(esc_attr("Eternal Glow en {$icon['label']}")); ?>'>
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