<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'investment_template_form_2_theme_setup' ) ) {
	add_action( 'investment_action_before_init_theme', 'investment_template_form_2_theme_setup', 1 );
	function investment_template_form_2_theme_setup() {
		investment_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'investment')
			));
	}
}

// Template output
if ( !function_exists( 'investment_template_form_2_output' ) ) {
	function investment_template_form_2_output($post_options, $post_data) {
		$address_1 = investment_get_theme_option('contact_address_1');
		$address_2 = investment_get_theme_option('contact_address_2');
		$phone = investment_get_theme_option('contact_phone');
		$fax = investment_get_theme_option('contact_fax');
		$email = investment_get_theme_option('contact_email');
		$open_hours = investment_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
            <div class="sc_form_fields column-1_2">
                <form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
                    <?php investment_sc_form_show_fields($post_options['fields']); ?>
                        <div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'investment'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'investment'); ?>"></div>
                        <div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'investment'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'investment'); ?>"></div>
                        <!--						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj">--><?php //esc_html_e('Subject', 'investment'); ?><!--</label><input id="sc_form_subj" type="text" name="subject" placeholder="--><?php //esc_attr_e('Subject', 'investment'); ?><!--"></div>-->
                    <div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'investment'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'investment'); ?>"></textarea></div>
                    <div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send Message', 'investment'); ?></button></div>
                    <div class="result sc_infobox"></div>
                </form>
            </div><div class="sc_form_address column-1_2">
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Address', 'investment'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_label"><?php esc_html_e('Phone', 'investment'); ?></span>
                    <span class="sc_form_address_data"><?php echo trim($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
                </div>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_label"><?php esc_html_e('E-mail', 'investment'); ?></span>
                    <span class="sc_form_address_data sc_form_address_data_email"><?php echo trim($email); ?></span>
                </div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('We are open', 'investment'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($open_hours); ?></span>
				</div>

			</div>
		</div>
		<?php
	}
}
?>