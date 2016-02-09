<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
class YT_Shortcode_audio_config {
static function get_config() {
        // audio
        return array(
	                'url' => array(
	                    'default' => '',
	                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUDIO_URL'),
	                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUDIO_URL_DESC')
	                ),
	                'autoplay' => array(
	                    'type'    => 'bool',
	                    'default' => 'no',
	                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY'),
	                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY_DESC'),
	                    'child'  => array(                        
	                        'loop' => array(
	                            'type'    => 'bool',
	                            'default' => 'no',
	                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOOP'),
	                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOOP_DESC')
	                        ),
	                        'volume' => array(
	                            'type'    => 'slider',
	                            'min'     => 0,
	                            'max'     => 100,
	                            'step'    => 2,
	                            'default' => 50,
	                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VOLUME'),
	                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VOLUME_DESC')
	                        ),
	                        'style' => array(
			                    'type' => 'select',
			                    'values' => array(
			                        'dark' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DARK'),
			                        'light'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHT')
			                    ),
			                    'default' => 'dark',
			                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'), 
			                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC')
			                ),
	                    )
	                ),
				);
	}
}
?>