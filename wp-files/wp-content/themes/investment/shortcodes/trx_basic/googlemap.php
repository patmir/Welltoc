<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_googlemap_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_googlemap_theme_setup' );
	function investment_sc_googlemap_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_googlemap_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('investment_sc_googlemap')) {	
	function investment_sc_googlemap($atts, $content = null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= investment_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = investment_get_custom_option('googlemap_style');
        $api_key = investment_get_theme_option('api_google');
        investment_enqueue_script( 'googlemap', investment_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
        investment_enqueue_script( 'investment-googlemap-script', investment_get_file_url('js/core.googlemap.js'), array(), null, true );
		investment_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = investment_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => investment_get_custom_option('googlemap_title'),
				'description' => investment_strmacros(investment_get_custom_option('googlemap_description')),
				'latlng' => investment_get_custom_option('googlemap_latlng'),
				'address' => investment_get_custom_option('googlemap_address'),
				'point' => investment_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !investment_param_is_off($scheme) && !investment_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(investment_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('investment_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	investment_require_shortcode("trx_googlemap", "investment_sc_googlemap");
}


if (!function_exists('investment_sc_googlemap_marker')) {	
	function investment_sc_googlemap_marker($atts, $content = null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		investment_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => do_shortcode($content),
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : investment_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	investment_require_shortcode("trx_googlemap_marker", "investment_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_googlemap_reg_shortcodes');
	function investment_sc_googlemap_reg_shortcodes() {
	
		investment_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'investment'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'investment'),
					"desc" => wp_kses_data( __("Map zoom factor", 'investment') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'investment'),
					"desc" => wp_kses_data( __("Select map style", 'investment') ),
					"value" => "default",
					"type" => "checklist",
					"options" => investment_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'investment'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'investment') ),
					"value" => "",
					"type" => "checklist",
					"options" => investment_get_sc_param('schemes')
				),
				"width" => investment_shortcodes_width('100%'),
				"height" => investment_shortcodes_height(240),
				"top" => investment_get_sc_param('top'),
				"bottom" => investment_get_sc_param('bottom'),
				"left" => investment_get_sc_param('left'),
				"right" => investment_get_sc_param('right'),
				"id" => investment_get_sc_param('id'),
				"class" => investment_get_sc_param('class'),
				"animation" => investment_get_sc_param('animation'),
				"css" => investment_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'investment'),
				"desc" => wp_kses_data( __("Google map marker", 'investment') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'investment'),
						"desc" => wp_kses_data( __("Address of this marker", 'investment') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'investment'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'investment') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'investment'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'investment') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'investment'),
						"desc" => wp_kses_data( __("Title for this marker", 'investment') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'investment'),
						"desc" => wp_kses_data( __("Description for this marker", 'investment') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => investment_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_googlemap_reg_shortcodes_vc');
	function investment_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'investment'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'investment'),
					"description" => wp_kses_data( __("Map zoom factor", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'investment'),
					"description" => wp_kses_data( __("Map custom style", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(investment_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'investment'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'investment') ),
					"class" => "",
					"value" => array_flip(investment_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('animation'),
				investment_get_vc_param('css'),
				investment_vc_width('100%'),
				investment_vc_height(240),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'investment'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'investment') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'investment'),
					"description" => wp_kses_data( __("Address of this marker", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'investment'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'investment'),
					"description" => wp_kses_data( __("Title for this marker", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'investment'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				investment_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends INVESTMENT_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends INVESTMENT_VC_ShortCodeCollection {}
	}
}
?>