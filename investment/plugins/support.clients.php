<?php
/**
 * Investment Framework: Clients support
 *
 * @package	investment
 * @since	investment 1.0
 */

// Theme init
if (!function_exists('investment_clients_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_clients_theme_setup', 1 );
	function investment_clients_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('investment_filter_get_blog_type',			'investment_clients_get_blog_type', 9, 2);
		add_filter('investment_filter_get_blog_title',		'investment_clients_get_blog_title', 9, 2);
		add_filter('investment_filter_get_current_taxonomy',	'investment_clients_get_current_taxonomy', 9, 2);
		add_filter('investment_filter_is_taxonomy',			'investment_clients_is_taxonomy', 9, 2);
		add_filter('investment_filter_get_stream_page_title',	'investment_clients_get_stream_page_title', 9, 2);
		add_filter('investment_filter_get_stream_page_link',	'investment_clients_get_stream_page_link', 9, 2);
		add_filter('investment_filter_get_stream_page_id',	'investment_clients_get_stream_page_id', 9, 2);
		add_filter('investment_filter_query_add_filters',		'investment_clients_query_add_filters', 9, 2);
		add_filter('investment_filter_detect_inheritance_key','investment_clients_detect_inheritance_key', 9, 1);

		// Extra column for clients lists
		if (investment_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-clients_columns',			'investment_post_add_options_column', 9);
			add_filter('manage_clients_posts_custom_column',	'investment_post_fill_options_column', 9, 2);
		}

		// Registar shortcodes [trx_clients] and [trx_clients_item] in the shortcodes list
		add_action('investment_action_shortcodes_list',		'investment_clients_reg_shortcodes');
		if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
			add_action('investment_action_shortcodes_list_vc','investment_clients_reg_shortcodes_vc');
		
		// Add supported data types
		investment_theme_support_pt('clients');
		investment_theme_support_tx('clients_group');
	}
}

if ( !function_exists( 'investment_clients_settings_theme_setup2' ) ) {
	add_action( 'investment_action_before_init_theme', 'investment_clients_settings_theme_setup2', 3 );
	function investment_clients_settings_theme_setup2() {
		// Add post type 'clients' and taxonomy 'clients_group' into theme inheritance list
		investment_add_theme_inheritance( array('clients' => array(
			'stream_template' => 'blog-clients',
			'single_template' => 'single-client',
			'taxonomy' => array('clients_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('clients'),
			'override' => 'custom'
			) )
		);
	}
}


if (!function_exists('investment_clients_after_theme_setup')) {
	add_action( 'investment_action_after_init_theme', 'investment_clients_after_theme_setup' );
	function investment_clients_after_theme_setup() {
		// Update fields in the meta box
		if (investment_storage_get_array('post_meta_box', 'page')=='clients') {
			// Meta box fields
			investment_storage_set_array('post_meta_box', 'title', esc_html__('Client Options', 'investment'));
			investment_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_clients" => array(
					"title" => esc_html__('Clients', 'investment'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "iconadmin-users",
					"type" => "partition"),
				"mb_info_clients_1" => array(
					"title" => esc_html__('Client details', 'investment'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => wp_kses_data( __('In this section you can put details for this client', 'investment') ),
					"class" => "client_meta",
					"type" => "info"),
				"client_name" => array(
					"title" => esc_html__('Contact name',  'investment'),
					"desc" => wp_kses_data( __("Name of the contacts manager", 'investment') ),
					"override" => "page,post,custom",
					"class" => "client_name",
					"std" => '',
					"type" => "text"),
				"client_position" => array(
					"title" => esc_html__('Position',  'investment'),
					"desc" => wp_kses_data( __("Position of the contacts manager", 'investment') ),
					"override" => "page,post,custom",
					"class" => "client_position",
					"std" => '',
					"type" => "text"),
				"client_show_link" => array(
					"title" => esc_html__('Show link',  'investment'),
					"desc" => wp_kses_data( __("Show link to client page", 'investment') ),
					"override" => "page,post,custom",
					"class" => "client_show_link",
					"std" => "no",
					"options" => investment_get_list_yesno(),
					"type" => "switch"),
				"client_link" => array(
					"title" => esc_html__('Link',  'investment'),
					"desc" => wp_kses_data( __("URL of the client's site. If empty - use link to this page", 'investment') ),
					"override" => "page,post,custom",
					"class" => "client_link",
					"std" => '',
					"type" => "text")
				)
			);
		}
	}
}


