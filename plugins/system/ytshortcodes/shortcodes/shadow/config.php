<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_shadow_config {
	static function get_config() {
	        // shadow
	        return array(
	        	'style' => array(
                    'type'    => 'select',
                    'default' => 'default',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
                    'values'   => array(
                        'default'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
                        'left'       => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT_CORNER'),
                        'right'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT_CORNER'),
                        'horizontal' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HORIZONTAL'),
                        'vertical'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VERTICAL'),
                        'bottom'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOTTOM'),
                        'simple'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIMPLE')
                    ),
                    'child' => array(
                    	'inline' => array(
		                    'type'    => 'bool',
		                    'default' => 'no',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INLINE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INLINE_DESC')
		                ),
                    ),
                ),
                'content' => array(
                	'type' => 'textarea',
                	'name' => 'Content',
                	'default' => 'Add content here'
                ),
	        );
	    }
	}

?>