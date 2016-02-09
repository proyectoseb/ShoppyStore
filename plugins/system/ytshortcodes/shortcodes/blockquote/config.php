<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
class YT_Shortcode_blockquote_config {
static function get_config() {
        // accordion
        return array(
			'title' => array(
				'default' => 'SOMEONE_FAMOUS_TITLE',
				'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE'),
				'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_DESC'),
			),
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
						'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_%_DESC'),
						),
		        )
			),
		   'color' => array('type'=>'color',
				'default' => '#f00',
				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
				'child' => array(
					'border' => array(
						'type' => 'color',
						'default' => '#ccc',
						'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLOCKQUOTE_BORDER_COLOR'),
						'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLOCKQUOTE_BORDER_COLOR_DESC'),
					),
				),
			),
		   	'content' => array('type' => 'textarea',
							'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum, maiores esse temporibus accusantium quas soluta quis sed rerum. Sapiente, culpa fugit sit est laboriosam odit.',
							'name' => 'Content',
							),
				   );
	}
}
?>