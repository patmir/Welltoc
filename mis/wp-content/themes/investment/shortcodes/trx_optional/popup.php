<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_popup_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_popup_theme_setup' );
	function investment_sc_popup_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_popup_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_popup_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('investment_sc_popup')) {	
	function investment_sc_popup($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
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
		investment_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('investment_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	investment_require_shortcode('trx_popup', 'investment_sc_popup');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_popup_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_popup_reg_shortcodes');
	function investment_sc_popup_reg_shortcodes() {
	
		investment_sc_map("trx_popup", array(
			"title" => esc_html__("Popup window", 'investment'),
			"desc" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'investment') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Container content", 'investment'),
					"desc" => wp_kses_data( __("Content for section container", 'investment') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'investment_sc_popup_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_popup_reg_shortcodes_vc');
	function investment_sc_popup_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_popup",
			"name" => esc_html__("Popup window", 'investment'),
			"description" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_popup',
			"class" => "trx_sc_collection trx_sc_popup",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'investment'),
					"description" => wp_kses_data( __("Content for popup container", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('css'),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Popup extends INVESTMENT_VC_ShortCodeCollection {}
	}
}
?>