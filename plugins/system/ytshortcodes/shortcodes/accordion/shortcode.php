<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

defined('_JEXEC') or die;
function accordionYTShortcode($atts, $content = null){
	global $style_acc;
	extract(ytshortcode_atts(array(
		"style"  =>'',
		"align"  =>'none',
		"width"  =>'100',
		"color_active" => '',
		"background_active" => '',
		"color_background_active" => 'yes'
	),$atts));
	$style_acc =$style;
	$id = uniqid('ul_').rand().time(); 
	$accordion = "<ul class='yt-clearfix yt-accordion ".$id." pull-".$align." ".trim($style)."' style=\"width:".$width."% ; \">";
	$accordion .= parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)); 
	$accordion .= "</ul>";
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/accordion/css/accordion.css");
	$css ='';
	if($style != 'line' && $color_background_active =="yes")
	{
		$css .= 'ul.yt-accordion.'.$id.' li.yt-accordion-group h3.accordion-heading.active{color: '.$color_active.' !important;background : '.$background_active.' !important;} ul.yt-accordion.'.$id.' li.yt-accordion-group .yt-accordion-inner.active{background : '.$background_active.' !important;} ul.yt-accordion.'.$id.' li.yt-accordion-group .active i{color: '.$color_active.' !important;}';
	}else
	{
		$css .= 'ul.yt-accordion.'.$id.'.line li.yt-accordion-group .yt-accordion-inner.active{
	border-top: '.$color_active.' 1px solid !important;	} ul.yt-accordion.'.$id.' li.yt-accordion-group h3.accordion-heading.active{color: '.$color_active.' !important;} ul.yt-accordion.'.$id.' li.yt-accordion-group .active i{color: '.$color_active.' !important;}';
	}
	$doc = JFactory::getDocument();
	$doc->addStyleDeclaration($css);
	return $accordion;
}	
?>