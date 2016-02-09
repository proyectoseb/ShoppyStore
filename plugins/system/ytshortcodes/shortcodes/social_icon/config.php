<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_social_icon_config {
	static function get_config() {
	        // social icon
	        return array(
	        	'type' => array(
	        		'type' => 'select',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_TYPE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_TYPE_DESC'),
	        		'default' => 'facebook',
	        		'values' => array(
	        			'facebook' => 'Facebook',
	        			'twitter' => 'Twitter',
	        			'google-plus' => 'Google Plus',
	        			'pinterest' => 'Pinterest',
	        			'dribbble' => 'Dribbble',
	        			'flickr' => 'Flickr',
	        			'linkedin' => 'Linkedin',
	        			'rss' => 'Rss',
	        			'skype' =>'Skype',
	        			'youtube' => 'Youtube'
	        		),
	        		'child' => array(
	        			'title' => array(
	        				'default' => 'Title',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_TITLE'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_TITLE_DESC')
	        			),
	        			'color' => array(
	        				'type' => 'bool',
	        				'default' => 'yes',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_COLOR'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_COLOR_DESC'),
	        			),
	        		),
	        	),
	        	'style' => array(
    				'type' => 'select',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_STYLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_STYLE_DESC'),
	        		'default' => 'min',
	        		'values' => array(
						'' =>'Default',
	        			'min' => 'Min',
	        			'cicle' => 'Circle',
	        			'flat' => 'Flat',
	        		),
	        		'child' => array(
	        			'size' => array(
	        				'type' => 'select',
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_SIZE'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_SIZE_DESC'),
			        		'default' => 'default',
			        		'values' => array(
			        			'small' => 'Small',
			        			'default' => 'Default',
			        			'large' => 'Large',
			        		),
	        			),
	        		),
    			),
	        	'content' => array(
	        		'type' => 'textarea',
	        		'name' => 'Content',
	        		'default' => 'http://smartaddons.com'
	        	),
	        );
	    }
	}

?>