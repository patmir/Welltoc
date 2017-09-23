// Init scripts
jQuery(document).ready(function(){
	"use strict";
	
	// Settings and constants
	INVESTMENT_STORAGE['shortcodes_delimiter'] = ',';		// Delimiter for multiple values
	INVESTMENT_STORAGE['shortcodes_popup'] = null;		// Popup with current shortcode settings
	INVESTMENT_STORAGE['shortcodes_current_idx'] = '';	// Current shortcode's index
	INVESTMENT_STORAGE['shortcodes_tab_clone_tab'] = '<li id="investment_shortcodes_tab_{id}" data-id="{id}"><a href="#investment_shortcodes_tab_{id}_content"><span class="iconadmin-{icon}"></span>{title}</a></li>';
	INVESTMENT_STORAGE['shortcodes_tab_clone_content'] = '';

	// Shortcode selector - "change" event handler - add selected shortcode in editor
	jQuery('body').on('change', ".sc_selector", function() {
		"use strict";
		INVESTMENT_STORAGE['shortcodes_current_idx'] = jQuery(this).find(":selected").val();
		if (INVESTMENT_STORAGE['shortcodes_current_idx'] == '') return;
		var sc = investment_clone_object(INVESTMENT_STORAGE['shortcodes'][INVESTMENT_STORAGE['shortcodes_current_idx']]);
		var hdr = sc.title;
		var content = "";
		try {
			content = tinyMCE.activeEditor ? tinyMCE.activeEditor.selection.getContent({format : 'raw'}) : jQuery('#wp-content-editor-container textarea').selection();
		} catch(e) {};
		if (content) {
			for (var i in sc.params) {
				if (i == '_content_') {
					sc.params[i].value = content;
					break;
				}
			}
		}
		var html = (!investment_empty(sc.desc) ? '<p>'+sc.desc+'</p>' : '')
			+ investment_shortcodes_prepare_layout(sc);


		// Show Dialog popup
		INVESTMENT_STORAGE['shortcodes_popup'] = investment_message_dialog(html, hdr,
			function(popup) {
				"use strict";
				investment_options_init(popup);
				popup.find('.investment_options_tab_content').css({
					maxHeight: jQuery(window).height() - 300 + 'px',
					overflow: 'auto'
				});
			},
			function(btn, popup) {
				"use strict";
				if (btn != 1) return;
				var sc = investment_shortcodes_get_code(INVESTMENT_STORAGE['shortcodes_popup']);
				if (tinyMCE.activeEditor) {
					if ( !tinyMCE.activeEditor.isHidden() )
						tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, sc );
					//else if (typeof wpActiveEditor != 'undefined' && wpActiveEditor != '') {
					//	document.getElementById( wpActiveEditor ).value += sc;
					else
						send_to_editor(sc);
				} else
					send_to_editor(sc);
			});

		// Set first item active
		jQuery(this).get(0).options[0].selected = true;

		// Add new child tab
		INVESTMENT_STORAGE['shortcodes_popup'].find('.investment_shortcodes_tab').on('tabsbeforeactivate', function (e, ui) {
			if (ui.newTab.data('id')=='add') {
				investment_shortcodes_add_tab(ui.newTab);
				e.stopImmediatePropagation();
				e.preventDefault();
				return false;
			}
		});

		// Delete child tab
		INVESTMENT_STORAGE['shortcodes_popup'].find('.investment_shortcodes_tab > ul').on('click', '> li+li > a > span', function (e) {
			var tab = jQuery(this).parents('li');
			var idx = tab.data('id');
			if (parseInt(idx) > 1) {
				if (tab.hasClass('ui-state-active')) {
					tab.prev().find('a').trigger('click');
				}
				tab.parents('.investment_shortcodes_tab').find('.investment_options_tab_content').eq(idx).remove();
				tab.remove();
				e.preventDefault();
				return false;
			}
		});

		return false;
	});

});



