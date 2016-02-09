<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_panel_config {
	static function get_config() {
	        // panel
	        return array(
	        	'background' => array(
                    'type'    => 'color',
                    'default' => '#fff',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
                    'child'   => array(
                        'color' => array(
                            'type'    => 'color',
                            'default' => '#444',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC')
                        )
                    )
                ),
                'border' => array(
                    'type'    => 'border',
                    'default' => '1px solid #DDDDDD',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC')
                ),
                'shadow' => array(
                    'type'    => 'shadow',
                    'default' => '0px 0px 0px #eeeeee',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW_DESC')
                ),
                'padding' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING_DESC'),
                    'child'   => array(
                        'margin' => array(
                            'default' => '0px 0px 15px 0px',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN_DESC')
                        )
                    )
                ),
                'text_align' => array(
                    'type'    => 'select',
                    'default' => 'left',
                    'values'  => array(
                        'left'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
                        'center' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER'),
                        'right'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT')
                    ),
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
                    'child'   => array(
                        'radius' => array(
                            'default' => '0px',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS_DESC')
                        )
                    )
                ),
                'url' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_DESC')
                ),
                'content' => array(
                	'type'=> 'textarea',
                	'name' => 'Content',
                	'default' => 'Add content here'
                ),
	        );
	    }
	}

?>