<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_frame_config {
	static function get_config() {
	        // frame
	        return array(
	        	'align' => array(
                    'type'   => 'select',
                    'values' => array(
                        'left'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
                        'center' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER'),
                        'right'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT')
                    ),
                    'default' => 'left',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC')
                ),
                'content' => array(
                	'type' => 'textarea',
                	'default' => "<img src='http://lorempixel.com/g/400/200/' />",
                	'name' => 'Content'
                ),
	        );
	    }
	}

?>