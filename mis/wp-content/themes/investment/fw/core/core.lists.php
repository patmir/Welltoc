<?php
/**
 * Investment Framework: return lists
 *
 * @package investment
 * @since investment 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'investment_get_list_styles' ) ) {
	function investment_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'investment'), $i);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'investment_get_list_margins' ) ) {
	function investment_get_list_margins($prepend_inherit=false) {
		if (($list = investment_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'investment'),
				'tiny'		=> esc_html__('Tiny',		'investment'),
				'small'		=> esc_html__('Small',		'investment'),
				'medium'	=> esc_html__('Medium',		'investment'),
				'large'		=> esc_html__('Large',		'investment'),
				'huge'		=> esc_html__('Huge',		'investment'),
                'super-huge'		=> esc_html__('Super Huge',		'investment'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'investment'),
				'small-'	=> esc_html__('Small (negative)',	'investment'),
				'medium-'	=> esc_html__('Medium (negative)',	'investment'),
				'large-'	=> esc_html__('Large (negative)',	'investment'),
				'huge-'		=> esc_html__('Huge (negative)',	'investment')
				);
			$list = apply_filters('investment_filter_list_margins', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'investment_get_list_animations' ) ) {
	function investment_get_list_animations($prepend_inherit=false) {
		if (($list = investment_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'investment'),
				'bounced'		=> esc_html__('Bounced',		'investment'),
				'flash'			=> esc_html__('Flash',		'investment'),
				'flip'			=> esc_html__('Flip',		'investment'),
				'pulse'			=> esc_html__('Pulse',		'investment'),
				'rubberBand'	=> esc_html__('Rubber Band',	'investment'),
				'shake'			=> esc_html__('Shake',		'investment'),
				'swing'			=> esc_html__('Swing',		'investment'),
				'tada'			=> esc_html__('Tada',		'investment'),
				'wobble'		=> esc_html__('Wobble',		'investment')
				);
			$list = apply_filters('investment_filter_list_animations', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'investment_get_list_line_styles' ) ) {
	function investment_get_list_line_styles($prepend_inherit=false) {
		if (($list = investment_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'investment'),
				'dashed'=> esc_html__('Dashed', 'investment'),
				'dotted'=> esc_html__('Dotted', 'investment'),
				'double'=> esc_html__('Double', 'investment'),
				'image'	=> esc_html__('Image', 'investment')
				);
			$list = apply_filters('investment_filter_list_line_styles', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'investment_get_list_animations_in' ) ) {
	function investment_get_list_animations_in($prepend_inherit=false) {
		if (($list = investment_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'investment'),
				'bounceIn'			=> esc_html__('Bounce In',			'investment'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'investment'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'investment'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'investment'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'investment'),
				'fadeIn'			=> esc_html__('Fade In',			'investment'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'investment'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'investment'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'investment'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'investment'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'investment'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'investment'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'investment'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'investment'),
				'flipInX'			=> esc_html__('Flip In X',			'investment'),
				'flipInY'			=> esc_html__('Flip In Y',			'investment'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'investment'),
				'rotateIn'			=> esc_html__('Rotate In',			'investment'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','investment'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'investment'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'investment'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','investment'),
				'rollIn'			=> esc_html__('Roll In',			'investment'),
				'slideInUp'			=> esc_html__('Slide In Up',		'investment'),
				'slideInDown'		=> esc_html__('Slide In Down',		'investment'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'investment'),
				'slideInRight'		=> esc_html__('Slide In Right',		'investment'),
				'zoomIn'			=> esc_html__('Zoom In',			'investment'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'investment'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'investment'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'investment'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'investment')
				);
			$list = apply_filters('investment_filter_list_animations_in', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'investment_get_list_animations_out' ) ) {
	function investment_get_list_animations_out($prepend_inherit=false) {
		if (($list = investment_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'investment'),
				'bounceOut'			=> esc_html__('Bounce Out',			'investment'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'investment'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'investment'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'investment'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'investment'),
				'fadeOut'			=> esc_html__('Fade Out',			'investment'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'investment'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'investment'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'investment'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'investment'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'investment'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'investment'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'investment'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'investment'),
				'flipOutX'			=> esc_html__('Flip Out X',			'investment'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'investment'),
				'hinge'				=> esc_html__('Hinge Out',			'investment'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'investment'),
				'rotateOut'			=> esc_html__('Rotate Out',			'investment'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'investment'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'investment'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'investment'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'investment'),
				'rollOut'			=> esc_html__('Roll Out',		'investment'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'investment'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'investment'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'investment'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'investment'),
				'zoomOut'			=> esc_html__('Zoom Out',			'investment'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'investment'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'investment'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'investment'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'investment')
				);
			$list = apply_filters('investment_filter_list_animations_out', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('investment_get_animation_classes')) {
	function investment_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return investment_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!investment_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'investment_get_list_categories' ) ) {
	function investment_get_list_categories($prepend_inherit=false) {
		if (($list = investment_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'investment_get_list_terms' ) ) {
	function investment_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = investment_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = investment_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'investment_get_list_posts_types' ) ) {
	function investment_get_list_posts_types($prepend_inherit=false) {
		if (($list = investment_storage_get('list_posts_types'))=='') {
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'investment');
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = investment_strtoproper($t);
				}
			}
			*/
			// Return only theme inheritance supported post types
			$list = apply_filters('investment_filter_list_post_types', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'investment_get_list_posts' ) ) {
	function investment_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = investment_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'investment');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set($hash, $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'investment_get_list_pages' ) ) {
	function investment_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return investment_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'investment_get_list_users' ) ) {
	function investment_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = investment_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'investment');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_users', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'investment_get_list_sliders' ) ) {
	function investment_get_list_sliders($prepend_inherit=false) {
		if (($list = investment_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'investment')
			);
			$list = apply_filters('investment_filter_list_sliders', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'investment_get_list_slider_controls' ) ) {
	function investment_get_list_slider_controls($prepend_inherit=false) {
		if (($list = investment_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'investment'),
				'side'		=> esc_html__('Side', 'investment'),
				'bottom'	=> esc_html__('Bottom', 'investment'),
				'pagination'=> esc_html__('Pagination', 'investment')
				);
			$list = apply_filters('investment_filter_list_slider_controls', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'investment_get_slider_controls_classes' ) ) {
	function investment_get_slider_controls_classes($controls) {
		if (investment_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'investment_get_list_popup_engines' ) ) {
	function investment_get_list_popup_engines($prepend_inherit=false) {
		if (($list = investment_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'investment'),
				"magnific"	=> esc_html__("Magnific popup", 'investment')
				);
			$list = apply_filters('investment_filter_list_popup_engines', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'investment_get_list_menus' ) ) {
	function investment_get_list_menus($prepend_inherit=false) {
		if (($list = investment_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'investment');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'investment_get_list_sidebars' ) ) {
	function investment_get_list_sidebars($prepend_inherit=false) {
		if (($list = investment_storage_get('list_sidebars'))=='') {
			if (($list = investment_storage_get('registered_sidebars'))=='') $list = array();
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'investment_get_list_sidebars_positions' ) ) {
	function investment_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = investment_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'investment'),
				'left'  => esc_html__('Left',  'investment'),
				'right' => esc_html__('Right', 'investment')
				);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'investment_get_sidebar_class' ) ) {
	function investment_get_sidebar_class() {
		$sb_main = investment_get_custom_option('show_sidebar_main');
		$sb_outer = investment_get_custom_option('show_sidebar_outer');
		return (investment_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (investment_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'investment_get_list_body_styles' ) ) {
	function investment_get_list_body_styles($prepend_inherit=false) {
		if (($list = investment_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'investment'),
				'wide'	=> esc_html__('Wide',		'investment')
				);
			if (investment_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'investment');
				$list['fullscreen']	= esc_html__('Fullscreen',	'investment');
			}
			$list = apply_filters('investment_filter_list_body_styles', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'investment_get_list_skins' ) ) {
	function investment_get_list_skins($prepend_inherit=false) {
		if (($list = investment_storage_get('list_skins'))=='') {
			$list = investment_get_list_folders("skins");
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_skins', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'investment_get_list_themes' ) ) {
	function investment_get_list_themes($prepend_inherit=false) {
		if (($list = investment_storage_get('list_themes'))=='') {
			$list = investment_get_list_files("css/themes");
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'investment_get_list_templates' ) ) {
	function investment_get_list_templates($mode='') {
		if (($list = investment_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = investment_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: investment_strtoproper($v['layout'])
										);
				}
			}
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'investment_get_list_templates_blog' ) ) {
	function investment_get_list_templates_blog($prepend_inherit=false) {
		if (($list = investment_storage_get('list_templates_blog'))=='') {
			$list = investment_get_list_templates('blog');
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'investment_get_list_templates_blogger' ) ) {
	function investment_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = investment_storage_get('list_templates_blogger'))=='') {
			$list = investment_array_merge(investment_get_list_templates('blogger'), investment_get_list_templates('blog'));
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'investment_get_list_templates_single' ) ) {
	function investment_get_list_templates_single($prepend_inherit=false) {
		if (($list = investment_storage_get('list_templates_single'))=='') {
			$list = investment_get_list_templates('single');
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'investment_get_list_templates_header' ) ) {
	function investment_get_list_templates_header($prepend_inherit=false) {
		if (($list = investment_storage_get('list_templates_header'))=='') {
			$list = investment_get_list_templates('header');
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'investment_get_list_templates_forms' ) ) {
	function investment_get_list_templates_forms($prepend_inherit=false) {
		if (($list = investment_storage_get('list_templates_forms'))=='') {
			$list = investment_get_list_templates('forms');
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'investment_get_list_article_styles' ) ) {
	function investment_get_list_article_styles($prepend_inherit=false) {
		if (($list = investment_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'investment'),
				"stretch" => esc_html__('Stretch', 'investment')
				);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'investment_get_list_post_formats_filters' ) ) {
	function investment_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = investment_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'investment'),
				"thumbs"  => esc_html__('With thumbs', 'investment'),
				"reviews" => esc_html__('With reviews', 'investment'),
				"video"   => esc_html__('With videos', 'investment'),
				"audio"   => esc_html__('With audios', 'investment'),
				"gallery" => esc_html__('With galleries', 'investment')
				);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'investment_get_list_portfolio_filters' ) ) {
	function investment_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = investment_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'investment'),
				"tags"		=> esc_html__('Tags', 'investment'),
				"categories"=> esc_html__('Categories', 'investment')
				);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'investment_get_list_hovers' ) ) {
	function investment_get_list_hovers($prepend_inherit=false) {
		if (($list = investment_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'investment');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'investment');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'investment');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'investment');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'investment');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'investment');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'investment');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'investment');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'investment');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'investment');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'investment');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'investment');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'investment');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'investment');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'investment');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'investment');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'investment');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'investment');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'investment');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'investment');
			$list['square effect1']  = esc_html__('Square Effect 1',  'investment');
			$list['square effect2']  = esc_html__('Square Effect 2',  'investment');
			$list['square effect3']  = esc_html__('Square Effect 3',  'investment');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'investment');
			$list['square effect5']  = esc_html__('Square Effect 5',  'investment');
			$list['square effect6']  = esc_html__('Square Effect 6',  'investment');
			$list['square effect7']  = esc_html__('Square Effect 7',  'investment');
			$list['square effect8']  = esc_html__('Square Effect 8',  'investment');
			$list['square effect9']  = esc_html__('Square Effect 9',  'investment');
			$list['square effect10'] = esc_html__('Square Effect 10',  'investment');
			$list['square effect11'] = esc_html__('Square Effect 11',  'investment');
			$list['square effect12'] = esc_html__('Square Effect 12',  'investment');
			$list['square effect13'] = esc_html__('Square Effect 13',  'investment');
			$list['square effect14'] = esc_html__('Square Effect 14',  'investment');
			$list['square effect15'] = esc_html__('Square Effect 15',  'investment');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'investment');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'investment');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'investment');
			$list['square effect_more']  = esc_html__('Square Effect More',  'investment');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'investment');
			$list = apply_filters('investment_filter_portfolio_hovers', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'investment_get_list_blog_counters' ) ) {
	function investment_get_list_blog_counters($prepend_inherit=false) {
		if (($list = investment_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'investment'),
				'likes'		=> esc_html__('Likes', 'investment'),
				'rating'	=> esc_html__('Rating', 'investment'),
				'comments'	=> esc_html__('Comments', 'investment')
				);
			$list = apply_filters('investment_filter_list_blog_counters', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'investment_get_list_alter_sizes' ) ) {
	function investment_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = investment_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'investment'),
					'1_2' => esc_html__('1x2', 'investment'),
					'2_1' => esc_html__('2x1', 'investment'),
					'2_2' => esc_html__('2x2', 'investment'),
					'1_3' => esc_html__('1x3', 'investment'),
					'2_3' => esc_html__('2x3', 'investment'),
					'3_1' => esc_html__('3x1', 'investment'),
					'3_2' => esc_html__('3x2', 'investment'),
					'3_3' => esc_html__('3x3', 'investment')
					);
			$list = apply_filters('investment_filter_portfolio_alter_sizes', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'investment_get_list_hovers_directions' ) ) {
	function investment_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = investment_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'investment'),
				'right_to_left' => esc_html__('Right to Left',  'investment'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'investment'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'investment'),
				'scale_up'      => esc_html__('Scale Up',  'investment'),
				'scale_down'    => esc_html__('Scale Down',  'investment'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'investment'),
				'from_left_and_right' => esc_html__('From Left and Right',  'investment'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'investment')
			);
			$list = apply_filters('investment_filter_portfolio_hovers_directions', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'investment_get_list_label_positions' ) ) {
	function investment_get_list_label_positions($prepend_inherit=false) {
		if (($list = investment_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'investment'),
				'bottom'	=> esc_html__('Bottom',		'investment'),
				'left'		=> esc_html__('Left',		'investment'),
				'over'		=> esc_html__('Over',		'investment')
			);
			$list = apply_filters('investment_filter_label_positions', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'investment_get_list_bg_image_positions' ) ) {
	function investment_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = investment_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'investment'),
				'center top'   => esc_html__("Center Top", 'investment'),
				'right top'    => esc_html__("Right Top", 'investment'),
				'left center'  => esc_html__("Left Center", 'investment'),
				'center center'=> esc_html__("Center Center", 'investment'),
				'right center' => esc_html__("Right Center", 'investment'),
				'left bottom'  => esc_html__("Left Bottom", 'investment'),
				'center bottom'=> esc_html__("Center Bottom", 'investment'),
				'right bottom' => esc_html__("Right Bottom", 'investment')
			);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'investment_get_list_bg_image_repeats' ) ) {
	function investment_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = investment_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'investment'),
				'repeat-x'	=> esc_html__('Repeat X', 'investment'),
				'repeat-y'	=> esc_html__('Repeat Y', 'investment'),
				'no-repeat'	=> esc_html__('No Repeat', 'investment')
			);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'investment_get_list_bg_image_attachments' ) ) {
	function investment_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = investment_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'investment'),
				'fixed'		=> esc_html__('Fixed', 'investment'),
				'local'		=> esc_html__('Local', 'investment')
			);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'investment_get_list_bg_tints' ) ) {
	function investment_get_list_bg_tints($prepend_inherit=false) {
		if (($list = investment_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'investment'),
				'light'	=> esc_html__('Light', 'investment'),
				'dark'	=> esc_html__('Dark', 'investment')
			);
			$list = apply_filters('investment_filter_bg_tints', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'investment_get_list_field_types' ) ) {
	function investment_get_list_field_types($prepend_inherit=false) {
		if (($list = investment_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'investment'),
				'textarea' => esc_html__('Text Area','investment'),
				'password' => esc_html__('Password',  'investment'),
				'radio'    => esc_html__('Radio',  'investment'),
				'checkbox' => esc_html__('Checkbox',  'investment'),
				'select'   => esc_html__('Select',  'investment'),
				'date'     => esc_html__('Date','investment'),
				'time'     => esc_html__('Time','investment'),
				'button'   => esc_html__('Button','investment')
			);
			$list = apply_filters('investment_filter_field_types', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'investment_get_list_googlemap_styles' ) ) {
	function investment_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = investment_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'investment')
			);
			$list = apply_filters('investment_filter_googlemap_styles', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'investment_get_list_icons' ) ) {
	function investment_get_list_icons($prepend_inherit=false) {
		if (($list = investment_storage_get('list_icons'))=='') {
			$list = investment_parse_icons_classes(investment_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'investment_get_list_socials' ) ) {
	function investment_get_list_socials($prepend_inherit=false) {
		if (($list = investment_storage_get('list_socials'))=='') {
			$list = investment_get_list_files("images/socials", "png");
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'investment_get_list_flags' ) ) {
	function investment_get_list_flags($prepend_inherit=false) {
		if (($list = investment_storage_get('list_flags'))=='') {
			$list = investment_get_list_files("images/flags", "png");
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'investment_get_list_yesno' ) ) {
	function investment_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'investment'),
			'no'  => esc_html__("No", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'investment_get_list_onoff' ) ) {
	function investment_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'investment'),
			"off" => esc_html__("Off", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'investment_get_list_showhide' ) ) {
	function investment_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'investment'),
			"hide" => esc_html__("Hide", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'investment_get_list_orderings' ) ) {
	function investment_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'investment'),
			"desc" => esc_html__("Descending", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'investment_get_list_directions' ) ) {
	function investment_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'investment'),
			"vertical" => esc_html__("Vertical", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'investment_get_list_shapes' ) ) {
	function investment_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'investment'),
			"square" => esc_html__("Square", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'investment_get_list_sizes' ) ) {
	function investment_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'investment'),
			"small"  => esc_html__("Small", 'investment'),
			"medium" => esc_html__("Medium", 'investment'),
			"large"  => esc_html__("Large", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'investment_get_list_controls' ) ) {
	function investment_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'investment'),
			"side" => esc_html__("Side", 'investment'),
			"bottom" => esc_html__("Bottom", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'investment_get_list_floats' ) ) {
	function investment_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'investment'),
			"left" => esc_html__("Float Left", 'investment'),
			"right" => esc_html__("Float Right", 'investment')
		);
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'investment_get_list_alignments' ) ) {
	function investment_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'investment'),
			"left" => esc_html__("Left", 'investment'),
			"center" => esc_html__("Center", 'investment'),
			"right" => esc_html__("Right", 'investment')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'investment');
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'investment_get_list_hpos' ) ) {
	function investment_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'investment');
		if ($center) $list['center'] = esc_html__("Center", 'investment');
		$list['right'] = esc_html__("Right", 'investment');
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'investment_get_list_vpos' ) ) {
	function investment_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'investment');
		if ($center) $list['center'] = esc_html__("Center", 'investment');
		$list['bottom'] = esc_html__("Bottom", 'investment');
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'investment_get_list_sortings' ) ) {
	function investment_get_list_sortings($prepend_inherit=false) {
		if (($list = investment_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'investment'),
				"title" => esc_html__("Alphabetically", 'investment'),
				"views" => esc_html__("Popular (views count)", 'investment'),
				"comments" => esc_html__("Most commented (comments count)", 'investment'),
				"author_rating" => esc_html__("Author rating", 'investment'),
				"users_rating" => esc_html__("Visitors (users) rating", 'investment'),
				"random" => esc_html__("Random", 'investment')
			);
			$list = apply_filters('investment_filter_list_sortings', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'investment_get_list_columns' ) ) {
	function investment_get_list_columns($prepend_inherit=false) {
		if (($list = investment_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'investment'),
				"1_1" => esc_html__("100%", 'investment'),
				"1_2" => esc_html__("1/2", 'investment'),
				"1_3" => esc_html__("1/3", 'investment'),
				"2_3" => esc_html__("2/3", 'investment'),
				"1_4" => esc_html__("1/4", 'investment'),
				"3_4" => esc_html__("3/4", 'investment'),
				"1_5" => esc_html__("1/5", 'investment'),
				"2_5" => esc_html__("2/5", 'investment'),
				"3_5" => esc_html__("3/5", 'investment'),
				"4_5" => esc_html__("4/5", 'investment'),
				"1_6" => esc_html__("1/6", 'investment'),
				"5_6" => esc_html__("5/6", 'investment'),
				"1_7" => esc_html__("1/7", 'investment'),
				"2_7" => esc_html__("2/7", 'investment'),
				"3_7" => esc_html__("3/7", 'investment'),
				"4_7" => esc_html__("4/7", 'investment'),
				"5_7" => esc_html__("5/7", 'investment'),
				"6_7" => esc_html__("6/7", 'investment'),
				"1_8" => esc_html__("1/8", 'investment'),
				"3_8" => esc_html__("3/8", 'investment'),
				"5_8" => esc_html__("5/8", 'investment'),
				"7_8" => esc_html__("7/8", 'investment'),
				"1_9" => esc_html__("1/9", 'investment'),
				"2_9" => esc_html__("2/9", 'investment'),
				"4_9" => esc_html__("4/9", 'investment'),
				"5_9" => esc_html__("5/9", 'investment'),
				"7_9" => esc_html__("7/9", 'investment'),
				"8_9" => esc_html__("8/9", 'investment'),
				"1_10"=> esc_html__("1/10", 'investment'),
				"3_10"=> esc_html__("3/10", 'investment'),
				"7_10"=> esc_html__("7/10", 'investment'),
				"9_10"=> esc_html__("9/10", 'investment'),
				"1_11"=> esc_html__("1/11", 'investment'),
				"2_11"=> esc_html__("2/11", 'investment'),
				"3_11"=> esc_html__("3/11", 'investment'),
				"4_11"=> esc_html__("4/11", 'investment'),
				"5_11"=> esc_html__("5/11", 'investment'),
				"6_11"=> esc_html__("6/11", 'investment'),
				"7_11"=> esc_html__("7/11", 'investment'),
				"8_11"=> esc_html__("8/11", 'investment'),
				"9_11"=> esc_html__("9/11", 'investment'),
				"10_11"=> esc_html__("10/11", 'investment'),
				"1_12"=> esc_html__("1/12", 'investment'),
				"5_12"=> esc_html__("5/12", 'investment'),
				"7_12"=> esc_html__("7/12", 'investment'),
				"10_12"=> esc_html__("10/12", 'investment'),
				"11_12"=> esc_html__("11/12", 'investment')
			);
			$list = apply_filters('investment_filter_list_columns', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'investment_get_list_dedicated_locations' ) ) {
	function investment_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = investment_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'investment'),
				"center"  => esc_html__('Above the text of the post', 'investment'),
				"left"    => esc_html__('To the left the text of the post', 'investment'),
				"right"   => esc_html__('To the right the text of the post', 'investment'),
				"alter"   => esc_html__('Alternates for each post', 'investment')
			);
			$list = apply_filters('investment_filter_list_dedicated_locations', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'investment_get_post_format_name' ) ) {
	function investment_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'investment') : esc_html__('galleries', 'investment');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'investment') : esc_html__('videos', 'investment');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'investment') : esc_html__('audios', 'investment');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'investment') : esc_html__('images', 'investment');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'investment') : esc_html__('quotes', 'investment');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'investment') : esc_html__('links', 'investment');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'investment') : esc_html__('statuses', 'investment');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'investment') : esc_html__('asides', 'investment');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'investment') : esc_html__('chats', 'investment');
		else						$name = $single ? esc_html__('standard', 'investment') : esc_html__('standards', 'investment');
		return apply_filters('investment_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'investment_get_post_format_icon' ) ) {
	function investment_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('investment_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'investment_get_list_fonts_styles' ) ) {
	function investment_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = investment_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','investment'),
				'u' => esc_html__('U', 'investment')
			);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'investment_get_list_fonts' ) ) {
	function investment_get_list_fonts($prepend_inherit=false) {
		if (($list = investment_storage_get('list_fonts'))=='') {
			$list = array();
			$list = investment_array_merge($list, investment_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>investment_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = investment_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('investment_filter_list_fonts', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'investment_get_list_font_faces' ) ) {
	function investment_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = investment_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? investment_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? investment_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>