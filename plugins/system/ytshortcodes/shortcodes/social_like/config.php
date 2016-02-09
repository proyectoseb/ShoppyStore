<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_social_like_config {
	static function get_config() {
	        // Social like
	        return array(
	        	'button' => array(
                    'type'   => 'select',
                    'values' => array(
                        'facebook' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FACEBOOK'),
                        'linkedin' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINKEDIN'),
                        'twitter'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TWITTER'),
                        'google'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE')
                    ),
                    'default' => 'google',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_DESC'),
                    'child' => array(
                    	'url' => array(
                    		'default' => '',
                    		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SC_SOCIAL_LIKE_URL'),
                    		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SC_SOCIAL_LIKE_URL_DESC')
                    	),
                    ),
                ),
                'button_animation' => array(
                    'type'   => 'select',
                    'values' => array(
                        'to_left'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TO_LEFT'),
                        'to_top'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TO_TOP'),
                        'to_bottom' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TO_BOTTOM'),
                        'to_right'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TO_RIGHT')
                    ),
                    'default' => 'to_left',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATION'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATION_DESC'),
                    'child' => array(
                    	'align' => array(
                    		'type' => 'select',
							'default' => 'left',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
							'values' => array(
								'left'=>'Left',
								'right' => 'Right',
								'none' =>'None'
							),
                    	),
                    ),
                ),
	        );
	    }
	}

?>