<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_br_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_br_theme_setup' );
	function investment_sc_br_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_br_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('investment_sc_br')) {	
	function investment_sc_br($atts, $content = null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('investment_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	investment_require_shortcode("trx_br", "investment_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_br_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_br_reg_shortcodes');
	function investment_sc_br_reg_shortcodes() {
	
		investment_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'investment'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'investment'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'investment') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'investment'),
						'left' => esc_html__('Left', 'investment'),
						'right' => esc_html__('Right', 'investment'),
						'both' => esc_html__('Both', 'investment')
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_br_reg_shortcodes_vc');
	function investment_sc_br_reg_shortcodes_vc() {
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", 'investment'),
			"description" => wp_kses_data( __("Line break or Clear Floating", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", 'investment'),
					"description" => wp_kses_data( __("Select clear side (if need)", 'investment') ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'investment') => 'none',
						esc_html__('Left', 'investment') => 'left',
						esc_html__('Right', 'investment') => 'right',
						esc_html__('Both', 'investment') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends INVESTMENT_VC_ShortCodeSingle {}
*/
	}
}
?>