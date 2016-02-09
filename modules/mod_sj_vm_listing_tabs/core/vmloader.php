<?php
/**
 * @package Sj Vm Listing Tabs
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

if (!function_exists('_core_register')) {
    function _core_register($class, $path)
    {
        if (file_exists($path)) {
            JLoader::register($class, $path);
        }
    }
}
if (file_exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php')) {
    require_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
}
if (defined('JPATH_VM_ADMINISTRATOR')) {
    // helpers
    _core_register('calculationHelper', JPATH_VM_ADMINISTRATOR . '/helpers/calculationh.php');
    _core_register('CurrencyDisplay', JPATH_VM_ADMINISTRATOR . '/helpers/currencydisplay.php');
    _core_register('VmMediaHandler', JPATH_VM_ADMINISTRATOR . '/helpers/mediahandler.php');
    _core_register('VmImage', JPATH_VM_ADMINISTRATOR . '/helpers/image.php');
    _core_register('ShopFunctions', JPATH_VM_ADMINISTRATOR . '/helpers/shopfunctions.php');

    // models
    _core_register('VirtueMartModelCategory', JPATH_VM_ADMINISTRATOR . '/models/category.php');
    _core_register('VirtueMartModelProduct', JPATH_VM_ADMINISTRATOR . '/models/product.php');
    _core_register('VirtueMartModelRatings', JPATH_VM_ADMINISTRATOR . '/models/ratings.php');
    _core_register('VirtueMartModelVendor', JPATH_VM_ADMINISTRATOR . '/models/vendor.php');
    _core_register('VirtueMartModelVirtueMart', JPATH_VM_ADMINISTRATOR . '/models/virtuemart.php');
}
if (defined('JPATH_VM_SITE')) {
    _core_register('VirtueMartCart', JPATH_VM_SITE . '/helpers/cart.php');
    _core_register('CouponHelper', JPATH_VM_SITE . '/helpers/coupon.php');
    _core_register('shopFunctionsF', JPATH_VM_SITE . '/helpers/shopfunctionsf.php');
}

if (!defined('ADD_TO_CART')) {
    vmJsApi::css('vm-ltr-site');
    vmJsApi::css('vm-ltr-common');
    vmJsApi::css('jquery.fancybox-1.3.4');
    $document = JFactory::getDocument();
    $file = 'components/com_virtuemart/assets/js/vmprices.js';
    $file1 = 'components/com_virtuemart/assets/js/fancybox/jquery.fancybox-1.3.4.pack.js';
    $document->addScript($file);
    $document->addScript($file1);
    VmConfig::loadJLang('com_virtuemart', true);
    define('ADD_TO_CART', 1);
}
?>