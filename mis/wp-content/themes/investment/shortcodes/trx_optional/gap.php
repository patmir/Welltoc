<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_gap_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_gap_theme_setup' );
	function investment_sc_gap_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_gap_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('investment_sc_gap')) {	
	function investment_sc_gap($atts, $content = null) {
		if (investment_in_shortcode_blogger()) return '';
		$output = investment_gap_start() . do_shortcode($content) . investment_gap_end();
		return apply_filters('investment_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	investment_require_shortcode("trx_gap", "investment_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_gap_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_gap_reg_shortcodes');
	function investment_sc_gap_reg_shortcodes() {
	
		investment_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'investment'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'investment') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'investment'),
					"desc" => wp_kses_data( __("Gap inner content", 'investment') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_gap_reg_shortcodes_vc');
	function investment_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'investment'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'investment') ),
			"category" => esc_html__('Structure', 'investment'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", 'investment'),
					"description" => wp_kses_data( __("Gap inner content", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends INVESTMENT_VC_ShortCodeCollection {}
	}
}
?>