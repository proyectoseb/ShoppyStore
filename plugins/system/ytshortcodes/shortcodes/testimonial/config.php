<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_testimonial_config {
	static function get_config() {
	        // Testimonial
	        return array(
	        	'title'=> array(
	        		'default' => 'Title',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_TITLE_DESC'),
	        		'child' => array(
	        			'column' => array(
	        				'type'=> 'slider',
	        				'default' => 1,
							'min' => 1,
							'max' => 3,
							'step' => 1,
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_COLUMN'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_COLUMN_DESC'),
	        			),
	        		),
	        	),
	        	'border'=> array(
	        		'type' => 'border',
					'default' => '1px solid #ccc',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC'),
	        	),
	        	'background' => array(
	        		'type' => 'media',
	        		'default' => '',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_BACKGROUND'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_BACKGORUND_DESC'),
					'child' => array(
						'title_color'=>array(
							'type' 		=> 'color',
							'default' 	=> '#ccc',
							'name' 		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
							'desc' 		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
						),
					),
	        	),
	        );
	    }
	}

?>