<?php
/**
 * Investment Framework: strings manipulations
 *
 * @package	investment
 * @since	investment 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'INVESTMENT_MULTIBYTE' ) ) define( 'INVESTMENT_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('investment_strlen')) {
	function investment_strlen($text) {
		return INVESTMENT_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('investment_strpos')) {
	function investment_strpos($text, $char, $from=0) {
		return INVESTMENT_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('investment_strrpos')) {
	function investment_strrpos($text, $char, $from=0) {
		return INVESTMENT_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('investment_substr')) {
	function investment_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = investment_strlen($text)-$from;
		}
		return INVESTMENT_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('investment_strtolower')) {
	function investment_strtolower($text) {
		return INVESTMENT_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('investment_strtoupper')) {
	function investment_strtoupper($text) {
		return INVESTMENT_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('investment_strtoproper')) {
	function investment_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<investment_strlen($text); $i++) {
			$ch = investment_substr($text, $i, 1);
			$rez .= investment_strpos(' .,:;?!()[]{}+=', $last)!==false ? investment_strtoupper($ch) : investment_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('investment_strrepeat')) {
	function investment_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('investment_strshort')) {
	function investment_strshort($str, $maxlength, $add='...') {
	//	if ($add && investment_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= investment_strlen($str)) 
			return strip_tags($str);
		$str = investment_substr(strip_tags($str), 0, $maxlength - investment_strlen($add));
		$ch = investment_substr($str, $maxlength - investment_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = investment_strlen($str) - 1; $i > 0; $i--)
				if (investment_substr($str, $i, 1) == ' ') break;
			$str = trim(investment_substr($str, 0, $i));
		}
		if (!empty($str) && investment_strpos(',.:;-', investment_substr($str, -1))!==false) $str = investment_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('investment_strclear')) {
	function investment_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (investment_substr($text, 0, investment_strlen($open))==$open) {
					$pos = investment_strpos($text, '>');
					if ($pos!==false) $text = investment_substr($text, $pos+1);
				}
				if (investment_substr($text, -investment_strlen($close))==$close) $text = investment_substr($text, 0, investment_strlen($text) - investment_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('investment_get_slug')) {
	function investment_get_slug($title) {
		return investment_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('investment_strmacros')) {
	function investment_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('investment_unserialize')) {
	function investment_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>