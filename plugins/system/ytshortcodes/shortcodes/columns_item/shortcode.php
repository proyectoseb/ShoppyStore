<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function columns_itemYTShortcode($atts, $content = null ){
	extract(ytshortcode_atts(array(
		"col" => 4,
		"offset" => '',
	), $atts));
	return '<div  class="yt-clearfix col-sm-'.$col.' col-md-' . $col. ' '.(($offset != '') ? ' col-md-offset-' . $offset : '') .'">' . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</div>';
}
?>