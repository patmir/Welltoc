<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'investment_shortcodes_is_used' ) ) {
	function investment_shortcodes_is_used() {
		return investment_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && investment_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('investment_vc_is_frontend') && investment_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'investment_shortcodes_width' ) ) {
	function investment_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'investment'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'investment_shortcodes_height' ) ) {
	function investment_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'investment'),
			"desc" => wp_kses_data( __("Width and height of the element", 'investment') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'investment_get_sc_param' ) ) {
	function investment_get_sc_param($prm) {
		return investment_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'investment_set_sc_param' ) ) {
	function investment_set_sc_param($prm, $val) {
		investment_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'investment_sc_map' ) ) {
	function investment_sc_map($sc_name, $sc_settings) {
		investment_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'investment_sc_map_after' ) ) {
	function investment_sc_map_after($after, $sc_name, $sc_settings='') {
		investment_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'investment_sc_map_before' ) ) {
	function investment_sc_map_before($before, $sc_name, $sc_settings='') {
		investment_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'investment_compare_sc_title' ) ) {
	function investment_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'investment_shortcodes_settings_theme_setup' ) ) {
//	if ( investment_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'investment_action_before_init_theme', 'investment_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'investment_action_after_init_theme', 'investment_shortcodes_settings_theme_setup' );
	function investment_shortcodes_settings_theme_setup() {
		if (investment_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = investment_storage_get('registered_templates');
			ksort($tmp);
			investment_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			investment_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'investment'),
					"desc" => wp_kses_data( __("ID for current element", 'investment') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'investment'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'investment'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'investment') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'investment'),
					'ol'	=> esc_html__('Ordered', 'investment'),
					'iconed'=> esc_html__('Iconed', 'investment')
				),

				'yes_no'	=> investment_get_list_yesno(),
				'on_off'	=> investment_get_list_onoff(),
				'dir' 		=> investment_get_list_directions(),
				'align'		=> investment_get_list_alignments(),
				'float'		=> investment_get_list_floats(),
				'hpos'		=> investment_get_list_hpos(),
				'show_hide'	=> investment_get_list_showhide(),
				'sorting' 	=> investment_get_list_sortings(),
				'ordering' 	=> investment_get_list_orderings(),
				'shapes'	=> investment_get_list_shapes(),
				'sizes'		=> investment_get_list_sizes(),
				'sliders'	=> investment_get_list_sliders(),
				'controls'	=> investment_get_list_controls(),
				'categories'=> investment_get_list_categories(),
				'columns'	=> investment_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), investment_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), investment_get_list_icons()),
				'locations'	=> investment_get_list_dedicated_locations(),
				'filters'	=> investment_get_list_portfolio_filters(),
				'formats'	=> investment_get_list_post_formats_filters(),
				'hovers'	=> investment_get_list_hovers(true),
				'hovers_dir'=> investment_get_list_hovers_directions(true),
				'schemes'	=> investment_get_list_color_schemes(true),
				'animations'		=> investment_get_list_animations_in(),
				'margins' 			=> investment_get_list_margins(true),
				'blogger_styles'	=> investment_get_list_templates_blogger(),
				'forms'				=> investment_get_list_templates_forms(),
				'posts_types'		=> investment_get_list_posts_types(),
				'googlemap_styles'	=> investment_get_list_googlemap_styles(),
				'field_types'		=> investment_get_list_field_types(),
				'label_positions'	=> investment_get_list_label_positions()
				)
			);

			// Common params
			investment_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'investment'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'investment') ),
				"value" => "none",
				"type" => "select",
				"options" => investment_get_sc_param('animations')
				)
			);
			investment_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'investment'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => investment_get_sc_param('margins')
				)
			);
			investment_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'investment'),
				"value" => "inherit",
				"type" => "select",
				"options" => investment_get_sc_param('margins')
				)
			);
			investment_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'investment'),
				"value" => "inherit",
				"type" => "select",
				"options" => investment_get_sc_param('margins')
				)
			);
			investment_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'investment'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'investment') ),
				"value" => "inherit",
				"type" => "select",
				"options" => investment_get_sc_param('margins')
				)
			);

			investment_storage_set('sc_params', apply_filters('investment_filter_shortcodes_params', investment_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			investment_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('investment_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = investment_storage_get('shortcodes');
			uasort($tmp, 'investment_compare_sc_title');
			investment_storage_set('shortcodes', $tmp);
		}
	}
}
?>