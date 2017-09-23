<?php
/**
 * Single post
 */
get_header(); 

$single_style = investment_storage_get('single_style');
if (empty($single_style)) $single_style = investment_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	investment_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !investment_param_is_off(investment_get_custom_option('show_sidebar_main')),
			'content' => investment_get_template_property($single_style, 'need_content'),
			'terms_list' => investment_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>