<?php
/**
 * Child-Theme functions and definitions
 */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
add_action( 'init', 'register_welltoc_posttypes');
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}
function register_welltoc_posttypes(){
$startSlidesLabels = array(
		'name'               => _x( 'Slajdy', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Slajd', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Slajdy', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Slajd', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Slajd', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Slajd', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Slajd', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Slajd', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Slajdy', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Slajdy', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Slajdy:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No slajdy found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No slajdy found in Trash.', 'your-plugin-textdomain' )
	);
 $startSlidesArgs = array(
      'public' => true,
      'labels'  => $startSlidesLabels,
      'show_ui'            => true,
      		'show_in_menu'       => true,
      		'query_var'          => true,
      		'rewrite'            => array( 'slug' => 'slajd' ),
      		'capability_type'    => 'post',
      		'has_archive'        => true,
      		'hierarchical'       => false,
      		'menu_position'      => 5,
      		'supports'           => array( 'title', 'editor','thumbnail','revisions','categories'),
      		'exclude_from_search' => true,
      		'taxonomies' => array('category')
    );
        register_post_type( 'start_slider', $startSlidesArgs );

        wp_insert_term('slide_start_top', 'category', array(
            'description' => 'Slajd na gorze Strony Glownej'
        ));
        wp_insert_term('slide_start_video', 'category', array(
                    'description' => 'Slajd z wideo Strony Glownej'
                ));

}
?>