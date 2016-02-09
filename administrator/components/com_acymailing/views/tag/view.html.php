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


class TagViewTag extends acymailingView{

	function display($tpl = null){
		$function = $this->getLayout();
		if(method_exists($this, $function)) $this->$function();

		parent::display($tpl);
	}

	function tag(){

		$doc = JFactory::getDocument();
		$doc->addStyleSheet(ACYMAILING_CSS.'frontendedition.css?v='.filemtime(ACYMAILING_MEDIA.'css'.DS.'frontendedition.css'));

		JPluginHelper::importPlugin('acymailing');

		$dispatcher = JDispatcher::getInstance();
		$tagsfamilies = $dispatcher->trigger('acymailing_getPluginType');
		$defaultFamily = reset($tagsfamilies);
		$app = JFactory::getApplication();
		$fctplug = $app->getUserStateFromRequest(ACYMAILING_COMPONENT.".tag", 'fctplug', $defaultFamily->function, 'cmd');

		ob_start();
		$defaultContents = $dispatcher->trigger($fctplug);
		$defaultContent = ob_get_clean();

		$js = 'function insertTag(){if(window.parent.insertTag(window.document.getElementById(\'tagstring\').value)) {acymailing_js.closeBox(true);}}';
		$js .= 'function setTag(tagvalue){window.document.getElementById(\'tagstring\').value = tagvalue;}';
		$js .= 'function showTagButton(){window.document.getElementById(\'insertButton\').style.display = \'inline\'; window.document.getElementById(\'tagstring\').style.display=\'inline\';}';
		$js .= 'function hideTagButton(){}';
		$js .= 'try{window.parent.previousSelection = window.parent.getPreviousSelection(); }catch(err){window.parent.previousSelection=false; }';

		$doc->addScriptDeclaration($js);


		$this->assignRef('fctplug', $fctplug);
		$type = JRequest::getString('type', 'news');
		$this->assignRef('type', $type);
		$this->assignRef('defaultContent', $defaultContent);
		$this->assignRef('tagsfamilies', $tagsfamilies);
		$app = JFactory::getApplication();
		$this->assignRef('app', $app);
		$ctrl = JRequest::getString('ctrl');
		$this->assignRef('ctrl', $ctrl);
	}

	function form(){
		$plugin = JRequest::getString('plugin');
		$plugin = preg_replace('#[^a-zA-Z0-9_]#Uis', '', $plugin);
		$templatePath = ACYMAILING_MEDIA.'plugins'.DS.$plugin.'.php';
		$body = '';
		if(file_exists($templatePath)) $body = file_get_contents($templatePath);
		$help = JRequest::getString('help');
		$help = preg_replace('#[^a-zA-Z0-9]#Uis', '', $help);
		$help = empty($help) ? $plugin : $help;

		$this->assignRef('help', $help);
		$this->assignRef('plugin', $plugin);
		$this->assignRef('body', $body);
	}
}
