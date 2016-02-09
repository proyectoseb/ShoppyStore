<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function dropcapYTShortcode($atts, $content = null){
	extract(ytshortcode_atts(array(
		"font"			=> '',
		"type" 			=> 'none',
		"color" 		=> '#333',
		"background"	=> 'none',
		"size" 			=> '',
	), $atts));
	if($size !='')
	$fontsize = 'font-size:'.$size.'';
	$doc = JFactory::getDocument();
	$href = "http://fonts.googleapis.com/css?family={$font}";
	$attribs = array('type' => 'text/css', 'title' => 'CSS');
	$doc->addHeadLink( $href, 'stylesheet', 'rel', $attribs );
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/dropcap/css/dropcap.css");
	return '<div class="yt-dropcap ' . $type . '" style="color:'. $color .'; background-color:' . $background . '; font-family: '.$font.'; '.$fontsize.'">' . $content . '</div>';
}

?>