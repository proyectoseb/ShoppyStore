<?php
defined('_JEXEC') or die();

/**
 *
 * @package    VirtueMart
 * @subpackage Plugins  - Elements
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');

if (!class_exists('ShopFunctions'))
	require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
if (!class_exists('TableCategories'))
	require(VMPATH_ADMIN . DS . 'tables' . DS . 'categories.php');
jimport('joomla.form.formfield');

/*
 * This element is used by the menu manager
 * Should be that way
 */
class JFormFieldVmcategories extends JFormField {

	var $type = 'vmcategories';

	protected function getInput() {

		VmConfig::loadConfig();
		VmConfig::loadJLang('com_virtuemart');

		if(!is_array($this->value))$this->value = array($this->value);
		$categorylist = ShopFunctions::categoryListTree($this->value);

		$name = $this->name;
		if($this->multiple){
			$name = $this->name;
			$this->multiple = ' multiple="multiple" ';
		}

		$html = '<select class="inputbox"   name="' . $name . '" '.$this->multiple.' >';
		if(!$this->multiple)$html .= '<option value="0">' . vmText::_('COM_VIRTUEMART_CATEGORY_FORM_TOP_LEVEL') . '</option>';
		$html .= $categorylist;
		$html .= "</select>";
		return $html;
	}


}


