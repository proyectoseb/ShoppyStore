<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_content_style_config {
	static function get_config() {
	        // content style
	        return array(
	        	'type_change' => array('type' => 'select',
					'default' => 'single_column',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC'),
					'values' => array(
						'single_column'=>'Single Column',
						'multiple_column' => 'Multiple Column',
						'masonry' =>'Masonry',
						'slider' => 'Slider',
						'timeline' => 'Timeline'	
					),
					'child' => array(
						'intro_text_limit' => array(
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_INTRO_TEXT_LIMIT'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_INTRO_TEXT_LIMIT_DESC'),
							'default' => ''
						),
					),
				),
	        	'source' => array(
                    'type'    => 'article_source',
                    'default' => 'none',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOURCE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_SOURCE_DESC'),
                    'child'   => array(
	                    			'limit' => array(
				                    'type'    => 'slider',
				                    'min'     => -1,
				                    'max'     => 100,
				                    'step'    => 1,
				                    'default' => 20,
				                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ITEM_LIMIT'),
				                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TIMELINE_LIMIT_DESC')
				                ),
                    		),
                ),
                
                'title' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_DESC'),
                    'child'     => array(
                        'link_title' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINK_TITLE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINK_TITLE_DESC')
                        ),
                        'date' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DATE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DATE_DESC')    
                        ),
                    )
                ),              
                'order' => array(
                    'type'     => 'select',
                    'values'   => array(
                        ''         => 'Default',
                        'title'    => 'Title',
                        'created'  => 'Created Date',
                        'hits'     => 'Hits',
                        'ordering' => 'Ordering'
                    ),
                   'default' => 'created',
                   'group'   => 'timeline',
                   'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ORDER'),
                   'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ORDER_DESCR'),
                   'child'      => array(
                        'order_by' => array(
                            'type'   => 'select',
                            'values' => array(
                                'asc'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ASC'),
                                'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DESC')
                            ),
                            'default' => 'desc',
                            'group'   => 'timeline',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ORDER_TYPE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ORDER_TYPE_DESC')
                        )
                   )
                ),
                'image' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'group'   => 'timeline',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TIMELINE_IMAGE_DESC'),
                    'child'     => array(
                        'highlight_year' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'group'   => 'timeline',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHTLIGHT_YEAR'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHTLIGHT_YEAR_DESC')
                        ),
                        'read_more' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'group'   => 'timeline',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_READMORE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_READMORE_DESC')
                        )
                    )
                ),
                'intro_text' => array(
                    'type'    => 'bool',
                    'default' => 'no',
                    'group'   => 'timeline',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INTRO_TEXT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INTRO_TEXT_DESC'),
                    'child'     => array(
                        'time' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'group'   => 'timeline',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TIME'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TIME_DESC')    
                        )
                    )
                ),
                
                'before_text' => array(
                    'default' => '',
                    'group'   => 'timeline',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BEFORE_TEXT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BEFORE_TEXT_DESC'),
                    'child'     => array(
                        'after_text' => array(
                            'default' => '',
                            'group'   => 'timeline',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AFTER_TEXT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AFTER_TEXT_DESC')
                        )
                    )
                ),
                'grid'  => array('type' => 'bool',
								'default' => 'no',
								'group' => 'multiple_column',
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_GRID'),
								'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_GRID_DESC'),
								'child' => array(
										'column' => array('type' => 'slider',
														'default' => 4,
														'min' => 2,
														'max' => 4,
														'step' => 1,
														'group' => 'multiple_column',
														'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_COL'),
														'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_COL_DESC"),
													),
										),
						),
				'gutter' =>array(
							'default' => '15',
								'group' => 'masonry',
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_GUTTER'),
								'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_GUTTER_DESC'),
								'child' => array(
										'width' => array(
														'default' => '40%',
														'group' => 'masonry',
														'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_WIDTH'),
														'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_WIDTH_DESC"),
													),
										),
						),
				'masonry_sticky_size' => array('type' => 'slider',
												'default' => 4,
												'min' => 1,
												'max' => 12,
												'step' => 1,
												'group' => 'masonry',
												'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_SIZE'),
												'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_SIZE_DESC"),
												'child' => array(
													'masonry_sticky_medium_size' => array('type' => 'slider',
																					'default' => 4,
																					'min' => 1,
																					'max' => 12,
																					'step' => 1,
																					'group' => 'masonry',
																					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_MEDIUM_SIZE'),
																					'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_MEDIUM_SIZE_DESC"),
																					),
												),
										),
				'masonry_sticky_small_size' => array('type' => 'slider',
												'default' => 6,
												'min' => 1,
												'max' => 12,
												'step' => 1,
												'group' => 'masonry',
												'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_SMALL_SIZE'),
												'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_SMALL_SIZE_DESC"),
												'child' => array(
													'masonry_sticky_extra_small_size' => array('type' => 'slider',
																					'default' => 12,
																					'min' => 1,
																					'max' => 12,
																					'step' => 1,
																					'group' => 'masonry',
																					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_EXTRA_SMALL_SIZE'),
																					'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_MASONRY_STICKY_EXTRA_SMALL_SIZE_DESC"),
																					),
												),
										),
				'transitionin' => array(
                    'type'    => 'select',
                    'values'  => array_combine( YT_Data::animations_in(), YT_Data::animations_in() ),
                    'default' => 'fadeIn',
                    'group' => 'slider',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TRANSITION_IN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TRANSITION_IN_DESC'),
                    'child'   => array(
                        'transitionout' => array(
                            'type'    => 'select',
                            'values'  => array_combine( YT_Data::animations_out(), YT_Data::animations_out() ),
                            'default' => 'fadeOut',
                            'group' => 'slider',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TRANSITION_OUT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TRANSITION_OUT_DESC')
                        )
                    )
                ),       
                'arrows' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'group' => 'slider',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROWS'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROWS_DESC'),
                    'child'   => array(
                        'arrow_position' => array(
                            'type' => 'select',
                            'values' => array(
                                'arrow-default'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
                                'arrow-top-left'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOP_LEFT'),
                                'arrow-top-right'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOP_RIGHT'),
                                'arrow-bottom-left'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOTTOM_LEFT'),
                                'arrow-bottom-right' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOTTOM_RIGHT')
                            ),
                            'default' => 'arrow-default',
                            'group' => 'slider',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROW_POSITION'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ARROW_POSITION_DESC')
                        )
                    )
                ),
                
                'pagination' => array(
                    'type'    => 'bool',
                    'default' => 'no',
                    'group' => 'slider',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PAGINATION'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PAGINATION_DESC'),
                    'child'   => array(
                        'autoplay' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'group' => 'slider',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOPLAY_DESC')
                        ),
                        'autoheight' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'group' => 'slider',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOHEIGHT'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUTOHEIGHT_DESC')
                        ),
                        'hoverpause' => array(
					                    'type'    => 'bool',
					                    'default' => 'yes',
					                    'group' => 'slider',
					                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVERPAUSE'),
					                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVERPAUSE_DESC'),
					                ),	
                    )
                ),
                
                				
	        );
	    }
	}

?>