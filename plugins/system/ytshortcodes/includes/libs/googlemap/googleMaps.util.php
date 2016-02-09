<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted access');

function get_paths($path_base) {
	$googleMaps_lib = $path_base.'/googleMaps/googleMaps.lib.php';
	$googleDirections_lib = $path_base.'/googleDirections/googleDirections.lib.php';
	$googleDirections_tohere_lib = $path_base.'/googleDirections_tohere/googleDirections_tohere.lib.php';
	return array($googleMaps_lib, $googleDirections_lib, $googleDirections_tohere_lib);
}

function get_googleMaps_ver($path_base) {
	$ver = '';
	$ver_file = $path_base . '/googleMaps/googleMaps.ver';
	if (file_exists ( $ver_file )) $ver = file_get_contents ( $ver_file );
	return $ver;
}

function get_googleDirections_ver($path_base) {
	$ver = '';
	$ver_file = $path_base . '/googleDirections/googleDirections.ver';
	if (file_exists ( $ver_file )) $ver = file_get_contents ( $ver_file );
	return $ver;
}

function error_msg2($required_url, $msg, $plugin) {
	print "<p style=\"background:#ffff00;padding-bottom:20px;padding-top:20px;padding-left:10px;padding-right:10px;\"><b>ERROR >>> </b>You need to install the <a href=\"http://www.kksou.com/php-gtk2/Joomla-Gadgets/{$required_url}.php#download\"><b>$msg</b></a> for the $plugin plugin to work.</p>";
}

function googleMaps_ver_ok($googleMaps_ver) {
	if (! isset ( $googleMaps_ver ) || $googleMaps_ver < '010718') { // version 1.7.18
		error_msg2('googleMaps-plugin.php', 'latest version of googleMaps plugin', 'googleDirections');
		return 0;
	} else {
		return 1;
	}
}

function googleDirections_ver_ok($googleDirections_ver) {
	if (! isset ( $googleDirections_ver ) || $googleDirections_ver < '010712') { // version 1.7.12
		error_msg2('googleDirections.php', 'latest version of googleDirections plugin', 'googleDirections_tohere');
		return 0;
	} else {
		return 1;
	}
}