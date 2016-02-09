<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_columns_item_config {
	static function get_config() {
	        // columns item
	        return array(
	        	'col' => array('type' => 'slider',
								'default' => 4,
								'min' => 1,
								'max' => 12,
								'step' => 1,
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_COL'),
								'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_COL_DESC"),
								'child' => array(
											'offset' => array('type' => 'slider',
															'default' => 0,
															'min' => 0,
															'max' => 12,
															'step' => 1,
															'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_OFFSET'),
															'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_OFFSET_DESC"),
															),
												),
							),
				'content'  => array('type' => 'textarea',
									'default' => 'Add content here',
									'name' => 'Content'
								),	
	        );
	    }
	}

?>