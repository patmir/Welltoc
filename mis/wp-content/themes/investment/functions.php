<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'investment_theme_setup' ) ) {
	add_action( 'investment_action_before_init_theme', 'investment_theme_setup', 1 );
	function investment_theme_setup() {

		// Register theme menus
		add_filter( 'investment_filter_add_theme_menus',		'investment_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'investment_filter_add_theme_sidebars',	'investment_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'investment_filter_importer_options',		'investment_set_importer_options' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'investment_body_classes' );

		// Set list of the theme required plugins
		investment_storage_set('required_plugins', array(
			'essgrids',
			'instagram_widget',
			'revslider',
//			'tribe_events',
			'trx_utils',
			'visual_composer',
//			'woocommerce',
			)
		);
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'investment_add_theme_menus' ) ) {
	//add_filter( 'investment_filter_add_theme_menus', 'investment_add_theme_menus' );
	function investment_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'investment');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'investment_add_theme_sidebars' ) ) {
	//add_filter( 'investment_filter_add_theme_sidebars',	'investment_add_theme_sidebars' );
	function investment_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'investment' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'investment' )
			);
			if (function_exists('investment_exists_woocommerce') && investment_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'investment' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme specified classes into the body
if ( !function_exists('investment_body_classes') ) {
	//add_filter( 'body_class', 'investment_body_classes' );
	function investment_body_classes( $classes ) {

		$classes[] = 'investment_body';
		$classes[] = 'body_style_' . trim(investment_get_custom_option('body_style'));
		$classes[] = 'body_' . (investment_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(investment_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(investment_get_custom_option('article_style'));
		
		$blog_style = investment_get_custom_option(is_singular() && !investment_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(investment_get_template_name($blog_style));
		
		$body_scheme = investment_get_custom_option('body_scheme');
		if (empty($body_scheme)  || investment_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = investment_get_custom_option('top_panel_position');
		if (!investment_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = investment_get_sidebar_class();

		if (investment_get_custom_option('show_video_bg')=='yes' && (investment_get_custom_option('video_bg_youtube_code')!='' || investment_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (investment_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;
	}
}


// Preloader load
if ( !function_exists( 'investment_preloader_load' ) ) {
    function investment_preloader_load() {
        if (($preloader=investment_get_theme_option('page_preloader'))!='') {
            $clr = investment_get_scheme_color('bg_color');
            ?>
            <style type="text/css">
                #page_preloader {
                    background-color: <?php echo esc_attr($clr); ?>;
                    background-image:url(<?php echo esc_url($preloader); ?>);
                    background-position:center; background-repeat:no-repeat;
                    position:fixed;
                    z-index:1000000;
                    left:0;
                    top:0;
                    right:0;
                    bottom:0;
                    opacity: 0.8;
                }
            </style>
        <?php
        }
    }
}

// Set theme specific importer options
if ( !function_exists( 'investment_set_importer_options' ) ) {
	//add_filter( 'investment_filter_importer_options',	'investment_set_importer_options' );
	function investment_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = investment_get_theme_option('debug_mode')=='yes';
			$options['domain_dev'] = 'investment.dv.ancorathemes.com';
			$options['domain_demo'] = 'investment.ancorathemes.com';
			$options['menus'] = array(
				'menu-main'	  => esc_html__('Main menu', 'investment'),
				'menu-user'	  => esc_html__('User menu', 'investment'),
				'menu-footer' => esc_html__('Footer menu', 'investment'),
				'menu-outer'  => esc_html__('Main menu', 'investment')
			);
			$options['file_with_attachments'] = array(				// Array with names of the attachments
//				'http://investment.ancorathemes.com/uploads.zip',		// Name of the remote file with attachments
                'http://investment.ancorathemes.com/wp-content/imports/uploads.001',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.002',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.003',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.004',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.005',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.006',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.007',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.008',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.009',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.010',
                'http://investment.ancorathemes.com/wp-content/imports/uploads.011'

			);
			$options['attachments_by_parts'] = true;				// Files above are parts of single file - large media archive. They are must be concatenated in one file before unpacking
		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files (to reduce server and DB uploads)
// Remove comments below only if your theme not work with own post types and/or taxonomies
//if (!isset($_POST['action']) || $_POST['action']!="heartbeat") {
	get_template_part('fw/loader');
//}
?>