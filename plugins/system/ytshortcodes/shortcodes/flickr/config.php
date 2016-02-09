<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_flickr_config {
	static function get_config() {
	        // flickr
	        return array(
	        	'id_flickr' => array(
                    'default' => '95572727@N00',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLICKR_ID'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLICKR_ID_DESC')
                ),  
                'limit' => array(
                    'type'    => 'slider',
                    'default' => '9',
                    'min'     => '0',
                    'max'     => '100',
                    'step'    => '1',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIMIT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIMIT_DESC'),
                    'child'   => array(
                    	'lightbox' => array(
		                    'type'    => 'bool',
		                    'default' => 'no',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_DESC')
		                ),                  
		                'radius' => array(
		                    'default' => '0px',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS_DESC')
		                ),
                    ),
                ),  
                
	        );
	    }
	}

?>