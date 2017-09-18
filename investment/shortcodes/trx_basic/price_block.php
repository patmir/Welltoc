<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_price_block_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_price_block_theme_setup' );
	function investment_sc_price_block_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_price_block_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('investment_sc_price_block')) {	
	function investment_sc_price_block($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= investment_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(investment_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !investment_param_is_off($scheme) && !investment_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('investment_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	investment_require_shortcode('trx_price_block', 'investment_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_price_block_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_price_block_reg_shortcodes');
	function investment_sc_price_block_reg_shortcodes() {
	
		investment_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'investment'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", 'investment'),
					"desc" => wp_kses_data( __("Select style for this price block", 'investment') ),
					"value" => 1,
					"options" => investment_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'investment'),
					"desc" => wp_kses_data( __("Block title", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'investment'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'investment'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'investment'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'investment') ),
					"value" => "",
					"type" => "icons",
					"options" => investment_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'investment'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'investment') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'investment'),
					"desc" => wp_kses_data( __("Currency character", 'investment') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'investment'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'investment'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'investment') ),
					"value" => "",
					"type" => "checklist",
					"options" => investment_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'investment'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'investment') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => investment_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'investment'),
					"desc" => wp_kses_data( __("Description for this price block", 'investment') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'investment_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_price_block_reg_shortcodes_vc');
	function investment_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'investment'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'investment'),
					"desc" => wp_kses_data( __("Select style of this price block", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(investment_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'investment'),
					"description" => wp_kses_data( __("Block title", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'investment'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'investment'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'investment'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'investment') ),
					"class" => "",
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'investment'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'investment') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'investment'),
					"description" => wp_kses_data( __("Currency character", 'investment') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'investment'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'investment'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'investment') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'investment'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					"value" => array_flip(investment_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'investment'),
					"description" => wp_kses_data( __("Align price to left or right side", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(investment_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'investment'),
					"description" => wp_kses_data( __("Description for this price block", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>