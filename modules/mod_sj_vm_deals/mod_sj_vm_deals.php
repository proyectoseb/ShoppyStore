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

	$layout = $params->get('layout', 'default');
	$type = $params->get('item_countdown_display');	
	if($type != 'POP'){
		require_once dirname(__FILE__) . '/core/helper_deals.php';
		$list = VMDealsHelper::getList($params);
		if($params->get('item_categori_title_display') == 0){
			$category_info = VMDealsHelper::getList($params,1);
		}
	}else{
		require_once dirname(__FILE__) . '/core/helper.php';
		$list = VMDealsPHelper::getList($params);
		$category_info = VMDealsPHelper::getList($params,1);
	}
	if(isset($list) && count($list) > 0){
		require JModuleHelper::getLayoutPath($module->module, $layout);
		require JModuleHelper::getLayoutPath($module->module, $layout.'_js');
	}else{
		echo 'Has no Item';
	}
   


?>
