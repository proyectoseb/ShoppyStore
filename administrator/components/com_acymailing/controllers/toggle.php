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

class ToggleController extends acymailingController{

	var $allowedTablesColumn = array();
	var $deleteColumns = array();

	function __construct($config = array()){
		parent::__construct($config);
		$this->registerDefaultTask('toggle');
		$this->allowedTablesColumn['list'] = array('published' => 'listid', 'visible' => 'listid');
		$this->allowedTablesColumn['subscriber'] = array('confirmed' => 'subid', 'html' => 'subid', 'enabled' => 'subid');
		$this->allowedTablesColumn['template'] = array('published' => 'tempid', 'premium' => 'tempid');
		$this->allowedTablesColumn['mail'] = array('published' => 'mailid', 'visible' => 'mailid');
		$this->allowedTablesColumn['listsub'] = array('status' => 'listid,subid');
		$this->allowedTablesColumn['plugins'] = array('published' => 'id');
		$this->allowedTablesColumn['followup'] = array('add' => 'mailid', 'addall' => 'mailid', 'update' => 'mailid');
		$this->allowedTablesColumn['rules'] = array('published' => 'ruleid');
		$this->allowedTablesColumn['filter'] = array('published' => 'filid');
		$this->allowedTablesColumn['fields'] = array('published' => 'fieldid', 'required' => 'fieldid', 'frontcomp' => 'fieldid', 'backend' => 'fieldid', 'listing' => 'fieldid', 'frontlisting' => 'fieldid', 'frontjoomlaregistration' => 'fieldid', 'frontjoomlaprofile' => 'fieldid', 'joomlaprofile' => 'fieldid', 'frontform' => 'fieldid');
		$this->allowedTablesColumn['config'] = array('addindex' => 'namekey', 'guessport' => 'port');
		$this->deleteColumns['queue'] = array('subid', 'mailid');
		$this->deleteColumns['filter'] = array('filid', 'filid');
		$this->deleteColumns['rules'] = array('ruleid', 'ruleid');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
	}

	function toggle(){
		$completeTask = JRequest::getCmd('task');
		$task = substr($completeTask, 0, strpos($completeTask, '_'));
		$elementId = substr($completeTask, strpos($completeTask, '_') + 1);

		$value = JRequest::getVar('value', '0', '', 'int');
		$table = JRequest::getVar('table', '', '', 'word');

		$pkey = $this->allowedTablesColumn[$table][$task];
		if(empty($pkey)) exit;

		$function = $table.$task;
		if(method_exists($this, $function)){
			$this->$function($elementId, $value);
		}else{
			$db = JFactory::getDBO();
			$db->setQuery('UPDATE '.acymailing_table($table).' SET '.$task.' = '.$value.' WHERE '.$pkey.' = '.intval($elementId).' LIMIT 1');
			$db->query();
		}

		$toggleClass = acymailing_get('helper.toggle');
		$extra = JRequest::getVar('extra', array(), '', 'array');
		if(!empty($extra)){
			foreach($extra as $key => $val){
				$extra[$key] = urldecode($val);
			}
		}
		echo $toggleClass->toggle(JRequest::getCmd('task', ''), $value, $table, $extra);
		exit;
	}

	function configguessport($port, $value){
		if(!function_exists('fsockopen')){
			echo '<span style="color:red">fsockopen is not enabled, please contact your hosting company to enable it</span>';
			exit;
		}

		$tests = array(25 => 'smtp.sendgrid.com', 2525 => 'smtp.sendgrid.com', 587 => 'smtp.sendgrid.com', 465 => 'ssl://smtp.sendgrid.com');
		$total = 0;
		foreach($tests as $port => $server){
			$fp = @fsockopen($server, $port, $errno, $errstr, 5);
			if($fp){
				echo '<br /><span style="color:green" >Port <b>'.$port.'</b> OK</span>';
				fclose($fp);
				$total++;
			}else{
				echo '<br /><span style="color:red" >Port <b>'.$port.'</b> not opened on your server ';
				echo " errornum: ".$errno.' : '.$errstr;
				echo '</span>';
			}
		}
		if(empty($total)){
		}

		exit;
	}

	function testApiKey(){
		$apiKey = JRequest::getString('value', '');
		if(empty($apiKey)){
			echo '<span style="color:red">No API key</span><br />';
			exit;
		}

		$classGeoloc = acymailing_get('class.geolocation');
		$test = $classGeoloc->testApiKey($apiKey);

		if(!empty($test) && $test->statusCode == 'OK'){ // Works fine
			echo '<span style="color:green" >API key OK : '.$test->countryName.' - '.$test->cityName.'</span>';
		}else if(!empty($test) && $test->statusCode == 'noReturn'){ // No return from the API, displaying the IP used for test and errors if there are any
			echo '<span style="color:red" >Error calling IPInfoDB API with IP : '.$test->ip.'</span><br />';
			if(!empty($test->errorAPI)) echo '<span style="color:red" >Details : '.$test->errorAPI.'</span>';
		}else{ // There is a return from the API but with an error status: display the content received to identify the pb
			echo '<span style="color:red" >Error returned from the API:<br /><br />';
			foreach($test as $key => $value){
				echo $key.' : '.$value.'<br />';
			}
			echo '</span>';
		}
		exit;
	}


