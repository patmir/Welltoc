<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_number_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_number_theme_setup' );
	function investment_sc_number_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_number_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_number_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('investment_sc_number')) {	
	function investment_sc_number($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
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
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < investment_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(investment_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('investment_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	investment_require_shortcode('trx_number', 'investment_sc_number');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_number_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_number_reg_shortcodes');
	function investment_sc_number_reg_shortcodes() {
	
		investment_sc_map("trx_number", array(
			"title" => esc_html__("Number", 'investment'),
			"desc" => wp_kses_data( __("Insert number or any word as set separate characters", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"value" => array(
					"title" => esc_html__("Value", 'investment'),
					"desc" => wp_kses_data( __("Number or any word", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'investment'),
					"desc" => wp_kses_data( __("Select block alignment", 'investment') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => investment_get_sc_param('align')
				),
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
if ( !function_exists( 'investment_sc_number_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_number_reg_shortcodes_vc');
	function investment_sc_number_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_number",
			"name" => esc_html__("Number", 'investment'),
			"description" => wp_kses_data( __("Insert number or any word as set of separated characters", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			"class" => "trx_sc_single trx_sc_number",
			'icon' => 'icon_trx_number',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'investment'),
					"description" => wp_kses_data( __("Number or any word to separate", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'investment'),
					"description" => wp_kses_data( __("Select block alignment", 'investment') ),
					"class" => "",
					"value" => array_flip(investment_get_sc_param('align')),
					"type" => "dropdown"
				),
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('animation'),
				investment_get_vc_param('css'),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Number extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>