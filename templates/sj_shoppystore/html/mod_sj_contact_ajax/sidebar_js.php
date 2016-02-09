<?php
/**
 * @package Sj Contact Ajax
 * @version 1.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;

?>

<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready(function($) {
		;(function(element){
			var $element =  $(element);
			$('.ca-tooltip',$element).tooltip(); 
			var $form = $('#el_ctajax_form',$element);
			var $ajax_url = '<?php echo JURI::getInstance(); ?>'
			var $name = $('#cainput_name', $element);
			var $email = $('#cainput_email', $element);
			var $subject = $('#cainput_subject', $element);
			var $message = $('#cainput_message', $element);
			var $captcha = $('#cainput_captcha', $element);
			var $recaptcha = $('#dynamic_recaptcha_1', $element);
			var $smail_copy = $('#contact_email_copy', $element);
			
			var $ca_submit = $('#cainput_submit',$element);
			var $image_load = $('.el-ctajax-loadding', $element );
			var $notice_return = $('.el-ctajax-return', $element );
			var $return_error = $('.return-error',$element);
			var $return_success = $('.return-success',$element);
			
			
			function validateInput(input, type){
				var validationResult = validation(input, type);
				checkFormValidationState();
				return validationResult.valid;
			}
			
			function validation(input, type){
				var result = new Object();
				result.valid = true;
				result.mes = "The field is valid";
				var value = $(input).val();
				switch (type)
				{
					case "name":
					case "subject":
						if (value.length == ''  ){
							result.valid = false;
							result.mes = "Please enter a valid!";
						}
						
						saveValidationState(input, result.valid);
						showValidationMessage(input, result);
						break;
					case "message":
						if (value.length == '' || value.length <= 5 ){
							result.valid = false;
							result.mes = "Please enter a valid!";
						}
						
						saveValidationState(input, result.valid);
						showValidationMessage(input, result);
						break;
					case "email":
						var re = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
						if (!re.test(value)){
							result.valid = false;
							result.mes = "Please enter a valid email!";
						}
						saveValidationState(input, result.valid);
						showValidationMessage(input, result);
						break;
					case 'captchaCode':
							if (value.length == '' || value.length < 6){
								result.valid = false;
								result.mes = "Please enter a valid!";
								saveValidationState(input, false);
								showValidationMessage(input, result);
							}else { 
								saveValidationState(input, false);
								$(input).parent().removeClass('ctajax-error').removeClass('ctajax-ok');
								$('.el-captcha-loadding', $element).css('display','inline-block');
								$ca_submit.addClass('check-captcha');
								$.ajax({
									type:'POST',
									url: $ajax_url,
									data : {
										captcha: value ,
										task: 'checkcaptcha'
									},
									dataType: 'json',
									success:function(data){
										$('.el-captcha-loadding', $element).css('display','none');
										$ca_submit.removeClass('check-captcha');
										saveValidationState(input, data.valid);
										showValidationMessage(input, data);
										checkFormValidationState();
									}
								});
							}
						break;
					default:
						break;
				}

				return result;
			}
			
			function saveValidationState(input, validationState){
				$(input).data("validated", validationState);
			}

			function checkFormValidationState(){
				var nameValid = $name.data("validated");
				var emailValid = $email.data("validated");
				var subjectValid = $subject.data("validated");
				var messageValid = $message.data("validated");
				var captchaValid = $captcha.data("validated");
				var check_valid = '';
				<?php if($captcha_dis == 1) { 
					if($captcha_disable == 1 && $user->id != 0 ){ ?>
						check_valid = nameValid && emailValid && subjectValid && messageValid  ;
					<?php } else{ 
						if($captcha_type == 1){?>
						check_valid = nameValid && emailValid && subjectValid && messageValid  && captchaValid ;
						<?php } else { ?>
						check_valid = nameValid && emailValid && subjectValid && messageValid  ;
						<?php } ?>
					<?php } 
				} else { ?>
					check_valid = nameValid && emailValid && subjectValid && messageValid  ;
				<?php } ?>
				if(check_valid){
					return true;
				}else {
					return false;
				}
			}

			function showValidationMessage(input, validationResult){
				if (validationResult.valid === false) {
					$(input).parent().addClass('ctajax-error').removeClass('ctajax-ok');
				} else {
					$(input).parent().removeClass('ctajax-error').addClass('ctajax-ok');
				}
			}

			var timer0 = 0;
			$name.on("keyup", function(e) {
				if(timer0){
					clearTimeout (timer0);
					timer0 = 0;
				 }
				 timer0 = setTimeout(function() {
					validateInput($name, "name");
				},1000);
			});
			
			var timer1 = 0;		
			$email.on("keyup", function(e) {
				if(timer1){
					clearTimeout (timer1);
					timer1 = 0;
				 }
				 timer1 = setTimeout(function() {
					validateInput($email, "email");
				},1000);
			});
			
			var timer2 = 0;	
			$subject.on("keyup", function(e) {
				if(timer2){
					clearTimeout (timer2);
					timer2 = 0;
				 }
				 timer2 = setTimeout(function() {
					validateInput($subject, "subject");
				},1000);
			});
			
			var timer3 = 0;
			$message.on("keyup", function(e) {
				if(timer3){
					clearTimeout (timer3);
					timer3 = 0;
				 }
				 timer3 = setTimeout(function() {
					validateInput($message, "message");
				},1000);
			});
			
			var timer4 = 0;
			$captcha.on("keyup", function(){
				if(timer4){
					clearTimeout (timer4);
					timer4 = 0;
				 }
				 timer4 = setTimeout(function() {
					validateInput($captcha, "captchaCode");
				},1000);
			});	
			
			$('.el-captcha-refesh', $element).on('click.refesh', function(){
				$captcha.val('');
			});
				
			$form.on('submit',function(){
				var $name_value = $.trim($name.val());
				var $email_value = $.trim($email.val());
				var $subject_value = $.trim($subject.val());
				var $message_value = $.trim($message.val());
				var $captcha_value = $.trim($captcha.val());
				
				//Recaptcha.response ('recaptcha_response_field');
				var $smail_copy_value = $smail_copy.attr('checked') ? 1 : 0;
				var $check_empty = '';
				<?php if($captcha_dis == 0) { ?>
						$check_empty = $name_value == '' || $subject_value == '' || $email_value == '' || $message_value == '';
				<?php } else {
					if($captcha_disable == 1 && $user->id != 0  ){ ?>
						$check_empty = $name_value == '' || $subject_value == '' || $email_value == '' || $message_value == '';
					<?php }else{
						if($captcha_type == 1){?>
							$check_empty = $name_value == '' || $subject_value == '' || $email_value == '' || $message_value == '' ||  $captcha_value == '';
						<?php } else { ?>
							var $recaptcha_value = Recaptcha.get_response();
							$check_empty = $name_value == '' || $subject_value == '' || $email_value == '' || $message_value == '' || $recaptcha_value == '';
					<?php	}
					 } 
				 } ?>
				if(checkFormValidationState() == false || $check_empty ){
					if($name_value == '' ){
						validateInput($name, "name");
					}
					if($email_value == ''){
						validateInput($email, "email");
					}
					if($subject_value == '' ){
						validateInput($subject, "subject");
					}
					if($message_value == '' || $message_value.length <= 5){
						validateInput($message, "message");
					}
					<?php if($captcha_dis == 1) { 
						if($captcha_disable == 1 && $user->id != 0 ){
						} else { 
							if($captcha_type == 1){ ?>
								if($captcha_value == ''){
									validateInput($captcha, "captchaCode");
								}
							<?php }else { ?>
								if($recaptcha_value == ''){
									$recaptcha.parent().removeClass('ctajax-ok').addClass('ctajax-error');
								}
					<?php } 
						}
					} ?>
					return false;
				}else{
						if($ca_submit.hasClass('check-captcha') || $ca_submit.hasClass('ca-sending')){
							return false;
						}else{
							$ca_submit.addClass('ca-sending');
							$image_load.css('display','inline-block');
							<?php if($captcha_type == 1){?>
								$.ajax({
									type:'POST',
									url: $ajax_url,
									data : {
										name: $name_value ,
										email: $email_value,
										message: $message_value,
										subject: $subject_value,
										send_copy: $smail_copy_value,
										task: 'sendmail'
									},
									success:function(data){
										$image_load.css('display','none');
										//$return_success.css('display','inline-block');	
										if(data.status == 1){
											$return_success.css('display','inline-block');	
										}else{
											$return_error.css('display','inline-block');	
										}
									},
									 complete:function(data){
										$form.each(function(){
											this.reset();  
										});
										$notice_return.delay(3000).fadeOut();	
										$('.el-control').each(function(){
											$(this).removeClass('ctajax-ok');
										});
										$ca_submit.removeClass('ca-sending');
										<?php if($captcha_dis == 1) { 
											if($captcha_disable == 1 && $user->id != 0 ){
											
											} else {  ?>
												$url ='<?php echo JURI::current()?>index.php?displayCaptcha=True&instanceCaptcha=<?php echo $module->id; ?>&time='+ new Date().getTime(); 
												$('#el_captcha<?php echo $module->id ?>', $element).attr("src",$url);
										<?php }
										}	?>
								   },
									dataType: 'json',
								});
							<?php } else { ?>
							
								$.ajax({
									type:'POST',
									url: $ajax_url,
									data : {
										name: $name_value ,
										email: $email_value,
										message: $message_value,
										subject: $subject_value,
										send_copy: $smail_copy_value,
										<?php if($captcha_dis == 1) { 
											if($captcha_disable == 1 && $user->id != 0 ){
											} else {  ?>
												recaptcha_response:$recaptcha_value,
												recaptcha_challenge: Recaptcha.get_challenge(),
										<?php } 
										} ?>
										task: 'sendmail'
									},
									success:function(data){
										$image_load.css('display','none');
										//$return_success.css('display','inline-block');	
									
										if(typeof data.error_captcha != 'undefined' && data.error_captcha == 0){
											$recaptcha.parent().removeClass('ctajax-ok').addClass('ctajax-error');
											
										}else{
											$recaptcha.parent().removeClass('ctajax-error').addClass('ctajax-ok');
											if(data.status == 1){
												$return_success.css('display','inline-block');	
											}else{
												$return_error.css('display','inline-block');	
											}
										}
									},
									 complete:function(data,xhr, status){
										if(data.responseText == '{"error_captcha":0}'){
											$recaptcha.parent().removeClass('ctajax-ok').addClass('ctajax-error');
										}else{
											$form.each(function(){
												this.reset();  
											});
											$notice_return.delay(3000).fadeOut();	
											$('.el-control').each(function(){
												$(this).removeClass('ctajax-ok');
											});
											<?php if($captcha_dis == 1) { 
												if($captcha_disable == 1 && $user->id != 0 ){
												} else {  ?>
													Recaptcha.reload();
											<?php }
											}	?>
										}
										$ca_submit.removeClass('ca-sending');
								   },
									dataType: 'json',
								});
							<?php } ?>
						}
					}	
				return false;
			});
		})('#<?php echo $tag_id; ?>')
	}); 
//]]>	
</script>
