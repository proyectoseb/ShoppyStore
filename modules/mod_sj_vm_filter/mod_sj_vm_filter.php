<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ('_JEXEC') or die;
defined ('DS') or define('DS',DIRECTORY_SEPARATOR);
require_once dirname (__FILE__).'/core/helper.php';
$layout = $params->get ('layout','default');
$ft_helper = new VmFilterHelper($params,$module);
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower ($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$is_ajax_ft = (int)JRequest::getVar ('is_ajax_ft',0);
if ($is_ajax && $is_ajax_ft){
	$ft_module_id = (int)JRequest::getVar ('ft_module_id');
	if ($ft_module_id == $module->id){
		$_cf_data = JRequest::getVar ('_config_data');
		$ft_helper->_processDataAjax ($_cf_data,$params);
	}
}
require JModuleHelper::getLayoutPath ($module->module,$params->get ('layout',$layout));
require JModuleHelper::getLayoutPath ($module->module,$layout.'_js');

