<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_points_item_config {
	static function get_config() {
	        // Points Item
	        return array(
	        	'x'=> array(
	        		'type' => 'slider',
					'default' => 30,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_X'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_X_DESC'),
					'child' => array(
						'y'=> array(
			        		'type' => 'slider',
							'default' => 30,
							'min' => 0,
							'max' => 100,
							'step' => 1,
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_Y'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_Y_DESC'),
						),
						'position' => array(
							'type' => 'select',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_POSITION'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_POSITION_DESC'),
							'default' => 'left',
							'values' => array(
								'bottom' => 'Bottom',
								'left' =>'Left',
								'top' => 'Top',
								'right' => 'Right',
							),
						),
					),
	        	),
	        	'content' => array(
	        		'type' => 'textarea',
	        		'default' => 'Add content here',
	        		'name' => 'Content'
	        	),
	        );
	    }
	}

?>