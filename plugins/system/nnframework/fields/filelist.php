<?php
/**
 * Element: Filelist
 *
 * @package         NoNumber Framework
 * @version         14.11.6
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
JFormHelper::loadFieldClass('list');

class JFormFieldNN_FileList extends JFormFieldList
{
	public $type = 'FileList';
	private $params = null;

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		return parent::getInput();
	}

	protected function getOptions()
	{
		$options = array();

		$path = $this->get('folder');

		if (!is_dir($path))
		{
			$path = JPATH_ROOT . '/' . $path;
		}

		// Prepend some default options based on field attributes.
		if (!$this->get('hidenone', 0))
		{
			$options[] = JHtml::_('select.option', '-1', JText::alt('JOPTION_DO_NOT_USE', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}

		if (!$this->get('hidedefault', 0))
		{
			$options[] = JHtml::_('select.option', '', JText::alt('JOPTION_USE_DEFAULT', preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
		}

		// Get a list of files in the search path with the given filter.
		$files = JFolder::files($path, $this->get('filter'));

		// Build the options list from the list of files.
		if (is_array($files))
		{
			foreach ($files as $file)
			{
				// Check to see if the file is in the exclude mask.
				if ($this->get('exclude'))
				{
					if (preg_match(chr(1) . $this->get('exclude') . chr(1), $file))
					{
						continue;
					}
				}

				// If the extension is to be stripped, do it.
				if ($this->get('stripext', 1))
				{
					$file = JFile::stripExt($file);
				}

				$label = $file;
				if ($this->get('language_prefix'))
				{
					$label = JText::_($this->get('language_prefix') . strtoupper($label));
				}

				$options[] = JHtml::_('select.option', $file, $label);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
