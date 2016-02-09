<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_document_config {
	static function get_config() {
	        // document
	        return array(
	        	'url' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOCUMENT_URL_DESC')
                ),
                'width' => array(
                    'type'    => 'slider',
                    'min'     => 200,
                    'max'     => 1600,
                    'step'    => 20,
                    'default' => 600,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOCUMENT_WIDTH_DESC'),
                    'child'   => array(
                        'height' => array(
                            'type'    => 'slider',
                            'min'     => 200,
                            'max'     => 1600,
                            'step'    => 20,
                            'default' => 600,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEIGHT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOCUMENT_HEIGHT_DESC')
                        ),
                         'responsive' => array(
		                    'type'    => 'bool',
		                    'default' => 'yes',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RESPONSIVE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RESPONSIVE_DESC')
		                ),
                    )
                ),
               
	        );
	    }
	}

?>