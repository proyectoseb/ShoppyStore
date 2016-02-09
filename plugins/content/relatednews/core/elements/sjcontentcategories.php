<?php
/**
 * @package SjCore
 * @subpackage Elements
 * @version 1.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

class JFormFieldSjContentCategories extends JFormField{

	public function getInput(){
		$html = array();
		$attr = $this->getFieldAttributes();

		// Get the field options.
		$db = JFactory::getDbo();
		$query="
			SELECT a.id, a.title, a.level
			FROM #__categories AS a
			WHERE a.parent_id >= 0
				AND extension = 'com_content'
				AND a.published IN (0,1)
			ORDER BY a.lft
		";
		$db->setQuery($query);
		$categories = $db->loadObjectList();
		$options = array();
		foreach ($categories as $cid => $category) {
			$category_title = (($category->level) ? str_repeat('- ', $category->level-1): '') . $category->title;
			$options[] = JHtml::_('select.option', $category->id, $category_title);
		}

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true') {
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
		}
		// Create a regular list.
		else {
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

		return implode($html);
	}
	
	protected function getFieldAttributes(){
		$attr = '';
		
		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		
		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
			$attr .= ' disabled="disabled"';
		}
		
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';
		
		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		
		return $attr;
	}
}