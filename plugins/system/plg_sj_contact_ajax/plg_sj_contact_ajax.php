<?php
/**
 * @package System - Sj Contact Ajax
 * @version 1.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}


class plgSystemPlg_Sj_Contact_Ajax extends JPlugin {
	
	var $bgColor = "#99CC00";
	var $textColor = "#FFFFFF";
		
    function onAfterDispatch(){
	
		$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		if ($is_ajax){
			$db = JFactory::getDbo();
			$db->setQuery( 'SELECT * FROM #__modules WHERE id='.JRequest::getInt('ctajax_modid') );
			$result = $db->loadObject();
			if (isset($result->module)){
				echo JModuleHelper::renderModule($result);
				exit(0);
			}
		}
	}
	
    function display(){
		$plugin 	= JPluginHelper::getPlugin('system', 'plg_sj_contact_ajax');
		$imageFunction = 'create_image'.$this->params->get('imageFunction');
		$imageFunction = ((!method_exists($this,$imageFunction)))?'create_Captcha':$imageFunction;
	
		$this->$imageFunction();
		exit;
		return true;
	}
	
	function setColors(){
			$plugin 	=  JPluginHelper::getPlugin('system', 'plg_sj_contact_ajax');
			$this->bgColor  = $this->params->get('bgColor',$this->bgColor);
			$this->textColor  = $this->params->get('textColor',$this->textColor);
	}
	
	function _generateRandom($length=6){
		$_rand_src = array(
			array(48,57) 
			, array(97,122)
		   , array(65,90) 
		);
		srand ((double) microtime() * 1000000);
		$random_string = "";
		for($i=0;$i<$length;$i++){
			$i1=rand(0,sizeof($_rand_src)-1);
			$random_string .= chr(rand($_rand_src[$i1][0],$_rand_src[$i1][1]));
		}
		return $random_string;
	}
	

	function create_Captcha()
	{
		
		
		$security_code = $this->_generateRandom(6);
		$currentSession =   JFactory::getSession() ;
		
		$currentSession->set('codeCaptcha'.(JRequest::getVar('instanceCaptcha')+0), $security_code);
		$width = 170;
		$height = 38;
		
		$image = imagecreate($width, $height);
		$this->setColors();
		$foreground_color = $this->HexToRGB($this->textColor) ;
		$background_color = $this->HexToRGB($this->bgColor) ;
		$white = imagecolorallocate ($image, $foreground_color[0],$foreground_color[1],$foreground_color[2]);
		$black = imagecolorallocate ($image,$background_color[0],$background_color[1],$background_color[2]);
		$grey = imagecolorallocate ($image, 204, 204, 204);
		imagefill($image, 0, 0, $black); 
		$size = 13;
		$this->ly = (int)(2.4 * $size);
		$x = 15;
		$_font = dirname(__FILE__).DS.'assets'.DS.'times_new_yorker.ttf';
		$textcolor = imagecolorallocate($image, 0, 0, 255);
		//phpinfo();
		for($i=0;$i<strlen($security_code);$i++){
			$angle = rand(-30,30);
			$y  = intval(rand((int)($size * 1.5), (int)($this->ly - ($size / 7))));
			if(function_exists('imagettftext')){
				imagettftext($image, $size, $angle, $x + (int)($size / 15), $y, $white,  $_font, $security_code[$i]);
			$x += ($size *2);
			}else{
				 imagestring($image, 5, $x, 12, $security_code[$i], $textcolor);
			$x += ($size *2);
			}
			
			
		}
		if(function_exists("imagepng")){
			header("Content-Type: image/x-png");
		}
		imagepng($image);
		imagedestroy($image);

	}
	 
	function confirm( $word,$instanceCaptcha='' ){
		$currentSession = & JFactory::getSession() ;
		$securiy_code = $currentSession->get('codeCaptcha'.$instanceCaptcha);
		if ( $word == $securiy_code  &&  ($word != '')) 
		   return true;
		else
		   return false;  

	}

	function onCaptcha_Display() {	
		return $this->display();	
	}

	function onCaptcha_confirm($word, &$return) {		
		$return = $this->confirm($word);
		return $return;
	} 
	 
    function onAfterInitialise(){
	   $displayCaptcha = JRequest::getVar('displayCaptcha');
		if($displayCaptcha == 'True'){
			return $this->display();
		}
   }

    function getCaptchaHTML($id){
		JPlugin::loadLanguage( 'plg_sj_contact_ajax', JPATH_ADMINISTRATOR );
		if(!isset($GLOBALS['numberCaptchs']))
		{
			$GLOBALS['numberCaptchs'] = -1;
		}

		$GLOBALS['numberCaptchs'] = $id;
		//var_dump($id); die;
		return ("
			<img class=\"el-captcha\" id=\"el_captcha".$GLOBALS['numberCaptchs']."\" src=\"".JURI::base()."index.php?displayCaptcha=True&amp;instanceCaptcha=".$GLOBALS['numberCaptchs']."&time=".time().rand()."\" alt=\"Captcha\" /> 
			 <a class=\"el-captcha-refesh\" href=\"#\" onclick=\"document.getElementById('el_captcha".$GLOBALS['numberCaptchs']."').src = '".JURI::base()."index.php?displayCaptcha=True&instanceCaptcha=".$GLOBALS['numberCaptchs']."&time=' + new Date().getTime() ;return false;\" ><i class=\"icon-refresh icon-large\"></i></a>
			");
		}

		function showCaptcha($id){
			echo $this->getCaptchaHTML($id);
		}
		
		function HexToRGB($hex) {
			$hex = preg_replace("/#/", "", $hex);
			$color = array();
			
			if(strlen($hex) == 3) {
				$color['r'] = hexdec(substr($hex, 0, 1) . $r);
				$color['g'] = hexdec(substr($hex, 1, 1) . $g);
				$color['b'] = hexdec(substr($hex, 2, 1) . $b);
			}
			else if(strlen($hex) == 6) {
				$color['r'] = hexdec(substr($hex, 0, 2));
				$color['g'] = hexdec(substr($hex, 2, 2));
				$color['b'] = hexdec(substr($hex, 4, 2));
			}
			
			return array_values($color);
		}
	
}
