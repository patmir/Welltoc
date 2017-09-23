<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'investment_template_header_3_theme_setup' ) ) {
	add_action( 'investment_action_before_init_theme', 'investment_template_header_3_theme_setup', 1 );
	function investment_template_header_3_theme_setup() {
		investment_add_template(array(
			'layout' => 'header_3',
			'mode'   => 'header',
			'title'  => esc_html__('Header 3', 'investment'),
			'icon'   => investment_get_file_url('templates/headers/images/3.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'investment_template_header_3_output' ) ) {
	function investment_template_header_3_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_3 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_3 top_panel_position_<?php echo esc_attr(investment_get_custom_option('top_panel_position')); ?>">
			
			<?php if (investment_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						investment_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('contact_phone', 'contact_email', 'login', 'socials', 'currency', 'bookmarks')
						));
						get_template_part(investment_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php echo trim($header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php investment_show_logo(true, true); ?>
					</div>
					<div class="menu_main_wrap">
						<nav class="menu_main_nav_area">
							<?php
							$menu_main = investment_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = investment_get_nav_menu();
							echo trim($menu_main);
							?>
						</nav>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
		investment_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => false,
				 'search' => false
			)
		);
	}
}
?>