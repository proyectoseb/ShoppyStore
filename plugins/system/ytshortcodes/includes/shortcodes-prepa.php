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
defined( '_JEXEC' ) or die( 'Restricted access' );
JLoader::register('ImageHelper', dirname(__FILE__).'/helper_image.php');
foreach (array_filter(glob(dirname(dirname(__FILE__)).'/shortcodes/*'), 'is_dir') as $directory_name) {
	$shortcode_tag = basename($directory_name);
	if($shortcode_tag == 'google_map') continue;
	$core_shortcode = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'shortcodes'.DIRECTORY_SEPARATOR.$shortcode_tag.DIRECTORY_SEPARATOR.'shortcode.php';
	require_once $core_shortcode;
	add_ytshortcode('yt_'.$shortcode_tag,$shortcode_tag.'YTShortcode');
}

?>
