<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_livicon_config {
	static function get_config() {
	        // livicon
	        return array(
	        	'icon' => array(
                    'type'    => 'select',
                    'values'  => array_combine( YT_Data::livicons(), YT_Data::livicons() ),
                    'default' => 'heart',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
                    'child'   => array(
                        'size' => array(
                            'type'    => 'slider',
                            'default' => 32,
                            'min'     => '4',
                            'max'     => '256',
                            'step'    => '2',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE_DESC')
                        )
                    )
                ),
                'color' => array(
                    'type'    => 'color',
                    'default' => '#555555',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR_DESC'),
                    'child'   => array(
                        'hover_color' => array(
                            'type'    => 'color',
                            'default' => '#000000',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVER_COLOR'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVER_COLOR_DESC')
                        ),
                        'event_type' => array(
		                    'type' => 'select',
		                    'values' => array(
		                        'hover' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVER'),
		                        'click' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLICK')
		                    ),
		                    'default' => 'hover',
		                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_EVENT_TYPE'),
		                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_EVENT_TYPE_DESC')
		                ),
                    )
                ),
                'animate' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATE_DESC'),
                    'child'   => array(
                        'loop' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOOP'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOOP_DESC')
                        ),
                        'parent' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PARENT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PARENT_DESC')
                        )
                    )
                ),
                'duration' => array(
                    'type'    => 'slider',
                    'default' => 0.6,
                    'min'     => 0.2,
                    'max'     => 5,
                    'step'    => 0.2,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION_DESC'),
                    'child'   => array(
                        'iteration' => array(
                            'type'    => 'slider',
                            'default' => 1,
                            'min'     => 1,
                            'max'     => 5,
                            'step'    => 1,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ITERATION'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ITERATION_DESC')
                        )
                    )
                ),
                'url' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_DESC'),
                    'child'  => array(
                        'target' => array(
                            'type'   => 'select',
                            'values' => array(
                                'self'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SELF'),
                                'blank'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLANK')
                            ),                    
                            'default' => 'self',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TARGET'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TARGET_DESC')
                        )
                    )
                ),
	        );
	    }
	}

?>