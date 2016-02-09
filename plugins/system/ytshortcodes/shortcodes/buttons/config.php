<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die; 
	class YT_Shortcode_buttons_config {
	static function get_config() {
	        // buttons
	        return array(
	        	'type'=> array(  	
        			'type' => 'select',
					'default' => 'none',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_TYPE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_TYPE_DESC'),
					'values' => YT_Data::buttons_type(),
					'child' => array(
						'style' => array(
		                    'type' => 'select',
		                    'values' => array(
		                        'default' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
		                        'soft'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOFT'),
		                        'glass'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GLASS'),
		                        'bubbles' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUBBLES'),
		                        'noise'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOISE'),
		                        'stroked' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STROKED'),
		                        'border' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
		                        '3d'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3D'),
		                        'bottom_line'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE'),
		                        'dropshadow' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DROPSHADOW'),
		                        'dot' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOTTED'),
		                        'insetshadow' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INSETSHADOW'),
		                        'transparent' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TRANSPARENT'),
		                        'gradient' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GRADIENT'),
		                    ),
		                    'default' => 'default',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'), 
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC')
		                ),
					),
					),
				'link' => array(
                    'values'  => array( ),
                    'default' => '#',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_DESC'),
                    'child'  => array(
                        'target' => array(
                            'type'   => 'select',
                            'values' => array(
                                '_self'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SELF'),
                                '_blank'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLANK')
                            ),                    
                            'default' => 'self',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TARGET'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TARGET_DESC')
                        )
                    )
                ),
            	'color' => array(
                    'type'    => 'color',
                    'values'  => array( ),
                    'default' => '#FFFFFF',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
                    'child' => array(
                        'background' => array(
                            'type'    => 'color',
                            'values'  => array( ),
                            'default' => '#2D89EF',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC')
                        ),
                    )
                ),
            	'icon' => array(
                    'type'    => 'icon',
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
                    'child'   => array(
			                        'icon_right' => array(
						                    'type'    => 'icon',
						                    'default' => '',
						                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_RIGHT'),
						                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_RIGHT_DESC'),
				                    		)
                				),
                			),
            	'subtitle' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DESCRIPTION'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DESCRIPTION_DESC'),
                    'child'   => array(
                        'radius' => array(
                            'default' => '3px',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RADIUS_DESC')
                        ),
                        'animated_icon' => array(
                        	'type' => 'bool',
                        	'default' => 'no',
                        	'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_ANIMATED_ICON'),
                        	'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_ANIMATED_ICON_DESC')
                        ),
                   )
                ),
                'size' => array(
                    'type' => 'select',
					'default' => 'sm',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE_DESC'),
					'values' => array(
						'xs'=>'Extra small devices',
						'sm' => 'Small devices Tablets ',
						'default' =>'Medium devices',
						'lg' =>'Large devices Desktops ',
						'huge' =>'Huge devices Desktops '	
						),
					'child' => array(
								'full'  => array('type' => 'bool',
													'default' => 'no',
													'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDE'),
													'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDE_DESC'),
												),
								'center'  => array('type' => 'bool',
													'default' => 'no',
													'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER'),
													'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER_DESC'),
												),
							),
                ),
                'content'  => array('type' => 'textarea',
									'default' => 'Button',
									'name' => 'Content',
								),
            );
	    }
	}

?>