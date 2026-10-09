<?php  
	$favicon = get_theme_favicon();
	$touch_icon = get_theme_touch_icon();
?>
<!doctype html>
<html lang="<?php _p(eg_lang() == 'en' ? 'en' : 'es-MX'); ?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		
		<?php if($favicon->ID){ ?>
			<link rel="shortcut icon" href="<?php _p($favicon->url); ?>" type="image/png" >
		<?php } ?>
		<?php if($touch_icon->ID){ ?>
			<link rel="apple-touch-icon" href="<?php _p($touch_icon->url); ?>">
		<?php } ?>
		
		<title><?php the_site_title(); ?></title>

		<?php scripts('after_head'); ?>

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Manrope:wght@400..700&display=swap" rel="stylesheet">

		<?php wp_head(); ?>

		<?php scripts('before_head'); ?>
	</head>
	
	<?php
		$mobile = (is_mobile()) ? 'mobile' : 'desktop';
		$args_body = array($mobile);
	?>
	
	<body <?php body_class($args_body); ?>>
		<?php scripts('after_body'); ?>

		<a class="lp-skip" href="#contenido"><?php _p(eg_lang() == 'en' ? 'Skip to content' : 'Saltar al contenido'); ?></a>

		<?php get_template_part('main', 'menu'); ?>
		<main id="contenido">