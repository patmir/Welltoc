<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('investment_instagram_widget_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_instagram_widget_theme_setup', 1 );
	function investment_instagram_widget_theme_setup() {
		if (investment_exists_instagram_widget()) {
			add_action( 'investment_action_add_styles', 						'investment_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'investment_filter_importer_required_plugins',		'investment_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'investment_filter_required_plugins',					'investment_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'investment_exists_instagram_widget' ) ) {
	function investment_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'investment_instagram_widget_required_plugins' ) ) {
	//add_filter('investment_filter_required_plugins',	'investment_instagram_widget_required_plugins');
	function investment_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', investment_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'investment_instagram_widget_frontend_scripts' ) ) {
	//add_action( 'investment_action_add_styles', 'investment_instagram_widget_frontend_scripts' );
	function investment_instagram_widget_frontend_scripts() {
		if (file_exists(investment_get_file_dir('css/plugin.instagram-widget.css')))
			investment_enqueue_style( 'investment-plugin.instagram-widget-style',  investment_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'investment_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'investment_filter_importer_required_plugins',	'investment_instagram_widget_importer_required_plugins', 10, 2 );
	function investment_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('instagram_widget', investment_storage_get('required_plugins')) && !investment_exists_instagram_widget() )
		if (investment_strpos($list, 'instagram_widget')!==false && !investment_exists_instagram_widget() )
			$not_installed .= '<br>WP Instagram Widget';
		return $not_installed;
	}
}
?>