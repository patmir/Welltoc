<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_emailer_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_emailer_theme_setup' );
	function investment_sc_emailer_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_emailer_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_emailer_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_emailer group=""]

if (!function_exists('investment_sc_emailer')) {	
	function investment_sc_emailer($atts, $content = null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "yes",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= investment_get_css_dimensions_from_values($width, $height);
		// Load core messages
		investment_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (investment_param_is_on($open) ? ' sc_emailer_opened' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
					. '>'
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="'.esc_attr__('Please, enter you email address.', 'investment').'">'
				. '<a href="#" class="sc_emailer_button icon-mail" title="'.esc_attr__('Submit', 'investment').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'investment')).'"></a>'
				. '</form>'
			. '</div>';
		return apply_filters('investment_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	investment_require_shortcode("trx_emailer", "investment_sc_emailer");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_emailer_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_emailer_reg_shortcodes');
	function investment_sc_emailer_reg_shortcodes() {
	
		investment_sc_map("trx_emailer", array(
			"title" => esc_html__("E-mail collector", 'investment'),
			"desc" => wp_kses_data( __("Collect the e-mail address into specified group", 'investment') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"group" => array(
					"title" => esc_html__("Group", 'investment'),
					"desc" => wp_kses_data( __("The name of group to collect e-mail address", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"open" => array(
					"title" => esc_html__("Open", 'investment'),
					"desc" => wp_kses_data( __("Initially open the input field on show object", 'investment') ),
					"divider" => true,
					"value" => "yes",
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'investment'),
					"desc" => wp_kses_data( __("Align object to left, center or right", 'investment') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => investment_get_sc_param('align')
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
if ( !function_exists( 'investment_sc_emailer_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_emailer_reg_shortcodes_vc');
	function investment_sc_emailer_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_emailer",
			"name" => esc_html__("E-mail collector", 'investment'),
			"description" => wp_kses_data( __("Collect e-mails into specified group", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_emailer',
			"class" => "trx_sc_single trx_sc_emailer",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "group",
					"heading" => esc_html__("Group", 'investment'),
					"description" => wp_kses_data( __("The name of group to collect e-mail address", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Opened", 'investment'),
					"description" => wp_kses_data( __("Initially open the input field on show object", 'investment') ),
					"class" => "",
					"value" => array(esc_html__('Initially opened', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'investment'),
					"description" => wp_kses_data( __("Align field to left, center or right", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(investment_get_sc_param('align')),
					"type" => "dropdown"
				),
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
		
		class WPBakeryShortCode_Trx_Emailer extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>