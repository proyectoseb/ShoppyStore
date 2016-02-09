<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_divider_config {
	static function get_config() {
	        // divider
	        return array(
	        	'style' => array(
                    'type' => 'select',
                    'values' => array(
                        '1'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SINGLE_LINE'),
                        '2'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOUBLE_LINE'),
                        '3' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SINGLE_DASHED'),
                        '4' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOUBLE_DASHED'),
                        '5' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SINGLE_DOTTED'),
                        '6' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOUBLE_DOTTED'),
                        '7' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STRIPED')
                    ),
                    'default' => '1',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
                    'child'  => array(
                        'color' => array(
                            'type'    => 'color',
                            'values'  => array( ),
                            'default' => '#999',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC')
                        )
                    )
                ),
                'align' => array(
                    'type'   => 'select',
                    'values' => array(
                        'left'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
                        'right'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT'),
                        'center'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER')
                    ),
                    'default' => 'center',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_ALIGN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_ALIGN_DESC'),
                    'child' => array(
                        'bottom_top' => array(
					                    'type'    => 'select',
					                    'default' => 'no',
					                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOW_TOP_LINK'),
					                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOW_TOP_LINK_DESC'),
					                    'values'   => array(
					                    		'0' => 'Default',
					                    		'1' => 'Go to bottom',
					                    		'2' => 'Go to top'
					                    	),
					                ),
		                'width' => array(
					                    'type'    => 'slider',
					                    'min'     => 0,
					                    'max'     => 100,
					                    'step'    => 1,
					                    'default' => 100,
					                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_WIDTH'),
					                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_WIDTH_DESC')
					                ),
                    )
                ),
                'icon_divider' => array(
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
                'icon_style' => array(
                    'type' => 'select',
                    'values' => array(
                        '1' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
                        '2' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
                        '3' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER')
                    ),
                    'default' => '1',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_STYLE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
                    'child'   => array(
                    		'icon_align' => array(
		                            'type'   => 'select',
		                            'values' => array(
		                                'left'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
		                                'right'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT'),
		                                'center'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER')
		                            ),
		                            'default' => 'center',
		                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_ALIGN'),
		                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_ALIGN_DESC')
		                        ),
		                    'icon_size' => array(
				                    'type'    => 'slider',
				                    'min'     => 10,
				                    'max'     => 320,
				                    'step'    => 1,
				                    'default' => 24,
				                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE'),
				                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE_DESC')
				                ),
                    	),
                ),
                'margin_top' => array(
                    'type'    => 'slider',
                    'default' => '10',
                    'min'     => '0',
                    'max'     => '200',
                    'step'    => '5',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_MARGIN_TOP'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_MARGIN_TOP_DESC'),
                    'child'   => array(
                        'margin_bottom' => array(
                            'type'    => 'slider',
                            'default' => '10',
                            'min'     => '0',
                            'max'     => '200',
                            'step'    => '5',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_MARGIN_BOTTOM'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_MARGIN_BOTTOM_DESC')
                        ),
						'border_width' => array(
                            'type'    => 'slider',
                            'default' => '1',
                            'min'     => '1',
                            'max'     => '5',
                            'step'    => '1',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_BORDER_WIDTH'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_BORDER_WIDTH_DESC')
                        )
                    )
                ),
                'text' => array(
                	'default' =>'',
                	'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_TEXT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_TEXT_DESC'),
                ),
	        );
	    }
	}

?>