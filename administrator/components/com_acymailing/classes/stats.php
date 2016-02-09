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

class statsClass extends acymailingClass{

	var $tables = array('urlclick','userstats','stats');
	var $pkey = 'mailid';

	var $countReturn = true;

	var $subid = 0;
	var $mailid = 0;


	function saveStats(){
		$subid = empty($this->subid) ? JRequest::getInt('subid') : $this->subid;
		$mailid = empty($this->mailid) ? JRequest::getInt('mailid') : $this->mailid;
		if(empty($subid) OR empty($mailid)) return false;
		if(acymailing_isRobot()) return false;

		$db = JFactory::getDBO();
		$db->setQuery('SELECT `open` FROM '.acymailing_table('userstats').' WHERE `mailid` = '.intval($mailid).' AND `subid` = '.intval($subid).' LIMIT 1');
		$actual = $db->loadObject();
		if(empty($actual)) return false;

		$userHelper = acymailing_get('helper.user');
		$db->setQuery('UPDATE #__acymailing_subscriber SET `lastopen_date` = '.time().', `lastopen_ip` = '.$db->Quote($userHelper->getIP()).' WHERE `subid` = '.intval($subid));
		try{
			$results = $db->query();
		}catch(Exception $e){
			$results = null;
		}
		if($results === null){
			acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags($db->getErrorMsg()),0,200).'...','error');
			exit;
		}

		$open = 0;

		if(empty($actual->open)){
			$open = 1;
			$unique = ',openunique = openunique +1';
		}elseif($this->countReturn){
			$open = $actual->open +1;
			$unique = '';
		}

		if(empty($open)) return true;

		$ipClass = acymailing_get('helper.user');
		$ip = $ipClass->getIP();

		$db->setQuery('UPDATE '.acymailing_table('userstats').' SET open = '.$open.', opendate = '.time().', `ip`= '.$db->Quote($ip).' WHERE mailid = '.$mailid.' AND subid = '.$subid.' LIMIT 1');
		try{
			$results = $db->query();
		}catch(Exception $e){
			$results = null;
		}
		if($results === null){
			acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags($db->getErrorMsg()),0,200).'...','error');
			exit;
		}

		$browsers = array("abrowse", "abolimba", "3ds", "acoo browser", "alienforce", "amaya", "amigavoyager", "antfresco", "aol", "arora", "avant", "baidubrowser", "beamrise", "beonex", "blackbird", "blackhawk", "bolt", "browsex", "browzar", "bunjalloo", "camino", "charon", "chromium", "columbus", "cometbird", "dragon", "conkeror", "coolnovo", "corom", "deepnet explorer", "demeter", "deskbrowse", "dillo", "dooble", "dplus", "edbrowse", "element browser", "elinks", "epic", "epiphany", "firebird", "flock", "fluid", "galeon", "globalmojo", "greenbrowser", "hotjava", "hv3", "hydra", "ibrowse", "icab", "icebrowser", "iceape", "icecat", "icedragon", "iceweasel", "surfboard", "irider", "iron", "meleon", "ninja", "kapiko", "kazehakase", "strata", "kkman", "konqueror", "kylo", "lbrowser", "links", "lobo", "lolifox", "lunascape", "lynx", "maxthon", "midori", "minibrowser", "mosaic", "multizilla", "myibrow", "netcaptor", "netpositive", "netscape", "navigator", "netsurf", "nintendobrowser", "offbyone", "omniweb", "orca", "oregano", "otter", "palemoon", "patriott", "perk", "phaseout", "phoenix", "polarity", "playstation 4", "qtweb internet browser", "qupzilla", "rekonq", "retawq", "roccat", "rockmelt", "ryouko", "saayaa", "seamonkey", "shiira", "sitekiosk", "skipstone", "sleipnir", "slimboat", "slimbrowser", "metasr", "stainless", "sundance", "sundial", "sunrise", "superbird", "surf", "swiftweasel", "tenfourfox", "theworld", "tjusig", "tencenttraveler", "ultrabrowser", "usejump", "uzbl", "vonkeror", "v3m", "webianshell", "webrender", "weltweitimnetzbrowser", "whitehat aviator", "wkiosk", "worldwideweb", "wyzo", "smiles", "yabrowser", "yrcweblink", "zbrowser", "zipzap", "firefox", "msie", "opera", "chrome", "safari", "thunderbird", "outlook", "airmail", "barca", "eudora", "gcmail", "lotus", "pocomail", "postbox", "shredder", "sparrow", "spicebird", "bat!", "tizenbrowser", "mozilla", "gecko",);
		$name = "unknown";
		$version = "";

		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		}else{
			$agent = "unknown";
		}
		foreach($browsers as $oneBrowser){
			if(preg_match("#($oneBrowser)[/ ]?([0-9]*)#", $agent, $match)) {
				$name = $match[1];
				$version = $match[2];
				break;
			}
		}

		$isMobile = 0;
		$osName = '';
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/',$agent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($agent,0,4))){
			$isMobile = 1;
			$osName = "unknown";
			$mobileOs = array("bada"=>"Bada","ubuntu; mobile"=>"Ubuntu","ubuntu; tablet"=>"Ubuntu","tizen"=>"Tizen","palm os"=>"Palm","meego"=>"meeGo","symbian"=>"Symbian","symbos"=>"Symbian","blackberry"=>"BlackBerry","windows ce"=>"Windows Phone","windows mobile"=>"Windows Phone","windows phone"=>"Windows Phone","iphone"=>"iOS","ipad"=>"iOS","ipod"=>"iOS","android"=>"Android");
			$mobileOsKeys = array_keys($mobileOs);
			foreach($mobileOsKeys as $oneMobileOsKey){
				if(preg_match("/($oneMobileOsKey)/",$agent,$match2)){
					$osName = $mobileOs[$match2[1]];
					break;
				}
			}
		}

		$db->setQuery('UPDATE '.acymailing_table('userstats').' SET `is_mobile` = '.intval($isMobile).', `mobile_os` = '.$db->Quote($osName).', `browser` = '.$db->Quote($name).', browser_version = '.intval($version).', user_agent = '.$db->Quote($agent).' WHERE mailid = '.$mailid.' AND subid = '.$subid.' LIMIT 1');
		try{
			$results = $db->query();
		}catch(Exception $e){
			$results = null;
		}
		if($results === null){
			acymailing_display(isset($e) ? $e->getMessage() : substr(strip_tags($db->getErrorMsg()),0,200).'...','error');
			exit;
		}

		$db->setQuery('UPDATE '.acymailing_table('stats').' SET opentotal = opentotal +1 '.$unique.' WHERE mailid = '.$mailid.' LIMIT 1');
		$db->query();

		if(!empty($subid)){
			$filterClass = acymailing_get('class.filter');
			$filterClass->subid = $subid;
			$filterClass->trigger('opennews');
		}

		$classGeoloc = acymailing_get('class.geolocation');
		$classGeoloc->saveGeolocation('open', $subid);

		JPluginHelper::importPlugin('acymailing');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onAcyOpenMail',array($subid,$mailid));

		return true;
	}

}
