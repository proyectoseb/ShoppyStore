<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_charts_config {
	static function get_config() {
	        // charts
	        return array(
	        	'type' => array(
					'type' => 'select',
					'default' => 'line',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC'),
					'values' => array(
								'line'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_LINE'),
								'bar'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_BAR'),
								'radar'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_RADAR'),	
								'doughnut' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_DOUGHNUT'),
								'pie'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_PIE'),
								'polar'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_POLAR'),
								),
					'child' =>array(
								'width' => array('type' => 'slider',
												'default' => 100,
												'min' => 0,
												'max' => 100,
												'step' => 1,
												'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
												'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_DESC"),
											),
							),
				),
	        	'labels' => array(
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_LABELS'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_LABELS_DESC'),		
	        			),
	        	'label_name' => array(
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_LABEL_NAME'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_LABEL_NAME_DESC'),
	        			),
	        	'data' => array(
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_DATA'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_DATA_DESC'),
	        			),
	        	'fill_color' => array(
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_FILL_COLOR'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_FILL_COLOR_DESC'),
	        				'child' => array(
	        					'stroke_color' => array(
			        				'default' => '',
			        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_STROKE_COLOR'),
			        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_STROKE_COLOR_DESC'),
			        			),
			        			'point_color' => array(
			        				'default' => '',
			        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_POINT_COLOR'),
			        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_POINT_COLOR_DESC'),
			        			),
	        				),
	        			),
	        	'highlight' => array(
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_HIGHLIGHT'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_HIGHLIGHT_DESC'),
	        				'child' =>array(
	        					'color'  => array(
			        				'default' => '',
			        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_COLOR'),
			        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_COLOR_DESC'),
			        			),
	        				),
	        			),
	        	
	        );
	    }
	}

?>