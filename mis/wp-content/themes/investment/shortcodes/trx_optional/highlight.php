<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_highlight_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_highlight_theme_setup' );
	function investment_sc_highlight_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_highlight_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('investment_sc_highlight')) {	
	function investment_sc_highlight($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(investment_prepare_css_value($font_size)) . ';' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('investment_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	investment_require_shortcode('trx_highlight', 'investment_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_highlight_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_highlight_reg_shortcodes');
	function investment_sc_highlight_reg_shortcodes() {
	
		investment_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'investment'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'investment'),
					"desc" => wp_kses_data( __("Highlight type", 'investment') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'investment'),
						1 => esc_html__('Type 1', 'investment'),
						2 => esc_html__('Type 2', 'investment'),
						3 => esc_html__('Type 3', 'investment')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'investment'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'investment') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'investment'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'investment') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'investment'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'investment'),
					"desc" => wp_kses_data( __("Content for highlight", 'investment') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => investment_get_sc_param('id'),
				"class" => investment_get_sc_param('class'),
				"css" => investment_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_highlight_reg_shortcodes_vc');
	function investment_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'investment'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'investment'),
					"description" => wp_kses_data( __("Highlight type", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'investment') => 0,
							esc_html__('Type 1', 'investment') => 1,
							esc_html__('Type 2', 'investment') => 2,
							esc_html__('Type 3', 'investment') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'investment'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'investment'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'investment'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'investment'),
					"description" => wp_kses_data( __("Content for highlight", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>