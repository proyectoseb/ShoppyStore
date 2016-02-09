<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_player_list_config {
	static function get_config() {
	        // player list
	        return array(
	        	'title'=> array(
	        		'name'=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_TITLE'),
	        		'desc'=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_TITLE_DESC'),
	        		'default' => 'Title player list'
	        	),
	        );
	    }
	}

?>