	function configaddindex($table, $value){
		$queries = array();
		$queries['listsub'] = array('ALTER TABLE `#__acymailing_listsub` ADD INDEX `subidindex` ( `subid` )');
		$queries['listsub'][] = 'ALTER TABLE `#__acymailing_listsub` ADD INDEX `listidstatusindex` ( `listid` , `status` )';

		$queries['stats'] = array('ALTER TABLE `#__acymailing_stats` ADD INDEX `senddateindex` ( `senddate` )');

		$queries['list'] = array('ALTER TABLE `#__acymailing_list` ADD INDEX `typeorderingindex` ( `type` , `ordering` ) ');
		$queries['list'][] = 'ALTER TABLE `#__acymailing_list` ADD INDEX `useridindex` ( `userid` ) ';
		$queries['list'][] = 'ALTER TABLE `#__acymailing_list` ADD INDEX `typeuseridindex` ( `type` , `userid` ) ';

		$queries['mail'] = array('ALTER TABLE `#__acymailing_mail` ADD INDEX `typemailidindex` ( `type` , `mailid` )');
		$queries['mail'][] = 'ALTER TABLE `#__acymailing_mail` ADD INDEX `useridindex` ( `userid` )';

		$queries['userstats'] = array('ALTER TABLE `#__acymailing_userstats` ADD INDEX `senddateindex` ( `senddate` )');
		$queries['userstats'][] = 'ALTER TABLE `#__acymailing_userstats` ADD INDEX `subidindex` ( `subid` )';

		$queries['urlclick'] = array('ALTER TABLE `#__acymailing_urlclick` ADD INDEX `dateindex` ( `date` )');
		$queries['urlclick'][] = 'ALTER TABLE `#__acymailing_urlclick` ADD INDEX `mailidindex` ( `mailid` )';
		$queries['urlclick'][] = 'ALTER TABLE `#__acymailing_urlclick` ADD INDEX `subidindex` ( `subid` ) ';

		$queries['history'] = array('ALTER TABLE `#__acymailing_history` ADD INDEX `dateindex` ( `date` )');
		$queries['history'][] = 'ALTER TABLE `#__acymailing_history` ADD INDEX `actionindex` ( `action` , `mailid` ) ';

		$queries['template'] = array('ALTER TABLE `#__acymailing_template` ADD INDEX `orderingindex` ( `ordering` )');

		$queries['queue'] = array('ALTER TABLE `#__acymailing_queue` ADD INDEX `orderingindex` ( `priority` , `senddate` , `subid` )');
		$queries['queue'][] = 'ALTER TABLE `#__acymailing_queue` ADD INDEX `listingindex` ( `senddate` , `subid` )';
		$queries['queue'][] = 'ALTER TABLE `#__acymailing_queue` ADD INDEX `mailidindex` ( `mailid` )';

		$queries['subscriber'] = array('ALTER TABLE `#__acymailing_subscriber` ADD INDEX `queueindex` ( `enabled` , `accept` , `confirmed` )');

		if(empty($queries[$table])){
			echo 'No optimization found...';
			exit;
		}

		$db = JFactory::getDBO();
		$indexOk = 0;
		echo '<span style="color:purple">| ';
		foreach($queries[$table] as $oneQuery){
			$db->setQuery($oneQuery);

			try{
				$isError = $db->query();
			}catch(Exception $e){
				$isError = null;
			}
			if($isError == null){
				echo isset($e) ? $e->getMessage() : substr(strip_tags($db->getErrorMsg()), 0, 200).'...';
			}else{
				$indexOk++;
			}
		}
		if(!empty($indexOk)) echo $indexOk.' indexes added | ';
		echo '</span>';

		$config = acymailing_config();
		$newConfig = new stdClass();
		$val = 'optimize_'.$table;
		$newConfig->$val = 1;
		$config->save($newConfig);

		exit;
	}

	function followupaddall($mailid, $value){
		$mailClass = acymailing_get('class.mail');
		$nbinserted = $mailClass->addFollowUpQueue($mailid, true);
		if($nbinserted !== false){
			echo JText::sprintf('ADDED_QUEUE', $nbinserted);
		}else{
			echo implode(',', $mailClass->errors);
		}
		exit;
	}

	function followupadd($mailid, $value){
		$mailClass = acymailing_get('class.mail');
		$nbinserted = $mailClass->addFollowUpQueue($mailid, false);
		if($nbinserted !== false){
			echo JText::sprintf('ADDED_QUEUE', $nbinserted);
		}else{
			echo implode(',', $mailClass->errors);
		}
		exit;
	}

