<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_icon_config {
	static function get_config() {
	        // icon
	        return array(
	        	'icon' => array(
                    'type'    => 'icon',
                    'default' => 'icon: heart',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC')
                ),
                'url' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_DESC'),
                    'child'   => array(
                    	 'size' => array(
		                    'type'    => 'slider',
		                    'default' => '20',
		                    'min'     => '4',
		                    'max'     => '256',
		                    'step'    => '2',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE_DESC')
		                ),
                    ),
                ),
                'background' => array(
                    'type'    => 'color',
                    'default' => '#eeeeee',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
                    'child'   => array(
                        'color' => array(
                            'type'    => 'color',
                            'default' => '#333333',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR_DESC')
                        )
                    )
                ),
                'radius' => array(
                    'default' => '3px',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS_DESC'),
                    'child'   => array(
                        'padding' => array(
                            'default' => '15px',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING_DESC')  
                        ),
                        'margin' => array(
                            'default' => '',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN_DESC')
                        )
                    )
                ),
                'border' => array(
                    'type'    => 'border',
                    'default' => '0px solid #444444',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC')
                ),
	        );
	    }
	}

?>