// Return true, if current page is clients page
if ( !function_exists( 'investment_is_clients_page' ) ) {
	function investment_is_clients_page() {
		$is = in_array(investment_storage_get('page_template'), array('blog-clients', 'single-client'));
		if (!$is) {
			if (!investment_storage_empty('pre_query'))
				$is = investment_storage_call_obj_method('pre_query', 'get', 'post_type')=='clients'
						|| investment_storage_call_obj_method('pre_query', 'is_tax', 'clients_group') 
						|| (investment_storage_call_obj_method('pre_query', 'is_page') 
							&& ($id=investment_get_template_page_id('blog-clients')) > 0 
							&& $id==investment_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='clients' 
						|| is_tax('clients_group') 
						|| (is_page() && ($id=investment_get_template_page_id('blog-clients')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'investment_clients_detect_inheritance_key' ) ) {
	//add_filter('investment_filter_detect_inheritance_key',	'investment_clients_detect_inheritance_key', 9, 1);
	function investment_clients_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return investment_is_clients_page() ? 'clients' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'investment_clients_get_blog_type' ) ) {
	//add_filter('investment_filter_get_blog_type',	'investment_clients_get_blog_type', 9, 2);
	function investment_clients_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('clients_group') || is_tax('clients_group'))
			$page = 'clients_category';
		else if ($query && $query->get('post_type')=='clients' || get_query_var('post_type')=='clients')
			$page = $query && $query->is_single() || is_single() ? 'clients_item' : 'clients';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'investment_clients_get_blog_title' ) ) {
	//add_filter('investment_filter_get_blog_title',	'investment_clients_get_blog_title', 9, 2);
	function investment_clients_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( investment_strpos($page, 'clients')!==false ) {
			if ( $page == 'clients_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'clients_group' ), 'clients_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'clients_item' ) {
				$title = investment_get_post_title();
			} else {
				$title = esc_html__('All clients', 'investment');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'investment_clients_get_stream_page_title' ) ) {
	//add_filter('investment_filter_get_stream_page_title',	'investment_clients_get_stream_page_title', 9, 2);
	function investment_clients_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (investment_strpos($page, 'clients')!==false) {
			if (($page_id = investment_clients_get_stream_page_id(0, $page=='clients' ? 'blog-clients' : $page)) > 0)
				$title = investment_get_post_title($page_id);
			else
				$title = esc_html__('All clients', 'investment');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'investment_clients_get_stream_page_id' ) ) {
	//add_filter('investment_filter_get_stream_page_id',	'investment_clients_get_stream_page_id', 9, 2);
	function investment_clients_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (investment_strpos($page, 'clients')!==false) $id = investment_get_template_page_id('blog-clients');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'investment_clients_get_stream_page_link' ) ) {
	//add_filter('investment_filter_get_stream_page_link',	'investment_clients_get_stream_page_link', 9, 2);
	function investment_clients_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (investment_strpos($page, 'clients')!==false) {
			$id = investment_get_template_page_id('blog-clients');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'investment_clients_get_current_taxonomy' ) ) {
	//add_filter('investment_filter_get_current_taxonomy',	'investment_clients_get_current_taxonomy', 9, 2);
	function investment_clients_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( investment_strpos($page, 'clients')!==false ) {
			$tax = 'clients_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'investment_clients_is_taxonomy' ) ) {
	//add_filter('investment_filter_is_taxonomy',	'investment_clients_is_taxonomy', 9, 2);
	function investment_clients_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('clients_group')!='' || is_tax('clients_group') ? 'clients_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'investment_clients_query_add_filters' ) ) {
	//add_filter('investment_filter_query_add_filters',	'investment_clients_query_add_filters', 9, 2);
	function investment_clients_query_add_filters($args, $filter) {
		if ($filter == 'clients') {
			$args['post_type'] = 'clients';
		}
		return $args;
	}
}





// ---------------------------------- [trx_clients] ---------------------------------------

/*
[trx_clients id="unique_id" columns="3" style="clients-1|clients-2|..."]
	[trx_clients_item name="client name" position="director" image="url"]Description text[/trx_clients_item]
	...
[/trx_clients]
*/
if ( !function_exists( 'investment_sc_clients' ) ) {
	function investment_sc_clients($atts, $content=null){	
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "clients-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "title",
			"order" => "asc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'investment'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_clients_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && investment_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$class .= ($class ? ' ' : '') . investment_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = investment_get_css_dimensions_from_values($width);
		$hs = investment_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		if (investment_param_is_on($slider)) investment_enqueue_slider('swiper');
	
		$columns = max(1, min(12, $columns));
		$count = max(1, (int) $count);
		if (investment_param_is_off($custom) && $count < $columns) $columns = $count;
		investment_storage_set('sc_clients_data', array(
			'id'=>$id,
            'style'=>$style,
            'counter'=>0,
            'columns'=>$columns,
            'slider'=>$slider,
            'css_wh'=>$ws . $hs
            )
        );

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_clients_wrap'
						. ($scheme && !investment_param_is_off($scheme) && !investment_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_clients sc_clients_style_'.esc_attr($style)
							. ' ' . esc_attr(investment_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!investment_param_is_off($animation) ? ' data-animation="'.esc_attr(investment_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_clients_subtitle sc_item_subtitle">' . trim(investment_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_clients_title sc_item_title">' . trim(investment_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_clients_descr sc_item_descr">' . trim(investment_strmacros($description)) . '</div>' : '')
					. (investment_param_is_on($slider) 
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(investment_get_slider_controls_classes($controls))
										. (investment_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && investment_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && investment_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ($style!='clients-2' ? ' data-slides-min-width="167"' : '')
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		$content = do_shortcode($content);
	
		if (investment_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'clients',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = investment_query_add_sort_order($args, $orderby, $order);
			$args = investment_query_add_posts_and_cats($args, $ids, 'clients', $cat, 'clients_group');

			$query = new WP_Query( $args );
	
			$post_number = 0;

			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => investment_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = investment_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], 'investment_post_options', true);
				$thumb_sizes = investment_get_thumb_sizes(array('layout' => $style));
				$args['client_name'] = $post_meta['client_name'];
				$args['client_position'] = $post_meta['client_position'];
				$args['client_image'] = $post_data['post_thumb'];
				$args['client_link'] = investment_param_is_on('client_show_link')
					? (!empty($post_meta['client_link']) ? $post_meta['client_link'] : $post_data['post_link'])
					: '';
				$output .= investment_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}
	
		if (investment_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_clients_button sc_item_button">'.investment_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div><!-- /.sc_clients -->'
			. '</div><!-- /.sc_clients_wrap -->';
	
		// Add template specific scripts and styles
		do_action('investment_action_blog_scripts', $style);
	
		return apply_filters('investment_shortcode_output', $output, 'trx_clients', $atts, $content);
	}
	investment_require_shortcode('trx_clients', 'investment_sc_clients');
}


if ( !function_exists( 'investment_sc_clients_item' ) ) {
	function investment_sc_clients_item($atts, $content=null) {
		if (investment_in_shortcode_blogger()) return '';
		extract(investment_html_decode(shortcode_atts( array(
			// Individual params
			"name" => "",
			"position" => "",
			"image" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		investment_storage_inc_array('sc_clients_data', 'counter');
	
		$id = $id ? $id : (investment_storage_get_array('sc_clients_data', 'id') ? investment_storage_get_array('sc_clients_data', 'id') . '_' . investment_storage_get_array('sc_clients_data', 'counter') : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = investment_get_thumb_sizes(array('layout' => investment_storage_get_array('sc_clients_data', 'style')));

		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$image = investment_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);

		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => investment_storage_get_array('sc_clients_data', 'style'),
			'number' => investment_storage_get_array('sc_clients_data', 'counter'),
			'columns_count' => investment_storage_get_array('sc_clients_data', 'columns'),
			'slider' => investment_storage_get_array('sc_clients_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => investment_storage_get_array('sc_clients_data', 'css_wh'),
			'client_position' => $position,
			'client_link' => $link,
			'client_image' => $image
		);
		$output = investment_show_post_layout($args, $post_data);
		return apply_filters('investment_shortcode_output', $output, 'trx_clients_item', $atts, $content);
	}
	investment_require_shortcode('trx_clients_item', 'investment_sc_clients_item');
}
// ---------------------------------- [/trx_clients] ---------------------------------------



// Add [trx_clients] and [trx_clients_item] in the shortcodes list
if (!function_exists('investment_clients_reg_shortcodes')) {
	//add_filter('investment_action_shortcodes_list',	'investment_clients_reg_shortcodes');
	function investment_clients_reg_shortcodes() {
		if (investment_storage_isset('shortcodes')) {

			$users = investment_get_list_users();
			$members = investment_get_list_posts(false, array(
				'post_type'=>'clients',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$clients_groups = investment_get_list_terms(false, 'clients_group');
			$clients_styles = investment_get_list_templates('clients');
			$controls 		= investment_get_list_slider_controls();

			investment_sc_map_after('trx_chat', array(

				// Clients
				"trx_clients" => array(
					"title" => esc_html__("Clients", 'investment'),
					"desc" => wp_kses_data( __("Insert clients list in your page (post)", 'investment') ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'investment'),
							"desc" => wp_kses_data( __("Title for the block", 'investment') ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'investment'),
							"desc" => wp_kses_data( __("Subtitle for the block", 'investment') ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'investment'),
							"desc" => wp_kses_data( __("Short description for the block", 'investment') ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Clients style", 'investment'),
							"desc" => wp_kses_data( __("Select style to display clients list", 'investment') ),
							"value" => "clients-1",
							"type" => "select",
							"options" => $clients_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'investment'),
							"desc" => wp_kses_data( __("How many columns use to show clients", 'investment') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'investment'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'investment') ),
							"value" => "",
							"type" => "checklist",
							"options" => investment_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'investment'),
							"desc" => wp_kses_data( __("Use slider to show clients", 'investment') ),
							"value" => "no",
							"type" => "switch",
							"options" => investment_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'investment'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'investment') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "no",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'investment'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'investment') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'investment'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'investment') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'investment'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'investment') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "no",
							"type" => "switch",
							"options" => investment_get_sc_param('yes_no')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'investment'),
							"desc" => wp_kses_data( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'investment') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => investment_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'investment'),
							"desc" => wp_kses_data( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'investment') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => investment_array_merge(array(0 => esc_html__('- Select category -', 'investment')), $clients_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'investment'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'investment') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'investment'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'investment') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'investment'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'investment') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => investment_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'investment'),
							"desc" => wp_kses_data( __("Select desired posts order", 'investment') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => investment_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'investment'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'investment') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'investment'),
							"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'investment') ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'investment'),
							"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'investment') ),
							"value" => "",
							"type" => "text"
						),
						"width" => investment_shortcodes_width(),
						"height" => investment_shortcodes_height(),
						"top" => investment_get_sc_param('top'),
						"bottom" => investment_get_sc_param('bottom'),
						"left" => investment_get_sc_param('left'),
						"right" => investment_get_sc_param('right'),
						"id" => investment_get_sc_param('id'),
						"class" => investment_get_sc_param('class'),
						"animation" => investment_get_sc_param('animation'),
						"css" => investment_get_sc_param('css')
					),
					"children" => array(
						"name" => "trx_clients_item",
						"title" => esc_html__("Client", 'investment'),
						"desc" => wp_kses_data( __("Single client (custom parameters)", 'investment') ),
						"container" => true,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Name", 'investment'),
								"desc" => wp_kses_data( __("Client's name", 'investment') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'investment'),
								"desc" => wp_kses_data( __("Client's position", 'investment') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'investment'),
								"desc" => wp_kses_data( __("Link on client's personal page", 'investment') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"image" => array(
								"title" => esc_html__("Image", 'investment'),
								"desc" => wp_kses_data( __("Client's image", 'investment') ),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'investment'),
								"desc" => wp_kses_data( __("Client's short description", 'investment') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => investment_get_sc_param('id'),
							"class" => investment_get_sc_param('class'),
							"animation" => investment_get_sc_param('animation'),
							"css" => investment_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_clients] and [trx_clients_item] in the VC shortcodes list
if (!function_exists('investment_clients_reg_shortcodes_vc')) {
	//add_filter('investment_action_shortcodes_list_vc',	'investment_clients_reg_shortcodes_vc');
	function investment_clients_reg_shortcodes_vc() {

		$clients_groups = investment_get_list_terms(false, 'clients_group');
		$clients_styles = investment_get_list_templates('clients');
		$controls		= investment_get_list_slider_controls();

		// Clients
		vc_map( array(
				"base" => "trx_clients",
				"name" => esc_html__("Clients", 'investment'),
				"description" => wp_kses_data( __("Insert clients list", 'investment') ),
				"category" => esc_html__('Content', 'investment'),
				'icon' => 'icon_trx_clients',
				"class" => "trx_sc_columns trx_sc_clients",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_clients_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Clients style", 'investment'),
						"description" => wp_kses_data( __("Select style to display clients list", 'investment') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($clients_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'investment'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'investment') ),
						"class" => "",
						"value" => array_flip(investment_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'investment'),
						"description" => wp_kses_data( __("Use slider to show testimonials", 'investment') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'investment'),
						"class" => "",
						"std" => "no",
						"value" => array_flip(investment_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'investment'),
						"description" => wp_kses_data( __("Slider controls style and position", 'investment') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'investment'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'investment'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'investment') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'investment'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'investment'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'investment') ),
						"group" => esc_html__('Slider', 'investment'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'investment'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'investment') ),
						"group" => esc_html__('Slider', 'investment'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'investment'),
						"description" => wp_kses_data( __("Allow get clients from inner shortcodes (custom) or get it from specified group (cat)", 'investment') ),
						"class" => "",
						"value" => array("Custom clients" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'investment'),
						"description" => wp_kses_data( __("Title for the block", 'investment') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'investment'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'investment'),
						"description" => wp_kses_data( __("Subtitle for the block", 'investment') ),
						"group" => esc_html__('Captions', 'investment'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'investment'),
						"description" => wp_kses_data( __("Description for the block", 'investment') ),
						"group" => esc_html__('Captions', 'investment'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'investment'),
						"description" => wp_kses_data( __("Select category to show clients. If empty - select clients from any category (group) or from IDs list", 'investment') ),
						"group" => esc_html__('Query', 'investment'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(investment_array_merge(array(0 => esc_html__('- Select category -', 'investment')), $clients_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'investment'),
						"description" => wp_kses_data( __("How many columns use to show clients", 'investment') ),
						"group" => esc_html__('Query', 'investment'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'investment'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'investment') ),
						"group" => esc_html__('Query', 'investment'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'investment'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'investment') ),
						"group" => esc_html__('Query', 'investment'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'investment'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'investment') ),
						"group" => esc_html__('Query', 'investment'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "title",
						"class" => "",
						"value" => array_flip(investment_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'investment'),
						"description" => wp_kses_data( __("Select desired posts order", 'investment') ),
						"group" => esc_html__('Query', 'investment'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "asc",
						"class" => "",
						"value" => array_flip(investment_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", 'investment'),
						"description" => wp_kses_data( __("Comma separated list of client's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'investment') ),
						"group" => esc_html__('Query', 'investment'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'investment'),
						"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'investment') ),
						"group" => esc_html__('Captions', 'investment'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'investment'),
						"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'investment') ),
						"group" => esc_html__('Captions', 'investment'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					investment_vc_width(),
					investment_vc_height(),
					investment_get_vc_param('margin_top'),
					investment_get_vc_param('margin_bottom'),
					investment_get_vc_param('margin_left'),
					investment_get_vc_param('margin_right'),
					investment_get_vc_param('id'),
					investment_get_vc_param('class'),
					investment_get_vc_param('animation'),
					investment_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_clients_item",
				"name" => esc_html__("Client", 'investment'),
				"description" => wp_kses_data( __("Client - all data pull out from it account on your site", 'investment') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_clients_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_clients_item',
				"as_child" => array('only' => 'trx_clients'),
				"as_parent" => array('except' => 'trx_clients'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", 'investment'),
						"description" => wp_kses_data( __("Client's name", 'investment') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", 'investment'),
						"description" => wp_kses_data( __("Client's position", 'investment') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'investment'),
						"description" => wp_kses_data( __("Link on client's personal page", 'investment') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Client's image", 'investment'),
						"description" => wp_kses_data( __("Clients's image", 'investment') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					investment_get_vc_param('id'),
					investment_get_vc_param('class'),
					investment_get_vc_param('animation'),
					investment_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Clients extends INVESTMENT_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Clients_Item extends INVESTMENT_VC_ShortCodeCollection {}

	}
}
?>