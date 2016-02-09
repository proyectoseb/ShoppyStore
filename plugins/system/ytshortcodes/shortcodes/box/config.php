<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_box_config {
	static function get_config() {
	        // box
	        return array(
	                'style' => array(
	                        'type'    => 'select',
	                        'values'  => array(
	                        'default' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
	                        'soft'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOFT'),
	                        'glass'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GLASS'),
	                        'bubbles' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUBBLES'),
	                        'noise'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOISE')
	                    ),
	                    'default' => 'default',
	                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
	                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
	                    'child'   => array(
	                    	'box_color' => array(
			                    'type'    => 'color',
			                    'values'  => array( ),
			                    'default' => '#333333',
			                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOX_COLOR'),
			                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC')    
			                ),                     
			                'radius' => array(
								'type' => 'slider',
								'min' => 0,
								'max' => 20,
								'step' => 1,
								'default' => 0,
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS'),
								'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOX_RADIUS_DESC')
							),
	                    ),
	                ),
	                'title' => array(
	                    'values'  => array( ),
	                    'default' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOX_TITLE_DEFAULT'),
	                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE'), 
	                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_DESC'),
	                    'child'  => array(
	                        'title_color' => array(
	                            'type'    => 'color',
	                            'default' => '#ffffff',
	                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_COLOR'),
	                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_COLOR_DESC')
	                        )
	                    )
	                ), 
					'content'  => array('type' => 'textarea',
										'default' => 'Add content here',
										'name' => 'Content',
									),	
	            );
	    }
	}

?>