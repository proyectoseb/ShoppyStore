<?php
/** 
 * YouTech utilities script file.
 * 
 * @author The YouTech JSC
 * @package menusys
 * @filesource ytdebuger.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */

if (!function_exists('p')){
	define('YTDEBUG', 1);
	function p($var, $usedie=null){	
		if(!defined('YTDEBUG') || YTDEBUG==0) return false;
		if (isset($usedie) && !$usedie) return false;
		echo "<pre>";
		switch(gettype($var)){
			case 'number':
			case 'string': echo "$var<br>"; break;
			case 'array': print_r($var); break;
			default: 
				var_dump($var);
		}
		echo "</pre>";
		if (isset($usedie) && $usedie) die();
	}
}