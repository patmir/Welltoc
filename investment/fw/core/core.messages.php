<?php
/**
 * Investment Framework: messages subsystem
 *
 * @package	investment
 * @since	investment 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('investment_messages_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_messages_theme_setup' );
	function investment_messages_theme_setup() {
		// Core messages strings
		add_action('investment_action_add_scripts_inline', 'investment_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('investment_get_error_msg')) {
	function investment_get_error_msg() {
		return investment_storage_get('error_msg');
	}
}

if (!function_exists('investment_set_error_msg')) {
	function investment_set_error_msg($msg) {
		$msg2 = investment_get_error_msg();
		investment_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('investment_get_success_msg')) {
	function investment_get_success_msg() {
		return investment_storage_get('success_msg');
	}
}

if (!function_exists('investment_set_success_msg')) {
	function investment_set_success_msg($msg) {
		$msg2 = investment_get_success_msg();
		investment_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('investment_get_notice_msg')) {
	function investment_get_notice_msg() {
		return investment_storage_get('notice_msg');
	}
}

if (!function_exists('investment_set_notice_msg')) {
	function investment_set_notice_msg($msg) {
		$msg2 = investment_get_notice_msg();
		investment_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('investment_set_system_message')) {
	function investment_set_system_message($msg, $status='info', $hdr='') {
		update_option('investment_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('investment_get_system_message')) {
	function investment_get_system_message($del=false) {
		$msg = get_option('investment_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			investment_del_system_message();
		return $msg;
	}
}

if (!function_exists('investment_del_system_message')) {
	function investment_del_system_message() {
		delete_option('investment_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('investment_messages_add_scripts_inline')) {
	function investment_messages_add_scripts_inline() {
		echo '<script type="text/javascript">'
			
			. "if (typeof INVESTMENT_STORAGE == 'undefined') var INVESTMENT_STORAGE = {};"
			
			// Strings for translation
			. 'INVESTMENT_STORAGE["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'investment')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'investment')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'investment')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'investment')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'investment')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'investment')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'investment')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'investment')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'investment')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'investment')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'investment')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'investment')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'investment')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'investment')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'investment')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'investment')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'investment')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'investment')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'investment')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'investment')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'investment')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'investment')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'investment')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'investment')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'investment')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'investment')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'investment')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'investment')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'investment')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'investment')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'investment')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'investment')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'investment')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'investment')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'investment')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'investment')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'investment')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'investment')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'investment')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'investment')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'investment')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'investment')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>