<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage shipmentmethod
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_config.php 7497 2013-12-18 14:24:09Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if (JVM_VERSION < 3){
	$control_field_class="width100 floatleft control-field";
	$control_group_class="width100 control-group";
	$control_label_class="width25 floatleft control-label";
	$control_input_class="width74 floatright control-input";
} else {
	$control_field_class="control-field";
	$control_group_class="control-group";
	$control_label_class="control-label";
	$control_input_class="control-input";
}
if ($this->shipment->shipment_jplugin_id) {
	?>
	<h2 style="text-align: center;"><?php echo $this->shipment->shipment_name ?></h2>
	<div style="text-align: center;"><?php echo  VmText::_('COM_VIRTUEMART_SHIPPING_CLASS_NAME').": ".$this->shipment->shipment_element ?></div>
	<?php
	if ($this->shipment->form) {
		$form = $this->shipment->form;
		include(VMPATH_ADMIN.DS.'fields'.DS.'formrenderer.php');
	}
} else {
	echo vmText::_('COM_VIRTUEMART_SELECT_SHIPMENT_METHOD_VM3');
}




