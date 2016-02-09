<?php
/**
 * @package Sj Super Categories for JoomShopping
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/core/helper.php';

$layout = $params->get('layout', 'default');

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$is_ajax = $is_ajax || JRequest::getInt('is_ajax_listing_tabs', 0);
if ($is_ajax) {
	$spcat_module_id = JRequest::getVar('spcat_module_id', null);
	if ($spcat_module_id == $module->id) {
		$result = new stdClass();
		ob_start();
		$catids = JRequest::getVar(('categoryid'));
		$order = JRequest::getVar('fieldorder', null);
		$loadmore = JRequest::getVar('load_more', 0);
		$orderdir = $params->get('product_ordering_direction', 'ASC');
		$tag_id = $params->get('tag_id');
		$limit_start = JRequest::getVar('ajax_limit_start',0);
		$limit = $params->get('count_products',5);
		$filters['categorys'] = explode(', ', $catids);
		if($loadmore == 1){
			$child_items = SjVMSuperCategoriesHelper::_getProductInforLoadMore('*', $params, $order,$limit_start,$limit,$orderdir);
		}else{
			$child_items = SjVMSuperCategoriesHelper::_getProductInfor('*', $params, $order);
		}
		
		require JModuleHelper::getLayoutPath($module->module, $layout . '_items');
		$buffer = ob_get_contents();
		$result->items_markup = preg_replace(
			array(
				'/ {2,}/',
				'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
			),
			array(
				' ',
				''
			),
			$buffer
		);
		ob_end_clean();
		die (json_encode($result));
	}
} else {

	if($params->get('catid') != NULL){
		$list = SjVMSuperCategoriesHelper::getList($params);
		require JModuleHelper::getLayoutPath($module->module, $layout);
		require JModuleHelper::getLayoutPath($module->module, $layout . '_js');
	}else{
		echo 'Has no item to show';
	}
}
?>
