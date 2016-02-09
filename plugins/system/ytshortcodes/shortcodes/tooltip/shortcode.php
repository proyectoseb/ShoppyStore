<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function tooltipYTShortcode($atts, $content = null){
	extract(ytshortcode_atts(array(
		"type"      =>'',
		"link" 		=> '#',
		"title" 	=> '',
		"position"	=> '',
		"background"=> '',
		"color"     => '#fff'
	), $atts));
	$css= '';
	$id_tool =uniqid('yt_too').rand().time();
	$divider = '<a class="btn btn-'.$type.'" data-placement="'.$position.'" href="'.$link.'" title="'.$title.'" id="'.$id_tool.'">'.$content. '</a>';
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/qtip.css",'text/css');
        // Add javascript file in head
	JHtml::_('jquery.framework');
	JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/qtip.js");
	if($type !='border')
	{
		$css .='#'.$id_tool.' + .tooltip > .tooltip-inner {background-color: '.$background.'; color:'.$color.'}	#'.$id_tool.' + .tooltip > .tooltip-arrow {border-'.$position.'-color: '.$background.';}';
	}
	else
	{
		$css .='#'.$id_tool.' + .tooltip > .tooltip-inner {border: '.$background.' 1px solid; color:'.$color.'; background:#fff; position:relative}	#'.$id_tool.' + .tooltip > .tooltip-arrow {border-'.$position.'-color: '.$background.';}';
			switch ($position) {
				case 'top':
			$css .='#'.$id_tool.' + .tooltip > .tooltip-inner:before{ content: "";width: 0;height: 0; border-style: solid;border-color: #fff transparent transparent transparent;border-width: 5px 5px 0; bottom: -5px;position:absolute;left:50%;z-index: 1;margin-left:-5px;}';
					break;
				case 'right':
			$css .='#'.$id_tool.' + .tooltip > .tooltip-inner:before{content: "";width: 0;height: 0;border-style: solid;border-color:transparent #fff transparent transparent; border-width: 5px;left: -5px;position:absolute;top:50%;z-index: 1;
				  margin-top:-5px;margin-left:-5px;}';
					break;
				case 'left':
			$css .='#'.$id_tool.' + .tooltip > .tooltip-inner:before{content: "";width: 0;height: 0;border-style: solid;border-color:transparent transparent transparent #fff;border-width: 5px;right: -10px;position:absolute;top:50%;z-index: 1;margin-top:-5px;margin-left:-5px;}';
					break;
				case 'bottom':
			$css .='#'.$id_tool.' + .tooltip > .tooltip-inner:before{content: "";width: 0;
				  height: 0;border-style: solid;border-color:  transparent transparent #fff; border-width: 5px ;top: -10px; position:absolute;left:50%;z-index: 1;margin-left:-5px;}';
					break;
				default:
					# code...
					break;
			}
	}
	$doc = JFactory::getDocument();
	$doc->addStyleDeclaration($css);
	return $divider;
}
?>