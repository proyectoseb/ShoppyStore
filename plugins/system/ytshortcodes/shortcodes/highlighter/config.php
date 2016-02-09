<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_highlighter_config {
	static function get_config() {
	        // highlighter
	        return array(
	        	'label' => array(
	        		'default' => 'Example',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHTER_LABEL'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHTER_LABEL_DESC'),
	        		'child' => array(
	        			'linenums' => array(
	        				'type' => 'bool',
	        				'default' => 'yes',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHTER_LINENUMS'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHTER_LINENUMS_DESC')
	        			),
	        		),
	        	),
	        	'content' => array(
	        		'type' => 'textarea',
	        		'default' => 'Add content here',
	        		'name' =>'Content'
	        	),
	        );
	    }
	}

?>