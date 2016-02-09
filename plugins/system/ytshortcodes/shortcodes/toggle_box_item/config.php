<?php 
/**
* @package   Shortcode Ultimate
* @author    BdThemes http://www.bdthemes.com
* @copyright Copyright (C) BdThemes Ltd
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_toggle_box_item_config {
	static function get_config() {
	        // toggle box item
	        return array(
	        	'title' => array(
	        		'default' => 'Title',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOGGLE_ITEM_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOGGLE_ITEM_TITLE_DESC'),
	        		'child' => array(
	        			'background' => array(
	        				'type' => 'color',
	        				'default' => '#f00',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC')
	        			),
	        			'color' => array(
	        				'type' => 'color',
	        				'default' => '#fff',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC')
	        			),
	        		),
	        	),
	        	'icon' => array(
	        		'type' => 'icon',
					'default' => '',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
	        	),
	        	'content' => array(
	        		'type' => 'textarea',
	        		'name' => 'Content',
	        		'default' => 'Add content here'
	        	),
	        );
	    }
	}

?>