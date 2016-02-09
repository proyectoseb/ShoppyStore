<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_skills_item_config {
	static function get_config() {
	        // skills item
	        return array(
	        	'title' => array(
	        		'default' => 'Title Skill',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_ITEM_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_ITEM_TITLE_DESC'),
	        		'child' => array(
	        			'number' => array(
			        		'default' => '30',
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_ITEM_NUMBER'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_ITEM_NUMBER_DESC')
			        	),
	        		),
	        	),
	        ); 
	    }
	}

?>