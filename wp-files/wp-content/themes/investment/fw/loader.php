<?php
/**
 * Investment Framework
 *
 * @package investment
 * @since investment 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'INVESTMENT_FW_DIR' ) )			define( 'INVESTMENT_FW_DIR', 'fw' );

// Theme timing
if ( ! defined( 'INVESTMENT_START_TIME' ) )		define( 'INVESTMENT_START_TIME', microtime(true));		// Framework start time
if ( ! defined( 'INVESTMENT_START_MEMORY' ) )		define( 'INVESTMENT_START_MEMORY', memory_get_usage());	// Memory usage before core loading
if ( ! defined( 'INVESTMENT_START_QUERIES' ) )	define( 'INVESTMENT_START_QUERIES', get_num_queries());	// DB queries used

// Include theme variables storage
get_template_part(INVESTMENT_FW_DIR.'/core/core.storage');

// Theme variables storage
investment_storage_set('options_prefix', 'investment');	// Used as prefix for store theme's options in the post meta and wp options
investment_storage_set('page_template', '');			// Storage for current page template name (used in the inheritance system)
investment_storage_set('widgets_args', array(			// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget_title">',
		'after_title'   => '</h3>',
	)
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'investment_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'investment_loader_theme_setup', 20 );
	function investment_loader_theme_setup() {

		investment_profiler_add_point(esc_html__('After load theme required files', 'investment'));

		// Before init theme
		do_action('investment_action_before_init_theme');

		// Load current values for main theme options
		investment_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			investment_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */
// Manual load important libraries before load all rest files
// core.strings must be first - we use investment_str...() in the investment_get_file_dir()
get_template_part(INVESTMENT_FW_DIR.'/core/core.strings');
// core.files must be first - we use investment_get_file_dir() to include all rest parts
get_template_part(INVESTMENT_FW_DIR.'/core/core.files');

// Include debug and profiler
get_template_part(investment_get_file_slug('core/core.debug.php'));

// Include custom theme files
investment_autoload_folder( 'includes' );

// Include core files
investment_autoload_folder( 'core' );

// Include theme-specific plugins and post types
investment_autoload_folder( 'plugins' );

// Include theme templates
investment_autoload_folder( 'templates' );

// Include theme widgets
investment_autoload_folder( 'widgets' );
?>