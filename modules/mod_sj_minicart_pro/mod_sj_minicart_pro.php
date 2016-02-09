<?php
/**
 * @package Sj Minicart Pro for VirtueMart
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

if (!class_exists('plgSystemPlg_Sj_Ajax_MiniCart_Pro')) {
	echo '<p ><b>' . JText::_('WARNING_NOT_INSTALL_PLUGIN') . '</b></p>';
	return;
}

require_once dirname(__FILE__) . '/core/helper.php';

$layout = $params->get('layout', 'default');
$cart = SjMiniCartProHelper::getList($params);

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$is_ajax_from_minicart_pro = (int)JRequest::getVar('vm_minicart_ajax', 0);

if ($is_ajax && $is_ajax_from_minicart_pro) {
	if (JRequest::getCmd('minicart_task') == 'refresh') {
		require JModuleHelper::getLayoutPath($module->module, $layout . '_list');
	}
} else {
	if ($cart) {
		$vm_currency_display = CurrencyDisplay::getInstance();
		$lang = JFactory::getLanguage();
		$extension = 'com_virtuemart';
		$lang->load($extension);
		if ($cart->_dataValidated == true) {
			$taskRoute = '&task=confirm';
			$linkName = JText::_('GO_TO_CART');
		} else {
			$taskRoute = '';
			$linkName = JText::_('GO_TO_CART');
		}
		
		$linkshopingcart = JRoute::_("index.php?option=com_virtuemart&view=cart" . $taskRoute);
		$cart->ajaxurl = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';
		$cart->ajaxurl .= $_SERVER['HTTP_HOST'] . $linkshopingcart;
		$cart->cart_show = '<a class="mc-gotocart" href="' . $linkshopingcart . '">' . $linkName . '</a>';

		$cart->billTotal =' - <strong>' . $vm_currency_display->priceDisplay($cart->cartPrices['billTotal']) . '</strong>';
		$cart->billTotal_Footer = $lang->_('COM_VIRTUEMART_CART_TOTAL') . ' : <strong>' . $vm_currency_display->priceDisplay($cart->cartPrices['billTotal']) . '</strong>';
		$cart->checkout = JRoute::_('index.php?option=com_virtuemart&view=cart&task=checkout', FALSE);
		require JModuleHelper::getLayoutPath($module->module, $layout);
		require JModuleHelper::getLayoutPath($module->module, $layout . '_js');
	}
}

?>