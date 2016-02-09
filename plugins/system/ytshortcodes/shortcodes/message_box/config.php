<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_message_box_config {
	static function get_config() {
	        // Message box
	        return array(
	        	'title' => array(
	        		'default' => 'Title Message',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MESSAGE_BOX_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MESSAGE_BOX_TITLE_DESC')
	        	),
	        	'type' => array('type' => 'select',
					'default' => 'left',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MESSAGE_BOX_TYPE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC'),
					'values' => array(
						'error'=>'Error',
						'info' => 'Info',
						'warning' =>'Warning',
						'success' => 'Success'	
					),
					'child' => array(
						'close'  => array(
							'type' => 'bool',
							'default' => 'yes',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MESSAGE_BOX_CLOSE'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MESSAGE_BOX_CLOSE_DESC'),
						),
					),
				),
				'content' => array(
					'type' =>'textarea',
					'default' => 'Add content here',
					'name' => 'Content'
				),
	        );
	    }
	}

?>