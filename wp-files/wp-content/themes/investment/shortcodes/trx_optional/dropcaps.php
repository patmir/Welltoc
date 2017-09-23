<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_dropcaps_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_dropcaps_theme_setup' );
	function investment_sc_dropcaps_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_dropcaps_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_dropcaps_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_dropcaps id="unique_id" style="1-6"]paragraph text[/trx_dropcaps]

if (!function_exists('investment_sc_dropcaps')) {	
	function investment_sc_dropcaps($atts, $content=null){
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
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
		$css .= investment_get_css_dimensions_from_values($width, $height);
		$style = min(4, max(1, $style));
		$content = do_shortcode(str_replace(array('[vc_column_text]', '[/vc_column_text]'), array('', ''), $content));
		$output = investment_substr($content, 0, 1) == '<' 
			? $content 
			: '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_dropcaps sc_dropcaps_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. '>' 
					. '<span class="sc_dropcaps_item">' . trim(investment_substr($content, 0, 1)) . '</span>' . trim(investment_substr($content, 1))
			. '</div>';
		return apply_filters('investment_shortcode_output', $output, 'trx_dropcaps', $atts, $content);
	}
	investment_require_shortcode('trx_dropcaps', 'investment_sc_dropcaps');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_dropcaps_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_dropcaps_reg_shortcodes');
	function investment_sc_dropcaps_reg_shortcodes() {
	
		investment_sc_map("trx_dropcaps", array(
			"title" => esc_html__("Dropcaps", 'investment'),
			"desc" => wp_kses_data( __("Make first letter as dropcaps", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'investment'),
					"desc" => wp_kses_data( __("Dropcaps style", 'investment') ),
					"value" => "1",
					"type" => "checklist",
					"options" => investment_get_list_styles(1, 4)
				),
				"_content_" => array(
					"title" => esc_html__("Paragraph content", 'investment'),
					"desc" => wp_kses_data( __("Paragraph with dropcaps content", 'investment') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'investment_sc_dropcaps_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_dropcaps_reg_shortcodes_vc');
	function investment_sc_dropcaps_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_dropcaps",
			"name" => esc_html__("Dropcaps", 'investment'),
			"description" => wp_kses_data( __("Make first letter of the text as dropcaps", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_dropcaps',
			"class" => "trx_sc_container trx_sc_dropcaps",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'investment'),
					"description" => wp_kses_data( __("Dropcaps style", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(investment_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Paragraph text", 'investment'),
					"description" => wp_kses_data( __("Paragraph with dropcaps content", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
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
			)
		
		) );
		
		class WPBakeryShortCode_Trx_Dropcaps extends INVESTMENT_VC_ShortCodeContainer {}
	}
}
?>