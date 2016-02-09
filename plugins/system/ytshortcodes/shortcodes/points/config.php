<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_points_config {
	static function get_config() {
	        // Points
	        return array(
	        	'width'=> array(
	        		'type' => 'slider',
					'default' => 100,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
					'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_%_DESC"),
					'child' => array(
						'src' => array(
							'type' => 'media',
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_SRC'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_SRC_DESC'),
			        		'default' => ''
			        	),
					),
	        	),
	        );
	    }
	}

?>