<?php
/**
 * @package Sj Vm Slick Slider
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */
defined('_JEXEC') or die;

if (!class_exists('VmConfig')) {
    require(JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_virtuemart' . '/' . 'helpers' . '/' . 'config.php');
}
VmConfig::loadConfig();

// Load the language file of com_virtuemart.
JFactory::getLanguage()->load('com_virtuemart');
if (!class_exists('calculationHelper')) {
    require(JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_virtuemart' . '/' . 'helpers' . '/' . 'calculationh.php');
}
if (!class_exists('CurrencyDisplay')) {
    require(JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_virtuemart' . '/' . 'helpers' . '/' . 'currencydisplay.php');
}
if (!class_exists('VirtueMartModelVendor')) {
    require(JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_virtuemart' . '/' . 'models' . '/' . 'vendor.php');
}
if (!class_exists('VmImage')) {
    require(JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_virtuemart' . '/' . 'helpers' . '/' . 'image.php');
}
if (!class_exists('shopFunctionsF')) {
    require(JPATH_SITE . '/' . 'components' . '/' . 'com_virtuemart' . '/' . 'helpers' . '/' . 'shopfunctionsf.php');
}
if (!class_exists('calculationHelper')) {
    require(JPATH_COMPONENT_SITE . '/' . 'helpers' . '/' . 'cart.php');
}
if (!class_exists('VirtueMartModelProduct')) {
    JLoader::import('product', JPATH_ADMINISTRATOR . '/' . 'components' . '/' . 'com_virtuemart' . '/' . 'models');
}

if(!defined('ADD_TO_CART')){
    vmJsApi::css('vm-ltr-site');
    vmJsApi::css('vm-ltr-common');
    vmJsApi::css('chosen.css');
	vmJsApi::css('jquery.fancybox-1.3.4');
	VmConfig::loadJLang('com_virtuemart',true);
    $document = JFactory::getDocument();
    $file = 'components/com_virtuemart/assets/js/vmprices.js';
    $file1 = 'components/com_virtuemart/assets/js/fancybox/jquery.fancybox-1.3.4.pack.js';
	$document->addScript('components/com_virtuemart/assets/js/vmsite.js');
	$document->addScript('components/com_virtuemart/assets/js/chosen.jquery.min.js');
	$document->addScript('components/com_virtuemart/assets/js/dynupdate.js');
    $document->addScript($file);
    $document->addScript($file1);
    define('ADD_TO_CART', 1);
}
?>