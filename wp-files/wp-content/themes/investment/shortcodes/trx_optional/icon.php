<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_icon_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_icon_theme_setup' );
	function investment_sc_icon_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_icon_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('investment_sc_icon')) {	
	function investment_sc_icon($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !investment_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(investment_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || investment_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !investment_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('investment_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	investment_require_shortcode('trx_icon', 'investment_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_icon_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_icon_reg_shortcodes');
	function investment_sc_icon_reg_shortcodes() {
	
		investment_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'investment'),
			"desc" => wp_kses_data( __("Insert icon", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'investment'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'investment') ),
					"value" => "",
					"type" => "icons",
					"options" => investment_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'investment'),
					"desc" => wp_kses_data( __("Icon's color", 'investment') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'investment'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'investment') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'investment'),
						'round' => esc_html__('Round', 'investment'),
						'square' => esc_html__('Square', 'investment')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'investment'),
					"desc" => wp_kses_data( __("Icon's background color", 'investment') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'investment'),
					"desc" => wp_kses_data( __("Icon's font size", 'investment') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'investment'),
					"desc" => wp_kses_data( __("Icon font weight", 'investment') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'investment'),
						'300' => esc_html__('Light (300)', 'investment'),
						'400' => esc_html__('Normal (400)', 'investment'),
						'700' => esc_html__('Bold (700)', 'investment')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'investment'),
					"desc" => wp_kses_data( __("Icon text alignment", 'investment') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => investment_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'investment'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"top" => investment_get_sc_param('top'),
				"bottom" => investment_get_sc_param('bottom'),
				"left" => investment_get_sc_param('left'),
				"right" => investment_get_sc_param('right'),
				"id" => investment_get_sc_param('id'),
				"class" => investment_get_sc_param('class'),
				"css" => investment_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_icon_reg_shortcodes_vc');
	function investment_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'investment'),
			"description" => wp_kses_data( __("Insert the icon", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'investment'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'investment'),
					"description" => wp_kses_data( __("Icon's color", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'investment'),
					"description" => wp_kses_data( __("Background color for the icon", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'investment'),
					"description" => wp_kses_data( __("Shape of the icon background", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'investment') => 'none',
						esc_html__('Round', 'investment') => 'round',
						esc_html__('Square', 'investment') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'investment'),
					"description" => wp_kses_data( __("Icon's font size", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'investment'),
					"description" => wp_kses_data( __("Icon's font weight", 'investment') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'investment') => 'inherit',
						esc_html__('Thin (100)', 'investment') => '100',
						esc_html__('Light (300)', 'investment') => '300',
						esc_html__('Normal (400)', 'investment') => '400',
						esc_html__('Bold (700)', 'investment') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'investment'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(investment_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'investment'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('css'),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>