<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_showcase_config {
	static function get_config() {
	        // showcase
	        return array(
	        	'source' => array(
                    'type'    => 'article_source',
                    'default' => 'none',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOURCE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOURCE_DESC'),
                    'child'   => array(
                        'limit' => array(
                            'type'    => 'slider',
                            'min'     => 5,
                            'max'     => 100,
                            'step'    => 1,
                            'default' => 12,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIMIT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIMIT_DESC')
                        )
                    )
                ),
                'loading_animation' => array(
                    'type'       => 'select',
                    'values'     => array(
                        'default' => 'Default',
                        'fadeIn' => 'Fade In',
                        'lazyLoading' => 'LazyLoading',
                        'fadeInToTop' => 'Fade In To Top',
                        'sequentially' => 'Sequentially',
                        'bottomToTop' => 'Bottom To Top'
                    ),
                    'default' => 'default',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOADING_ANIMATION'), 
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LOADING_ANIMATION_DESC'),
                    'child'   => array(
                        'filter_animation' => array(
                            'type'       => 'select',
                            'values'     => array(
                                'fadeOut' => 'Fade Out',
                                'quicksand' => 'Quicksand',
                                'boxShadow' => 'Box Shadow',
                                'bounceLeft' => 'Bounce Left',
                                'bounceTop' => 'Bounce Top',
                                'bounceBottom' => 'Bounce Bottom',
                                'moveLeft' => 'Move Left',
                                'slideLeft' => 'Slide Left',
                                'fadeOutTop' => 'Fade Out Top',
                                'sequentially' => 'Sequentially',
                                'skew' => 'Skew',
                                'slideDelay' => 'Slide Delay',
                                '3dflip' => '3d Flip',
                                'rotateSides' => 'Rotate Sides',
                                'flipOutDelay' => 'Flip Out Delay',
                                'flipOut' => 'Flip Out',
                                'unfold' => 'Unfold',
                                'foldLeft' => 'Fold Left',
                                'scaleDown' => 'Scale Down',
                                'scaleSides' => 'Scale Sides',
                                'frontRow' => 'Front Row',
                                'flipBottom' => 'Flip Bottom',
                                'rotateRoom' => 'Rotate Room'
                            ),
                            'default' => 'rotateSides',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FILTER_ANIMATION'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FILTER_ANIMATION_DESC')
                        ),
                        'caption_style' => array(
                            'type'       => 'select',
                            'values'     => array(
                                'pushTop' => 'Push Top',
                                'pushDown' => 'Push Down',
                                'revealBottom' => 'Reveal Bottom',
                                'revealTop' => 'Reveal Top',
                                'revealLeft' => 'Reveal Left',
                                'moveRight' => 'Move Right',
                                'overlayBottom' => 'Overlay Bottom',
                                'overlayBottomPush' => 'Overlay Push',
                                'overlayBottomReveal' => 'Overlay Reveal',
                                'overlayBottomAlong' => 'Overlay Along',
                                'overlayRightAlong' => 'Overlay Right',
                                'minimal' => 'Minimal',
                                'fadeIn' => 'Fade In',
                                'zoom' => 'Zoom',
                                'opacity' => 'Opacity'
                            ),
                            'default' => 'overlayBottomPush',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAPTION_STYLE'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAPTION_STYLE_DESC'),
                        ),
                    )
                ),
            	'horizontal_gap' => array(
                    'min'     => 0,
            	    'type'    => 'slider',
            	    'max'     => 50,
            	    'step'    => 5,
            	    'default' => 10,
            	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HORIZONTAL_GAP'), 
            	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HORIZONTAL_GAP_DESC'),
                    'child'   => array(
                        'vertical_gap' => array(
                            'type'    => 'slider',
                            'min'     => 0,
                            'max'     => 50,
                            'step'    => 5,
                            'default' => 10,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VERTICAL_GAP'), 
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VERTICAL_GAP_DESC')
                        )
                    )
            	),
                'filter' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_FILTER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_FILTER_DESC'),
                    'child'   => array(
                        'filter_deeplink' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_FILTER_DEEPLINK'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_FILTER_DEEPLINK_DESC')
                        )
                    )
                ),
                'popup_position' => array(
                    'type'       => 'select',
                    'values'     => array(
                        'bottom' => 'Bottom',
                        'top' => 'Top',
                        'above' => 'Above',
                        'below' => 'Below'
                    ),
                    'default' => 'below',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POPUP_POSITION'), 
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POPUP_POSITION_DESC'),
                    'child'   => array(
                        'popup_category' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_POPUP_CATEGORY'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_POPUP_CATEGORY_DESC'),  
                        ),
                        'popup_date' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_POPUP_DATE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_POPUP_DATE_DESC')
                        ),
                        'popup_image' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_POPUP_IMAGE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHOWCASE_POPUP_IMAGE_DESC')
                        ),
                    )
                ),
                'large' => array(
                    'type'    => 'slider',
                    'min'     => 1,
                    'max'     => 10,
                    'step'    => 1,
                    'default' => 4,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LARGE_DEVICE_ITEM'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LARGE_DEVICE_ITEM_DESC'),
                    'child'   => array(
                        'medium' => array(
                            'type'    => 'slider',
                            'min'     => 1,
                            'max'     => 5,
                            'step'    => 1,
                            'default' => 3,
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MEDIUM_DEVICE_ITEM'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MEDIUM_DEVICE_ITEM_DESC')
                        )
                    )
                ),
	        );
	    }
	}

?>