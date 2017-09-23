<?php
if (!function_exists('investment_theme_shortcodes_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_theme_shortcodes_setup', 1 );
	function investment_theme_shortcodes_setup() {
		add_filter('investment_filter_googlemap_styles', 'investment_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'investment_theme_shortcodes_googlemap_styles' ) ) {
	function investment_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'investment');
		$list['greyscale']	= esc_html__('Greyscale', 'investment');
		$list['inverse']	= esc_html__('Inverse', 'investment');
		$list['dark']		= esc_html__('Dark', 'investment');
		return $list;
	}
}
?>