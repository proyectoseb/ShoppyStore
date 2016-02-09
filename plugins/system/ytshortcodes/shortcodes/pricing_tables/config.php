<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_pricing_tables_config {
	static function get_config() {
	        // pricing tables
	        return array(
	        	'type' => array(
	        		'type' => 'select',
					'default' => 'left',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_TYPE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_TYPE_DESC'),
					'values' => array(
						'style1' => 'Style 1',
						'style2' => 'Style 2',
						'style3' => 'Style 3'	
					),
					'child' => array(
						'columns' => array('type' => 'slider',
							'default' => 3,
							'min' => 1,
							'max' => 5,
							'step' => 1,
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_COLUMNS'),
							'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_COLUMNS_DESC"),
						),
					),		
	        	),
	        );
	    }
	}

?>