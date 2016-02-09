<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
class YT_Shortcode_contact_form_config {
static function get_config() {
    // contact_form
    return array(
    	'email' => array(
            'default' => '',
            'name'    => 'Email',
            'desc'    => 'Enter your admin email for get email'
        ),
        'type' => array('type' => 'select',
						'default' => 'border',
						'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE'),
						'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TYPE_DESC'),
						'values' => array(
									'border'=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_BORDER'),
									'line'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_LINE'),
									'dot1'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_DOT1'),	
									'dot2'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_DOT2'),
								),
						'child' => array(
									'margin' => array(
							                    'default' => '20px',
							                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN'),
							                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MARGIN_DESC')                           
							                ),
								),
					),
        'name' => array(
                    'type'    => 'bool',
                    'default' => 'yes',
                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NAME'),
                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_NAME_DESC'),
                    'child'   => array(
                    				'label_show' => array(
					                            'type'    => 'bool',
					                            'default' => 'yes',
					                            'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LABEL_SHOW'),
					                            'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LABEL_SHOW_DESC'),
		                            			),
		                            'subject' => array(
					                    'type'    => 'bool',
					                    'default' => 'yes',
					                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUBJECT'),
					                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUBJECT_DESC'),
				                	),
                    			),
                ),
        'color_name' => array(
						'type'=>'color',
							'default' => '#000',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_NAME_COLOR'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
							'child' =>array(
										'background_name' => array('type'=>'color',
										'default' => '#fff',
										'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_NAME_BACKGROUND'),
										'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
									),
						'icon_name' => array(
									'type' => 'icon',
									'default' => '',
									'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_NAME_ICON'),
									'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
									),									
							),
					),
		'color_email' => array(
						'type'=>'color',
							'default' => '#000',
							'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_EMAIL_COLOR'),
							'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
							'child' =>array(
										'background_email' => array('type'=>'color',
										'default' => '#fff',
										'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_EMAIL_BACKGROUND'),
										'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
										),
										'icon_email' => array(
													'type' => 'icon',
													'default' => '',
													'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_EMAIL_ICON'),
													'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
													),									
									),
					),			
        'color_subject' => array(
        						'type'=>'color',
								'default' => '#000',
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_SUBJECT_COLOR'),
								'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLOR_DESC'),
								'child' =>array(
											'background_subject' => array('type'=>'color',
											'default' => '#fff',
											'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_SUBJECT_BACKGROUND'),
											'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
											),
											'icon_subject' => array(
														'type' => 'icon',
														'default' => '',
														'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_SUBJECT_ICON'),
														'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
														),									
												),
							),
        'color_message' => array(
        						'type'=>'color',
								'default' => '#000',
								'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_MESSAGE_COLOR'),
								'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_MESSAGE_COLOR_DESC'),
								'child' =>array(
											'background_message' => array('type'=>'color',
																			'default' => '#fff',
																			'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_MESSAGE_BACKGROUND'),
																			'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BACKGROUND_DESC'),
																	),
											'textarea_height' => array(
												                    'default' => '120',
												                    'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXTAREA_HEIGHT'),
												                    'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TEXTAREA_HEIGHT_DESC')                           
												                ),
								),			
							),
        'reset' => array(
                        'type'    => 'bool',
                        'default' => 'yes',
                        'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RESET'),
                        'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RESET_DESC'),
                        'child'   => array(
                        			'btn_reset' => array('type' => 'select',
													'default' => 'warning',
													'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RESET_STYLE'),
													'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
													'values' => array(
																'default' => 'Default',
																'primary' => 'Primary',
																'success' => 'Success',	
																'info'    => 'Info',
																'warning' => 'Warning',
																'danger'  => 'Danger',
																'border'  => 'Border'
																),
											),
                        		),
                    ),
        'submit_button_text' => array(
                        'default' => '',
                        'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUBMIT'),
                        'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUBMIT_DESC'),
                        'child'   => array(
                        			'btn_submit' => array('type' => 'select',
													'default' => 'info',
													'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SUBMIT_STYLE'),
													'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_STYLE_DESC'),
													'values' => array(
																'default' => 'Default',
																'primary' => 'Primary',
																'success' => 'Success',	
																'info'    => 'Info',
																'warning' => 'Warning',
																'danger'  => 'Danger',
																'border'  => 'Border'
																),
											),
                        		),
                    ),
        'add_field' => array(
                        'default' => '',
                        'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ADD_FIELD'),
                        'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ADD_FIELD_DESC'),
                        ),
    );
    }
}

?>