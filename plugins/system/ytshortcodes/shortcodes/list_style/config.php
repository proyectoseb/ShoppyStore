<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_list_style_config {
	static function get_config() {
	        // list style
	        return array(
	        	'type' => array(
	        		'type' => 'select',
					'default' => 'left',
					'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIST_STYLE_TYPE'),
					'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC'),
					'values' => array(
						'disc'=>'Disc',
						'circle' => 'Circle',
						'square' =>'Square',
						'check'  => 'Check',
						'arrow'  => 'Arrow',
						'star'   => 'Star',
						'roman'  => 'Roman',
						'decimal' => 'Decimal',
						'alpha'	=> 'Alpha',
						'numblocks1' =>'Number blocks 1',
						'numblocks2' =>'Number blocks 2',
						'numblocks3' =>'Number blocks 3',
						'smallnumber1' => 'Small number 1',
						'smallnumber2' => 'Small number 2',
						'smallnumber3' => 'Small number 3',
						'smallnumber4' => 'Small number 4',
						'smallnumber5' => 'Small number 5',
						'smallnumber6' => 'Small number 6',
					),
					'child' => array(
						'color'=>array(
							'type'=>'color',
							'default' => '#000',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIST_STYLE_COLOR'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
						),
					),
				),
				
	        );
	    }
	}

?>