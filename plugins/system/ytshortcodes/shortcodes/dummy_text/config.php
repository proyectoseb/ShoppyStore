<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_dummy_text_config {
	static function get_config() {
	        // dummy text
	        return array(
	        	'what' => array(
                    'type'   => 'select',
                    'values' => array(
                        'paras' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PARAS'),
                        'words' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WORDS'),
                        'bytes' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BYTES'),
                    ),
                    'default' => 'paras',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WHAT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WHAT_DESC'),
                    'child'   => array(
                        'cache' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CACHE'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CACHE_DESC')
                        )
                    )
                ),
                'amount' => array(
                    'type'    => 'slider',
                    'min'     => 1,
                    'max'     => 200,
                    'step'    => 10,
                    'default' => 1,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AMOUNT'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AMOUNT_DESC')
                ),
	        );
	    }
	}

?>
