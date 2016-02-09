<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_masonry_config {
	static function get_config() {
	        // masonry
	        return array(
	        	'id_masonry' => array(
	        		'default' =>'',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_ID'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_ID_DESC'),
	        		'child' => array(
	        			'gutter' => array(
	        				'default' => 15,
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_GUTTER'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_GUTTER_DESC'),
	        			),
	        			'rtl' => array(
	        				'type' =>'bool',
	        				'default' => 'no',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_RIGHT_TO_LEFT'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_RIGHT_TO_LEFT_DESC')
	        			),
	        		),
	        	),
	        );
	    }
	}

?>