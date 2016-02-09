<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_tabs_item_config {
	static function get_config() {
	        // tabs
	        return array(
	        	'title'=> array(
	        		'default' => 'Title',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_TITLE_DESC'),
	        		'child' => array(
	        			'icon'=> array(
	        				'type'=>'icon',
							'default' => '',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
	        			),
	        		),
	        	),
	        	'content' => array(
	        		'default' => 'Add content here',
	        		'name' => 'Content',
	        		'type' => 'textarea'
	        	),
	        );
	    }
	}

?>