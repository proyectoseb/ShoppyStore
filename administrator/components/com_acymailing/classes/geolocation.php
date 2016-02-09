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

class geolocationClass extends acymailingClass{
	var $tables = array('geolocation');
	var $pkey = 'geolocation_id';

	function saveGeolocation($geoloc_action, $subid){
		$config = acymailing_config();
		$geoloc_config = $config->get('geolocation');
		if(stripos($geoloc_config, $geoloc_action) === false) return false;

		$geo_element = new stdClass();
		$geo_element->geolocation_subid = $subid;
		$geo_element->geolocation_type = $geoloc_action;

		$userHelper = acymailing_get('helper.user');
		$geo_element->geolocation_ip = $userHelper->getIP();
		if(empty($geo_element->geolocation_subid) || empty($geo_element->geolocation_ip)) return false;

		$geo_element = $this->getIpLocation($geo_element);
		if($geo_element!= false){
			parent::save($geo_element);
			return $geo_element;
		} else{
			return false;
		}
	}

	function getIpLocation($element){
		$oldElement = $this->getMostRecentDataByIp($element->geolocation_ip);
		if(!empty($oldElement) && (time() - $oldElement->geolocation_created < 2592000)){
			$element->geolocation_latitude = $oldElement->geolocation_latitude;
			$element->geolocation_longitude = $oldElement->geolocation_longitude;
			$element->geolocation_postal_code = $oldElement->geolocation_postal_code;
			$element->geolocation_country = $oldElement->geolocation_country;
			$element->geolocation_country_code = $oldElement->geolocation_country_code;
			$element->geolocation_state = $oldElement->geolocation_state;
			$element->geolocation_state_code = $oldElement->geolocation_state_code;
			$element->geolocation_city = $oldElement->geolocation_city;
			$element->geolocation_created = time();
			return $element;
		}

		$geoClass = acymailing_get('inc.ipinfodb');

		$config = acymailing_config();
		$api_key = trim($config->get('geoloc_api_key', ''));
		if($api_key == '') return false;
		$geoClass->setKey($api_key);
		$location = $geoClass->getCity($element->geolocation_ip);
		$errorLoc = $geoClass->getError();

		if(empty($errorLoc) && !empty($location) && !empty($location->countryCode) && $location->countryCode != '-'){
			$element->geolocation_latitude = (!empty($location->latitude)?$location->latitude:0);
			$element->geolocation_longitude = (!empty($location->longitude)?$location->longitude:0);
			$element->geolocation_postal_code = (!empty($location->zipCode)?$location->zipCode:'');
			$element->geolocation_country = (!empty($location->countryName)?ucwords(strtolower($location->countryName)):'');
			$element->geolocation_country_code = (!empty($location->countryCode)?$location->countryCode:'');
			$element->geolocation_state = (!empty($location->regionName)?$location->regionName:'');
			$element->geolocation_state_code = (!empty($location->regioncode)?$location->regioncode:'');
			$element->geolocation_city = (!empty($location->cityName)?ucwords(strtolower($location->cityName)):'');
			$element->geolocation_created = time();
			return $element;
		} else{
			return false;
		}
	}

	function getMostRecentDataByIp($ip){
		$this->database->setQuery("SELECT * FROM #__acymailing_geolocation WHERE geolocation_ip=".$this->database->Quote($ip)." ORDER BY geolocation_created DESC");
		return $this->database->loadObject();
	}

	function testApiKey($apiKey){
		$geoClass = acymailing_get('inc.ipinfodb');
		$geoClass->setKey(trim($apiKey));

		$userHelper = acymailing_get('helper.user');
		$ipUser = $userHelper->getIP();
		$test = $geoClass->getCity($ipUser);
		$errorLoc = $geoClass->getError();

		 if(!empty($test)){ // Has a return from the API
		 	return $test;
		 }else{ // No return, we will display the IP used when calling API
		 	$retourError = new stdClass();
		 	$retourError->statusCode = 'noReturn';
		 	$retourError->ip = $ipUser;
		 	if(!empty($errorLoc)) $retourError->errorAPI = $errorLoc;
		 	return $retourError;	
		 }
	}

}
