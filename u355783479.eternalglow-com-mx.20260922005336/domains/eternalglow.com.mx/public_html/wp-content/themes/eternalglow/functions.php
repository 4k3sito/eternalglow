<?php
	require_once(TEMPLATEPATH.'/includes/functions.php');
	require_once(get_template_path().'/includes/_theme.php');
	require_once(get_template_path().'/includes/_token.php');
	require_once(get_template_path().'/includes/_vc.php');
	require_once(get_template_path().'/includes/_ajax.php');


	register_nav_menus(
		array(
			'theme_main_menu' => 'Menú Pricinpal',
		)
	);

	function register_post_types() {

		$args_services = array(
			'label' => 'Servicios',
			'labels' => array(
				'name' => 'Servicios',
				'menu_name' => 'Servicios',
				'singular_name' => 'Servicio',
				'add_new' => 'Agregar nuevo hgf',
				'add_new_item' => 'Agregar nuevo',
				'edit_item' => 'Editar Servicio'
			),
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'rewrite' => array( 
				'slug' => 'servicio'
			),
            'supports' => array(
                'title', 
				'editor',
				'author',
				'thumbnail',
				'page-attributes',
			)
		);
		
		$args_tax_category = array(
			'label' => 'Categorías',
			'labels' => array(
				'name' => 'Categorías',
				'menu_name' => 'Categorías',
				'add_new_item' => 'Agregar nueva categoría',
				'edit_item' => 'Editar Categoría',
			),
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => true,
			'rewrite' => array( 
				'slug' => 'categoria'
			),
		);

		register_post_type('service', $args_services);
		register_taxonomy('tax_category', 'service', $args_tax_category);

	}

	function get_services($category, $limit = -1){
		$d = [];
		$args = [
			'posts_per_page' => $limit,
			'tax_query' => array(
				array(
					'taxonomy' => 'tax_category',
					'field' => 'id',
					'terms' => $category
				)
			),
			'post_type' => 'service',
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
		];
		$d = get_posts($args);
		return $d;
	}

	function get_related_services($post_id = 0, $limit = -1){
		$post_id = get_post_id($post_id);
		$tax_category = 'tax_category';
        $term_ids = wp_get_post_terms($post_id, $tax_category, array('fields' => 'ids'));
		$args = array( 
			'posts_per_page' => $limit,
            'tax_query' => array(
                array(
                    'taxonomy' => $tax_category,
                    'field' => 'id',
                    'terms' => $term_ids
                )
            ),
            'post__not_in' => array($post_id),
            'post_type' => 'service',
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
        );
		$d = get_posts($args);
		return $d;
	}

	function format_jss_text($text){
		$text = str_replace('{{', "<span class='text-primary'>", $text);
		$text = str_replace('}}', "</span>", $text);
		$text = str_replace('{i}', "<span class='fst-italic'>", $text);
		$text = str_replace('{/i}', "</span>", $text);
		$text = str_replace('{sb}', "<span class='fw-semibold'>", $text);
		$text = str_replace('{/sb}', "</span>", $text);
		$text = str_replace('{b}', "<span class='fw-bold'>", $text);
		$text = str_replace('{/b}', "</span>", $text);
		return $text;
	}
	
	function get_css_variables_from_images($var_prefix = 'img') {
		$var_prefix = $var_prefix ? $var_prefix: 'img';
		$dir = get_images_path(); // Debe devolver una ruta tipo /ruta/absoluta/o/relativa/
		$url_base = str_replace(ABSPATH, site_url('/') , $dir); // Convierte ruta a URL

		$css = "<style>:root {";

		foreach (scandir($dir) as $file) {
			if (in_array($file, ['.', '..'])) continue;

			$path = $dir . '/' . $file;

			if (is_file($path) && preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $file)) {
				$name = pathinfo($file, PATHINFO_FILENAME);
				$var_name = '--' . $var_prefix . '-' . sanitize_title($name);
				$url = $url_base . $file;

				$css .= "  {$var_name}: url('{$url}');\n";
			}
		}

		$css .= "}</style>";
		return $css;
	}

	
	
	function css_variables_from_images($var_prefix = 'img') {
		echo get_css_variables_from_images($var_prefix);
	}

	add_action('wp_head', 'css_variables_from_images');

















	function get_master_user_id(){
		return 1;
	}
	
	function is_master_user(){
		return get_current_user_id() == get_master_user_id();
	}
	
	function extra_pre_get_users($query){
		$user_id = get_current_user_id();
		if(is_admin() && !is_master_user()){
			$query->query_vars['exclude'] = get_master_user_id();
		}
		return $query;
	}

	add_action('pre_get_users', 'extra_pre_get_users'); 

	add_filter('wpcf7_autop_or_not', '__return_false');







	
	
	/*function custom_template_redirect() {
		if (is_page('book-appointment')) {
			wp_redirect('https://book.nimblr.co/TrimRx');
			exit;
		}
	}

	add_action('template_redirect', 'custom_template_redirect');*/

	function extra_add_meta_boxes() {
		
	}
	
	function extra_scripts() {
		$rv = get_rv();
		$manifest = get_rev_manifest();
		$vars = array(
			'ajaxURL' => admin_url('admin-ajax.php'),
			'templateURL' => get_template_url(),
			'token' => getSessionToken(),
			'is_mobile' => is_mobile(),
			'jss_token' => get_jss_token(),
		);
		wp_enqueue_style('custom-app', $manifest->css, '', $rv);
		wp_enqueue_script('custom-app', $manifest->js, '', $rv, true);
		wp_localize_script('custom-app', 'JSS_APP', $vars);
	}
	
	add_action('wp_enqueue_scripts', 'extra_scripts');
	
	add_action('add_meta_boxes', 'extra_add_meta_boxes');
	
	add_action('init', 'extra_init');
	add_action('admin_init', 'extra_admin_init');
	add_action('wp', 'extra_wp');
	
	function extra_wp() {

	}

	function extra_init(){
		register_post_types();
	}
	
	function extra_admin_init(){

	}