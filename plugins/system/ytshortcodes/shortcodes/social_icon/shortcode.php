<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function social_iconYTShortcode($atts, $content = null){
	extract(ytshortcode_atts(array(
		"type" 	=> 'facebook',
		"title"	=> '',
		"size"	=> 'default',
		"style"	=> '',
		"color"	=> ''
	), $atts));
	//var_dump($content);die();
	$social_color=(($color == "Yes" || $color == "yes")? 'color' : "");
	$social = '<div class="yt-socialbt"><a data-placement="top" target="_blank" class="sb '.$type." ". $size."  ".$style." ".$social_color.'" title="' . $title . '" href="' . trim($content) . '">';
	
	$social .= '<i class="fa fa-'.$type.'"></i></a></div>';
	//echo $social ;die();
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/social_icon/css/social_icon.css");
	return $social;
}
?>