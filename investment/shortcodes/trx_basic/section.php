<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_section_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_section_theme_setup' );
	function investment_sc_section_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_section_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

investment_storage_set('sc_section_dedicated', '');

if (!function_exists('investment_sc_section')) {	
	function investment_sc_section($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'investment'),
			"link" => '',
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = investment_get_scheme_color('bg');
			$rgb = investment_hex2rgb($bg_color);
		}
	
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(investment_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!investment_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(investment_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !investment_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = investment_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && investment_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = investment_prepare_css_value($width);
		$height = investment_prepare_css_value($height);
	
		if ((!investment_param_is_off($scroll) || !investment_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!investment_param_is_off($scroll)) investment_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !investment_param_is_off($scheme) && !investment_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (investment_param_is_on($scroll) && !investment_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || investment_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (investment_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (investment_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (investment_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (investment_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(investment_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_section_title sc_item_title">' . trim(investment_strmacros($title)) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(investment_strmacros($description)) . '</div>' : '')
							. do_shortcode($content)
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.investment_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (investment_param_is_on($pan) ? '</div>' : '')
					. (investment_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!investment_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || investment_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (investment_param_is_on($dedicated)) {
			if (investment_storage_get('sc_section_dedicated')=='') {
				investment_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('investment_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	investment_require_shortcode('trx_section', 'investment_sc_section');
	investment_require_shortcode('trx_block', 'investment_sc_section');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_section_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_section_reg_shortcodes');
	function investment_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'investment'),
			"desc" => wp_kses_data( __("Container for any block ([section] analog - to enable nesting)", 'investment') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'investment'),
					"desc" => wp_kses_data( __("Title for the block", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'investment'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'investment'),
					"desc" => wp_kses_data( __("Short description for the block", 'investment') ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'investment'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'investment'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'investment'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'investment') ),
					"value" => "no",
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'investment'),
					"desc" => wp_kses_data( __("Select block alignment", 'investment') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => investment_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'investment'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'investment') ),
					"value" => "none",
					"type" => "checklist",
					"options" => investment_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'investment'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'investment') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'investment'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'investment') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'investment'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'investment') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => investment_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'investment'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'investment') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => investment_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'investment'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'investment') ),
					"value" => "",
					"type" => "checklist",
					"options" => investment_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'investment'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'investment') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'investment'),
					"desc" => wp_kses_data( __("Any background color for this section", 'investment') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'investment'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'investment') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'investment'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'investment') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'investment'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'investment') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'investment'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'investment') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'investment'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'investment') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => investment_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'investment'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'investment'),
					"desc" => wp_kses_data( __("Font weight of the text", 'investment') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'investment'),
						'300' => esc_html__('Light (300)', 'investment'),
						'400' => esc_html__('Normal (400)', 'investment'),
						'700' => esc_html__('Bold (700)', 'investment')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'investment'),
					"desc" => wp_kses_data( __("Content for section container", 'investment') ),
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
		);
		investment_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'investment');
		$sc["desc"] = esc_html__("Container for any section ([block] analog - to enable nesting)", 'investment');
		investment_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_section_reg_shortcodes_vc');
	function investment_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'investment'),
			"description" => wp_kses_data( __("Container for any block ([section] analog - to enable nesting)", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'investment'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'investment'),
					"description" => wp_kses_data( __("Select block alignment", 'investment') ),
					"class" => "",
					"value" => array_flip(investment_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'investment'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(investment_get_sc_param('columns')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'investment'),
					"description" => wp_kses_data( __("Title for the block", 'investment') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'investment'),
					"description" => wp_kses_data( __("Subtitle for the block", 'investment') ),
					"group" => esc_html__('Captions', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'investment'),
					"description" => wp_kses_data( __("Description for the block", 'investment') ),
					"group" => esc_html__('Captions', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'investment'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'investment') ),
					"group" => esc_html__('Captions', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'investment'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'investment') ),
					"group" => esc_html__('Captions', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'investment'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'investment') ),
					"group" => esc_html__('Scroll', 'investment'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'investment'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'investment') ),
					"group" => esc_html__('Scroll', 'investment'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'investment'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'investment'),
					"value" => array_flip(investment_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'investment'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'investment') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'investment'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(investment_get_sc_param('controls')),
					"type" => "dropdown"
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
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'investment'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'investment'),
					"description" => wp_kses_data( __("Any background color for this section", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'investment'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'investment'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'investment') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'investment'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'investment'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'investment'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'investment') ),
					"group" => esc_html__('Colors and Images', 'investment'),
					"class" => "",
					'dependency' => array(
						'element' => array('bg_color','bg_texture','bg_image'),
						'not_empty' => true
					),
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'investment') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'investment'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'investment'),
					"description" => wp_kses_data( __("Font weight of the text", 'investment') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'investment') => 'inherit',
						esc_html__('Thin (100)', 'investment') => '100',
						esc_html__('Light (300)', 'investment') => '300',
						esc_html__('Normal (400)', 'investment') => '400',
						esc_html__('Bold (700)', 'investment') => '700'
					),
					"type" => "dropdown"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Container content", 'investment'),
					"description" => wp_kses_data( __("Content for section container", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'investment');
		$sc["description"] = wp_kses_data( __("Container for any section ([block] analog - to enable nesting)", 'investment') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends INVESTMENT_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends INVESTMENT_VC_ShortCodeCollection {}
	}
}
?>