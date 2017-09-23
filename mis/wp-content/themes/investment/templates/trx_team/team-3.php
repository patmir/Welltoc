<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'investment_template_team_3_theme_setup' ) ) {
	add_action( 'investment_action_before_init_theme', 'investment_template_team_3_theme_setup', 1 );
	function investment_template_team_3_theme_setup() {
		investment_add_template(array(
			'layout' => 'team-3',
			'template' => 'team-3',
			'mode'   => 'team',
			/*'container_classes' => 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side',*/
			'title'  => esc_html__('Team /Style 3/', 'investment'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'investment'),
			'w' => 370,
			'h' => 370
		));
	}
}

// Template output
if ( !function_exists( 'investment_template_team_3_output' ) ) {
	function investment_template_team_3_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (investment_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_team_item sc_team_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!investment_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(investment_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<div class="sc_team_item_avatar"><?php echo trim($post_options['photo']); ?>
					<div class="sc_team_item_hover">
						<div class="sc_team_item_socials"><?php echo trim($post_options['socials']); ?></div>
					</div>
				</div>
				<div class="sc_team_item_info">
                    <?php
                        if(!isset($post_data['post_id'])) $post_data['post_id']=1;
                    ?>
					<h3 class="sc_team_item_title"><?php echo (!empty($post_options['link']) ? '<a href="'.esc_url($post_options['link']).'">' : '') . (investment_get_post_title($post_data['post_id'])) . (!empty($post_options['link']) ? '</a>' : ''); ?></h3>
					<div class="sc_team_item_position"><?php echo (!empty($post_options['link']) ? '<a href="'.esc_url($post_options['link']).'">' : '') . ($post_options['position']) . (!empty($post_options['link']) ? '</a>' : ''); ?></div>
				</div>
			</div>
		<?php
		if (investment_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>