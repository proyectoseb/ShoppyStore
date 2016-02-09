<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_counter_config {
	static function get_config() {
	        // counter
	        return array(
	        	'count_start' => array(
                    'type'    => 'number',
                    'min'     => 0,
                    'max'     => 9999999,
                    'step'    => 10,
                    'default' => 0,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_START'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_START_DESC'),
                    'child'   => array(
                        'count_end' => array(
                            'type'    => 'number',
                            'min'     => 1,
                            'max'     => 9999999,
                            'step'    => 10,
                            'default' => 5000,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_END'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_END_DESC')
                        ),
                        'counter_speed' => array(
                            'type'    => 'number',
                            'min'     => 1,
                            'max'     => 100,
                            'step'    => 1,
                            'default' => 5,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTER_SPEED'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTER_SPEED_DESC')
                        )
                    )
                ), 
                'prefix' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PREFIX'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PREFIX_DESC'),
                    'child'   => array(
                        'suffix' => array(
                            'default' => '',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUFFIX'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUFFIX_DESC')
                        ),
                        'separator' => array(
		                    'type'    => 'bool',
		                    'default' => 'no',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SEPARATOR'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SEPARATOR_DESC')
		                ),
                    )
                ),
                'align' => array(
                    'type'    => 'select',
                    'default' => 'top',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
                    'values'  => array(
                        'left'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
                        'right'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT'),
                    ),
                    'child'   => array(
                    		'background' => array(
								'type'    => 'color',
								'default' => '#FFFFFF',
								'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
								'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC')
							),
							'border_radius' => array(
								'default' => '0px',
								'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_RADIUS'),
								'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_RADIUS_DESC')
							),
                    	),
                ),
                'icon' => array(
                    'type'    => 'icon',
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
                    'child'   => array(
                        'icon_color' => array(
                            'type'    => 'color',
                            'values'  => array( ),
                            'default' => '#444',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR_DESC')
                        )
                    )
                ),
                'count_color' => array(
                    'type'    => 'color',
                    'default' => '#444444',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_COLOR'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_COLOR_DESC'),
                    'child'   => array(
                        'count_size' => array(
                            'default' => '32px',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_SIZE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNT_SIZE_DESC')
                        )
                    )
                ),
                'text_color' => array(
                    'type'    => 'color',
                    'default' => '#666666',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR_DESC'),
                    'child'   => array(
                        'text_size' => array(
                            'default' => '14px',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTER_TEXT_SIZE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTER_TEXT_SIZE_DESC')
                        )     
                    )
                ),      
                'border' => array(
                    'type'    => 'border',
                    'default' => '0px solid #DDD',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC')
                ),
				'content' => array(
                    'type'    => 'textarea',
                    'default' => '',
                    'name'    => 'Content',
                ),
	        );
	    }
	}

?>