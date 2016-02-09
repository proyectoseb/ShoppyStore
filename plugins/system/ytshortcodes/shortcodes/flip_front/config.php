<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_flip_front_config {
	static function get_config() {
	        // flip front
	        return array(
	        	'background' => array(
                    'type'    => 'color',
                    'values'  => array( ),
                    'default' => '#ffffff',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'), 
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
                    'child'   => array(
                        'color' => array(
                            'type'    => 'color',
                            'values'  => array( ),
                            'default' => '#444',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR_DESC')
                        )
                    )
                ),
                'border' => array(
                    'type'    => 'border',
                    'default' => '0 solid #eeeeee',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC')
                ),
                'shadow' => array(
                    'type'    => 'shadow',
                    'default' => '0 0 0 #444444',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW_DESC')
                ),
                'text_align' => array(
                    'type'    => 'select',
                    'default' => 'center',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
                    'values'  => array(
                        'left'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
                        'center' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER'),
                        'right'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT')
                    )
                ),
                'padding' => array(
                    'default' => '15px',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING_DESC'),
                    'child'   => array(
                        'radius' => array(
                            'default' => '0px',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS_DESC')
                        )
                    )
                ),
                'content' => array(
                	'type' => 'textarea',
                	'name' => 'Content',
                	'default' => 'Flip Front Box Content'
                ),
                
	        );
	    }
	}

?>
