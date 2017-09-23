<?php
/**
 * Investment Framework: Theme options custom fields
 *
 * @package	investment
 * @since	investment 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'investment_options_custom_theme_setup' ) ) {
	add_action( 'investment_action_before_init_theme', 'investment_options_custom_theme_setup' );
	function investment_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'investment_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'investment_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'investment_options_custom_load_scripts');
	function investment_options_custom_load_scripts() {
		investment_enqueue_script( 'investment-options-custom-script',	investment_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'investment_show_custom_field' ) ) {
	function investment_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(investment_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager investment_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'investment') : esc_html__( 'Choose Image', 'investment')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'investment') : esc_html__( 'Choose Image', 'investment')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'investment') : esc_html__( 'Choose Image', 'investment')) . '</a>';
				break;
		}
		return apply_filters('investment_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>