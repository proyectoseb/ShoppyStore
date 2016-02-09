<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_url_underline_config {
	static function get_config() {
	        // url underline
	        return array(
	        	'href'=> array(
	        		'default' => 'http://',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE_HREF'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE_HREF_DESC'),
	        		'child' => array(
	        			'align' => array('type' => 'select',
							'default' => 'left',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC'),
							'values' => array(
								'left'=>'Left',
								'right' => 'Right',
								'none' =>'None'	
							),
						),
						'padding' => array(
							'default'=> '1px',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE_PADDING'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE_PADDING_DESC'),
						),
	        		),
	        	),
	        	'background' => array(
	        		'type'=>'color',
					'default' => '#fff',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
					'child' => array(
						'color' => array(
			        		'type'=>'color',
							'default' => '#000',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
			        	),
			        	'font_size' => array(
							'default' => '12px',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE_FONT_SIZE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE_FONT_SIZE_DESC'),
			        	),
					),
	        	),
	        	'border' => array(
					'type' => 'border',
					'default' => '1px solid #ff0',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC'),
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