<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_infobox_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_infobox_theme_setup' );
	function investment_sc_infobox_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_infobox_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('investment_sc_infobox')) {	
	function investment_sc_infobox($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
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
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-cogs';
			else if ($style=='success')
				$icon = 'icon-ok';
			else if ($style=='error')
				$icon = 'icon-error-message-icon';
			else if ($style=='info')
				$icon = 'icon-info-circled';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (investment_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !investment_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('investment_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	investment_require_shortcode('trx_infobox', 'investment_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_infobox_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_infobox_reg_shortcodes');
	function investment_sc_infobox_reg_shortcodes() {
	
		investment_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'investment'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'investment'),
					"desc" => wp_kses_data( __("Infobox style", 'investment') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'investment'),
						'info' => esc_html__('Info', 'investment'),
						'success' => esc_html__('Success', 'investment'),
						'error' => esc_html__('Error', 'investment')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'investment'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'investment') ),
					"value" => "no",
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'investment'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'investment') ),
					"value" => "",
					"type" => "icons",
					"options" => investment_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'investment'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'investment') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'investment'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'investment') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'investment'),
					"desc" => wp_kses_data( __("Content for infobox", 'investment') ),
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
				"animation" => investment_get_sc_param('animation'),
				"css" => investment_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_infobox_reg_shortcodes_vc');
	function investment_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'investment'),
			"description" => wp_kses_data( __("Box with info or error message", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'investment'),
					"description" => wp_kses_data( __("Infobox style", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'investment') => 'regular',
							esc_html__('Info', 'investment') => 'info',
							esc_html__('Success', 'investment') => 'success',
							esc_html__('Error', 'investment') => 'error',
							esc_html__('Result', 'investment') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'investment'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'investment') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'investment'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'investment') ),
					"class" => "",
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'investment'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'investment'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Message text", 'investment'),
					"description" => wp_kses_data( __("Message for the infobox", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('animation'),
				investment_get_vc_param('css'),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends INVESTMENT_VC_ShortCodeContainer {}
	}
}
?>