// Return result code
//------------------------------------------------------------------------------------------
function investment_shortcodes_get_code(popup) {
	INVESTMENT_STORAGE['sc_custom'] = '';
	
	var sc_name = INVESTMENT_STORAGE['shortcodes_current_idx'];
	var sc = INVESTMENT_STORAGE['shortcodes'][sc_name];
	var tabs = popup.find('.investment_shortcodes_tab > ul > li');
	var decor = !investment_isset(sc.decorate) || sc.decorate;
	var rez = '[' + sc_name + investment_shortcodes_get_code_from_tab(popup.find('#investment_shortcodes_tab_0_content').eq(0)) + ']'
			// + (decor ? '\n' : '')
			;
	if (investment_isset(sc.children)) {
		if (INVESTMENT_STORAGE['sc_custom']!='no') {
			var decor2 = !investment_isset(sc.children.decorate) || sc.children.decorate;
			for (var i=0; i<tabs.length; i++) {
				var tab = tabs.eq(i);
				var idx = tab.data('id');
				if (isNaN(idx) || parseInt(idx) < 1) continue;
				var content = popup.find('#investment_shortcodes_tab_' + idx + '_content').eq(0);
				rez += (decor2 ? '\n\t' : '') + '[' + sc.children.name + investment_shortcodes_get_code_from_tab(content) + ']';	// + (decor2 ? '\n' : '');
				if (investment_isset(sc.children.container) && sc.children.container) {
					if (content.find('[data-param="_content_"]').length > 0) {
						rez += 
							//(decor2 ? '\t\t' : '') + 
							content.find('[data-param="_content_"]').val()
							// + (decor2 ? '\n' : '')
							;
					}
					rez += 
						//(decor2 ? '\t' : '') + 
						'[/' + sc.children.name + ']'
						// + (decor ? '\n' : '')
						;
				}
			}
		}
	} else if (investment_isset(sc.container) && sc.container && popup.find('#investment_shortcodes_tab_0_content [data-param="_content_"]').length > 0) {
		rez += 
			//(decor ? '\t' : '') + 
			popup.find('#investment_shortcodes_tab_0_content [data-param="_content_"]').val()
			// + (decor ? '\n' : '')
			;
	}
	if (investment_isset(sc.container) && sc.container || investment_isset(sc.children))
		rez += 
			(investment_isset(sc.children) && decor && INVESTMENT_STORAGE['sc_custom']!='no' ? '\n' : '')
			+ '[/' + sc_name + ']'
			 //+ (decor ? '\n' : '')
			 ;
	return rez;
}

// Collect all parameters from tab into string
function investment_shortcodes_get_code_from_tab(tab) {
	var rez = ''
	var mainTab = tab.attr('id').indexOf('tab_0') > 0;
	tab.find('[data-param]').each(function () {
		var field = jQuery(this);
		var param = field.data('param');
		if (!field.parents('.investment_options_field').hasClass('investment_options_no_use') && param.substr(0, 1)!='_' && !investment_empty(field.val()) && field.val()!='none' && (field.attr('type') != 'checkbox' || field.get(0).checked)) {
			rez += ' '+param+'="'+investment_shortcodes_prepare_value(field.val())+'"';
		}
		// On main tab detect param "custom"
		if (mainTab && param=='custom') {
			INVESTMENT_STORAGE['sc_custom'] = field.val();
		}
	});
	// Get additional params for general tab from items tabs
	if (INVESTMENT_STORAGE['sc_custom']!='no' && mainTab) {
		var sc = INVESTMENT_STORAGE['shortcodes'][INVESTMENT_STORAGE['shortcodes_current_idx']];
		var sc_name = INVESTMENT_STORAGE['shortcodes_current_idx'];
		if (sc_name == 'trx_columns' || sc_name == 'trx_skills' || sc_name == 'trx_team' || sc_name == 'trx_price_table') {	// Determine "count" parameter
			var cnt = 0;
			tab.siblings('div').each(function() {
				var item_tab = jQuery(this);
				var merge = parseInt(item_tab.find('[data-param="span"]').val());
				cnt += !isNaN(merge) && merge > 0 ? merge : 1;
			});
			rez += ' count="'+cnt+'"';
		}
	}
	return rez;
}


// Shortcode parameters builder
//-------------------------------------------------------------------------------------------

