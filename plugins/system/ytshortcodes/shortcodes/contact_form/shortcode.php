<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function contact_formYTShortcode ($atts,$content = null)
{
	extract(ytshortcode_atts(array(
		'type' 				 => 'border',
		'style'              => 'default',
		'email'              => '',
		'name'               => 'yes',
		'subject'            => 'yes',
		'submit_button_text' => '',
		'label_show'         => 'yes',
		'textarea_height'    => '120',
		'reset'              => 'yes',
		'margin'             => '',
		'class'              => '',
		'color_name'         => '#000',
		'background_name'	 => '#fff',
		'icon_name'			 => '',
		'color_email'        => '#000',
		'background_email'	 => '#fff',
		'icon_email' 	 	 => '',
		'color_subject'      => '#000',
		'background_subject' => '#fff',
		'icon_subject' 		 => '',
		'color_message'      => '#000',
		'background_message' => '#fff',
		'add_field'			 => '',
		'btn_submit'		 => 'info',
		'btn_reset'          => 'warning'
	),$atts));
	$config = JFactory::getConfig();
	parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content));
	// add filed
	$addstr = '';
	$idman ='';
	$iduniq = uniqid('ytcf_').rand().time();
	$validation_mandatory ='';
	if($icon_name != '')
	{
		if (strpos($icon_name, 'icon:') !== false) {
		$icon_name = "<span class='add-on' style='background:".$background_name."; color:".$color_name."'><i class='fa fa-" . trim(str_replace('icon:', '', $icon_name)) . "'></i></span> ";
		}else
		{
			$icon_name = '<span class="add-on" style="background:'.$background_name.'; color:'.$color_name.'"><img src="'.yt_image_media($icon_name).'" style="width:18px" alt="" /></span> ';
		}
	}
	if($icon_email != '')
	{
		if (strpos($icon_email, 'icon:') !== false) {
			$icon_email = "<span class='add-on' style='background:".$background_name."; color:".$color_name."'><i class='fa fa-" . trim(str_replace('icon:', '', $icon_email)) . "'></i></span> ";
		}else
		{
			$icon_email = '<span class="add-on" style="background:'.$background_name.'; color:'.$color_name.'"><img src="'.yt_image_media($icon_email).'" style="width:18px" alt="" /></span> ';
		}
	}
	if($icon_subject != '')
	{
		if (strpos($icon_subject, 'icon:') !== false) {
			$icon_subject = "<span class='add-on' style='background:".$background_name."; color:".$color_name."'><i class='fa fa-" . trim(str_replace('icon:', '', $icon_subject)) . "'></i></span> ";
		}else
		{
			$icon_subject = '<span class="add-on" style="background:'.$background_name.'; color:'.$color_name.'"><img src="'.yt_image_media($icon_subject).'" style="width:18px" alt="" /></span> ';
		}
	}
	if($add_field != '')
	{
		$field_array = explode(',',$add_field);
		if(count($field_array) >0)
		{
			$addstr .= '<div class="yt-form-common-field">';
			for ($i=0; $i<count($field_array);$i++)
			{
				$str1 = $field_array[$i];
				$str1_array = explode('|',$str1);
				if(count($str1_array) >4)
				{
					if(count($str1_array) >5)
					{
						if($str1_array[5] == 'yes')
						$idman = 'mandatory'.$iduniq;
						$validation_mandatory =$str1_array[0];
					}
					$icon_add ='';
					if(count($str1_array) >6)
					{
						$icon_add = '<span class="add-on" style="background:'.$str1_array[4].';color:'.$str1_array[3].'"><i class="fa fa-'.$str1_array[6].'"></i></span>';
					}
					$addstr.='
						<div class="form-group">
							';
							if (strtolower($label_show)=='yes' || strtolower($label_show)=='no|yes')
							{
								$addstr.='<label for="'.$str1_array[0].'" class="yt-form-label" style="color:'.$str1_array[3].'">'.$str1_array[0].':</label>';
							}
							$addstr.='
							<div class="yt-input-box '.($icon_add !="" ? "yt-input-prepend" : "").'">';
							if($str1_array[1] == 'textarea')
							{
								$addstr .='<textarea id="'.$str1_array[0].' "  placeholder="'.$str1_array[2].'"  class="form-control-1 '.$idman.'" name="'.$str1_array[0].'" style="height: '.$textarea_height.'px; margin-bottom:10px; background:'.$str1_array[4].';color:'.$str1_array[3].' !important" ></textarea>';
							}
							else
							{
								$addstr.= $icon_add;
								$addstr.='<input type="'.$str1_array[1].'" placeholder="'.$str1_array[2].'" id="'.$str1_array[0].'" class="form-control-1 '.$idman.'" name="'.$str1_array[0].'" style="background:'.$str1_array[4].';margin-bottom:10px;color:'.$str1_array[3].'" />';
							}
				$addstr.='</div>
					</div>';
				}
			}
			$addstr .='</div>';
		}
	}


        $margin = ($margin != '') ? 'margin: '. $margin : ' margin: 0 0 25px 0';
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/contact_form/js/contact_form.js");
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/contact_form/css/contact_form.css");
        $fields = "";

        if ($name=='yes' && $subject=='yes') {
            $fields .= 'name-email-subject';
        } elseif ($name=='yes') {
           $fields .= 'name-email';
        }  elseif ($subject=='yes') {
           $fields .= 'email-subject';
        } else {
            $fields .= 'email';
        }

        if (isset($_POST['email'])) {
        	$name='';
        	$email1='';
        	$message ='';
        	$subject ='';
        	if(isset($_POST['name']))
        	{
        		$name = $_POST['name'];
        	}
            if(isset($_POST['email']))
        	{
        		$email1 = $_POST['email'];
        	}
        	if(isset($_POST['subject']))
        	{
        		$subject = $_POST['subject'];
        	}
            if(isset($_POST['message']))
        	{
        		$message = $_POST['message'];
        	}
            $to = ($email) ? $email :  $config->get( 'mailfrom' );


            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";


            if(!empty($subject)){
                $subject = $subject;
            } else {
                $subject =  $config->get( 'fromname' );
            }
            if(!empty($name)){
                $name = $name;
            } else {
                $name =  $config->get( 'fromname' );
            }
            $addmess = '';
            if($add_field != '')
			{
				$field_a = explode(',',$add_field);
				for ($i=0; $i<count($field_a);$i++)
				{
					$str2 = $field_a[$i];
					$str2_array = explode('|',$str2);
					$addmess .= '<tr>
								<td>'.$str2_array[0].'</td>
								<td>'.$_POST["".$str2_array[0].""].'</td>
								</tr>';
				}
			}
            $message = '<table>
            			<tr>
            				<td>Name</td>
            				<td>'.$name.'</td>
            			</tr>
            			<tr>
            				<td>Email</td>
            				<td>'.$email1.'</td>
            			</tr>
            			'. $addmess.'
            			<tr>
            				<td>Message</td>
            				<td>'.$message.'</td>
            			</tr>
            			</table>';

            if (!$email1) {
                $erre = "Email";
            } elseif (!$message) {
                $errm = "Message";
            } else {
                $email1 = trim($email1);
                $_ename = "/^[-!#$%&\'*+\\.\/0-9=?A-Z^_'{|}~]+";
                $_host = "([0-9A-Z]+\.)+";
                $_tlds = "([0-9A-Z]){2,4}$/i";
                $mail_validate = FALSE;

                if (!preg_match($_ename . "@" . $_host . $_tlds, $email1)) {
                    $mail_validate = TRUE;
                    $errev = "Please enter a valid e-mail address";
                } else {

                       	$mail1 = JFactory::getMailer();
                       	$mail1->IsHTML(true);
						$mail1->addRecipient($to);
						$mail1->setSender(array( $config->get( 'mailfrom' ), $name ));
						$mail1->setSubject($subject);
						$mail1->setBody($message);
						$result = new stdClass();
						if ($mail1->Send()) {
							echo '1';
						} else {
							echo '0';
						}
						exit(0);
                }
            }
        }
        $str = '
        <div class="yt-clearfix yt-contact_form '.$type.' '.$fields .'" style="'.$margin.'">
            <script type="text/javascript">
            jQuery(document).ready(function() {

				jQuery(document).off(\'click\', \'input[name="contact_us_submit'.$iduniq.'"]\');
				jQuery(document).on(\'click\', \'input[name="contact_us_reset'.$iduniq.'"]\', function(e) {
					resetForm("contact_us_form'.$iduniq.'");
				});
				jQuery(document).on(\'click\', \'input[name="contact_us_submit'.$iduniq.'"]\', function(e) {
					//e.preventDefault;
					$ = jQuery;

					var form = jQuery(\'form[name="contact_us_form'.$iduniq.'"]\');
					var formData = form.serialize();
					var formAction = form.attr(\'action\');

					var name = escape(jQuery(\'#name'.$iduniq.'\').val());
					var email = escape(jQuery(\'#email'.$iduniq.'\').val());
					var message = escape(jQuery(\'#message'.$iduniq.'\').val());
					var subject = escape(jQuery(\'#subject'.$iduniq.'\').val());
					var mandatory = escape(jQuery(\'.mandatory'.$iduniq.'\').val());
					var validation_mandatory = escape(jQuery(\'.mandatory'.$iduniq.'\').attr(\'name\'));
					isVal = false;
					vEmail = isValidEmail(email);
					if (name == "") {
						onErrorMessage(\'danger\', validation_name);
					} else if (email == "") {
						onErrorMessage(\'danger\', validation_email);
					} else if (!isNaN(email) || vEmail == false) {
						onErrorMessage(\'danger\', validation_vemail);
					} else if (subject == "") {
						onErrorMessage(\'danger\', validation_subject);
					} else if (mandatory == "") {
						onErrorMessage(\'danger\', validation_mandatory);
					}else if (message == "") {
						onErrorMessage(\'danger\', validation_message);
					}else {
						isVal = true;
					}
					if (isVal != false) {
						onContactSubmit(formAction, formData);
					}
				});
				function onErrorMessage(msgType, msgText) {
					jQuery(\'.error-message.'.$iduniq.'\').removeClass(\'message-info\');
					jQuery(\'.error-message.'.$iduniq.'\').removeClass(\'message-warning\');
					jQuery(\'.error-message.'.$iduniq.'\').removeClass(\'message-success\');
					jQuery(\'.error-message.'.$iduniq.'\').removeClass(\'message-danger\');

					if (msgType == \'info\') {
						jQuery(\'.error-message.'.$iduniq.'\').addClass(\'message-info\');
					} else if (msgType == \'warning\') {
						jQuery(\'.error-message.'.$iduniq.'\').addClass(\'message-warning\');
					} else if (msgType == \'success\') {
						jQuery(\'.error-message.'.$iduniq.'\').addClass(\'message-success\');
					} else if (msgType == \'danger\') {
						jQuery(\'.error-message.'.$iduniq.'\').addClass(\'message-danger\');
					}
					if (jQuery(\'.error-message.'.$iduniq.'\').is(\':visible\')) {
						jQuery(\'.error-message.'.$iduniq.' .text\').html(msgText);
					} else {
						jQuery(\'.error-message.'.$iduniq.' .text\').html(msgText);
						jQuery(\'.error-message.'.$iduniq.'\').slideDown(800);
					}
				}
			function onContactSubmit(formAction, formData) {
				jQuery.ajax({
					\'type\' : \'POST\',
					\'url\' : formAction,
					\'data\' : formData,
					\'dataType\': \'json\',
					\'success\' : function(response) {
						if (response == 1)
						{
							jQuery(\'#contact_us_form'.$iduniq.'\').each(function() {
								this.reset();
							});
							onErrorMessage(\'success\', \'Your Message Has been Sent Successfully\');
							jQuery(\'body\').animate
							({
								opacity : 1
								}, 1600, function()
								{
								if (jQuery(\'.error-message.'.$iduniq.'\').is(\':visible\')) {
									jQuery(\'.error-message.'.$iduniq.'\').slideUp(800);
								}
							});

						} else {
							onErrorMessage(\'warning\', \'Server Processing Error\');
						}
					}
				});
			};

	});
			validation_name="Name";
			validation_email="Email";
			validation_vemail="Please enter a valid e-mail address";
			validation_subject="Subject";
			validation_message="Message";
            </script>
            <div class="yt-form-wrapper">
                <div class="yt-form">
                    <div class="error-message '.$iduniq.'">
                        <span class="text"></span>
                </div>
                <form name="contact_us_form'.$iduniq.'" id="contact_us_form'.$iduniq.'" class="yt-clearfix contact_us_form " action="' . JURI::current() . '" method="POST">
                    <div class="yt-form-fields">
                        ';
                        if(strtolower($name)=='yes'){
                        $str.='
                            <div class="form-group">
                                ';

                                if (strtolower($label_show)=='yes' || strtolower($label_show)=='no|yes') {$str.='<label for="name'.$iduniq.'" class="yt-form-label" style="color:'.$color_name.'">Your Name:</label>';}
                                $str.='
                                <div class="yt-input-box '.($icon_name !="" ? "yt-input-prepend" : "").'">
                                	'.$icon_name.'
                                    <input type="text" placeholder="Name" id="name'.$iduniq.'" class="form-control-1" name="name" style="background:'.$background_name.';color:'.$color_name.' !important ; " />
                                </div>
                            </div>';
                        }
                        $str.='
                        <div class="form-group">
                            ';
                            if (strtolower($label_show)=='yes') {$str.='<label for="email'.$iduniq.'" class="yt-form-label" style="color:'.$color_email.'">Your E-Mail:</label>';}
                            $str.='
                            <div class="yt-input-box '.($icon_email !="" ? "yt-input-prepend" : "").'">
                            	 '.$icon_email.'
                                <input type="text" placeholder="Email"  id="email'.$iduniq.'" class="form-control-1" name="email" style="background:'.$background_email.';color:'.$color_email.'"  />
                            </div>
                        </div>';

                        if(strtolower($subject)=='yes' || strtolower($subject)=='no|yes'){

                        $str.='
                            <div class="form-group">
                                ';
                                if (strtolower($label_show)=='yes') {$str.='<label for="subject'.$iduniq.'" class="yt-form-label" style="color:'.$color_subject.'">Your Subject:</label>';}
                                $str.='

                                <div class="yt-input-box '.($icon_subject !="" ? "yt-input-prepend" : "").'">
                                  '.$icon_subject.'
                                    <input type="text" placeholder="Subject"  id="subject'.$iduniq.'" class="form-control-1" name="subject" style="background:'.$background_subject.';color:'.$color_subject.'" >

                                </div>
                            </div>
                    ';
                    }
					$str.='
                    </div>';
					$str.=$addstr;
                    $str.='
                    <div class="yt-form-common-field">
                        <div class="form-group ">
                            ';
                            if (strtolower($label_show)=='yes' || strtolower($label_show)=='no|yes') {$str.='<label for="message'.$iduniq.'" class="yt-form-label" style="color:'.$color_message.'">Your Message:</label>';}
                            $str.='
                            <div class="yt-input-box">
                                <textarea id="message'.$iduniq.'"  placeholder="Message"  class="form-control-1 message" name="message" style="height: '.$textarea_height.'px; background:'.$background_message.';color:'.$color_message.'" ></textarea>
                            </div>
                        </div>

                        <div style="display: none;"></div>

                            <div class="form-group">
                                <div class=" submit-button">
                                    ';
                                    if ($submit_button_text) {$str.='<input name="contact_us_submit'.$iduniq.'" class="btn btn-'.$btn_submit.'" type="button" value="'. $submit_button_text .'" />';}
                                    else{ $str.='<input name="contact_us_submit'.$iduniq.'" class="btn btn-'.$btn_submit.'" type="button" value="Submit" />';}

                                    if(strtolower($reset)=='yes' || strtolower($reset)=='no|yes'){
                                        $str.='<input name="contact_us_reset'.$iduniq.'" class="btn btn-'.$btn_reset.'" type="reset" value="Reset" />';
                                    }
                                    $str.='
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
        return $str;
}
?>