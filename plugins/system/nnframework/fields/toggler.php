<?php
/**
 * Element: Toggler
 * Adds slide in and out functionality to framework based on an framework value
 *
 * @package         NoNumber Framework
 * @version         14.11.6
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 * To use this, make a start xml param tag with the param and value set
 * And an end xml param tag without the param and value set
 * Everything between those tags will be included in the slide
 *
 * Available extra parameters:
 * param            The name of the reference parameter
 * value            a comma separated list of value on which to show the framework
 */
class JFormFieldNN_Toggler extends JFormField
{
	public $type = 'Toggler';
	private $params = null;

	protected function getLabel()
	{
		return '';
	}

	protected function getInput()
	{
		$field = new nnFieldToggler;

		return $field->getInput($this->element->attributes());
	}
}

class nnFieldToggler
{
	function getInput($params)
	{
		$this->params = $params;

		$option = JFactory::getApplication()->input->get('option');

		// do not place toggler stuff on JoomFish pages
		if ($option == 'com_joomfish')
		{
			return '';
		}

		$param = $this->get('param');
		$value = $this->get('value');
		$nofx = $this->get('nofx');
		$method = $this->get('method');
		$div = $this->get('div', 0);

		JHtml::_('jquery.framework');
		JFactory::getDocument()->addScriptVersion(JURI::root(true) . '/media/nnframework/js/script.min.js');
		JFactory::getDocument()->addScriptVersion(JURI::root(true) . '/media/nnframework/js/toggler.min.js');

		$param = preg_replace('#^\s*(.*?)\s*$#', '\1', $param);
		$param = preg_replace('#\s*\|\s*#', '|', $param);

		$html = array();
		if ($param != '')
		{
			$param = preg_replace('#[^a-z0-9-\.\|\@]#', '_', $param);
			$param = str_replace('@', '_', $param);
			$set_groups = explode('|', $param);
			$set_values = explode('|', $value);
			$ids = array();
			foreach ($set_groups as $i => $group)
			{
				$count = $i;
				if ($count >= count($set_values))
				{
					$count = 0;
				}
				$value = explode(',', $set_values[$count]);
				foreach ($value as $val)
				{
					$ids[] = $group . '.' . $val;
				}
			}

			if (!$div)
			{
				$html[] = '</div></div>';
			}

			$html[] = '<div id="' . rand(1000000, 9999999) . '___' . implode('___', $ids) . '" class="nntoggler';
			if ($nofx)
			{
				$html[] = ' nntoggler_nofx';
			}
			if ($method == 'and')
			{
				$html[] = ' nntoggler_and';
			}
			$html[] = '">';

			if (!$div)
			{
				$html[] = '<div><div>';
			}
		}
		else
		{
			$html[] = '</div>';
		}

		return implode('', $html);
	}

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
