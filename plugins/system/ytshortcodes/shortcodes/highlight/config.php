<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_highlight_config {
	static function get_config() {
	        // high light
	        return array(
	        	'background' => array(
                    'type'    => 'color',
                    'values'  => array( ),
                    'default' => '#DDFF99',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
                    'child'   => array(
                        'color' => array(
                            'type'    => 'color',
                            'values'  => array( ),
                            'default' => '#000000',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR_DESC')
                        ),
                        'size' => array('type' => 'slider',
							'default' => 14,
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
							'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE_DESC"),
						),
                    )
                ),
                'content' =>array(
                	'type' => 'textarea',
                	'default' => 'Add content here',
                	'name' => 'Content'
                ),
	        );
	    }
	}

?>