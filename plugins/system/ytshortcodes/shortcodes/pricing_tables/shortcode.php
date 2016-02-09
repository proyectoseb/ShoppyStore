<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
$pcolumns = 3;
function pricing_tablesYTShortcode($atts, $content = null ){
	global $pcolumns,$type;

	extract(ytshortcode_atts(array(
		"columns" 			=> '3',
		"width" 		=> '100%',
		"style" 		=> '',
		"type"			=>''
	), $atts));
	$pcolumns	= $columns;
	$class 		= 'yt-pricing block row col-' . $columns.' pricing-'.$style;
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/pricing_tables/css/pricingtable.css",'text/css',"screen");
	return '<div class="'.$class.'"  style="width:'.$width.';" >' . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</div>';
}
?>