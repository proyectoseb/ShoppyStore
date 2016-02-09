<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_popovers_config {
	static function get_config() {
	        // Popovers
	        return array(
	        	'style' => array(
            	    'type'   => 'select',
            	    'values' => array(
            	        'light'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHT'),
            	        'dark'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DARK'),
            	        'yellow'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_YELLOW'),
            	        'green'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GREEN'),
            	        'red'       => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RED'),
            	        'blue'      => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLUE'),
            	        'youtube'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_YOUTUBE'),
            	        'tipsy'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TIPSY'),
            	        'bootstrap' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOOTSTRAP'),
            	        'jtools'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_JTOOLS'),
            	        'tipped'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TIPPED'),
            	        'cluetip'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLUETIP'),
            	    ),
            	    'default' => 'yellow',
            	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE'),
            	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
            	    'child'  => array(
            	    	'title' => array(
		                    'default' => '',
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TITLE_DESC')
		                ),
		                'text' => array(
		                    'default' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_DEFAULT'),
		                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_TEXT'),
		                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_TEXT_DESC')
		                ),
            	    ),
            	),
                'size' => array(
                    'type'   => 'select',
                    'values' => array(
                        'default' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DEFAULT'),
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                        '5' => 5,
                        '6' => 6,
                    ),
                    'default' => 'default',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SIZE_DESC'),
                    'child'		=> array(
                    	'behavior' => array(
                    	    'type'   => 'select',
                    	    'values' => array(
                    	        'hover'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HOVER'),
                    	        'click'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ONCLICK'),
                    	        'always' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALWAYS')
                    	    ),
                    	    'default' => 'hover',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BEHAVIOR'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BEHAVIOR_DESC')   
                    	),
						'position' => array(
                    	    'type'   => 'select',
                    	    'values' => array(
                    	        'top'  => 'Top',
                    	        'right'  => 'Right',
								'bottom'  => 'Bottom',
								'left' => 'Left'
                    	    ),
                    	    'default' => 'top',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POPOVERS_POSITION'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POPOVERS_POSITION_DESC')   
                    	)
                    )
                ),
                'shadow' => array(
                    'type'    => 'bool',
                    'default' => 'no',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_SHADOW_DESC'),
                    'child'		=> array(
                    	'rounded' => array(
                    	    'type'    => 'bool',
                    	    'default' => 'no',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ROUND'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_ROUND_DESC')
                    	),
                    	'close' => array(
                    	    'type'    => 'bool',
                    	    'default' => 'no',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLOSE_DESC')
                    	)
                    )
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