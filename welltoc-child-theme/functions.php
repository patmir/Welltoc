<?php
/**
 * Child-Theme functions and definitions
 */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

add_action( 'init', 'register_welltoc_posttypes');
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}
function my_scripts_method() {
    wp_enqueue_script(
        'welltoc-js',
        get_stylesheet_directory_uri() . '/welltoc-js.js',
        array( 'jquery' )
    );
}
function register_welltoc_posttypes(){
$startSlidesLabels = array(
		'name'               => _x( 'Slajdy', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Slajd', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Slajdy', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Slajd', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Dodaj Nowy', 'book', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Dodaj Nowy Slajd', 'your-plugin-textdomain' ),
		'new_item'           => __( 'Nowy Slajd', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edytuj Slajd', 'your-plugin-textdomain' ),
		'view_item'          => __( 'Zobacz Slajd', 'your-plugin-textdomain' ),
		'all_items'          => __( 'Wszystkie Slajdy', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Znajdź Slajd', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Nadrzędny Slajd:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'Nie znaleziono slajdów.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'Nie znaleziono slajdów w koszu.', 'your-plugin-textdomain' )
	);
  $jobOfferLables = array(
  		'name'               => _x( 'Oferty Pracy', 'post type general name', 'your-plugin-textdomain' ),
  		'singular_name'      => _x( 'Oferta Pracy', 'post type singular name', 'your-plugin-textdomain' ),
  		'menu_name'          => _x( 'Oferty Pracy', 'admin menu', 'your-plugin-textdomain' ),
  		'name_admin_bar'     => _x( 'Oferta Pracy', 'add new on admin bar', 'your-plugin-textdomain' ),
  		'add_new'            => _x( 'Dodaj Nową', 'book', 'your-plugin-textdomain' ),
  		'add_new_item'       => __( 'Dodaj Nową Ofertę Pracy', 'your-plugin-textdomain' ),
  		'new_item'           => __( 'Nowa Oferta Pracy', 'your-plugin-textdomain' ),
  		'edit_item'          => __( 'Edytuj Ofertę Pracy', 'your-plugin-textdomain' ),
  		'view_item'          => __( 'Zobacz Ofertę Pracy', 'your-plugin-textdomain' ),
  		'all_items'          => __( 'Wszystkie Oferty Pracy', 'your-plugin-textdomain' ),
  		'search_items'       => __( 'Wyszukaj Oferty Pracy', 'your-plugin-textdomain' ),
  		'parent_item_colon'  => __( 'Nadrzędne Oferty Pracy:', 'your-plugin-textdomain' ),
  		'not_found'          => __( 'Nie znaleziono ofert pracy.', 'your-plugin-textdomain' ),
  		'not_found_in_trash' => __( 'Nie znaleziono ofert pracy w koszu.', 'your-plugin-textdomain' )
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
      		'supports'           => array( 'title', 'editor','thumbnail','revisions'),
      		'exclude_from_search' => true,
      		'taxonomies' => array('category')
    );
    $jobOfferArgs = array(
         'public' => true,
         'labels'  => $jobOfferLables,
         'show_ui'            => true,
         		'show_in_menu'       => true,
         		'query_var'          => true,
         		'rewrite'            => array( 'slug' => 'oferta' ),
         		'capability_type'    => 'page',
         		'has_archive'        => true,
         		'hierarchical'       => false,
         		'menu_position'      => 5,
         		'supports'           => array( 'title', 'editor','revisions'),
         		'exclude_from_search' => true,
         		'taxonomies' => array('category'),
        'register_meta_box_cb' => 'jobOfferMetaboxes'
       );
       register_post_type( 'start_slider', $startSlidesArgs );
        register_post_type( 'oferta_pracy', $jobOfferArgs );

        wp_insert_term('slide_start_top', 'category', array(
            'description' => 'Slajd na gorze Strony Glownej'
        ));
        wp_insert_term('slide_start_video', 'category', array(
                    'description' => 'Slajd z wideo Strony Glownej'
                ));
                wp_insert_term('Miejscowość', 'category', array(
                            'description' => 'Miejscowość'
                        ));
                        wp_insert_term('Województwo', 'category', array(
                                    'description' => 'Województwo'
                                ));
                                wp_insert_term('Branża', 'category', array(
                                            'description' => 'Branża'
                                        ));

}
function jobOfferMetaboxes(){

  /** Job Offer MetaBoxes */
  add_meta_box("job-offer-city", __( 'Miejscowość', 'welltoc' ),'jobOfferCityCallback','oferta_pracy','side' );
  add_meta_box("job-offer-voivodeship", __( 'Województwo', 'welltoc' ),'jobOfferVoivodeshipCallback','oferta_pracy','side' );
  add_meta_box("job-offer-trade", __( 'Branża', 'welltoc' ),'jobOfferTradeCallback','oferta_pracy','side' );
  }

  function jobOfferCityCallback( $post ) {
    wp_nonce_field( 'job-offer-city-nonce', 'job-offer-city-nonce' );
    $value = get_post_meta( $post->ID, '_job-offer-city', true );
    echo '<input type="text" style="width:100%" id="job-offer-city" name="job-offer-city" value="' . esc_attr( $value ) . '">';
}
function jobOfferTradeCallback( $post ) {
  wp_nonce_field( 'job-offer-trade-nonce', 'job-offer-trade-nonce' );
  $value = get_post_meta( $post->ID, '_job-offer-trade', true );
  echo '<input type="text" style="width:100%" id="job-offer-trade" name="job-offer-trade" value="' . esc_attr( $value ) . '">';
}
function jobOfferVoivodeshipCallback( $post ) {
  wp_nonce_field( 'job-offer-voivodeship-nonce', 'job-offer-voivodeship-nonce' );
  $value = get_post_meta( $post->ID, '_job-offer-voivodeship', true );
  echo '<input type="text" style="width:100%" id="job-offer-voivodeship" name="job-offer-voivodeship" value="' . esc_attr( $value ) . '">';
}
function save_job_offer_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['job-offer-city-nonce'] ) || !isset($_POST['job-offer-voivodeship-nonce']) || !isset($_POST['job-offer-trade-nonce'])) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['job-offer-city-nonce'], 'job-offer-city-nonce' )
    ||  ! wp_verify_nonce( $_POST['job-offer-voivodeship-nonce'], 'job-offer-voivodeship-nonce' )
  ||  ! wp_verify_nonce( $_POST['job-offer-trade-nonce'], 'job-offer-trade-nonce' )) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    }
    else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    if ( ! isset( $_POST['job-offer-city'] ) || ! isset( $_POST['job-offer-voivodeship'] ) || ! isset( $_POST['job-offer-trade'] )) {
        return;
    }

    // Sanitize user input.
    $city = sanitize_text_field( $_POST['job-offer-city'] );
    $voivodeship = sanitize_text_field( $_POST['job-offer-voivodeship'] );
    $trade = sanitize_text_field( $_POST['job-offer-trade'] );
