<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_countdown_config {
	static function get_config() {
	        // count down
	        return array(
	        	'count_date' => array(
                    'default' => '2020/12/25',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_DATE'),
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_DATE_DESC'),
                    'child'   => array(
                        'count_time' => array(
                            'default' => '',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TIME'),
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TIME_DESC')
                        )         
                    )
                ),                
                'align' => array(
                    'type'    => 'select',
                    'default' => 'left',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_ALIGN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_ALIGN_DESC'),
                    'values'   => array(
                        'left'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
                        'right'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT'),
                        'center' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER')
                    ),
					'child' => array(
						'count_size' => array(
							'type' => 'slider',
							'min' => 8,
							'max' => 128,
							'step' => 2,
							'default' => 32,
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_COUNT_SIZE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_COUNT_SIZE_DESC')
						),
					),
                ),
                'count_color' => array(
                    'type' => 'color',
                    'values' => array( ),
                    'default' => '#666666',
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_COLOR'),
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_COLOR_DESC'),
                    'child'   => array(
                        'background' => array(
                            'type' => 'color',
                            'values' => array( ),
                            'default' => '#FFFFFF',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TXT_BACKGROUND_COLOR'),
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TXT_BACKGROUND_COLOR_DESC')
                        ),
                        'text_color' => array(
                            'type' => 'color',
                            'values' => array( ),
                            'default' => '#999999',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TXT_COLOR'),
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TXT_COLOR_DESC')
                        )
                    )
                ),                
                'text_align' => array(
                    'type'    => 'select',
                    'default' => 'default',
                    'values'   => array(
                        'right'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_TEXT_ALIGN_DEFAULT'),
                        'bottom' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_TEXT_ALIGN_BOTTOM')
                    ),
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_TEXT_ALIGN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_TEXT_ALIGN_DESC'),
                    'child'   => array(
                        'text_size' => array(
                            'type' => 'slider',
                            'min' => 8,
                            'max' => 72,
                            'step' => 2,
                            'default' => 14,
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TEXT_SIZE'),
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_TEXT_SIZE_DESC')
                        )
                    )
                ),
                'padding' => array(
                    'default' => 0,
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING'),
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING_DESC'),
                    'child'   => array(
                        'margin' => array(
                        'default' => 0,
                        'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
                        'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN_DESC')
                        ),
                        'radius' => array(
                            'default' => '0px',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS'),
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS_DESC')
                        )
                    )
                ),   
                'divider' => array(
                    'type'    => 'select',
                    'default' => 'none',
                    'values'   => array(
                        'none' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_NO_DIVIDER'),
                        'spacer' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_SPACER'),
                        'dot'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_DOT'),
                        'comma'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_COMMA'),
                        'colon'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_COLON'),
                        'vertical_line'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_VERTICAL_LINE'),
                        'horizontal_line'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_HORIZONTAL_LINE')
                    ),
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DIVIDER_DESC'),
                    'child'   => array(
                        'divider_color' => array(
                            'type' => 'color',
                            'values' => array( ),
                            'default' => 'rgba(100,100,100,.1)',
                            'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_DIVIDER_COLOR'),
                            'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CD_DIVIDER_COLOR_DESC')
                        ),
                    )
                ),
	        );
	    }
	}

?>