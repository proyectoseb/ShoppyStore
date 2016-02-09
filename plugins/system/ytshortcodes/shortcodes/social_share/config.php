<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_social_share_config {
	static function get_config() {
	        // accordion
	        return array(
	        	'facebook' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_FACEBOOK'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_FACEBOOK_DESC'),
                    'child'		=> array(
                    	'twitter' => array(
                            'type'    => 'bool',
                            'default' => 'yes',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_TWITTER'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_TWITTER_DESC')
                        ),
                        'googleplus' => array(
                    	    'type'    => 'bool',
                    	    'default' => 'yes',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_GOOGLE_PLUS'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_GOOGLE_PLUS_DESC')
                    	)
                    )
                ),
                'linkedin' => array(
                    'type'    => 'bool',
                    'default' => 'no',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_LINKEDIN'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_LINKEDIN_DESC'),
                    'child'		=> array(
                    	'pinterest' => array(
                    	    'type'    => 'bool',
                    	    'default' => 'no',
                    	    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_PINTEREST'),
                    	    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_PINTEREST_DESC')
                    	),
                        'tumblr' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_TUMBLR'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_TUMBLR_DESC')
                        ),
                        'pocket' => array(
                            'type'    => 'bool',
                            'default' => 'no',
                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_POCKET'),
                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SL_SHARE_BUTTON_POCKET_DESC')
                        )
                    )
                ),
	        );
	    }
	}

?>