<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_fancy_text_config {
	static function get_config() {
	        // fancy text
	        return array(
	        	'tags' => array(
                    'default' => 'Text 1, Text 2, Text 3',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FANCY_TEXT_TAGS'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FANCY_TEXT_TAGS_DESC'),
					'child'   => array(
						'type' => array(
							'type'   => 'select',
							'values' => array(
								'1'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE1'),
								'2'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE2'),
								'3'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE3'),
								'4'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE4'),
								'5'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE5'),
								'6'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE6'),
								'7'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE7'),
								'8'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE8'),
								'9'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE9'),
								'10' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE10')
							),
							'default' => 'rotate-1',
							'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE'),
							'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC')
						),
					),
                ),
                 
                'color' => array(
                	'type'=>'color',
					'default' => '#000',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
					'child' => array(
						'size' => array('type' => 'slider',
								'default' => 12,
								'min' => 1,
								'max' => 50,
								'step' => 1,
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
								'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE_DESC"),
							),
					),
				),
	        );
	    }
	}

?>