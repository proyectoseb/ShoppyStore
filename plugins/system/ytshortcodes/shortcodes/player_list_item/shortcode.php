<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function player_list_itemYTShortcode($atts, $content = null){
	global $playeritem_count ;
	extract(ytshortcode_atts(array(
		"src"		=> '',
		"song"		=> '',
		"artist"	=> ''
	), $atts));
	$playeritem_count++;
	$player_item  = '<li class=""><span>'.$playeritem_count.'</span>  <a href="#" data-src="'.$src.'" >'.$song.'</a> - '.$artist.'</li>';

	return $player_item;
}
?>