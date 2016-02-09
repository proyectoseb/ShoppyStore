<?php
	 /**
    * @package YT Framework
    * @author Smartaddons http://www.Smartaddons.com
    * @copyright Copyright (c) 2009 - 2014 Smartaddons
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');


class JFormFieldPattern extends JFormField
{
	public $type = 'Pattern';

	protected function getInput() {
		$options = (array) $this->getOptions();
		$className = $this->element['class']; 
		$html = '';
		$html .= '
		<div class="pattern-wrap '.$className.'" id="'.$this->id.'_pattern">';
			for($i=0;$i<count($options);$i++){ 
			$class = ($options[$i]->value==$this->value)?" active":"";
		$html .= '
				<div data-placement="top" rel="tooltip" href="#" data-original-title="'.(string)$options[$i]->value.'" class="pattern '.(string)$options[$i]->value.$class.'">'.(string)$options[$i]->value.'</div>';	
			}
		$html .= '
		<input type="hidden" value="'.$this->value.'" name="'.$this->name.'" id="'.$this->id.'">
		</div>
		<script language="javascript" type="text/javascript">
		jQuery(document).ready(function($){
			$("#'.$this->id.'_pattern .pattern").bind("click", function(){
				oldvalue = $(this).parent().find(".active").html();
				$("#'.$this->id.'_pattern .pattern").removeClass("active");
				$(this).addClass("active");
				value = $(this).html();
				$("#'.$this->id.'_pattern input#'.$this->id.'").attr("value", value);
			})
		});
		</script>';
		
		
		return $html;
	}

	protected function getOptions() {
		$options = array();
		$yttemplate = $this->form->getValue('template');
		$directory = $this->element['directory'];
		$path ='/templates/'.$yttemplate.'/'.$directory;
		if (!is_dir($path)) $path = JPATH_ROOT.'/'.$path; 
		$files = JFolder::files($path); 

		if (is_array($files)) {
			foreach($files as $file) {
				$file = JFile::stripExt($file);
				$options[] = JHtml::_('select.option', $file, $file);
			}
		}

		return array_merge($options);
	}
}
