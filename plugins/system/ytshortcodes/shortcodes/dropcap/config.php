<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_dropcap_config {
	static function get_config() {
	        // dropcap
	        return array(
	        	'type' => array('type' => 'select',
					'default' => 'square',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC'),
					'values' => array(
						'default' => 'Default',
						'square'=>'Square',
						'round' => 'Round',
					),
					'child' =>array(
						'font'=> array(
							'default' => '',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DROPCAP_FRONT'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DROPCAP_FRONT_DESC'),
						),
					),
				),
				
				'size' => array('type' => 'slider',
					'default' => 14,
					'min' => 1,
					'max' => 50,
					'step' => 1,
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
					'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE_DESC"),
					'child' => array(
						'color' => array(
							'type'=>'color',
							'default' => '#000',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
						),
						'background' => array(
							'type'=>'color',
							'default' => '#fff',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
						),
					),
				),
				'content' =>array(
					'default' =>'D',
					'name' => 'Content'
				),
	        );
	    }
	}

?>