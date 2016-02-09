<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
class YT_Shortcode_animation_config {
	static function get_config() {
        // animation
        return array(
					'type' => array(
	                    'type'    => 'select',
	                    'values'  => array_combine( YT_Data::animations(), YT_Data::animations() ),
	                    'default' => 'bounceIn',
	                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
	                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
	                    'child'   => array(
							'inline' => array(
								'type'    => 'bool',
								'default' => 'no',
								'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INLINE'),
								'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INLINE_DESC'),
							),	
						),
	                ),
	                'duration' => array(
	                    'type'    => 'slider',
	                    'min'     => 0,
	                    'max'     => 20,
	                    'step'    => 0.5,
	                    'default' => 1,
	                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION'),
	                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DURATION_DESC'),
	                    'child'  => array(
	                        'delay' => array(
	                            'type'    => 'slider',
	                            'min'     => 0,
	                            'max'     => 20,
	                            'step'    => 0.5,
	                            'default' => 0,
	                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY'),
	                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY_DESC')
	                        )
	                    )
	                ),
	                'content' => array('type' => 'textarea',
								   'default' => 'Add Content Here',
								   'name'  => 'Content',
								   ),
	            );
	}
}
?>