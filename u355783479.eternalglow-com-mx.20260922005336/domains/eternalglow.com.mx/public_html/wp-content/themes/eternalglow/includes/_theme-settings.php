<div class="wrap">
	<h1>Theme Settings</h1>

	<?php settings_errors(); ?>

	<?php
		$tab = 'logos';
		extract($_GET);
		$tabs = array(
			'logos' => 'Logos',
			'contact' => 'Contacto',
			'social' => 'Social',
			'texts' => 'Textos',
			'links' => 'Links',
		);
	?>
	<nav class="nav-tab-wrapper wp-clearfix">
		<?php foreach($tabs as $k => $_tab){ ?>
			<a href="<?php _p(admin_url("admin.php?page=theme-settings&tab={$k}")); ?>" class="nav-tab<?php if($tab == $k){ ?> nav-tab-active<?php } ?>">
				<?php _p($_tab); ?>
			</a>
		<?php } ?>
	</nav>

	<form method="post" action="options.php">
		<?php 
			settings_fields("theme-{$tab}");
			do_settings_sections("theme-{$tab}" );
			
			require_once(get_template_path()."/includes/_theme-{$tab}.php");
		?>

		<hr />
		
		<?php submit_button(); ?>

	</form>
</div>