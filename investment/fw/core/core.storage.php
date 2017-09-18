<?php
/**
 * Investment Framework: theme variables storage
 *
 * @package	investment
 * @since	investment 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('investment_storage_get')) {
	function investment_storage_get($var_name, $default='') {
		global $INVESTMENT_STORAGE;
		return isset($INVESTMENT_STORAGE[$var_name]) ? $INVESTMENT_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('investment_storage_set')) {
	function investment_storage_set($var_name, $value) {
		global $INVESTMENT_STORAGE;
		$INVESTMENT_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('investment_storage_empty')) {
	function investment_storage_empty($var_name, $key='', $key2='') {
		global $INVESTMENT_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($INVESTMENT_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($INVESTMENT_STORAGE[$var_name][$key]);
		else
			return empty($INVESTMENT_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('investment_storage_isset')) {
	function investment_storage_isset($var_name, $key='', $key2='') {
		global $INVESTMENT_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($INVESTMENT_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($INVESTMENT_STORAGE[$var_name][$key]);
		else
			return isset($INVESTMENT_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('investment_storage_inc')) {
	function investment_storage_inc($var_name, $value=1) {
		global $INVESTMENT_STORAGE;
		if (empty($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = 0;
		$INVESTMENT_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('investment_storage_concat')) {
	function investment_storage_concat($var_name, $value) {
		global $INVESTMENT_STORAGE;
		if (empty($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = '';
		$INVESTMENT_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('investment_storage_get_array')) {
	function investment_storage_get_array($var_name, $key, $key2='', $default='') {
		global $INVESTMENT_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($INVESTMENT_STORAGE[$var_name][$key]) ? $INVESTMENT_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($INVESTMENT_STORAGE[$var_name][$key][$key2]) ? $INVESTMENT_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('investment_storage_set_array')) {
	function investment_storage_set_array($var_name, $key, $value) {
		global $INVESTMENT_STORAGE;
		if (!isset($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = array();
		if ($key==='')
			$INVESTMENT_STORAGE[$var_name][] = $value;
		else
			$INVESTMENT_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('investment_storage_set_array2')) {
	function investment_storage_set_array2($var_name, $key, $key2, $value) {
		global $INVESTMENT_STORAGE;
		if (!isset($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = array();
		if (!isset($INVESTMENT_STORAGE[$var_name][$key])) $INVESTMENT_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$INVESTMENT_STORAGE[$var_name][$key][] = $value;
		else
			$INVESTMENT_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('investment_storage_set_array_after')) {
	function investment_storage_set_array_after($var_name, $after, $key, $value='') {
		global $INVESTMENT_STORAGE;
		if (!isset($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = array();
		if (is_array($key))
			investment_array_insert_after($INVESTMENT_STORAGE[$var_name], $after, $key);
		else
			investment_array_insert_after($INVESTMENT_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('investment_storage_set_array_before')) {
	function investment_storage_set_array_before($var_name, $before, $key, $value='') {
		global $INVESTMENT_STORAGE;
		if (!isset($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = array();
		if (is_array($key))
			investment_array_insert_before($INVESTMENT_STORAGE[$var_name], $before, $key);
		else
			investment_array_insert_before($INVESTMENT_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('investment_storage_push_array')) {
	function investment_storage_push_array($var_name, $key, $value) {
		global $INVESTMENT_STORAGE;
		if (!isset($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($INVESTMENT_STORAGE[$var_name], $value);
		else {
			if (!isset($INVESTMENT_STORAGE[$var_name][$key])) $INVESTMENT_STORAGE[$var_name][$key] = array();
			array_push($INVESTMENT_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('investment_storage_pop_array')) {
	function investment_storage_pop_array($var_name, $key='', $defa='') {
		global $INVESTMENT_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($INVESTMENT_STORAGE[$var_name]) && is_array($INVESTMENT_STORAGE[$var_name]) && count($INVESTMENT_STORAGE[$var_name]) > 0) 
				$rez = array_pop($INVESTMENT_STORAGE[$var_name]);
		} else {
			if (isset($INVESTMENT_STORAGE[$var_name][$key]) && is_array($INVESTMENT_STORAGE[$var_name][$key]) && count($INVESTMENT_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($INVESTMENT_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('investment_storage_inc_array')) {
	function investment_storage_inc_array($var_name, $key, $value=1) {
		global $INVESTMENT_STORAGE;
		if (!isset($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = array();
		if (empty($INVESTMENT_STORAGE[$var_name][$key])) $INVESTMENT_STORAGE[$var_name][$key] = 0;
		$INVESTMENT_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('investment_storage_concat_array')) {
	function investment_storage_concat_array($var_name, $key, $value) {
		global $INVESTMENT_STORAGE;
		if (!isset($INVESTMENT_STORAGE[$var_name])) $INVESTMENT_STORAGE[$var_name] = array();
		if (empty($INVESTMENT_STORAGE[$var_name][$key])) $INVESTMENT_STORAGE[$var_name][$key] = '';
		$INVESTMENT_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('investment_storage_call_obj_method')) {
	function investment_storage_call_obj_method($var_name, $method, $param=null) {
		global $INVESTMENT_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($INVESTMENT_STORAGE[$var_name]) ? $INVESTMENT_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($INVESTMENT_STORAGE[$var_name]) ? $INVESTMENT_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('investment_storage_get_obj_property')) {
	function investment_storage_get_obj_property($var_name, $prop, $default='') {
		global $INVESTMENT_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($INVESTMENT_STORAGE[$var_name]->$prop) ? $INVESTMENT_STORAGE[$var_name]->$prop : $default;
	}
}
?>