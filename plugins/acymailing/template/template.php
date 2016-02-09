<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class plgAcymailingTemplate extends JPlugin
{

	var $templates = array();
	var $tags = array();
	var $headerstyles = array();
	var $others = array();
	var $stylesheets = array();
	var $templateClass = '';
	var $config;

	function plgAcymailingTemplate(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('acymailing', 'template');
			$this->params = new acyParameter( $plugin->params );
		}
		$this->config = acymailing_config();
		if(version_compare(PHP_VERSION, '5.0.0', '>=') && class_exists('DOMDocument') && function_exists('mb_convert_encoding')){
			require_once(ACYMAILING_FRONT.'inc'.DS.'emogrifier'.DS.'emogrifier.php');
		}
	}

	private function _applyTemplate(&$email,$addbody){
		if(empty($email->tempid)) return;

		if(!isset($this->templates[$email->tempid])){
			$this->headerstyles[$email->tempid] = array();
			$this->headerstyles[$email->tempid][] = '.ReadMsgBody{width: 100%;}';
			$this->headerstyles[$email->tempid][] = '.ExternalClass{width: 100%;}';
			$this->headerstyles[$email->tempid][] = 'div, p, a, li, td { -webkit-text-size-adjust:none; }';

			$this->templates[$email->tempid] = array();
			if(empty($this->templateClass)){
				$this->templateClass = acymailing_get('class.template');
			}
			if(!empty($email->template) && $email->tempid == $email->template->tempid){
				$template = $email->template;
			}else{
				$template = $email->template = $this->templateClass->get($email->tempid);
			}

			if(!empty($template->styles) OR !empty($template->stylesheet)){
				$this->stylesheets[$email->tempid] = $this->templateClass->buildCSS($template->styles,$template->stylesheet);

				if(preg_match_all('#@import[^;]*;#is',$this->stylesheets[$email->tempid],$results)){
					foreach($results[0] as $oneResult){
						array_unshift($this->headerstyles[$email->tempid],trim($oneResult));
					}
				}

				if(preg_match_all('#@media.*}[^{}]*}#Uis',$this->stylesheets[$email->tempid],$results)){

					foreach($results[0] as $oneResult){
						$this->stylesheets[$email->tempid] = str_replace($oneResult,'',$this->stylesheets[$email->tempid]);
						$this->headerstyles[$email->tempid][] = trim($oneResult);
					}
				}

				if(preg_match_all('#}([^}]+:hover[^{]*{[^{]*})#Uis','} '.$this->stylesheets[$email->tempid],$results)){
					foreach($results[1] as $oneResult){
						$this->stylesheets[$email->tempid] = str_replace($oneResult,'',$this->stylesheets[$email->tempid]);
						$this->headerstyles[$email->tempid][] = trim($oneResult);
					}
				}
			}


			if(!empty($template->styles)){
				foreach($template->styles as $class => $style){
					if(empty($style)) continue;
					if(preg_match('#^tag_(.*)$#',$class,$result)){
						$this->tags[$email->tempid]['#< *'.$result[1].'((?:(?!style).)*)>#Ui'] = '<'.$result[1].' style="'.$style.'" $1>';
						if(strpos($style,'!important')) $this->headerstyles[$email->tempid][] = $result[1].'{ '.str_replace('!important','',$style).' }';
					}elseif($class == 'color_bg'){
						$this->others[$email->tempid][$class] = $style;
					}else{
						$this->templates[$email->tempid]['class="'.$class.'"'] = 'style="'.$style.'"';
					}
				}
				if(!empty($template->styles['tag_a'])){
					$this->headerstyles[$email->tempid][] = 'a:visited{'.$template->styles['tag_a'].'}';
				}
			}
		}

		if($addbody AND !strpos($email->body,'</body>')){
			$before = '<html><head>'."\n";
			$before .= '<meta http-equiv="Content-Type" content="text/html; charset='.strtolower($this->config->get('charset')).'" />'."\n";
			$before .= '<meta name="viewport" content="width=device-width, initial-scale=1.0" />'."\n";
			$before .= '<title>'.$email->subject.'</title>'."\n";
			if(!empty($this->headerstyles[$email->tempid])){
				$before .= '<style type="text/css">'."\n";
				$before .= implode("\n",$this->headerstyles[$email->tempid])."\n";
				$before .= '</style>'."\n";
			}
			$before .= '</head>'."\n".'<body yahoo="fix"';
			if(!empty($this->others[$email->tempid]['color_bg'])) $before .= ' bgcolor="'.$this->others[$email->tempid]['color_bg'].'" ';
			$before .= '>'."\n";
			$email->body = $before.$email->body.'</body>'."\n".'</html>';
		}

		if(!empty($this->stylesheets[$email->tempid]) AND class_exists('acymailingEmogrifier')){
			$emogrifier = new acymailingEmogrifier($email->body,$this->stylesheets[$email->tempid]);
			$email->body = $emogrifier->emogrify();

			if(!$addbody AND strpos($email->body,'<!DOCTYPE') !== false){
				$email->body = preg_replace('#<\!DOCTYPE.*<body([^>]*)>#Usi','',$email->body);
				$email->body = preg_replace('#</body>.*$#si','',$email->body);
			}
		}else{
			if(!empty($this->templates[$email->tempid])){
				$email->body = str_replace(array_keys($this->templates[$email->tempid]),$this->templates[$email->tempid],$email->body);
			}

			if(!empty($this->tags[$email->tempid])){
				$email->body = preg_replace(array_keys($this->tags[$email->tempid]),$this->tags[$email->tempid],$email->body);
			}
		}

		$newbody = preg_replace('#(<(div|tr|td|table)[^>]*)title="[^"]*"#Uis','$1',$email->body);
		if(!empty($newbody)) $email->body = $newbody;

		$newbody = preg_replace('# id="zone_[0-9]+"#Uis',' ',$email->body);
		if(!empty($newbody)) $email->body = $newbody;

		$newbody = preg_replace('# *(acyeditor_text|acyeditor_picture|acyeditor_delete|acyeditor_sortable|ui-sortable) *#is','',$email->body);
		$newbody = preg_replace('#(class|title|style|id)=" *"#Ui','',$newbody);
		if(!empty($newbody)) $email->body = $newbody;

	}

	public function acymailing_replaceusertags(&$email,&$user,$send = true){

		if(!$email->sendHTML) return;

		$this->_applyTemplate($email,$send);

		$email->body = preg_replace('#< *(tr|td|table)([^>]*)(style="[^"]*)background-image *: *url\(\'?([^)\']*)\'?\);?#Ui','<$1 background="$4" $2 $3',$email->body);
		$email->body = acymailing_absoluteURL($email->body);

		if(preg_match_all('#< *img([^>]*)>#Ui',$email->body,$allPictures)){

			foreach($allPictures[0] as $i => $onePict){
				if(strpos($onePict,'align=') !== false) continue;
				if(!preg_match('#(style="[^"]*)(float *: *)(right|left|top|bottom|middle)#Ui',$onePict,$pictParams)) continue;

				$newPict = str_replace('<img','<img align="'.$pictParams[3].'" ',$onePict);

				$email->body = str_replace($onePict,$newPict,$email->body);

				if(strpos($onePict,'hspace=') !== false) continue;

				$hspace = 5;
				if(preg_match('#margin(-right|-left)? *:([^";]*)#i',$onePict,$margins)){
					$currentMargins = explode(' ',trim($margins[2]));
					$myMargin = (count($currentMargins) > 1) ? $currentMargins[1] : $currentMargins[0];
					if(strpos($myMargin,'px') !== false) $hspace = preg_replace('#[^0-9]#i','',$myMargin);
				}

				$lastPict = str_replace('<img','<img hspace="'.$hspace.'" ',$newPict);

				$email->body = str_replace($newPict,$lastPict,$email->body);
			}
		}

		if(!preg_match('#(<thead|<tfoot|< *tbody *[^> ]+ *>)#Ui',$email->body)){
			$email->body = preg_replace('#< *\/? *tbody *>#Ui','',$email->body);
		}

		$email->body = preg_replace_callback('/src="([^"]* [^"]*)"/Ui', array($this, '_convertSpaces'), $email->body);

		$this->fixPictureSize($email->body);

		$acypluginsHelper = acymailing_get('helper.acyplugins');
		$acypluginsHelper->fixPictureDim($email->body);

	}//endfct

	public function _convertSpaces($matches) {
		return "src='".str_replace(' ', '%20', $matches[1])."'";
	}

	private function fixPictureSize(&$body){
		if(!preg_match_all('#(<img)([^>]*>)#i',$body,$results)) return;

		$replace = array();
		$widthheight = array('width','height');
		foreach($results[0] as $num => $oneResult){
			$add = array();
			foreach($widthheight as $whword){
				if(preg_match('#'.$whword.' *=#i',$oneResult) || !preg_match('#[^a-z_\-]'.$whword.' *:([0-9 ]{1,8})px#i',$oneResult,$resultWH)) continue;

				if(empty($resultWH[1])) continue;
				$add[] = $whword.'="'.trim($resultWH[1]).'" ';
			}
			if(!empty($add)) $replace[$oneResult] = '<img '.implode(' ',$add).$results[2][$num];
		}

		if(empty($replace)) return;

		$body = str_replace(array_keys($replace),$replace,$body);

	}

}//endclass
