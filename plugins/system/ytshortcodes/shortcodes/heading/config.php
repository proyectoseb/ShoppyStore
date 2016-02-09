<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_heading_config {
	static function get_config() {
	        // heading
	        return array(
	        	'style' => array(
                    'type'   => 'select',
                    'values' => array(
                        'default'         => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
                        'modern-1-dark'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_1_DARK'),
                        'modern-1-light'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_1_LIGHT'),
                        'modern-1-blue'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_1_BLUE'),
                        'modern-1-orange' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_1_ORANGE'),
                        'modern-1-violet' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_1_VIOLET'),

                        'modern-2-dark'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_2_DARK'),
                        'modern-2-light'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_2_LIGHT'),
                        'modern-2-blue'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_2_BLUE'),
                        'modern-2-orange' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_2_ORANGE'),
                        'modern-2-violet' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODERN_2_VIOLET'),

                        'line-dark'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE_DARK'),
                        'line-light'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE_LIGHT'),
                        'line-blue'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE_BLUE'),
                        'line-orange' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE_ORANGE'),
                        'line-violet' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE_VIOLET'),
                        
                        'dotted-line-dark'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOTTED_LINE_DARK'),
                        'dotted-line-light'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOTTED_LINE_LIGHT'),
                        'dotted-line-blue'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOTTED_LINE_BLUE'),
                        'dotted-line-orange' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOTTED_LINE_ORANGE'),
                        'dotted-line-violet' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOTTED_LINE_VIOLET'),

                        'flat-dark'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLAT_DARK'),
                        'flat-light' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLAT_LIGHT'),
                        'flat-blue'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLAT_BLUE'),
                        'flat-green' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLAT_GREEN'),

                        'small-line' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SMALL_LINE'),
                        'fancy'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FANCY')
                    ),
                    'default' => 'default',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
                    'desc'    => sprintf( '%s. ', JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC')),
                    'child'   => array(
                    	'align' => array(
		                    'type'   => 'select',
		                    'values' => array(
		                        'left'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
		                        'center' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER'),
		                        'right'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT')
		                    ),
		                    'default' => 'center',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC')
		                ),
                    ),
                ),
                'heading' => array(
                    'type'   => 'select',
                    'values' => array(
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6'
                    ),
                    'default' => 'h3',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEADING_TAG'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEADING_TAG_DESC'),
                    'child'   => array(
                        'color' => array(
                            'type'    => 'color',
                            'values'  => array( ),
                            'default' => '#666',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR_DESC')
                        )
                    )
                ),
                'width' => array(
                    'type'    => 'slider',
                    'min'     => 50,
                    'max'     => 100,
                    'step'    => 5,
                    'default' => 100,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_DESC'),
                    'child'   => array(
                    	'size' => array(
		                    'type'    => 'slider',
		                    'min'     => 7,
		                    'max'     => 48,
		                    'step'    => 1,
		                    'default' => 16,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE_DESC')
		                ),
		                'margin' => array(
		                    'type'    => 'slider',
		                    'min'     => 0,
		                    'max'     => 200,
		                    'step'    => 10,
		                    'default' => 20,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEADING_MARGIN_DESC')
		                ),
                    ),
                ),
                
                'content' => array(
                	'type' => 'textarea',
                	'default' => 'Add content here',
                	'name' => 'Content'
                ),
	        );
	    }
	}

?>