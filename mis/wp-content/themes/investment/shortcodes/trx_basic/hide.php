<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_hide_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_hide_theme_setup' );
	function investment_sc_hide_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('investment_sc_hide')) {	
	function investment_sc_hide($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('investment_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	investment_require_shortcode('trx_hide', 'investment_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_hide_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_hide_reg_shortcodes');
	function investment_sc_hide_reg_shortcodes() {
	
		investment_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'investment'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'investment'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'investment'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'investment') ),
					"value" => "yes",
					"size" => "small",
					"options" => investment_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>