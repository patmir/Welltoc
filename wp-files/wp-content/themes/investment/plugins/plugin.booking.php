<?php
/* Booking Calendar support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('investment_booking_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_booking_theme_setup', 1 );
	function investment_booking_theme_setup() {
		// Register shortcode in the shortcodes list
		if (investment_exists_booking()) {
			add_action('investment_action_add_styles',					'investment_booking_frontend_scripts');
			add_action('investment_action_shortcodes_list',				'investment_booking_reg_shortcodes');
			if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
				add_action('investment_action_shortcodes_list_vc',		'investment_booking_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'investment_filter_importer_options',			'investment_booking_importer_set_options' );
				add_action( 'investment_action_importer_params',			'investment_booking_importer_show_params', 10, 1 );
				add_action( 'investment_action_importer_import',			'investment_booking_importer_import', 10, 2 );
				add_action( 'investment_action_importer_import_fields',	'investment_booking_importer_import_fields', 10, 1 );
				add_action( 'investment_action_importer_export',			'investment_booking_importer_export', 10, 1 );
				add_action( 'investment_action_importer_export_fields',	'investment_booking_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'investment_filter_importer_required_plugins',	'investment_booking_importer_required_plugins', 10, 2);
			add_filter( 'investment_filter_required_plugins',				'investment_booking_required_plugins' );
		}
	}
}


// Check if Booking Calendar installed and activated
if ( !function_exists( 'investment_exists_booking' ) ) {
	function investment_exists_booking() {
		return function_exists('wp_booking_start_session');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'investment_booking_required_plugins' ) ) {
	//add_filter('investment_filter_required_plugins',	'investment_booking_required_plugins');
	function investment_booking_required_plugins($list=array()) {
		if (in_array('booking', investment_storage_get('required_plugins'))) {
			$path = investment_get_file_dir('plugins/install/wp-booking-calendar.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Booking Calendar',
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'investment_booking_frontend_scripts' ) ) {
	//add_action( 'investment_action_add_styles', 'investment_booking_frontend_scripts' );
	function investment_booking_frontend_scripts() {
		if (file_exists(investment_get_file_dir('css/plugin.booking.css')))
			investment_enqueue_style( 'investment-plugin.booking-style',  investment_get_file_url('css/plugin.booking.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'investment_booking_importer_required_plugins' ) ) {
	//add_filter( 'investment_filter_importer_required_plugins',	'investment_booking_importer_required_plugins', 10, 2);
	function investment_booking_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booking', investment_storage_get('required_plugins')) && !investment_exists_booking() )
		if (investment_strpos($list, 'booking')!==false && !investment_exists_booking() )
			$not_installed .= '<br>Booking Calendar';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'investment_booking_importer_set_options' ) ) {
	//add_filter( 'investment_filter_importer_options',	'investment_booking_importer_set_options', 10, 1 );
	function investment_booking_importer_set_options($options=array()) {
		if ( in_array('booking', investment_storage_get('required_plugins')) && investment_exists_booking() ) {
			$options['file_with_booking'] = 'demo/booking.txt';			// Name of the file with Booking Calendar data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'investment_booking_importer_show_params' ) ) {
	//add_action( 'investment_action_importer_params',	'investment_booking_importer_show_params', 10, 1 );
	function investment_booking_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('booking', investment_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_booking" id="import_booking" /> <label for="import_booking"><?php esc_html_e('Import Booking Calendar', 'investment'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'investment_booking_importer_import' ) ) {
	//add_action( 'investment_action_importer_import',	'investment_booking_importer_import', 10, 2 );
	function investment_booking_importer_import($importer, $action) {
		if ( $action == 'import_booking' ) {
			$importer->import_dump('booking', esc_html__('Booking Calendar', 'investment'));
		}
	}
}

// Display import progress
if ( !function_exists( 'investment_booking_importer_import_fields' ) ) {
	//add_action( 'investment_action_importer_import_fields',	'investment_booking_importer_import_fields', 10, 1 );
	function investment_booking_importer_import_fields($importer) {
		?>
		<tr class="import_booking">
			<td class="import_progress_item"><?php esc_html_e('Booking Calendar', 'investment'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'investment_booking_importer_export' ) ) {
	//add_action( 'investment_action_importer_export',	'investment_booking_importer_export', 10, 1 );
	function investment_booking_importer_export($importer) {
		investment_storage_set('export_booking', serialize( array(
			"booking_calendars"		=> $importer->export_dump("booking_calendars"),
			"booking_categories"	=> $importer->export_dump("booking_categories"),
            "booking_config"		=> $importer->export_dump("booking_config"),
            "booking_reservation"	=> $importer->export_dump("booking_reservation"),
            "booking_slots"			=> $importer->export_dump("booking_slots")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'investment_booking_importer_export_fields' ) ) {
	//add_action( 'investment_action_importer_export_fields',	'investment_booking_importer_export_fields', 10, 1 );
	function investment_booking_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('Booking', 'investment'); ?></th>
			<td><?php investment_fpc(investment_get_file_dir('core/core.importer/export/booking.txt'), investment_storage_get('export_booking')); ?>
				<a download="booking.txt" href="<?php echo esc_url(investment_get_file_url('core/core.importer/export/booking.txt')); ?>"><?php esc_html_e('Download', 'investment'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Booking categories list, prepended inherit (if need)
if ( !function_exists( 'investment_get_list_booking_categories' ) ) {
	function investment_get_list_booking_categories($prepend_inherit=false) {
		if (($list = investment_storage_get('list_booking_cats'))=='') {
			$list = array();
			if (investment_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT category_id, category_name FROM " . esc_sql($wpdb->prefix . 'booking_categories') );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->category_id] = $row->category_name;
					}
				}
			}
			$list = apply_filters('investment_filter_list_booking_categories', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_booking_cats', $list); 
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}

// Return Booking calendars list, prepended inherit (if need)
if ( !function_exists( 'investment_get_list_booking_calendars' ) ) {
	function investment_get_list_booking_calendars($prepend_inherit=false) {
		if (($list = investment_storage_get('list_booking_calendars'))=='') {
			$list = array();
			if (investment_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT cl.calendar_id, cl.calendar_title, ct.category_name FROM " . esc_sql($wpdb->prefix . 'booking_calendars') . " AS cl"
												. " INNER JOIN " . esc_sql($wpdb->prefix . 'booking_categories') . " AS ct ON cl.category_id=ct.category_id"
										);
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->calendar_id] = $row->calendar_title . ' (' . $row->category_name . ')';
					}
				}
			}
			$list = apply_filters('investment_filter_list_booking_calendars', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_booking_calendars', $list); 
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('investment_booking_reg_shortcodes')) {
	//add_filter('investment_action_shortcodes_list',	'investment_booking_reg_shortcodes');
	function investment_booking_reg_shortcodes() {
		if (investment_storage_isset('shortcodes')) {

			$booking_cats = investment_get_list_booking_categories();
			$booking_cals = investment_get_list_booking_calendars();

			investment_sc_map('wp_booking_calendar', array(
				"title" => esc_html__("Booking Calendar", 'investment'),
				"desc" => esc_html__("Insert Booking calendar", 'investment'),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"category_id" => array(
						"title" => esc_html__("Category", 'investment'),
						"desc" => esc_html__("Select booking category", 'investment'),
						"value" => "",
						"type" => "select",
						"options" => investment_array_merge(array(0 => esc_html__('- Select category -', 'investment')), $booking_cats)
					),
					"calendar_id" => array(
						"title" => esc_html__("Calendar", 'investment'),
						"desc" => esc_html__("or select booking calendar (id category is empty)", 'investment'),
						"dependency" => array(
							'category_id' => array('empty', '0')
						),
						"value" => "",
						"type" => "select",
						"options" => investment_array_merge(array(0 => esc_html__('- Select calendar -', 'investment')), $booking_cals)
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('investment_booking_reg_shortcodes_vc')) {
	//add_filter('investment_action_shortcodes_list_vc',	'investment_booking_reg_shortcodes_vc');
	function investment_booking_reg_shortcodes_vc() {

		$booking_cats = investment_get_list_booking_categories();
		$booking_cals = investment_get_list_booking_calendars();


		// Investment Donations form
		vc_map( array(
				"base" => "wp_booking_calendar",
				"name" => esc_html__("Booking Calendar", 'investment'),
				"description" => esc_html__("Insert Booking calendar", 'investment'),
				"category" => esc_html__('Content', 'investment'),
				'icon' => 'icon_trx_booking',
				"class" => "trx_sc_single trx_sc_booking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "category_id",
						"heading" => esc_html__("Category", 'investment'),
						"description" => esc_html__("Select Booking category", 'investment'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(investment_array_merge(array(0 => esc_html__('- Select category -', 'investment')), $booking_cats)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "calendar_id",
						"heading" => esc_html__("Calendar", 'investment'),
						"description" => esc_html__("Select Booking calendar", 'investment'),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'category_id',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(investment_array_merge(array(0 => esc_html__('- Select calendar -', 'investment')), $booking_cals)),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Wp_Booking_Calendar extends INVESTMENT_VC_ShortCodeSingle {}

	}
}
?>