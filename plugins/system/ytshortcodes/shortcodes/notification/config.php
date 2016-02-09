<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_notification_config {
	static function get_config() {
	        // Notification
	        return array(
	        	'effect' => array(
                    'type' => 'select',
                    'values' => array(
                        '1' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FADE_IN_AND_SCALE'),
                        '2' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SLIDE_IN_RIGHT'),
                        '3' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SLIDE_IN_BOTTOM'),
                        '4' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FALL'),
                        '5' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SLIDE_FALL'),
                        '6' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STICKY_UP'),
                        '7' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3D_FLIP_HORIZONTAL'),
                        '8' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3D_FLIP_VERTICAL'),
                        '9' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3D_SIGN'),
                        '10' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUPER_SCALED'),
                        '11' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_JUST_ME'),
                        '12' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3D_SLIT'),
                        '13' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3D_ROTATE_BOTTOM'),
                        '14' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3D_ROTATE_IN_LEFT'),
                        '15' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLUR'),
                        '16' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LET_ME_IN'),
                        '17' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MAKE_WAY'),
                        '18' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SLIP_FROM_TOP')
                    ),
                    'default' => '1',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_EFFECT'),
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_EFFECT_DESC'),
                    'child' => array(
                    	'close_button' => array(
		                    'type' => 'bool',
		                    'default' => 'no',
		                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE_BUTTON'),
		                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE_BUTTON_DESC')
		                ),
                    ),
                ),
                'button_text' => array(
                    'default' => 'Open Notification',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_TEXT'), 
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_TEXT_DESC'),
                    'child'   => array(
                        'button_class' => array(
                            'default' => '',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_CLASS'), 
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_CLASS_DESC')
                        )
                    )
                ),
                                      
                'title' => array(
                    'default' => 'Notification title',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_TITLE'), 
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_TITLE_DESC'),
                    'child' => array(
                    	'overlay_background' => array(
		                    'default' => 'rgba(0,0,0,0.5)',
		                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_OVERLAY_BACKGROUND'),
		                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_OVERLAY_BACKGROUND_DESC')
		                ),
                    ),
                ),
                'title_background' => array(
                    'type' => 'color',
                    'values' => array( ),
                    'default' => 'rgba(0,0,0,0.1)',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_BACKGROUND'), 
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_BACKGROUND_DESC'),
                    'child'   => array(
                        'title_color' => array(
                            'type' => 'color',
                            'default' => '#ffcc00',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_COLOR'), 
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_COLOR_DESC')
                        )
                    )
                ),
                'background' => array(
                    'type' => 'color',
                    'values' => array( ),
                    'default' => '#2D89EF',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_BACKGROUND'), 
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_BACKGROUND_DESC'),
                    'child'   => array(
                        'color' => array(
                            'type' => 'color',
                            'default' => '#ffcc00',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_COLOR'), 
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_COLOR_DESC')
                        )
                    )
                ),
                'border' => array(
                    'type'    => 'border',
                    'default' => '0 solid #cccccc',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_BORDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_BORDER_DESC')
                ),
                'shadow' => array(
                    'type' => 'shadow',
                    'default' => '0 0 0 #000000',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_SHADOW'),
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_SHADOW_DESC')
                ),
                'height' => array(
                    'default' => 'auto',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_HEIGHT'),
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_HEIGHT_DESC'),
                    'child'   => array(
                        'width' => array(
                            'default' => '640px',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_WIDTH'),
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_WIDTH_DESC')
                        )
                    )
                ),
                'content'=> array(
                	'type' => 'textarea',
                	'default' => 'Add content here',
                	'name' => 'Content'
                ),
	        );
	    }
	}

?>