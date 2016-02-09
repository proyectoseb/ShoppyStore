<?php
/**
 * Element: PlainText
 * Displays plain text as element
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

require_once JPATH_PLUGINS . '/system/nnframework/helpers/text.php';

/**
 * PlainText Element
 */
class JFormFieldNN_PlainText extends JFormField
{
	public $type = 'PlainText';
	private $params = null;

	protected function getLabel()
	{
		JHtml::stylesheet('nnframework/style.min.css', false, true);

		$this->params = $this->element->attributes();

		$label = $this->prepareText($this->get('label'));
		$tooltip = $this->prepareText(trim($this->get('description')));

		if (!$label && !$tooltip)
		{
			return '';
		}

		if (!$label)
		{
			return '<div>' . $tooltip . '</div>';
		}

		if (!$tooltip)
		{
			return '<div>' . $label . '</div>';
		}

		return '<label class="hasTooltip" title="<strong>' . $label . '</strong><br />' . htmlentities($tooltip) . '">'
		. $label . '</label>';
	}

	protected function getInput()
	{
		$this->params = $this->element->attributes();

		$text = $this->prepareText(trim($this->value));

		if (!$text)
		{
			return '';
		}

		return '<fieldset class="nn_plaintext">' . $text . '</fieldset>';
	}

	private function prepareText($string = '')
	{
		if ($string == '')
		{
			return '';
		}

		// variables
		$var1 = JText::_($this->get('var1'));
		$var2 = JText::_($this->get('var2'));
		$var3 = JText::_($this->get('var3'));
		$var4 = JText::_($this->get('var4'));
		$var5 = JText::_($this->get('var5'));

		$string = JText::sprintf(JText::_($string), $var1, $var2, $var3, $var4, $var5);
		$string = trim(nnText::html_entity_decoder($string));
		$string = str_replace('&quot;', '"', $string);
		$string = str_replace('span style="font-family:monospace;"', 'span class="nn_code"', $string);

		return $string;
	}

	private function get($val, $default = '')
	{
		return (isset($this->params[$val]) && (string) $this->params[$val] != '') ? (string) $this->params[$val] : $default;
	}
}
