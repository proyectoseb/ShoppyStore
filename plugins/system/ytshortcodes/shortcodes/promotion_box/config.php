<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_promotion_box_config {
	static function get_config() {
	        // promotion box
	        return array(
	        	'type' => array(
	        		'type' => 'select',
	        		'default' => '',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TYPE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TYPE_DESC'),
	        		'values' => array(
	        			'' => 'None',
	        			'border' => 'Border',
	        			'background-border'=> 'Background Border',
	        			'arrow-box' => 'Arrow Box'
	        		),
	        		'child' => array(
	        			'align' => array(
			        		'type' => 'select',
			        		'default' => 'left',
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_ALIGN'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_ALIGN_DESC'),
			        		'values' => array(
			        			'left' => 'Left',
			        			'center'=> 'Center',
			        			'right' => 'Right'
			        		),
			        	),
	        		),
	        	),
	        	'title' => array(
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TITLE_DESC'),
	        		'default' => 'Promotion Title',
	        		'child' => array(
	        			'title_color' => array(
	        				'type' => 'color',
	        				'default' => '#000',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TITLE_COLOR'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TITLE_COLOR_DESC')
	        			),
	        		),
	        	),
	        	'button_text' => array(
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BUTTON_TEXT'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BUTTON_TEXT_DESC'),
	        		'default' => 'Click here',
	        		'child' => array(
	        			'button_link' => array(
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BUTTON_LINK'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BUTTON_LINK_DESC'),
			        		'default' => '',
			        	),
	        		),
	        	),
	        	'width' => array('type' => 'slider',
					'default' => 100,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
					'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_DESC"),
					'child' => array(
						'target' => array(
							'type' => 'select',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TARGET'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_TARGET_DESC'),
							'default' => '_self',
							'values'=> array(
								'_self' => 'Self',
								'_blank' => 'Blank'
							),
						),
					),
				),
				'promotion_color' => array('type'=>'color',
					'default' => '#000',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_COLOR'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_COLOR_DESC'),
					'child' => array(
						'promotion_background' => array('type'=>'color',
							'default' => '#ddd',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BACKGROUND'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BACKGROUND_DESC'),
						),
						'promotion_radius' => array(
							'default' => '3px',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_RADIUS'),
							'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_RADIUS_DESC"),
						),
					),
				),
				'button_color' => array('type'=>'color',
					'default' => '#fff',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_COLOR'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_COLOR_DESC'),
					'child' => array(
						'button_background' => array('type'=>'color',
							'default' => '#4e9e41',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BACKGROUND'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BACKGROUND_DESC'),
						),
						'button_background_hover' => array('type'=>'color',
							'default' => '#2e6b24',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BUTTON_BACKGROUND_HOVER'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_BUTTON_BACKGROUND_HOVER_DESC'),
						),
						'button_radius' => array(
							'default' => '3px',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_RADIUS'),
							'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_RADIUS_DESC"),
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