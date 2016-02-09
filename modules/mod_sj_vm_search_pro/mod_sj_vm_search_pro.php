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
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
require_once dirname(__FILE__) . '/core/helper.php';
$layout = $params->get('layout', 'default');
 $Search_helper = new VmSearchProHelper($params, $module);
 $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
 $is_ajax_searchpro = (int)JRequest::getVar('is_ajax_searchpro', 0);
if ($is_ajax && $is_ajax_searchpro) {
	$search_module_id = (int)JRequest::getVar('search_module_id');
	if ($search_module_id == $module->id) {
		$search_category_id = JRequest::getVar('search_category_id');
		$search_name = JRequest::getVar('search_name');
		$Search_helper->_autocomplete($search_category_id,$search_name, $params);
		$category_id = vRequest::getInt ('virtuemart_category_id', 0);
		$search_name = vRequest::getVar ('keyword', '');
	}
	
}
if($params->get('show_form_category')){
	$categories = array();
	$category_id = 0;
	/* Level 1 */	
	$categories = $Search_helper->getList();
}

$category_id = vRequest::getInt ('virtuemart_category_id', 0);
$search_name = vRequest::getVar ('keyword', '');


require JModuleHelper::getLayoutPath($module->module, $params->get('layout', $layout));
require JModuleHelper::getLayoutPath($module->module, $layout . '_js');

