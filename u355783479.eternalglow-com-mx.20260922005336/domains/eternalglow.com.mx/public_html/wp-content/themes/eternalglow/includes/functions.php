<?php
	//session_start();

	define ('DOMAIN', $_SERVER['SERVER_NAME']);
	define ('SESSION_PREFIX', $table_prefix);
	define ('SESSION_AUTH_TOKEN', SESSION_PREFIX.'_authToken');
	define ('SESSION_NOTICES', SESSION_PREFIX.'_notices');

    $timezone_string = get_option('timezone_string');
	if($timezone_string){
    	date_default_timezone_set($timezone_string); //'America/Mexico_City'
	}

	$EXCERPT_LENGTH = 24; //55;
	$EXCERPT_MORE = '...'; //' [...]';
	
	add_action('after_setup_theme', 'custom_after_setup_theme');
	add_filter('widget_text', 'do_shortcode');
	
	function custom_after_setup_theme(){
		remove_admin_bar();
		add_theme_support( 'post-thumbnails' ); 
	}
	
	function pre($v = array(), $c = 'inherit'){
		echo "<pre style='color: $c; font-family: Consolas, Arial, Helvetica, sans-serif;'>";
		print_r($v);
		echo '</pre>';
	}

	function _p($s = ''){
		echo $s;
	}
	
	function load_view($view = '', $params = array(), $cond = false){
		if(!is_array($params)){
			$params = array();
		}
		extract($params);
		$is_php = false;
		if(strlen($view) > 4){
			if(substr($view, -4, 4) == '.php'){
				$is_php = true;
			}
		} 
		if(!$is_php){
			$view .= '.php';
		}
		if($view != ''){
			$view = get_template_path().'/'.$view;
			if(!$cond){
				include($view);
			} else {
				ob_start();
				include($view);
				$view = ob_get_clean();
				return $view;
			}
		}
	}
	
	function file_get_contents_https($url = ''){
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$output = curl_exec($ch); 
		curl_close($ch); 
		return $output;
	}
	
	function day_name($n = 1){
		$d = '';
		if($n == 1){ $d = 'Lunes'; }
		if($n == 2){ $d = 'Martes'; }
		if($n == 3){ $d = 'Miércoles'; }
		if($n == 4){ $d = 'Jueves'; }
		if($n == 5){ $d = 'Viernes'; }
		if($n == 6){ $d = 'Sábado'; }
		if($n == 7){ $d = 'Domingo'; }
		return $d;
	}
	
	function month_name($m = 1){
		$d = '';
		if($m == 1){ $d = 'Enero'; }
		if($m == 2){ $d = 'Febrero'; }
		if($m == 3){ $d = 'Marzo'; }
		if($m == 4){ $d = 'Abril'; }
		if($m == 5){ $d = 'Mayo'; }
		if($m == 6){ $d = 'Junio'; }
		if($m == 7){ $d = 'Julio'; }
		if($m == 8){ $d = 'Agosto'; }
		if($m == 9){ $d = 'Septiembre'; }
		if($m == 10){ $d = 'Octubre'; }
		if($m == 11){ $d = 'Noviembre'; }
		if($m == 12){ $d = 'Diciembre'; }
		return $d;
	}
	
	function random_string($type = 'unique', $len = 8){
		switch($type){
			case 'basic': return mt_rand();
				break;
			case 'alnum':
			case 'numeric':
			case 'nozero':
			case 'alpha':
				switch ($type){
					case 'alpha': $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'alnum': $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric': $pool = '0123456789';
						break;
					case 'nozero': $pool = '123456789';
						break;
				}
				$str = '';
				for ($i=0; $i < $len; $i++){
					$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
				}
				return $str;
				break;
			case 'unique'	:
			case 'md5'		:
					return md5(uniqid(mt_rand()));
				break;
			case 'encrypt'	:
			case 'sha1'	:
					return do_hash(uniqid(mt_rand(), true), 'sha1');
				break;
		}
	}
	
	function do_hash($str, $type = 'sha1'){
		if ($type == 'sha1'){
			return sha1($str);
		} else {
			return md5($str);
		}
	}
	
	function format_number($n = '', $d = 2){
		if ($n == ''){
			return '0.00';
		}
		$n = trim(preg_replace('/([^0-9\.])/i', '', $n));
		$n = number_format($n, $d, '.', ',');
		return str_replace('.00', '', $n);
	}
	
	function get_rv(){
		$theme = wp_get_theme();
		$d = $theme->Version;
		if(is_localhost()){
			$d = time();
		}
		return $d;
	}
	
	function _rv(){
		echo get_rv();
	}
	
	function redirect($url = false){
		if($url){
			wp_redirect( $url );
			exit;
		}
	}	
	
	function get_the_current_url(){
		$d = get_base_url($_SERVER['REQUEST_URI']);
		if(is_localhost()){
			$d = str_replace(DOMAIN.'/'.DOMAIN, DOMAIN, $d);
		}
		if(is_demo()){
			$d = str_replace(DOMAIN.'/'.DOMAIN, DOMAIN, $d);
		}
		return $d;
	}
	
	function the_current_url(){
		echo get_the_current_url();
	}
	
	function get_the_site_title(){
		if(!is_yoast_seo_active() && !is_seo_by_rank_math_active()){
			$title = get_site_name();
			$site_description = get_site_description();
			if ($site_description && (is_the_home())) {
				$title .= " | $site_description";
			}
			echo $title; 
		}
		wp_title('|', true, 'left');
	}
	
	function the_site_title(){
		echo get_the_site_title();
	}
	
	function is_the_home(){
		return is_front_page();
	}
	
	function get_site_name(){
		$d = get_bloginfo('name');
		return $d;
	}
	
	function get_site_description(){
		$d = get_bloginfo('description');
		return $d;
	}
	
	function site_name(){
		echo get_site_name();
	}
	
	function site_description(){
		echo get_site_description();
	}

	function get_base_url($path = '', $scheme = null){
		return site_url($path, $scheme);
	}
	
	function base_url($path = '', $scheme = null){
		echo get_base_url($path, $scheme);
	}
	
	function get_template_url(){
		return get_template_directory_uri();
	}
	
	function template_url(){
		echo get_template_url();
	}
	
	function get_template_path(){
		return TEMPLATEPATH;
	}
	
	function template_path(){
		echo get_template_path();
	}
	
	function get_stylesheet_url(){
		return get_bloginfo('stylesheet_url');
	}
	
	function stylesheet_url(){
		echo get_stylesheet_url();
	}
	
	function get_images_path($path = ''){
		return get_template_path().'/assets/images/'.$path;
	}
	
	function images_path($path){
		echo get_images_path($path);
	}
	
	function get_images_url($path = ''){
		return get_template_url().'/assets/images/'.$path;
	}
	
	function images_url($path){
		echo get_images_url($path);
	}

	function get_svg($path){
		$svg = wp_cache_get($path, 'svg' );
		if(!$svg){
			$file = get_template_path().'/assets/images/'.$path;
			$svg = file_get_contents($file);
			wp_cache_add($path, $svg, 'svg' );
		}
		return $svg;
	}

	function svg($path){
		echo get_svg($path);
	}
	
	function is_mobile(){
		return wp_is_mobile();
	}
	
	function is_localhost(){
		return (filter_var(DOMAIN, FILTER_VALIDATE_IP));
	}
	
	function is_demo(){
		$pos = strpos(DOMAIN, 'demos');
		return ($pos !== false);
	}
	
	function get_nav_menu_items($menu_name = ''){
		$locations = get_nav_menu_locations();
		$menu_items = array();
		if ( ( $locations ) && isset( $locations[ $menu_name ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
			$menu_items = wp_get_nav_menu_items($menu->term_id);
		}
		$d = $menu_items;
		return $d;
	}
	
	add_filter('nav_menu_css_class' , 'custom_nav_menu_css_class', 10, 4);
	
	function custom_nav_menu_css_class($classes, $item, $args, $depth){
		$is_active = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes) || in_array('current-menu-parent', $classes);
		$has_children = in_array('menu-item-has-children', $classes);
		if($args->theme_location == 'theme_main_menu'){
			if($has_children){
				$classes[] = 'dropdown';
				if($depth){
					$classes[] = 'dropend';
				}
			}
			if($is_active){
				$classes[] = 'active';
			}
			if($depth){
				$classes[] = 'border-bottom';
			}
		}
		if(!$args->theme_location){
			$classes[] = 'nav-item';
			if($is_active){
				$classes[] = 'active';
			}
		}
		return $classes;
	}

	add_filter('nav_menu_link_attributes', 'custom_nav_menu_link_attributes', 10, 4);

	function custom_nav_menu_link_attributes($atts, $item, $args, $depth) {
		$classes = $item->classes;
		$is_active = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes) || in_array('current-menu-parent', $classes);
		$has_children = in_array('menu-item-has-children', $classes);
		if($args->theme_location == 'theme_main_menu'){
			$class = [];
			if($has_children){
				$class[] = 'dropdown-toggle';
				$atts['data-bs-toggle'] = 'dropdown';
				$atts['data-bs-auto-close'] = 'outside';
			}
			if($depth){
				$pl = 24 * $depth;
				$class[] = "dropdown-item nav-link";
			} else {
				$class[] = "nav-link nav-main-menu-link px-0 px-lg-14 py-14";
			}
			if($is_active){
				$class[] = 'active';
			}
			$atts['class'] = implode(' ', $class);
		}
		if(!$args->theme_location){
			$class = ['nav-link d-inline-block p-0'];
			if($is_active){
				$class[] = 'active';
			}
			$atts['class'] = implode(' ', $class);
		}
		return $atts;
	}

	function custom_nav_menu_submenu_css_class($classes, $args, $depth) {
		if($args->theme_location == 'theme_main_menu'){
			$classes[] = 'dropdown-menu text-nowrap bg-white fs-14 px-8 py-4';
			if($depth){
				$classes[] = 'top-lg-0 right-lg-auto left-lg-100p';
			}
		}
		if(!$args->theme_location){
			$classes[] = 'nav flex-column gap-8 pl-16 mt-8';
		}
		return $classes;
	}
	 
	add_filter( 'nav_menu_submenu_css_class', 'custom_nav_menu_submenu_css_class', 10, 3);

	function custom_wp_nav_menu_args($args) {

		if(!$args['theme_location']){
			$args['menu_class'] .= ' nav flex-column gap-16';
		}

		return $args;
	};
	 
	add_filter( 'wp_nav_menu_args', 'custom_wp_nav_menu_args');

	

	/*function custom_nav_menu_item_title( $title, $menu_item, $args ) {
		if($args->theme_location == 'theme_main_menu'){
			$title = "<span>{$title}</span>";
		}		
		return $title;
	}

	add_filter( 'nav_menu_item_title', 'custom_nav_menu_item_title', 10, 3 );*/
			
	function get_custom_trim_excerpt( $text = '', $length = 40, $more = ' [...]' ){
		$trimmed_content = wp_trim_words( $text, $length, $more );
		$d = wpautop($trimmed_content);
		return $d;
	}
	
	function custom_trim_excerpt( $text = '', $length = 40, $more = ' [...]' ){
		echo get_custom_trim_excerpt( $text, $length, $more );
	}
	
	function custom_excerpt_length( $length ) {
		global $EXCERPT_LENGTH;
		return $EXCERPT_LENGTH;
	}

	function set_custom_excerpt_length( $length ) {
		global $EXCERPT_LENGTH;
		$EXCERPT_LENGTH = $length;
	}
	
	function custom_excerpt_more( $more ) {
		global $EXCERPT_MORE;
		return $EXCERPT_MORE;
	}

	function set_custom_excerpt_more( $more ) {
		global $EXCERPT_MORE;
		$EXCERPT_MORE = $more;
	}
	
	add_filter('excerpt_more', 'custom_excerpt_more');
	add_filter('excerpt_length', 'custom_excerpt_length', 999);

	function get_pagination($paged = 0, $max_num_pages = 0, $format_query = false, $prev_next = true, $url = ''){
		if(!$max_num_pages){
			global $wp_query;
			$paged = get_query_var('paged');
			$max_num_pages = $wp_query->max_num_pages;
		}
		$big = 999999999; // need an unlikely integer
		$pagenum_link = esc_url(get_pagenum_link($big));
		if($url){
			$pagenum_link = $url."page/{$big}/";
		}
		$base = str_replace($big, '%#%', $pagenum_link);
		$base = str_replace(http_build_query($_GET, '', '&#038;'), '', $base);
		$args = array(
			'base' => $base,
			//'format' => '?paged=%#%',
			'current' => max( 1, $paged ),
			'total' => $max_num_pages,
			'type' => 'array',
			'mid_size' => 1,
			'prev_next' => $prev_next,
			'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
			'next_text' => '<i class="fa-solid fa-chevron-right"></i>'
		);
		if($format_query){
			$args['base'] = '%_%';
			$args['format'] = '?_paged=%#%';
			if(is_array($format_query)){
				$args = array_merge($args, $format_query);
			}
		}
		$pagination = paginate_links($args);
		return $pagination;
	}
	
	function pagination($paged = 0, $max_num_pages = 0, $format_query = false, $prev_next = true, $url = ''){
		$pagination = get_pagination($paged, $max_num_pages, $format_query, $prev_next, $url);
		$html = '';
		
		if($pagination){
			$html .= '
					<div class="nav-pagination">
						<div class="row g-8">
					';
			foreach($pagination as $page){
				$pos1 = strpos($page, 'current');
				$pos2 = strpos($page, 'prev');
				$pos3 = strpos($page, 'next');
				$page = str_replace('page-numbers', 'text-reset d-flex align-items-center justify-content-center', $page);
				$class = $pos1 !== false ? ' active fw-bold': '';
				$class .= $pos2 !== false || $pos3 !== false ? ' bg-primary text-white': '';
				$html .= "
					<div class='col-auto'>
						<div class='ratio ratio-1x1 rounded-pill width-32{$class}'>
							{$page}
						</div>
					</div>";
			}
			$html .= '
						</div>
					</div>
				';
		}
		echo $html;
	}

	function get_theme_image($id = 0, $size = 'full'){
		$d = array(
			'ID' => 0,
			'title' => '',
			'caption' => '',
			'alt' => '',
			'description' => '',
			'url' => '',
			'width' => 700,
			'height' => 700
		);
		if($id){
			$image = get_post($id);
			if($image){
				$d['ID'] = $image->ID;
				$d['title'] = $image->post_title;
				$d['caption'] = $image->post_excerpt;
				$d['alt'] = $image->_wp_attachment_image_alt;
				$d['description'] = $image->post_content;
				
				$imageArray = wp_get_attachment_image_src($id, $size);
				$url = $imageArray[0];
				$w = $imageArray[1];
				$h = $imageArray[2];
				$d['url'] = $url;
				$d['width'] = $w;
				$d['height'] = $h;
			}
		}
		if(!$d['url']){
			$url = get_template_url().'/assets/images/no-image.jpg';
			$d['url'] = $url;
		}
		$d = (object)$d;
		return $d;
	}

	function get_theme_file($id = 0){
		$d = array(
			'ID' => 0,
			'title' => '',
			'caption' => '',
			'description' => '',
			'filename' => '',
			'url' => '',
			'icon' => '',
			'mime_type',
		);
		if($id){
			$image = get_post($id);
			if($image){
				$icon = wp_mime_type_icon($image->post_mime_type);
				$d['ID'] = $image->ID;
				$d['title'] = $image->post_title;
				$d['caption'] = $image->post_excerpt;
				$d['description'] = $image->post_content;
				$url = wp_get_attachment_url($id);
				$d['filename'] = wp_basename($url);
				$d['url'] = $url;
				$d['icon'] = $icon;
				$d['mime_type'] = $image->post_mime_type;
			}
		}
		$d = (object)$d;
		return $d;
	}
	
	function get_thumbnail($post_id = 0, $size = 'full'){
		if(!$post_id){
			$post_id = get_the_ID();
		}
		if(is_object($post_id)){
			$post_id = $post_id->ID;
		}
		$thumbnail_id = get_post_thumbnail_id($post_id);
		$d = get_theme_image($thumbnail_id, $size);
		if(!$d->ID){
			$_d = get_post_meta($post_id, 'image_url', true);
			if($_d){
				$d->url = $_d;
			}
		}
		return $d;
	}
	
	function get_extra_image($post_id = 0, $field = 'extra_image', $size = 'full'){
		if(!$post_id){
			$post_id = get_the_ID();
		}
		if(is_object($post_id)){
			$post_id = $post_id->ID;
		}
		$thumbnail_id = get_post_meta($post_id, $field, true);
		$d = get_theme_image($thumbnail_id, $size);
		return $d;
	}

	function get_term_thumbnail($term_id = 0, $size = 'full'){
		if(is_object($term_id)){
			$term_id = $term_id->term_id;
		}
		$thumbnail_id = get_term_meta($term_id, '_thumbnail', true);
		$d = get_theme_image($thumbnail_id, $size);
		$d = (object)$d;
		return $d;
	}

	function get_term_extra_image($term_id = 0, $size = 'full'){
		if(is_object($term_id)){
			$term_id = $term_id->term_id;
		}
		$thumbnail_extra = get_term_meta($term_id, '_thumbnail_extra', true);
		$d = get_theme_image($thumbnail_extra, $size);
		return $d;
	}

	function get_term_file($term_id = 0){
		if(is_object($term_id)){
			$term_id = $term_id->term_id;
		}
		$_file = get_term_meta($term_id, '_file', true);
		$d = get_theme_file($_file);
		return $d;
	}
	
	function get_term_depth($term_id = 0, $taxonomy = ''){
		$d = 0;
		if(is_object($term_id)){
			$term_id = $term_id->term_id;
		}
		$ancestors = get_ancestors($term_id, $taxonomy);
		$d = count($ancestors);
		return $d;
	}
	
	function get_the_timthumb($url = '', $w = 100, $h = 100, $params = '', $q = 90){
		// color f=5,R,G,B,A Ej: f=5,131,204,31,1
		$template_url = get_template_url();
		if($params){
			$params = '&amp;'.$params;
		}
		$params = $params.'&amp;q='.$q;
		$d = $template_url.'/timthumb/?src='.$url."&amp;w=$w&amp;h=$h".$params;
		return $d;
	}
	
	function the_timthumb($url = '', $w = 100, $h = 100, $params = '', $q = 90){
		$d = get_the_timthumb($url, $w, $h, $params);
		echo $d;
	}
	
	function is_request_method_get(){
		$d = false;
		if ($_SERVER['REQUEST_METHOD'] == 'GET'){
			$d = true;
		}
		return $d;
	}
	
	function is_request_method_post(){
		$d = false;
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$d = true;
		}
		return $d;
	}
	
	function delete_post($post_id = 0){
		if(is_object($post_id)){
			$post_id = $post_id->ID;
		}
		wp_delete_post( $post_id, true );
	}
	
	function get_filter_content($content = ''){
		$d = apply_filters('the_content', $content);
		return $d;
	}
	
	function the_filter_content($content = ''){
		echo get_filter_content($content);
	}
	
	add_action( 'admin_bar_menu', 'custom_admin_bar_menu', 999);

	function custom_admin_bar_menu( $wp_admin_bar ) {
		$args = array(
			'id' => 'theme-settings',
			'title' => 'Theme Settings',
			'href' => admin_url('admin.php?page=theme-settings'),
		);
		$wp_admin_bar->add_node( $args );
		$wp_admin_bar->remove_node( 'wp-logo' );
	}
	
	function remove_admin_bar() {
		$is_the_user_admin = is_the_user_admin();
		if(!$is_the_user_admin){
			show_admin_bar(false);
		}
	}

	add_action( 'current_screen', 'custom_current_screen' );

	function custom_current_screen() {
		$current_screen = get_current_screen();
		if(is_the_user_subscriber() && is_admin()){
			if($current_screen->id != 'async-upload'){
				redirect(get_base_url());
			}
		}
		if($current_screen->base != 'post'){
			wp_enqueue_media();
		}
	}
	
	function is_the_user($role = '', $user_id = false){
		$d = false;
		$current_user = false;
		if(is_object($user_id)){
			$current_user = $user_id;
		} elseif ($user_id) {
			$current_user = get_userdata( $user_id );
		} else {
			$current_user = wp_get_current_user();
		}
		if($current_user){
			if($current_user->roles){
				if(in_array($role, $current_user->roles)){
					$d = true;
				}
			}
		}
		return $d;
	}	
	
	function is_the_user_admin($user_id = false){
		$d = is_the_user('administrator', $user_id);
		return $d;
	}
	
	function is_the_user_subscriber($user_id = false){
		$d = is_the_user('subscriber', $user_id);
		return $d;
	}
	
	function send_email($to = '', $subject = '', $message = '', $cc = '', $bcc = '', $attachments = array()){
		$d = false;
		$name = get_site_name();
		$headers[] = 'From: '.$name.' <no_reply@'.DOMAIN.'>';
		if($cc){
			if(is_array($cc)){
				foreach($cc as $_cc){
					$headers[] = 'Cc: '.$_cc;
				}
			} else {
				$headers[] = 'Cc: '.$cc;
			}
		}
		if($bcc){
			if(is_array($bcc)){
				foreach($bcc as $_bcc){
					$headers[] = 'Bcc: '.$_bcc;
				}
			} else {
				$headers[] = 'Bcc: '.$bcc;
			}
		}
		add_filter( 'wp_mail_content_type', 'set_html_content_type' );
		$d = wp_mail( $to, $subject, $message, $headers, $attachments);	
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		return $d;
	}
	
	function set_html_content_type() {
		return 'text/html';
	}

	function get_custom_input_thumbnail($name = '', $thumbnail = false){
		$uniqid = random_string();
		$class = '';
		$id_img = "custom-thumbnail-img-{$uniqid}";
		$id_input = "custom-thumbnail-image-{$uniqid}";
		if(!$thumbnail){
			$thumbnail = new stdClass;
			$thumbnail->ID = '';
			$thumbnail->url = '';
		}
		if($thumbnail->ID){
			$class = ' active';
		}
		$html = "
			<div>
				<button class='button add-custom-theme-image' type='button' data-img='#{$id_img}' data-input='#{$id_input}'>Agregar / Editar</button>
				<img src='{$thumbnail->url}' class='custom-theme-img{$class}' id='{$id_img}' />
				<a href='#' data-img='#{$id_img}' data-input='#{$id_input}' class='remove-custom-theme-image'>Quitar imagen</a>
				<input type='hidden' value='{$thumbnail->ID}' id='{$id_input}' class='custom-theme-image' name='{$name}' />
			</div>
		";
		return $html;
	}

	function custom_input_thumbnail($name = '', $thumbnail = false){
		echo get_custom_input_thumbnail($name, $thumbnail);
	}

	function get_custom_input_file($name = '', $file = object){
		$uniqid = random_string();
		$class = '';
		$id_img = "custom-file-img-{$uniqid}";
		$id_input = "custom-file-image-{$uniqid}";
		$id_span = "custom-file-name-{$uniqid}";
		if($file->ID){
			$class = ' active';
		}
		$html = "
			<button class='button add-custom-theme-file' type='button' data-img='#{$id_img}' data-input='#{$id_input}' data-span='#{$id_span}'>Agregar / Editar</button>
			<img src='{$file->icon}' class='custom-theme-img{$class}' id='{$id_img}' />
			<span id='{$id_span}' class='custom-theme-filename'>{$file->filename}</span>
			<a href='#' data-img='#{$id_img}' data-input='#{$id_input}' data-span='#{$id_span}' class='remove-custom-theme-file'>Quitar archivo</a>
			<input type='hidden' value='{$file->ID}' id='{$id_input}' class='custom-theme-file' name='{$name}' />
		";
		return $html;
	}

	function custom_input_file($name = '', $file = object){
		echo get_custom_input_file($name, $file);
	}
	
	function term_add_image(){
		$term_image = get_custom_input_thumbnail('term_image');
		echo "
			<div class='form-field'>
				<label>Imagen</label>
				{$term_image}
			</div>
		";
	}
	
	function term_edit_image($term){
		$term_id = $term->term_id;
		$thumbnail = get_term_thumbnail($term_id);
		$term_image = get_custom_input_thumbnail('term_image', $thumbnail);
		echo "
			<tr class='form-field'>
				<th scope='row'>
					<label>Imagen</label>
				</th>
				<td>					
					{$term_image}
				</td>
			</tr>
		";
	}
	
	function save_term_image($term_id){
		$term_image = '';
		extract($_POST);
		if(($action == 'editedtag' || $action == 'add-tag') && $taxonomy == 'tax_category'){
			update_term_meta($term_id, '_thumbnail', $term_image);
		}
	}
	
	function term_add_extra_image(){
		$term_image = get_custom_input_thumbnail('term_image_extra');
		echo "
			<div class='form-field'>
				<label>Banner</label>
				{$term_image}
			</div>
		";
	}
	
	function term_edit_extra_image($term){
		$thumbnail = get_term_extra_image($term->term_id);
		$term_image = get_custom_input_thumbnail('term_image_extra', $thumbnail);
		echo "
			<tr class='form-field'>
				<th scope='row'>
					<label>Banner</label>
				</th>
				<td>					
					{$term_image}
				</td>
			</tr>
		";
	}
	
	function save_term_extra_image($term_id){
		$term_image_extra = '';
		extract($_POST);
		if($action == 'editedtag' || $action == 'add-tag'){
			update_term_meta($term_id, '_thumbnail_extra', $term_image_extra);
		}
	}
	
	function term_add_file(){
		$term_file = get_custom_input_file('term_file');
		echo "
			<div class='form-field'>
				<label>Archivo</label>
				{$term_file}
			</div>
		";
	}
	
	function term_edit_file($term){
		$_file = get_term_meta($term->term_id, '_file', true);
		$file = get_theme_file($_file);
		$term_file = get_custom_input_file('term_file', $file);
		echo "
			<tr class='form-field'>
				<th scope='row'>
					<label>Archivo</label>
				</th>
				<td>					
					{$term_file}
				</td>
			</tr>
		";
	}
	
	function save_term_file($term_id){
		$term_file = '';
		extract($_POST);
		if($action == 'editedtag' || $action == 'add-tag'){
			update_term_meta($term_id, '_file', $term_file);
		}
	}
	
	function term_add_color(){
		echo "
			<div class='form-field'>
				<label>Color</label>
				<input type='text' name='color' value='' class='colorpicker' />
			</div>
		";
	}
	
	function term_edit_color($term){
		$color = get_term_meta($term->term_id, 'color', true);
		echo "
			<tr class='form-field'>
				<th scope='row'>
					<label>Color</label>
				</th>
				<td>				
					<input type='text' name='color' value='{$color}' class='colorpicker' />
				</td>
			</tr>
		";
	}
	
	function save_term_color($term_id){
		$color = '';
		extract($_POST);
		if($action == 'editedtag' || $action == 'add-tag'){
			update_term_meta($term_id, 'color', $color);
		}
	}

	function get_current_term(){
		$field = 'slug';
		$var = 'term';
		$taxonomy = get_query_var('taxonomy');
		$cat_slug = get_query_var('cat_slug');
		if(is_category()){
			$field = 'id';
			$var = 'cat';
			$taxonomy = 'category';
		}
		if(is_tag()){
			$var = 'tag';
			$taxonomy = 'post_tag';
		}
		if($cat_slug){
			$var = 'cat_slug';
		}
		$term = get_term_by($field, get_query_var($var), $taxonomy);
		return $term;
	}
	
	function get_terms_deep($term_id = 0){
		$d = [];
		$term = get_term($term_id);
		$d[] = $term;
		if($term->parent > 0){
			$d = array_merge($d, get_terms_deep($term->parent));
		}
		return $d;
	}

	function get_pages_deep($page_id = 0){
		$d = [];
		$page = get_page($page_id);
		$d[] = $page;
		if($page->post_parent > 0){
			$d = array_merge($d, get_pages_deep($page->post_parent));
		}
		return $d;
	}

	function nested_terms($terms = array(), $parent_id = 0){
		$d = [];
		if($terms){
			foreach($terms as $term){
				if($term->parent == $parent_id){
					$d[] = $term;
					$d = array_merge($d, nested_terms($terms, $term->term_id));
					break;
				}
			}
		}
		return $d;
	}
	
	/*add_filter('get_the_archive_title', 'extra_the_archive_title');

	function extra_the_archive_title($title) {    
        if (is_category()){    
			$title = single_cat_title( '', false );    
		} elseif ( is_tag() ) {    
			$title = single_tag_title( '', false );    
		} elseif ( is_author() ) {    
			$title = get_the_author();    
		} elseif ( is_tax() ) { //for custom post types
			$title = single_term_title( '', false );
		} elseif (is_post_type_archive()) {
			$title = post_type_archive_title( '', false );
		}
        return $title;    
	}*/
	
	add_action('wp_ajax_nopriv_username_exists', 'ajax_username_exists');
	add_action('wp_ajax_username_exists', 'ajax_username_exists');
	
	function ajax_username_exists(){
		$d = 'true';
		if (is_request_method_post()){
			$username = '';
			extract($_POST);
			if ( username_exists( $username ) ){
				$d = 'false';
			}
		}
		echo $d;
		wp_die();
	}
	
	add_action('wp_ajax_nopriv_email_exists', 'ajax_email_exists');
	add_action('wp_ajax_email_exists', 'ajax_email_exists');
	
	function ajax_email_exists(){
		$d = 'true';
		if (is_request_method_post()){
			$email = '';
			extract($_POST);
			if ( email_exists( $email ) ){
				$d = 'false';
			}
		}
		echo $d;
		wp_die();
	}
	
	function get_ip(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){   //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	function array_count_value($value = '', $array = array()){
		$d = 0;
		foreach($array as $k => $v){
			if($value == $v){
				$d++;
			}
		}
		return $d;
	}
	
	function get_sidebar_widgets($name = ''){
		$d = array();
		if($name){
			$sidebars = wp_get_sidebars_widgets();
			if(array_key_exists($name, $sidebars)){
				$d = $sidebars[$name];
			}
		}
		return $d;
	}
	
	function get_total_widgets($name = ''){
		$d = 0;
		if($name){
			$widgets = get_sidebar_widgets($name);
			$d = count($widgets);
		}
		return $d;
	}
	
	function metabox_posts_gallery_callback( $post ) {
		$gallery = get_the_gallery($post->ID, true);
		$html = '';
		foreach($gallery as $image){
			$thumbnail = get_the_timthumb($image->url, 100, 100);
			$html .= '
				<li>
					<a href="#" class="remove"><span class="dashicons dashicons-dismiss"></span></a>
					<img src="'.$thumbnail.'" alt="" />
					<input type="hidden" value="'.$image->ID.'" name="gallery[]">
				</li>
			';
		}
		$html = '
			<button class="button" id="add-image-gallery" type="button">Agregar imagen</button>
			<div class="gallery" id="gallery">
				<div class="my-10">Arrastra para ordenar.</div>
				<ul id="gallery-sortable" class="gallery-sortable">
					'.$html.'
				</ul>
				<div class="clearfix"></div>
			</div>
		';
		echo $html;
	}
	
	function save_metabox_posts_gallery($post_id){
		if (is_request_method_post()){
			$posts = array('post_type');
			if(!array_key_exists('post_type', $_POST) || !in_array($_POST['post_type'], $posts)){
				return $post_id;
			}
			$gallery = array();
			extract($_POST);
			if($action == 'editpost'){ //inline-save
				delete_post_meta($post_id, 'gallery');
				$g = [];
				foreach($gallery as $id){
					if($id){
						$g[] = $id;
					}
				}
				if($g){
					update_post_meta($post_id, 'gallery', $g);
				}
			}
		}
	}
	
	add_action( 'save_post', 'save_metabox_posts_gallery' );
	
	function get_the_gallery($post_id = 0, $hide_url = false){
		$d = array();
		if(!$post_id){
			$post_id = get_the_ID();
		}
		if(is_object($post_id)) {
			$post_id = $post_id->ID;
		}
		if($post_id){
			$gallery = get_post_meta($post_id, 'gallery', true);
			if($gallery){
				foreach($gallery as $id){
					$image = get_theme_image($id);
					$d[] = $image;
				}
			} else {
				if(!$hide_url){
					$gallery = get_post_meta($post_id, 'gallery_url', true);
					if($gallery){
						$image = get_theme_image(0);
						foreach($gallery as $url){
							$image->url = $url;
							$d[] = $image;
						}
					}
				}
			}
		}
		return $d;
	}

	function get_the_file_gallery($post_id = 0){
		$d = array();
		if(!$post_id){
			$post_id = get_the_ID();
		}
		if(is_object($post_id)) {
			$post_id = $post_id->ID;
		}
		if($post_id){
			$gallery = get_post_meta($post_id, 'file_gallery', true);
			if($gallery){		
				foreach($gallery as $id){
                    $file = get_theme_file($id);
					$d[] = $file;
				}
			}
		}
		return $d;
	}

	function metabox_extra_image_callback($post) {
		$thumbnail = get_extra_image($post);
		custom_input_thumbnail('extra_image', $thumbnail);
	}
	
	function save_metabox_extra_image($post_id){
		if (is_request_method_post()){
			$posts = array('post_type');
			if(!array_key_exists('post_type', $_POST)){
				return $post_id;
			}
			if (!in_array($_POST['post_type'], $posts)) {
				return $post_id;
			}
			$extra_image = '';
			extract($_POST);
			if($action == 'editpost'){
				delete_post_meta($post_id, 'extra_image');
				if($extra_image){
					update_post_meta($post_id, 'extra_image', $extra_image);
				}
			}
		}
	}
	
	add_action('save_post', 'save_metabox_extra_image');	

	function get_page_by_slug($slug = '', $post_type = 'page'){
		$args = array(
			'posts_per_page' => 1,
			'name' => $slug,
			'post_type' => $post_type,
			'post_status' => 'publish'
		);
		$posts = get_posts($args);
		return $posts ? $posts[0]: false;
	}
	
	function notices(){
		get_template_part('notices');
	}
	function add_notice($type = 'success', $message = ''){
		$_SESSION[SESSION_NOTICES][] = (object)array(
			'type' => $type,
			'message' => $message
		);
	}
	function clear_notices() {
		$_SESSION[SESSION_NOTICES] = [];
	}
	function get_notices() {
		return isset($_SESSION[SESSION_NOTICES]) ? $_SESSION[SESSION_NOTICES]: [];
	}
	add_action('wp_footer', 'clear_notices');	
	
	function format_date($time = 0, $format = 'j F, Y'){
		$time = $time ? $time: time();
		$the_date = date('Y-m-d H:i:s', $time);
        $date_text = mysql2date($format, $the_date);
		return $date_text;
	}
	
	function get_theme_colors($key = 'class', $value = 'color'){
		$_variables = file_get_contents(get_template_path().'/assets/scss/_variables.scss');
		$_variables = text_to_array($_variables);
		$_variables = array_filter($_variables, function($line){
			$pos = strpos($line, ': #');
			return $pos !== false;
		});
		$_variables = array_map(function($line){
			$line = str_replace('$', '', $line);
			$line = str_replace(' ', '', $line);
			$line = str_replace(';', '', $line);
			return explode(':', $line);
		}, $_variables);
		$d = [];
		foreach($_variables as $line){
			$class = $line[0];
			$color = $line[1];
			$slug = str_replace('-', '_', $class);
			$name = str_replace('-', ' ', $class);
			$name = str_replace('_', ' ', $name);
			$d[] = (object)array(
				'name' => ucfirst($name),
				'class' => $class,
				'slug' => $slug,
				'color' => $color,
			);
		}
		if($key && $value){
			$_d = $d;
			$d = [];
			foreach($_d as $color){
				$d[$color->{$key}] = $color->{$value};
			}
		}
		return $d;
	}

	function get_theme_color($color = 'body'){
		$colors = get_theme_colors();
		return $colors[$color];
	}

	function get_theme_fonts($key = 'class', $value = 'style'){
		$_variables = file_get_contents(get_template_path().'/assets/scss/_variables.scss');
		$_variables = text_to_array($_variables);
		$_variables = array_filter($_variables, function($line){
			return str_starts_with($line, '$font-family');
		});
		$_variables = array_map(function($line){
			$line = str_replace('$', '', $line);
			$line = str_replace(': ', ':', $line);
			$line = str_replace(';', '', $line);
			return explode(':', $line);
		}, $_variables);
		$d = [];
		foreach($_variables as $line){
			$k = $line[0];
			$class = str_replace('family-', '', $k);
			if(!in_array($k, array('headings-font-family'))){
				$style = $line[1];
				$style = explode(',', $style);
				$name = trim($style[0]);
				$name = trim($name, "'");
				$name = trim($name, "\"");
				$slug = str_replace('-', '_', $class);
				$d[] = (object)array(
					'name' => $name,
					'class' => $class,
					'slug' => $slug,
					'style' => $line[1],
				);
			}
		}
		if($key && $value){
			$_d = $d;
			$d = [];
			foreach($_d as $font){
				$d[$font->{$key}] = $font->{$value};
			}
		}
		return $d;
	}

	function get_rev_manifest(){
		$template_url = get_template_url();
		$template_path = get_template_path();
		$dist = '/dist/';
		$file = $template_path.$dist.'rev-manifest.json';
		$d = array(
			'js' => 'app.js',
			'css' => 'app.css'
		);
		if(file_exists($file)){
			$json = file_get_contents($file);
			$obj = json_decode($json);
			$js = 'app.js';
			$css = 'app.css';
			$d['js'] = $template_url.$dist.$obj->$js;
			$d['css'] = $template_url.$dist.$obj->$css;
		}
		return (object)$d;
	}

    function get_the_css(){
		$manifest = get_rev_manifest();
		echo $manifest->css;
    }

	function get_the_js(){
		$manifest = get_rev_manifest();
		echo $manifest->js;
    }

    function the_css(){
        echo get_the_css();
    }

    function the_js(){
        echo get_the_js();
    }

	function save_log($filename, $value = ''){
		file_put_contents(get_template_path()."/logs/{$filename}.txt", $value);
	}

	function hex2rgb($color, $implode = false){
		$color = str_replace('#', '', $color);
		$color = str_split($color, 2);
		foreach($color as $k => &$v){
			$v = hexdec($v);
		}
		$d = array(
			'r' => $color[0],
			'g' => $color[1],
			'b' => $color[2],
		);
		return $implode ? implode(', ', $color): json_decode(json_encode($d));
	}
	
	/******** - - - - - - - - ********/

	function custom_admin_scripts() {
		$template_url = get_template_url();
		$rv = get_rv();
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('custom-jquery-ui', $template_url.'/assets/css/jquery-ui.min.css', '', $rv);
		wp_enqueue_style('custom-bootstrap', $template_url.'/assets/css/bootstrap.css', '', $rv);
		wp_enqueue_style('custom-admin', $template_url.'/assets/css/admin.css', '', $rv);
		wp_enqueue_style('custom-fancybox', $template_url.'/assets/css/fancybox.css', '', $rv);
		wp_enqueue_style('custom-vc', $template_url.'/assets/css/vc.css', '', $rv);

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('custom-admin', $template_url.'/assets/js/admin.js', array( 'wp-color-picker' ), $rv, true);
		wp_enqueue_script('custom-fancybox', $template_url.'/assets/js/fancybox.umd.js', '', $rv, true);
		wp_enqueue_script('custom-bootstrap', $template_url.'/assets/js/bootstrap.js', $rv, true);
	}
	
	add_action('login_enqueue_scripts', 'custom_admin_scripts');
	add_action('admin_enqueue_scripts', 'custom_admin_scripts');

	function custom_wp_enqueue_scripts(){
		/*wp_enqueue_style('wp-mediaelement');
		wp_enqueue_script('mediaelement-vimeo');
		wp_enqueue_script('wp-mediaelement');*/
	}
	
	add_action('wp_enqueue_scripts', 'custom_wp_enqueue_scripts');

	add_action('wp_loaded', 'output_buffer_start');

	function output_buffer_start() { 
		ob_start("output_callback"); 
	}

	add_action('shutdown', 'output_buffer_end');

	function output_buffer_end() { 
		if (ob_get_length() > 0) { ob_end_clean();}
	}

	function output_callback($buffer) {
		return preg_replace( "%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", '', $buffer );
	}

	function get_css_animation($animation = 'fadeInRight'){
		require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-single-image.php' );
		$img_class = new WPBakeryShortCode_VC_Single_image(array('base' => 'vc_single_image'));
		return trim($img_class->getCSSAnimation($animation));
	}

	function the_css_animation($animation = 'fadeInRight'){
		echo get_css_animation($animation);
	}

	function generate_class($class = '', $add = ''){
		if(is_array($class)){
			$class = implode(' ', $class);
		}
		if(is_array($add)){
			$add = array_filter($add);
			$add = implode(' ', $add);
		}
		$class = explode(' ', $class);
		$class[] = $add;
		sort($class);
		$class = trim(implode(' ', $class));
		return $class;
	}

	function text_to_array($str = '', $flat = true){
		$d = [];
		if($str){
			$a = preg_replace('/\r|\n|<br(\s*)?\/?\>/', '||', $str);
			$a = explode('||', $a);
			$c = [];
			foreach($a as $v){
				if($v){
					$c[] = $v;
				} else {
					$d[] = $c;
					$c = [];
				}
			}
			if($c){
				$d[] = $c;
			}
			if($flat){
				$d = array_merge(...$d);
			}
		}
		return $d;
	}

	function rem($px) {
		return ($px / 16) . 'rem';
	}
	
	function jss_template($file, $params = array()){	
		return load_view("includes/jss-templates/{$file}", $params, true);
	}

	function insert_extra_columns($columns, $inserted, $position) {
		$i = 1;
		$d = [];
		foreach ($columns as $k => $v) {
			if ($i == $position) {
				foreach ($inserted as $_k => $_v) {
					$d[$_k] = $_v;
				}
			}
			$d[$k] = $v;
			$i++;
		}
		return $d;
	}

	function is_polylang_active(){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active('polylang/polylang.php');
	}

	function is_yoast_seo_active(){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active('wordpress-seo/wp-seo.php');
    }

	function is_seo_by_rank_math_active(){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active('seo-by-rank-math/rank-math.php');
    }

	function convert_accented_characters(string $str): string {
		$characterList = [
			'/ä|æ|ǽ/'                                                     => 'ae',
			'/ö|œ/'                                                       => 'oe',
			'/ü/'                                                         => 'ue',
			'/Ä/'                                                         => 'Ae',
			'/Ü/'                                                         => 'Ue',
			'/Ö/'                                                         => 'Oe',
			'/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|Α|Ά|Ả|Ạ|Ầ|Ẫ|Ẩ|Ậ|Ằ|Ắ|Ẵ|Ẳ|Ặ|А/'         => 'A',
			'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|α|ά|ả|ạ|ầ|ấ|ẫ|ẩ|ậ|ằ|ắ|ẵ|ẳ|ặ|а/'       => 'a',
			'/Б/'                                                         => 'B',
			'/б/'                                                         => 'b',
			'/Ç|Ć|Ĉ|Ċ|Č/'                                                 => 'C',
			'/ç|ć|ĉ|ċ|č/'                                                 => 'c',
			'/Д/'                                                         => 'D',
			'/д/'                                                         => 'd',
			'/Ð|Ď|Đ|Δ/'                                                   => 'Dj',
			'/ð|ď|đ|δ/'                                                   => 'dj',
			'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Ε|Έ|Ẽ|Ẻ|Ẹ|Ề|Ế|Ễ|Ể|Ệ|Е|Э/'                 => 'E',
			'/è|é|ê|ë|ē|ĕ|ė|ę|ě|έ|ε|ẽ|ẻ|ẹ|ề|ế|ễ|ể|ệ|е|э/'                 => 'e',
			'/Ф/'                                                         => 'F',
			'/ф/'                                                         => 'f',
			'/Ĝ|Ğ|Ġ|Ģ|Γ|Г|Ґ/'                                             => 'G',
			'/ĝ|ğ|ġ|ģ|γ|г|ґ/'                                             => 'g',
			'/Ĥ|Ħ/'                                                       => 'H',
			'/ĥ|ħ/'                                                       => 'h',
			'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|Η|Ή|Ί|Ι|Ϊ|Ỉ|Ị|И|Ы/'                     => 'I',
			'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|η|ή|ί|ι|ϊ|ỉ|ị|и|ы|ї/'                   => 'i',
			'/Ĵ/'                                                         => 'J',
			'/ĵ/'                                                         => 'j',
			'/Ķ|Κ|К/'                                                     => 'K',
			'/ķ|κ|к/'                                                     => 'k',
			'/Ĺ|Ļ|Ľ|Ŀ|Ł|Λ|Л/'                                             => 'L',
			'/ĺ|ļ|ľ|ŀ|ł|λ|л/'                                             => 'l',
			'/М/'                                                         => 'M',
			'/м/'                                                         => 'm',
			'/Ñ|Ń|Ņ|Ň|Ν|Н/'                                               => 'N',
			'/ñ|ń|ņ|ň|ŉ|ν|н/'                                             => 'n',
			'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|Ο|Ό|Ω|Ώ|Ỏ|Ọ|Ồ|Ố|Ỗ|Ổ|Ộ|Ờ|Ớ|Ỡ|Ở|Ợ|О/'   => 'O',
			'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|ο|ό|ω|ώ|ỏ|ọ|ồ|ố|ỗ|ổ|ộ|ờ|ớ|ỡ|ở|ợ|о/' => 'o',
			'/П/'                                                         => 'P',
			'/п/'                                                         => 'p',
			'/Ŕ|Ŗ|Ř|Ρ|Р/'                                                 => 'R',
			'/ŕ|ŗ|ř|ρ|р/'                                                 => 'r',
			'/Ś|Ŝ|Ş|Ș|Š|Σ|С/'                                             => 'S',
			'/ś|ŝ|ş|ș|š|ſ|σ|ς|с/'                                         => 's',
			'/Ț|Ţ|Ť|Ŧ|τ|Т/'                                               => 'T',
			'/ț|ţ|ť|ŧ|т/'                                                 => 't',
			'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ũ|Ủ|Ụ|Ừ|Ứ|Ữ|Ử|Ự|У/'           => 'U',
			'/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|υ|ύ|ϋ|ủ|ụ|ừ|ứ|ữ|ử|ự|у/'       => 'u',
			'/Ƴ|Ɏ|Ỵ|Ẏ|Ӳ|Ӯ|Ў|Ý|Ÿ|Ŷ|Υ|Ύ|Ϋ|Ỳ|Ỹ|Ỷ|Ỵ|Й/'                       => 'Y',
			'/ẙ|ʏ|ƴ|ɏ|ỵ|ẏ|ӳ|ӯ|ў|ý|ÿ|ŷ|ỳ|ỹ|ỷ|ỵ|й/'                         => 'y',
			'/В/'                                                         => 'V',
			'/в/'                                                         => 'v',
			'/Ŵ/'                                                         => 'W',
			'/ŵ/'                                                         => 'w',
			'/Ź|Ż|Ž|Ζ|З/'                                                 => 'Z',
			'/ź|ż|ž|ζ|з/'                                                 => 'z',
			'/Æ|Ǽ/'                                                       => 'AE',
			'/ß/'                                                         => 'ss',
			'/Ĳ/'                                                         => 'IJ',
			'/ĳ/'                                                         => 'ij',
			'/Œ/'                                                         => 'OE',
			'/ƒ/'                                                         => 'f',
			'/ξ/'                                                         => 'ks',
			'/π/'                                                         => 'p',
			'/β/'                                                         => 'v',
			'/μ/'                                                         => 'm',
			'/ψ/'                                                         => 'ps',
			'/Ё/'                                                         => 'Yo',
			'/ё/'                                                         => 'yo',
			'/Є/'                                                         => 'Ye',
			'/є/'                                                         => 'ye',
			'/Ї/'                                                         => 'Yi',
			'/Ж/'                                                         => 'Zh',
			'/ж/'                                                         => 'zh',
			'/Х/'                                                         => 'Kh',
			'/х/'                                                         => 'kh',
			'/Ц/'                                                         => 'Ts',
			'/ц/'                                                         => 'ts',
			'/Ч/'                                                         => 'Ch',
			'/ч/'                                                         => 'ch',
			'/Ш/'                                                         => 'Sh',
			'/ш/'                                                         => 'sh',
			'/Щ/'                                                         => 'Shch',
			'/щ/'                                                         => 'shch',
			'/Ъ|ъ|Ь|ь/'                                                   => '',
			'/Ю/'                                                         => 'Yu',
			'/ю/'                                                         => 'yu',
			'/Я/'                                                         => 'Ya',
			'/я/'                                                         => 'ya',
		];
		$arrayFrom = array_keys($characterList);
		$arrayTo   = array_values($characterList);
		return preg_replace($arrayFrom, $arrayTo, $str);
	}



	function scripts_positions(){
		return array(
			'after_head' => 'Después del &lt;head&gt;',
			'before_head' => 'Antes del &lt;/head&gt;',
			'after_body' => 'Después del &lt;body&gt;',
			'before_body' => 'Antes del &lt;/body&gt;',
		);
	}

	function metabox_script_callback($post) {
		$position = $post->position;
		$options = '';
		$positions = scripts_positions();
		foreach($positions as $k => $v){
			$selected = $position == $k ? 'selected': '';
			$options .= "<option value='{$k}' {$selected}>{$v}</option>";
		}
		echo "
			<div>
				<select name='position'>
					{$options}
				</select>
			</div>
		";
	}

	function save_metabox_script($post_id){
		if (is_request_method_post()){
			$posts = array('script');
			if(!array_key_exists('post_type', $_POST) || !in_array($_POST['post_type'], $posts)){
				return $post_id;
			}
			$position = '';
			extract($_POST);
			if($action == 'editpost'){
				update_post_meta($post_id, 'position', $position);
			}
		}
	}

	add_action('save_post', 'save_metabox_script');

	function manage_script_posts_columns($columns) {
		$columns = insert_extra_columns($columns, array(
			'position' => 'Posición',
		), 3);
		return $columns;
	}

	function manage_script_posts_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'position':
				$position = get_post_meta($post_id, 'position', true);
				$positions = scripts_positions();
				echo $positions[$position];
			break;
		}
	}

	add_filter('manage_script_posts_columns', 'manage_script_posts_columns');
	add_action('manage_script_posts_custom_column' , 'manage_script_posts_custom_column', 10, 2);

	function get_scripts($position = 'after_head'){
		$d = '';
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'script',
			'meta_query' => array(
				array(
					'key' => 'position',
					'value' => $position,
				),
			),
		);
		$_posts = get_posts($args);
		foreach($_posts as $_post){
			$d .= $_post->post_content;
		}
		return $d;
	}

	function scripts($position = 'after_head'){
		echo get_scripts($position);
	}

	function custom_wp_editor_settings($settings) {
		global $post_type;
		if($post_type == 'script' ) {
			$settings[ 'tinymce' ] = false;
		}
		return $settings;
	}
	
	add_filter( 'wp_editor_settings', 'custom_wp_editor_settings' );

	function get_user_id($user_id){
		return is_object($user_id) ? $user_id->ID: $user_id;
	}

	function get_post_id($post_id){
		return $post_id = is_object($post_id) ? $post_id->ID: $post_id;
	}












	add_filter('use_block_editor_for_post', '__return_false', 10);
	add_filter('use_block_editor_for_post_type', '__return_false', 10);
	add_filter('use_widgets_block_editor', '__return_false');

	function custom_register_post_types(){
		
		
		$args_script = array(
			'label' => 'Scripts',
			'labels' => array(
				'name' => 'Scripts',
				'menu_name' => 'Scripts',
				'singular_name' => 'Script',
				'add_new' => 'Agregar nuevo',
				'add_new_item' => 'Agregar nuevo script',
				'edit_item' => 'Editar Script'
			),
			'public' => false,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'menu_icon' => 'dashicons-embed-generic',
			'show_in_rest' => true,
            'supports' => array(
				'title', 
				'editor',
				'author',
			)
        );

		register_post_type('script', $args_script);

	}
		
	
	add_action( 'widgets_init', 'custom_widgets_init' );
	
	function custom_widgets_init() {
		
		$args_sidebar = array(
			'name' => 'Sidebar',
			'id' => 'sidebar',
			'class' => 'sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="widget-title mb-15"><h4 class="mb-0">',
			'after_title'   => '</h4></div>'
		);

		//register_sidebar( $args_sidebar );
		
		$args_footer_sidebar = array(
			'name' => 'Footer %d',
			'id' => 'footer-sidebar',
			'class' => 'footer-sidebar',
			'before_widget' => '<div id="%1$s" class="widget widget-footer %2$s col-md d-flex justify-content-center"><div class="widget-content">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="widget-title mb-24"><h4>',
			'after_title'   => '</h4></div>'
		);

		register_sidebars( 3, $args_footer_sidebar );
		
	}
	
	function custom_add_meta_boxes() {
		// add_meta_box('posts_gallery_id', 'Galería', 'metabox_posts_gallery_callback', 'page', 'side', 'low');
		add_meta_box('post_scripts', 'Posición', 'metabox_script_callback', 'script', 'side');
	}
	
	add_action( 'add_meta_boxes', 'custom_add_meta_boxes' );

	add_action('admin_menu', 'custom_admin_menu');

	function custom_admin_menu() {
		add_menu_page('Theme Settings', 'Theme Settings', 'administrator', 'theme-settings', 'theme_settiings');
	}

	function custom_register_settings() {
		register_setting('theme-logos', 'theme_logos' );
		register_setting('theme-contact', 'theme_contact');
		register_setting('theme-social', 'theme_social');
		register_setting('theme-texts', "theme_texts");
		register_setting('theme-links', "theme_links");
	}

	function theme_settiings() {
		require_once(get_template_path().'/includes/_theme-settings.php');
	}
	
	function init_vars(){

	}
	
	add_action('wp', 'custom_wp');
	add_action('init', 'custom_init');
	add_action('admin_init', 'custom_admin_init');

	function custom_wp() {
		init_vars();
	}

	function custom_init(){
		init_vars();
		$session_token = getSessionToken();
		if(!$session_token){
			registerNextToken();
		}
		custom_register_post_types();
	}
	
	function custom_admin_init(){
		custom_register_settings();
	}