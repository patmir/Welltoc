<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_search_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_search_theme_setup' );
	function investment_sc_search_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_search_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('investment_sc_search')) {	
	function investment_sc_search($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'investment'),
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
		if (empty($ajax)) $ajax = investment_get_theme_option('use_ajax_search');
		// Load core messages
		investment_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (investment_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'investment') : esc_attr__('Start search', 'investment')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !investment_param_is_off($scheme) && !investment_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('investment_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	investment_require_shortcode('trx_search', 'investment_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_search_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_search_reg_shortcodes');
	function investment_sc_search_reg_shortcodes() {
	
		investment_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'investment'),
			"desc" => wp_kses_data( __("Show search form", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'investment'),
					"desc" => wp_kses_data( __("Select style to display search field", 'investment') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'investment'),
						"rounded" => esc_html__('Rounded', 'investment')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'investment'),
					"desc" => wp_kses_data( __("Select search field initial state", 'investment') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'investment'),
						"opened" => esc_html__('Opened', 'investment'),
						"closed" => esc_html__('Closed', 'investment')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'investment'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'investment') ),
					"value" => esc_html__("Search &hellip;", 'investment'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'investment'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'investment') ),
					"value" => "yes",
					"options" => investment_get_sc_param('yes_no'),
					"type" => "switch"
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
if ( !function_exists( 'investment_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_search_reg_shortcodes_vc');
	function investment_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'investment'),
			"description" => wp_kses_data( __("Insert search form", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'investment'),
					"description" => wp_kses_data( __("Select style to display search field", 'investment') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'investment') => "regular",
						esc_html__('Flat', 'investment') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'investment'),
					"description" => wp_kses_data( __("Select search field initial state", 'investment') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'investment')  => "fixed",
						esc_html__('Opened', 'investment') => "opened",
						esc_html__('Closed', 'investment') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'investment'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'investment'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'investment'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'investment') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'investment') => 'yes'),
					"type" => "checkbox"
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
		
		class WPBakeryShortCode_Trx_Search extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>