<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_gallery_config {
	static function get_config() {
	        // gallery
	        return array(
	        	'title' => array(
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_TITLE'),
	        		'default' => 'Title gallery',
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_TITLE_DESC'),
	        		'child' => array(
	        			'columns' => array('type' => 'slider',
							'default' => 3,
							'min' => 1,
							'max' => 5,
							'step' => 1,
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_COLUMN'),
							'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_COLUMN_DESC"),
						),
	        		),
	        	), 
				'width' => array(
					'default' => '500',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_DESC'),	
					'child' => array(
						'height' => array(
							'default' => '500',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEIGHT'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEIGHT_DESC')	
						),
					),
				),	
				'align' => array('type' => 'select',
					'default' => 'left',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
					'values' => array(
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right'	,
					),
					'child'=> array(
						'caption' => array('type' => 'select',
							'default' => '0',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAPTION_STYLE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAPTION_STYLE_DESC'),
							'values' => array(
								'0'  => 'default',
								'1'  => 'Caption 1',
								'2'  => 'Caption 2',	
							),
						),
					),
				),
				'border' => array(
					'type' => 'border',
					'default' => '0px solid #4e9e41',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_BORDER'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_BORDER_DESC'),
				),
				'hover' => array(
					'type' => 'select',
					'default' => '1',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_HOVER'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_HOVER_DESC'),
					'values' => array(
						'1' => 'Hover 1',
						'2' => 'Hover 2'
					),
					'child' => array(
						'padding' => array(
							'default' => '0px',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_PADDING'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_PADDING_DESC')
						),
					),
				),
	        );
	    }
	}

?>