// Prepare layout from shortcode object (array)
function investment_shortcodes_prepare_layout(field) {
	"use strict";
	// Make params cloneable
	field['params'] = [field['params']];
	if (!investment_empty(field.children)) {
		field.children['params'] = [field.children['params']];
	}
	// Prepare output
	var output = '<div class="investment_shortcodes_body investment_options_body"><form>';
	output += investment_shortcodes_show_tabs(field);
	output += investment_shortcodes_show_field(field, 0);
	if (!investment_empty(field.children)) {
		INVESTMENT_STORAGE['shortcodes_tab_clone_content'] = investment_shortcodes_show_field(field.children, 1);
		output += INVESTMENT_STORAGE['shortcodes_tab_clone_content'];
	}
	output += '</div></form></div>';
	return output;
}



// Show tabs
function investment_shortcodes_show_tabs(field) {
	"use strict";
	// html output
	var output = '<div class="investment_shortcodes_tab investment_options_container investment_options_tab">'
		+ '<ul>'
		+ INVESTMENT_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 0).replace('{icon}', 'cog').replace('{title}', 'General');
	if (investment_isset(field.children)) {
		for (var i=0; i<field.children.params.length; i++)
			output += INVESTMENT_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, i+1).replace('{icon}', 'cancel').replace('{title}', field.children.title + ' ' + (i+1));
		output += INVESTMENT_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 'add').replace('{icon}', 'list-add').replace('{title}', '');
	}
	output += '</ul>';
	return output;
}

// Add new tab
function investment_shortcodes_add_tab(tab) {
	"use strict";
	var idx = 0;
	tab.siblings().each(function () {
		"use strict";
		var i = parseInt(jQuery(this).data('id'));
		if (i > idx) idx = i;
	});
	idx++;
	tab.before( INVESTMENT_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, idx).replace('{icon}', 'cancel').replace('{title}', INVESTMENT_STORAGE['shortcodes'][INVESTMENT_STORAGE['shortcodes_current_idx']].children.title + ' ' + idx) );
	tab.parents('.investment_shortcodes_tab').append(INVESTMENT_STORAGE['shortcodes_tab_clone_content'].replace(/tab_1_/g, 'tab_' + idx + '_'));
	tab.parents('.investment_shortcodes_tab').tabs('refresh');
	investment_options_init(tab.parents('.investment_shortcodes_tab').find('.investment_options_tab_content').eq(idx));
	tab.prev().find('a').trigger('click');
}



