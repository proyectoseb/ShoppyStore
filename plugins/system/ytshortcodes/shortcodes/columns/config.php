<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_columns_config {
	static function get_config() {
	        // columns
	        return array(
	        	'grid'  => array('type' => 'bool',
									'default' => 'yes',
									'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_GRID'),
									'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_GRID_DESC'),
							),
	        );
	    }
	}

?>