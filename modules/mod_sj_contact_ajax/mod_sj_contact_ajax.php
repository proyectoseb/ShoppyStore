<?php
/**
 * @package Sj Contact Ajax
 * @version 1.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once dirname(__FILE__).'/core/helper.php';

if(!class_exists('plgSystemPlg_Sj_Contact_Ajax')){
	echo JText::_('WARNING_NOT_INSTALL_PLUGIN');
	return;
}

$layout = $params->get('layout', 'default');
$cacheid = md5(serialize(array ($layout, $module->id)));
$cacheparams = new stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = 'ContactAjax';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = $cacheid;
$list = JModuleHelper::moduleCache ($module, $params, $cacheparams);
$captcha_type = $params->get('captcha_type');
$captcha_dis = $params->get('captcha_dis');
$captcha_disable = $params->get('captcha_disable');

if($captcha_dis == 1){
	if($captcha_type == 0){
			$captcha_plg = JPluginHelper::importPlugin('captcha');
		if($captcha_plg == null){
			echo JText::_('WARNING_NOT_INSTALL_PLUGIN_RECAPTCHA');
			return;
		}
	}
}

$user =  JFactory::getUser();
$currentSession = JFactory::getSession() ;
if ($list != false){
	$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	if($is_ajax){
		$ctajax_modid	= JRequest::getVar('ctajax_modid', null);
		if($ctajax_modid == $module->id){
			if(isset($_POST['task'])){
				if($_POST['task'] == 'checkcaptcha'){
					$ss_c = $currentSession->get('codeCaptcha'.$module->id);
					$result = new stdClass();
					if($_POST['captcha'] != $ss_c){
						$result->valid = false;
					}else{
						$result->valid = true;
					}
					echo json_encode($result);
					die();
				}
				if($_POST['task'] == 'sendmail'){
					if($captcha_dis == 1) { 
						if($captcha_disable == 1 && $user->id != 0 ){
						}else{
							if($captcha_type == 0){
								JPluginHelper::importPlugin('captcha');
								$dispatcher = JDispatcher::getInstance();
								JRequest::setVar('recaptcha_challenge_field', $_POST['recaptcha_challenge']);
								JRequest::setVar('recaptcha_response_field', $_POST['recaptcha_response']);
								$res = $dispatcher->trigger('onCheckAnswer',$_POST['recaptcha_response']);
								$result = new stdClass();
								if(!$res[0]){
									$result->error_captcha = 0;
									echo json_encode($result);
									die();
								}else{	
									ContactAjax::_processSendMail();
								}
							}
						} 
					} 
					$mail_to = $list->email_to;
					ContactAjax::_processSendMail($mail_to);
				}	
			}	
		}
	}else{
		require JModuleHelper::getLayoutPath($module->module, $layout);
		require JModuleHelper::getLayoutPath($module->module, $layout.'_js');
	}
 } else {
	 echo JText::_('WARNING_MASSAGE');
 }
