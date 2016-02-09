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

class acyupdateHelper{

	var $db;
	var $errors = array();
	var $bouncerulesversion = 13;

	function acyupdateHelper(){
		$this->db = JFactory::getDBO();
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
	}

	function fixDoubleExtension(){

		if(!ACYMAILING_J16) return;

		$this->db->setQuery("SELECT extension_id FROM #__extensions WHERE type='component' AND element = 'com_acymailing' AND extension_id > 0 ORDER BY client_id ASC, extension_id ASC");
		$results = $this->db->loadObjectList();
		if(empty($results) || count($results) == 1) return;

		$validExtension = reset($results)->extension_id;

		$toDelete = array();
		for($i = 1; $i < count($results); $i++){
			$toDelete[] = $results[$i]->extension_id;
		}


		$tablesToUpdate = array('#__menu' => 'component_id');
		foreach($tablesToUpdate as $table => $field){
			$this->db->setQuery("UPDATE ".$table." SET ".$field." = ".intval($validExtension)." WHERE ".$field." IN (".implode(',', $toDelete).")");
			$this->db->query();
		}
		$tablesToCheck = array('#__updates' => 'extension_id', '#__update_sites_extensions' => 'extension_id', '#__extensions' => 'extension_id');
		foreach($tablesToCheck as $table => $field){
			$this->db->setQuery("DELETE FROM ".$table." WHERE ".$field." IN (".implode(',', $toDelete).")");
			$this->db->query();
		}
	}

	function fixMenu(){

		if(!ACYMAILING_J16) return;

		$this->db->setQuery("SELECT extension_id FROM #__extensions WHERE type='component' AND element LIKE '%acymailing' LIMIT 1");
		$extensionid = $this->db->loadResult();
		if(empty($extensionid)) return;

		$this->db->setQuery("UPDATE #__menu SET component_id = ".intval($extensionid).",published = 1 WHERE link LIKE '%com_acymailing%' AND component_id = 0 AND client_id = 1");
		$this->db->query();
	}

	function installTables(){
		$db = JFactory::getDBO();
		echo '<h2 style="color:red">The installation failed, some tables are missing, we will try to create them now...</h2>';

		$queries = file_get_contents(ACYMAILING_BACK.'tables.sql');
		$queriesTable = explode("CREATE TABLE", $queries);

		$success = true;
		foreach($queriesTable as $oneQuery){
			$oneQuery = trim($oneQuery);
			if(empty($oneQuery)) continue;
			$db->setQuery("CREATE TABLE ".$oneQuery);
			if(!$db->query()){
				echo '<br /><br /><span style="color:red">Error creating table : '.$db->getErrorMsg().'</span><br />';
				$success = false;
			}else{
				echo '<br /><span style="color:green">Table successfully created</span>';
			}
		}

		if($success){
			echo '<h2 style="color:orange">Please install again AcyMailing via the Joomla Extensions manager, the tables are now created so the installation will work</h2>';
		}else{
			echo '<h2 style="color:red">Some tables could not be created, please fix the above issues and then install again AcyMailing.</h2>';
		}
	}

	function addUpdateSite(){
		$config = acymailing_config();

		$newconfig = new stdClass();
		$newconfig->website = ACYMAILING_LIVE;
		$newconfig->max_execution_time = 0;

		$config->save($newconfig);

		if(!ACYMAILING_J16) return false;

		$this->db->setQuery("DELETE FROM #__updates WHERE element = 'com_acymailing'");
		$this->db->query();

		$query = "SELECT update_site_id FROM #__update_sites WHERE location LIKE '%acymailing%' AND type LIKE 'extension'";
		$this->db->setQuery($query);
		$update_site_id = $this->db->loadResult();

		$object = new stdClass();
		$object->name = 'AcyMailing';
		$object->type = 'extension';
		$object->location = 'http://www.acyba.com/component/updateme/updatexml/component-acymailing/level-'.$config->get('level').'/file-extension.xml';

		$object->enabled = 1;

		if(empty($update_site_id)){
			$this->db->insertObject("#__update_sites", $object);
			$update_site_id = $this->db->insertid();
		}else{
			$object->update_site_id = $update_site_id;
			$this->db->updateObject("#__update_sites", $object, 'update_site_id');
		}

		$query = "SELECT extension_id FROM #__extensions WHERE `name` LIKE 'acymailing' AND type LIKE 'component'";
		$this->db->setQuery($query);
		$extension_id = $this->db->loadResult();
		if(empty($update_site_id) OR empty($extension_id)) return false;

		$query = 'INSERT IGNORE INTO #__update_sites_extensions (update_site_id, extension_id) values ('.$update_site_id.','.$extension_id.')';
		$this->db->setQuery($query);
		$this->db->query();
		return true;
	}

