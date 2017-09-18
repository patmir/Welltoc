<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_reviews_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_reviews_theme_setup' );
	function investment_sc_reviews_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_reviews_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_reviews_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_reviews]
*/

if (!function_exists('investment_sc_reviews')) {	
	function investment_sc_reviews($atts, $content = null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "right",
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
		$output = investment_param_is_off(investment_get_custom_option('show_sidebar_main'))
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_reviews'
							. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class ? ' '.esc_attr($class) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
						. '>'
					. trim(investment_get_reviews_placeholder())
					. '</div>'
			: '';
		return apply_filters('investment_shortcode_output', $output, 'trx_reviews', $atts, $content);
	}
	investment_require_shortcode("trx_reviews", "investment_sc_reviews");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_reviews_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_reviews_reg_shortcodes');
	function investment_sc_reviews_reg_shortcodes() {
	
		investment_sc_map("trx_reviews", array(
			"title" => esc_html__("Reviews", 'investment'),
			"desc" => wp_kses_data( __("Insert reviews block in the single post", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment", 'investment'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'investment') ),
					"divider" => true,
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
if ( !function_exists( 'investment_sc_reviews_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_reviews_reg_shortcodes_vc');
	function investment_sc_reviews_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_reviews",
			"name" => esc_html__("Reviews", 'investment'),
			"description" => wp_kses_data( __("Insert reviews block in the single post", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_reviews',
			"class" => "trx_sc_single trx_sc_reviews",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'investment'),
					"description" => wp_kses_data( __("Align counter to left, center or right", 'investment') ),
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
		
		class WPBakeryShortCode_Trx_Reviews extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>