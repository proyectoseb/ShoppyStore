<?php
/**
 * @package SJ Search Pro for VirtueMart
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2015 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
JFormHelper::loadFieldClass('list');

if (!class_exists('JFormFieldSjVmCustomFields')) {

	class JFormFieldSjVmCustomFields extends JFormFieldList
	{
		protected $customfields = null;

		public function getInput()
		{
			if ($this->vm_require()) {
				$customfields = $this->getCustomFields();
				if (!count($customfields)) {
					$input = '<div style="margin: 5px 0;float: left;font-size: 1.091em;">You have no custom fields to select.</div>';
				} else {
					$input = parent::getInput();
				}
			} else {
				$input = '<div style="margin: 5px 0;float: left;font-size: 1.091em;">Maybe your component (Virtuemart) has been installed incorrectly. <br/>Please sure your component work properly. <br/>If you still get errors, please contact us via our <a href="http://www.smartaddons.com/forum/" target="_blank">forum</a> or <a href="http://www.smartaddons.com/tickets/" target="_blank">ticket system</a></div>';
			}
			return $input;
		}

		protected function vm_require()
		{
			if (!class_exists('VmConfig')) {
				if (file_exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php')) {
					require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
				} else {
					$this->error = 'Could not find VmConfig helper';
					return false;
				}
			}

			VmConfig::loadConfig();
			VmConfig::loadJLang('com_virtuemart', true);

			if (!class_exists('VmModel')) {
				if (defined('JPATH_VM_ADMINISTRATOR') && file_exists(JPATH_VM_ADMINISTRATOR . '/helpers/vmmodel.php')) {
					require JPATH_VM_ADMINISTRATOR . '/helpers/vmmodel.php';
				} else {
					$this->error = 'Could not find VmModel helper';
					return false;
				}
			}
			if (defined('JPATH_VM_ADMINISTRATOR')) {
				JTable::addIncludePath(JPATH_VM_ADMINISTRATOR . '/tables');
			}
			if (!class_exists('VirtueMartModelCustom')) {
				require JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'custom.php';
			}
			if (!class_exists('VirtueMartModelCustomfields')) {
				require JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'customfields.php';
			}
			return true;
		}

		protected function getCustomFields()
		{
			if (is_null($this->customfields)) {
				$this->customfields = array();
				$db = JFactory::getDBO();
				$query = ' * FROM `#__virtuemart_customs` WHERE field_type = "S"  ';
				$query .= 'AND `custom_parent_id` = 0';
				$_custom_model = VmModel::getModel('custom');
				$list = array();
				$customs = $_custom_model->exeSortSearchListQuery(0, $query, '', '', $_custom_model->_getOrdering());
				$_list = array();
				if (!empty($customs)) {
					foreach ($customs as $custom) {
						$list[$custom->custom_title] = $custom;
					}
				}

				$this->customfields = $list;
			}

			return $this->customfields;
		}


		public function getOptions()
		{
			$options = parent::getOptions();
			$customfields = $this->getCustomFields();
			if (count($customfields)) {
				foreach ($customfields as $key => $custom) {
					$value = $custom->virtuemart_custom_id;
					$text = $key;
					$options[] = JHtml::_('select.option', $value, $text);
				}
			}

			return $options;
		}

	}
}