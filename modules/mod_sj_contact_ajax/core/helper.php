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


if(!class_exists('ContactModelContact') && file_exists(JPATH_SITE.'/components/com_contact/models/contact.php')){
	require_once JPATH_SITE.'/components/com_contact/models/contact.php';
}

if(!class_exists('ContactControllerContact') && file_exists(JPATH_SITE.'/components/com_contact/controllers/contact.php')){
	require_once JPATH_SITE.'/components/com_contact/controllers/contact.php';
}

class ContactAjax {
	public static function getList( $params ){
		$list  = array();
		$contact_id = (int)$params->get('contact_id');
		if(class_exists('ContactControllerContact') && $contact_id != ''){
			$ctrl 	= new ContactControllerContact();
			$model 	= $ctrl->getModel('contact');
			$list	= $model->getItem($contact_id);
			//$list['mail_to'] = $list['infor']->email_to; 
			return $list;
		}else{
			return false;
		}
	}
	
	public static function _processSendMail($mail_to)
	{
		$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		if($is_ajax) {	
			$app		=  JFactory::getApplication();
			$name 			= isset($_POST['name'])?$_POST['name']:'';
			$email 			= isset($_POST['email'])?$_POST['email']:'';
			$subject 		= isset($_POST['subject'])?$_POST['subject']:'';
			$body	 		= isset($_POST['message'])?$_POST['message']:'';
			$prefix = JText::sprintf('COM_CONTACT_ENQUIRY_TEXT', JURI::base());
			$body	= $prefix."\n".$name.' <'.$email.'>'."\r\n\r\n".stripslashes($body);
			$mailfrom	= $app->getCfg('mailfrom');
			$fromname	= $app->getCfg('fromname');
			$sitename	= $app->getCfg('sitename');
			$mail_to = ($mail_to != '')?$mail_to:$mailfrom;	
			$mail =  JFactory::getMailer();
			$mail->addRecipient($mail_to);
			$mail->addReplyTo(array($email, $name));
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($sitename.': '.$subject);
			$mail->setBody($body);
			
			$sent = $mail->Send();
			
			
			if ( isset($_POST['send_copy']) && $_POST['send_copy'] == 1  ) {
				$copytext		= JText::sprintf('COM_CONTACT_COPYTEXT_OF', $name, $sitename);
				$copytext		.= "\r\n\r\n".$body;
				$copysubject	= JText::sprintf('COM_CONTACT_COPYSUBJECT_OF', $subject);

				$mail = JFactory::getMailer();
				$mail->addRecipient($email);
				$mail->addReplyTo(array($email, $name));
				$mail->setSender(array($mailfrom, $fromname));
				$mail->setSubject($copysubject);
				$mail->setBody($copytext);
				$sent =  $mail->Send();
			}
			$result = new stdClass();
			if ( $sent !== true ) {
				$result->status = 0;
				$result->message = 'Mail is not sent'	;
			} else {
				$result->status = 1;
				$result->message = 'Your email has been sent.';
					
			}
			echo json_encode($result);
			die();
		}
		return true;
	}
	
	public static function captcha()
	{

	}
	
}
