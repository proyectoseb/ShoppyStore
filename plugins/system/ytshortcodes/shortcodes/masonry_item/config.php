<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_masonry_item_config {
	static function get_config() {
	        // masonry item
	        return array(
	        	'width'=> array(
	        		'default'=> '',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_WIDTH'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_WIDTH_DESC'),
	        		'child' => array(
	        			'size' => array('type' => 'select',
							'default' => '',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_SIZE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_SIZE_DESC'),
							'values' => array(
								''  => 'None',
								'1' =>'1',
								'2' =>'2',
								'3' =>'3',
								'4' =>'4',
								'5' =>'5',
								'6' =>'6',
								'7' =>'7',
								'8' =>'8',
								'9' =>'9',
								'10' =>'10',
								'11' =>'11',
								'12' =>'12',	
							),
						),
	        		),
	        	),
	        	'medium_size' => array('type' => 'select',
					'default' => '',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_MEDIUM_SIZE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_MEDIUM_SIZE_DESC'),
					'values' => array(
						''  => 'None',
						'1' =>'1',
						'2' =>'2',
						'3' =>'3',
						'4' =>'4',
						'5' =>'5',
						'6' =>'6',
						'7' =>'7',
						'8' =>'8',
						'9' =>'9',
						'10' =>'10',
						'11' =>'11',
						'12' =>'12',	
					),
					'child' => array(
						'small_size' => array('type' => 'select',
							'default' => '',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_SMALL_SIZE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_SMALL_SIZE_DESC'),
							'values' => array(
								''  => 'None',
								'1' =>'1',
								'2' =>'2',
								'3' =>'3',
								'4' =>'4',
								'5' =>'5',
								'6' =>'6',
								'7' =>'7',
								'8' =>'8',
								'9' =>'9',
								'10' =>'10',
								'11' =>'11',
								'12' =>'12',	
							),
						),
						'extra_small_size' => array('type' => 'select',
							'default' => '',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_EXTRAL_SMALL_SIZE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_EXTRAL_SMALL_SIZE_DESC'),
							'values' => array(
								''  => 'None',
								'1' =>'1',
								'2' =>'2',
								'3' =>'3',
								'4' =>'4',
								'5' =>'5',
								'6' =>'6',
								'7' =>'7',
								'8' =>'8',
								'9' =>'9',
								'10' =>'10',
								'11' =>'11',
								'12' =>'12',	
							),
						),
					),
				),
				'content' => array(
					'type' => 'textarea',
					'default' => 'Add content here',
					'name' => "Content",
				),
	        );
	    }
	}

?>