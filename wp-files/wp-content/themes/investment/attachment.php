<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move investment_set_post_views to the javascript - counter will work under cache system
	if (investment_get_custom_option('use_ajax_views_counter')=='no') {
		investment_set_post_views(get_the_ID());
	}

	investment_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !investment_param_is_off(investment_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>