<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_gallery_item_config {
	static function get_config() {
	        // gallery item
	        return array(
	        	'tag' => array(
	        		'default' => '',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_TAG'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_TAG_DESC'),
	        		'child' => array(
	        			'title' => array(
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_TITLE'),
			        		'default' => 'Title gallery',
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_TITLE_DESC'),
			        	), 
	        		),
	        	),
	        	'src' => array(
	        		'type' => 'media',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_SRC'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_SRC_DESC'),
	        		'default' => '',
	        		'child'=> array(
	        			'video_addr' => array(
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_VIDEO_ADDR'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_ITEM_VIDEO_ADDR_DESC'),
			        		'default' => '',
			        	),
	        		),
	        	),
	        	'content' => array(
	        		'type' => 'textarea',
	        		'name' => 'Content',
	        		'default' => 'Description image'
	        	),
	        );
	    }
	}

?>