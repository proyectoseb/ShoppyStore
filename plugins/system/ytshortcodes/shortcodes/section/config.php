<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_section_config {
	static function get_config() {
	        // Section
	        return array(
	        	'background' => array(
                    'type'    => 'color',
                    'default' => '#ffffff',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
                    'child'		=> array(
                    	'color' => array(
                    	    'type'    => 'color',
                    	    'default' => '#333333',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXT_COLOR_DESC')
                    	)
                    )
                ),
                'image' => array(
                    'type'    => 'media',
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_DESC' ),
                    'child'		=> array(
                    	'parallax' => array(
                    	    'type'    => 'bool',
                    	    'default' => 'yes',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PARALLAX'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PARALLAX_DESC')
                    	)
                    )
                ),
                'speed' => array(
                    'type'    => 'slider',
                    'min'     => 1,
                    'max'     => 12,
                    'step'    => 1,
                    'default' => 10,
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPEED'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPEED_DESC'),
                    'child'   => array(
                    	'max_width' => array(
		                    'type'    => 'slider',
		                    'min'     => 0,
		                    'max'     => 1600,
		                    'step'    => 10,
		                    'default' => 960,
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_WIDTH_DESC')
		                ),
		                'font_size' => array(
		                	'default' => '12px',
		                	'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SECTION_FONT_SIZE'),
		                	'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SECTION_FONT_SIZE_DESC')
		                ),
                    ),
                ),
                
                'margin' => array(
                    'default' => '0px',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN_DESC'),
                    'child'		=> array(
                    	'padding' => array(
                    	    'default' => '0px',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PADDING_DESC')
                    	),
                    	'text_align' => array(
                    	    'type'    => 'select',
                    	    'default' => 'left',
                    	    'values'  => array(
                    	        'left'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LEFT'),
                    	        'center' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CENTER'),
                    	        'right'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIGHT')
                    	    ),
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALIGN_DESC')
                    	)
                    )
                ),
                'border' => array(
                    'type'    => 'border',
                    'default' => '1px solid #cccccc',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BORDER_DESC')
                ),
                'text_shadow' => array(
                    'type'    => 'shadow',
                    'default' => '0 1px 10px #ffffff',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW_DESC')
                ),
                'url_youtube' => array(
                    'default' => '',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_YOUTUBE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_YOUTUBE_DESC'),
                    'child'   => array(
                    	'url_webm' => array(
		                    'default' => '',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_WEBM'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_WEBM_DESC'),
		                    
		                ),
                    ),
                ),
                'content' => array(
                	'type' => 'textarea',
                	'name' => 'Content',
                	'default' => 'Add content here'
                ),
	        );
	    }
	}

?>