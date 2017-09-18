<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_accordion_theme_setup')) {
    add_action( 'investment_action_before_init_theme', 'investment_sc_accordion_theme_setup' );
    function investment_sc_accordion_theme_setup() {
        add_action('investment_action_shortcodes_list', 		'investment_sc_accordion_reg_shortcodes');
        if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
            add_action('investment_action_shortcodes_list_vc','investment_sc_accordion_reg_shortcodes_vc');
    }
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_accordion counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('investment_sc_accordion')) {
    function investment_sc_accordion($atts, $content=null){
        if (investment_in_shortcode_blogger()) return '';
        extract(investment_html_decode(shortcode_atts(array(
            // Individual params
            "initial" => "1",
            "counter" => "off",
            "icon_closed" => "icon-down-open",
            "icon_opened" => "icon-cancel-1",
            // Common params
            "id" => "",
            "class" => "",
            "css" => "",
            "animation" => "",
            "top" => "",
            "bottom" => "",
            "left" => "",
            "right" => ""
        ), $atts)));
        $class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
        $initial = max(0, (int) $initial);
        investment_storage_set('sc_accordion_data', array(
                'counter' => 0,
                'show_counter' => investment_param_is_on($counter),
                'icon_closed' => empty($icon_closed) || investment_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
                'icon_opened' => empty($icon_opened) || investment_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened
            )
        );
        investment_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
        $output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_accordion'
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . (investment_param_is_on($counter) ? ' sc_show_counter' : '')
            . '"'
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . ' data-active="' . ($initial-1) . '"'
            . (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
            . '>'
            . do_shortcode($content)
            . '</div>';
        return apply_filters('investment_shortcode_output', $output, 'trx_accordion', $atts, $content);
    }
    investment_require_shortcode('trx_accordion', 'investment_sc_accordion');
}


if (!function_exists('investment_sc_accordion_item')) {
    function investment_sc_accordion_item($atts, $content=null) {
        if (investment_in_shortcode_blogger()) return '';
        extract(investment_html_decode(shortcode_atts( array(
            // Individual params
            "icon_closed" => "",
            "icon_opened" => "",
            "icon_global" => "",
            "title" => "",
            // Common params
            "id" => "",
            "class" => "",
            "css" => ""
        ), $atts)));
        investment_storage_inc_array('sc_accordion_data', 'counter');
        if (empty($icon_closed) || investment_param_is_inherit($icon_closed)) $icon_closed = investment_storage_get_array('sc_accordion_data', 'icon_closed', '', "icon-plus");
        if (empty($icon_opened) || investment_param_is_inherit($icon_opened)) $icon_opened = investment_storage_get_array('sc_accordion_data', 'icon_opened', '', "icon-minus");
        $output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
            . ' class="sc_accordion_item'
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . (investment_storage_get_array('sc_accordion_data', 'counter') % 2 == 1 ? ' odd' : ' even')
            . (investment_storage_get_array('sc_accordion_data', 'counter') == 1 ? ' first' : '')
            . '">'
            . '<h3 class="sc_accordion_title">'
            . '<span class="sc_accordion_icon '.esc_attr($icon_global).' in_begin"></span>'
            . (!investment_param_is_off($icon_closed) ? '<span class="sc_accordion_icon in_end sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
            . (!investment_param_is_off($icon_opened) ? '<span class="sc_accordion_icon in_end sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
            . (investment_storage_get_array('sc_accordion_data', 'show_counter') ? '<span class="sc_items_counter">'.(investment_storage_get_array('sc_accordion_data', 'counter')).'</span>' : '')
            . ($title)
            . '</h3>'
            . '<div class="sc_accordion_content"'
            . ($css!='' ? ' style="'.esc_attr($css).'"' : '')
            . '>'
            . do_shortcode($content)
            . '</div>'
            . '</div>';
        return apply_filters('investment_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
    }
    investment_require_shortcode('trx_accordion_item', 'investment_sc_accordion_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_accordion_reg_shortcodes' ) ) {
    //add_action('investment_action_shortcodes_list', 'investment_sc_accordion_reg_shortcodes');
    function investment_sc_accordion_reg_shortcodes() {

        investment_sc_map("trx_accordion", array(
            "title" => esc_html__("Accordion", 'investment'),
            "desc" => wp_kses( __("Accordion items", 'investment'), investment_storage_get('allowed_tags') ),
            "decorate" => true,
            "container" => false,
            "params" => array(
                "counter" => array(
                    "title" => esc_html__("Counter", 'investment'),
                    "desc" => wp_kses( __("Display counter before each accordion title", 'investment'), investment_storage_get('allowed_tags') ),
                    "value" => "off",
                    "type" => "switch",
                    "options" => investment_get_sc_param('on_off')
                ),
                "initial" => array(
                    "title" => esc_html__("Initially opened item", 'investment'),
                    "desc" => wp_kses( __("Number of initially opened item", 'investment'), investment_storage_get('allowed_tags') ),
                    "value" => 1,
                    "min" => 0,
                    "type" => "spinner"
                ),
                "icon_closed" => array(
                    "title" => esc_html__("Icon while closed",  'investment'),
                    "desc" => wp_kses( __('Select icon for the closed accordion item from Fontello icons set',  'investment'), investment_storage_get('allowed_tags') ),
                    "value" => "",
                    "type" => "icons",
                    "options" => investment_get_sc_param('icons')
                ),
                "icon_opened" => array(
                    "title" => esc_html__("Icon while opened",  'investment'),
                    "desc" => wp_kses( __('Select icon for the opened accordion item from Fontello icons set',  'investment'), investment_storage_get('allowed_tags') ),
                    "value" => "",
                    "type" => "icons",
                    "options" => investment_get_sc_param('icons')
                ),
                "icon_global" => array(
                    "title" => esc_html__("Icon of title",  'investment'),
                    "desc" => wp_kses( __('Select icon for the title accordion item from Fontello icons set',  'investment'), investment_storage_get('allowed_tags') ),
                    "value" => "",
                    "type" => "icons",
                    "options" => investment_get_sc_param('icons')
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
                "name" => "trx_accordion_item",
                "title" => esc_html__("Item", 'investment'),
                "desc" => wp_kses( __("Accordion item", 'investment'), investment_storage_get('allowed_tags') ),
                "container" => true,
                "params" => array(
                    "title" => array(
                        "title" => esc_html__("Accordion item title", 'investment'),
                        "desc" => wp_kses( __("Title for current accordion item", 'investment'), investment_storage_get('allowed_tags') ),
                        "value" => "",
                        "type" => "text"
                    ),
                    "icon_closed" => array(
                        "title" => esc_html__("Icon while closed",  'investment'),
                        "desc" => wp_kses( __('Select icon for the closed accordion item from Fontello icons set',  'investment'), investment_storage_get('allowed_tags') ),
                        "value" => "",
                        "type" => "icons",
                        "options" => investment_get_sc_param('icons')
                    ),
                    "icon_opened" => array(
                        "title" => esc_html__("Icon while opened",  'investment'),
                        "desc" => wp_kses( __('Select icon for the opened accordion item from Fontello icons set',  'investment'), investment_storage_get('allowed_tags') ),
                        "value" => "",
                        "type" => "icons",
                        "options" => investment_get_sc_param('icons')
                    ),
                    "icon_global" => array(
                        "title" => esc_html__("Icon of title",  'investment'),
                        "desc" => wp_kses( __('Select icon for the title accordion item from Fontello icons set',  'investment'), investment_storage_get('allowed_tags') ),
                        "value" => "",
                        "type" => "icons",
                        "options" => investment_get_sc_param('icons')
                    ),
                    "_content_" => array(
                        "title" => esc_html__("Accordion item content", 'investment'),
                        "desc" => wp_kses( __("Current accordion item content", 'investment'), investment_storage_get('allowed_tags') ),
                        "rows" => 4,
                        "value" => "",
                        "type" => "textarea"
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
if ( !function_exists( 'investment_sc_accordion_reg_shortcodes_vc' ) ) {
    //add_action('investment_action_shortcodes_list_vc', 'investment_sc_accordion_reg_shortcodes_vc');
    function investment_sc_accordion_reg_shortcodes_vc() {

        vc_map( array(
            "base" => "trx_accordion",
            "name" => esc_html__("Accordion", 'investment'),
            "description" => wp_kses( __("Accordion items", 'investment'), investment_storage_get('allowed_tags') ),
            "category" => esc_html__('Content', 'investment'),
            'icon' => 'icon_trx_accordion',
            "class" => "trx_sc_collection trx_sc_accordion",
            "content_element" => true,
            "is_container" => true,
            "show_settings_on_create" => false,
            "as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
            "params" => array(
                array(
                    "param_name" => "counter",
                    "heading" => esc_html__("Counter", 'investment'),
                    "description" => wp_kses( __("Display counter before each accordion title", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => array("Add item numbers before each element" => "on" ),
                    "type" => "checkbox"
                ),
                array(
                    "param_name" => "initial",
                    "heading" => esc_html__("Initially opened item", 'investment'),
                    "description" => wp_kses( __("Number of initially opened item", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => 1,
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "icon_closed",
                    "heading" => esc_html__("Icon while closed", 'investment'),
                    "description" => wp_kses( __("Select icon for the closed accordion item from Fontello icons set", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => investment_get_sc_param('icons'),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "icon_opened",
                    "heading" => esc_html__("Icon while opened", 'investment'),
                    "description" => wp_kses( __("Select icon for the opened accordion item from Fontello icons set", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => investment_get_sc_param('icons'),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "icon_global",
                    "heading" => esc_html__("Icon for title", 'investment'),
                    "description" => wp_kses( __("Select icon for the title accordion item from Fontello icons set", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => investment_get_sc_param('icons'),
                    "type" => "dropdown"
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
				[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'investment' ) . '"][/trx_accordion_item]
				[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'investment' ) . '"][/trx_accordion_item]
			',
            "custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'investment').'">'.esc_html__("Add item", 'investment').'</button>
				</div>
			',
            'js_view' => 'VcTrxAccordionView'
        ) );


        vc_map( array(
            "base" => "trx_accordion_item",
            "name" => esc_html__("Accordion item", 'investment'),
            "description" => wp_kses( __("Inner accordion item", 'investment'), investment_storage_get('allowed_tags') ),
            "show_settings_on_create" => true,
            "content_element" => true,
            "is_container" => true,
            'icon' => 'icon_trx_accordion_item',
            "as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
            "as_parent" => array('except' => 'trx_accordion'),
            "params" => array(
                array(
                    "param_name" => "title",
                    "heading" => esc_html__("Title", 'investment'),
                    "description" => wp_kses( __("Title for current accordion item", 'investment'), investment_storage_get('allowed_tags') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "icon_closed",
                    "heading" => esc_html__("Icon while closed", 'investment'),
                    "description" => wp_kses( __("Select icon for the closed accordion item from Fontello icons set", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => investment_get_sc_param('icons'),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "icon_opened",
                    "heading" => esc_html__("Icon while opened", 'investment'),
                    "description" => wp_kses( __("Select icon for the opened accordion item from Fontello icons set", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => investment_get_sc_param('icons'),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "icon_global",
                    "heading" => esc_html__("Icon for title", 'investment'),
                    "description" => wp_kses( __("Select icon for the title accordion item from Fontello icons set", 'investment'), investment_storage_get('allowed_tags') ),
                    "class" => "",
                    "value" => investment_get_sc_param('icons'),
                    "type" => "dropdown"
                ),
                investment_get_vc_param('id'),
                investment_get_vc_param('class'),
                investment_get_vc_param('css')
            ),
            'js_view' => 'VcTrxAccordionTabView'
        ) );

        class WPBakeryShortCode_Trx_Accordion extends investment_VC_ShortCodeAccordion {}
        class WPBakeryShortCode_Trx_Accordion_Item extends investment_VC_ShortCodeAccordionItem {}
    }
}
?>