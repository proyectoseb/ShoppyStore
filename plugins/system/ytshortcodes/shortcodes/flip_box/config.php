<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_flip_box_config {
	static function get_config() {
	        // flip_box
	        return array(
	        	'animation_style' => array(
                    'type'   => 'select',
                    'values' => array(
                        'horizontal_flip_left'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HORIZONTAL_FLIP_LEFT'),
                        'horizontal_flip_right' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HORIZONTAL_FLIP_RIGHT'),
                        'vertical_flip_top'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VERTICAL_FLIP_TOP'),
                        'vertical_flip_bottom'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VERTICAL_FLIP_BOTTOM'),
                        'flip_left'             => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLIP_LEFT'),
                        'flip_right'            => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLIP_RIGHT'),
                        'flip_top'              => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLIP_TOP'),
                        'flip_bottom'           => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLIP_BOTTOM')
                    ),
                    'default' => 'horizontal_flip_left',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATION_STYLE'),
                    'desc'    => sprintf( '%s.', JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATION_STYLE_DESC') ),
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
                'content' => array(
                	'type' => 'textarea',
                	'default' =>sprintf( "[yt_flip_front] Front Box Content [/yt_flip_front]\n[yt_flip_back] Back Box Content [/yt_flip_back]"),
                	'name' =>'Content'
                ),
	        );
	    }
	}

?>