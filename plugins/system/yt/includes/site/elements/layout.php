<?php

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLayout extends JFormField
{
	public $type = 'Layout';
	die('vao day');
	protected function getInput() {
		$options = (array) $this->getOptions(); 
		$html = JHtml::_('select.genericlist', $options, $this->name, '', $this->value, 'text', $this->element['default']);
		return $html;
	}

	protected function getOptions() {
		$options = array();
		$path = (string) $this->element['directory'];
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
