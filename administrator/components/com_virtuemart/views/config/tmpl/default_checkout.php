<?php
/**
 * Admin form for the checkout configuration settings
 *
 * @package	VirtueMart
 * @subpackage Config
 * @author Oscar van Eijk
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */
defined('_JEXEC') or die('Restricted access');
$js = '
		jQuery(document).ready(function( $ ) {
				if ( $("#oncheckout_opc").is(\':checked\') ) {
					$(".not_opc_param").hide();
				} else {
					$(".not_opc_param").show();
				}
			 $("#oncheckout_opc").click(function() {
				if ( $("#oncheckout_opc").is(\':checked\') ) {
					$(".not_opc_param").hide();
				} else {
					$(".not_opc_param").show();
				}
			});
		});
	';
vmJsApi::addJScript('hideNotOPC',$js);

/*
 <table width="100%">
<tr>
<td valign="top" width="50%"> */
?>
<fieldset>
	<legend><?php echo vmText::_('COM_VIRTUEMART_ADMIN_CFG_CHECKOUT_SETTINGS'); ?></legend>
	<table class="admintable">
		<?php
		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_ADDTOCART_POPUP','addtocart_popup',VmConfig::get('addtocart_popup',1));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_CFG_POPUP_REL','popup_rel',VmConfig::get('popup_rel',1));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_CHECKOUT_OPC','oncheckout_opc',VmConfig::get('oncheckout_opc',1));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_CFG_OPC_AJAX','oncheckout_ajax',VmConfig::get('oncheckout_ajax',1));
		?>

		<tr class="not_opc_param">
			<td class="key">
            	<span class="hasTip" title="<?php echo vmText::_('COM_VIRTUEMART_ADMIN_ONCHECKOUT_SHOW_STEPS_TIP'); ?>">
					<label for="oncheckout_show_steps">
						<?php echo vmText::_('COM_VIRTUEMART_ADMIN_ONCHECKOUT_SHOW_STEPS'); ?>
					</label>
                </span>
			</td>
			<td>
				<?php echo VmHTML::checkbox('oncheckout_show_steps', VmConfig::get('oncheckout_show_steps',0)); ?>
			</td>
		</tr> <?php
		echo VmHTML::row('genericlist','COM_VIRTUEMART_ADMIN_CFG_AUTOMATIC_SHIPMENT',$this->listShipment,'set_automatic_shipment','','virtuemart_shipmentmethod_id','shipment_name',VmConfig::get('set_automatic_shipment',0));
		echo VmHTML::row('genericlist','COM_VIRTUEMART_ADMIN_CFG_AUTOMATIC_PAYMENT',$this->listPayment,'set_automatic_payment','','virtuemart_paymentmethod_id','payment_name',VmConfig::get('set_automatic_payment',0));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_CFG_AGREE_TERMS_ONORDER','agree_to_tos_onorder',VmConfig::get('agree_to_tos_onorder',1));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_ONCHECKOUT_SHOW_LEGALINFO','oncheckout_show_legal_info',VmConfig::get('oncheckout_show_legal_info',1));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_ONCHECKOUT_SHOW_REGISTER','oncheckout_show_register',VmConfig::get('oncheckout_show_register',1));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_ONCHECKOUT_ONLY_REGISTERED','oncheckout_only_registered',VmConfig::get('oncheckout_only_registered',0));
		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_ONCHECKOUT_SHOW_PRODUCTIMAGES','oncheckout_show_images',VmConfig::get('oncheckout_show_images',0));


		$attrlist = 'class="inputbox" multiple="multiple" ';
		echo VmHTML::row('genericlist','COM_VIRTUEMART_ADMIN_CFG_STATUS_PDF_INVOICES',$this->osWoP_Options,'inv_os[]',$attrlist, 'order_status_code', 'order_status_name', VmConfig::get('inv_os',array('C')), 'inv_os',true);
		echo VmHTML::row('genericlist','COM_VIRTUEMART_CFG_OSTATUS_EMAILS_SHOPPER',$this->osWoP_Options,'email_os_s[]',$attrlist, 'order_status_code', 'order_status_name', VmConfig::get('email_os_s',array('U','C','S','R','X')), 'email_os_s',true);
		echo VmHTML::row('genericlist','COM_VIRTUEMART_CFG_OSTATUS_EMAILS_VENDOR',$this->os_Options,'email_os_v[]',$attrlist, 'order_status_code', 'order_status_name', VmConfig::get('email_os_v',array('U','C','R','X')), 'email_os_v',true);

		echo VmHTML::row('checkbox','COM_VIRTUEMART_ADMIN_ONCHECKOUT_CHANGE_SHOPPER','oncheckout_change_shopper',VmConfig::get('oncheckout_change_shopper',1));

		/*$_delivery_date_options = array(
			'm' => vmText::_('COM_VIRTUEMART_DELDATE_INV')
		, 'osP' => vmText::_('COM_VIRTUEMART_ORDER_STATUS_PENDING')
		, 'osU' => vmText::_('COM_VIRTUEMART_ORDER_STATUS_CONFIRMED_BY_SHOPPER')
		, 'osC' => vmText::_('COM_VIRTUEMART_ORDER_STATUS_CONFIRMED')
		, 'osS' => vmText::_('COM_VIRTUEMART_ORDER_STATUS_SHIPPED')
		, 'osR' => vmText::_('COM_VIRTUEMART_ORDER_STATUS_REFUNDED')
		, 'osC' => vmText::_('COM_VIRTUEMART_ORDER_STATUS_CANCELLED')
		);*/

		echo VmHTML::row('genericlist','COM_VIRTUEMART_CFG_DELDATE_INV',$this->osDel_Options,'del_date_type','class="inputbox"', 'order_status_code', 'order_status_name', VmConfig::get('del_date_type',array('m')), 'del_date_type',true);

		//echo VmHTML::row('selectList','COM_VIRTUEMART_CFG_DELDATE_INV','del_date_type', VmConfig::get('del_date_type','m'), $_delivery_date_options);
		echo VmHTML::row('input','COM_VIRTUEMART_CFG_ATTACH','attach', VmConfig::get('attach',''));
		echo VmHTML::row('genericlist','COM_VIRTUEMART_CFG_ATTACH_OS',$this->osWoP_Options,'attach_os[]',$attrlist, 'order_status_code', 'order_status_name', VmConfig::get('attach_os',array('U','C','R','X')), 'attach_os',true);

		?>

	</table>
</fieldset>