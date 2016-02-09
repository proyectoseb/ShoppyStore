<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_google_font_config {
	static function get_config() {
	        // google font
	        return array(
	        	'font'=>array(
	        		'default' => '',
	        		'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_FONT'),
	        		'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_FONT_DESC'),
	        		'child'   => array(
	        			'size' => array('type' => 'slider',
								'default' => 14,
								'min' => 1,
								'max' => 50,
								'step' => 1,
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
								'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE_DESC"),
							),
	        		),
	        	),
        		'color' => array('type'=>'color',
					'default' => '#000',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_COLOR'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
					'child' => array(
						'font_weight' =>array(
							'default' => '',
							'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_FONT_WEIGHT'),
							'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_FONT_WEIGHT_DESC')
						),
					),
				),
				'margin' => array(
					'default' => '',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_MARGIN'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_MARGIN_DESC'),
					'child' => array(
						'align' => array('type' => 'select',
								'default' => 'left',
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
								'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
								'values' => array(
									'left'  => 'Left',
									'right' => 'Right',
									'none'  => 'None'	
								),
							),
	        		),
	        	),
	        	'content' => array(
	        		'type'  => 'textarea',
	        		'default' => 'Add content here',
	        		'name' => 'Content'
	        	),
	        );
	    }
	}

?>