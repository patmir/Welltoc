<?php
/**
 * Investment Framework: less manipulations
 *
 * @package	investment
 * @since	investment 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('investment_less_theme_setup2')) {
	add_action( 'investment_action_after_init_theme', 'investment_less_theme_setup2' );
	function investment_less_theme_setup2() {
		if (investment_storage_get('less_recompile')) {

			// Theme first run - compile and save css
			do_action('investment_action_compile_less');

		} else if (!is_admin() && investment_get_theme_option('debug_mode')=='yes') {

			// Regular run - if not admin - recompile only changed files
			investment_storage_set('less_check_time', true);
			do_action('investment_action_compile_less');
			investment_storage_set('less_check_time', false);

		}
	}
}

// Theme first run - compile and save css
if (!function_exists('investment_less_theme_setup3')) {
	add_action( 'after_switch_theme', 'investment_less_theme_setup3' );
	function investment_less_theme_setup3() {
		investment_storage_set('less_recompile', true);
	}
}



/* LESS
-------------------------------------------------------------------------------- */

// Recompile all LESS files
if (!function_exists('investment_compile_less')) {	
	function investment_compile_less($list = array(), $recompile=true) {

		if (!function_exists('trx_utils_less_compiler')) return false;

		$success = true;

		// Less compiler
		$less_compiler = investment_get_theme_setting('less_compiler');
		if ($less_compiler == 'no') return $success;
		
		// Generate map for the LESS-files
		$less_map = investment_get_theme_setting('less_map');
		if (investment_get_theme_option('debug_mode')=='no' || $less_compiler=='lessc') $less_map = 'no';
		
		// Get separator to split LESS-files
		$less_sep = $less_map!='no' ? '' : investment_get_theme_setting('less_separator');
	
		// Prepare skin specific LESS-vars (colors, backgrounds, logo height, etc.)
		$vars = apply_filters('investment_filter_prepare_less', '');

		// Collect .less files in parent and child themes
		if (empty($list)) {
			$list = investment_collect_files(get_template_directory(), 'less');
			if (get_template_directory() != get_stylesheet_directory()) $list = array_merge($list, investment_collect_files(get_stylesheet_directory(), 'less'));
		}
		// Prepare separate array with less utils (not compile it alone - only with main files)
		$utils = $less_map!='no' ? array() : '';
		$utils_time = 0;
		if (is_array($list) && count($list) > 0) {
			foreach($list as $k=>$file) {
				$fname = basename($file);
				if ($fname[0]=='_') {
					if ($less_map!='no')
						$utils[] = $file;
					else
						$utils .= investment_fgc($file);
					$list[$k] = '';
					$tmp = filemtime($file);
					if ($utils_time < $tmp) $utils_time = $tmp;
				}
			}
		}
		
		// Compile all .less files
		if (is_array($list) && count($list) > 0) {
			$success = trx_utils_less_compiler($list, array(
				'compiler' => $less_compiler,
				'map' => $less_map,
				'utils' => $utils,
				'utils_time' => $utils_time,
				'vars' => $vars,
				'separator' => $less_sep,
				'check_time' => investment_storage_get('less_check_time')==true,
				'compressed' => investment_get_theme_option('debug_mode')=='no'
				)
			);
		}
		
		return $success;
	}
}
?>