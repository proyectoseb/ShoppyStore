<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die;

$joomlaVersion = new JVersion(); 
$currentVersion = $joomlaVersion->getShortVersion(); 
$versionParts = explode('.', $currentVersion); 
$jversion = implode('', array_slice($versionParts, 0, 1));

define('J_VERSION', $jversion);
if(J_VERSION >= 3){
	define('J_SEPARATOR', '/');
}elseif(J_VERSION <= 2){
	define('J_SEPARATOR', DS);
}
define('YT_FRAMEWORK', 1);
define ('YT_PLUGIN', 'yt');
define ('YT_PLUGIN_URL', JURI::root(true).'/plugins/system/'.YT_PLUGIN);
define ('YT_PLUGIN_REL_URL', 'plugins/system/'.YT_PLUGIN);
define ('YT_INCLUDES_PATH', dirname(dirname(dirname(__FILE__))).J_SEPARATOR.'includes'.J_SEPARATOR);
?>