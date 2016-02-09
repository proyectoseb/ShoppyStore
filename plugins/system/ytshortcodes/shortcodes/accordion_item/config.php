<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
class YT_Shortcode_accordion_item_config {
	static function get_config(){
		return array(
			'title' => array('type' => 'text',
				'default' => 'Item Title',
				'name'  => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE"),
				'desc'  => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_DESC"),
				'child' => array(
					'icon' => array('type'=>'icon',
						'default' => 'icon:heart',
						'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ICON"),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC"),
					),
				),
			),
			
		   	'icon_color'=>array(
				'type' => 'color',
				'default'=> '#ccc',
				'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR"),
				'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_COLOR_DESC"),
				'child'=>array(
					'icon_size' => array(
						'type' => 'slider',
						'default' => 16,
						'min'  => 14,
						'max'  => 20,
						'step' => 1,
						'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE"),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_SIZE_DESC"),
						),
				),
			),
			'color_title'=>array(
				'type' => 'color',
				'default'=> '#000',
				'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_COLOR_TITLE"),
				'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_COLOR_TITLE_DESC"),
				'child' => array(
					'color_desc'=>array(
						'type' => 'color',
						'default'=> '#000',
						'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_COLOR_DESC"),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_ITEM_COLOR_DESC_DESC"),
					),
					
				),
			),
			
		   	'active' => array(
				'type' => 'bool',
				'default' => 'no',
				'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACTIVE_ACCORDION"),
				'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACTIVE_ACCORDION_DESC"),
				'child' => array(
					'background'=>array(
						'type' => 'color',
						'default'=> '#fff',
						'name' =>JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND"),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC"),			
					),
					'border_color' => array(
						'type' => 'color',
						'default' => '#ccc',
						'name' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_COLOR_ACCORDION_ITEM"),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_COLOR_ACCORDION_ITEM_DESC"),		
					),
				),
			),
			'content' => array('type' => 'textarea',
			   'default' => 'Add Content Here',
			   'name'  => 'Content',
			),			  
		);
	}
	
}

?>