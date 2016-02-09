<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function highlighterYTShortcode($atts, $content = null){

	extract(ytshortcode_atts(array(
			"label"		=> '',
			"linenums" 	=> 'no'

	), $atts));
	$highli_lang=(($label != '') ? '' . $label : '');
	if(preg_match("/yt_google_map/i",$content))
	{
		$content = '['.trim($content).']';
	}
	$highlighter = '<pre title="'.$highli_lang.'"class="highlighter prettyprint'.(($linenums == 'Yes' || $linenums == 'yes' ) ? ' linenums' : '') . '"> '.trim($content).' </pre>';
	
	
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/highlighter/css/highlighter.css");
	return $highlighter;
	
}
?>