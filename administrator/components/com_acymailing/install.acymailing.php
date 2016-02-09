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

if(version_compare(PHP_VERSION, '5.0.0', '<')){
	echo '<p style="color:red">This version of AcyMailing does not support PHP4, it is time to upgrade your server to PHP5!</p>';
	exit;
}

function installAcyMailing(){
	$success = true;
	try{
		include_once(rtrim(JPATH_ADMINISTRATOR, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php');
	} catch(Exception $e){
		$updateHelper = acymailing_get('helper.update');
		$updateHelper->installTables();
		$success = false;
	}

	acymailing_increasePerf();

	$installClass = new acymailingInstall();
	$installClass->updateJoomailing();
	$installClass->addPref();
	$installClass->updatePref();
	$installClass->updateSQL();
	if($success) $installClass->displayInfo();
}

function uninstallAcyMailing(){
	$uninstallClass = new acymailingUninstall();
	$uninstallClass->unpublishModules();
	$uninstallClass->message();
}

if(!function_exists('com_install')){
	function com_install(){
		return installAcyMailing();
	}
}

if(!function_exists('com_uninstall')){
	function com_uninstall(){
		return uninstallAcyMailing();
	}
}

class com_acymailingInstallerScript{
	function install($parent){
		installAcyMailing();
	}

	function update($parent){
		installAcyMailing();
	}

	function uninstall($parent){
		uninstallAcyMailing();
	}

	function preflight($type, $parent){
		return true;
	}

	function postflight($type, $parent){
		return true;
	}
}


class acymailingInstall{

	var $level = 'starter';
	var $version = '5.0.1';
	var $update = false;
	var $fromLevel = '';
	var $fromVersion = '';
	var $db;

	function acymailingInstall(){
		$this->db = JFactory::getDBO();
	}

	function displayInfo(){

		echo '<h1>Please wait... </h1><h2>AcyMailing will now automatically install the Plugins and the Module</h2>';
		$url = 'index.php?option=com_acymailing&ctrl=update&task=install&fromlevel='.$this->fromLevel.'&fromversion='.$this->fromVersion;
		echo '<a href="'.$url.'">Please click here if you are not automatically redirected within 3 seconds</a>';
		echo "<script language=\"javascript\" type=\"text/javascript\">document.location.href='$url';</script>\n";
	}


	function updatePref(){

		$this->db->setQuery("SELECT `namekey`, `value` FROM `#__acymailing_config` WHERE `namekey` IN ('version','level') LIMIT 2");
		try{
			$results = $this->db->loadObjectList('namekey');
		} catch(Exception $e){
			$results = null;
		}

		if($results === null){
			acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags($this->db->getErrorMsg()), 0, 200).'...', 'error');
			return false;
		}

		if($results['version']->value == $this->version AND $results['level']->value == $this->level) return true;

		$this->update = true;
		$this->fromLevel = $results['level']->value;
		$this->fromVersion = $results['version']->value;

		$query = "REPLACE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ('level',".$this->db->Quote($this->level)."),('version',".$this->db->Quote($this->version)."),('installcomplete','0')";
		$this->db->setQuery($query);
		$this->db->query();

		return true;
	}

	function updateSQL(){
		if(!$this->update) return true;

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		if(version_compare($this->fromVersion, '1.1.4', '<')){
			$replace1 = "REPLACE(`params`, 'showhtml=1\nshowname=1', 'customfields=name,email,html' )";
			$replace2 = "REPLACE( $replace1 , 'showhtml=0\nshowname=1', 'customfields=name,email' )";
			$replace3 = "REPLACE( $replace2 , 'showhtml=1\nshowname=0', 'customfields=email,html' )";
			$replace4 = "REPLACE( $replace3 , 'showhtml=0\nshowname=0', 'customfields=email' )";
			$this->updateQuery("UPDATE #__modules SET `params`= $replace4 WHERE `module` = 'mod_acymailing' ");
		}

		if(version_compare($this->fromVersion, '1.2.1', '<')){
			$this->updateQuery("UPDATE `#__acymailing_config` SET `value` = 'data' WHERE `value` = '0' AND `namekey` = 'allow_modif' LIMIT 1");
			$this->updateQuery("UPDATE `#__acymailing_config` SET `value` = 'all' WHERE `value` = '1' AND `namekey` = 'allow_modif' LIMIT 1");
		}

		if(version_compare($this->fromVersion, '1.2.2', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_mail` ADD `sentby` INT UNSIGNED NULL DEFAULT NULL");
			$this->updateQuery("ALTER TABLE `#__acymailing_template` ADD `subject` VARCHAR( 250 ) NULL DEFAULT NULL");
			$this->updateQuery("DELETE FROM `#__plugins` WHERE `folder` = 'acymailing' AND `element` = 'autocontent'");
		}

		if(version_compare($this->fromVersion, '1.2.3', '<')){
			$this->updateQuery("UPDATE `#__plugins` SET `folder` = 'system', `element`= 'regacymailing', `name` = 'AcyMailing : (auto)Subscribe during Joomla registration', `params`= REPLACE(`params`, 'lists=', 'autosub=' ) WHERE `folder` = 'user' AND `element` = 'acymailing'");
			$this->updateQuery("DELETE FROM `#__plugins` WHERE `folder` = 'acymailing' AND `element` = 'autocontent'");
			$this->updateQuery("ALTER TABLE `#__acymailing_template` ADD `stylesheet` TEXT NULL");

			if(is_dir(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.ACYMAILING_COMPONENT.DS.'plugins'.DS.'plg_user_acymailing')){
				JFolder::delete(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.ACYMAILING_COMPONENT.DS.'plugins'.DS.'plg_user_acymailing');
			}
			if(is_dir(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.ACYMAILING_COMPONENT.DS.'plugins'.DS.'plg_acymailing_autocontent')){
				JFolder::delete(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.ACYMAILING_COMPONENT.DS.'plugins'.DS.'plg_acymailing_autocontent');
			}
		}

		if(version_compare($this->fromVersion, '1.3.1', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_config` CHANGE `value` `value` TEXT NULL ");

			$this->updateQuery("ALTER TABLE `#__acymailing_fields` ADD `listing` TINYINT NULL DEFAULT NULL ");
			$this->updateQuery("UPDATE `#__acymailing_fields` SET `listing` = 1 WHERE `namekey` IN ('name','email','html') ");
			$this->updateQuery("ALTER TABLE `#__acymailing_template` ADD `fromname` VARCHAR( 250 ) NULL , ADD `fromemail` VARCHAR( 250 ) NULL , ADD `replyname` VARCHAR( 250 ) NULL , ADD `replyemail` VARCHAR( 250 ) NULL ");
		}

		if(version_compare($this->fromVersion, '1.5.2', '<')){

			$this->db->setQuery("SELECT `params` FROM #__plugins WHERE `element` = 'regacymailing' LIMIT 1");
			$existingEntry = $this->db->loadResult();
			$listids = 'None';
			if(preg_match('#autosub=(.*)#i', $existingEntry, $autosubResult)){
				$listids = $autosubResult[1];
			}
			$this->updateQuery("INSERT IGNORE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ('autosub',".$this->db->Quote($listids).")");
		}

		if(version_compare($this->fromVersion, '1.5.3', '<')){
			$this->updateQuery('UPDATE #__acymailing_config SET `value` = REPLACE(`value`,\'<sup style="font-size: 4px;">TM</sup>\',\'™\')');
		}


		if(version_compare($this->fromVersion, '1.6.2', '<')){

			$this->updateQuery("UPDATE #__acymailing_config SET `value` = 'media/com_acymailing/upload' WHERE `namekey` = 'uploadfolder' AND `value` = 'components/com_acymailing/upload' ");

			$this->updateQuery("UPDATE #__acymailing_config SET `value` = 'media/com_acymailing/logs/report".rand(0, 999999999).".log' WHERE `namekey` = 'cron_savepath' ");

			if(!ACYMAILING_J16){
				$this->updateQuery("UPDATE #__plugins SET `params` = REPLACE(`params`,'components/com_acymailing/images','media/com_acymailing/images') ");
			}
			else{
				$this->updateQuery("UPDATE #__extensions SET `params` = REPLACE(`params`,'components\/com_acymailing\/images','media\/com_acymailing\/images') ");
			}


			$updateClass = acymailing_get('helper.update');
			$removeFiles = array();
			$removeFiles[] = ACYMAILING_FRONT.'css'.DS.'component_default.css';
			$removeFiles[] = ACYMAILING_FRONT.'css'.DS.'frontendedition.css';
			$removeFiles[] = ACYMAILING_FRONT.'css'.DS.'module_default.css';
			foreach($removeFiles as $oneFile){
				if(is_file($oneFile)) JFile::delete($oneFile);
			}

			$fromFolders = array();
			$toFolders = array();
			$fromFolders[] = ACYMAILING_FRONT.'css';
			$toFolders[] = ACYMAILING_MEDIA.'css';
			$fromFolders[] = ACYMAILING_FRONT.'templates'.DS.'plugins';
			$toFolders[] = ACYMAILING_MEDIA.'plugins';
			$fromFolders[] = ACYMAILING_FRONT.'upload';
			$toFolders[] = ACYMAILING_MEDIA.'upload';

			foreach($fromFolders as $i => $oneFolder){
				if(!is_dir($oneFolder)) continue;
				if(is_dir($toFolders[$i])){
					$updateClass->copyFolder($oneFolder, $toFolders[$i]);
				}
			}

			$deleteFolders = array();
			$deleteFolders[] = ACYMAILING_FRONT.'css';
			$deleteFolders[] = ACYMAILING_FRONT.'images';
			$deleteFolders[] = ACYMAILING_FRONT.'js';
			$deleteFolders[] = ACYMAILING_BACK.'logs';

			foreach($deleteFolders as $oneFolder){
				if(!is_dir($oneFolder)) continue;
				JFolder::delete($oneFolder);
			}
		}

		if(version_compare($this->fromVersion, '1.7.1', '<')){
			$this->updateQuery("CREATE TABLE IF NOT EXISTS `#__acymailing_history` (`subid` INT UNSIGNED NOT NULL ,`date` INT UNSIGNED NOT NULL ,`ip` VARCHAR( 50 ) NULL ,
								`action` VARCHAR( 50 ) NOT NULL , `data` TEXT NULL , `source` TEXT NULL , INDEX ( `subid` , `date` ) ) ;");
		}

		if(version_compare($this->fromVersion, '1.7.3', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_mail` ADD `metakey` TEXT NULL , ADD `metadesc` TEXT NULL ");
		}

		if(version_compare($this->fromVersion, '1.8.4', '<')){
			$this->updateQuery("UPDATE `#__acymailing_config` as a, `#__acymailing_config` as b SET a.`value` = b.`value` WHERE a.`namekey`= 'queue_nbmail_auto' AND b.`namekey`= 'queue_nbmail' ");
			$this->updateQuery("UPDATE `#__acymailing_mail` SET `body` = CONCAT(`body`,'<p>{survey}</p>') WHERE type = 'notification' AND `alias` IN ('notification_refuse','notification_unsub','notification_unsuball')");
		}

		if(version_compare($this->fromVersion, '1.8.5', '<')){
			$metaFile = ACYMAILING_FRONT.'metadata.xml';
			if(file_exists($metaFile)) JFile::delete($metaFile);
			$this->updateQuery('ALTER TABLE #__acymailing_url DROP INDEX url');
			$this->updateQuery('ALTER TABLE `#__acymailing_url` CHANGE `url` `url` TEXT NOT NULL');
			$this->updateQuery('ALTER TABLE `#__acymailing_url` ADD INDEX `url` ( `url` ( 250 ) ) ');
			$this->updateQuery("UPDATE `#__acymailing_mail` SET `body` = CONCAT(`body`,'<p>Subscription : {user:subscription}</p>') WHERE type = 'notification' AND `alias` = 'notification_created'");
		}

		if(version_compare($this->fromVersion, '1.9.1', '<')){
			$this->updateQuery('ALTER TABLE `#__acymailing_history` ADD `mailid` MEDIUMINT UNSIGNED NULL');

			$this->updateQuery('CREATE TABLE IF NOT EXISTS `#__acymailing_rules` (
				`ruleid` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`name` VARCHAR( 250 ) NOT NULL ,
				`ordering` SMALLINT UNSIGNED NULL ,
				`regex` VARCHAR( 250 ) NOT NULL ,
				`executed_on` TEXT NOT NULL ,
				`action_message` TEXT NOT NULL ,
				`action_user` TEXT NOT NULL ,
				`published` TINYINT UNSIGNED NOT NULL
				)');
			$this->updateQuery("UPDATE `#__acymailing_mail` SET `body` = CONCAT(`body`,'<p>Subscription : {user:subscription}</p>') WHERE type = 'notification' AND `alias` IN ( 'notification_unsuball','notification_refuse','notification_unsub')");
			$this->updateQuery("REPLACE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ('auto_bounce','0')");
		}

		if(version_compare($this->fromVersion, '3.0.1', '<')){
			$this->updateQuery('ALTER TABLE `#__acymailing_mail` ADD `filter` TEXT NULL');

			$this->updateQuery("ALTER TABLE `#__acymailing_subscriber` CHANGE `userid` `userid` INT UNSIGNED NOT NULL DEFAULT '0'");
		}

		if(version_compare($this->fromVersion, '3.5.1', '<')){
			if(file_exists(ACYMAILING_FRONT.'sef_ext.php')) JFile::delete(ACYMAILING_FRONT.'sef_ext.php');

			$this->updateQuery("ALTER TABLE `#__acymailing_queue` ADD `paramqueue` VARCHAR( 250 ) NULL ");

			if(!ACYMAILING_J16){
				$this->updateQuery("DELETE FROM `#__plugins` WHERE folder = 'acymailing' AND element LIKE 'tagvm%'");
			}
			else{
				$this->updateQuery("DELETE FROM `#__extensions` WHERE folder = 'acymailing' AND element LIKE 'tagvm%'");
			}
		}

		if(version_compare($this->fromVersion, '3.6.1', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_rules` CHANGE `regex` `regex` TEXT NOT NULL");
			$this->updateQuery("ALTER TABLE `#__acymailing_stats` ADD `bouncedetails` TEXT NULL");
		}

		if(version_compare($this->fromVersion, '3.7.1', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_userstats` ADD `ip` VARCHAR( 100 ) NULL");
			$this->updateQuery("ALTER TABLE `#__acymailing_urlclick` ADD `ip` VARCHAR( 100 ) NULL");
		}

		if(version_compare($this->fromVersion, '3.8.1', '<')){
			$this->updateQuery("UPDATE #__acymailing_mail SET subject = CONCAT(subject,' ','{mainreport}') WHERE type = 'notification' AND alias = 'report' AND subject NOT LIKE '%mainreport%' LIMIT 1");
		}

		if(version_compare($this->fromVersion, '3.8.2', '<')){
			$this->updateQuery("INSERT IGNORE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ('optimize_listsub',0),('optimize_stats',0),('optimize_list',0),('optimize_mail',0),('optimize_userstats',0),('optimize_urlclick',0),('optimize_history',0),('optimize_template',0),('optimize_queue',0),('optimize_subscriber',0) ");
		}

		$file = ACYMAILING_FRONT.'views'.DS.'newsletter'.DS.'metadata.xml';
		if(file_exists($file)) JFile::delete($file);

		$file = ACYMAILING_BACK.'admin.acymailing.php';
		if(file_exists($file)) JFile::delete($file);

		if(version_compare($this->fromVersion, '4.0.0', '<')){

			$this->db->setQuery("SELECT params,id FROM #__modules WHERE module = 'mod_acymailing'");
			$allModules = $this->db->loadObjectList();

			foreach($allModules as $oneMod){
				$newParams = preg_replace('#fieldsize=.*#i', 'fieldsize=80%', $oneMod->params);
				$newParams = preg_replace('#"fieldsize":"[^"]*"#i', '"fieldsize":"80%"', $newParams);
				$this->updateQuery("UPDATE #__modules SET params = ".$this->db->Quote($newParams)." WHERE id = ".intval($oneMod->id));
			}

			$this->db->setQuery("SELECT options,fieldid FROM #__acymailing_fields WHERE type IN ('phone','text','date','file') AND options LIKE '%size%'");
			$allFields = $this->db->loadObjectList();

			foreach($allFields as $oneField){
				$options = unserialize($oneField->options);
				$options['size'] = intval($options['size'] * 5);
				$this->updateQuery("UPDATE #__acymailing_fields SET options = ".$this->db->Quote(serialize($options))." WHERE fieldid = ".intval($oneField->fieldid));
			}
		}

		if(is_dir(ACYMAILING_BACK.'inc'.DS.'openflash')){
			JFolder::delete(ACYMAILING_BACK.'inc'.DS.'openflash');
		}
		if(is_dir(ACYMAILING_FRONT.'inc'.DS.'openflash')){
			JFolder::delete(ACYMAILING_FRONT.'inc'.DS.'openflash');
		}

		if(version_compare($this->fromVersion, '4.2.0', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_template` ADD `thumb` VARCHAR( 250 ) NULL , ADD `readmore` VARCHAR( 250 ) NULL ");

			$this->db->setQuery("SELECT tempid, description FROM #__acymailing_template WHERE `thumb` IS NULL");
			$allTemplates = $this->db->loadObjectList();
			foreach($allTemplates as $oneTemplate){
				if(preg_match('#<img[^>]*src="([^"]*)"[^>]*>#Ui', $oneTemplate->description, $onethumb)){
					$this->updateQuery('UPDATE #__acymailing_template SET `description` = '.$this->db->Quote(str_replace($onethumb[0], '', $oneTemplate->description)).', `thumb` = '.$this->db->Quote($onethumb[1]).' WHERE tempid = '.$oneTemplate->tempid);
				}
			}

			$this->updateQuery("ALTER TABLE `#__acymailing_subscriber` ADD `confirmed_date` INT UNSIGNED NOT NULL DEFAULT '0', ADD `confirmed_ip` VARCHAR(100) NULL , ADD `lastopen_date` INT UNSIGNED NOT NULL DEFAULT '0', ADD `lastclick_date` INT UNSIGNED NOT NULL DEFAULT '0'");
			$this->updateQuery('UPDATE #__acymailing_subscriber as sub JOIN #__acymailing_history as hist ON sub.subid = hist.subid AND hist.action = "confirmed" SET sub.confirmed_date = hist.date, sub.confirmed_ip = hist.ip WHERE sub.confirmed_date = 0');
			$this->updateQuery('UPDATE #__acymailing_subscriber as sub JOIN #__acymailing_userstats as stats ON sub.subid = stats.subid SET sub.lastopen_date = stats.opendate WHERE sub.lastopen_date = 0');
			$this->updateQuery('UPDATE #__acymailing_subscriber as sub JOIN #__acymailing_urlclick as url ON sub.subid = url.subid SET sub.lastclick_date = url.date WHERE sub.lastclick_date = 0');
			$this->updateQuery('ALTER TABLE `#__acymailing_list` CHANGE `ordering` `ordering` SMALLINT UNSIGNED NULL DEFAULT \'0\'');
			$this->updateQuery('ALTER TABLE `#__acymailing_template` CHANGE `ordering` `ordering` SMALLINT UNSIGNED NULL DEFAULT \'0\'');

			$templateClass = acymailing_get('class.template');
			for($i = 1; $i <= 10; $i++){
				$templateClass->createTemplateFile($i);
			}
		}

		if(version_compare($this->fromVersion, '4.3.0', '<')){
			if(!ACYMAILING_J16){
				$queryReplace = "UPDATE `#__plugins` SET `name` = REPLACE(`name`,'(beta)','') WHERE `element` = 'acyeditor'";
			}
			else{
				$queryReplace = "UPDATE `#__extensions` SET `name` = REPLACE(`name`,'(beta)','') WHERE `element` = 'acyeditor'";
			}
			$this->updateQuery($queryReplace);

			if(!ACYMAILING_J16){
				$this->db->setQuery("SELECT `params` FROM #__plugins WHERE `element` = 'urltracker' LIMIT 1");
				$pattern = '#trackingsystem=(.*)#i';
			}
			else{
				$this->db->setQuery("SELECT `params` FROM #__extensions WHERE `element` = 'urltracker' LIMIT 1");
				$pattern = '#"trackingsystem":"([^"]*)"#i';
			}
			$existingEntry = $this->db->loadResult();
			$trackingMode = 'acymailing';
			if(preg_match($pattern, $existingEntry, $autosubResult)){
				$trackingMode = $autosubResult[1];
			}
			if($trackingMode == 'googleacy') $trackingMode = 'acymailing,google';
			$this->updateQuery("INSERT IGNORE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ('trackingsystem',".$this->db->Quote($trackingMode).")");
		}

		if(version_compare($this->fromVersion, '4.3.1', '<')){
			$query = 'CREATE TABLE IF NOT EXISTS `#__acymailing_geolocation` (`geolocation_id` int unsigned NOT NULL AUTO_INCREMENT, `geolocation_subid` int unsigned NOT NULL DEFAULT \'0\',';
			$query .= ' `geolocation_type` varchar(255) NOT NULL DEFAULT \'subscription\', `geolocation_ip` varchar(255) NOT NULL DEFAULT \'\', `geolocation_created` int unsigned NOT NULL DEFAULT \'0\',';
			$query .= ' `geolocation_latitude` decimal(9,6) NOT NULL DEFAULT \'0.000000\', `geolocation_longitude` decimal(9,6) NOT NULL DEFAULT \'0.000000\', `geolocation_postal_code` varchar(255) NOT NULL DEFAULT \'\',';
			$query .= ' `geolocation_country` varchar(255) NOT NULL DEFAULT \'\', `geolocation_country_code` varchar(255) NOT NULL DEFAULT \'\', `geolocation_state` varchar(255) NOT NULL DEFAULT \'\',';
			$query .= ' `geolocation_state_code` varchar(255) NOT NULL DEFAULT \'\', `geolocation_city` varchar(255) NOT NULL DEFAULT \'\',';
			$query .= ' PRIMARY KEY (`geolocation_id`), KEY `geolocation_type` (`geolocation_subid`, `geolocation_type`)) ;';
			$this->updateQuery($query);
		}

		if(version_compare($this->fromVersion, '4.3.3', '<')){
			$this->updateQuery('UPDATE #__acymailing_list SET access_manage = CONCAT(",",access_manage) WHERE access_manage NOT IN ("all","none","")');
		}

		if(version_compare($this->fromVersion, '4.4.2', '<')){
			$this->updateQuery('ALTER TABLE `#__acymailing_fields` ADD `frontlisting` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT \'0\', ADD `frontjoomlaprofile` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT \'0\', ADD `frontjoomlaregistration` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT \'0\', ADD `joomlaprofile` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT \'0\'');
			$this->updateQuery('UPDATE `#__acymailing_fields` SET `frontlisting`  = `listing`');

			if(!ACYMAILING_J16){
				$this->db->setQuery("SELECT `params` FROM #__plugins WHERE `element` = 'regacymailing' LIMIT 1");
				$pattern = '#customfields=(.*)#i';
			}
			else{
				$this->db->setQuery("SELECT `params` FROM #__extensions WHERE `element` = 'regacymailing' LIMIT 1");
				$pattern = '#"customfields":"([^"]*)"#i';
			}
			$existingEntry = $this->db->loadResult();
			if(preg_match($pattern, $existingEntry, $pregResult)){
				$existingEntries = explode(',', $pregResult[1]);
				foreach($existingEntries as $fieldToDisplay){
					$this->updateQuery("UPDATE `#__acymailing_fields` SET frontjoomlaregistration=1 WHERE namekey=".$this->db->Quote(trim($fieldToDisplay)));
				}
			}

			$this->updateQuery("ALTER TABLE `#__acymailing_list` ADD `startrule` VARCHAR(50) NOT NULL DEFAULT '0'");

			if(is_dir(ACYMAILING_ROOT.'plugins'.DS.'editors'.DS.'acyeditor'.DS.'acyeditor'.DS.'kcfinder')){
				JFolder::delete(ACYMAILING_ROOT.'plugins'.DS.'editors'.DS.'acyeditor'.DS.'acyeditor'.DS.'kcfinder');
			}
			if(is_dir(ACYMAILING_ROOT.'plugins'.DS.'editors'.DS.'acyeditor'.DS.'kcfinder')){
				JFolder::delete(ACYMAILING_ROOT.'plugins'.DS.'editors'.DS.'acyeditor'.DS.'kcfinder');
			}
			if(is_dir(ACYMAILING_BACK.'extensions'.DS.'plg_editors_acyeditor'.DS.'acyeditor'.DS.'kcfinder')){
				JFolder::delete(ACYMAILING_BACK.'extensions'.DS.'plg_editors_acyeditor'.DS.'acyeditor'.DS.'kcfinder');
			}
		}

		if(version_compare($this->fromVersion, '4.5.2', '<')){
			$this->db->setQuery("SELECT * FROM #__acymailing_config WHERE namekey='acl_newsletters_manage'");
			$res = $this->db->query();
			if(!empty($res)){
				$this->updateQuery("INSERT IGNORE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ('acl_newsletters_lists', 'all'), ('acl_newsletters_attachments', 'all'), ('acl_newsletters_sender_informations', 'all'), ('acl_newsletters_meta_data','all')");
			}

			$this->updateQuery("ALTER TABLE `#__acymailing_template` ADD `access` VARCHAR( 250 ) NOT NULL DEFAULT 'all'");
			$this->updateQuery("ALTER TABLE `#__acymailing_subscriber` ADD `lastopen_ip` VARCHAR( 100 ) NULL, ADD `lastsent_date` INT UNSIGNED NOT NULL DEFAULT '0'");

			$this->updateQuery("UPDATE #__acymailing_subscriber as sub JOIN #__acymailing_userstats as stats ON sub.subid = stats.subid SET sub.lastopen_ip = stats.ip WHERE stats.ip != ''");

			$this->updateQuery("UPDATE #__acymailing_subscriber as sub JOIN #__acymailing_userstats as stats ON sub.subid = stats.subid SET sub.lastsent_date = stats.senddate");

			$this->updateQuery("ALTER TABLE `#__acymailing_mail` MODIFY `type` ENUM('news','autonews','followup','unsub','welcome','notification','joomlanotification') NOT NULL DEFAULT 'news'");
		}

		if(version_compare($this->fromVersion, '4.6.3', '<')){
			$file = ACYMAILING_ROOT.'plugins'.DS.'editors'.DS.'acyeditor'.DS.'acyeditor_j30.xml';
			if(file_exists($file)) JFile::delete($file);

			$file = ACYMAILING_ROOT.'plugins'.DS.'system'.DS.'acymailingclassmail'.DS.'acymailingclassmail_j30.xml';
			if(file_exists($file)) JFile::delete($file);

			$config = acymailing_config();
			if($config->get('mailer_method') == 'smtp_com'){
				$newConfig = new stdClass();
				$newConfig->mailer_method = 'smtp';
				$newConfig->smtp_host = 'retail.smtp.com';
				$newConfig->smtp_port = '2525';
				$newConfig->smtp_username = $config->get('smtp_com_username');
				$newConfig->smtp_password = $config->get('smtp_com_password');
				$newConfig->smtp_auth = 1;
				$newConfig->smtp_keepalive = 1;
				$newConfig->smtp_secured = '';
				$config->save($newConfig);
			}

			$this->updateQuery("ALTER TABLE `#__acymailing_userstats` ADD `browser` VARCHAR( 255 ) DEFAULT NULL, ADD `browser_version` TINYINT UNSIGNED DEFAULT NULL, ADD `is_mobile` TINYINT UNSIGNED DEFAULT NULL, ADD `mobile_os` VARCHAR( 255 ) DEFAULT NULL, ADD `user_agent` VARCHAR( 255 ) DEFAULT NULL");

			$this->updateQuery("ALTER TABLE `#__acymailing_mail` ADD `language` VARCHAR( 50 ) NOT NULL DEFAULT ''");
		}

		if(version_compare($this->fromVersion, '4.7.3', '<')){
			try{
				$this->db->setQuery("SELECT * FROM #__acymailing_config WHERE namekey='acl_newsletters_manage'");
				$res = $this->db->query();
				if(!empty($res)){
					$this->updateQuery("INSERT IGNORE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ('acl_newsletters_abtesting', 'all')");
				}
			} catch(Exception $e){
				$res = null;
			}
			if($res === null) acymailing_enqueueMessage(isset($e) ? $e->getMessage() : substr(strip_tags($this->db->getErrorMsg()), 0, 200).'...', 'error');

			$this->updateQuery("ALTER TABLE `#__acymailing_mail` ADD `abtesting` VARCHAR( 250 ) DEFAULT NULL");

			$this->updateQuery("ALTER TABLE `#__acymailing_subscriber` ADD `source` VARCHAR( 250 ) NOT NULL DEFAULT ''");
		}

		if(version_compare($this->fromVersion, '4.8.2', '<')){
			$tagsFile = JPATH_SITE.DS.'plugins'.DS.'acymailing'.DS.'tagcontent'.DS.'tagcontenttags.xml';
			if(file_exists($tagsFile)) JFile::delete($tagsFile);

			$this->updateQuery("ALTER TABLE `#__acymailing_mail` ADD `thumb` VARCHAR( 250 ) DEFAULT NULL");
			$this->updateQuery("ALTER TABLE `#__acymailing_mail` ADD `summary` TEXT NOT NULL DEFAULT ''");
			$this->updateQuery("ALTER TABLE `#__acymailing_template` ADD `category` VARCHAR( 250 ) NOT NULL DEFAULT ''");
			$this->updateQuery("ALTER TABLE `#__acymailing_list` ADD `category` VARCHAR( 250 ) NOT NULL DEFAULT ''");
			$this->updateQuery("ALTER TABLE `#__acymailing_fields` ADD `access` VARCHAR( 250 ) NOT NULL DEFAULT 'all'");
			$this->updateQuery("ALTER TABLE `#__acymailing_fields` ADD `fieldcat` INT( 11 ) NOT NULL DEFAULT '0'");

			$this->updateQuery("UPDATE `#__acymailing_template` SET body = REPLACE(body,'<tbody>','<tbody class=\"acyeditor_sortable\">') WHERE body LIKE '%acyeditor_%' ");
		}

		if(version_compare($this->fromVersion, '4.9.1', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_geolocation` ADD KEY `geolocation_ip_created` (`geolocation_ip`, `geolocation_created`)");
		}

		if(version_compare($this->fromVersion, '4.9.3', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_userstats` ADD `bouncerule` VARCHAR( 255 ) NULL");
			$this->updateQuery("ALTER TABLE `#__acymailing_fields` ADD `listingfilter` TINYINT NULL DEFAULT NULL ");
			$this->updateQuery("ALTER TABLE `#__acymailing_fields` ADD `frontlistingfilter` TINYINT NULL DEFAULT NULL ");
		}

		if(version_compare($this->fromVersion, '4.9.4', '<')){
			$this->updateQuery("UPDATE #__acymailing_mail SET body = REPLACE(REPLACE(body, 'newsletter-4/top.png', 'newsletter-4/images/top.png'), 'newsletter-4/bottom.png', 'newsletter-4/images/bottom.png')");
		}

		if(version_compare($this->fromVersion, '5.0.0', '<')){
			$this->db->setQuery('SELECT mailid, attach FROM #__acymailing_mail WHERE attach IS NOT NULL');
			$mails = $this->db->loadObjectList();
			if(!empty($mails)){
				$query = 'INSERT INTO #__acymailing_mail (`mailid`,`attach`) VALUES ';
				$folderPath = acymailing_getFilesFolder();
				foreach($mails as $oneMail){
					$attachments = unserialize($oneMail->attach);
					foreach($attachments as &$oneAttach){
						if(strpos($oneAttach->filename, $folderPath) === false) $oneAttach->filename = $folderPath.'/'.$oneAttach->filename;
					}
					$query .= '('.$oneMail->mailid.','.$this->db->Quote(serialize($attachments)).'),';
				}
				$query = rtrim($query, ',');
				$query .= ' ON DUPLICATE KEY UPDATE `attach` = VALUES(`attach`)';
				$this->updateQuery($query);
			}
			$config = acymailing_config();
			$newConfig = new stdClass();
			$newConfig->css_backend = '';
			$config->save($newConfig);
		}

		if(version_compare($this->fromVersion, '5.0.1', '<')){
			$this->updateQuery("ALTER TABLE `#__acymailing_fields` ADD `frontform` TINYINT NULL DEFAULT 1");
			$this->updateQuery("UPDATE `#__acymailing_fields` SET frontform = backend");
		}
	}

	function updateQuery($query){
		try{
			$this->db->setQuery($query);
			$res = $this->db->query();
		} catch(Exception $e){
			$res = null;
		}
		if($res === null) acymailing_enqueueMessage(isset($e) ? $e->getMessage() : substr(strip_tags($this->db->getErrorMsg()), 0, 200).'...', 'error');
	}

	function updateJoomailing(){
		$this->db->setQuery("SHOW TABLES LIKE '".$this->db->getPrefix()."joomailing_config'");
		$result = $this->db->loadResult();

		if(empty($result)) return true;


		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_config` (`namekey`,`value`) SELECT `namekey`, REPLACE(`value`,'com_joomailing','com_acymailing') FROM `#__joomailing_config`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_list` (`name`, `description`, `ordering`, `listid`, `published`, `userid`, `alias`, `color`, `visible`, `welmailid`, `unsubmailid`, `type`) SELECT `name`, `description`, `ordering`, `listid`, `published`, `userid`, `alias`, `color`, `visible`, `welmailid`, `unsubmailid`, `type` FROM `#__joomailing_list`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_listcampaign` (`campaignid`, `listid`) SELECT `campaignid`, `listid` FROM `#__joomailing_listcampaign`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_listmail` (`listid`, `mailid`) SELECT `listid`, `mailid` FROM `#__joomailing_listmail`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_listsub` (`listid`, `subid`, `subdate`, `unsubdate`, `status`) SELECT `listid`, `subid`, `subdate`, `unsubdate`, `status` FROM `#__joomailing_listsub`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_mail` (`mailid`, `subject`, `body`, `altbody`, `published`, `senddate`, `created`, `fromname`, `fromemail`, `replyname`, `replyemail`, `type`, `visible`, `userid`, `alias`, `attach`, `html`, `tempid`, `key`, `frequency`, `params`) SELECT `mailid`, `subject`, REPLACE(`body`,'joomailing','acymailing'), REPLACE(`altbody`,'joomailing','acymailing'), `published`, `senddate`, `created`, `fromname`, `fromemail`, `replyname`, `replyemail`, `type`, `visible`, `userid`, `alias`, REPLACE(`attach`,'com_joomailing','com_acymailing'), `html`, `tempid`, `key`, `frequency`, REPLACE(`params`,'com_joomailing','com_acymailing') FROM `#__joomailing_mail`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_queue` (`senddate`, `subid`, `mailid`, `priority`, `try`) SELECT `senddate`, `subid`, `mailid`, `priority`, `try` FROM `#__joomailing_queue`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_stats` (`mailid`, `senthtml`, `senttext`, `senddate`, `openunique`, `opentotal`, `bounceunique`, `fail`, `clicktotal`, `clickunique`, `unsub`, `forward`) SELECT `mailid`, `senthtml`, `senttext`, `senddate`, `openunique`, `opentotal`, `bounceunique`, `fail`, `clicktotal`, `clickunique`, `unsub`, `forward` FROM `#__joomailing_stats`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_subscriber` (`subid`, `email`, `userid`, `name`, `created`, `confirmed`, `enabled`, `accept`, `ip`, `html`, `key`) SELECT `subid`, `email`, `userid`, `name`, `created`, `confirmed`, `enabled`, `accept`, `ip`, `html`, `key` FROM `#__joomailing_subscriber`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_template` (`tempid`, `name`, `description`, `body`, `altbody`, `created`, `published`, `premium`, `ordering`, `namekey`, `styles`) SELECT `tempid`, `name`, REPLACE(`description`,'joomailing','acymailing'), REPLACE(`body`,'joomailing','acymailing'), REPLACE(`altbody`,'joomailing','acymailing'), `created`, `published`, `premium`, `ordering`, `namekey`, REPLACE(`styles`,'joomailing','acymailing') FROM `#__joomailing_template`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_url` (`urlid`, `name`, `url`) SELECT `urlid`, REPLACE(`name`,'com_joomailing','com_acymailing'), REPLACE(`url`,'com_joomailing','com_acymailing') FROM `#__joomailing_url`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_urlclick` (`urlid`, `mailid`, `click`, `subid`, `date`) SELECT `urlid`, `mailid`, `click`, `subid`, `date` FROM `#__joomailing_urlclick`");
		$this->db->query();
		$this->db->setQuery("INSERT IGNORE INTO `#__acymailing_userstats` (`mailid`, `subid`, `html`, `sent`, `senddate`, `open`, `opendate`, `bounce`, `fail`) SELECT `mailid`, `subid`, `html`, `sent`, `senddate`, `open`, `opendate`, `bounce`, `fail` FROM `#__joomailing_userstats`");
		$this->db->query();

		$this->db->setQuery("DROP TABLE IF EXISTS `#__joomailing_config`, `#__joomailing_list`, `#__joomailing_listcampaign`, `#__joomailing_listmail`, `#__joomailing_listsub`, `#__joomailing_mail`, `#__joomailing_queue` , `#__joomailing_stats`, `#__joomailing_subscriber`, `#__joomailing_template` , `#__joomailing_url`, `#__joomailing_urlclick`, `#__joomailing_userstats`");
		$this->db->query();

		$this->db->setQuery("UPDATE `#__modules` SET `title` = REPLACE(`title`,'JooMailing','AcyMailing'), `module` = REPLACE(`module`,'joomailing','acymailing'), `params` = REPLACE(`params`,'joomailing','acymailing')");
		$this->db->query();
		$this->db->setQuery("UPDATE `#__plugins` SET `name` = REPLACE(REPLACE(REPLACE(`name`,'jooMailing','AcyMailing'),'joomailing','acymailing'),'JooMailing','AcyMailing'), `element` = REPLACE(`element`,'joomailing','acymailing'), `folder` = REPLACE(`folder`,'joomailing','acymailing'), `params` = REPLACE(`params`,'joomailing','acymailing')");
		$this->db->query();

		$this->db->setQuery("DELETE FROM `#__components` WHERE `option` LIKE '%joomailing%' OR `admin_menu_link` LIKE '%joomailing%'");
		$this->db->query();

		$this->db->setQuery("UPDATE `#__menu` SET `menutype` = REPLACE(`menutype`,'joomailing','acymailing'), `name` = REPLACE(`name`,'joomailing','acymailing'), `alias` = REPLACE(`alias`,'joomailing','acymailing'), `link` = REPLACE(`link`,'joomailing','acymailing')");
		$this->db->query();


		$newFile = '<?php
					$app = JFactory::getApplication();
					$url = \'index.php?option=com_acymailing\';
					foreach($_GET as $name => $value){
						if($name == \'option\') continue;
						$url .= \'&\'.$name.\'=\'.$value;
					}
					$app->redirect($url);
					';

		@file_put_contents(rtrim(JPATH_SITE, DS).DS.'components'.DS.'com_joomailing'.DS.'joomailing.php', $newFile);
		@file_put_contents(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.'com_joomailing'.DS.'admin.joomailing.php', $newFile);
	}

	function addPref(){
		$conf = JFactory::getConfig();

		$this->level = ucfirst($this->level);

		$allPref = array();

		$allPref['level'] = $this->level;
		$allPref['version'] = $this->version;
		$allPref['smtp_port'] = '';

		if(ACYMAILING_J30){
			$allPref['from_name'] = $conf->get('fromname');
			$allPref['from_email'] = $conf->get('mailfrom');
			$allPref['bounce_email'] = $conf->get('mailfrom');
			$allPref['mailer_method'] = $conf->get('mailer');
			$allPref['sendmail_path'] = $conf->get('sendmail');
			$smtpinfos = explode(':', $conf->get('smtphost'));
			$allPref['smtp_port'] = $conf->get('smtpport');
			$allPref['smtp_secured'] = $conf->get('smtpsecure');
			$allPref['smtp_auth'] = $conf->get('smtpauth');
			$allPref['smtp_username'] = $conf->get('smtpuser');
			$allPref['smtp_password'] = $conf->get('smtppass');
		}
		else{
			$allPref['from_name'] = $conf->getValue('config.fromname');
			$allPref['from_email'] = $conf->getValue('config.mailfrom');
			$allPref['bounce_email'] = $conf->getValue('config.mailfrom');
			$allPref['mailer_method'] = $conf->getValue('config.mailer');
			$allPref['sendmail_path'] = $conf->getValue('config.sendmail');
			$smtpinfos = explode(':', $conf->getValue('config.smtphost'));
			$allPref['smtp_secured'] = $conf->getValue('config.smtpsecure');
			$allPref['smtp_auth'] = $conf->getValue('config.smtpauth');
			$allPref['smtp_username'] = $conf->getValue('config.smtpuser');
			$allPref['smtp_password'] = $conf->getValue('config.smtppass');
		}

		$allPref['reply_name'] = $allPref['from_name'];
		$allPref['reply_email'] = $allPref['from_email'];
		$allPref['cron_sendto'] = $allPref['from_email'];

		$allPref['add_names'] = '1';
		$allPref['encoding_format'] = '8bit';
		$allPref['charset'] = 'UTF-8';
		$allPref['word_wrapping'] = '150';
		$allPref['hostname'] = '';
		$allPref['embed_images'] = '0';
		$allPref['embed_files'] = '1';
		$allPref['editor'] = 'acyeditor';
		$allPref['multiple_part'] = '1';
		$allPref['smtp_host'] = $smtpinfos[0];
		if(isset($smtpinfos[1])) $allPref['smtp_port'] = $smtpinfos[1];
		if(!in_array($allPref['smtp_secured'], array('tls', 'ssl'))) $allPref['smtp_secured'] = '';

		$allPref['queue_nbmail'] = '40';
		$allPref['queue_nbmail_auto'] = '70';
		$allPref['queue_type'] = 'auto';
		$allPref['queue_try'] = '3';
		$allPref['queue_pause'] = '120';
		$allPref['allow_visitor'] = '1';
		$allPref['require_confirmation'] = '0';
		$allPref['priority_newsletter'] = '3';
		$allPref['allowedfiles'] = 'zip,doc,docx,pdf,xls,txt,gzip,rar,jpg,jpeg,gif,xlsx,pps,csv,bmp,ico,odg,odp,ods,odt,png,ppt,swf,xcf,mp3,wma';
		$allPref['uploadfolder'] = 'media/com_acymailing/upload';
		$allPref['confirm_redirect'] = '';
		$allPref['subscription_message'] = '1';
		$allPref['notification_unsuball'] = '';
		$allPref['cron_next'] = '1251990901';
		$allPref['confirmation_message'] = '1';
		$allPref['welcome_message'] = '1';
		$allPref['unsub_message'] = '1';
		$allPref['cron_last'] = '0';
		$allPref['cron_fromip'] = '';
		$allPref['cron_report'] = '';
		$allPref['cron_frequency'] = '900';
		$allPref['cron_sendreport'] = '2';

		$allPref['cron_fullreport'] = '1';
		$allPref['cron_savereport'] = '2';
		$allPref['cron_savepath'] = 'media/com_acymailing/logs/report'.rand(0, 999999999).'.log';
		$allPref['notification_created'] = '';
		$allPref['notification_accept'] = '';
		$allPref['notification_refuse'] = '';
		$allPref['forward'] = '0';

		$descriptions = array('Joomla!® Newsletter Extension', 'Joomla!® Mailing Extension', 'Joomla!® Newsletter System', 'Joomla!® E-mail Marketing', 'Joomla!® Marketing Campaign');
		$allPref['description_starter'] = $descriptions[rand(0, 4)];
		$allPref['description_essential'] = $descriptions[rand(0, 4)];
		$allPref['description_business'] = $descriptions[rand(0, 4)];
		$allPref['description_enterprise'] = $descriptions[rand(0, 4)];

		$allPref['priority_followup'] = '2';
		$allPref['unsub_redirect'] = '';
		$allPref['use_sef'] = '0';
		$allPref['itemid'] = '0';
		$allPref['css_module'] = 'default';
		$allPref['css_frontend'] = 'default';
		$allPref['css_backend'] = '';
		$allPref['bootstrap_frontend'] = 0;

		$allPref['unsub_reasons'] = serialize(array('UNSUB_SURVEY_FREQUENT', 'UNSUB_SURVEY_RELEVANT'));

		$allPref['security_key'] = acymailing_generateKey(30);

		$app = JFactory::getApplication();

		$allPref['installcomplete'] = '0';

		$allPref['Starter'] = '0';
		$allPref['Essential'] = '1';
		$allPref['Business'] = '2';
		$allPref['Enterprise'] = '3';

		$query = "INSERT IGNORE INTO `#__acymailing_config` (`namekey`,`value`) VALUES ";
		foreach($allPref as $namekey => $value){
			$query .= '('.$this->db->Quote($namekey).','.$this->db->Quote($value).'),';
		}
		$query = rtrim($query, ',');

		$this->db->setQuery($query);
		try{
			$res = $this->db->query();
		} catch(Exception $e){
			$res = null;
		}
		if($res === null){
			acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags($this->db->getErrorMsg()), 0, 200).'...', 'error');
			return false;
		}
		return true;
	}
}

class acymailingUninstall{
	var $db;

	function acymailingUninstall(){
		$this->db = JFactory::getDBO();
	}

	function message(){
		?>
		You uninstalled the AcyMailing component.<br/>
		AcyMailing also unpublished the modules attached to the component.<br/><br/>
		If you want to completely uninstall AcyMailing, please select all the AcyMailing modules and plugins and uninstall them from the Joomla Extensions Manager.<br/>
		Then execute this query via phpMyAdmin to remove all AcyMailing data:<br/><br/>
		DROP TABLE <?php
		$this->db->setQuery("SHOW TABLES LIKE '".$this->db->getPrefix()."acymailing%' ");
		if(version_compare(JVERSION, '3.0.0', '>=')){
			echo implode(' , ', $this->db->loadColumn());
		}
		else{
			echo implode(' , ', $this->db->loadResultArray());
		}

		?>;<br/><br/>
		If you DO NOT execute the query, you will be able to install AcyMailing again without losing data.<br/>
		Please note that you don't have to uninstall AcyMailing to install a new version, simply install the new one without uninstalling your current version.
		<?php
	}

	function unpublishModules(){
		$this->db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%acymailing%'");
		$this->db->query();
	}
}
