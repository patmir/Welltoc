<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_socials_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_socials_theme_setup' );
	function investment_sc_socials_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_socials_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('investment_sc_socials')) {	
	function investment_sc_socials($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => investment_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		investment_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? investment_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) investment_storage_set_array('sc_social_data', 'icons', $list);
		} else if (investment_param_is_off($custom))
			$content = do_shortcode($content);
		if (investment_storage_get_array('sc_social_data', 'icons')===false) investment_storage_set_array('sc_social_data', 'icons', investment_get_custom_option('social_icons'));
		$output = investment_prepare_socials(investment_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('investment_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	investment_require_shortcode('trx_socials', 'investment_sc_socials');
}


if (!function_exists('investment_sc_social_item')) {	
	function investment_sc_social_item($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = investment_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(investment_get_socials_dir($name.'.png')))
					$icon = investment_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (investment_storage_get_array('sc_social_data', 'icons')===false) investment_storage_set_array('sc_social_data', 'icons', array());
			investment_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	investment_require_shortcode('trx_social_item', 'investment_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_socials_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_socials_reg_shortcodes');
	function investment_sc_socials_reg_shortcodes() {
	
		investment_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'investment'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'investment') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'investment'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'investment') ),
					"value" => investment_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'investment'),
						'images' => esc_html__('Images', 'investment')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'investment'),
					"desc" => wp_kses_data( __("Size of the icons", 'investment') ),
					"value" => "small",
					"options" => investment_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'investment'),
					"desc" => wp_kses_data( __("Shape of the icons", 'investment') ),
					"value" => "square",
					"options" => investment_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'investment'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'investment') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'investment'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'investment') ),
					"divider" => true,
					"value" => "no",
					"options" => investment_get_sc_param('yes_no'),
					"type" => "switch"
				),
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'investment'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'investment') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'investment'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'investment') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'investment'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'investment') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'investment'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'investment') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_socials_reg_shortcodes_vc');
	function investment_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'investment'),
			"description" => wp_kses_data( __("Custom social icons", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'investment'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'investment') ),
					"class" => "",
					"std" => investment_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'investment') => 'icons',
						esc_html__('Images', 'investment') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'investment'),
					"description" => wp_kses_data( __("Size of the icons", 'investment') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(investment_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'investment'),
					"description" => wp_kses_data( __("Shape of the icons", 'investment') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(investment_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'investment'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'investment'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'investment') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('animation'),
				investment_get_vc_param('css'),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'investment'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'investment') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'investment'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'investment'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'investment'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends INVESTMENT_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>