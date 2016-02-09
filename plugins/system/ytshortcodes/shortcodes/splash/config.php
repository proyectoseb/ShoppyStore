<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_splash_config {
	static function get_config() {
	        // Splash screen
	        return array(
	        	'style' => array(
                    'type'    => 'select',
                    'default' => 'dark',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
                    'values'  => array(
                        'dark'               => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DARK'),
                        'dark-boxed'         => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DARK_BOXED'),
                        'light'              => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHT'),
                        'light-boxed'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHT_BOXED'),
                        'blue-boxed'         => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLUE_BOXED'),
                        'light-boxed-blue'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHT_BOXED_BLUE'),
                        'light-boxed-green'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHT_BOXED_GREEN'),
                        'light-boxed-orange' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHT_BOXED_ORANGE'),
                        'maintenance'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MAINTENANCE'),
                        'incisor'            => 'Incisor'
                    ),
                    'child' => array(
                    	'align' => array('type' => 'select',
							'default' => 'left',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
							'values' => array(
								'left'=>'Left',
								'right' => 'Right',
								'none' =>'None'	
							),
						),
						'delay' => array(
		                    'type'    => 'slider',
		                    'min'     => 0,
		                    'max'     => 120,
		                    'step'    => 1,
		                    'default' => 0,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY_DESC')
		                ),
                    ),
                ),
                'width' => array(
                    'type'    => 'slider',
                    'min'     => 100,
                    'max'     => 1600,
                    'step'    => 20,
                    'default' => 480,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_DESC'),
                    'child'		=> array(
                    	'opacity' => array(
                    	    'type'    => 'slider',
                    	    'min'     => 0,
                    	    'max'     => 100,
                    	    'step'    => 5,
                    	    'default' => 80,
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_OPACITY'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_OPACITY_DESC')
                    	)
                    )
                ),
                'url' => array(
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_DESC'),
                    'default' => 'http://www.smartaddons.com',
                    'child'		=> array(
                    	'onclick' => array(
                    	    'type'    => 'select',
                    	    'default' => 'close-bg',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ONCLICK'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ONCLICK_DESC'),
                    	    'values'  => array(
                    	        'none'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NONE'),
                    	        'close'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE'),
                    	        'close-bg' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE_BG'),
                    	        'url'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL')
                    	    )
                    	)
                    )
                ),
                'close' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE_DESC'),
                    'child'		=> array(
                    	'esc' => array(
                    	    'type'    => 'bool',
                    	    'default' => 'yes',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ESC'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ESC_DESC')
                    	    
                    	)
                    )
                ),
                'background' => array(
					'type' => 'color',
					'default' => '#4e9e41',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
					'child' => array(
						'color' => array(
							'type' => 'color',
							'default' => '#fff',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
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