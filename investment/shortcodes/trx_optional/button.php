<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_button_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_button_theme_setup' );
	function investment_sc_button_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_button_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('investment_sc_button')) {	
	function investment_sc_button($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= investment_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (investment_param_is_on($popup)) investment_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (investment_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('investment_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	investment_require_shortcode('trx_button', 'investment_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_button_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_button_reg_shortcodes');
	function investment_sc_button_reg_shortcodes() {
	
		investment_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'investment'),
			"desc" => wp_kses_data( __("Button with link", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'investment'),
					"desc" => wp_kses_data( __("Button caption", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'investment'),
					"desc" => wp_kses_data( __("Select button's shape", 'investment') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'investment'),
						'round' => esc_html__('Round', 'investment')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'investment'),
					"desc" => wp_kses_data( __("Select button's style", 'investment') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'investment'),
						'border' => esc_html__('Border', 'investment')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'investment'),
					"desc" => wp_kses_data( __("Select button's size", 'investment') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'investment'),
						'medium' => esc_html__('Medium', 'investment'),
						'large' => esc_html__('Large', 'investment')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'investment'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'investment') ),
					"value" => "",
					"type" => "icons",
					"options" => investment_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'investment'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'investment') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'investment'),
					"desc" => wp_kses_data( __("Any color for button's background", 'investment') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'investment'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'investment') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => investment_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'investment'),
					"desc" => wp_kses_data( __("URL for link on button click", 'investment') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'investment'),
					"desc" => wp_kses_data( __("Target for link on button click", 'investment') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'investment'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'investment') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'investment'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'investment') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => investment_shortcodes_width(),
				"height" => investment_shortcodes_height(),
				"top" => investment_get_sc_param('top'),
				"bottom" => investment_get_sc_param('bottom'),
				"left" => investment_get_sc_param('left'),
				"right" => investment_get_sc_param('right'),
				"id" => investment_get_sc_param('id'),
				"class" => investment_get_sc_param('class'),
				"animation" => investment_get_sc_param('animation'),
				"css" => investment_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_button_reg_shortcodes_vc');
	function investment_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'investment'),
			"description" => wp_kses_data( __("Button with link", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'investment'),
					"description" => wp_kses_data( __("Button caption", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'investment'),
					"description" => wp_kses_data( __("Select button's shape", 'investment') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'investment') => 'square',
						esc_html__('Round', 'investment') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'investment'),
					"description" => wp_kses_data( __("Select button's style", 'investment') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'investment') => 'filled',
                        esc_html__('Filled with white hover', 'investment') => 'filled white-hover',
						esc_html__('Border', 'investment') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'investment'),
					"description" => wp_kses_data( __("Select button's size", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'investment') => 'small',
						esc_html__('Medium', 'investment') => 'medium',
						esc_html__('Large', 'investment') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'investment'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'investment') ),
					"class" => "",
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'investment'),
					"description" => wp_kses_data( __("Any color for button's caption", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'investment'),
					"description" => wp_kses_data( __("Any color for button's background", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'investment'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'investment') ),
					"class" => "",
					"value" => array_flip(investment_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'investment'),
					"description" => wp_kses_data( __("URL for the link on button click", 'investment') ),
					"class" => "",
					"group" => esc_html__('Link', 'investment'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'investment'),
					"description" => wp_kses_data( __("Target for the link on button click", 'investment') ),
					"class" => "",
					"group" => esc_html__('Link', 'investment'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'investment'),
					"description" => wp_kses_data( __("Open link target in popup window", 'investment') ),
					"class" => "",
					"group" => esc_html__('Link', 'investment'),
					"value" => array(esc_html__('Open in popup', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'investment'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'investment') ),
					"class" => "",
					"group" => esc_html__('Link', 'investment'),
					"value" => "",
					"type" => "textfield"
				),
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('animation'),
				investment_get_vc_param('css'),
				investment_vc_width(),
				investment_vc_height(),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>