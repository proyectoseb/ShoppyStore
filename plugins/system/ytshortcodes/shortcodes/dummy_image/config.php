<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_dummy_image_config {
	static function get_config() {
	        // dummy image
	        return array(
	        	'theme' => array(
                    'type'   => 'select',
                    'values' => array(
                        'any'       => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANY'),
                        'abstract'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ABSTRACT'),
                        'animals'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMALS'),
                        'business'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUSINESS'),
                        'cats'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CATS'),
                        'city'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CITY'),
                        'food'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FOOD'),
                        'nightlife' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NIGHT_LIFE'),
                        'fashion'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FASHION'),
                        'people'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PEOPLE'),
                        'nature'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NATURE'),
                        'sports'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPORTS'),
                        'technics'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TECHNICS'),
                        'transport' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TRANSPORT')
                    ),
                    'default' => 'any',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_THEME'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_THEME_DESC')
                ),
                'width' => array(
                    'type'    => 'slider',
                    'min'     => 10,
                    'max'     => 1600,
                    'step'    => 10,
                    'default' => 500,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_DESC'),
                    'child'   => array(
                        'height' => array(
                            'type'    => 'slider',
                            'min'     => 10,
                            'max'     => 1600,
                            'step'    => 10,
                            'default' => 300,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEIGHT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEIGHT_DESC')
                        )
                    )
                ),
	        );
	    }
	}

?>