// Show one field layout
function investment_shortcodes_show_field(field, tab_idx) {
	"use strict";
	
	// html output
	var output = '';

	// Parse field params
	for (var clone_num in field['params']) {
		var tab_id = 'tab_' + (parseInt(tab_idx) + parseInt(clone_num));
		output += '<div id="investment_shortcodes_' + tab_id + '_content" class="investment_options_content investment_options_tab_content">';

		for (var param_num in field['params'][clone_num]) {
			
			var param = field['params'][clone_num][param_num];
			var id = tab_id + '_' + param_num;
	
			// Divider after field
			var divider = investment_isset(param['divider']) && param['divider'] ? ' investment_options_divider' : '';
		
			// Setup default parameters
			if (param['type']=='media') {
				if (!investment_isset(param['before'])) param['before'] = {};
				param['before'] = investment_merge_objects({
						'title': 'Choose image',
						'action': 'media_upload',
						'type': 'image',
						'multiple': false,
						'sizes': false,
						'linked_field': '',
						'captions': { 	
							'choose': 'Choose image',
							'update': 'Select image'
							}
					}, param['before']);
				if (!investment_isset(param['after'])) param['after'] = {};
				param['after'] = investment_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'media_reset'
					}, param['after']);
			}
			if (param['type']=='color' && (INVESTMENT_STORAGE['shortcodes_cp']=='tiny' || (investment_isset(param['style']) && param['style']!='wp'))) {
				if (!investment_isset(param['after'])) param['after'] = {};
				param['after'] = investment_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'color_reset'
					}, param['after']);
			}
		
			// Buttons before and after field
			var before = '', after = '', buttons_classes = '', rez, rez2, i, key, opt;
			
			if (investment_isset(param['before'])) {
				rez = investment_shortcodes_action_button(param['before'], 'before');
				before = rez[0];
				buttons_classes += rez[1];
			}
			if (investment_isset(param['after'])) {
				rez = investment_shortcodes_action_button(param['after'], 'after');
				after = rez[0];
				buttons_classes += rez[1];
			}
			if (investment_in_array(param['type'], ['list', 'select', 'fonts']) || (param['type']=='socials' && (investment_empty(param['style']) || param['style']=='icons'))) {
				buttons_classes += ' investment_options_button_after_small';
			}

			if (param['type'] != 'hidden') {
				output += '<div class="investment_options_field'
					+ ' investment_options_field_' + (investment_in_array(param['type'], ['list','fonts']) ? 'select' : param['type'])
					+ (investment_in_array(param['type'], ['media', 'fonts', 'list', 'select', 'socials', 'date', 'time']) ? ' investment_options_field_text'  : '')
					+ (param['type']=='socials' && !investment_empty(param['style']) && param['style']=='images' ? ' investment_options_field_images'  : '')
					+ (param['type']=='socials' && (investment_empty(param['style']) || param['style']=='icons') ? ' investment_options_field_icons'  : '')
					+ (investment_isset(param['dir']) && param['dir']=='vertical' ? ' investment_options_vertical' : '')
					+ (!investment_empty(param['multiple']) ? ' investment_options_multiple' : '')
					+ (investment_isset(param['size']) ? ' investment_options_size_'+param['size'] : '')
					+ (investment_isset(param['class']) ? ' ' + param['class'] : '')
					+ divider 
					+ '">' 
					+ "\n"
					+ '<label class="investment_options_field_label" for="' + id + '">' + param['title']
					+ '</label>'
					+ "\n"
					+ '<div class="investment_options_field_content'
					+ buttons_classes
					+ '">'
					+ "\n";
			}
			
			if (!investment_isset(param['value'])) {
				param['value'] = '';
			}
			

			switch ( param['type'] ) {
	
			case 'hidden':
				output += '<input class="investment_options_input investment_options_input_hidden" name="' + id + '" id="' + id + '" type="hidden" value="' + investment_shortcodes_prepare_value(param['value']) + '" data-param="' + investment_shortcodes_prepare_value(param_num) + '" />';
			break;

			case 'date':
				if (investment_isset(param['style']) && param['style']=='inline') {
					output += '<div class="investment_options_input_date"'
						+ ' id="' + id + '_calendar"'
						+ ' data-format="' + (!investment_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!investment_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-linked-field="' + (!investment_empty(data['linked_field']) ? data['linked_field'] : id) + '"'
						+ '></div>'
						+ '<input id="' + id + '"'
							+ ' name="' + id + '"'
							+ ' type="hidden"'
							+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
							+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
							+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
							+ ' />';
				} else {
					output += '<input class="investment_options_input investment_options_input_date' + (!investment_empty(param['mask']) ? ' investment_options_input_masked' : '') + '"'
						+ ' name="' + id + '"'
						+ ' id="' + id + '"'
						+ ' type="text"'
						+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-format="' + (!investment_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!investment_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
						+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
						+ before 
						+ after;
				}
			break;

			case 'text':
				output += '<input class="investment_options_input investment_options_input_text' + (!investment_empty(param['mask']) ? ' investment_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
					+ (!investment_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
				+ before 
				+ after;
			break;
		
			case 'textarea':
				var cols = investment_isset(param['cols']) && param['cols'] > 10 ? param['cols'] : '40';
				var rows = investment_isset(param['rows']) && param['rows'] > 1 ? param['rows'] : '8';
				output += '<textarea class="investment_options_input investment_options_input_textarea"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' cols="' + cols + '"'
					+ ' rows="' + rows + '"'
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ '>'
					+ param['value']
					+ '</textarea>';
			break;

			case 'spinner':
				output += '<input class="investment_options_input investment_options_input_spinner' + (!investment_empty(param['mask']) ? ' investment_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"' 
					+ (!investment_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ (investment_isset(param['min']) ? ' data-min="'+param['min']+'"' : '') 
					+ (investment_isset(param['max']) ? ' data-max="'+param['max']+'"' : '') 
					+ (!investment_empty(param['step']) ? ' data-step="'+param['step']+'"' : '') 
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />' 
					+ '<span class="investment_options_arrows"><span class="investment_options_arrow_up iconadmin-up-dir"></span><span class="investment_options_arrow_down iconadmin-down-dir"></span></span>';
			break;

			case 'tags':
				var tags = param['value'].split(INVESTMENT_STORAGE['shortcodes_delimiter']);
				if (tags.length > 0) {
					for (i=0; i<tags.length; i++) {
						if (investment_empty(tags[i])) continue;
						output += '<span class="investment_options_tag iconadmin-cancel">' + tags[i] + '</span>';
					}
				}
				output += '<input class="investment_options_input_tags"'
					+ ' type="text"'
					+ ' value=""'
					+ ' />'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
						+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case "checkbox": 
				output += '<input type="checkbox" class="investment_options_input investment_options_input_checkbox"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' value="true"' 
					+ (param['value'] == 'true' ? ' checked="checked"' : '') 
					+ (!investment_empty(param['disabled']) ? ' readonly="readonly"' : '') 
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<label for="' + id + '" class="' + (!investment_empty(param['disabled']) ? 'investment_options_state_disabled' : '') + (param['value']=='true' ? ' investment_options_state_checked' : '') + '"><span class="investment_options_input_checkbox_image iconadmin-check"></span>' + (!investment_empty(param['label']) ? param['label'] : param['title']) + '</label>';
			break;
		
			case "radio":
				for (key in param['options']) { 
					output += '<span class="investment_options_radioitem"><input class="investment_options_input investment_options_input_radio" type="radio"'
						+ ' name="' + id + '"'
						+ ' value="' + investment_shortcodes_prepare_value(key) + '"'
						+ ' data-value="' + investment_shortcodes_prepare_value(key) + '"'
						+ (param['value'] == key ? ' checked="checked"' : '') 
						+ ' id="' + id + '_' + key + '"'
						+ ' />'
						+ '<label for="' + id + '_' + key + '"' + (param['value'] == key ? ' class="investment_options_state_checked"' : '') + '><span class="investment_options_input_radio_image iconadmin-circle-empty' + (param['value'] == key ? ' iconadmin-dot-circled' : '') + '"></span>' + param['options'][key] + '</label></span>';
				}
				output += '<input type="hidden"'
						+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
						+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';

			break;
		
			case "switch":
				opt = [];
				i = 0;
				for (key in param['options']) {
					opt[i++] = {'key': key, 'title': param['options'][key]};
					if (i==2) break;
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + investment_shortcodes_prepare_value(investment_empty(param['value']) ? opt[0]['key'] : param['value']) + '"'
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<span class="investment_options_switch' + (param['value']==opt[1]['key'] ? ' investment_options_state_off' : '') + '"><span class="investment_options_switch_inner iconadmin-circle"><span class="investment_options_switch_val1" data-value="' + opt[0]['key'] + '">' + opt[0]['title'] + '</span><span class="investment_options_switch_val2" data-value="' + opt[1]['key'] + '">' + opt[1]['title'] + '</span></span></span>';
			break;

			case 'media':
				output += '<input class="investment_options_input investment_options_input_text investment_options_input_media"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
					+ (!investment_isset(param['readonly']) || param['readonly'] ? ' readonly="readonly"' : '')
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before 
					+ after;
				if (!investment_empty(param['value'])) {
					var fname = investment_get_file_name(param['value']);
					var fext  = investment_get_file_ext(param['value']);
					output += '<a class="investment_options_image_preview" rel="prettyPhoto" target="_blank" href="' + param['value'] + '">' + (fext!='' && investment_in_list('jpg,png,gif', fext, ',') ? '<img src="'+param['value']+'" alt="" />' : '<span>'+fname+'</span>') + '</a>';
				}
			break;
		
			case 'button':
				rez = investment_shortcodes_action_button(param, 'button');
				output += rez[0];
			break;

			case 'range':
				output += '<div class="investment_options_input_range" data-step="'+(!investment_empty(param['step']) ? param['step'] : 1) + '">'
					+ '<span class="investment_options_range_scale"><span class="investment_options_range_scale_filled"></span></span>';
				if (param['value'].toString().indexOf(INVESTMENT_STORAGE['shortcodes_delimiter']) == -1)
					param['value'] = Math.min(param['max'], Math.max(param['min'], param['value']));
				var sliders = param['value'].toString().split(INVESTMENT_STORAGE['shortcodes_delimiter']);
				for (i=0; i<sliders.length; i++) {
					output += '<span class="investment_options_range_slider"><span class="investment_options_range_slider_value">' + sliders[i] + '</span><span class="investment_options_range_slider_button"></span></span>';
				}
				output += '<span class="investment_options_range_min">' + param['min'] + '</span><span class="investment_options_range_max">' + param['max'] + '</span>'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
						+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
					+ '</div>';			
			break;
		
			case "checklist":
				for (key in param['options']) { 
					output += '<span class="investment_options_listitem'
						+ (investment_in_list(param['value'], key, INVESTMENT_STORAGE['shortcodes_delimiter']) ? ' investment_options_state_checked' : '') + '"'
						+ ' data-value="' + investment_shortcodes_prepare_value(key) + '"'
						+ '>'
						+ param['options'][key]
						+ '</span>';
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />';
			break;
		
			case 'fonts':
				for (key in param['options']) {
					param['options'][key] = key;
				}
			case 'list':
			case 'select':
				if (!investment_isset(param['options']) && !investment_empty(param['from']) && !investment_empty(param['to'])) {
					param['options'] = [];
					for (i = param['from']; i <= param['to']; i+=(!investment_empty(param['step']) ? param['step'] : 1)) {
						param['options'][i] = i;
					}
				}
				rez = investment_shortcodes_menu_list(param);
				if (investment_empty(param['style']) || param['style']=='select') {
					output += '<input class="investment_options_input investment_options_input_select" type="text" value="' + investment_shortcodes_prepare_value(rez[1]) + '"'
						+ ' readonly="readonly"'
						//+ (!investment_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
						+ ' />'
						+ '<span class="investment_options_field_after investment_options_with_action iconadmin-down-open" onchange="investment_options_action_show_menu(this);return false;"></span>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
						+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'images':
				rez = investment_shortcodes_menu_list(param);
				if (investment_empty(param['style']) || param['style']=='select') {
					output += '<div class="investment_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
						+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case 'icons':
				rez = investment_shortcodes_menu_list(param);
				if (investment_empty(param['style']) || param['style']=='select') {
					output += '<div class="investment_options_caption_icon iconadmin-down-open"><span class="' + rez[1] + '"></span></div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
						+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'socials':
				if (!investment_is_object(param['value'])) param['value'] = {'url': '', 'icon': ''};
				rez = investment_shortcodes_menu_list(param);
				if (investment_empty(param['style']) || param['style']=='icons') {
					rez2 = investment_shortcodes_action_button({
						'action': investment_empty(param['style']) || param['style']=='icons' ? 'select_icon' : '',
						'icon': (investment_empty(param['style']) || param['style']=='icons') && !investment_empty(param['value']['icon']) ? param['value']['icon'] : 'iconadmin-users'
						}, 'after');
				} else
					rez2 = ['', ''];
				output += '<input class="investment_options_input investment_options_input_text investment_options_input_socials' 
					+ (!investment_empty(param['mask']) ? ' investment_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text" value="' + investment_shortcodes_prepare_value(param['value']['url']) + '"' 
					+ (!investment_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ rez2[0];
				if (!investment_empty(param['style']) && param['style']=='images') {
					output += '<div class="investment_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '_icon' + '" type="hidden" value="' + investment_shortcodes_prepare_value(param['value']['icon']) + '" />';
			break;

			case "color":
				var cp_style = investment_isset(param['style']) ? param['style'] : INVESTMENT_STORAGE['shortcodes_cp'];
				output += '<input class="investment_options_input investment_options_input_color investment_options_input_color_'+cp_style +'"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' data-param="' + investment_shortcodes_prepare_value(param_num) + '"'
					+ ' type="text"'
					+ ' value="' + investment_shortcodes_prepare_value(param['value']) + '"'
					+ (!investment_empty(param['action']) ? ' onchange="investment_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before;
				if (cp_style=='custom')
					output += '<span class="investment_options_input_colorpicker iColorPicker"></span>';
				else if (cp_style=='tiny')
					output += after;
			break;   
	
			}

			if (param['type'] != 'hidden') {
				output += '</div>';
				if (!investment_empty(param['desc']))
					output += '<div class="investment_options_desc">' + param['desc'] + '</div>' + "\n";
				output += '</div>' + "\n";
			}

		}

		output += '</div>';
	}

	
	return output;
}



// Return menu items list (menu, images or icons)
function investment_shortcodes_menu_list(field) {
	"use strict";
	if (field['type'] == 'socials') field['value'] = field['value']['icon'];
	var list = '<div class="investment_options_input_menu ' + (investment_empty(field['style']) ? '' : ' investment_options_input_menu_' + field['style']) + '">';
	var caption = '';
	for (var key in field['options']) {
		var value = field['options'][key];
		if (investment_in_array(field['type'], ['list', 'icons', 'socials'])) key = value;
		var selected = '';
		if (investment_in_list(field['value'], key, INVESTMENT_STORAGE['shortcodes_delimiter'])) {
			caption = value;
			selected = ' investment_options_state_checked';
		}
		list += '<span class="investment_options_menuitem' 
			+ selected 
			+ '" data-value="' + investment_shortcodes_prepare_value(key) + '"'
			+ '>';
		if (investment_in_array(field['type'], ['list', 'select', 'fonts']))
			list += value;
		else if (field['type'] == 'icons' || (field['type'] == 'socials' && field['style'] == 'icons'))
			list += '<span class="' + value + '"></span>';
		else if (field['type'] == 'images' || (field['type'] == 'socials' && field['style'] == 'images'))
			//list += '<img src="' + value + '" data-icon="' + key + '" alt="" class="investment_options_input_image" />';
			list += '<span style="background-image:url(' + value + ')" data-src="' + value + '" data-icon="' + key + '" class="investment_options_input_image"></span>';
		list += '</span>';
	}
	list += '</div>';
	return [list, caption];
}



// Return action button
function investment_shortcodes_action_button(data, type) {
	"use strict";
	var class_name = ' investment_options_button_' + type + (investment_empty(data['title']) ? ' investment_options_button_'+type+'_small' : '');
	var output = '<span class="' 
				+ (type == 'button' ? 'investment_options_input_button'  : 'investment_options_field_'+type)
				+ (!investment_empty(data['action']) ? ' investment_options_with_action' : '')
				+ (!investment_empty(data['icon']) ? ' '+data['icon'] : '')
				+ '"'
				+ (!investment_empty(data['icon']) && !investment_empty(data['title']) ? ' title="'+investment_shortcodes_prepare_value(data['title'])+'"' : '')
				+ (!investment_empty(data['action']) ? ' onclick="investment_options_action_'+data['action']+'(this);return false;"' : '')
				+ (!investment_empty(data['type']) ? ' data-type="'+data['type']+'"' : '')
				+ (!investment_empty(data['multiple']) ? ' data-multiple="'+data['multiple']+'"' : '')
				+ (!investment_empty(data['sizes']) ? ' data-sizes="'+data['sizes']+'"' : '')
				+ (!investment_empty(data['linked_field']) ? ' data-linked-field="'+data['linked_field']+'"' : '')
				+ (!investment_empty(data['captions']) && !investment_empty(data['captions']['choose']) ? ' data-caption-choose="'+investment_shortcodes_prepare_value(data['captions']['choose'])+'"' : '')
				+ (!investment_empty(data['captions']) && !investment_empty(data['captions']['update']) ? ' data-caption-update="'+investment_shortcodes_prepare_value(data['captions']['update'])+'"' : '')
				+ '>'
				+ (type == 'button' || (investment_empty(data['icon']) && !investment_empty(data['title'])) ? data['title'] : '')
				+ '</span>';
	return [output, class_name];
}

// Prepare string to insert as parameter's value
function investment_shortcodes_prepare_value(val) {
	return typeof val == 'string' ? val.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;') : val;
}
