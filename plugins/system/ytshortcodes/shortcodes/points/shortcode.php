<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function pointsYTShortcode($atts, $content = null){
	global $no_number;

	extract(ytshortcode_atts(array(
		"src"		=> '',
		"width"		=> '',
	), $atts));

	$points = JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/points/css/points.css",'text/css',"screen");
	if ($src == '')
	{
		 $src = 'plugins/system/ytshortcodes/assets/images/URL_IMAGES.png';
	}
	$src = (strpos($src, "http://") === false) ? JURI::base().$src : $src;
	$points .= "<div class='yt-product-wrapper' style='max-width:".$width."%'>";
	$points .= '<img src="'.$src.'" alt="Preview image"><ul class="blank">';
	$points .= parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) ;
	$points .= '</ul></div>';

	return $points;
}
?>