// Check categories
    $cityTermId = get_term_by('name', 'Miejscowość','category')->term_id;
    $voivodeshipTermId = get_term_by('name', 'Województwo','category')->term_id;
    $tradeTermId = get_term_by('name', 'Branża','category')->term_id;

    $finalCategories = [$cityTermId, $voivodeshipTermId, $tradeTermId];

      if($newCity = get_term_by('name', $city, 'category')){
        $newCity = $newCity->term_id;
        wp_update_term($newCity, 'category', array('parent' => $cityTermId ));

      } else {
        $newCity = wp_insert_term($city, 'category', array('description' => $city, 'parent' => $cityTermId));
        $newCity = $newCity["term_id"];
      }
      $finalCategories[] = $newCity;

      if($newVoivodeship = get_term_by('name', $voivodeship, 'category')){
        $newVoivodeship = $newVoivodeship->term_id;
        wp_update_term($newVoivodeship, 'category', array('parent' => $voivodeshipTermId ));
      } else {
        $newVoivodeship = wp_insert_term($voivodeship, 'category', array('description' => $voivodeship, 'parent' => $voivodeshipTermId));
        $newVoivodeship = $newVoivodeship["term_id"];
      }
      $finalCategories[] = $newVoivodeship;

      if($newTrade = get_term_by('name', $trade, 'category')){
        $newTrade = $newTrade->term_id;
        wp_update_term($newTrade, 'category', array('parent' => $tradeTermId ));
      } else {
        $newTrade = wp_insert_term($trade, 'category', array('description' => $trade, 'parent' => $tradeTermId));
        $newTrade = $newTrade["term_id"];
      }
      $finalCategories[] = $newTrade;
      wp_set_post_categories($post_id, $finalCategories);
    // Update the meta field in the database.
    update_post_meta( $post_id, '_job-offer-city', $city);
    update_post_meta( $post_id, '_job-offer-voivodeship', $voivodeship);
    update_post_meta( $post_id, '_job-offer-trade', $trade);

    /** Update Job Offers Page Filters **/
    // Get currently published categories
    $cities = [];
    $voivodeships = [];
    $trades = [];
    $allForPost = [];
    $jobOffers = get_posts(['post_type' => 'oferta_pracy', 'post_status' => 'publish', 'numberposts' => -1]);
    foreach ($jobOffers as $jobOffer) {
        // get given post categoreis
        $terms = wp_get_post_terms($jobOffer->ID, "category");
        foreach ($terms as $term) {
          $parent = $term->parent;
          if($parent == $cityTermId && !in_array($term->term_id, $cities)){
            $cities[] = "category_".$term->term_id;
            $allForPost[] = "category_".$term->term_id;
          } else if($parent == $voivodeshipTermId && !in_array($term->term_id, $voivodeships)){
            $voivodeships[] = "category_".$term->term_id;
            $allForPost[] = "category_".$term->term_id;
          } else if($parent == $tradeTermId && !in_array($term->term_id, $trades)){
            $trades[] = "category_".$term->term_id;
            $allForPost[] = "category_".$term->term_id;
          }
        }
    }
    /** prepare post_category **/
    $es_post_category = implode(",",$allForPost);
global $wpdb;
    $results = $wpdb->get_row( "SELECT * FROM wp_eg_grids where handle='oferty-pracy'");
    $post_params = json_decode($results->postparams);
    $params = json_decode($results->params);

    $post_params->post_category = $es_post_category;
    $params->{'filter-selected'} = $cities;
    $params->{'filter-selected-2'} = $voivodeships;
    $params->{'filter-selected-3'} = $trades;
    $x = $wpdb->update("wp_eg_grids", array("postparams" => json_encode($post_params), "params" => json_encode($params)), array("handle"=>"oferty-pracy"));

}

add_action( 'save_post', 'save_job_offer_meta_box_data' );
/** Functions override
*/
function investment_clients_theme_setup(){}
function investment_testimonial_theme_setup(){}
function investment_services_theme_setup(){}
function investment_team_theme_setup(){}



  if (!function_exists('write_log')) {
    function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }}
?>
