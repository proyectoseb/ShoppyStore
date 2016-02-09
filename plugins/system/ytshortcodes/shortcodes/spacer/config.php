<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_spacer_config {
	static function get_config() {
	        // accordion
	        return array(
	        	'height' => array(
	        		'default' => '30',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPACER_HEIGHT'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPACER_HEIGHT_DESC')
	        	)
	        );
	    }
	}

?>