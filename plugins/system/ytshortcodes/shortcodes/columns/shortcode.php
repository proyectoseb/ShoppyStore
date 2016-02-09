<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function columnsYTShortcode($atts, $content = null ){
	extract(ytshortcode_atts(array(
		"grid" => 'no'
	), $atts));
	$show_grid = ($grid =='yes')? 'show-grid':'';
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/columns/css/columns.css");
	return '<div class="yt-clearfix yt-show-grid '.$show_grid.' ">' . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</div>';
}
?>