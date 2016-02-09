<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class plgSystemRegacymailing extends JPlugin{
	var $option = '';
	var $view = '';

	function plgSystemRegacymailing(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('system', 'regacymailing');
			$this->params = new JParameter($plugin->params);
		}
	}

	function onAfterRoute(){
		if(!empty($_POST['option']) && $_POST['option'] == 'com_virtuemart' && !empty($_POST['func']) && $_POST['func'] == 'shopperupdate'){
			$this->_updateVM();
		}

		if(!empty($_REQUEST['option']) && $_REQUEST['option'] == 'com_community' && !empty($_REQUEST['task']) && ($_REQUEST['task'] == 'register_save' || $_REQUEST['task'] == 'save')){
			$this->_saveInSession();
		}

		if(!empty($_REQUEST['option']) && $_REQUEST['option'] == 'com_jblance' && !empty($_REQUEST['layout']) && in_array($_REQUEST['layout'], array('showfront', 'planadd'))){
			$this->_saveInSession();
		}

		if(!empty($_REQUEST['option']) && in_array($_REQUEST['option'], array('com_user', 'com_users')) && !empty($_REQUEST['view']) && in_array($_REQUEST['view'], array('register', 'registration', 'profile', 'user'))){
			require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
			$fieldsClass = acymailing_get('class.fields');
			$fieldsClass->origin = 'joomla';
			$user = new stdClass();
			$app = JFactory::getApplication();

			$taskVar = ACYMAILING_J16 ? 'layout' : 'task';

			if($app->isAdmin()){
				if($_REQUEST['view'] == 'user' && !empty($_REQUEST[$taskVar]) && $_REQUEST[$taskVar] == 'edit'){
					$extraFields = $fieldsClass->getFields('joomlaprofile', $user);
				}
			}else{
				if(in_array($_REQUEST['view'], array('register', 'registration'))){
					$extraFields = $fieldsClass->getFields('frontjoomlaregistration', $user);
				}elseif(in_array($_REQUEST['view'], array('user', 'profile')) && (!empty($_REQUEST[$taskVar]) && $_REQUEST[$taskVar] == 'edit')){
					$extraFields = $fieldsClass->getFields('frontjoomlaprofile', $user);
				}
			}

			if(!empty($extraFields)){
				foreach($extraFields as $oneField){
					if($oneField->type != 'date') continue;
					JHTML::_('behavior.calendar');
					break;
				}
			}
		}
	}

	private function _saveInSession(){
		$acysub = JRequest::getVar('acysub', array(), '', 'array');
		$session = JFactory::getSession();
		if(!empty($acysub)){
			$session->set('acysub', $acysub);
		}

		$acysubhidden = JRequest::getString('acysubhidden');
		if(!empty($acysubhidden)){
			$session->set('acysubhidden', $acysubhidden);
		}

		$regacy = JRequest::getVar('regacy', array(), '', 'array');
		if(!empty($regacy)){
			$session->set('regacy', $regacy);
		}
	}

	private function _updateVM(){
		$user = JFactory::getUser();
		if(empty($user->id)) return;

		$acylistsdisplayed = JRequest::getString('acylistsdisplayed_dispall').','.JRequest::getString('acylistsdisplayed_onecheck');
		if(strlen($acylistsdisplayed) < 2) return;
		$listsDisplayed = explode(',', $acylistsdisplayed);
		JArrayHelper::toInteger($listsDisplayed);
		if(empty($listsDisplayed)) return;

		if(!include_once(rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php')) return;

		$userClass = acymailing_get('class.subscriber');

		$subid = $userClass->subid($user->id);
		if(empty($subid)) return; //The user should already be there

		$visiblelistschecked = JRequest::getVar('acysub', array(), '', 'array');
		$acySubHidden = JRequest::getString('acysubhidden');
		if(!empty($acySubHidden)){
			$visiblelistschecked = array_merge($visiblelistschecked, explode(',', $acySubHidden));
		}

		$listsClass = acymailing_get('class.list');
		$allLists = $listsClass->getLists('listid');
		if(acymailing_level(1)){
			$allLists = $listsClass->onlyCurrentLanguage($allLists);
		}

		$formLists = array();
		foreach($listsDisplayed as $listidDisplayed){
			$newlists = array();
			$newlists['status'] = in_array($listidDisplayed, $visiblelistschecked) ? '1' : '-1';
			$formLists[$listidDisplayed] = $newlists;
		}

		$userClass->saveSubscription($subid, $formLists);
	}

	function _getVmVersion(){
		$file = ACYMAILING_ROOT.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'version.php';
		if(!file_exists($file)) return '0.0.0';
		include_once($file);
		$vmversion = new vmVersion();
		if(empty($vmversion->RELEASE)){
			return vmVersion::$RELEASE;
		}else{
			return $vmversion->RELEASE;
		}
	}

	function onAfterRender(){
		$helperFile = rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
		if(!file_exists($helperFile) || !include_once($helperFile)) return;

		$option = JRequest::getCmd('option', '', 'GET');
		if(empty($option)) $option = JRequest::getCmd('option');

		if(empty($option)) return;
		$this->option = $option;

		$this->components = array();
		$this->components['com_user'] = array('view' => array('register', 'user'), 'edittasks' => array('profile', 'user'), 'lengthafter' => 200, 'email' => array('email2', 'email'), 'password' => array('password2', 'password'), 'displayBackend' => true, 'displayLoggedin' => true);
		$jversion = preg_replace('#[^0-9\.]#i', '', JVERSION);
		if(version_compare($jversion, '1.6.0', '>=')){
			$this->components['com_users'] = array('view' => array('registration', 'profile', 'user'), 'edittasks' => array('profile', 'user'), 'lengthafter' => 200, 'email' => array('jform[email2]', 'jform[email]'), 'password' => 'jform[password2]', 'displayBackend' => true, 'displayLoggedin' => true, 'checkLayout' => array('profile' => 'edit'));
		}else{
			$this->components['com_users'] = array('view' => array('registration', 'profile', 'user'), 'edittasks' => array('profile', 'user'), 'lengthafter' => 200, 'email' => array('email2', 'email'), 'password' => 'password2', 'displayBackend' => true, 'displayLoggedin' => true, 'tdfieldlabelclass' => 'key', 'tdclassfield' => 'key');
		}

		$this->components['com_alpharegistration'] = array('view' => array('register'), 'lengthafter' => 250);
		$this->components['com_ccusers'] = array('view' => array('register'), 'lengthafter' => 500);
		$this->components['com_community'] = array('view' => array('register', 'profile'), 'edittasks' => array('profile'), 'lengthafter' => 500, 'password' => 'jspassword2', 'email' => 'jsemail', 'displayLoggedin' => true, 'fieldclass' => 'form-field', 'labelclass' => 'form-label', 'tdclassfield' => 'paramlist_key', 'tdclassvalue' => 'paramlist_value');
		$this->components['com_extendedreg'] = array('view' => array('register'), 'lengthafter' => 200, 'password' => 'verify-password', 'email' => 'email');
		$this->components['com_gcontact'] = array('view' => array('registration'), 'lengthafter' => 200);
		$this->components['com_hikashop'] = array('view' => array('checkout', 'user'), 'viewvar' => 'ctrl', 'lengthafter' => 500, 'tdclassfield' => 'key', 'email' => 'data[register][email]', 'password' => 'data[register][password2]');
		$this->components['com_jblance'] = array('view' => array('guest'), 'layout' => array('register'), 'lengthaftermin' => 250, 'lengthafter' => 300, 'email' => 'email', 'password' => 'password2');
		$this->components['com_jshopping'] = array('view' => array('register', 'checkout'), 'viewvar' => array('task', 'controller'), 'lengthafter' => 200, 'email' => 'email', 'password' => 'password_2', 'displayLoggedin' => true);
		$this->components['com_juser'] = array('view' => array('user'), 'lengthafter' => 200);
		$this->components['com_mijoshop'] = array('viewvar' => array('route', 'view'), 'view' => array('registration', 'account/register', 'account/edit', 'account/registration'), 'edittasks' => array('account/edit', 'account/registration'), 'displayLoggedin' => true, 'lengthafter' => 500, 'email' => 'email', 'password' => array('confirm', 'password'));
		$this->components['com_osemsc'] = array('view' => array('register'), 'lengthafter' => 200, 'email' => 'oseemail', 'password' => 'osepassword2');
		$this->components['com_redshop'] = array('view' => array('registration'), 'lengthafter' => 200, 'password' => 'password2', 'email' => 'email1');
		$this->components['com_tienda'] = array('view' => array('checkout'), 'lengthafter' => 500, 'email' => 'email_address', 'password' => 'password2');
		$vmViews = array('shop.registration', 'account.billing', 'checkout.index', 'user', 'cart', 'editaddresscart', 'editaddresscheckout');
		if(version_compare($this->_getVmVersion(), '3.0.10', '>=')) $vmViews[] = 'askquestion';
		$this->components['com_virtuemart'] = array('view' => $vmViews, 'displayLoggedin' => true, 'viewvar' => 'page', 'lengthafter' => 500, 'acysubscribestyle' => 'style="clear:both"');

		if($option == 'com_rsform'){
			$formId = JRequest::getCmd('formId', '', 'GET');
			if(empty($formId)) $formId = JRequest::getCmd('formId');
			$db = JFactory::getDBO();
			if(!empty($formId) && in_array($db->getPrefix().'rsform_registration', $db->getTableList())){
				$db->setQuery('SELECT * FROM #__rsform_registration WHERE form_id = '.intval($formId).' AND published = 1');
				$registration = $db->loadObject();
				if(!empty($registration)){
					$regVar = empty($registration->reg_merge_vars) ? 'vars' : 'reg_merge_vars';
					$registrationVars = unserialize($registration->$regVar);
					$this->components['com_rsform'] = array('view' => array('rsform'), 'lengthafter' => 220, 'lengthaftermin' => 190, 'password' => array('form['.$registrationVars['password2'].']', 'form['.$registrationVars['password1'].']'), 'email' => array('form['.$registrationVars['email2'].']', 'form['.$registrationVars['email1'].']'));
				}
			}
		}

		$excludedComponents = $this->params->get('excluded');
		if(!empty($excludedComponents)){
			if(!ACYMAILING_J16) $excludedComponents = explode(',', $excludedComponents);
			foreach($excludedComponents as $oneComponent){
				unset($this->components[$oneComponent]);
			}
		}

		if(!isset($this->components[$option])) return;
		$viewVar = (isset($this->components[$option]['viewvar']) ? $this->components[$option]['viewvar'] : 'view');
		if(!is_array($viewVar)){
			if(!in_array(JRequest::getString($viewVar, JRequest::getString('task', JRequest::getString('view'))), $this->components[$option]['view'])) return;
			$this->view = JRequest::getString($viewVar, JRequest::getString('task', JRequest::getString('view')));
		}else{
			$isvalid = false;
			foreach($viewVar as $oneVar){
				if(in_array(JRequest::getString($oneVar, JRequest::getString('task', JRequest::getString('view'))), $this->components[$option]['view'])){
					$isvalid = true;
					$this->view = JRequest::getString($oneVar, JRequest::getString('task', JRequest::getString('view')));
					break;
				}
			}
			if(!$isvalid) return;
		}

		if(isset($this->components[$option]['layout']) && !in_array(JRequest::getString('layout'), $this->components[$option]['layout'])) return;

		if(empty($this->components[$option]['displayBackend'])){
			$app = JFactory::getApplication();
			if($app->isAdmin()) return;
		}
		if(empty($this->components[$option]['displayLoggedin'])){
			$user = JFactory::getUser();
			if(!empty($user->id)) return;
		}


		if($option == 'com_community' && in_array(JRequest::getString('task'), array('registerAvatar', 'registerProfile'))) return;

		$this->_addFields();
		$this->_addLists();
		$this->_addCSS();
	}

	private function _addFields(){
		if(!acymailing_level(3)) return;

		$option = $this->option;

		if(empty($this->components[$option]['lengthaftermin'])) $this->components[$option]['lengthaftermin'] = 0;

		$app = JFactory::getApplication();
		if($app->isAdmin()){
			$area = 'joomlaprofile';
		}elseif(!empty($this->components[$option]['edittasks']) && in_array($this->view, $this->components[$option]['edittasks'])){
			$area = 'frontjoomlaprofile';
		}else{
			$area = 'frontjoomlaregistration';
		}

		$fieldsClass = acymailing_get('class.fields');
		$fieldsClass->origin = 'joomla';
		$user = new stdClass();
		$extraFields = $fieldsClass->getFields($area, $user);

		$newOrdering = array();
		foreach($extraFields as $fieldnamekey => $oneField){
			if(in_array($oneField->namekey, array('name', 'email'))) continue;
			$newOrdering[] = $fieldnamekey;
		}

		if(empty($newOrdering)) return;

		$body = JResponse::getBody();

		$severalValueTest = false;
		if($this->params->get('customfieldsafter', 'email') == "custom"){
			$customFieldAfter = explode(';', str_replace(array('\\[', '\\]'), array('[', ']'), $this->params->get('customfieldsaftercustom')));
			$after = !empty($customFieldAfter) ? $customFieldAfter : $this->components[$option]['email'];
		}elseif(!empty($this->components[$option][$this->params->get('customfieldsafter', 'email')])){
			$after = $this->components[$option][$this->params->get('customfieldsafter', 'email')];
		}else{
			$after = ($this->params->get('customfieldsafter', 'email') == 'email') ? 'email' : 'password2';
		}
		if(is_array($after)){
			$severalValueTest = true;
			$allAfters = $after;
			$after = $after[0];
		}

		$allFormats = array();
		$allFormats['tr'] = array('tagfield' => 'tr', 'tagfieldname' => 'td', 'tagfieldvalue' => 'td');
		$allFormats['li'] = array('tagfield' => 'li', 'tagfieldname' => '', 'tagfieldvalue' => 'div');
		$allFormats['div'] = array('tagfield' => 'div', 'tagfieldname' => '', 'tagfieldvalue' => '');
		$allFormats['p'] = array('tagfield' => 'p', 'tagfieldname' => '', 'tagfieldvalue' => '');
		$allFormats['dd'] = array('tagfield' => '', 'tagfieldname' => 'dt', 'tagfieldvalue' => 'dd');

		$currentFormat = '';
		foreach($allFormats as $oneFormat => $values){
			if(preg_match('#(name="'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</'.$oneFormat.'>)#Uis', $body)){
				$currentFormat = $oneFormat;
				break;
			}
		}

		if(empty($currentFormat) && $severalValueTest){
			$i = 1;
			while(empty($currentFormat) && $i < count($allAfters)){
				foreach($allFormats as $oneFormat => $values){
					if(preg_match('#(name="'.preg_quote($allAfters[$i]).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</'.$oneFormat.'>)#Uis', $body)){
						$after = $allAfters[$i];
						$currentFormat = $oneFormat;
						break;
					}
				}
				$i++;
			}
		}

		if(empty($currentFormat)){
			if(JDEBUG){
				echo 'regAcyMailing plugin, could not find the right format to display the fields...';
			}
			return false;
		}

		$text = '';
		if(!empty($this->components[$option]['labelclass'])){
			$fieldsClass->labelClass = $this->components[$option]['labelclass'];
		}

		if($app->isAdmin()){
			$jversion = preg_replace('#[^0-9\.]#i', '', JVERSION);
			if(version_compare($jversion, '1.6.0', '>=')){
				$currentUserId = JRequest::getint('id', 0);
			}else{
				$currentUserIdArray = JRequest::getVar('cid', array());
				if(is_array($currentUserIdArray) && !empty($currentUserIdArray)){
					$currentUserId = array_shift($currentUserIdArray);
				}else $currentUserId = 0;
			}
		}else{
			$user = JFactory::getUser();
			$currentUserId = $user->id;
		}

		if(!empty($this->components[$option]['edittasks']) && in_array($this->view, $this->components[$option]['edittasks']) && $currentUserId != 0){
			$userClass = acymailing_get('class.subscriber');
			$acyUserData = $userClass->get($userClass->subid($currentUserId));
			if(!empty($acyUserData->email)) $fieldsClass->currentUser = $acyUserData;
		}

		foreach($newOrdering as $fieldName){
			if(!empty($allFormats[$currentFormat]['tagfield'])) $text .= '<'.$allFormats[$currentFormat]['tagfield'].' id="acy'.$fieldName.'" class="acyregfield">';
			if(!empty($allFormats[$currentFormat]['tagfieldname'])) $text .= '<'.$allFormats[$currentFormat]['tagfieldname'].' class="key acyregfieldname'.(!empty($this->components[$option]['tdfieldlabelclass']) ? ' '.$this->components[$option]['tdfieldlabelclass'] : '').'">';
			$text .= $fieldsClass->getFieldName($extraFields[$fieldName]);
			if(!empty($allFormats[$currentFormat]['tagfieldname'])) $text .= '</'.$allFormats[$currentFormat]['tagfieldname'].'>';
			if(!empty($allFormats[$currentFormat]['tagfieldvalue'])) $text .= '<'.$allFormats[$currentFormat]['tagfieldvalue'].' class="acyregfieldvalue'.(empty($this->components[$option]['fieldclass']) ? '' : ' '.$this->components[$option]['fieldclass']).'" >';
			$fieldValue = (!empty($acyUserData->$fieldName) ? $acyUserData->$fieldName : $extraFields[$fieldName]->default);
			$text .= $fieldsClass->display($extraFields[$fieldName], $fieldValue, 'regacy['.$fieldName.']');
			if(!empty($allFormats[$currentFormat]['tagfieldvalue'])) $text .= '</'.$allFormats[$currentFormat]['tagfieldvalue'].'>';
			if(!empty($allFormats[$currentFormat]['tagfield'])) $text .= '</'.$allFormats[$currentFormat]['tagfield'].'>';
		}

		$doc = JFactory::getDocument();
		if($app->isAdmin()){
			if(ACYMAILING_J25){
				$formid = 'user-form';
			}else $formid = 'adminForm';
		}elseif(empty($user->id)){
			if(ACYMAILING_J25){
				$formid = 'member-registration';
			}else $formid = 'josForm';
		}else{
			if(ACYMAILING_J25){
				$formid = 'member-profile';
			}else $formid = 'userform';
		}

		$js = $fieldsClass->prepareConditionalDisplay($extraFields, 'regacy', 'joomlaProfile', $formid);
		$js .= $this->_getAdditionalJs($extraFields);
		$body = str_replace('</head>', '<script type="text/javascript">'.$js.'</script></head>', $body);

		$body = preg_replace('#(name="'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</'.$currentFormat.'>)#Uis', '$1'.$text, $body, 1);
		JResponse::setBody($body);
		return;
	}

	private function _getAdditionalJs($fields){
		$js = '';
		foreach($fields as $oneField){
			if($oneField->type == 'date'){
				if(ACYMAILING_J30){
					$js .= 'jQuery(document).ready(function($) {Calendar.setup({';
				}else{
					$js .= 'window.addEvent(\'domready\', function() {Calendar.setup({';
				}
				if(empty($oneField->options['format'])) $oneField->options['format'] = "%Y-%m-%d";
				$js .= 'inputField: "field_'.$oneField->namekey.'",
						ifFormat: "'.$oneField->options['format'].'",
						button: "field_'.$oneField->namekey.'_img",
						align: "Tl",
						singleClick: true,
						firstDay: 0
					});});';
			}
		}
		return $js;
	}

	private function _addLists(){
		$app = JFactory::getApplication();
		$option = $this->option;

		$visibleLists = $this->params->get('lists', 'None');
		if($visibleLists == 'None') return;

		$visibleListsArray = array();
		$listsClass = acymailing_get('class.list');
		$allLists = $listsClass->getLists('listid');
		if(acymailing_level(1)){
			$allLists = $listsClass->onlyCurrentLanguage($allLists);
		}

		if(strpos($visibleLists, ',') OR is_numeric($visibleLists)){
			$allvisiblelists = explode(',', $visibleLists);
			foreach($allLists as $oneList){
				if($oneList->published AND in_array($oneList->listid, $allvisiblelists)) $visibleListsArray[] = $oneList->listid;
			}
		}elseif(strtolower($visibleLists) == 'all'){
			foreach($allLists as $oneList){
				if($oneList->published){
					$visibleListsArray[] = $oneList->listid;
				}
			}
		}

		if(empty($visibleListsArray)) return;

		$checkedLists = $this->params->get('listschecked', 'All');
		$userClass = acymailing_get('class.subscriber');

		if($app->isAdmin()){
			$jversion = preg_replace('#[^0-9\.]#i', '', JVERSION);
			if(version_compare($jversion, '1.6.0', '>=')){
				$currentUserId = JRequest::getint('id', 0);
			}else{
				$currentUserIdArray = JRequest::getVar('cid', array());
				if(is_array($currentUserIdArray) && !empty($currentUserIdArray)) $currentUserId = array_shift($currentUserIdArray);
			}
		}else{
			$loggedinUser = JFactory::getUser();
			if(!empty($loggedinUser->id)){
				$currentUserId = $loggedinUser->id;
			}
		}

		if(!empty($currentUserId)){
			$currentSubid = $userClass->subid($currentUserId);
			if(!empty($currentSubid)){
				$currentSubscription = $userClass->getSubscriptionStatus($currentSubid, $visibleListsArray);
				$checkedLists = '';
				foreach($currentSubscription as $listid => $oneSubsciption){
					if($oneSubsciption->status == '1' || $oneSubsciption->status == '2') $checkedLists .= $listid.',';
				}
			}
		}

		if(strtolower($checkedLists) == 'all'){
			$checkedListsArray = $visibleListsArray;
		}elseif(strpos($checkedLists, ',') OR is_numeric($checkedLists)){
			$checkedListsArray = explode(',', $checkedLists);
		}else{
			$checkedListsArray = array();
		}

		$subText = $this->params->get('subscribetext');
		if(empty($subText)){
			if(in_array($this->params->get('displaymode', 'dispall'), array('dispall', 'dropdown'))){
				$subText = JText::_('SUBSCRIPTION').':';
			}else{
				$subText = JText::_('YES_SUBSCRIBE_ME');
			}
		}else{
			$subText = JText::_($subText);
		}

		$body = JResponse::getBody();

		$severalValueTest = false;
		if($this->params->get('fieldafter', 'password') == 'custom'){
			$listAfter = explode(';', str_replace(array('\\[', '\\]'), array('[', ']'), $this->params->get('fieldaftercustom')));
			$after = !empty($listAfter) ? $listAfter : $this->components[$option]['password'];
		}elseif(!empty($this->components[$option][$this->params->get('fieldafter', 'password')])){
			$after = $this->components[$option][$this->params->get('fieldafter', 'password')];
		}else{
			$after = ($this->params->get('fieldafter', 'password') == 'email') ? 'email' : 'password2';
		}
		if(is_array($after)){
			$severalValueTest = true;
			$allAfters = $after;
			$after = $after[0];
		}

		$listsDisplayed = '<input type="hidden" value="'.implode(',', $visibleListsArray).'" name="acylistsdisplayed_'.$this->params->get('displaymode', 'dispall').'" />';
		$return = '';
		if($this->params->get('displaymode', 'dispall') == 'dispall'){
			$return = '<table class="acy_lists" style="border:0px">';
			foreach($visibleListsArray as $oneList){
				$check = in_array($oneList, $checkedListsArray) ? 'checked="checked"' : '';
				$return .= '<tr style="border:0px"><td style="border:0px"><input type="checkbox" id="acy_list_'.$oneList.'" class="acymailing_checkbox" name="acysub[]" '.$check.' value="'.$oneList.'"/></td><td style="border:0px;padding-left:10px;" nowrap="nowrap"><label for="acy_list_'.$oneList.'" class="acylabellist">';
				$return .= $allLists[$oneList]->name;
				$return .= '</label></td></tr>';
			}
			$return .= '</table>';
		}elseif($this->params->get('displaymode', 'dispall') == 'onecheck'){
			$check = '';
			foreach($visibleListsArray as $oneList){
				if(in_array($oneList, $checkedListsArray)){
					$check = 'checked="checked"';
					break;
				};
			}
			$return = '<span class="acysubscribe_span"><input type="checkbox" id="acysubhidden" name="acysubhidden" value="'.implode(',', $visibleListsArray).'" '.$check.' /><label for="acysubhidden">'.$subText.'</label>'.$listsDisplayed.'</span>';
		}elseif($this->params->get('displaymode', 'dispall') == 'dropdown'){
			$return = '<select name="acysub[1]">';
			foreach($visibleListsArray as $oneList){
				$return .= '<option value="'.$oneList.'">'.$allLists[$oneList]->name.'</option>';
			}
			$return .= '</select>';
		}

		$return .= '<input type="hidden" name="allVisibleLists" value="'.implode(',', $visibleListsArray).'" />';

		$resInsertLists = $this->addListsReplace($after, $body, $subText, $listsDisplayed, $return);
		if(!$resInsertLists && $severalValueTest){
			$i = 1;
			while(!$resInsertLists && $i < count($allAfters)){
				$resInsertLists = $this->addListsReplace($allAfters[$i], $body, $subText, $listsDisplayed, $return);
				$i++;
			}
		}
	}

	private function addListsReplace($after, $body, $subText, $listsDisplayed, $return){
		$option = $this->option;

		if(empty($this->components[$option]['lengthaftermin'])) $this->components[$option]['lengthaftermin'] = 0;
		if(empty($this->components[$option]['acysubscribestyle'])) $this->components[$option]['acysubscribestyle'] = '';
		if(preg_match('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</tr>)#Uis', $body)){
			$tdclassfield = '';
			$tdclassvalue = '';
			if(!empty($this->components[$option]['tdclassfield'])) $tdclassfield = 'class="'.$this->components[$option]['tdclassfield'].'"';
			if(!empty($this->components[$option]['tdclassvalue'])) $tdclassvalue = 'class="'.$this->components[$option]['tdclassvalue'].'"';

			if(in_array($this->params->get('displaymode', 'dispall'), array('dispall', 'dropdown'))){
				$return = '<tr class="acysubscribe"><td '.$tdclassfield.' style="padding-top:5px" valign="top">'.$subText.$listsDisplayed.'</td><td '.$tdclassvalue.'>'.$return.'</td></tr>';
			}else{
				$return = '<tr class="acysubscribe"><td colspan="2">'.$return.'</td></tr>';
			}
			$body = preg_replace('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</tr>)#Uis', '$1'.$return, $body, 1);
			JResponse::setBody($body);
			return true;
		}
		if(preg_match('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</li>)#Uis', $body)){
			if(in_array($this->params->get('displaymode', 'dispall'), array('dispall', 'dropdown'))){
				$return = '<li class="acysubscribe"><label class="labelacysubscribe'.(empty($this->components[$option]['labelclass']) ? '' : ' '.$this->components[$option]['labelclass'].'"').'">'.$subText.$listsDisplayed.'</label><div '.(empty($this->components[$option]['fieldclass']) ? '' : ' class="'.$this->components[$option]['fieldclass'].'"').' >'.$return.'</div></li>';
			}else{
				$return = '<li class="acysubscribe" '.$this->components[$option]['acysubscribestyle'].' >'.$return.'</li>';
			}
			$body = preg_replace('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</li>)#Uis', '$1'.$return, $body, 1);
			JResponse::setBody($body);
			return true;
		}
		if(preg_match('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</div>)#Uis', $body)){
			if(in_array($this->params->get('displaymode', 'dispall'), array('dispall', 'dropdown'))){
				$return = '<div class="acysubscribe"><label class="labelacysubscribe">'.$subText.$listsDisplayed.'</label>'.$return.'</div>';
			}else{
				$return = '<div class="acysubscribe" '.$this->components[$option]['acysubscribestyle'].' >'.$return.'</div>';
			}
			$body = preg_replace('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</div>)#Uis', '$1'.$return, $body, 1);
			JResponse::setBody($body);
			return true;
		}

		if(preg_match('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</p>)#Uis', $body)){
			if(in_array($this->params->get('displaymode', 'dispall'), array('dispall', 'dropdown'))){
				$return = '<div class="acysubscribe"><label class="labelacysubscribe">'.$subText.$listsDisplayed.'</label>'.$return.'</div>';
			}else{
				$return = '<div class="acysubscribe">'.$return.'</div>';
			}
			$body = preg_replace('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</p>)#Uis', '$1'.$return, $body, 1);
			JResponse::setBody($body);
			return true;
		}
		if(preg_match('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</dd>)#Uis', $body)){
			if(in_array($this->params->get('displaymode', 'dispall'), array('dispall', 'dropdown'))){
				$return = '<dt class="acysubscribe"><label class="labelacysubscribe">'.$subText.$listsDisplayed.'</label></dt><dd>'.$return.'</dd>';
			}else{
				$return = '<div class="acysubscribe">'.$return.'</div>';
			}
			$body = preg_replace('#(name *= *"'.preg_quote($after).'".{'.$this->components[$option]['lengthaftermin'].','.$this->components[$option]['lengthafter'].'}</dd>)#Uis', '$1'.$return, $body, 1);
			JResponse::setBody($body);
			return true;
		}
		return false;
	}

	private function _addCSS(){
		$style = $this->params->get('customcss');
		$jversion = preg_replace('#[^0-9\.]#i', '', JVERSION);

		if(empty($style) && version_compare($jversion, '1.6.0', '<')) return;

		if(empty($style)){
			$app = JFactory::getApplication();
			$stylestring = '<style type="text/css">'."\n";
			if(version_compare($jversion, '3.0.0', '>=')){
				$stylestring .= '.acyregfield label, .acysubscribe label {float:left; width:160px; '.(!$app->isAdmin() ? 'text-align:right;' : '').'}'."\n";
				$stylestring .= '.acyregfield span label, .acysubscribe .acy_lists label {width:auto;}'."\n";
				$stylestring .= '.acyregfield div:first-of-type, .acyregfield select:first-of-type, .acyregfield input, .acyregfield textarea, .acysubscribe input {margin-left:20px;}'."\n";
				$stylestring .= '.acyregfield, .acysubscribe {clear:both; padding-top:18px;}'."\n";
			}elseif(version_compare($jversion, '1.6.0', '>=') && $app->isAdmin()){
				$stylestring .= 'table.acy_lists{float:left;}'."\n";
			}
			$stylestring .= '</style>'."\n";
		}else{
			$stylestring = '<style type="text/css">'."\n".$style."\n".'</style>'."\n";
		}
		$body = JResponse::getBody();
		$body = preg_replace('#</head>#', $stylestring.'</head>', $body, 1);
		JResponse::setBody($body);
	}


	function onUserBeforeSave($user, $isnew, $new){
		return $this->onBeforeStoreUser($user, $isnew);
	}

	function plgVmOnAskQuestion($VendorEmail, $vars, $function){
		$user = JFactory::getUser();

		$db = JFactory::getDBO();
		$db->setQuery('SELECT id FROM #__users WHERE email = '.$db->Quote($vars['user'][email]));
		$id = $db->loadResult();
		if(empty($id)){
			$isnew = true;
			$user->id = 0;
		}else{
			$isnew = false;
			$user->id = $id;
		}
		$user->email = $vars['user'][email];
		$user->name = $vars['user'][name];
		$user->block = 0;

		$this->onAfterStoreUser($user, $isnew, true, '');
	}

	function onBeforeStoreUser($user, $isnew){

		if(is_object($user)) $user = get_object_vars($user);

		$this->oldUser = $user;

		return true;
	}

	function onAfterUserCreate(&$element){
		$app = JFactory::getApplication();
		$formData = JRequest::getVar('data', array(), '', 'array');

		if(empty($element->user_email) || empty($formData['address']) || !empty($element->user_cms_id) || $app->isAdmin()) return;

		JRequest::setVar('acy_source', 'hikashop');

		$name = @$formData['address']['address_firstname'].(!empty($formData['address']['address_middle_name']) ? ' '.$formData['address']['address_middle_name'] : '').(!empty($formData['address']['address_lastname']) ? ' '.$formData['address']['address_lastname'] : '');
		$user = array('id' => 0, 'block' => 0, 'email' => $element->user_email, 'name' => $name);
		$this->onAfterStoreUser($user, true, true, '');
	}

	function onUserAfterSave($user, $isnew, $success, $msg){
		return $this->onAfterStoreUser($user, $isnew, $success, $msg);
	}

	function onAfterStoreUser($user, $isnew, $success, $msg){

		if(is_object($user)) $user = get_object_vars($user);

		if($success === false OR empty($user['email'])) return true;

		$helperFile = rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
		if(!file_exists($helperFile) || !include_once($helperFile)) return true;

		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('system', 'regacymailing');
			$this->params = new JParameter($plugin->params);
		}

		if(!JRequest::getCmd('acy_source')) JRequest::setVar('acy_source', 'joomla');

		$config = acymailing_config();
		$app = JFactory::getApplication();

		$userClass = acymailing_get('class.subscriber');
		$joomUser = new stdClass();
		$joomUser->email = trim(strip_tags($user['email']));
		if(!empty($user['name'])) $joomUser->name = trim(strip_tags($user['name']));
		if(empty($user['block']) && !$this->params->get('forceconf', 0)) $joomUser->confirmed = 1;
		$joomUser->enabled = 1 - (int)$user['block'];
		$joomUser->userid = $user['id'];

		$userHelper = acymailing_get('helper.user');
		if(!$userHelper->validEmail($joomUser->email)) return true;

		if(!$app->isAdmin()) $userClass->geolocRight = true;

		if(!$isnew AND !empty($this->oldUser['email']) AND $user['email'] != $this->oldUser['email']){
			$joomUser->subid = $userClass->subid($this->oldUser['email']);
		}
		if(empty($joomUser->subid)){
			if(empty($joomUser->userid)){
				$joomUser->subid = null;
			}else{
				$joomUser->subid = $userClass->subid($joomUser->userid);
			}
		}

		if(!empty($joomUser->subid)){
			$currentSubid = $userClass->subid($joomUser->email);
			if(!empty($currentSubid) && $joomUser->subid != $currentSubid){
				$userClass->delete($currentSubid);
			}
		}

		$userClass->checkVisitor = false;
		$userClass->sendConf = false;

		$isnew = (bool)($isnew || empty($joomUser->subid));

		$customValues = JRequest::getVar('regacy', array(), '', 'array');
		$session = JFactory::getSession();
		if(empty($customValues) && $session->get('regacy')){
			$customValues = $session->get('regacy');
			$session->set('regacy', null);
		}
		if(!empty($customValues)){
			$userClass->checkFields($customValues, $joomUser);
		}

		$userClass->triggerFilterBE = true;
		$subid = $userClass->save($joomUser);

		$listsToSubscribe = ($isnew) ? $config->get('autosub', 'None') : 'None';
		$currentSubscription = $userClass->getSubscriptionStatus($subid);

		$listsClass = acymailing_get('class.list');
		$allLists = $listsClass->getLists('listid');
		if(acymailing_level(1)){
			$allLists = $listsClass->onlyCurrentLanguage($allLists);
		}

		$session = JFactory::getSession();
		$visiblelistschecked = JRequest::getVar('acysub', array(), '', 'array');
		if(empty($visiblelistschecked) && $session->get('acysub')){
			$visiblelistschecked = $session->get('acysub');
			$session->set('acysub', null);
		}

		$acySubHidden = JRequest::getString('acysubhidden');
		if(empty($acySubHidden) && $session->get('acysubhidden')){
			$acySubHidden = $session->get('acysubhidden');
			$session->set('acysubhidden', null);
		}

		if(!empty($acySubHidden)){
			$visiblelistschecked = array_merge($visiblelistschecked, explode(',', $acySubHidden));
		}

		$allvisiblelists = JRequest::getString('allVisibleLists');
		$allvisiblelistsArray = explode(',', $allvisiblelists);

		$listsArray = array();
		if(strpos($listsToSubscribe, ',') || is_numeric($listsToSubscribe)){
			$listsArrayParam = explode(',', $listsToSubscribe);
			foreach($allLists as $oneList){
				$okSub = false;
				if(in_array($oneList->listid, $listsArrayParam) && (!in_array($oneList->listid, $allvisiblelistsArray) || in_array($oneList->listid, $visiblelistschecked))) $okSub = true;
				if($oneList->published && (in_array($oneList->listid, $visiblelistschecked) || $okSub)){
					$listsArray[] = $oneList->listid;
				}
			}
		}elseif(strtolower($listsToSubscribe) == 'all'){
			foreach($allLists as $oneList){
				$okSub = false;
				if(!in_array($oneList->listid, $allvisiblelistsArray) || in_array($oneList->listid, $visiblelistschecked)) $okSub = true;
				if($oneList->published && $okSub){
					$listsArray[] = $oneList->listid;
				}
			}
		}elseif(!empty($visiblelistschecked)){
			foreach($allLists as $oneList){
				if($oneList->published && in_array($oneList->listid, $visiblelistschecked)){
					$listsArray[] = $oneList->listid;
				}
			}
		}
		$statusAdd = (empty($joomUser->enabled) || (empty($joomUser->confirmed) && $config->get('require_confirmation', false))) ? 2 : 1;
		$addlists = array();
		if(!empty($listsArray)){
			foreach($listsArray as $idOneList){
				if(!isset($currentSubscription[$idOneList]) || $currentSubscription[$idOneList]->status == -1){
					$addlists[$statusAdd][$idOneList] = $idOneList;
				}
			}
		}

		$listsubClass = acymailing_get('class.listsub');
		$userSubscriptions = $listsubClass->getSubscription($subid);

		if(!$isnew && !empty($allvisiblelistsArray)){
			$subscribedLists = array_keys($userSubscriptions);
			$unsubscribeLists = array_intersect($subscribedLists, array_diff($allvisiblelistsArray, $visiblelistschecked));
			if(!empty($unsubscribeLists)) $listsubClass->updateSubscription($subid, array(-1 => $unsubscribeLists));
		}

		if(!empty($addlists)){
			if(!empty($user['gid'])) $listsubClass->gid = $user['gid'];
			if(!empty($user['groups'])) $listsubClass->gid = $user['groups'];
			$listsToUpdate = array_intersect(array_keys($userSubscriptions), $addlists[$statusAdd]);
			$updateLists = array();

			if(!empty($listsToUpdate)){
				foreach($listsToUpdate as $key => $oneListToUpdate){
					if($userSubscriptions[$oneListToUpdate]->status == -1 && !in_array($oneListToUpdate, $allvisiblelistsArray)) continue;
					$updateLists[] = $oneListToUpdate;
				}

				if(!empty($updateLists)) $listsubClass->updateSubscription($subid, array($statusAdd => $updateLists));
				$addlists[$statusAdd] = array_diff($addlists[$statusAdd], $listsToUpdate);
			}

			if(!empty($addlists[$statusAdd])) $listsubClass->addSubscription($subid, $addlists);
		}

		if($isnew && $this->params->get('sendnotif', false)){
			$userClass->sendNotification();
		}

		$listssub = $listsubClass->getSubscription($subid);

		if($isnew && $this->params->get('forceconf', 0) && empty($user['block'])){
			$userClass->sendConf($subid);
			return true;
		}

		if($isnew || empty($this->oldUser['block']) || !empty($user['block'])) return true;

		if($this->params->get('forceconf', 0)){
			if(!empty($listssub)) $userClass->sendConf($subid);
		}else{
			$userClass->confirmSubscription($subid);
		}

		return true;
	}

	function onUserAfterDelete($user, $success, $msg){
		return $this->onAfterDeleteUser($user, $success, $msg);
	}

	function onAfterDeleteUser($user, $success, $msg){
		if(is_object($user)) $user = get_object_vars($user);

		if($success === false || empty($user['email'])) return true;

		$helperFile = rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
		if(!file_exists($helperFile) || !include_once($helperFile)) return true;

		$userClass = acymailing_get('class.subscriber');
		$subid = $userClass->subid($user['email']);
		if(!empty($subid)){
			if($this->params->get('deletebehavior', '0') == 0){
				$userClass->delete($subid);
			}else{
				$db = JFactory::getDBO();
				$db->setQuery('UPDATE #__acymailing_subscriber SET `userid` = 0 WHERE subid = '.intval($subid));
				$db->query();
			}
		}

		return true;
	}

	function onExtregUserActivate($form_id = 0, $er_user = null){
		if(empty($er_user->id)) return true;
		include_once(rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
		$userClass = acymailing_get('class.subscriber');
		$userSubid = $userClass->subid($er_user->id);
		if(empty($userSubid)) return true;

		if(!empty($er_user->approve)){
			$db = JFactory::getDBO();
			$query = 'UPDATE  #__acymailing_subscriber SET `enabled` = '.(int)$er_user->approve.' WHERE subid ='.intval($userSubid);
			$db->setQuery($query);
			$db->query();
		}
		$userClass->confirmSubscription($userSubid);
		return true;
	}

	function onExtregUserApprove($form_id = 0, $er_user = null){
		if(empty($er_user->id)) return true;
		include_once(rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
		$userClass = acymailing_get('class.subscriber');
		$userSubid = $userClass->subid($er_user->id);
		if(empty($userSubid)) return true;

		$db = JFactory::getDBO();
		$query = 'UPDATE  #__acymailing_subscriber SET `enabled` = "1" WHERE subid ='.intval($userSubid);
		$db->setQuery($query);
		$db->query();

		return true;
	}
}//endclass
