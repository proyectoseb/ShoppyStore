<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function panelYTShortcode($atts = null, $content = null) {
	$atts = ytshortcode_atts(array(
		'background' => '',
		'color'      => '',
		'shadow'     => '',
		'padding'    => '',
		'margin'     => '',
		'border'     => '',
		'radius'     => '',
		'text_align' => '',
		'url'        => '',
		'class'      => ''
	), $atts, 'panel');

	$id = uniqid('ytpnl').rand().time();
	$margin = '';
	$css = '';
	$padding = $atts['padding'];
	if ($atts['url']) {
		$atts['class'] .= ' yt-panel-clickable';
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/panel/js/panel.js");
	}

	if (substr($atts['padding'], -2)=='px' || substr($atts['padding'], -2)=='em') {
		if ($atts['padding']) {
			$padding = 'padding:'.$atts['padding'].';';
		}
	} else {
		if ($atts['padding'] != '') {
			$padding = 'padding:'.$atts['padding'].'px;';
		}
	}
	if ($atts['margin'] != '') {
		$margin = 'margin:'.$atts['margin'].';';
	}
	$radius =  ($atts['radius']) ? '-webkit-border-radius:' . $atts['radius'] . ';border-radius:' . $atts['radius'] . ';' : '';
	$border =  ($atts['border'] != '') ? 'border:' . $atts['border'] . ';' : '';
	$shadow = ($atts['shadow'] != '') ? '-webkit-box-shadow:' . $atts['shadow'] . ';box-shadow:' . $atts['shadow'] . ';' : '';
	$background = ($atts['background'] != '') ? 'background-color:' . $atts['background'] . ';' : '';
	$color = ($atts['color'] != '') ? 'color:' . $atts['color'] . ';' : '';

	if ($radius or $border or $shadow or $background or $color)
		$css .= '#'.$id.'.yt-panel { '.$background. $color. $border . $radius. $margin. '}';

	if ($atts['text_align'])
		$css .= '#'.$id.'.yt-panel .yt-panel-content { text-align:' . $atts['text_align'] . ';'.$padding.'}';

	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/panel/css/panel.css",'text/css');
	$doc = JFactory::getDocument();
	$doc->addStyleDeclaration($css);
	$return = '<div id="'.$id.'" class="yt-panel ' . trim($atts['class']) . '" data-url="' . $atts['url'] . '"><div class="yt-panel-content yt-content-wrap">' . parse_shortcode(str_replace(array("<br/>","<br />"), " ", $content)) . '</div></div>';
	return  $return;
}
?>