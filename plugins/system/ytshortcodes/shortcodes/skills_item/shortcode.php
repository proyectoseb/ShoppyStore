<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function skills_itemYTShortcode($atts, $content = null){
	global $no_number;
	extract(ytshortcode_atts(array(
		"title"		=> '',
		"number"	=> '',
	), $atts));

	$skill_item  =  "<div class='form-group'>";
	$skill_item .=  "<strong>".$title."</strong>";
	$skill_item .=   ($no_number != 'no' || $no_number != 'No') ? "<span class='pull-right'>".$number."%</span>" : '' ;
	$skill_item .=   "<div class='progress progress-danger active'> <div style='width:". $number ."%' class='bar'></div> </div>";
	$skill_item .=  "</div>";

	return $skill_item;
}
?>