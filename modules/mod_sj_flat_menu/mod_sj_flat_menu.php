<?php
/**
 * @package Sj Flat Menu
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;


if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once dirname(__FILE__).'/core/helper.php';

$layout = $params->get('layout', 'default');
$cacheid = md5(serialize(array ($layout, $module->id)));
$cacheparams = new stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = 'FlatMenuHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = $cacheid;
$menus = JModuleHelper::moduleCache ($module, $params, $cacheparams);
$type_menu = $params->get('type_menu');
$imagesURI = JURI::base()."modules/".$module->module."/assets/images";
$icon_normal = $imagesURI."/icon_active.png";
$icon_active = $imagesURI."/icon_normal.png";
$itemID = JRequest::getInt('Itemid');
require JModuleHelper::getLayoutPath($module->module, $layout);
require JModuleHelper::getLayoutPath($module->module, $layout.'_js');
?>