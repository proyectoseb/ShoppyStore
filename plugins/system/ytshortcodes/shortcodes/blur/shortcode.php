<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

defined('_JEXEC') or die;
function blurYTShortcode( $atts, $content = null ) {
		extract(ytshortcode_atts(array(
		"blur"          => '1',
		"hover_blur"    => '0.1'
	), $atts));
		$class = uniqid('yt_blur_').rand().time();
		$css='.'.$class.' {-webkit-filter: blur('.$blur.'px); -moz-filter: blur('.$blur.'px); -o-filter: blur('.$blur.'px); -ms-filter: blur('.$blur.'px); filter: blur('.$blur.'px);}
				.'.$class.':hover{-webkit-filter: blur('.$hover_blur.'px); -moz-filter: blur('.$hover_blur.'px); -o-filter: blur('.$hover_blur.'px); -ms-filter: blur('.$hover_blur.'px); filter: blur('.$hover_blur.'px);}';
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
		return '<div class="yt-clearfix '.$class.'" >	'.parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)).'</div> ';
}
?>