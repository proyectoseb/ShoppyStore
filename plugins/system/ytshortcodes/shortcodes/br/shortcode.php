<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function brYTShortcode($atts){
	extract(ytshortcode_atts(array(
		"height" => '20'
	), $atts));

	return "</br>";
}
?>