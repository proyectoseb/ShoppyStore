<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function points_itemYTShortcode($atts, $content = null){
	global $no_number;
	//set these positions of interest points according to your product image
	extract(ytshortcode_atts(array(
		"x"		=> '',
		"y"		=> '',
		"position" => '',
	), $atts));

	$points_item  =  "<li class='yt-single-point' style='top:".$y."%; left: ".$x."%; list-style:none'>";
	$points_item .=  "<a class='yt-img-replace' href='#0'>More</a>";
	$points_item .=  "<div class='yt-more-info yt-".$position."'>" ;
	$points_item .=  parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) ;
	$points_item .=  "<a href='#0' class='yt-close-info yt-img-replace'>Close</a></div> </li>";

	return $points_item;
}
?>