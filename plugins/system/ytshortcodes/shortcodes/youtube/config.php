<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
class YT_Shortcode_youtube_config {
	static function get_config() {
	        // Youtube
	        return array(
	        	'url' => array(
                    'values'  => array( ),
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_YOUTUBE_URL_DESC'),
                    
                ),
                'width' => array(
                    'type'    => 'slider',
                    'min'     => 200,
                    'max'     => 1600,
                    'step'    => 20,
                    'default' => 600,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_WIDTH_DESC'),
                    'child'		=> array(
                    	'height' => array(
                    	    'type'    => 'slider',
                    	    'min'     => 200,
                    	    'max'     => 1600,
                    	    'step'    => 20,
                    	    'default' => 400,
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEIGHT'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_HEIGHT_DESC')
                    	)
                    )
                ),
                'responsive' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RESPONSIVE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RESPONSIVE_DESC'),
                    'child'		=> array(
                    	'autoplay' => array(
                    	    'type'    => 'bool',
                    	    'default' => 'no',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY_DESC')
                    	)
                    )
                ),
	        );
	    }
	}

?>