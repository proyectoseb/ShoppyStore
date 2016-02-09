<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

defined('_JEXEC') or die;
function accordion_itemYTShortcode($atts, $content = null){
	global $style_acc;
	extract(ytshortcode_atts(array(
		"title"        => '',
		"icon"         => 'plus',
		'background'   =>'#fff',
		"color_title"  => '#333',
		"color_desc"   => '#333',
		"width"        =>'100',
		"height"       =>'100%',
		"active"       =>'',
		"border_color" =>'',
		"icon_color"   => '#ccc',
		"icon_size"    => '16',
		"background_active" => '#fff',
		"color_active" => '#4e9e31'
	), $atts));
	$id = uniqid('yt_acc_item_').rand().time();
	$css= '';
	$icon_color = ($icon_color) ? 'color:' . $icon_color . ';' : '';
    $icon_size_ = ($icon_size) ? 'font-size: '.intval($icon_size).'px;' : '';
	$style    = ($style_acc=='border' ? 'border:1px solid '.$border_color : '');
	$acc_item = "<li class='yt-accordion-group' id='".$id."' style='".$style."'>";
	$active = ($active == "yes") ? 'enable' : 'disable';
	$acc_item .= "<h3 class='accordion-heading ".$active."'" ;
	$acc_item .= " style='background:" . $background . "; box-shadow: 0px 1px 0px ".$background." inset ; color:".$color_title.";font-size:".$icon_size."px'>";
	if($icon !='')
	{
		if (strpos($icon, 'icon:') !== false) 
		{
			$acc_item .= "<i class='icon_accordion fa fa-" . trim(str_replace('icon:', '', $icon)) . "'></i> ";
			if ($icon_color or $icon_size) 
			{
	                $css .= '#'.$id.' .icon_accordion {'.$icon_color.$icon_size_.'}';
	       	}
		}else
		{
			$acc_item .= '<img src="'.yt_image_media($icon).'" style="width:'.$icon_size.'px" alt="" /> ';
		}
	}
	
	
	$acc_item .=' '. $title . "</h3>";
	$acc_item .= "<div class='yt-accordion-inner' style='background:" . $background . ";color:".$color_desc.";border-color:".$color_title."'>" . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . "</div>";
	$acc_item .= "</li>";
	$doc = JFactory::getDocument();
	$doc->addStyleDeclaration($css);
	return $acc_item;
}	
?>