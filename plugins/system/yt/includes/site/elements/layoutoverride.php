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
JFormHelper::loadFieldClass('list');

class JFormFieldLayoutOverride extends JFormField
{
	public $type = 'LayoutOverride';

	protected function getInput() {
		$options = (array) $this->getOptions();
		$html = '';
		$html .= '<div id="'.str_replace('jform_params_','', $this->id).'_form">';
		$html .= '<div class="span4"><label>' . JText::_('ItemID:') . '</label>';
		$html .= '<input type="text" id="'.str_replace('jform_params_','', $this->id).'_input" /></div>';
		$html .= '<div class="span4"><label>' . JText::_('Layout:') . '</label>';
		$html .= JHtml::_('select.genericlist', $options, 'name', '', 'value', 'text', 'default', ''.str_replace('jform_params_','', $this->id).'_select') .'</div>';
		$html .= '<div class="span4"><span data-placement="top" rel="tooltip" data-original-title="Apply" title="Apply" class="btn" id="'.str_replace('jform_params_','', $this->id).'_add_btn"><span class="icon-checkmark-2"></span></span></div>';
		$html .= '<textarea name="'.$this->name.'" id="'.$this->id.'">' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
		$html .= '<div id="'.str_replace('jform_params_','', $this->id).'_rules" ></div>';
		$html .= '</div>';
		
		return $html;
	}

	protected function getOptions() {
		$options = array();
		$yttemplate = $this->form->getValue('template');
		$directory = $this->element['directory'];
		$path ='/templates/'.$yttemplate.'/'.$directory;
		
		if (!is_dir($path)) $path = JPATH_ROOT.'/'.$path;
		$files = JFolder::files($path, '.xml'); 

		if (is_array($files)) {
			foreach($files as $file) {
				$file = JFile::stripExt($file);
				$options[] = JHtml::_('select.option', $file, $file);
			}
		}

		return array_merge($options);
	}
}