	function followupupdate($mailid, $value){
		$mailClass = acymailing_get('class.mail');
		$followup = $mailClass->get($mailid);
		if(empty($followup->mailid)){
			echo 'Could not load mailid '.$mailid;
			exit;
		}

		$listmailClass = acymailing_get('class.listmail');
		$mycampaign = $listmailClass->getCampaign($followup->mailid);
		if(empty($mycampaign->listid)){
			echo 'Could not get the attached campaign';
			exit;
		}

		$db = JFactory::getDBO();
		$query = 'UPDATE #__acymailing_queue as a ';
		$query .= 'LEFT JOIN #__acymailing_listsub as b ON a.subid = b.subid AND b.listid = '.$mycampaign->listid;
		$query .= ' SET a.`senddate` = b.`subdate` + '.$followup->senddate;
		$query .= ' WHERE a.mailid = '.$followup->mailid;
		$db->setQuery($query);
		$db->query();
		$nbupdated = $db->getAffectedRows();

		if(!empty($nbupdated)){
			$campaignHelper = acymailing_get('helper.campaign');
			$campaignHelper->updateUnsubdate($mycampaign->listid, $followup->senddate);
		}

		echo JText::sprintf('NB_EMAILS_UPDATED', $nbupdated);
		exit;
	}


	function delete(){
		list($value1, $value2) = explode('_', JRequest::getCmd('value'));
		$table = JRequest::getVar('table', '', '', 'word');
		if(empty($table)) exit;

		$function = 'delete'.$table;
		if(method_exists($this, $function)){
			$this->$function($value1, $value2);
			exit;
		}

		list($key1, $key2) = $this->deleteColumns[$table];

		if(empty($key1) OR empty($key2) OR empty($value1) OR empty($value2)) exit;

		$db = JFactory::getDBO();
		$db->setQuery('DELETE FROM '.acymailing_table($table).' WHERE '.$key1.' = '.intval($value1).' AND '.$key2.' = '.intval($value2));
		$db->query();

		exit;
	}

	function deleteconfig($namekey, $val){
		$config = acymailing_config();
		$newConfig = new stdClass();
		$newConfig->$namekey = $val;
		$config->save($newConfig);
	}

	function deletefollowup($campaignid, $mailid){
		$mailClass = acymailing_get('class.mail');
		$mailClass->delete((int)$mailid);
	}

	function deleteMail($mailid, $attachid){
		$mailid = intval($mailid);
		if(empty($mailid)) return false;

		$db = JFactory::getDBO();
		$db->setQuery('SELECT attach FROM '.acymailing_table('mail').' WHERE mailid = '.$mailid.' LIMIT 1');
		$attachment = $db->loadResult();
		if(empty($attachment)) return;
		$attach = unserialize($attachment);

		unset($attach[$attachid]);
		$attachdb = serialize($attach);

		$db->setQuery('UPDATE '.acymailing_table('mail').' SET attach = '.$db->Quote($attachdb).' WHERE mailid = '.$mailid.' LIMIT 1');

		return $db->query();
	}

	function subscriberconfirmed($subid, $value){

		if(!empty($value)){
			$subscriberClass = acymailing_get('class.subscriber');
			$subscriberClass->confirmSubscription($subid);
		}else{
			$db = JFactory::getDBO();
			$db->setQuery('UPDATE '.acymailing_table('subscriber').' SET confirmed = '.$value.' WHERE subid = '.intval($subid).' LIMIT 1');
			$db->query();
		}
	}

	function listsubstatus($ids, $status){

		list($listid, $subid) = explode('_', $ids);
		$listid = (int)$listid;
		$subid = (int)$subid;

		if(empty($subid) OR empty($listid)) exit;
		$listSubClass = acymailing_get('class.listsub');
		$lists = array();
		$lists[$status] = array($listid);
		if($listSubClass->updateSubscription($subid, $lists)) return;

		echo 'error while updating the subscription';
	}

	function pluginspublished($id, $publish){

		$db = JFactory::getDBO();
		if(!ACYMAILING_J16){
			$db->setQuery('UPDATE '.acymailing_table('plugins', false).' SET `published` = '.intval($publish).' WHERE `id` = '.intval($id).' AND (`folder` = \'acymailing\' OR `name` LIKE \'%acymailing%\' OR `element` LIKE \'%acymailing%\') LIMIT 1');
		}else{
			$db->setQuery('UPDATE `#__extensions` SET `enabled` = '.intval($publish).' WHERE `extension_id` = '.intval($id).' AND (`folder` = \'acymailing\' OR `name` LIKE \'%acymailing%\' OR `element` LIKE \'%acymailing%\') LIMIT 1');
		}
		$db->query();

		$updateHelper = acymailing_get('helper.update');
		$updateHelper->cleanPluginCache();
	}
}
