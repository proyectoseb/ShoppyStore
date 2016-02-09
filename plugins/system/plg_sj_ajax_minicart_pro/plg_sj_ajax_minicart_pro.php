<?php
/**
 * @package Sj Ajax Minicart Pro for VirtueMart
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2009-2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemPlg_Sj_Ajax_MiniCart_Pro extends JPlugin
{

	static $_coupon_code = null;

	public static function onAfterDispatch()
	{
		$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		$is_ajax_from_minicart_pro = (int)JRequest::getVar('vm_minicart_ajax', 0);

		if ($is_ajax && $is_ajax_from_minicart_pro) {
			if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
			VmConfig::loadConfig();
			if (!class_exists('calculationHelper')) require(JPATH_VM_SITE . '/helpers/cart.php');
			switch (JRequest::getCmd('minicart_task')) {
				case 'setcoupon':
					$coupon_code = JRequest::getVar('coupon_code', '');
					$cart = VirtueMartCart::getCart(false);
					$viewName = vRequest::getString('view', 0);
					if ($viewName == 'cart') {
						$checkAutomaticPS = true;
					} else {
						$checkAutomaticPS = false;
					}
					$cart->prepareAjaxData($checkAutomaticPS);
					$result = new stdClass();

					if ($cart) {
						$msg = $cart->setCouponCode($coupon_code);
						if (empty($msg)) {
							$result->status = 0;
							$result->message = $msg;

						} else {
							$result->status = 1;
							$result->message = $cart->couponCode;
						}
					} else {
						$result->status = 0;
						$result->message = 'no cart';
					}
					if ($coupon_code == 'null'){
						$cart->couponCode = '';
						$cart->setCartIntoSession ();
					}

					die(json_encode($result));
					break;

				case 'update':
					$cart_virtuemart_product_id = JRequest::getVar('cart_virtuemart_product_id', array(), 'POST', 'array');
					$quantity_post = JRequest::getVar('quantity', array(), 'POST', 'array');

					$cart = VirtueMartCart::getCart(false);
					$cartProductsData = $cart->cartProductsData;
					$result = new stdClass();
					if (!empty($cartProductsData)) {
						$count1 = 0;
						$count2 = 0;
						$quantity = array();
						$product_indexs = JRequest::getVar('product_index', array(), 'POST', 'array');
						foreach ($product_indexs as $i => $pro) {
							if (isset($cartProductsData[$pro]) && isset($quantity_post[$i])) {
								$cartProductsData[$pro]['quantity'] = $quantity_post[$i];

							}
							$quantity[$pro] = $cartProductsData[$pro]['quantity'];
						}
						JRequest::setVar('quantity', $quantity);
						$msg = $cart->updateProductCart();

						$result->status = '1';
						$result->message = 'Update success update.';
					} else {
						$result->status = 0;
						$result->message = 'no cart';
					}
					die(json_encode($result));
					break;
				case 'refresh':
					ob_start();
					$db = JFactory::getDbo();
					$db->setQuery('SELECT * FROM #__modules WHERE id=' . JRequest::getInt('minicart_modid'));
					$result = $db->loadObject();
					if (isset($result->module)) {
						echo JModuleHelper::renderModule($result);
					}
					$list_html = ob_get_contents();
					ob_end_clean();
					$cart = VirtueMartCart::getCart(false);

					if (self::$_coupon_code == 'null') {
						CouponHelper::setInUseCoupon($cart->couponCode, false);
						die('fffffffffff');
					}
					$viewName = vRequest::getString('view', 0);
					if ($viewName == 'cart') {
						$checkAutomaticPS = true;
					} else {
						$checkAutomaticPS = false;
					}
					$cart->prepareAjaxData($checkAutomaticPS);

					$vm_currency_display = CurrencyDisplay::getInstance();
					$lang = JFactory::getLanguage();
					$extension = 'com_virtuemart';
					$lang->load($extension);
					$cart->billTotal = ' - <strong>' . $vm_currency_display->priceDisplay($cart->cartPrices['billTotal']) . '</strong>';
					$cart->billTotal_Footer = $lang->_('COM_VIRTUEMART_CART_TOTAL') . ' : <strong>' . $vm_currency_display->priceDisplay($cart->cartPrices['billTotal']) . '</strong>';
					$result = new stdClass();
					$result->list_html = $list_html;
					$result->billTotal = $cart->billTotal;
					$result->billTotal_Footer = $cart->billTotal_Footer;
					$result->length = count($cart->products);
					die(json_encode($result));
					break;
				case 'delete':
					$cart_virtuemart_product_id = JRequest::getVar('cart_virtuemart_product_id');
					$cart = VirtueMartCart::getCart(false);
					$result = new stdClass();
					$cartProductsData = $cart->cartProductsData;
					if (!empty($cartProductsData)) {
						$quantity = array();
						foreach ($cartProductsData as $key => $cartpro) {
							if ($cartProductsData[$key]['virtuemart_product_id'] == $cart_virtuemart_product_id) {
								$cartProductsData[$key]['quantity'] = 0;
							}
							$quantity[$key] = $cartProductsData[$key]['quantity'];
						}
						JRequest::setVar('quantity', $quantity);
						$msg = $cart->updateProductCart();
						$result->status = 1;
						$result->message = 'success delete';

					} else {
						$result->status = 0;
						$result->message = 'no cart';
					}
					die(json_encode($result));
					break;
				default:
					die('invalid task');
					break;
			}

			die;
		}
	}

}
