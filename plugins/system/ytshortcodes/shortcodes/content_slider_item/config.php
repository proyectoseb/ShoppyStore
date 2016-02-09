<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_content_slider_item_config {
	static function get_config() {
	        // content_slider
	        return array(
	        	'src' => array('type' => 'media',
					   'default' => '',
					   'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER_MEDIA'),
					   'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER_MEDIA_DESC'),
					   'child' => array(
					   		'caption' => array(
					   			'type' => 'bool',
					   			'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER_CAPTION'),
					   			'default' => 'no',
					   			'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER_CAPTION_DESC')
					   		),
					   ),
		   		),
				'link'  => array(
					'type' => 'text',
					'default' => '',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER_LINK'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER_LINK_DESC')
				),	   
				'content'  => array('type' => 'textarea',
					'default' => 'Add content here',
					'name' => 'Content',
				),	
	        );
	    }
	}

?>