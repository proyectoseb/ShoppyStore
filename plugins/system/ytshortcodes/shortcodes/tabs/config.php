<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_tabs_config {
	static function get_config() {
	        // Tabs
	        return array(
	        	'type' => array(
	        		'type' => 'select',
					'default' => 'basic',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
					'values' => array(
						'basic' => 'Basic',
						'vertical'=>'Vertical',
						'vertical-right' => 'Vertical Right',
						'boxed'   => 'Boxed',
						'underline' => 'Underline',
						'curved' => 'Curved',
						'curved-opened' => 'Curved Opened'
					),
					'child' => array(
						'style' => array(
			        		'type' => 'select',
							'default' => '',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_STYLE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_STYLE_DESC'),
							'values' => array(
								'' => 'Default',
								'green' => 'Green',
								'blue'  =>'Blue',
								'black' => 'Black',
								'red'   => 'Red',
								'oranges' => 'Oranges',
								'darkblue' => 'Dark Blue',
								'pink' => 'Pink',
								'darkred' => 'Darkred',
								'brown' => 'Brown',
								'purple' => 'Purple',
								'cyan' => 'Cyan'
							),
			        	),
					),
	        	),
	        	'width' => array(
	        		'default' => '',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_WIDTH'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_WIDTH_DESC'),
	        		'child'=> array(
	        			'height' => array(
			        		'default' => '',
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_HEIGHT'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_HEIGHT_DESC')
			        	),
			        	'align' => array('type' => 'select',
							'default' => 'left',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
							'values' => array(
								'left'=>'Left',
								'right' => 'Right',
								'none' =>'None'	
							),
						),
	        		),
	        	),
	        );
	    }
	}

?>