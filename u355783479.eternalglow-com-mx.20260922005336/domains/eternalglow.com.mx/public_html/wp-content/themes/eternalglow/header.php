<?php  
	$favicon = get_theme_favicon();
	$touch_icon = get_theme_touch_icon();
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		
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

		<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
		
		<script src="https://kit.fontawesome.com/b86bc3371d.js" crossorigin="anonymous"></script>

		<?php wp_head(); ?>

		<?php scripts('before_head'); ?>
	</head>
	
	<?php
		$mobile = (is_mobile()) ? 'mobile' : 'desktop';
		$args_body = array($mobile);
	?>
	
	<body <?php body_class($args_body); ?>>
		<?php scripts('after_body'); ?>

		<?php get_template_part('main', 'menu'); ?>
		<main>