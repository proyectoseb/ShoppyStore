<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_player_list_item_config {
	static function get_config() {
	        // accordion
	        return array(
	        	'song' => array(
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_ITEM_SONG'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_ITEM_SONG_DESC'),
	        		'default'=>'Song Name',
	        		'child'=> array(
	        			'artist'=> array(
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_ITEM_ARTIST'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_ITEM_ARTIST_DESC'),
			        		'default'=>'Artist Name',
	        			),
						'src' => array(
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_ITEM_SRC'),
							'desc'=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_ITEM_SRC_DESC'),
							'default' => ''
						),
	        		),
	        	),
	        );
	    }
	}

?>