<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'investment_template_404_theme_setup' ) ) {
	add_action( 'investment_action_before_init_theme', 'investment_template_404_theme_setup', 1 );
	function investment_template_404_theme_setup() {
		investment_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'investment_template_404_output' ) ) {
	function investment_template_404_output() {
		?>
        <article class="post_item post_item_404">
            <div class="post_content">
                <div class="image404">
                    <img class="image-404" src="http://investment.ancorathemes.com/wp-content/uploads/2016/03/img404.png" alt="">
                </div>
                <h2 class="page_title"><?php esc_html_e( 'Sorry! Can\'t Find That Page!', 'investment' ); ?></h2>
                <h2 class="page_title"><?php esc_html_e( 'Error 404!', 'investment' ); ?></h2>
                <p class="page_description"><?php echo wp_kses_data( sprintf( __('Can\'t find what you need? Take a moment and do a search below or start from our <a href="%s">homepage</a>.', 'investment'), esc_url(home_url('/')) ) ); ?></p>
                <div class="page_search"><?php echo trim(investment_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'investment')))); ?></div>
            </div>
        </article>
		<?php
	}
}
?>