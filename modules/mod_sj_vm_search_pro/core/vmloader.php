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

$_config = JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php';
if (!class_exists('VmConfig') && file_exists($_config)) {
	require_once($_config);
}

if (!function_exists('_core_register')) {
	function _core_register($class, $path)
	{
		if (file_exists($path) && !class_exists($class)) {
			JLoader::register($class, $path);
		}
	}
}

if (defined('JPATH_VM_ADMINISTRATOR')) {
	// helpers
	_core_register('VmModel', JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'vmmodel.php');
	_core_register('calculationHelper', JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'calculationh.php');
	_core_register('CurrencyDisplay', JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'currencydisplay.php');
	_core_register('VmMediaHandler', JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'mediahandler.php');
	_core_register('VmImage', JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'image.php');
	_core_register('ShopFunctions', JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'shopfunctions.php');

	//tables
	_core_register('TableMedias', JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'medias.php');
	_core_register('TableCategories', JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'categories.php');
	_core_register('TableProducts', JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'products.php');
	_core_register('TableCustoms', JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'customs.php');
	_core_register('TableRatings', JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'ratings.php');
	_core_register('TableManufacturer_medias', JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'manufacturer_medias.php');
	_core_register('TableManufacturers', JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'manufacturers.php');

	// models
	_core_register('VirtueMartModelCategory', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'category.php');
	_core_register('VirtueMartModelProduct', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'product.php');
	_core_register('VirtueMartModelRatings', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'ratings.php');
	_core_register('VirtueMartModelVendor', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'vendor.php');
	_core_register('VirtueMartModelVirtueMart', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'virtuemart.php');
	_core_register('VirtueMartModelManufacturer', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'manufacturer.php');
	_core_register('VirtueMartModelCustom', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'custom.php');
	_core_register('VirtueMartModelCustomfields', JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'customfields.php');

}
if (defined('JPATH_VM_SITE')) {
	_core_register('VirtueMartCart', JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');
	_core_register('CouponHelper', JPATH_VM_SITE . DS . 'helpers' . DS . 'coupon.php');
	_core_register('shopFunctionsF', JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
}