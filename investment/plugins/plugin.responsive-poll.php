<?php
/* Responsive Poll support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('investment_responsive_poll_theme_setup')) {
	add_action( 'investment_action_before_init_theme', 'investment_responsive_poll_theme_setup', 1 );
	function investment_responsive_poll_theme_setup() {
		// Register shortcode in the shortcodes list
		if (investment_exists_responsive_poll()) {
			add_action('investment_action_add_styles', 					'investment_responsive_poll_frontend_scripts');
			add_action('investment_action_shortcodes_list',				'investment_responsive_poll_reg_shortcodes');
			if (function_exists('investment_exists_visual_composer') && investment_exists_visual_composer())
				add_action('investment_action_shortcodes_list_vc',		'investment_responsive_poll_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'investment_filter_importer_options',			'investment_responsive_poll_importer_set_options', 10, 1 );
				add_action( 'investment_action_importer_params',			'investment_responsive_poll_importer_show_params', 10, 1 );
				add_action( 'investment_action_importer_import',			'investment_responsive_poll_importer_import', 10, 2 );
				add_action( 'investment_action_importer_import_fields',	'investment_responsive_poll_importer_import_fields', 10, 1 );
				add_action( 'investment_action_importer_export',			'investment_responsive_poll_importer_export', 10, 1 );
				add_action( 'investment_action_importer_export_fields',	'investment_responsive_poll_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'investment_filter_importer_required_plugins',	'investment_responsive_poll_importer_required_plugins', 10, 2 );
			add_filter( 'investment_filter_required_plugins',				'investment_responsive_poll_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'investment_exists_responsive_poll' ) ) {
	function investment_exists_responsive_poll() {
		return class_exists('Weblator_Polling');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'investment_responsive_poll_required_plugins' ) ) {
	//add_filter('investment_filter_required_plugins',	'investment_responsive_poll_required_plugins');
	function investment_responsive_poll_required_plugins($list=array()) {
		if (in_array('responsive_poll', investment_storage_get('required_plugins'))) {
			$path = investment_get_file_dir('plugins/install/responsive-poll.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Responsive Poll',
					'slug' 		=> 'responsive-poll',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'investment_responsive_poll_frontend_scripts' ) ) {
	//add_action( 'investment_action_add_styles', 'investment_responsive_poll_frontend_scripts' );
	function investment_responsive_poll_frontend_scripts() {
		if (file_exists(investment_get_file_dir('css/plugin.responsive-poll.css')))
			investment_enqueue_style( 'investment-plugin.responsive-poll-style',  investment_get_file_url('css/plugin.responsive-poll.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'investment_responsive_poll_importer_required_plugins' ) ) {
	//add_filter( 'investment_filter_importer_required_plugins',	'investment_responsive_poll_importer_required_plugins', 10, 2 );
	function investment_responsive_poll_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('responsive_poll', investment_storage_get('required_plugins')) && !investment_exists_responsive_poll() )
		if (investment_strpos($list, 'responsive_poll')!==false && !investment_exists_responsive_poll() )
			$not_installed .= '<br>Responsive Poll';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'investment_responsive_poll_importer_set_options' ) ) {
	//add_filter( 'investment_filter_importer_options',	'investment_responsive_poll_importer_set_options', 10, 1 );
	function investment_responsive_poll_importer_set_options($options=array()) {
		if ( in_array('responsive_poll', investment_storage_get('required_plugins')) && investment_exists_responsive_poll() ) {
			$options['file_with_responsive_poll'] = 'demo/responsive_poll.txt';			// Name of the file with Responsive Poll data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'investment_responsive_poll_importer_show_params' ) ) {
	//add_action( 'investment_action_importer_params',	'investment_responsive_poll_importer_show_params', 10, 1 );
	function investment_responsive_poll_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('responsive_poll', investment_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_responsive_poll" id="import_responsive_poll" /> <label for="import_responsive_poll"><?php esc_html_e('Import Responsive Poll', 'investment'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'investment_responsive_poll_importer_import' ) ) {
	//add_action( 'investment_action_importer_import',	'investment_responsive_poll_importer_import', 10, 2 );
	function investment_responsive_poll_importer_import($importer, $action) {
		if ( $action == 'import_responsive_poll' ) {
			$importer->import_dump('responsive_poll', esc_html__('Responsive Poll', 'investment'));
		}
	}
}

// Display import progress
if ( !function_exists( 'investment_responsive_poll_importer_import_fields' ) ) {
	//add_action( 'investment_action_importer_import_fields',	'investment_responsive_poll_importer_import_fields', 10, 1 );
	function investment_responsive_poll_importer_import_fields($importer) {
		?>
		<tr class="import_responsive_poll">
			<td class="import_progress_item"><?php esc_html_e('Responsive Poll', 'investment'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'investment_responsive_poll_importer_export' ) ) {
	//add_action( 'investment_action_importer_export',	'investment_responsive_poll_importer_export', 10, 1 );
	function investment_responsive_poll_importer_export($importer) {
		investment_storage_set('export_responsive_poll', serialize( array(
			'weblator_polls'		=> $importer->export_dump('weblator_polls'),
			'weblator_poll_options'	=> $importer->export_dump('weblator_poll_options'),
			'weblator_poll_votes'	=> $importer->export_dump('weblator_poll_votes')
			) )
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'investment_responsive_poll_importer_export_fields' ) ) {
	//add_action( 'investment_action_importer_export_fields',	'investment_responsive_poll_importer_export_fields', 10, 1 );
	function investment_responsive_poll_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('Responsive Poll', 'investment'); ?></th>
			<td><?php investment_fpc(investment_get_file_dir('core/core.importer/export/responsive_poll.txt'), investment_storage_get('export_responsive_poll')); ?>
				<a download="responsive_poll.txt" href="<?php echo esc_url(investment_get_file_url('core/core.importer/export/responsive_poll.txt')); ?>"><?php esc_html_e('Download', 'investment'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Responsive Pollst list, prepended inherit (if need)
if ( !function_exists( 'investment_get_list_responsive_polls' ) ) {
	function investment_get_list_responsive_polls($prepend_inherit=false) {
		if (($list = investment_storage_get('list_responsive_polls'))=='') {
			$list = array();
			if (investment_exists_responsive_poll()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, poll_name FROM " . esc_sql($wpdb->prefix . 'weblator_polls') );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->poll_name;
					}
				}
			}
			$list = apply_filters('investment_filter_list_responsive_polls', $list);
			if (investment_get_theme_setting('use_list_cache')) investment_storage_set('list_responsive_polls', $list);
		}
		return $prepend_inherit ? investment_array_merge(array('inherit' => esc_html__("Inherit", 'investment')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('investment_responsive_poll_reg_shortcodes')) {
	//add_filter('investment_action_shortcodes_list',	'investment_responsive_poll_reg_shortcodes');
	function investment_responsive_poll_reg_shortcodes() {
		if (investment_storage_isset('shortcodes')) {

			$polls_list = investment_get_list_responsive_polls();

			investment_sc_map_before('trx_popup', 'poll', array(
					"title" => esc_html__("Poll", 'investment'),
					"desc" => esc_html__("Insert poll", 'investment'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Poll ID", 'investment'),
							"desc" => esc_html__("Select Poll to insert into current page", 'investment'),
							"value" => "",
							"size" => "medium",
							"options" => $polls_list,
							"type" => "select"
							)
						)
					)
			);
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('investment_responsive_poll_reg_shortcodes_vc')) {
	//add_filter('investment_action_shortcodes_list_vc',	'investment_responsive_poll_reg_shortcodes_vc');
	function investment_responsive_poll_reg_shortcodes_vc() {

		$polls_list = investment_get_list_responsive_polls();

		// Calculated fields form
		vc_map( array(
				"base" => "poll",
				"name" => esc_html__("Poll", 'investment'),
				"description" => esc_html__("Insert poll", 'investment'),
				"category" => esc_html__('Content', 'investment'),
				'icon' => 'icon_trx_poll',
				"class" => "trx_sc_single trx_sc_poll",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Poll ID", 'investment'),
						"description" => esc_html__("Select Poll to insert into current page", 'investment'),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($polls_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Poll extends INVESTMENT_VC_ShortCodeSingle {}

	}
}
?>