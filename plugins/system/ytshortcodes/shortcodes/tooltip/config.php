<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_tooltip_config {
	static function get_config() {
	        // accordion
	        return array(
	        	'title' => array(
	        		'default' => 'Title',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_TITLE_DESC'),
	        		'child' => array(
	        			'type' => array(
	        				'type' => 'select',
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_TYPE'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_TYPE_DESC'),
	        				'values' => array(
	        					''        => 'Default',
	        					'warning' => 'Warning',
	        					'primary' => 'Primary',
	        					'info'    => 'Info',
	        					'success' => 'Success',
	        					'border'  => 'Border'
	        				),
	        			), 
	        			'link' => array(
	        				'default' => 'http://',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_LINK'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_LINK_DESC')
	        			),
	        		),
	        	),
	        	'position' => array(
    				'type' => 'select',
    				'default' => 'top',
    				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_POSITION'),
    				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_POSITION_DESC'),
    				'values' => array(
    					'top' => 'Top',
    					'right' => 'Right',
    					'bottom' => 'Bottom',
    					'left' => 'Left'
    				),
    				'child' => array(
    					'background' => array(
    						'type' => 'color',
    						'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
    						'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
    						'default' => '#fff'
    					),
    					'color' => array(
    						'type' => 'color',
    						'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
    						'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
    						'default' => '#000'
    					),
    				),
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