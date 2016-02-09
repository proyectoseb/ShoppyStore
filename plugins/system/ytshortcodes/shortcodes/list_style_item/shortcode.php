<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function list_style_itemYTShortcode($atts, $content = null ){
	global $list_color;
	extract(ytshortcode_atts(array(
		"link"   => '',
		"offset" => ''
	), $atts));
	if($link != '')
	{
		$content = '<a href="'.$link.'" title="'.$content.'" target="_brank">' . $content . '</a>';
	}
	if($list_color!=''){
		return '<li ><span>' . $content . '</span></li>';
	}else{
		return '<li >' . $content . '</li>';
	}
}
?>