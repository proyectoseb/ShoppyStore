<?php
/**
 * Element: Languages
 * Displays a select box of languages
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

class JFormFieldNN_Languages extends JFormField
{
	public $type = 'Languages';
	private $params = null;

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$size = (int) $this->get('size');
		$multiple = $this->get('multiple');
		$client = $this->get('client', 'SITE');

		jimport('joomla.language.helper');
		$langs = JLanguageHelper::createLanguageList($this->value, constant('JPATH_' . strtoupper($client)), true);
		$options = array();
		foreach ($langs as $lang)
		{
			if ($lang['value'])
			{
				$option = new stdClass;
				$option->value = $lang['value'];
				$option->text = $lang['text'] . ' [' . $lang['value'] . ']';
				$options[] = $option;
			}
		}

		require_once JPATH_PLUGINS . '/system/nnframework/helpers/html.php';

		return nnHtml::selectlistsimple($options, $this->name, $this->value, $this->id, $size, $multiple);
	}

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
