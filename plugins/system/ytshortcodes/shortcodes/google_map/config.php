<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_google_map_config {
	static function get_config() {
	        // google map
	        return array(
	        	'type' => array(
	        		'type'=> 'select',
	        		'default' => 'style1',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_TYPE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_TYPE_DESC'),
	        		'values' => array(
	        			'style1' => 'Style 1',
	        			'style2' => 'Style 2',
	        			'style3' => 'Style 3',
	        			'style4' => 'Style 4',
	        			'style5' => 'Style 5',
	        			'style6' => 'Style 6',
	        			'style7' => 'Style 7',
	        			'style8' => 'Style 8',
	        		),
	        		'child' => array(
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
				'addr' => array(
					'default' => '',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_ADDR'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_ADDR_DESC'),
					'child' => array(
						'label' => array(
							'default' => 'GOOLE_MAP_LABEL',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_LABEL'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_LABEL_DESC'),
						),
					),
				),
				'width' => array(
					'default' => '100%',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_WIDTH'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_WIDTH_DESC'),
					'child' => array(
						'height' => array(
							'default' => '400',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_HEIGHT'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_HEIGHT_DESC'),
						),
					),
				),
	        );
	    }
	}

?>