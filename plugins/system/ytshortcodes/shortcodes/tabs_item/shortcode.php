<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function tabs_itemYTShortcode($atts, $content = null){
	extract(ytshortcode_atts(array(
		"title" => '',
		"icon"	=>''
	), $atts));
	global $tab_array;
	if($icon !='')
	{
		if (strpos($icon, 'icon:') !== false) 
		{
			$icon = "<i class='fa fa-" . trim(str_replace('icon:', '', $icon)) . "'></i> ";	
		}else
		{
			$icon = '<img src="'.yt_image_media($icon).'" style="width:16px" alt="" /> ';
		}
	}
	$tab_array[] = array("title" => $title,"icon" => $icon , "content" => parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)));
}
?>