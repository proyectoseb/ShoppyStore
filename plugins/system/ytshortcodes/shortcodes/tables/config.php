<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_tables_config {
	static function get_config() {
	        // table
	        return array(
	        	'type' => array(
	        		'type' => 'select',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_TYPE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_TYPE_DESC'),
	        		'default' =>'',
	        		'values' => array(
	        			'striped' => 'Striped',
	        			'striped2' => 'Striped 2',
	        			'bordered' => 'Bordered',
						'striped table-hover'  => 'Striped width hover',
						'bordered table-hover'  => 'Bordered width hover',
	        		),
	        		'child' => array(
	        			'background' => array(
	        				'type' => 'color',
	        				'default' => '#fff',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
	        			), 
	        			'color' => array(
	        				'type' => 'color',
	        				'default' => '#000',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
	        			), 
	        		),
	        	),
	        	'row_color' => array(
    				'default' => '',
    				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_ROW_COLOR'),
    				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_ROW_COLOR_DESC'),
    				'child' => array(
    					'column_color' => array(
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_COLUMN_COLOR'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_COLUMN_COLOR_DESC'),
	        			), 
    				),
    			), 
	        	'cols' => array(
	        		'default' => '',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_COLS'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_COLS_DESC'),
	        	),
	        	'content' => array(
	        		'default' => 'Value1| Value2 | Value3 | Value4 | Value5 | Value6',
	        		'name' => 'Content',
	        		'type' => 'textarea'
	        	),
	        );
	    }
	}

?>