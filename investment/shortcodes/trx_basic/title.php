<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('investment_sc_title_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_sc_title_theme_setup' );
	function investment_sc_title_theme_setup() {
		add_action('investment_action_shortcodes_list', 		'investment_sc_title_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('investment_sc_title')) {	
	function investment_sc_title($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= investment_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !investment_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !investment_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(investment_strpos($image, 'http:')!==false ? $image : investment_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !investment_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('investment_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	investment_require_shortcode('trx_title', 'investment_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_sc_title_reg_shortcodes' ) ) {
	//add_action('investment_action_shortcodes_list', 'investment_sc_title_reg_shortcodes');
	function investment_sc_title_reg_shortcodes() {
	
		investment_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'investment'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'investment') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'investment'),
					"desc" => wp_kses_data( __("Title content", 'investment') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'investment'),
					"desc" => wp_kses_data( __("Title type (header level)", 'investment') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'investment'),
						'2' => esc_html__('Header 2', 'investment'),
						'3' => esc_html__('Header 3', 'investment'),
						'4' => esc_html__('Header 4', 'investment'),
						'5' => esc_html__('Header 5', 'investment'),
						'6' => esc_html__('Header 6', 'investment'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'investment'),
					"desc" => wp_kses_data( __("Title style", 'investment') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'investment'),
						'underline' => esc_html__('Underline', 'investment'),
						'divider' => esc_html__('Divider', 'investment'),
						'iconed' => esc_html__('With icon (image)', 'investment')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'investment'),
					"desc" => wp_kses_data( __("Title text alignment", 'investment') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => investment_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'investment'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'investment') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'investment'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'investment') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'investment'),
						'100' => esc_html__('Thin (100)', 'investment'),
						'300' => esc_html__('Light (300)', 'investment'),
						'400' => esc_html__('Normal (400)', 'investment'),
						'600' => esc_html__('Semibold (600)', 'investment'),
						'700' => esc_html__('Bold (700)', 'investment'),
						'900' => esc_html__('Black (900)', 'investment')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'investment'),
					"desc" => wp_kses_data( __("Select color for the title", 'investment') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'investment'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'investment') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => investment_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'investment'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'investment') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => investment_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'investment'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'investment') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'investment'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'investment') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'investment'),
						'medium' => esc_html__('Medium', 'investment'),
						'large' => esc_html__('Large', 'investment')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'investment'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'investment') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'investment'),
						'left' => esc_html__('Left', 'investment')
					)
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
if ( !function_exists( 'investment_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('investment_action_shortcodes_list_vc', 'investment_sc_title_reg_shortcodes_vc');
	function investment_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'investment'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'investment') ),
			"category" => esc_html__('Content', 'investment'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'investment'),
					"description" => wp_kses_data( __("Title content", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'investment'),
					"description" => wp_kses_data( __("Title type (header level)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'investment') => '1',
						esc_html__('Header 2', 'investment') => '2',
						esc_html__('Header 3', 'investment') => '3',
						esc_html__('Header 4', 'investment') => '4',
						esc_html__('Header 5', 'investment') => '5',
						esc_html__('Header 6', 'investment') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'investment'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'investment') => 'regular',
						esc_html__('Underline', 'investment') => 'underline',
						esc_html__('Divider', 'investment') => 'divider',
						esc_html__('With icon (image)', 'investment') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'investment'),
					"description" => wp_kses_data( __("Title text alignment", 'investment') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(investment_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'investment'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'investment'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'investment') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'investment') => 'inherit',
						esc_html__('Thin (100)', 'investment') => '100',
						esc_html__('Light (300)', 'investment') => '300',
						esc_html__('Normal (400)', 'investment') => '400',
						esc_html__('Semibold (600)', 'investment') => '600',
						esc_html__('Bold (700)', 'investment') => '700',
						esc_html__('Black (900)', 'investment') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'investment'),
					"description" => wp_kses_data( __("Select color for the title", 'investment') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'investment'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'investment') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'investment'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => investment_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'investment'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'investment') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'investment'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => investment_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'investment'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'investment') ),
					"group" => esc_html__('Icon &amp; Image', 'investment'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'investment'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'investment') ),
					"group" => esc_html__('Icon &amp; Image', 'investment'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'investment') => 'small',
						esc_html__('Medium', 'investment') => 'medium',
						esc_html__('Large', 'investment') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'investment'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'investment') ),
					"group" => esc_html__('Icon &amp; Image', 'investment'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'investment') => 'top',
						esc_html__('Left', 'investment') => 'left'
					),
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends INVESTMENT_VC_ShortCodeSingle {}
	}
}
?>