<?php

	function jss_heading($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'text' => '',
			'url' => '',
			'text_align' => '',
			'color' => '',
			'fs' => '',
			'fw' => '',
			'font_style' => '',
			'font_family' => '',
			'line_height' => '',
			'letter_spacing' => '',
			'tag' => '',
			'tag_class' => '',
			'text_shadow' => '',
			'responsive' => '',
			'id' => '',
			'animation' => '',
		), $atts, 'jss-heading');
		return jss_template('jss-heading', $atts);
	}

	add_shortcode('jss-heading', 'jss_heading');

	function jss_text($atts, $content = null) {
		$content = wpb_js_remove_wpautop($content, true);
		$atts = shortcode_atts(array(
			'class' => '',
			'content' => $content,
			'id' => '',
			'color' => '',
			'fs' => '',
			'text_align' => '',
			'font_family' => '',
			'fw' => '',
			'line_height' => '',
			'letter_spacing' => '',
			'text_shadow' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-text');
		return jss_template('jss-text', $atts);
	}

	add_shortcode('jss-text', 'jss_text');

	function jss_empty_space($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'height' => '',
			'responsive' => '',
		), $atts, 'jss-empty-space');
		return jss_template('jss-empty-space', $atts);
	}

	add_shortcode('jss-empty-space', 'jss_empty_space');

	function jss_image($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'image' => '',
			'url' => '',
			'text_align' => '',
			'width' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-image');
		return jss_template('jss-image', $atts);
	}

	add_shortcode('jss-image', 'jss_image');

	function jss_bg_image($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'image' => '',
			'url' => '',
			'text_align' => '',
			'width' => '',
			'full_height' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-bg-image');
		return jss_template('jss-bg-image', $atts);
	}

	add_shortcode('jss-bg-image', 'jss_bg_image');

	function jss_button($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'text' => '',
			'url' => '',
			'type' => '',
			'size' => '',
			'round' => '',
			'text_align' => '',
			'block' => '',
			'width' => '',
			'bg_color' => '',
			'color' => '',
			'padding' => '',
			'border_radius' => '',
			'font_family' => '',
			'fw' => '',
			'fs' => '',
			'responsive' => '',
			'animation' => ''
		), $atts, 'jss-button');
		return jss_template('jss-button', $atts);
	}

	add_shortcode('jss-button', 'jss_button');

	function jss_video($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'video' => '',
			'image' => '',
			'animation' => '',
		), $atts, 'jss-video');
        return jss_template('jss-video', $atts);
	}
	
	add_shortcode('jss-video', 'jss_video');

	function jss_carousel($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'slides' => '',
			'autoplay' => '',
			'hide_controls' => '',
			'hide_indicators' => '',
			'is_fade' => '',
			'interval' => 5,
			'ratio' => '',
			'animation' => '',
		), $atts, 'jss-carousel');
        return jss_template('jss-carousel', $atts);
	}
	
	add_shortcode('jss-carousel', 'jss_carousel');

	function jss_separator($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'height' => '',
			'width' => '',
			'color' => '',
			'align' => '',
			'animation' => '',
		), $atts, 'jss-separator');
        return jss_template('jss-separator', $atts);
	}
	
	add_shortcode('jss-separator', 'jss_separator');

	function jss_wpcf7($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'form' => '',
			'animation' => '',
		), $atts, 'jss-wpcf7');
        return jss_template('jss-wpcf7', $atts);
	}
	
	add_shortcode('jss-wpcf7', 'jss_wpcf7');

	function jss_contact_data($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'animation' => '',
		), $atts, 'jss-contact-data');
        return jss_template('jss-contact-data', $atts);
	}
	
	add_shortcode('jss-contact-data', 'jss_contact_data');






	function jss_button_arrow($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'text' => '',
			'url' => '',
			'text_align' => '',
			'responsive' => '',
			'animation' => ''
		), $atts, 'jss-button-arrow');
		return jss_template('jss-button-arrow', $atts);
	}

	add_shortcode('jss-button-arrow', 'jss_button_arrow');

	function jss_subtitle($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'text' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-subtitle');
		return jss_template('jss-subtitle', $atts);
	}

	add_shortcode('jss-subtitle', 'jss_subtitle');

	function jss_numbers($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'texts' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-numbers');
        return jss_template('jss-numbers', $atts);
	}
	
	add_shortcode('jss-numbers', 'jss_numbers');

	function jss_products($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'texts' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-products');
        return jss_template('jss-products', $atts);
	}
	
	add_shortcode('jss-products', 'jss_products');

	function jss_images_grid($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'images' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-images-grid');
		return jss_template('jss-images-grid', $atts);
	}

	add_shortcode('jss-images-grid', 'jss_images_grid');

	function jss_services($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'texts' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-services');
        return jss_template('jss-services', $atts);
	}
	
	add_shortcode('jss-services', 'jss_services');

	function jss_services_list($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-services-list');
        return jss_template('jss-services-list', $atts);
	}
	
	add_shortcode('jss-services-list', 'jss_services_list');

	function jss_procedure($atts) {
        $atts = shortcode_atts(array(
			'class' => '',
			'texts' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-procedure');
        return jss_template('jss-procedure', $atts);
	}
	
	add_shortcode('jss-procedure', 'jss_procedure');

	function jss_patients($atts) {
		$atts = shortcode_atts(array(
			'class' => '',
			'images' => '',
			'responsive' => '',
			'animation' => '',
		), $atts, 'jss-patients');
		return jss_template('jss-patients', $atts);
	}

	add_shortcode('jss-patients', 'jss_patients');




	function custom_vc_before_init(){
		{
			$bg_color = array(
				"type" => "colorpicker",
				"heading" => 'Color de fondo',
				"param_name" => "bg_color",
				"value" => ''
			);
			$color = array(
				"type" => "colorpicker",
				"heading" => 'Color',
				"param_name" => "color",
				"value" => ''
			);
			$letter_spacing = array(
				"type" => "dropdown",
				"heading" => 'Letter spacing',
				"param_name" => "letter_spacing",
				'value' => array(
					'Seleccione' => '',
					'1px' => 'lsp-1', 
					'2px' => 'lsp-2', 
					'3px' => 'lsp-3', 
					'4px' => 'lsp-4', 
					'5px' => 'lsp-5', 
				),
				'admin_label' => false,
			);
			$tag = array(
				"type" => "dropdown",
				"heading" => 'Etiqueta del texto',
				"param_name" => "tag",
				'value' => array(
					'Seleccione' => '',
					'h1' => 'h1', 
					'h2' => 'h2', 
					'h3' => 'h3', 
					'h4' => 'h4', 
					'h5' => 'h5', 
					'h6' => 'h6', 
					'p' => 'p',  
					'div' => 'div'
				),
				'admin_label' => true,
			);
			$text_align = array(
				"type" => "align",
				"heading" => 'Alinear',
				"param_name" => "text_align",
			);
			$tag_class = array(
				"type" => "dropdown",
				"heading" => 'Clase',
				"param_name" => "tag_class",
				'value' => array(
					'Seleccione' => '',
					'h1' => 'h1', 
					'h2' => 'h2', 
					'h3' => 'h3', 
					'h4' => 'h4', 
					'h5' => 'h5', 
					'h6' => 'h6'
				),
			);
			$font_weight = array(
				"type" => "dropdown",
				"heading" => 'Ancho de fuente',
				"param_name" => "fw",
				'value' => array(
					'Seleccione' => '',
					'Thin' => 'fw-thin',
					'Light 300' => 'fw-light',
					'Normal 400' => 'fw-normal', 
					'Medium 500' => 'fw-medium', 
					'Semibold 600' => 'fw-semibold', 
					'Bold 700' => 'fw-bold',
					'Extra-bold 800' => 'fw-extrabold', 
					'Black 900' => 'fw-black',
				),
			);
			$font_style = array(
				"type" => "dropdown",
				"heading" => 'Estilo',
				"param_name" => "font_style",
				'value' => array(
					'Seleccione' => '',
					'Normal' => 'fst-normal',
					'Italic' => 'fst-italic',
				),
			);
			$fonts = get_theme_fonts('name', 'class');
			$font_family['Seleccione'] = '';
			foreach($fonts as $k => $font){
				$font_family[$k] = $font;
			}
			$font_family = array(
				"type" => "dropdown",
				"heading" => 'Tipografía',
				"param_name" => "font_family",
				'value' => $font_family,
			);
			$line_height = array(
				"type" => "dropdown",
				"heading" => "Interlineado",
				"param_name" => "line_height",
				'value' => array(
					'Seleccione' => '',
					'Line height normal' => 'lh-normal', 
					'Line height 1' => 'lh-1', 
					'Line height 1.1' => 'lh-11', 
					'Line height 1.2' => 'lh-12', 
					'Line height 1.3' => 'lh-13', 
					'Line height 1.4' => 'lh-14', 
					'Line height 1.5' => 'lh-15', 
					'Line height 2' => 'lh-2', 
				),
			);
			$align = array(
				"type" => "dropdown",
				"heading" => 'Alinear',
				"param_name" => "align",
				'value' => array(
					'Seleccione' => '',
					'Izquierda' => 'left',
					'Derecha' => 'right',
					'Centro' => 'center'
				),
			);
			$width = array(
				"type" => "range",
				"heading" => 'Ancho (px)',
				"param_name" => 'width',
				'min' => 0,
				'max' => 1000,
			);
			$font_size = array(
				"type" => "range",
				"heading" => 'Tamaño de fuente (px)',
				"param_name" => 'fs',
				'min' => 1,
				'max' => 100,
			);
			$url = array(
				"type" => "vc_link",
				"heading" => "URL",
				"param_name" => "url",
				"value" => "",
			);
			$text = array(
				"type" => "textarea",
				"heading" => "Texto",
				"param_name" => "text",
				"value" => "",
				'admin_label' => true,
			);
			$bg_image = array(
				"type" => "attach_image",
				"heading" => "Imagen fondo",
				"param_name" => "bg_image",
				"value" => "",
			);
			$image = array(
				"type" => "attach_image",
				"heading" => "Imagen",
				"param_name" => "image",
				"value" => "",
				'admin_label' => true,
			);
			$images = array(
				"type" => "attach_images",
				"heading" => "Imágenes",
				"param_name" => "images",
				"value" => "",
				'admin_label' => true,
			);
			$animation = array(
				'type' => 'animation_style',
				'heading' => 'Animación CSS',
				'param_name' => 'animation',
				'admin_label' => true,
				'description' => 'Seleccione el tipo de animación para el elemento a animar cuando "entra" en la ventana de visualización del navegador (Nota: sólo funciona en navegadores modernos).'
			);
			$class = array(
				"type" => "textfield",
				"heading" => "Clase CSS extra",
				"param_name" => "class",
				"value" => "",
				"description" => "Dar un estilo diferente a un elemento concreto - añadirle un nombre de clase y haz referencia a ella en el CSS personalizado."
			);
			$id = array(
				"type" => "textfield",
				"heading" => "ID del elemento",
				"param_name" => "id",
				"value" => "",
				'admin_label' => true,
				"description" => "Introduce el ID de la fila (Nota: asegúrate de que es único y válido según las especificaciones del w3c)."
			);
			$responsive = array(
				"type" => "dropdown",
				"heading" => 'Responsive',
				"param_name" => "responsive",
				'value' => array(
					'Seleccione' => '',
					'Ocultar en Mobile' => 'd-none d-sm-block', //576px
					'Ocultar en Tablet' => 'd-none d-md-block', //768px
					//'Ocultar en Tablet MD < 992px' => 'd-none d-lg-block', //992px
					'Ocultar en Desktop' => 'd-block d-xl-none',
					'Visible en Mobile' => 'd-block d-sm-none',
					'Visible en Tablet' => 'd-block d-md-none',
					//'Visible en MD < 992px' => 'd-block d-lg-none',
					'Visible en Desktop' => 'd-none d-xl-block',
				),
				'admin_label' => true,
			);
			$divider = array(
				"type" => "divider",
				"param_name" => 'divider',
			);
			$padding = array(
				"type" => "dimension",
				"heading" => 'Relleno (px)',
				"param_name" => "padding",
			);
			$border_radius = array(
				"type" => "dimension",
				"heading" => 'Radio del borde (px)',
				"param_name" => "border_radius",
			);
			$text_shadow = array(
				"type" => "checkbox",
				"heading" => 'Sombra',
				"param_name" => "text_shadow",
				'value' => array('Sí' => 'text-shadow'),
			);
			$ratio = array(
				"type" => "dropdown",
				"heading" => 'Relación',
				"param_name" => "ratio",
				'value' => array(
					'Seleccione' => '',
					'1x1' => '1x1',
					'4x3' => '4x3',
					'16x9' => '16x9',
					'21x9' => '21x9',
				),
			);

			$group_design = ['group' => 'Diseño'];

			$btn_values['Seleccione'] = '';
			$color_values['Seleccione'] = '';
			$_colors = get_theme_colors('', '');
			foreach($_colors as $_color){
				$btn_values[$_color->name.': '.$_color->color] = $_color->class;
				$color_values[$_color->name.': '.$_color->color] = $_color->class;
			}
			foreach($_colors as $_color){
				$btn_values['Outline '.$_color->name.': '.$_color->color] = 'outline-'.$_color->class;
			}

			$cf7 = get_posts(array(
				'posts_per_page' => -1,
				'post_type' => 'wpcf7_contact_form',
			));
			$wpcf7['Seleccione'] = '';
			foreach($cf7 as $_cf7){
				$wpcf7[$_cf7->post_title] = $_cf7->ID;
			}
		}
		$maps = array(
			array(
	            "name" => 'Encabezado',
	            "base" => "jss-heading",
	            "params" => array(
					$text,
					$url,
					$color,
					$tag,
					$tag_class,
					$font_family,
					$line_height,
					$text_align,
					$font_size,
					$font_style,
					$font_weight,
					$letter_spacing,
					$text_shadow,
					$responsive,
					$animation,
					$id,
					$class
	            )
	        ),
	        array(
	            "name" => 'Texto',
	            "base" => "jss-text",
	            "params" => array(
					array(
						"type" => "textarea_html",
						"holder" => "div",
						"heading" => "Texto",
						"param_name" => "content",
						"value" => "",
					),
					$color,
					$font_family,
					$line_height,
					$font_size,
					$text_align,
					$font_weight,
					$letter_spacing,
					$text_shadow,
					$responsive,
					$animation,
					$id,
					$class
	            )
	        ),
	        array(
	            "name" => 'Espacio vacío',
	            "base" => "jss-empty-space",
	            "params" => array(
					array(
						"type" => "range",
						"heading" => 'Altura (px)',
						"param_name" => 'height',
						'value' => 40,
						'min' => 0,
						'max' => 1000,
						'admin_label' => true,
					),
					$responsive,
					$class
	            )
	        ),
	        array(
	            "name" => 'Imagen',
	            "base" => "jss-image",
	            "params" => array(
					$image,
					$url,
					$text_align,
	                $width,
					$responsive,
					$animation,
					$class
	            )
			),
	        array(
	            "name" => 'Imagen fondo',
	            "base" => "jss-bg-image",
	            "params" => array(
					$image,
					$url,
					$text_align,
	                $width,
					array(
						"type" => "checkbox",
						"heading" => 'Altura completa',
						"param_name" => "full_height",
						'value' => array('Sí' => 'h-100'),
					),
					$responsive,
					$animation,
					$class
	            )
			),
	        array(
	            "name" => 'Botón',
	            "base" => "jss-button",
	            "params" => array(
	                $text,
					$url,
					array(
	                    "type" => "dropdown",
	                    "heading" => "Tipo",
	                    "param_name" => "type",
	                    "value" => $btn_values
					),
					array(
	                    "type" => "dropdown",
	                    "heading" => "Tamaño",
	                    "param_name" => "size",
	                    "value" => array(
							'Normal' => '',
							'Pequeño' => 'sm',
							'Grande' => 'lg'
						),
					),
					array(
						"type" => "checkbox",
						"heading" => 'Round',
						"param_name" => "round",
						'value' => array('Sí' => 'btn-round'),
					),
					$text_align,
					$font_family,
					$font_weight,
					$font_size,
					$responsive,
					$animation,
					$class,
					array(
						"type" => "checkbox",
						"heading" => 'Block (full width)',
						"param_name" => "block",
						'value' => array('Sí' => 'btn-block'),
						'group' => 'Diseño',
					),
					array_merge($padding, $group_design),
					array_merge($width, $group_design),
					array_merge($bg_color, $group_design),
					array_merge($color, $group_design),
					array_merge($border_radius, $group_design),
	            )
			),
	        array(
	            "name" => 'Video',
	            "base" => "jss-video",
	            "params" => array(
	                array(
						"type" => "textfield",
						"heading" => "YouTube URL",
						"param_name" => "video",
						"value" => "",
						'admin_label' => true,
					),
					$image,
					$animation,
					$class,
	            )
			),
	        array(
	            "name" => 'Carrusel',
	            "base" => "jss-carousel",
	            "params" => array(
					array(
						'type' => 'param_group',
						'value' => '',
						'heading' => 'Slides',
						'param_name' => 'slides',
						'params' => array(
							$image,
							$url,
						)
					),
					array(
						"type" => "checkbox",
						"heading" => 'Reproducción automática',
						"param_name" => "autoplay",
						'value' => array('Sí' => 'true'),
					),
					array(
						"type" => "checkbox",
						"heading" => 'Fade',
						"param_name" => "is_fade",
						'value' => array('Sí' => 'true'),
					),
					array(
						"type" => "checkbox",
						"heading" => 'Ocultar controles',
						"param_name" => "hide_controls",
						'value' => array('Sí' => 'true'),
					),
					array(
						"type" => "checkbox",
						"heading" => 'Ocultar indicadores',
						"param_name" => "hide_indicators",
						'value' => array('Sí' => 'true'),
					),
	                array(
	                    "type" => "textfield",
	                    "heading" => "Intervalo (segundos)",
	                    "param_name" => "interval",
	                    "value" => "",
						"description" => "Por default 5 segundos"
					),
					$ratio,
					$animation,
					$class
	            )
	        ),
	        array(
	            "name" => 'Separador',
	            "base" => "jss-separator",
	            "params" => array(
					array(
	                    "type" => "dropdown",
	                    "heading" => "Altura",
	                    "param_name" => "height",
	                    "value" => array(
							'Seleccione' => '',
							'1px' => '1px',
							'2px' => '2px',
							'3px' => '3px',
							'4px' => '4px',
							'5px' => '5px',
							'6px' => '6px',
							'7px' => '7px',
							'8px' => '8px',
							'9px' => '9px',
							'10px' => '10px',
						)
					),
					array(
	                    "type" => "dropdown",
	                    "heading" => "Ancho",
	                    "param_name" => "width",
	                    "value" => array(
							'Seleccione' => '',
							'10%' => '10%',
							'20%' => '20%',
							'30%' => '30%',
							'40%' => '40%',
							'50%' => '50%',
							'60%' => '60%',
							'70%' => '70%',
							'80%' => '80%',
							'90%' => '90%',
							'100%' => '100%',
						)
					),
					$color,
					$align,
					$animation,
					$class
	            )
			),

			array(
	            "name" => 'Contact Form 7',
	            "base" => 'jss-wpcf7',
	            "params" => array(
					array(
	                    "type" => "dropdown",
	                    "heading" => "Form",
	                    "param_name" => "form",
	                    "value" => $wpcf7,
						'admin_label' => true,
					),
					$animation,
					$class
	            )
			),
			array(
	            "name" => 'Datos de contacto',
	            "base" => 'jss-contact-data',
	            "params" => array(
					$animation,
					$class
	            )
			),

	        array(
	            "name" => 'Botón Flecha',
	            "base" => "jss-button-arrow",
	            "params" => array(
	                $text,
					$url,
					$text_align,
					$responsive,
					$animation,
					$class,
	            )
			),
	        array(
	            "name" => 'Subtítulo',
	            "base" => "jss-subtitle",
	            "params" => array(
					array(
						"type" => "textfield",
						"heading" => "Texto",
						"param_name" => "text",
						"value" => "",
						'admin_label' => true,
					),
					$responsive,
					$animation,
					$class
	            )
	        ),
			array(
	            "name" => 'Números',
	            "base" => "jss-numbers",
	            "params" => array(
					array(
						'type' => 'param_group',
						'value' => '',
						'heading' => 'Textos',
						'param_name' => 'texts',
						'params' => array(
							array(
								"type" => "textfield",
								"heading" => "Valor",
								"param_name" => "value",
								"value" => "",
							),
							array(
								"type" => "textarea",
								"heading" => "Texto",
								"param_name" => "text",
								"value" => "",
								'admin_label' => true,
							),
						)
					),
					$responsive,
					$animation,
					$class
	            )
	        ),
			array(
	            "name" => 'Productos',
	            "base" => "jss-products",
	            "params" => array(
					array(
						'type' => 'param_group',
						'value' => '',
						'heading' => 'Textos',
						'param_name' => 'texts',
						'params' => array(
							$image,
							array(
								"type" => "textfield",
								"heading" => "Título",
								"param_name" => "title",
								"value" => "",
								'admin_label' => true,
							),
							array(
								"type" => "textfield",
								"heading" => "Texto botón",
								"param_name" => "btn_text",
								"value" => "",
							),
							$url,
						)
					),
					$responsive,
					$animation,
					$class
	            )
	        ),
	        array(
	            "name" => 'Imágenes grid',
	            "base" => "jss-images-grid",
	            "params" => array(
					$images,
					$responsive,
					$animation,
					$class
	            )
	        ),
			array(
	            "name" => 'Servicios',
	            "base" => "jss-services",
	            "params" => array(
					array(
						'type' => 'param_group',
						'value' => '',
						'heading' => 'Textos',
						'param_name' => 'texts',
						'params' => array(
							$image,
							array(
								"type" => "textfield",
								"heading" => "Título",
								"param_name" => "title",
								"value" => "",
								'admin_label' => true,
							),
							array(
								"type" => "textarea",
								"heading" => "Texto",
								"param_name" => "text",
								"value" => "",
							),
							array(
								"type" => "textfield",
								"heading" => "Texto botón",
								"param_name" => "btn_text",
								"value" => "",
							),
							$url,
						)
					),
					$responsive,
					$animation,
					$class
	            )
	        ),
			array(
	            "name" => 'Servicios Lista',
	            "base" => "jss-services-list",
	            "params" => array(
					$responsive,
					$animation,
					$class
	            )
	        ),
			array(
	            "name" => 'Procedimiento',
	            "base" => "jss-procedure",
	            "params" => array(
					array(
						'type' => 'param_group',
						'value' => '',
						'heading' => 'Textos',
						'param_name' => 'texts',
						'params' => array(
							$image,
							array(
								"type" => "textfield",
								"heading" => "Título",
								"param_name" => "title",
								"value" => "",
								'admin_label' => true,
							),
							array(
								"type" => "textarea",
								"heading" => "Texto",
								"param_name" => "text",
								"value" => "",
							),
						)
					),
					$responsive,
					$animation,
					$class
	            )
	        ),
	        array(
	            "name" => 'Pacientes',
	            "base" => "jss-patients",
	            "params" => array(
					$images,
					$responsive,
					$animation,
					$class
	            )
	        ),
			
			
		);
		foreach($maps as $map){
			$map["icon"] = "vc-jss-icon";
			$map["category"] = "JSS";
			vc_map($map);
		}
	}

	add_action('vc_before_init', 'custom_vc_before_init');

	if(function_exists('vc_add_shortcode_param')){
		vc_add_shortcode_param('divider', 'vc_divider_settings_field' );
		vc_add_shortcode_param('range', 'vc_range_settings_field' );
		vc_add_shortcode_param('dimension', 'vc_dimension_settings_field' );
		vc_add_shortcode_param('align', 'vc_align_settings_field' );
	}

	function vc_divider_settings_field( $settings, $value ) {
		return '<div class="border-top"></div>';
	}

	function vc_range_settings_field( $settings, $value ) {
		$settings['responsive'] = !isset($settings['responsive']) ? true: $settings['responsive'];
		$name = esc_attr($settings['param_name']);
		$min = esc_attr($settings['min']);
		$max = esc_attr($settings['max']);
		$responsive = esc_attr($settings['responsive']);
		$value = esc_attr($value);
		$devices = vc_devices($value);
		$desktop = $devices->desktop;
		$tablet = $devices->tablet;
		$mobile = $devices->mobile;
		$id = 'range-'.random_string();

		$script_devices = '';
		$tabs_devices = '';
		$panes_devices = '';
		if($responsive){
			$script_devices = "
				var d2 = document.getElementById('{$id}-tablet').value;
				d.push(d2);
				var d3 = document.getElementById('{$id}-mobile').value;
				d.push(d3);
			";
			$tabs_devices = "
				<div class='nav justify-content-end mb-10'>
					<div class='btn-group btn-group-sm'>
						<button class='btn btn-outline-primary active' data-bs-toggle='tab' data-bs-target='#desktop-{$id}' type='button'>
							<span class='dashicons dashicons-desktop'></span>
						</button>
						<button class='btn btn-outline-primary' data-bs-toggle='tab' data-bs-target='#tablet-{$id}' type='button'>
							<span class='dashicons dashicons-tablet'></span>
						</button>
						<button class='btn btn-outline-primary' data-bs-toggle='tab' data-bs-target='#mobile-{$id}' type='button'>
							<span class='dashicons dashicons-smartphone'></span>
						</button>
					</div>
				</div>
			";
			$panes_devices = "
				<div class='tab-pane fade' id='tablet-{$id}'>
					<div class='d-flex align-items-center'>
						<input 
							id='{$id}-range-tablet'
							type='range' value='{$tablet}' 
							oninput='changeRange{$name}(this.value, \"tablet\")'
							onchange='changeRange{$name}(this.value, \"tablet\")' 
							min='{$min}' max='{$max}'>
						<input 
							id='{$id}-tablet' type='number' value='{$tablet}' 
							oninput='changeNumber{$name}(this.value, \"tablet\")'
							onchange='changeNumber{$name}(this.value, \"tablet\")' 
							class='width-75 text-center lh-1 ml-10' min='{$min}' max='{$max}' />
					</div>
				</div>
				<div class='tab-pane fade' id='mobile-{$id}'>
					<div class='d-flex align-items-center'>
						<input 
							id='{$id}-range-mobile'
							type='range' value='{$mobile}' 
							oninput='changeRange{$name}(this.value, \"mobile\")'
							onchange='changeRange{$name}(this.value, \"mobile\")' 
							min='{$min}' max='{$max}'>
						<input 
							id='{$id}-mobile' type='number' value='{$mobile}' 
							oninput='changeNumber{$name}(this.value, \"mobile\")'
							onchange='changeNumber{$name}(this.value, \"mobile\")' 
							class='width-75 text-center lh-1 ml-10' min='{$min}' max='{$max}' />
					</div>
				</div>
			";
		}

		return "
			<script>
				function changeRange{$name}(newVal, device){
					var d = [];
					document.getElementById(`{$id}-\${device}`).value = newVal;
					var d1 = document.getElementById('{$id}-desktop').value;
					d.push(d1);
					{$script_devices}
					document.getElementById('{$id}').value = d.join(',');
				}
				function changeNumber{$name}(newVal, device){
					document.getElementById(`{$id}-range-\${device}`).value = newVal;
					changeRange{$name}(newVal, device);
				}
			</script>
			<div class='vc-range-block'>

				{$tabs_devices}
				
				<div class='tab-content'>
					<div class='tab-pane fade show active' id='desktop-{$id}'>
						<div class='d-flex align-items-center'>
							<input 
								id='{$id}-range-desktop'
								type='range' value='{$desktop}' 
								oninput='changeRange{$name}(this.value, \"desktop\")'
								onchange='changeRange{$name}(this.value, \"desktop\")' 
								min='{$min}' max='{$max}'>
							<input 
								id='{$id}-desktop' type='number' value='{$desktop}' 
								oninput='changeNumber{$name}(this.value, \"desktop\")'
								onchange='changeNumber{$name}(this.value, \"desktop\")' 
								class='width-75 text-center lh-1 ml-10' min='{$min}' max='{$max}' />
						</div>
					</div>
					{$panes_devices}
				</div>
				
				<input id='{$id}' type='hidden' name='{$name}' value='{$value}' class='wpb_vc_param_value' />

			</div>
		";
	}

	function vc_align_settings_field( $settings, $value ) {
		$name = esc_attr($settings['param_name']);
		$value = esc_attr($value);
		$devices = vc_devices($value);
		$id = 'align-'.random_string();

		$options_desktop = "<option value=''>Seleccione</option>";
		$options_tablet = "<option value=''>Seleccione</option>";
		$options_mobile = "<option value=''>Seleccione</option>";
		$values = array(
			'left' => 'Izquierda',
			'center' => 'Centro',
			'right' => 'Derecha'
		);
		foreach($values as $k => $v){
			$selected_desktop = $k == $devices->desktop ? ' selected': '';
			$selected_tablet = $k == $devices->tablet ? ' selected': '';
			$selected_mobile = $k == $devices->mobile ? ' selected': '';
			$options_desktop .= "<option value='{$k}'{$selected_desktop}>{$v}</option>";
			$options_tablet .= "<option value='{$k}'{$selected_tablet}>{$v}</option>";
			$options_mobile .= "<option value='{$k}'{$selected_mobile}>{$v}</option>";
		}
		return "
			<script>
				function changeValue{$name}(){
					var d1 = document.getElementById('{$id}-desktop').value;
					var d2 = document.getElementById('{$id}-tablet').value;
					var d3 = document.getElementById('{$id}-mobile').value;
					var d = [d1, d2, d3];
					document.getElementById('{$id}').value = d.join(',');
				}
			</script>
			<div>

				<div class='nav justify-content-end mb-10'>
					<div class='btn-group btn-group-sm'>
						<button class='btn btn-outline-primary active' data-bs-toggle='tab' data-bs-target='#desktop-{$id}' type='button'>
							<span class='dashicons dashicons-desktop'></span>
						</button>
						<button class='btn btn-outline-primary' data-bs-toggle='tab' data-bs-target='#tablet-{$id}' type='button'>
							<span class='dashicons dashicons-tablet'></span>
						</button>
						<button class='btn btn-outline-primary' data-bs-toggle='tab' data-bs-target='#mobile-{$id}' type='button'>
							<span class='dashicons dashicons-smartphone'></span>
						</button>
					</div>
				</div>
				
				<div class='tab-content'>
					<div class='tab-pane fade show active' id='desktop-{$id}'>
						<select id='{$id}-desktop' onchange='changeValue{$name}()' class='mw-100'>
							{$options_desktop}
						</select>
					</div>
					<div class='tab-pane fade' id='tablet-{$id}'>
						<select id='{$id}-tablet' onchange='changeValue{$name}()' class='mw-100'>
							{$options_tablet}
						</select>
					</div>
					<div class='tab-pane fade' id='mobile-{$id}'>
						<select id='{$id}-mobile' onchange='changeValue{$name}()' class='mw-100'>
							{$options_mobile}
						</select>
					</div>
				</div>
				<input id='{$id}' type='hidden' name='{$name}' value='{$value}' class='wpb_vc_param_value' />
			</div>
		";
	}

	function vc_dimension_settings_field( $settings, $value ) {
		$name = esc_attr($settings['param_name']);
		$type = esc_attr($settings['type']);
		$value = esc_attr($value);
		$value = $value ? $value: ',,,';
		$values = explode(',', $value);
		$id = 'dimension-'.random_string();
		return "
			<script>
				function changeDimension{$name}(){
					var d1 = document.getElementById('{$id}-1').value;
					var d2 = document.getElementById('{$id}-2').value;
					var d3 = document.getElementById('{$id}-3').value;
					var d4 = document.getElementById('{$id}-4').value;
					var d = [d1, d2, d3, d4];
					document.getElementById('{$id}').value = d.join(',');
				}
			</script>
			<div class='vc-align-block'>
				<div class='vc-dimension-block input-group maxw-270'>
					<div>
						<input id='{$id}-1' type='number' value='{$values[0]}' oninput='changeDimension{$name}()' onchange='changeDimension{$name}()'>
						<label>Arriba</label>
					</div>
					<div>
						<input id='{$id}-2' type='number' value='{$values[1]}' oninput='changeDimension{$name}()' onchange='changeDimension{$name}()'>
						<label>Derecha</label>
					</div>
					<div>
						<input id='{$id}-3' type='number' value='{$values[2]}' oninput='changeDimension{$name}()' onchange='changeDimension{$name}()'>
						<label>Abajo</label>
					</div>
					<div>
						<input id='{$id}-4' type='number' value='{$values[3]}' oninput='changeDimension{$name}()' onchange='changeDimension{$name}()'>
						<label>Izquierda</label>
					</div>
				</div>
				<input id='{$id}' type='hidden' name='{$name}' value='{$value}' class='wpb_vc_param_value'/>
			</div>
		";
	}

	function vc_devices($value){
		$value = $value ? $value: ',,';
		$value = explode(',', $value);
		if(count($value) == 1){
			$value[] = '';
			$value[] = '';
		}
		$desktop = $value[0];
		$tablet = $value[1];
		$mobile = $value[2];
		$has_values = $desktop || $tablet || $mobile;
		return (object)array(
			'desktop' => $desktop,
			'tablet' => $tablet,
			'mobile' => $mobile,
			'has_values' => $has_values,
		);
	}

	function vc_dimension($dimension, $field = ''){
		$dimension = $dimension ? $dimension: ',,,';
		$dimension = explode(',', $dimension);
		$dt = $dimension[0] ? rem($dimension[0]): '';
		$dr = $dimension[1] ? rem($dimension[1]): '';
		$db = $dimension[2] ? rem($dimension[2]): '';
		$dl = $dimension[3] ? rem($dimension[3]): '';
		if($field == 'padding'){
			$dt = $dt ? "padding-top: {$dt} !important;": '';
			$dr = $dr ? "padding-right: {$dr} !important;": '';
			$db = $db ? "padding-bottom: {$db} !important;": '';
			$dl = $dl ? "padding-left: {$dl} !important;": '';
		}
		if($field == 'border-radius'){
			$dt = $dt ? "border-top-left-radius: {$dt} !important;": '';
			$dr = $dr ? "border-top-right-radius: {$dr} !important;": '';
			$db = $db ? "border-bottom-right-radius: {$db} !important;": '';
			$dl = $dl ? "border-bottom-left-radius: {$dl} !important;": '';
		}
		return $dt.$dr.$db.$dl;
	}

	function vc_devices_class($values, $field = '', $default = ''){
		$space = is_object($values) ? $values : vc_devices($values);
		$class = [];
		if($space->has_values){
			if($space->desktop){
				$v = "{$field}-lg-".$space->desktop;
				if(!$space->tablet && !$space->mobile){
					$v = "{$field}-".$space->desktop;
				}
				$class[] = $v;
			}
			if($space->tablet){
				$v = "{$field}-md-".$space->tablet;
				$class[] = $v;
			}
			if($space->mobile){
				$class[] = "{$field}-".$space->mobile;
			}
		} else {
			$class[] = $default;
		}
		return trim(implode(' ', $class));
	}













