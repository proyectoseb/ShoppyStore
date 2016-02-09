<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_carousel_config {
	static function get_config() {
	        // carousel
	        return array(
				'source' => array(
                    'type'    => 'source',
                    'default' => 'none',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOURCE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROUSEL_SOURCE_DESC'),
                    'child'   => array(
                    	'style' => array(
		                    'type'   => 'select',
		                    'values' => array(
		                        '1' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE1'),
		                        '2' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE2'),
		                        '3' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE3'),
		                        '4' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE4'),
		                        '5' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE5')
		                    ),
		                    'default' => 1,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC')
		                ),
                    ),
                ),
                
                'limit' => array(
                    'type'    => 'slider',
                    'min'     => 1,
                    'max'     => 100,
                    'step'    => 1,
                    'default' => 5,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIMIT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROUSEL_LIMIT_DESC'),
                    'child'   => array(
                    	'items' => array(
		                    'type'    => 'slider',
		                    'min'     => 1,
		                    'max'     => 10,
		                    'step'    => 1,
		                    'default' => 5,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ITEMS'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ITEMS_DESC')
		                ),
		                'margin' => array(
		                    'type'    => 'slider',
		                    'min'     => 0,
		                    'max'     => 80,
		                    'step'    => 5,
		                    'default' => 10,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROUSEL_MARGIN_DESC')
		                ),
                    ),
                ),
                'image' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_DESC'),
                    'child'  => array(                        
                        'image_height' => array(
                            'type'    => 'slider',
                            'min'     => 10,
                            'max'     => 640,
                            'step'    => 10,
                            'default' => 320,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_HEIGHT'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_HEIGHT_DESC')
                        ),
                        'image_width' => array(
                            'type'    => 'slider',
                            'min'     => 10,
                            'max'     => 640,
                            'step'    => 10,
                            'default' => 360,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_WIDTH'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_WIDTH_DESC')
                        )
                    )
                ),
                'title' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROULES_TITLE_DESC'),
                    'child'  => array(
                        'title_link' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_LINK'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_LINK_DESC')
                        ),
                        'title_limit' => array(
                            'default' => '',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_LIMIT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_LIMIT_DESC')
                        )
                    )
                ),
                'intro_text' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROULES_TEXT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROULES_TEXT_DESC'),
                    'child'  => array(                        
                        'intro_text_limit' => array(
                            'default' => '60',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_LIMIT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_LIMIT_DESC')
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
                        'title_color' => array(
                            'type'    => 'color',
                            'values'  => array( ),
                            'default' => '#FFFFFF',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_COLOR'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_COLOR_DESC')
                        )
                    )
                ),
                'arrows' => array(
                    'type'    => 'bool',
                    'default' => 'no',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROWS'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROWS_DESC'),
                    'child'  => array(                        
                        'arrow_position' => array(
                            'type'   => 'select',
                            'values' => array(
                                'default'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
                                'top-left'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOP_LEFT'),
                                'top-right'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOP_RIGHT'),
                                'bottom-left'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOTTOM_LEFT'),
                                'bottom-right' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOTTOM_RIGHT'),
                            ),
                            'default' => 'default',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROW_POSITION'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROW_POSITION_DESC')
                        ),
                        'pagination' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PAGINATION'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PAGINATION_DESC')
                        )
                    )
                ),
                'autoplay' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY_DESC'),
                    'child'  => array(                        
                        'hoverpause' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVERPAUSE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVERPAUSE_DESC')
                        ),
                        'lazyload' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LAZYLOAD'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LAZYLOAD_DESC')
                        ),
                        'loop' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOOP'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOOP_DESC')
                        )
                    )
                ),                
                'delay' => array(
                    'type'    => 'slider',
                    'min'     => 1,
                    'max'     => 10,
                    'step'    => 0.5,
                    'default' => 4,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY'), 
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DELAY_DESC'),
                    'child'  => array(                        
                        'speed' => array(
                            'type'    => 'slider',
                            'min'     => 0.1,
                            'max'     => 15,
                            'step'    => 0.2,
                            'default' => 0.35,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPEED'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPEED_DESC')
                        )
                    )
                ),
            );
	    }
	}

?>