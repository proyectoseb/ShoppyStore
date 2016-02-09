<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_toggle_box_config {
	static function get_config() {
	        // Toggle boxs
	        return array(
	        	'align' => array('type' => 'select',
					'default' => 'left',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
					'values' => array(
						'left'  => 'Left',
						'right' => 'Right',
						'none'  => 'None'	
					),
					'child' => array(
						'width' => array(
							'default' => '100%',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOGGLE_WIDTH'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOGGLE_WIDTH_DESC')
						),
					),
				),
	        );
	    }
	}

?>