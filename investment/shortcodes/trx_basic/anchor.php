<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_anchor_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_anchor_theme_setup' );
	function investment_sc_anchor_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_anchor_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('investment_sc_anchor')) {	
	function investment_sc_anchor($atts, $content = null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(investment_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (investment_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('investment_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	investment_require_shortcode("trx_anchor", "investment_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_anchor_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_anchor_reg_shortcodes');
	function investment_sc_anchor_reg_shortcodes() {
	
		investment_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'investment'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'investment'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'investment') ),
					"value" => "",
					"type" => "icons",
					"options" => investment_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'investment'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'investment'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'investment'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'investment'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'investment') ),
					"value" => "no",
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"id" => investment_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_anchor_reg_shortcodes_vc');
	function investment_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'investment'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'investment'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'investment') ),
					"class" => "",
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'investment'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'investment'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'investment'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'investment'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'investment') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				investment_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>