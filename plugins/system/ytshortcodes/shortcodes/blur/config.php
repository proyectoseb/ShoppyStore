<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
class YT_Shortcode_blur_config {
static function get_config() {
        // blur
        return array(
			'blur' => array(
				'type' => 'slider',
				'default' => 2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLUR'),
				'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_BLUR_DESC"),
				'child'=> array(
					'hover_blur' => array(
						'type' => 'slider',
						'default' => 1,
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
						'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVER_BLUR'),
						'desc' => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_HOVER_BLUR_DESC"),
					),
				),
			),
			
			'content'  => array('type' => 'textarea',
				'default' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
				'name' => 'Content',
			),	
	   );
	}
}
?>