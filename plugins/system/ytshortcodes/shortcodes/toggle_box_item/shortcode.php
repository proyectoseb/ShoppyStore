<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function toggle_box_itemYTShortcode($atts, $content = null)
{
	extract(ytshortcode_atts(array(
		"title"  => '',
		"icon"  => '',
		"active"  => '',
		"background"  => '#f1f1f1',
		"color"  => '#000'
	), $atts));
	$toggle_active=((strtoupper($active) == 'YES') ? 'active' : '');
	$icon_active='';
	if($icon !='')
	{
		if (strpos($icon, 'icon:') !== false) 
		{
			$icon_active .= "<i class='fa fa-" . trim(str_replace('icon:', '', $icon)) . "'></i> ";	
		}else
		{
			$icon_active .= '<img src="'.yt_image_media($icon).'" style="width:16px" alt="" /> ';
		}
	}
	$toggle_item = "<li class='yt-divider'>";
	$toggle_item = $toggle_item . "<h3 class='toggle-box-head ".$toggle_active."' style=' ".(($background !='') ? 'background:'.$background.'' :'' ).";".(($color !='') ? 'color:'.$color.'' : '')."'>";
	$toggle_item = $toggle_item . $icon_active;
	$toggle_item = $toggle_item . "<span style='color:".$color."; ".(($background =='') ? 'background:rgba(255,255,255,0.5)' : 'background:none')."'></span>";
	$toggle_item = $toggle_item . $title . "</h3>";
	$toggle_item = $toggle_item . "<div class='toggle-box-content ".$toggle_active."' style='border-color:".$background."'>" . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . "</div>";
	$toggle_item = $toggle_item . "</li>";

	return $toggle_item;
}
?>