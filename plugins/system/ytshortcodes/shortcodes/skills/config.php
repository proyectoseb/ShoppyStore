<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_skills_config {
	static function get_config() {
	        // skills
	        return array(
	        	'width' => array(
	        		'default' =>'',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_WIDTH'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_WIDTH_DESC'),
	        		'child' => array(
	        			'no_number' => array(
	        				'type' => 'bool',
	        				'default' => 'yes',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_NO_NUMBER'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_NO_NUMBER_DESC')
	        			),
	        		),
	        	),
	        );
	    }
	}

?>