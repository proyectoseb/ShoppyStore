<?php
	/**
    * @package YT Framework
    * @author Smartaddons http://www.Smartaddons.com
    * @copyright Copyright (c) 2009 - 2014 Smartaddons
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (class_exists('JElement')){
	class JElementListThumbnailMode extends JElement {
		var $_name = 'Yt Thumbnail Modes';
		var $_mode = array('none', 'center', 'fill', 'fit', 'stretch');
		function fetchElement($name, $value, &$node, $control_name){
			$html = "";
			if (count($this->_mode)>0){
				$html = '<select class="inputbox" id="' . $control_name . $name .'" name="' . $control_name . '[' . $name . ']" style="width:132px;">';
				foreach ($this->_mode as $mode){
					$selected = $mode == $value ? 'selected="selected"' : '';
					$html .= '<option value="' . $mode . '" ' . $selected . '>' . JText::_(ucfirst($mode)) . '</option>';
				}
				$html .= '<select>';
			}
			$html .= "<br>"
				. "<div class=\"yt_description\" style=\"padding: 5px 0 0 0;\">
						<img src=\"data:image/gif;base64,R0lGODlhAQABAJEAAAAAAP///////wAAACH5BAUUAAIALAAAAAABAAEAAAICVAEAOw==\" alt=\"Youtech Thumbnail Modes\" style=\"background:url(http://www.smartaddons.com/thumbmode.png) no-repeat;width:400px; height: 60px;\"/>
					</div>";
			return $html;
		}
	}
}
if (class_exists('JFormField')){
	class JFormFieldListThumbnailMode extends JFormField {
		var $_name = 'Yt Thumbnail Modes';
		var $_mode = array('center', 'fill', 'fit', 'stretch');
		function getInput(){
			$html = "";
			if (count($this->_mode)>0){
				$html = '<select class="inputbox" id="' . $this->id .'" name="' . $this->name . '" style="width:132px;">';
				foreach ($this->_mode as $mode){
					$selected = $mode == $this->value ? 'selected="selected"' : '';
					$html .= '<option value="' . $mode . '" ' . $selected . '>' . JText::_(ucfirst($mode)) . '</option>';
				}
				$html .= '<select>';
			}
			$html .= "<br>"
				. "<div class=\"yt_description\" style=\"padding: 5px 0 0 0;\">
						<img src=\"data:image/gif;base64,R0lGODlhAQABAJEAAAAAAP///////wAAACH5BAUUAAIALAAAAAABAAEAAAICVAEAOw==\" alt=\"Youtech Thumbnail Modes\" style=\"background:url(http://www.smartaddons.com/thumbmode.png) no-repeat;width:400px; height: 60px;\"/>
					</div>";
			return $html;
		}
	}
}