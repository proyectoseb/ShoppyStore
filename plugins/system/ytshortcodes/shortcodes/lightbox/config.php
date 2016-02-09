<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_lightbox_config {
	static function get_config() {
	        // Lightbox
	        return array(
	        	'title' => array(
	        		'default' => 'Title',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_TITLE_DESC'),
	        		'child' => array(
	        			'align' => array(
	        				'type' => 'select',
							'default' => 'left',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
							'values' => array(
								'left'=>'Left',
								'right' => 'Right',
								'none' =>'None'	
							),
						),
	        		),
	        	),
                'type' => array(
                    'type'   => 'select',
                    'values' => array(
                        'none' => 'None',
                        'inline' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INLINE')
                    ),
                    'default' => 'iframe',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_TYPE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_TYPE_DESC'),
                    'child'   => array(
                    	 'style' => array(
		                    'type'   => 'select',
		                    'values' => array(
		                        'none' => 'None',
		                        'borderInner' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_INLINE'),
		                        'shadow' => "Shadow",
		                        'border' => "Border",
		                        'reflect' => "Reflect"
		                    ),
		                    'default' => 'iframe',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_STYLE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
		                ),
                    ),
                ),
                'src' => array(
                	'type' => 'media',
                	'default' =>'',
                	'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_SRC'),
                	'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_SRC_DESC'),
                	'child' => array(
                		'video_addr' => array(
			                	'default' =>'',
			                	'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_VIDEO_ADDR'),
			                	'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_VIDEO_ADDR_DESC'),
			            ),
                	),
                ),
                'width' =>array(
                	'default' =>'100%',
                	'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_WIDTH'),
                	'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_WIDTH_DESC'),
                	'child' => array(
                		'height' =>array(
		                	'default' =>'100%',
		                	'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_HEIGHT'),
		                	'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_HEIGHT_DESC'),
		                ),
		                'lightbox' => array(
		                	'type' => 'bool',
							'default' => 'yes',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_LIGHTBOX'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_LIGHTBOX_DESC'),
		                ),
                	),
                ),
                'description' => array(
                	'type' => 'textarea',
                	'default' => '',
                	'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_DESCRIPTION'),
                	'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_DESCRIPTION_DESC'),
                ),
	        );
	    }
	}

?>