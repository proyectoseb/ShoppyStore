<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_qrcode_config {
	static function get_config() {
	        // qrcode
	        return array(
	        	'data' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE_DATA'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE_DATA_DESC'),
                    'child'   => array(
                    	'title' => array(
		                    'default' => '',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE_TITLE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE_TITLE_DESC')
		                ),
                    ),
                ),
                
                'size' => array(
                    'type'    => 'slider',
                    'min'     => 10,
                    'max'     => 1000,
                    'step'    => 10,
                    'default' => 200,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE_SIZE_DESC'),
                    'child'   => array(
                    	'margin' => array(
		                    'type'    => 'slider',
		                    'min'     => 0,
		                    'max'     => 50,
		                    'step'    => 5,
		                    'default' => 0,
		                    'name'    =>  JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
		                    'desc'    =>  JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QUCODE_MARGIN_DESC')
		                ),
		                'align' => array(
		                    'type'   => 'select',
		                    'values' => array(
		                        'none'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NONE'),
		                        'left'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
		                        'center' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER'),
		                        'right'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT'),
		                    ),
		                    'default' => 'none',
		                    'name'    =>  JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
		                    'desc'    =>  JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC')
		                    
		                ),
                    ),
                ),
                
                'link' => array(
                    'default' => '',
                    'name'    =>  JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINK'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE_LINK_DESC'),
                    'child'		=> array(
                    	'target' => array(
                    	    'type'   => 'select',
                    	    'values' => array(
                    	        'self'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SELF'),
                    	        'blank' =>  JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLANK'),
                    	    ),
                    	    'default' => 'blank',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TARGET'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TARGET_DESC')
                    	)
                    )
                ),
                'color' => array(
                    'type'    => 'color',
                    'default' => '#000000',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CODE_COLOR'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CODE_COLOR_DESC'),
                    'child'		=> array(
                    	'background' => array(
                    	    'type'    => 'color',
                    	    'default' => '#ffffff',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC')
                    	)
                    )
                ),
	        );
	    }
	}

?>