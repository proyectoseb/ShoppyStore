<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_portfolio_config {
	static function get_config() {
	        // Portfolio
	        return array(
	        	'style' => array(
                    'type' => 'select',
                    'values' => array(
                        1 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE1'),
                        2 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE2'),
                        3 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE3'),
                        4 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE4')
                    ),
                    'default' => 1,
                    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
                    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
                    'child' => array(
                    	'padding' => array(
		                    'default' => 10,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_PADDING'), 
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_PADDING_DESC')
		                ),
                    ),
                ),
                'source' => array(
                    'type'    => 'article_source',
                    'default' => 'none',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOURCE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOURCE_DESC')
                ),
                'limit' => array(
                    'type'    => 'slider',
                    'min'     => 5,
                    'max'     => 100,
                    'step'    => 1,
                    'default' => 15,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ITEM_LIMIT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIMIT_DESC'),
                    'child'   => array(
                        'intro_text_limit' => array(
                            'type'    => 'slider',
                            'min'     => 0,
                            'max'     => 500,
                            'step'    => 5,
                            'default' => 50,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_LIMIT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_TEXT_LIMIT_DESC')
                        )
                    )
                ),
                'grid_type' => array(
                    'type'       => 'select',
                    'values'     => array(
                        0  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RANDOM'),
                        1  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_1'),
                        2  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_2'),
                        3  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_3'),
                        4  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_4'),
                        5  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_5'),
                        6  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_6'),
                        7  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_7'),
                        8  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_8'),
                        9  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_9'),
                        10 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_10'),
                        11 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_11'),
                        12 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_12'),
                        13 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_13'),
                        14 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_14'),
                        15 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_15'),
                        16 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_16'),
                        17 => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_17')
                    ),
                    'default' => 0,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GRID_TYPES'), 
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GRID_TYPES_DESC'),
                    'child'   => array(
                        'animation' => array(
                            'type' => 'select',
                            'values' => array(
                                'fade'        => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FADE'),
                                'rotate'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ROTATE'),
                                'scale'       => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SCALE'),
                                'rotatescale' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ROTATESCALE'),
                                'pagetop'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PAGETOP'),
                                'pagebottom'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PAGEBOTTOM'),
                                'pagemiddle'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PAGEMIDDLE')
                            ),
                            'default' => 'default',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_ANIMATION'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_ANIMATION_DESC')
                        )
                    )
                ),
                'speed' => array(
                    'type'    => 'slider',
                    'min'     => 300,
                    'max'     => 1500,
                    'step'    => 1,
                    'default' => 850,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPEED'), 
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPEED_DESC'),
                    'child'   => array(
                        'rotate' => array(
                            'type'    => 'slider',
                            'min'     => 0,
                            'max'     => 99,
                            'step'    => 1,
                            'default' => 99,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_ROTATE'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_ROTATE_DESC')
                        ),
                        'delay' => array(
                            'type'    => 'slider',
                            'min'     => 0,
                            'max'     => 500,
                            'step'    => 1,
                            'default' => 20,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY_DESC')
                        )
                    )
                ),
                'border' => array(
                    'type'    => 'border',
                    'default' => '0px solid #000',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC')
                ),
                
	        );
	    }
	}

?>