	function installNotifications(){
		$this->db->setQuery('SELECT `alias` FROM `#__acymailing_mail` WHERE `type` = \'notification\'');

		$notifications = acymailing_loadResultArray($this->db);

		$data = array();

		if(!in_array('notification_created', $notifications)){
			$data[] = "('New Subscriber on your website : {user:email}', '<p>Hello {subtag:name},</p><p>A new user has been created in AcyMailing : </p><blockquote><p>Name : {user:name}</p><p>Email : {user:email}</p><p>IP : {user:ip} </p><p>Subscription : {user:subscription}</p></blockquote>', '', 1, 'notification', 0,'notification_created', 1,0,NULL)";
		}

		if(!in_array('notification_unsuball', $notifications)){
			$data[] = "('A User unsubscribed from all your lists : {user:email}', '<p>Hello {subtag:name},</p><p>The user {user:name} : {user:email} unsubscribed from all your lists</p><p>Subscription : {user:subscription}</p><p>{survey}</p>', '', 1, 'notification', 0, 'notification_unsuball', 1,0,NULL)";
		}

		if(!in_array('notification_unsub', $notifications)){
			$data[] = "('A User unsubscribed : {user:email}', '<p>Hello {subtag:name},</p><p>The user {user:name} : {user:email} unsubscribed from your list</p><p>Subscription : {user:subscription}</p><p>{survey}</p>', '', 1, 'notification', 0, 'notification_unsub', 1,0,NULL)";
		}

		if(!in_array('notification_refuse', $notifications)){
			$data[] = "('A User refuses to receive e-mails from your website : {user:email}', '<p>The User {user:name} : {user:email} refuses to receive any e-mail anymore from your website.</p><p>Subscription : {user:subscription}</p><p>{survey}</p>', '', 1, 'notification',0,'notification_refuse', 1,0,NULL)";
		}

		if(!in_array('notification_contact', $notifications)){
			$data[] = "('New contact from your website : {user:email}', '<p>Hello {subtag:name},</p><p>A user submitted the form : </p><blockquote><p>Name : {user:name}</p><p>Email : {user:email}</p><p>IP : {user:ip} </p><p>Subscription : {user:subscription}</p></blockquote>', '', 1, 'notification', 0,'notification_contact', 1,0,NULL)";
		}

		if(!in_array('notification_contact_menu', $notifications)){
			$data[] = "('A user subscribed or modified his subscription : {user:email}', '<p>Hello {subtag:name},</p><p>A user submitted the form : </p><blockquote><p>Name : {user:name}</p><p>Email : {user:email}</p><p>IP : {user:ip} </p><p>Subscription : {user:subscription}</p></blockquote>', '', 1, 'notification', 0,'notification_contact_menu', 1,0,NULL)";
		}

		if(!in_array('notification_confirm', $notifications)){
			$data[] = "('A user confirmed his subscription : {user:email}', '<p>Hello {subtag:name},</p><p>A user confirmed his subscription : </p><blockquote><p>Name : {user:name}</p><p>Email : {user:email}</p><p>IP : {user:ip} </p><p>Subscription : {user:subscription}</p></blockquote>', '', 1, 'notification', 0,'notification_confirm', 1,0,NULL)";
		}

		if(!in_array('confirmation', $notifications)){
			$confcontent = $this->getJoomlaNotification('{subtag:name|ucfirst}, {trans:PLEASE_CONFIRM_SUB}', '<h1>Hello {subtag:name|ucfirst},</h1>
			<p>{trans:CONFIRM_MSG}<br /><br />{trans:CONFIRM_MSG_ACTIVATE}</p>
			<br />
			<p style="text-align:center;"><strong>{confirm}{trans:CONFIRM_SUBSCRIPTION}{/confirm}</strong></p>');
			$this->db->setQuery("SELECT tempid FROM #__acymailing_template WHERE namekey = 'newsletter-4' LIMIT 1");
			$conftemplate = (int)$this->db->loadResult();
			$data[] = "('{subtag:name|ucfirst}, {trans:PLEASE_CONFIRM_SUB}', '".$confcontent."', '',1, 'notification', 0, 'confirmation', 1,".$conftemplate.',\'a:3:{s:6:"action";s:7:"confirm";s:13:"actionbtntext";s:28:"{trans:CONFIRM_SUBSCRIPTION}";s:9:"actionurl";s:19:"{confirm}{/confirm}";}\')';
		}else{
			$this->db->setQuery('SELECT `params` FROM `#__acymailing_mail` WHERE `alias` = \'confirmation\'');
			$confirmParams = $this->db->loadResult();
			if(empty($confirmParams)){
				$this->db->setQuery('UPDATE `#__acymailing_mail` SET `params` = \'a:3:{s:6:"action";s:7:"confirm";s:13:"actionbtntext";s:28:"{trans:CONFIRM_SUBSCRIPTION}";s:9:"actionurl";s:19:"{confirm}{/confirm}";}\' WHERE `alias` = \'confirmation\'');
				$this->db->query();
			}
		}

		if(!in_array('report', $notifications)){
			$data[] = "('AcyMailing Cron Report {mainreport}', '<p>{report}</p><p>{detailreport}</p>', '',1, 'notification',0,  'report', 1,0,NULL)";
		}

		if(!in_array('modif', $notifications)){
			$data[] = "('Modify your subscription', '<p>Hello {subtag:name}, </p><p>You requested some changes on your subscription,</p><p>Please {modify}click here{/modify} to be identified as the owner of this account and then modify your subscription.</p>', '',1, 'notification', 0, 'modif', 1,0,NULL)";
		}

		if(acymailing_level(1)){
			$this->db->setQuery('SELECT LCASE(`alias`) FROM `#__acymailing_mail` WHERE `type` = \'joomlanotification\'');
			$JNotifications = acymailing_loadResultArray($this->db);
			$this->db->setQuery("SELECT tempid FROM #__acymailing_template WHERE namekey = 'newsletter-4' LIMIT 1");
			$conftemplate = (int)$this->db->loadResult();
			if(ACYMAILING_J30){
				if(!in_array(strtolower('joomla-directRegNoPwd-j3'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_BODY_NOPW|param1|param2|param3}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-directRegNoPwd-j3', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-directReg-j3'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_BODY|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-directReg-j3', 1, ".$conftemplate.",NULL)";
				}
			}elseif(ACYMAILING_J16){
				if(!in_array(strtolower('joomla-directReg'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_BODY|param1|param2|param3}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-directReg', 1, ".$conftemplate.",NULL)";
				}
			}
			if(ACYMAILING_J16){
				if(!in_array(strtolower('joomla-ownActivReg'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY|param1|param2|param3|param4|param5|param6}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-ownActivReg', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-ownActivRegNoPwd'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-ownActivRegNoPwd', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-adminActivReg'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY|param1|param2|param3|param4|param5|param6}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-adminActivReg', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-adminActivRegNoPwd'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-adminActivRegNoPwd', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-usernameReminder'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_USERNAME_REMINDER_SUBJECT|param1}', '{trans:COM_USERS_EMAIL_USERNAME_REMINDER_BODY|param1|param2|param3}');
					$data[] = "('{trans:COM_USERS_EMAIL_USERNAME_REMINDER_SUBJECT|param1}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-usernameReminder', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-confirmActiv'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT|param1|param2}', '{trans:COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY|param1|param2|param3}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-confirmActiv', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-resetPwd'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_PASSWORD_RESET_SUBJECT|param1}', '{trans:COM_USERS_EMAIL_PASSWORD_RESET_BODY|param1|param2|param3}');
					$data[] = "('{trans:COM_USERS_EMAIL_PASSWORD_RESET_SUBJECT|param1}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-resetPwd', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-regByAdmin'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:PLG_USER_JOOMLA_NEW_USER_EMAIL_SUBJECT}', '{trans:PLG_USER_JOOMLA_NEW_USER_EMAIL_BODY|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:PLG_USER_JOOMLA_NEW_USER_EMAIL_SUBJECT}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-regByAdmin', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-regNotifAdmin'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '{trans:COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY|param1|param2|param3}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACCOUNT_DETAILS|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-regNotifAdmin', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-regNotifAdminActiv'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT|param2|param1}', '{trans:COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT|param2|param1}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-regNotifAdminActiv', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-directreg'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR_WELCOME|param2}', '{trans:COM_COMMUNITY_EMAIL_REGISTRATION_ACCOUNT_DETAILS|param1|param2|param3|param4}');
					$data[] = "('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR_WELCOME|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-directreg', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-ownactivreg'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param1|param2}', '{trans:COM_COMMUNITY_EMAIL_REGISTRATION_COMPLETED_REQUIRES_ACTIVATION|param1|param2|param3|param5}');
					$data[] = "('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-ownactivreg', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-welcomeactiv'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR_WELCOME|param2}', '{trans:COM_COMMUNITY_EMAIL_REGISTRATION_ACCOUNT_DETAILS_REQUIRES_ACTIVATION|param1|param2|param3|param4}');
					$data[] = "('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR_WELCOME|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-welcomeactiv', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-regactivadmin'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param1|param2}', '{trans:COM_COMMUNITY_EMAIL_REGISTRATION_COMPLETED_REQUIRES_ADMIN_ACTIVATION|param1|param2|param3|param5}');
					$data[] = "('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-regactivadmin', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-notifadmin'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param3|param2}', '{trans:COM_COMMUNITY_SEND_MSG_ADMIN|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param3|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-notifadmin', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-notifadminactiv'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param3|param2}', '{trans:COM_COMMUNITY_USER_REGISTERED_NEEDS_APPROVAL|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:COM_COMMUNITY_ACCOUNT_DETAILS_FOR|param3|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-notifadminactiv', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-notifactivated'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT|param1|param2}', '{trans:COM_COMMUNITY_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY|param1|param2|param3}');
					$data[] = "('{trans:COM_COMMUNITY_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-notifactivated', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('jomsocial-notifaccountparameters'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:COM_COMMUNITY_USER_REGISTERED_WAITING_APPROVAL_TITLE|param2}', '{trans:COM_COMMUNITY_EMAIL_REGISTRATION|param1|param2|param3|param4}');
					$data[] = "('{trans:COM_COMMUNITY_USER_REGISTERED_WAITING_APPROVAL_TITLE|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'jomsocial-notifaccountparameters', 1, ".$conftemplate.",NULL)";
				}
			}else{
				if(!in_array(strtolower('joomla-directReg'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:ACCOUNT DETAILS FOR|param1|param2}', '{trans:SEND_MSG|param1|param2|param3}');
					$data[] = "('{trans:ACCOUNT DETAILS FOR|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-directReg', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-ownActivReg'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:ACCOUNT DETAILS FOR|param1|param2}', '{trans:SEND_MSG_ACTIVATE|param1|param2|param3|param4|param5|param6}');
					$data[] = "('{trans:ACCOUNT DETAILS FOR|param1|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-ownActivReg', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-usernameReminder'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:USERNAME_REMINDER_EMAIL_TITLE|param1}', '{trans:USERNAME_REMINDER_EMAIL_TEXT|param1|param2|param3}');
					$data[] = "('{trans:USERNAME_REMINDER_EMAIL_TITLE|param1}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-usernameReminder', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-resetPwd'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE|param1}', '{trans:PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT|param1|param2|param3}');
					$data[] = "('{trans:PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE|param1}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-resetPwd', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-regByAdmin'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:NEW_USER_MESSAGE_SUBJECT}', '{trans:NEW_USER_MESSAGE|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:NEW_USER_MESSAGE_SUBJECT}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-regByAdmin', 1, ".$conftemplate.",NULL)";
				}
				if(!in_array(strtolower('joomla-regNotifAdmin'), $JNotifications)){
					$bodyNotif = $this->getJoomlaNotification('{trans:ACCOUNT DETAILS FOR|param3|param2}', '{trans:SEND_MSG_ADMIN|param1|param2|param3|param4|param5}');
					$data[] = "('{trans:ACCOUNT DETAILS FOR|param3|param2}', '".$bodyNotif."', '', 0, 'joomlanotification', 0, 'joomla-regNotifAdmin', 1, ".$conftemplate.",NULL)";
				}
			}
		}

		if(!empty($data)){
			$this->db->setQuery("INSERT INTO `#__acymailing_mail` (`subject`, `body`, `altbody`, `published`, `type`, `visible`, `alias`, `html`, `tempid`, `params`) VALUES ".implode(',', $data));
			$this->db->query();
		}

		$query = "INSERT IGNORE INTO `#__acymailing_fields` (`fieldname`, `namekey`, `type`, `value`, `published`, `ordering`, `options`, `core`, `required`, `backend`, `frontcomp`, `default`, `listing`, `frontlisting`, `frontform`) VALUES
		('NAMECAPTION', 'name', 'text', '', 1, 1, '', 1, 1, 1, 1, '',1,1,1),
		('EMAILCAPTION', 'email', 'text', '', 1, 2, '', 1, 1, 1, 1, '',1,1,1),
		('RECEIVE', 'html', 'radio', '0::JOOMEXT_TEXT\n1::HTML', 1, 3, '', 1, 1, 1, 1, '1',1,0,1);";
		$this->db->setQuery($query);
		$this->db->query();
	}

	function getJoomlaNotification($subject, $body){
		return '<div style=\"text-align: center; width: 100%; background-color:#ffffff;\">
		<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"w600\" style=\"text-align: justify; margin: auto; width: 600px;\">
			<tbody>
				<tr class=\"acyeditor_delete\" style=\"line-height: 0px;\" id=\"zone_2\">
					<td class=\"w600\" colspan=\"5\" style=\"background-color: #69b4c0;\" valign=\"bottom\" width=\"600\" id=\"zone_3\"><img id=\"zone_29\" alt=\" - - - \" border=\"0\" src=\"media/com_acymailing/templates/newsletter-4/images/top.png\"></td>
				</tr>
				<tr class=\"acyeditor_delete\" id=\"zone_4\">
					<td class=\"w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_5\"></td>
					<td class=\"w520 acyeditor_text\" colspan=\"3\" height=\"80\" style=\"text-align: left; background-color: #ebebeb;\" width=\"520\" id=\"zone_6\"><strong>​</strong>​​​​​​​​<img alt=\"-\" border=\"0\" src=\"media/com_acymailing/templates/newsletter-4/images/message_icon.png\" style=\"float: left; margin-right: 10px;\">
		<h3>'.$subject.'<span style=\"display: none;\">&nbsp;</span></h3>
		</td>
					<td class=\"acyeditor_picture w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_7\"></td>
				</tr>
				<tr class=\"acyeditor_delete\" id=\"zone_8\">
					<td class=\"w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_9\"></td>
					<td class=\"w20\" style=\"background-color: #fff;\" width=\"20\" id=\"zone_10\"></td>
					<td class=\"w480\" height=\"20\" style=\"background-color: #fff;\" width=\"480\" id=\"zone_11\"></td>
					<td class=\"w20\" style=\"background-color: #fff;\" width=\"20\" id=\"zone_12\"></td>
					<td class=\"w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_13\"></td>
				</tr>
				<tr class=\"acyeditor_delete\" id=\"zone_14\">
					<td class=\"w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_15\"></td>
					<td class=\"w20\" style=\"background-color: #fff;\" width=\"20\" id=\"zone_16\"></td>
					<td class=\"w480 pict acyeditor_text\" style=\"background-color: #fff; text-align: left;\" width=\"480\" id=\"zone_17\">'.$body.'</td>
					<td class=\"w20\" style=\"background-color: #fff;\" width=\"20\" id=\"zone_18\"></td>
					<td class=\"w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_19\"></td>
				</tr>
				<tr class=\"acyeditor_delete\" id=\"zone_20\">
					<td class=\"w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_21\"></td>
					<td class=\"w20\" style=\"background-color: #fff;\" width=\"20\" id=\"zone_22\"></td>
					<td class=\"w480\" height=\"20\" style=\"background-color: #fff;\" width=\"480\" id=\"zone_23\"></td>
					<td class=\"w20\" style=\"background-color: #fff;\" width=\"20\" id=\"zone_24\"></td>
					<td class=\"w40\" style=\"background-color: #ebebeb;\" width=\"40\" id=\"zone_25\"></td>
				</tr>
				<tr class=\"acyeditor_delete\" style=\"line-height: 0px;\" id=\"zone_26\">
					<td class=\"w600\" colspan=\"5\" style=\"background-color: #ebebeb;\" width=\"600\" id=\"zone_27\"><img id=\"zone_31\" alt=\" - - - \" border=\"0\" src=\"media/com_acymailing/templates/newsletter-4/images/bottom.png\"></td>
				</tr>
			</tbody>
		</table>
		</div>';
	}

	function installMenu($code = ''){
		if(empty($code)){
			$lang = JFactory::getLanguage();
			$code = $lang->getTag();
		}
		$path = JLanguage::getLanguagePath(JPATH_ROOT).DS.$code.DS.$code.'.com_acymailing.ini';
		if(!file_exists($path)) return;
		$content = file_get_contents($path);
		if(empty($content)) return;

		$menuFileContent = 'COM_ACYMAILING="AcyMailing"'."\r\n";
		$menuFileContent .= 'ACYMAILING="AcyMailing"'."\r\n";
		$menuFileContent .= 'COM_ACYMAILING_CONFIGURATION="AcyMailing"'."\r\n";
		$menuStrings = array('USERS', 'LISTS', 'TEMPLATES', 'NEWSLETTERS', 'AUTONEWSLETTERS', 'CAMPAIGN', 'QUEUE', 'STATISTICS', 'CONFIGURATION', 'UPDATE_ABOUT', 'COM_ACYMAILING_ARCHIVE_VIEW_DEFAULT_TITLE', 'COM_ACYMAILING_FRONTSUBSCRIBER_VIEW_DEFAULT_TITLE', 'COM_ACYMAILING_LISTS_VIEW_DEFAULT_TITLE', 'COM_ACYMAILING_FRONTNEWSLETTER_VIEW_DEFAULT_TITLE', 'COM_ACYMAILING_USER_VIEW_DEFAULT_TITLE');
		foreach($menuStrings as $oneString){
			preg_match('#(\n|\r)(ACY_)?'.$oneString.'="(.*)"#i', $content, $matches);
			if(empty($matches[3])) continue;
			if(!ACYMAILING_J16){
				$menuFileContent .= 'COM_ACYMAILING.'.$oneString.'="'.$matches[3].'"'."\r\n";
			}else{
				$menuFileContent .= $oneString.'="'.$matches[3].'"'."\r\n";
			}
		}

		if(!ACYMAILING_J16){
			$menuPath = ACYMAILING_ROOT.'administrator'.DS.'language'.DS.$code.DS.$code.'.com_acymailing.menu.ini';
		}else{
			$menuPath = ACYMAILING_ROOT.'administrator'.DS.'language'.DS.$code.DS.$code.'.com_acymailing.sys.ini';
		}
		if(!JFile::write($menuPath, $menuFileContent)){
			acymailing_enqueueMessage(JText::sprintf('FAIL_SAVE', $menuPath), 'error');
		}
	}

	function installTemplates(){
		$path = ACYMAILING_TEMPLATE;
		$dirs = JFolder::folders($path);

		$template = array();
		$order = 0;
		foreach($dirs as $oneTemplateDir){
			$order++;
			$description = '';
			$name = '';
			$body = '';
			$altbody = '';
			$readmore = '';
			$thumb = '';
			$premium = 0;
			$ordering = $order;
			$styles = array();
			$stylesheet = '';
			if(!@include($path.DS.$oneTemplateDir.DS.'install.php')) continue;
			$body = str_replace(array('src="./', 'src="../', 'src="images/'), array('src="media/com_acymailing/templates/'.$oneTemplateDir.'/', 'src="media/com_acymailing/templates/', 'src="media/com_acymailing/templates/'.$oneTemplateDir.'/images/'), $body);

			$template[] = $this->db->Quote($oneTemplateDir).','.$this->db->Quote($name).','.$this->db->Quote($description).','.$this->db->Quote($body).','.$this->db->Quote($altbody).','.$this->db->Quote($premium).','.$this->db->Quote($ordering).','.$this->db->Quote(serialize($styles)).','.$this->db->Quote($stylesheet).','.$this->db->Quote($thumb).','.$this->db->Quote($readmore);
		}

		if(empty($template)) return true;

		try{
			$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_template` (`namekey`, `name`, `description`, `body`, `altbody`, `premium`, `ordering`, `styles`,`stylesheet`,`thumb`,`readmore`) VALUES (".implode('),(', $template).')');
			$this->db->query();

			$lastId = $this->db->insertid();

			$nbTemplates = $this->db->getAffectedRows();
		}catch(Exception $e){

			acymailing_enqueueMessage(substr(strip_tags($e->getMessage()), 0, 300).'...', 'error');
			$nbTemplates = 0;
		}

		if(!empty($nbTemplates)){
			acymailing_enqueueMessage(JText::sprintf('TEMPLATES_INSTALL', $nbTemplates), 'success');

			$templateClass = acymailing_get('class.template');
			for($i = $lastId; $i <= $lastId + count($template); $i++){
				$templateClass->createTemplateFile($i);
			}
		}
	}

	function initList(){

		$query = 'UPDATE IGNORE '.acymailing_table('users', false).' as b, '.acymailing_table('subscriber').' as a SET a.email = b.email, a.name = b.name WHERE a.userid = b.id AND a.userid > 0';
		$this->db->setQuery($query);
		$this->db->query();

		$time = time();
		$query = 'INSERT IGNORE INTO `#__acymailing_subscriber` (`email`,`name`,`confirmed`,`userid`,`created`,`enabled`,`accept`,`html`) SELECT `email`,`name`,1-`block`,`id`,UNIX_TIMESTAMP(`registerDate`),1-`block`,1,1 FROM `#__users`';
		$this->db->setQuery($query);
		$this->db->query();

		$this->db->setQuery('SELECT COUNT(*) FROM `#__acymailing_list`');
		$nbLists = $this->db->loadResult();

		if(!empty($nbLists)) return true;

		$user = JFactory::getUser();
		$this->db->setQuery("INSERT INTO `#__acymailing_list` (`name`, `description`, `ordering`, `published`, `alias`, `color`, `visible`, `type`,`userid`) VALUES ('Newsletters','Receive our latest news','1','1','mailing_list','#3366ff','1','list',".(int)$user->id.")");
		$this->db->query();
		$listid = $this->db->insertid();


		$this->db->setQuery('INSERT IGNORE INTO `#__acymailing_listsub` (`listid`, `subid`, `subdate`, `status`) SELECT '.$listid.', subid, '.$time.',1 FROM `#__acymailing_subscriber`');
		$this->db->query();
	}


	function installBounceRules(){
		if(!acymailing_level(3)) return;

		$this->db->setQuery('SELECT COUNT(*) FROM #__acymailing_rules');
		if($this->db->loadResult() > 0) return;


		$config = acymailing_config();
		if($config->get('reply_email') != $config->get('bounce_email')){
			$forwardEmail = strlen($config->get('reply_email')).':"'.$config->get('reply_email').'"';
		}else $forwardEmail = strlen($config->get('from_email')).':"'.$config->get('from_email').'"';

		$query = 'INSERT INTO `#__acymailing_rules` (`name`, `ordering`, `regex`, `executed_on`, `action_message`, `action_user`, `published`) VALUES ';
		$query .= '(\'Action Required\', 1, \'action *requ|verif\', \'a:1:{s:7:"subject";s:1:"1";}\', \'a:2:{s:6:"delete";s:1:"1";s:9:"forwardto";s:'.$forwardEmail.';}\', \'a:1:{s:3:"min";s:1:"0";}\', 1),';
		$query .= '(\'Acknowledgement of receipt - in subject\', 2, \'(out|away) *(of|from)|vacation|holiday|absen|congés|recept|acknowledg|thank you for\', \'a:1:{s:7:"subject";s:1:"1";}\', \'a:1:{s:6:"delete";s:1:"1";}\', \'a:1:{s:3:"min";s:1:"0";}\', 1),';
		$query .= '(\'Feedback loop\', 3, \'feedback|staff@hotmail.com|complaints@.{0,15}email-abuse.amazonses.com|complaint about message\', \'a:2:{s:10:"senderinfo";s:1:"1";s:7:"subject";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:2:{s:3:"min";s:1:"0";s:5:"unsub";s:1:"1";}\', 1),';
		$query .= '(\'Feedback loop - in body\', 4, \'Feedback-Type.{1,5}abuse\', \'a:1:{s:4:"body";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:2:{s:3:"min";s:1:"0";s:5:"unsub";s:1:"1";}\', 1),';
		$query .= '(\'Mailbox Full\', 5, \'((mailbox|mailfolder|storage|quota|space|inbox) *(is)? *(over)? *(exceeded|size|storage|allocation|full|quota|maxi))|status(-code)? *(:|=)? *5\.2\.2|quota-issue|not *enough.{1,20}space|((over|exceeded|full|exhausted) *(allowed)? *(mail|storage|quota))\', \'a:2:{s:7:"subject";s:1:"1";s:4:"body";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:3:{s:5:"stats";s:1:"1";s:3:"min";s:1:"3";s:5:"block";s:1:"1";}\', 1),';
		$query .= '(\'Blocked by Google Groups\', 6, \'message *rejected *by *Google *Groups\',  \'a:1:{s:4:"body";s:1:"1";}\', \'a:2:{s:6:"delete";s:1:"1";s:9:"forwardto";s:'.$forwardEmail.';}\', \'a:2:{s:5:"stats";s:1:"1";s:3:"min";s:1:"0";}\', 1),';
		$query .= '(\'Mailbox does not exist 1\', 7, \'(Invalid|no such|unknown|bad|des?activated|undelivered|inactive|unrouteable) *(mail|destination|recipient|user|address|person)|bad-mailbox|inactive-mailbox|not listed in.{1,20}directory|RecipNotFound|(user|mailbox|address|recipients?|host|account|domain) *(is|has been)? *(error|disabled|failed|unknown|unavailable|not *(found|available)|.{1,30}inactiv)|no *mailbox *here|user does.?n.t have.{0,30}account\', \'a:2:{s:7:"subject";s:1:"1";s:4:"body";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:3:{s:5:"stats";s:1:"1";s:3:"min";s:1:"0";s:5:"block";s:1:"1";}\', 1),';
		$query .= '(\'Message blocked by recipient filters\',8, \'blocked *by|block *list|look(ed)? *like *spam|spam-related|spam *detected| CXBL | CDRBL | IPBL | URLBL |(unacceptable|banned|offensive|filtered|blocked|unsolicited) *(content|message|e?-?mail)|service refused|(status(-code)?|554) *(:|=)? *5\.7\.1|administratively *denied|blacklisted *IP|policy *reasons|rejected.{1,10}spam|junkmail *rejected|throttling *constraints|exceeded.{1,10}max.{1,40}hour|comply with required standards|421 RP-00|550 SC-00|550 DY-00|550 OU-00\', \'a:1:{s:4:"body";s:1:"1";}\', \'a:2:{s:6:"delete";s:1:"1";s:9:"forwardto";s:'.$forwardEmail.';}\', \'a:2:{s:5:"stats";s:1:"1";s:3:"min";s:1:"0";}\', 1),';
		$query .= '(\'Mailbox does not exist 2\', 9, \'status(-code)? *(:|=)? *5\.(1\.[1-6]|0\.0|4\.[0123467])|recipient *address *rejected|does *not *like *recipient\', \'a:2:{s:7:"subject";s:1:"1";s:4:"body";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:3:{s:5:"stats";s:1:"1";s:3:"min";s:1:"0";s:5:"block";s:1:"1";}\', 1),';
		$query .= '(\'Domain does not exist\', 10, \'No.{1,10}MX *(record|host)|host *does *not *receive *any *mail|bad-domain|connection.{1,10}mail.{1,20}fail|domain.{1,10}not *exist|fail.{1,10}establish *connection\', \'a:2:{s:7:"subject";s:1:"1";s:4:"body";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:3:{s:5:"stats";s:1:"1";s:3:"min";s:1:"0";s:5:"block";s:1:"1";}\', 1),';
		$query .= '(\'Temporary failures\', 11, \'has.*been.*delayed|delayed *mail|message *delayed|message-expired|temporar(il)?y *(failure|unavailable|disable|offline|unable)|deferred|delayed *([0-9]*) *(hour|minut)|possible *mail *loop|too *many *hops|delivery *time *expired|Action: *delayed|status(-code)? *(:|=)? *4\.4\.6|will continue to be attempted\', \'a:2:{s:7:"subject";s:1:"1";s:4:"body";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:3:{s:5:"stats";s:1:"1";s:3:"min";s:1:"3";s:5:"block";s:1:"1";}\', 1),';
		$query .= '(\'Failed Permanently\', 12, \'failed *permanently|permanent.{1,20}(failure|error)|not *accepting *(any)? *mail|does *not *exist|no *valid *route|delivery *failure\', \'a:2:{s:7:"subject";s:1:"1";s:4:"body";s:1:"1";}\', \'a:3:{s:4:"save";s:1:"1";s:6:"delete";s:1:"1";s:9:"forwardto";s:0:"";}\', \'a:3:{s:5:"stats";s:1:"1";s:3:"min";s:1:"0";s:5:"block";s:1:"1";}\', 1),';
		$query .= '(\'Acknowledgement of receipt - in body\', 13, \'vacances|holiday|vacation|absen|urlaub\', \'a:1:{s:4:"body";s:1:"1";}\', \'a:1:{s:6:"delete";s:1:"1";}\', \'a:1:{s:3:"min";s:1:"0";}\', 1),';
		$query .= '(\'Final Rule\', 14, \'.\', \'a:2:{s:10:"senderinfo";s:1:"1";s:7:"subject";s:1:"1";}\', \'a:2:{s:6:"delete";s:1:"1";s:9:"forwardto";s:'.$forwardEmail.';}\', \'a:1:{s:3:"min";s:1:"0";}\', 1)';

		$this->db->setQuery($query);
		$this->db->query();

		$newConfig = new stdClass();
		$newConfig->bouncerulesversion = $this->bouncerulesversion;
		$config->save($newConfig);
	}


	function installExtensions(){
		$path = ACYMAILING_BACK.'extensions';
		$dirs = JFolder::folders($path);

		if(!ACYMAILING_J16){
			if(file_exists(ACYMAILING_BACK.'config.xml')) JFile::delete(ACYMAILING_BACK.'config.xml');

			$query = "SELECT CONCAT(`folder`,`element`) FROM #__plugins WHERE `folder` = 'acymailing' OR `element` LIKE '%acy%'";
			$query .= " UNION SELECT `module` FROM #__modules WHERE `module` LIKE '%acymailing%'";
			$this->db->setQuery($query);
			$existingExtensions = acymailing_loadResultArray($this->db);
		}else{

			$this->db->setQuery("SELECT CONCAT(`folder`,`element`) FROM #__extensions WHERE `folder` = 'acymailing' OR `element` LIKE '%acy%'");
			$existingExtensions = acymailing_loadResultArray($this->db);
		}


		$plugins = array();
		$modules = array();
		$extensioninfo = array(); //array('name','ordering','required table or published')
		$extensioninfo['mod_acymailing'] = array('AcyMailing Module');
		$extensioninfo['plg_acymailing_share'] = array('AcyMailing : share on social networks', 20, 1);
		$extensioninfo['plg_acymailing_contentplugin'] = array('AcyMailing : trigger Joomla Content plugins', 15, 0);
		$extensioninfo['plg_acymailing_managetext'] = array('AcyMailing Manage text', 10, 1);
		$extensioninfo['plg_acymailing_tablecontents'] = array('AcyMailing table of contents generator', 5, 1);
		$extensioninfo['plg_acymailing_online'] = array('AcyMailing Tag : Website links', 6, 1);
		$extensioninfo['plg_acymailing_stats'] = array('AcyMailing : Statistics Plugin', 50, 1);
		$extensioninfo['plg_acymailing_tagcbuser'] = array('AcyMailing Tag : CB User information', 4, '#__comprofiler');
		$extensioninfo['plg_acymailing_tagcontent'] = array('AcyMailing Tag : content insertion', 11, 1);
		$extensioninfo['plg_acymailing_tagmodule'] = array('AcyMailing Tag : Insert a Module', 12, 1);
		$extensioninfo['plg_acymailing_tagsubscriber'] = array('AcyMailing Tag : Subscriber information', 2, 1);
		$extensioninfo['plg_acymailing_tagsubscription'] = array('AcyMailing Tag : Manage the Subscription', 1, 1);
		$extensioninfo['plg_acymailing_tagtime'] = array('AcyMailing Tag : Date / Time', 5, 1);
		$extensioninfo['plg_acymailing_taguser'] = array('AcyMailing Tag : Joomla User Information', 3, 1);
		$extensioninfo['plg_acymailing_virtuemart'] = array('AcyMailing Tag : VirtueMart integration', 7, '#__vm_product');
		$extensioninfo['plg_acymailing_template'] = array('AcyMailing Template Class Replacer', 25, 1);
		$extensioninfo['plg_acymailing_urltracker'] = array('AcyMailing : Handle Click tracking part1', 30, 1);
		$extensioninfo['plg_system_acymailingurltracker'] = array('AcyMailing : Handle Click tracking part2', 1, 1);
		$extensioninfo['plg_system_regacymailing'] = array('AcyMailing : (auto)Subscribe during Joomla registration', 0, 1);
		$extensioninfo['plg_system_vmacymailing'] = array('AcyMailing : VirtueMart checkout subscription', 0, 0);
		$extensioninfo['plg_editors_acyeditor'] = array('AcyMailing Editor', 5, 1);
		$extensioninfo['plg_acymailing_geolocation'] = array('AcyMailing Geolocation : Tag and filter', 10, 1);
		$extensioninfo['plg_acymailing_plginboxactions'] = array('AcyMailing : Inbox actions', 35, 1);
		$extensioninfo['plg_system_acymailingclassmail'] = array('Override Joomla mailing system', 1, 0);
		$extensioninfo['plg_acymailing_calltoaction'] = array('AcyMailing Tag : Call to action', 22, 1);

		$listTables = $this->db->getTableList();
		$fromVersion = JRequest::getCmd('fromversion');

		foreach($dirs as $oneDir){
			$arguments = explode('_', $oneDir);
			if(!isset($extensioninfo[$oneDir])) continue;

			$additionalInfo = new stdClass();
			if($arguments[0] == 'mod') $arguments[2] = $oneDir;
			if(ACYMAILING_J16 && !empty($arguments[2]) && file_exists($path.DS.$oneDir.DS.$arguments[2].'.xml')){
				$xmlFile = simplexml_load_file($path.DS.$oneDir.DS.$arguments[2].'.xml');
				$additionalInfo->version = (string)$xmlFile->version;
				$additionalInfo->author = (string)$xmlFile->author;
				$additionalInfo->creationDate = (string)$xmlFile->creationDate;

				$extension = $arguments[0] == 'mod' ? $oneDir : $arguments[1].$arguments[2];

				if(in_array($extension, $existingExtensions) && version_compare($fromVersion, '4.8.1', '<')){
					$query = "UPDATE `#__extensions` SET `manifest_cache` = ".$this->db->Quote(json_encode($additionalInfo))." WHERE (type = ";
					if($arguments[0] == 'mod'){
						$query .= "'module' AND `element` = ".$this->db->Quote($oneDir).")";
					}else{
						$query .= "'plugin' AND folder = ".$this->db->Quote($arguments[1])." AND `element` = ".$this->db->Quote($arguments[2]).")";
					}
					$this->db->setQuery($query);
					$this->db->query();
				}
			}

			if($arguments[0] == 'plg'){
				$newPlugin = new stdClass();
				if(!empty($additionalInfo)) $newPlugin->additionalInfo = json_encode($additionalInfo);
				$newPlugin->name = $oneDir;
				if(isset($extensioninfo[$oneDir][0])) $newPlugin->name = $extensioninfo[$oneDir][0];
				$newPlugin->type = 'plugin';
				$newPlugin->folder = $arguments[1];
				$newPlugin->element = $arguments[2];
				$newPlugin->enabled = 1;
				if(isset($extensioninfo[$oneDir][2])){
					if(is_numeric($extensioninfo[$oneDir][2])){
						$newPlugin->enabled = $extensioninfo[$oneDir][2];
					}elseif(!in_array(str_replace('#__', $this->db->getPrefix(), $extensioninfo[$oneDir][2]), $listTables)) $newPlugin->enabled = 0;
				}
				$newPlugin->params = '{}';
				$newPlugin->ordering = 0;
				if(isset($extensioninfo[$oneDir][1])) $newPlugin->ordering = $extensioninfo[$oneDir][1];

				if(!acymailing_createDir(ACYMAILING_ROOT.'plugins'.DS.$newPlugin->folder)) continue;

				if(!ACYMAILING_J16){
					$destinationFolder = ACYMAILING_ROOT.'plugins'.DS.$newPlugin->folder;
				}else{
					$destinationFolder = ACYMAILING_ROOT.'plugins'.DS.$newPlugin->folder.DS.$newPlugin->element;
					if(!acymailing_createDir($destinationFolder)) continue;
				}

				if(!$this->copyFolder($path.DS.$oneDir, $destinationFolder)) continue;


				if(in_array($newPlugin->folder.$newPlugin->element, $existingExtensions)) continue;

				$plugins[] = $newPlugin;
			}elseif($arguments[0] == 'mod'){
				$newModule = new stdClass();
				if(!empty($additionalInfo)) $newModule->additionalInfo = json_encode($additionalInfo);
				$newModule->name = $oneDir;
				if(isset($extensioninfo[$oneDir][0])) $newModule->name = $extensioninfo[$oneDir][0];
				$newModule->type = 'module';
				$newModule->folder = '';
				$newModule->element = $oneDir;
				$newModule->enabled = 1;
				$newModule->params = '{}';
				$newModule->ordering = 0;
				if(isset($extensioninfo[$oneDir][1])) $newModule->ordering = $extensioninfo[$oneDir][1];

				$destinationFolder = ACYMAILING_ROOT.'modules'.DS.$oneDir;

				if(!acymailing_createDir($destinationFolder)) continue;

				if(!$this->copyFolder($path.DS.$oneDir, $destinationFolder)) continue;

				if(in_array($newModule->element, $existingExtensions)) continue;

				$modules[] = $newModule;
			}else{
				acymailing_enqueueMessage('Could not handle : '.$oneDir, 'error');
			}
		}

		if(!empty($this->errors)) acymailing_enqueueMessage($this->errors, 'error');

		if(!ACYMAILING_J16){
			$extensions = $plugins;
		}else{
			$extensions = array_merge($plugins, $modules);
		}

		$success = array();
		if(!empty($extensions)){
			if(!ACYMAILING_J16){
				$queryExtensions = 'INSERT INTO `#__plugins` (`name`,`element`,`folder`,`published`,`ordering`) VALUES ';
			}else{
				$queryExtensions = 'INSERT INTO `#__extensions` (`name`,`element`,`folder`,`enabled`,`ordering`,`type`,`access`,`manifest_cache`) VALUES ';
			}

			foreach($extensions as $oneExt){
				$queryExtensions .= '('.$this->db->Quote($oneExt->name).','.$this->db->Quote($oneExt->element).','.$this->db->Quote($oneExt->folder).','.$oneExt->enabled.','.$oneExt->ordering;
				if(ACYMAILING_J16) $queryExtensions .= ','.$this->db->Quote($oneExt->type).',1,'.$this->db->Quote(!empty($oneExt->additionalInfo) ? $oneExt->additionalInfo : '');
				$queryExtensions .= '),';
				if($oneExt->type != 'module') $success[] = JText::sprintf('PLUG_INSTALLED', $oneExt->name);
			}
			$queryExtensions = trim($queryExtensions, ',');

			$this->db->setQuery($queryExtensions);
			$this->db->query();
		}

		if(!empty($modules)){
			foreach($modules as $oneModule){
				if(!ACYMAILING_J16){
					$queryModule = 'INSERT INTO `#__modules` (`title`,`position`,`published`,`module`) VALUES ';
					$queryModule .= '('.$this->db->Quote($oneModule->name).",'left',0,".$this->db->Quote($oneModule->element).")";
				}else{
					$queryModule = 'INSERT INTO `#__modules` (`title`,`position`,`published`,`module`,`access`,`language`) VALUES ';
					$queryModule .= '('.$this->db->Quote($oneModule->name).",'position-7',0,".$this->db->Quote($oneModule->element).",1,'*')";
				}
				$this->db->setQuery($queryModule);
				$this->db->query();
				$moduleId = $this->db->insertid();

				$this->db->setQuery('INSERT IGNORE INTO `#__modules_menu` (`moduleid`,`menuid`) VALUES ('.$moduleId.',0)');
				$this->db->query();

				$success[] = JText::sprintf('MODULE_INSTALLED', $oneModule->name);
			}
		}

		if(ACYMAILING_J16){
			$this->db->setQuery("UPDATE `#__extensions` SET `access` = 1 WHERE ( `folder` = 'acymailing' OR `element` LIKE '%acymailing%' ) AND `type` = 'plugin'");
			$this->db->query();
		}

		$this->cleanPluginCache();

		if(!empty($success)) acymailing_enqueueMessage($success, 'success');
	}

	function copyFolder($from, $to){
		$return = true;

		$allFiles = JFolder::files($from);
		foreach($allFiles as $oneFile){
			if(file_exists($to.DS.'index.html') AND $oneFile == 'index.html') continue;
			if(JFile::copy($from.DS.$oneFile, $to.DS.$oneFile) !== true){
				$this->errors[] = 'Could not copy the file from '.$from.DS.$oneFile.' to '.$to.DS.$oneFile;
				$return = false;
			}
			if(ACYMAILING_J30 && substr($oneFile, -4) == '.xml'){
				$data = file_get_contents($to.DS.$oneFile);
				if(strpos($data, '<install ') !== false){
					$data = str_replace(array('<install ', '</install>', 'version="1.5"', '<!DOCTYPE install SYSTEM "http://dev.joomla.org/xml/1.5/plugin-install.dtd">'), array('<extension ', '</extension>', 'version="2.5"', ''), $data);
					JFile::write($to.DS.$oneFile, $data);
				}
			}
		}
		$allFolders = JFolder::folders($from);
		if(!empty($allFolders)){
			foreach($allFolders as $oneFolder){
				if(!acymailing_createDir($to.DS.$oneFolder)) continue;
				if(!$this->copyFolder($from.DS.$oneFolder, $to.DS.$oneFolder)) $return = false;
			}
		}
		return $return;
	}

	public function cleanPluginCache(){
		if(!ACYMAILING_J16 || !class_exists('JCache')) return;

		$conf = JFactory::getConfig();
		$dispatcher = JDispatcher::getInstance();

		$options = array('defaultgroup' => 'com_plugins', 'cachebase' => $conf->get('cache_path', JPATH_SITE.'/cache'));

		$cache = JCache::getInstance('callback', $options);
		$cache->clean();

		$dispatcher->trigger('onContentCleanCache', $options);
	}
}
