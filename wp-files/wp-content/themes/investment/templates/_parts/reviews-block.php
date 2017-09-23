<?php
// Get template args
extract(investment_template_get_args('reviews-block'));

$reviews_markup = '';
if ($avg_author > 0 || $avg_users > 0) {
	$reviews_first_author = investment_get_theme_option('reviews_first')=='author';
	$reviews_second_hide = investment_get_theme_option('reviews_second')=='hide';
	$use_tabs = !$reviews_second_hide; // && $avg_author > 0 && $avg_users > 0;
	if ($use_tabs) investment_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
	$max_level = max(5, (int) investment_get_custom_option('reviews_max_level'));
	$allow_user_marks = (!$reviews_first_author || !$reviews_second_hide) && (!isset($_COOKIE['investment_votes']) || investment_strpos($_COOKIE['investment_votes'], ','.($post_data['post_id']).',')===false) && (investment_get_theme_option('reviews_can_vote')=='all' || is_user_logged_in());
	$reviews_markup = '<h3 class="widget_title">'. esc_html__('Review', 'investment') .'</h3><div class="reviews_block'.($use_tabs ? ' sc_tabs sc_tabs_style_2' : '').'">';
	$output = $marks = $users = '';
	if ($use_tabs) {
		$author_tab = '<li class="sc_tabs_title"><a href="#author_marks" class="theme_button">'.esc_html__('Author', 'investment').'</a></li>';
		$users_tab = '<li class="sc_tabs_title"><a href="#users_marks" class="theme_button">'.esc_html__('Users', 'investment').'</a></li>';
		$output .= '<ul class="sc_tabs_titles">' . ($reviews_first_author ? ($author_tab) . ($users_tab) : ($users_tab) . ($author_tab)) . '</ul>';
	}
	// Criterias list
	$field = array(
		"options" => investment_get_theme_option('reviews_criterias')
	);
	if (!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy']]->terms)) {
		foreach ($post_data['post_terms'][$post_data['post_taxonomy']]->terms as $cat) {
			$id = (int) $cat->term_id;
			$prop = investment_taxonomy_get_inherited_property($post_data['post_taxonomy'], $id, 'reviews_criterias');
			if (!empty($prop) && !investment_is_inherit_option($prop)) {
				$field['options'] = $prop;
				break;
			}
		}
	}
	// Author marks
	if ($reviews_first_author || !$reviews_second_hide) {
		$field["id"] = "reviews_marks_author";
		$field["descr"] = strip_tags($post_data['post_excerpt']);
		$field["accept"] = false;
		$marks = investment_reviews_marks_to_display(investment_reviews_marks_prepare(investment_get_custom_option('reviews_marks'), count($field['options'])));
		$output .= '<div id="author_marks" class="sc_tabs_content">' . trim(investment_reviews_get_markup($field, $marks, false, false, $reviews_first_author)) . '</div>';
	}
	// Users marks
	if (!$reviews_first_author || !$reviews_second_hide) {
		$marks = investment_reviews_marks_to_display(investment_reviews_marks_prepare(get_post_meta($post_data['post_id'], 'investment_reviews_marks2', true), count($field['options'])));
		$users = max(0, get_post_meta($post_data['post_id'], 'investment_reviews_users', true));
		$field["id"] = "reviews_marks_users";
		$field["descr"] = wp_kses_data( sprintf(__("Summary rating from <b>%s</b> user's marks.", 'investment'), $users) 
									. ' ' 
                                    . ( !isset($_COOKIE['investment_votes']) || investment_strpos($_COOKIE['investment_votes'], ','.($post_data['post_id']).',')===false
											? __('You can set own marks for this article - just click on stars above and press "Accept".', 'investment')
                                            : __('Thanks for your vote!', 'investment')
                                      ) );
		$field["accept"] = $allow_user_marks;
		$output .= '<div id="users_marks" class="sc_tabs_content"'.(!$output ? ' style="display: block;"' : '') . '>' . trim(investment_reviews_get_markup($field, $marks, $allow_user_marks, false, !$reviews_first_author)) . '</div>';
	}
	$reviews_markup .= $output . '</div>';
	if ($allow_user_marks) {
		investment_enqueue_script('jquery-ui-draggable', false, array('jquery', 'jquery-ui-core'), null, true);
		$reviews_markup .= '
			<script type="text/javascript">
				jQuery(document).ready(function() {
					INVESTMENT_STORAGE["reviews_allow_user_marks"] = '.($allow_user_marks ? 'true' : 'false').';
					INVESTMENT_STORAGE["reviews_max_level"] = '.($max_level).';
					INVESTMENT_STORAGE["reviews_levels"] = "'.trim(investment_get_theme_option('reviews_criterias_levels')).'";
					INVESTMENT_STORAGE["reviews_vote"] = "'.(isset($_COOKIE['investment_votes']) ? $_COOKIE['investment_votes'] : '').'";
					INVESTMENT_STORAGE["reviews_marks"] = "'.($marks).'".split(",");
					INVESTMENT_STORAGE["reviews_users"] = '.max(0, $users).';
					INVESTMENT_STORAGE["post_id"] = '.($post_data['post_id']).';
				});
			</script>
		';
	}
}
investment_storage_set('reviews_markup', $reviews_markup);
?>