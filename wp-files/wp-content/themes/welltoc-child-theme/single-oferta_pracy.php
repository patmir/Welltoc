<?php
/**
 * Single post
 */
get_header();
$single_style = investment_storage_get('single_style');
if (empty($single_style)) $single_style = investment_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	$post = get_post();
	echo do_shortcode("[vc_row][vc_column][trx_title style='iconed' icon='icon-star' animation='slideInLeft']".$post->post_title."[/trx_title][/vc_column][/vc_row]");
	echo do_shortcode("[vc_row el_class='margin-top-48'][vc_column][vc_column_text css_animation='slideInUp']".$post->post_content."[/vc_column_text][/vc_column][/vc_row]");

}

get_footer();
?>
