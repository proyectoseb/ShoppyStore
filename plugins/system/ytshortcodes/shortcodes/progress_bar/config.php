<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_progress_bar_config {
	static function get_config() {
	        // Progress bar
	        return array(
	        	'type_change' => array(
	        		'type'   => 'select',
                    'values' => array(
                        'linear' => 'Linear',
                        'circle'  => 'Circle',
                    ),
                    'default' => '1',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROGRESS_BAR_TYPE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC'),
                    'child'  => array(
                    	'percent' => array(
		                    'type'    => 'slider',
		                    'min'     => 0,
		                    'max'     => 100,
		                    'step'    => 1,
		                    'default' => 75,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PERCENT'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PERCENT_DESC'),
		                    'child'   => array(
		                        'show_percent' => array(
		                            'type'    => 'bool',
		                            'group'   => 'linear',
		                            'default' => 'yes',
		                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOW_PERCENT'),
		                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOW_PERCENT_DESC')
		                        )
		                    )
		                ),
                    ),
	        	),
	        	'style_linear' => array(
                    'type'   => 'select',
                    'group'   => 'linear',
                    'values' => array(
                        'default' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
                        'fancy'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FANCY'),
                        'thin'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_THIN'),
                        'striped' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STRIPED'),
                        'animation' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATE')
                    ),
                    'default' => '1',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROGRESS_BAR_STYLE_LINEAR'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
                    'child'   => array(
                    	'style_circle' => array(
		                    'type'   => 'select',
		                    'values' => array(
		                        'circle1' => 'Circle 1',
		                        'circle2' => 'Circle 2',
		                        'circle3' => 'Circle 3',
		                        'circle4' => 'Circle 4'
		                    ),
		                    'group'   => 'circle',
		                    'default' => 'circle1',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROGRESS_BAR_STYLE_CIRCLE'),
		                    'desc'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
		                ),
                    	'text' => array(
		                    'default' => '',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_DESC')
		                ),
                    ),
                ),
                'text_color' => array(
                    'type'    => 'color',
                    'default' => '#555555',
                    'group'   => 'linear',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR_DESC'),
                    'child'   => array(
                    	'bar_color' => array(
                    	    'type'    => 'color',
                    	    'default' => '#f0f0f0',
                    	    'group'   => 'linear',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BAR_COLOR'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BAR_COLOR_DESC')
                    	),
                    	'fill_color' => array(
                    	    'type'    => 'color',
                    	    'default' => '#97daed',
                    	    'group'   => 'linear',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FILL_COLOR'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FILL_COLOR_DESC')
                    	)
                    )
                ),
                'animation' => array(
                    'type'    => 'select',
                    'group'   => 'linear',
                    'values'  => array_combine(YT_Data::easings(), YT_Data::easings()),
                    'default' => 'easeInOutExpo',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATION'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATION_DESC'),
                    'child'   => array(
                        'duration' => array(
                            'type'    => 'slider',
                            'group'   => 'linear',
                            'min'     => 0.5,
                            'max'     => 10,
                            'step'    => 0.5,
                            'default' => 1.5,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION_DESC')
                        ),
                        'delay' => array(
                            'type'    => 'slider',
                            'group'   => 'linear',
                            'min'     => 0.1,
                            'max'     => 5,
                            'step'    => 0.2,
                            'default' => 0.3,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION_DESC')
                        )
                    )
                ),
	        );
	    }
	}

?>