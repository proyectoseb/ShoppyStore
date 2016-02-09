<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
	class YT_Shortcode_pricing_tables_item_config {
	static function get_config() {
	        // Pringcing tables item
	        return array(
	        	'title' => array(
	        		'default' => 'Pricing Tables',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_TITLE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_TITLE_DESC'),
	        		'child' => array(
	        			'icon_name' => array(
	        				'type' => 'icon',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_ICON'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
	        				'default' => 'icon:plus'
	        			),
	        		),
	        	),
	        	'button_link' => array(
	        		'default' => 'http://',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_BUTTON_LINK'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_BUTTON_LINK_DESC'),
	        		'child' => array(
	        			'button_label' => array(
	        				'default' => 'Pringcing button label',
			        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_BUTTON_LABEL'),
			        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_BUTTON_LABEL_DESC'),
	        			),
	        		),
	        	),
	        	'price' => array(
	        		'default' => '',
	        		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_PRICE'),
	        		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_PRICE_DESC'),
	        		'child' => array(
	        			'per' => array(
	        				'type' => 'bool',
	        				'default' => 'yes',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_PER'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_PER_DESC')
	        			),
	        			'featured' => array(
	        				'type' => 'bool',
	        				'default' => 'no',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_FEATURED'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_FEATURED_DESC')
	        			),
	        			'text' => array(
	        				'default' => '',
	        				'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_TEXT'),
	        				'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_TEXT_DESC')
	        			),
	        		),
	        	),
	        	'background' => array(
				    'type' => 'color',
				    'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_BACKGROUND'),
				    'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
				    'default' => '#4e9e41',
				    'child' => array(
				    	'color'=> array(
				    		'type' => 'color',
				    		'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_ITEM_COLOR'),
				    		'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
				    		'default' => '#fff'
				    	),
				    ),	
	        	),
	        	'content' => array(
	        		'type' => 'textarea',
	        		'name' => 'Content',
	        		'default' => "<ul class='pricing-list'>
								    <li> Disk Space <strong>10 GB</strong> </li>
								    <li> Bandwidth <strong>Unlimited </strong>  </li>
								    <li> Setup Free <a class='boxtip' rel='tooltip' data-original-title='TEXT_TOOLTIP' href='#' >(?) </a></li>
								    <li> <strong>1 </strong> Free Email Accounts <a class='boxtip' rel='tooltip' data-original-title='TEXT_TOOLTIP' href='#' >(?) </a></li>
								    <li> <strong>1 </strong> FTP Accounts</li>
								    <li> Half Privacy</li>
								</ul>"
	        	),
	        );
	    }
	}

?>