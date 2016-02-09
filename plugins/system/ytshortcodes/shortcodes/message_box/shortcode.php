<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function message_boxYTShortcode($atts, $content = null){
	extract(ytshortcode_atts(array(
		"title" =>'',
		"type" =>'error',
		"close" => "Yes",

	), $atts));

	$message_box = '<div class="alert alert-'.$type.' fade in">';
	$message_box .= ($close == "Yes" || $close == "yes") ? '<button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button>' : "";
	$message_title=(!empty($title) && $title != null ) ? '<h3 class="alert-heading">' . $title . '</h3>' : "";

	$message_box = $message_box . $message_title;
	$message_box = $message_box . '<div class="alert-content">' . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</div>';
	$message_box = $message_box . '</div>';

	return $message_box;
}
?>