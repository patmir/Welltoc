<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_list_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_list_theme_setup' );
	function investment_sc_list_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_list_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('investment_sc_list')) {	
	function investment_sc_list($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
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
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		investment_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || investment_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('investment_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	investment_require_shortcode('trx_list', 'investment_sc_list');
}


if (!function_exists('investment_sc_list_item')) {	
	function investment_sc_list_item($atts, $content=null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		investment_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || investment_param_is_inherit($icon)) $icon = investment_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || investment_param_is_inherit($icon_color)) $icon_color = investment_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (investment_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (investment_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (investment_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('investment_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	investment_require_shortcode('trx_list_item', 'investment_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_list_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_list_reg_shortcodes');
	function investment_sc_list_reg_shortcodes() {
	
		investment_sc_map("trx_list", array(
			"title" => esc_html__("List", 'investment'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'investment') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'investment'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'investment') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => investment_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'investment'),
					"desc" => wp_kses_data( __("List items color", 'investment') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'investment'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'investment') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => investment_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'investment'),
					"desc" => wp_kses_data( __("List icons color", 'investment') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => investment_get_sc_param('top'),
				"bottom" => investment_get_sc_param('bottom'),
				"left" => investment_get_sc_param('left'),
				"right" => investment_get_sc_param('right'),
				"id" => investment_get_sc_param('id'),
				"class" => investment_get_sc_param('class'),
				"animation" => investment_get_sc_param('animation'),
				"css" => investment_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'investment'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'investment') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'investment'),
						"desc" => wp_kses_data( __("Current list item content", 'investment') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'investment'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'investment') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'investment'),
						"desc" => wp_kses_data( __("Text color for this item", 'investment') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'investment'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'investment') ),
						"value" => "",
						"type" => "icons",
						"options" => investment_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'investment'),
						"desc" => wp_kses_data( __("Icon color for this item", 'investment') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'investment'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'investment') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'investment'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'investment') ),
						"value" => "",
						"type" => "text"
					),
					"id" => investment_get_sc_param('id'),
					"class" => investment_get_sc_param('class'),
					"css" => investment_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_list_reg_shortcodes_vc');
	function investment_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'investment'),
			"description" => wp_kses_data( __("List items with specific bullets", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'investment'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'investment') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(investment_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'investment'),
					"description" => wp_kses_data( __("List items color", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'investment'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'investment'),
					"description" => wp_kses_data( __("List icons color", 'investment') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
				),
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('animation'),
				investment_get_vc_param('css'),
				investment_get_vc_param('margin_top'),
				investment_get_vc_param('margin_bottom'),
				investment_get_vc_param('margin_left'),
				investment_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'investment'),
			"description" => wp_kses_data( __("List item with specific bullet", 'investment') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'investment'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'investment'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'investment') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'investment'),
					"description" => wp_kses_data( __("Link target for the current list item", 'investment') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'investment'),
					"description" => wp_kses_data( __("Text color for this item", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'investment'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'investment'),
					"description" => wp_kses_data( __("Icon color for this item", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("List item text", 'investment'),
					"description" => wp_kses_data( __("Current list item content", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				investment_get_vc_param('id'),
				investment_get_vc_param('class'),
				investment_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends INVESTMENT_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends INVESTMENT_VC_ShortCodeContainer {}
	}
}
?>