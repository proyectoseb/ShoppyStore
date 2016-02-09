<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_modal_config {
	static function get_config() {
	        // modal
	        return array(
	        	'title' => array(
	        		'default' =>'Title Modal',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODAL_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODAL_TITLE_DESC'),
	        		'child' => array(
	        			'header' => array(
	        				'default' => 'Title header',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODAL_HEADER'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODAL_HEADER_DESC')
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