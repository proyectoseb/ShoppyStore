<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_testimonial_item_config {
	static function get_config() {
	        // Testimonial Item
	        return array(
	        	'author' => array(
	        		'default'=> 'TESTIMONIAL AUTHOR',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_AUTHOR'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_AUTHOR_DESC'),
	        		'child' => array(
	        			'position' => array(
	        				'default'=> 'AUTHOR POSITION',
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_POSITION'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_POSITION_DESC'),
	        			),
						'avatar' => array(
							'type' => 'media',
							'default'=> '',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_AVATAR'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_AVATAR_DESC')
						),	
	        		),
	        	),
	        	'content' => array(
	        		'type' => 'textarea',
	        		'name' => 'Content',
	        		'default' =>'Add content here'
	        	),
	        );
	    }
	}

?>