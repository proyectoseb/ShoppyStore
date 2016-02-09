<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;

class YT_Shortcode_accordion_config {
static function get_config() {
        // accordion
        return array(
			'align' => array('type' => 'select',
				'default' => 'left',
				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
				'values' => array(
					'left'=>'Left',
					'right' => 'Right',
					'none' =>'None'	
				),
				'child'  => array(
	                'width' => array('type' => 'slider',
						'default' => 100,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_%_DESC"),
					),
	                'style' => array(
	                	'type'    => 'select',
	                    'default' => 'basic',
	                    'values'   => array(
        					'basic' => 'Basic',
        					'line'  => 'Line',
        					'border'=> 'Border', 
        				),
	                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
	                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC')
	                )
	            )
			),
			'color_background_active' => array(
				'type' => 'bool',
				'default' =>'yes',
				'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_BACKGROUND_ACTIVE"),
				'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_BACKGROUND_ACTIVE_DESC"),
				'child' => array(
					'background_active'=>array(
						'type' => 'color',
						'default'=> '#fff',
						'name' =>JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_BACKGROUND_ACTIVE"),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_BACKGROUND_ACTIVE_DESC"),
					),
					'color_active' => array(
						'type' => 'color',
						'default' => '#ccc',
						'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_COLOR_ACTIVE"),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_COLOR_ACTIVE_DESC"),		
					),
				),
			),
			
			
		);
	}
}
?>