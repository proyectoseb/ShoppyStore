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

class ytAsset {

	protected static $asset = array();
	
	function __construct() {
	}

	public static function yt_addFile($type, $file, $shortcode=NULL) {
		return self::yt_filePath($type, $file, $shortcode);
	}

	
	public static function yt_coreURI($shortcode) {
		$yt_corePath = YT_SC_URI.'/shortcodes/'.$shortcode;
		return $yt_corePath;
	

	
	public static function yt_corePath($shortcode) {
		$yt_corePath = YT_SC_ROOT.DIRECTORY_SEPARATOR.'shortcodes'.DIRECTORY_SEPARATOR.$shortcode;
		return $yt_corePath;
	}

	public static function yt_filePath($type, $file, $shortcode) {
		
		$coreFile = self::yt_corePath($shortcode).DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$file;
		$commonFile = YT_SC_ROOT.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$file;
		if (file_exists($coreFile)) {
			$assetFile = self::yt_coreURI($shortcode);
		} elseif (file_exists($commonFile)) {
			$assetFile = YT_SC_URI;
		}

		if (isset($assetFile)) {
			if ($type==='css') {
				$cssFile = $assetFile.'/css/'.$file;
				if (@self::$asset['css'][$file]) {
				    return;
				}
				if (@$_REQUEST["action"] == 'yt_shortcode_live_preview') {
					self::$asset['css'][$file] = 1;
					echo '<link rel="stylesheet" href="'.$cssFile.'" type="text/css" />';
        			return;
				} 
				else {
					self::$asset['css'][$file] = 1;
					JFactory::getDocument()->addStyleSheet($cssFile);
					return;
				}
			} 
			elseif ($type==='js') {
				$jsFile = $assetFile.'/js/'.$file;
				if (@self::$asset['js'][$file]) {
				    return;
				}
				if (@$_REQUEST["action"] == 'su_generator_preview') {
					self::$asset['js'][$file] = 1;
					echo '<script src="'.$jsFile.'" type="text/javascript"></script>';
					return;
				} 
				else {
					self::$asset['js'][$file] = 1;
					JFactory::getDocument()->addScript($jsFile);
					return;
				}
			}
		}
	}

	public static function yt_addString($type, $string) {
		if ($type === 'css') {
			$style = $string;
			$css_dcheck = md5($style);
			if (@self::$asset['css'][$css_dcheck]) {
			    return;
			}
			if (@$_REQUEST["action"] == 'su_generator_preview') {
				self::$asset['css'][$css_dcheck] = 1;
				$css_output = '<style type="text/css">'.$style.'</style>';
		        echo $css_output;
    			return;
			} else {
				self::$asset['css'][$css_dcheck] = 1;
				JFactory::getDocument()->addStyleDeclaration($style);
			}
		} elseif ($type === 'js') {
			$script = $string;
			$js_dcheck = md5($script);
			if (@self::$asset['js'][$js_dcheck]) {
			    return;
			}
			if (@$_REQUEST["action"] == 'su_generator_preview') {
				self::$asset['js'][$js_dcheck] = 1;
				echo '<script type="text/javascript">'.$script.'</script>';
				return;
			} else {
				self::$asset['js'][$js_dcheck] = 1;
				JFactory::getDocument()->addScriptDeclaration($script);
			}
		}
		return;
	}
}