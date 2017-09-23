<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_tooltip_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_tooltip_theme_setup' );
	function investment_sc_tooltip_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('investment_sc_tooltip')) {	
	function investment_sc_tooltip($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('investment_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	investment_require_shortcode('trx_tooltip', 'investment_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_tooltip_reg_shortcodes');
	function investment_sc_tooltip_reg_shortcodes() {
	
		investment_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'investment'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'investment'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'investment'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'investment') ),
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
?>