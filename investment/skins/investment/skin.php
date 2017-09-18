<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('investment_action_skin_theme_setup')) {
	add_action( 'investment_action_init_theme', 'investment_action_skin_theme_setup', 1 );
	function investment_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('investment_filter_used_fonts',			'investment_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('investment_filter_list_fonts',			'investment_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('investment_action_add_styles',			'investment_action_skin_add_styles');
		// Add skin inline styles
		add_filter('investment_filter_add_styles_inline',		'investment_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('investment_action_add_responsive',		'investment_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('investment_filter_add_responsive_inline',	'investment_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('investment_action_add_scripts',			'investment_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('investment_action_add_scripts_inline',	'investment_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('investment_filter_compile_less',			'investment_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		investment_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'investment'),

			// Accent colors
			'accent1'				=> '#0e8fc8',       //
			'accent1_hover'			=> '#144e68',       //



			// Headers, text and links colors
			'text'					=> '#7f7f7f',       //
			'text_light'			=> '#56b0d8',       //
			'text_dark'				=> '#4c4c4c',       //
			'inverse_text'			=> '#ffffff',       //
			'inverse_light'			=> '#aeaeae',       //
			'inverse_dark'			=> '#2f9ed0',       //
			'inverse_link'			=> '#696969',       //
			'inverse_hover'			=> '#757575',       //
			
			// Whole block border and background
			'bd_color'				=> '#cccccc',       //
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#9e9e9e',       //
			'alter_light'			=> '#2d2d2d',       //
			'alter_dark'			=> '#3d3d3d',       //
			'alter_link'			=> '#999999',       //
			'alter_hover'			=> '#e7e7e7',       //
			'alter_bd_color'		=> '#efeff0',       //
			'alter_bd_hover'		=> '#166486',       //
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#f2f2f2',       //
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

        investment_add_color_scheme('green', array(

                'title'					=> esc_html__('Green', 'investment'),

                // Accent colors
                'accent1'				=> '#99c038',       //
                'accent1_hover'			=> '#688226',       //



                // Headers, text and links colors
                'text'					=> '#7f7f7f',       //
                'text_light'			=> '#abd248',       //
                'text_dark'				=> '#4c4c4c',       //
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#aeaeae',       //
                'inverse_dark'			=> '#d3f383',       //
                'inverse_link'			=> '#696969',       //
                'inverse_hover'			=> '#757575',       //

                // Whole block border and background
                'bd_color'				=> '#cccccc',       //
                'bg_color'				=> '#ffffff',
                'bg_image'				=> '',
                'bg_image_position'		=> 'left top',
                'bg_image_repeat'		=> 'repeat',
                'bg_image_attachment'	=> 'scroll',
                'bg_image2'				=> '',
                'bg_image2_position'	=> 'left top',
                'bg_image2_repeat'		=> 'repeat',
                'bg_image2_attachment'	=> 'scroll',

                // Alternative blocks (submenu items, form's fields, etc.)
                'alter_text'			=> '#9e9e9e',       //
                'alter_light'			=> '#2d2d2d',       //
                'alter_dark'			=> '#3d3d3d',       //
                'alter_link'			=> '#999999',       //
                'alter_hover'			=> '#e7e7e7',       //
                'alter_bd_color'		=> '#efeff0',       //
                'alter_bd_hover'		=> '#166486',       //
                'alter_bg_color'		=> '#f7f7f7',
                'alter_bg_hover'		=> '#f2f2f2',       //
                'alter_bg_image'			=> '',
                'alter_bg_image_position'	=> 'left top',
                'alter_bg_image_repeat'		=> 'repeat',
                'alter_bg_image_attachment'	=> 'scroll',
            )
        );


        investment_add_color_scheme('red', array(

                'title'					=> esc_html__('Red', 'investment'),

                // Accent colors
                'accent1'				=> '#ea5a38',       //
                'accent1_hover'			=> '#893b29',       //



                // Headers, text and links colors
                'text'					=> '#7f7f7f',       //
                'text_light'			=> '#f37557',       //
                'text_dark'				=> '#4c4c4c',       //
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#aeaeae',       //
                'inverse_dark'			=> '#e45837',       //
                'inverse_link'			=> '#696969',       //
                'inverse_hover'			=> '#757575',       //

                // Whole block border and background
                'bd_color'				=> '#cccccc',       //
                'bg_color'				=> '#ffffff',
                'bg_image'				=> '',
                'bg_image_position'		=> 'left top',
                'bg_image_repeat'		=> 'repeat',
                'bg_image_attachment'	=> 'scroll',
                'bg_image2'				=> '',
                'bg_image2_position'	=> 'left top',
                'bg_image2_repeat'		=> 'repeat',
                'bg_image2_attachment'	=> 'scroll',

                // Alternative blocks (submenu items, form's fields, etc.)
                'alter_text'			=> '#9e9e9e',       //
                'alter_light'			=> '#2d2d2d',       //
                'alter_dark'			=> '#3d3d3d',       //
                'alter_link'			=> '#999999',       //
                'alter_hover'			=> '#e7e7e7',       //
                'alter_bd_color'		=> '#efeff0',       //
                'alter_bd_hover'		=> '#166486',       //
                'alter_bg_color'		=> '#f7f7f7',
                'alter_bg_hover'		=> '#f2f2f2',       //
                'alter_bg_image'			=> '',
                'alter_bg_image_position'	=> 'left top',
                'alter_bg_image_repeat'		=> 'repeat',
                'alter_bg_image_attachment'	=> 'scroll',
            )
        );


        investment_add_color_scheme('orange', array(

                'title'					=> esc_html__('Orange', 'investment'),

                // Accent colors
                'accent1'				=> '#f09e0e',       //
                'accent1_hover'			=> '#805a17',       //



                // Headers, text and links colors
                'text'					=> '#7f7f7f',       //
                'text_light'			=> '#fabb4c',       //
                'text_dark'				=> '#4c4c4c',       //
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#aeaeae',       //
                'inverse_dark'			=> '#ffd894',       //
                'inverse_link'			=> '#696969',       //
                'inverse_hover'			=> '#757575',       //

                // Whole block border and background
                'bd_color'				=> '#cccccc',       //
                'bg_color'				=> '#ffffff',
                'bg_image'				=> '',
                'bg_image_position'		=> 'left top',
                'bg_image_repeat'		=> 'repeat',
                'bg_image_attachment'	=> 'scroll',
                'bg_image2'				=> '',
                'bg_image2_position'	=> 'left top',
                'bg_image2_repeat'		=> 'repeat',
                'bg_image2_attachment'	=> 'scroll',

                // Alternative blocks (submenu items, form's fields, etc.)
                'alter_text'			=> '#9e9e9e',       //
                'alter_light'			=> '#2d2d2d',       //
                'alter_dark'			=> '#3d3d3d',       //
                'alter_link'			=> '#999999',       //
                'alter_hover'			=> '#e7e7e7',       //
                'alter_bd_color'		=> '#efeff0',       //
                'alter_bd_hover'		=> '#166486',       //
                'alter_bg_color'		=> '#f7f7f7',
                'alter_bg_hover'		=> '#f2f2f2',       //
                'alter_bg_image'			=> '',
                'alter_bg_image_position'	=> 'left top',
                'alter_bg_image_repeat'		=> 'repeat',
                'alter_bg_image_attachment'	=> 'scroll',
            )
        );

		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		investment_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Source Sans Pro',
			'font-size' 	=> '4.533em',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.15em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '0.07em'
			)
		);
		investment_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Source Sans Pro',
			'font-size' 	=> '3.067em',
			'font-weight'	=> '300',
			'font-style'	=> '',
			'line-height'	=> '1.15em',
			'margin-top'	=> '0.1em',
			'margin-bottom'	=> '0.3em'
			)
		);
		investment_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Source Sans Pro',
			'font-size' 	=> '1.6em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.15em',
			'margin-top'	=> '0.6em',
			'margin-bottom'	=> '0.6em'
			)
		);
		investment_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Source Sans Pro',
			'font-size' 	=> '1.333em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.7em',
			'margin-bottom'	=> '0.9em'
			)
		);
		investment_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Source Sans Pro',
			'font-size' 	=> '1.333em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.9em',
			'margin-bottom'	=> '0.95em'
			)
		);
		investment_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Source Sans Pro',
			'font-size' 	=> '0.8em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.5em',
			'margin-bottom'	=> '1.5em'
			)
		);
		investment_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Karla',
			'font-size' 	=> '15px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.4em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		investment_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'investment'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		investment_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'investment'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		investment_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'investment'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '1.8em',
			'margin-bottom'	=> '1.8em'
			)
		);
		investment_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'investment'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		investment_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'investment'),
			'description'	=> '',
			'font-family'	=> 'Source Sans Pro',
			'font-size' 	=> '2em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '1.85em',
			'margin-bottom'	=> '1.15em'
			)
		);
		investment_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'investment'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.2em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		investment_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'investment'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('investment_filter_skin_used_fonts')) {
	//add_filter('investment_filter_used_fonts', 'investment_filter_skin_used_fonts');
	function investment_filter_skin_used_fonts($theme_fonts) {
        $theme_fonts['Source Sans Pro'] = 1;
        $theme_fonts['Karla'] = 1;
        $theme_fonts['Niconne'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('investment_filter_skin_list_fonts')) {
	//add_filter('investment_filter_list_fonts', 'investment_filter_skin_list_fonts');
	function investment_filter_skin_list_fonts($list) {
		if (!isset($list['Source Sans Pro']))	$list['Source Sans Pro'] = array(
            'family'=>'sans-serif',
            'link'=>'Source+Sans+Pro:400,300,700'
        );
        if (!isset($list['Karla']))	$list['Karla'] = array(
            'family'=>'sans-serif',
            'link'=>'Karla:400,700'
        );
        if (!isset($list['Niconne']))	$list['Niconne'] = array(
            'family'=>'cursive',
            'link'=>'Niconne'
        );
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('investment_action_skin_add_styles')) {
	//add_action('investment_action_add_styles', 'investment_action_skin_add_styles');
	function investment_action_skin_add_styles() {
		// Add stylesheet files
		investment_enqueue_style( 'investment-skin-style', investment_get_file_url('skin.css'), array(), null );
		if (file_exists(investment_get_file_dir('skin.customizer.css')))
			investment_enqueue_style( 'investment-skin-customizer-style', investment_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('investment_filter_skin_add_styles_inline')) {
	//add_filter('investment_filter_add_styles_inline', 'investment_filter_skin_add_styles_inline');
	function investment_filter_skin_add_styles_inline($custom_style) {

		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('investment_action_skin_add_responsive')) {
	//add_action('investment_action_add_responsive', 'investment_action_skin_add_responsive');
	function investment_action_skin_add_responsive() {
		$suffix = investment_param_is_off(investment_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(investment_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			investment_enqueue_style( 'theme-skin-responsive-style', investment_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('investment_filter_skin_add_responsive_inline')) {
	//add_filter('investment_filter_add_responsive_inline', 'investment_filter_skin_add_responsive_inline');
	function investment_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('investment_filter_skin_compile_less')) {
	//add_filter('investment_filter_compile_less', 'investment_filter_skin_compile_less');
	function investment_filter_skin_compile_less($files) {
		if (file_exists(investment_get_file_dir('skin.less'))) {
		 	$files[] = investment_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('investment_action_skin_add_scripts')) {
	//add_action('investment_action_add_scripts', 'investment_action_skin_add_scripts');
	function investment_action_skin_add_scripts() {
		if (file_exists(investment_get_file_dir('skin.js')))
			investment_enqueue_script( 'theme-skin-script', investment_get_file_url('skin.js'), array(), null );
		if (investment_get_theme_option('show_theme_customizer') == 'yes' && file_exists(investment_get_file_dir('skin.customizer.js')))
			investment_enqueue_script( 'theme-skin-customizer-script', investment_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('investment_action_skin_add_scripts_inline')) {
	//add_action('investment_action_add_scripts_inline', 'investment_action_skin_add_scripts_inline');
	function investment_action_skin_add_scripts_inline() {

	}
}
?>