<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_image_compare_config {
	static function get_config() {
	        // Image compare
	        return array(
	        	'before_image' => array(
                    'type'    => 'media',
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BEFORE_IMAGE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BEFORE_IMAGE_DESC'),
					'child'   => array(
						'after_image' => array(
							'type'    => 'media',
							'default' => '',
							'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AFTER_IMAGE'),
							'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AFTER_IMAGE_DESC')
						), 
					),
                ), 
                
                'before_text' => array(
                    'default' => 'Original',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IC_BEFORE_TEXT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IC_BEFORE_TEXT_DESC'),
					'child'   => array(
						'after_text' => array(
							'default' => 'Modified',
							'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IC_AFTER_TEXT'),
							'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IC_AFTER_TEXT_DESC')
						), 
					),
                ), 
                'orientation' => array(
                    'type'   => 'select',
                    'values' => array(
                        'horizontal' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IC_HORIZONTAL'),
                        'vertical' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IC_VERTICAL')
                    ),
                    'default' => 1,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ORIENTATION'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ORIENTATION_DESC')
                ),
	        );
	    }
	}

?>