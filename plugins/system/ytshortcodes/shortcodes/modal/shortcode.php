<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function modalYTShortcode($atts, $content = null){
	global $index_modal;
	extract(ytshortcode_atts(array(
		"title"		=> 'default',
		"header"	=> '',
		"type"		=> '',
		"icon" 		=> '',
	), $atts));
	$btn_icon = '<i class="'.(($icon != '') ? 'fa fa-' . $icon : '').'"></i>';

	$modal = '<a class="btn btn-default '.(($type != '') ? ' btn-' . $type : '').'" href="#myModal'.$index_modal.'" data-toggle="modal">'.$btn_icon.$title.'</a>';
	$modal .= '<div id="myModal'.$index_modal.'" class="modal yt-modal fade" tabindex="-1">';
	$modal .= '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"> <button style="background:none;" class="close" type="button" data-dismiss="modal"><i class="fa fa-times"></i></button>';
	$modal .= '<h3 id="myModalLabel">'.$header.'</h3> </div>';
	$modal .= '<div class="modal-body">'. parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)).'</div>';
	$modal .= '</div></div></div>';
	$index_modal ++;
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/modal/css/modal.css");
	return $modal;
}
?>