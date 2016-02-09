<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2015 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class plgSystemYt extends JPlugin {
	/* This event is triggered after the framework has loaded and the application initialise method has been called. */
	function onAfterInitialise() {
		global $app;
		
		// Include defines
		include_once dirname(__FILE__) . '/includes/core/defines.php';
		// Include function less
		include_once YT_INCLUDES_PATH.J_SEPARATOR.'core'.J_SEPARATOR.'less.php';
		// Include function resize image
		include_once YT_INCLUDES_PATH.J_SEPARATOR.'libs'.J_SEPARATOR.'resize'.J_SEPARATOR.'tool.php';
		
		// Include function template, render xml
		if($app->isSite()){
			include_once(YT_INCLUDES_PATH.J_SEPARATOR.'site'.J_SEPARATOR.'lib'.J_SEPARATOR.'yt_template.php');
			include_once(YT_INCLUDES_PATH.J_SEPARATOR.'site'.J_SEPARATOR.'lib'.J_SEPARATOR.'yt_renderxml.php');
		}
		
	}
	
	
	
	public function onBeforeCompileHead(){
		// Application Object
		$app 	= JFactory::getApplication();
		$doc    = JFactory::getDocument();
	    $option = $app->input->get('option');
		$view 	= $app->input->get('view');
		$layout = $app->input->get('layout');
		
		// Frontend
		$k2detail =  true;
		$k2detail =  ($option == 'com_k2' && $view == 'item') ? true: false;
		$config_module =  ($option == 'com_config' && $view == 'modules') ? true: false;
		$comMedia =  ($option == 'com_media' ) ? true: false;
		$disMootool = $app->getTemplate(true)->params->get('disableMootool');
		
		if($app->isSite() ){
			unset($doc->_scripts[JURI::root(true) .'/media/jui/js/bootstrap.min.js']);
		}
		
		if($app->isSite() && !$k2detail && !$config_module && $layout== null && !$comMedia &&  $disMootool) {
			// Remove default mootools
			unset($doc->_scripts[JURI::root(true) . '/media/system/js/core.js']);
			unset($doc->_scripts[JURI::root(true) . '/media/system/js/modal.js']);
			unset($doc->_scripts[JURI::root(true).'/media/system/js/caption.js']);
			unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools.js']);
			unset($doc->_scripts[JURI::root(true).'/media/system/js/mootools-core.js']);
			unset($doc->_scripts[JURI::root(true).'/media/system/js/mootools-more.js']);
			
			unset($doc->_styleSheets[JUri::root(true) . '/media/system/css/modal.css']);
			
			
			if (isset($doc->_script) && isset($doc->_script['text/javascript']))
			{
				$doc->_script['text/javascript'] = preg_replace('%window\.addEvent\(\'load\',\s*function\(\)\s*{\s*new\s*JCaption\(\'img.caption\'\);\s*}\);\s*%', '', $doc->_script['text/javascript']);
				$doc->_script['text/javascript'] = preg_replace('%jQuery\(window\)\.on\(\'load\'\,\s*function\(\)\s*\{\s*new\s*JCaption\(\'img\.caption\'\);\s*\}\);%', '', $doc->_script['text/javascript']);
				$doc->_script['text/javascript'] = preg_replace('%window\.addEvent\(\'load\',function\(\)\{\s*\}\);%', '', $doc->_script['text/javascript']);
					
				//remove SqueezeBox.initialize in behavior.php
				$doc->_script['text/javascript'] = preg_replace('%SqueezeBox\.initialize\(\{\}\);\s*SqueezeBox\.assign\(\$\(\'a.modal\'\)\.get\(\)\, {\s*parse\:\s\'rel\'\s*\}\);%', '', $doc->_script['text/javascript']);
				
				// Remove call to JTooltips
				$doc->_script['text/javascript'] = preg_replace('%window\.addEvent\(\'domready\',\s*function\(\)\s*{\s*\$\$\(\'.hasTip\'\).each\(function\(el\)\s*{\s*var\s*title\s*=\s*el.get\(\'title\'\);\s*if\s*\(title\)\s*{\s*var\s*parts\s*=\s*title.split\(\'::\',\s*2\);\s*el.store\(\'tip:title\',\s*parts\[0\]\);\s*el.store\(\'tip:text\',\s*parts\[1\]\);\s*}\s*}\);\s*var\s*JTooltips\s*=\s*new\s*Tips\(\$\$\(\'.hasTip\'\),\s*{\s*\"maxTitleChars\":\s*[\d]*,\s*\"fixed\":\s*false}\);\s*}\);%', '', $doc->_script['text/javascript']);
				$doc->_script['text/javascript'] = preg_replace('%jQuery\s*function\(\)\s*{\s*\$\$\(\'.hasTip\'\).each\(function\(el\)\s*{\s*var\s*title\s*=\s*el.get\(\'title\'\);\s*if\s*\(title\)\s*{\s*var\s*parts\s*=\s*title.split\(\'::\',\s*2\);\s*el.store\(\'tip:title\',\s*parts\[0\]\);\s*el.store\(\'tip:text\',\s*parts\[1\]\);\s*}\s*}\);\s*var\s*JTooltips\s*=\s*new\s*Tips\(\$\$\(\'.hasTip\'\),\s*{\s*\'maxTitleChars\':\s*[\d]*,\s*\'fixed\':\s*false}\);\s*}\);%', '', $doc->_script['text/javascript']);
				//$doc->_script['text/javascript'] = str_replace($doc->_script['text/javascript'],'',$doc->_script['text/javascript']);
			
				if (empty($doc->_script['text/javascript']))
					unset($doc->_script['text/javascript']);
			}
			
		}
		
		
	}
	
	
	function onContentPrepareForm($form, $data){
		// Add param(support Mega menu) for menu item
		if($form->getName()=='com_menus.item'){
			JForm::addFormPath(YT_INCLUDES_PATH.J_SEPARATOR.'libs'.J_SEPARATOR.'menu'.J_SEPARATOR.'params');
			$form->loadFile('params', false);
		}
		
	}
	function onBeforeRender(){
		/* Current, only include css, js */
		global $app;
		$document = JFactory::getDocument();
		$option = $app->input->get('option');
		$view = $app->input->get('view');
		
		
		// For backend
		if($app->isAdmin()){
			// Load language Yt Plugin
			$this->loadLanguage();
			
			
			if($this->nameOfSJTemplate()){
				$document->addStyleSheet(JURI::root(true).'/templates/'.$this->nameOfSJTemplate().'/css/system/pattern.css');
				$document->addStyleSheet(JURI::root(true).'/templates/'.$this->nameOfSJTemplate().'/asset/minicolors/jquery.miniColors.css');
				$document->addStyleSheet(YT_PLUGIN_URL.'/includes/admin/css/theme.css');
				if(!defined('FONT_AWESOME')){
					$document->addStyleSheet(YT_PLUGIN_URL.'/includes/admin/css/awesome/css/font-awesome.css');
					define('FONT_AWESOME', 1);
				}
				$document->addScript(JURI::root(true).'/templates/'.$this->nameOfSJTemplate().'/asset/minicolors/jquery.miniColors.min.js');
				$document->addCustomTag('
					<script type="text/javascript">
						TMPL_BACKEND = "'.$this->nameOfSJTemplate().'_backend"
					</script>
				');
				
				if($option == 'com_templates' && $view == 'style'){
				 $document->addScript(YT_PLUGIN_URL.'/includes/admin/js/yt-backendtemplate.js');
				}
				
			}
			// For menu SJ Help, Clean cache
			if($this->params->get('show_sjhelp', 0)==1){
				$document->addScript(YT_PLUGIN_URL.'/includes/admin/js/menu-sjhelp.js');
			}
		}
		
	}
	function onAfterRender() {
		global $app;
		$document = JFactory::getDocument();
		$option   = JRequest::getVar('option', '');
		$task	  = JRequest::getVar('task', '');
		
		$this->snippet();
		
		//  Minify
		if($app->isSite() && $document->_type == 'html' && !$app->getCfg('offline') && (!($option == 'com_content' && $task =='edit'))){
			require_once(YT_INCLUDES_PATH.J_SEPARATOR.'libs'.J_SEPARATOR.'yt-minify.php');
			$yt_mini = new YT_Minify;
			
			
			if($app->getTemplate(true)->params->get('optimizeCSS', 0)) $yt_mini->optimizecss();
			if($app->getTemplate(true)->params->get('optimizeJS', 0)) $yt_mini->optimizejs();

			if(JRequest::getVar('type') == 'plugin' && JRequest::getVar('action') == 'clearCache')
				$yt_mini->clearCache();
		}
		
		if($this->nameOfSJTemplate()){
			// Override template backend. New UI
			if($app->isAdmin()){
				$body = JResponse::getBody();
				if(JRequest::getCmd('view') == 'style'){
					// Template Content
					ob_start();
					require_once(YT_INCLUDES_PATH . '/admin/template/default.php');
					$buffer = ob_get_clean();
					$body1 = preg_replace('@<form\s[^>]*name="adminForm"[^>]*>?.*?</form>@siu', $buffer, $body);
					if( preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR){
						ini_set( 'pcre.backtrack_limit', (int)ini_get( 'pcre.backtrack_limit' )+ 15000 );
						$body1 = preg_replace('@<form\s[^>]*name="adminForm"[^>]*>([\w|\W]*)</form>@msu', $buffer, $body); 
					}
					if($body1!=null){
						JResponse::setBody($body1);
					}else{
						die('Error occurred because preg_replace is null');
					}
				}
			}
		}
		
		// Site offline
		if($app->getCfg('offline')){
			if(!class_exists('YT_Minify')) {
				require_once(YT_INCLUDES_PATH.J_SEPARATOR.'libs'.J_SEPARATOR.'yt-minify.php');
			}
			
		
		}
	}
	
	//Render snippet
	function snippet()
	{
		global $app;
		$places   = array();
		$contents = array();
		$body = JResponse::getBody();
			
			
		if (($openhead = $app->getTemplate(true)->params->get('headAfter', ''))) {
			$places[] = '<head>';	//not sure that any attritube can be place in head open tag, profile is not support in html5
			$contents[] = "<head>\n" . $openhead;
			
		}
		if (($closehead = $app->getTemplate(true)->params->get('headBefore', ''))) {
			$places[] = '</head>';
			$contents[] = $closehead . "\n</head>";
		}
		if (($openbody = $app->getTemplate(true)->params->get('bodyAfter', ''))) {
			$body = JResponse::getBody();
			
			if(strpos($body, '<body>') !== false){
				$places[] = '<body>';
				$contents[] = "<body>\n" . $openbody;
			} else {	//in case the body has other attribute	
				$body = preg_replace('@<body[^>]*?>@msU', "$0\n" . $openbody, $body);
				
				JResponse::setBody($body);
			}
		}
		
	
		if (($closebody = $app->getTemplate(true)->params->get('bodyBefore', ''))) {
			$places[] = '</body>';
			$contents[] = $closebody . "\n</body>";
		}

		if (count($places)) {
			$body = JResponse::getBody();
			$body = str_replace($places, $contents, $body);
			JResponse::setBody($body);
			
		}
	}

	
	// Get template name(only SJ's Template)
	function nameOfSJTemplate(){
		global $app;
		static $yt_templatename;
		if (!isset($yt_templatename)) {
			$yt_templatename = false; // set false
			$app = JFactory::getApplication();
			// get template name
			$name = '';
			if ($app->isAdmin()) {
				// if not login, do nothing
				$user = JFactory::getUser();
				if (!$user->id) return false;
				if(JRequest::getCmd('option') == 'com_templates' && (preg_match('/style\./', JRequest::getCmd('task')) || JRequest::getCmd('view') == 'style' || JRequest::getCmd('view') == 'template')){
					$db       = JFactory::getDBO();
					$query    = $db->getQuery(true);
					$id  = JRequest::getInt('id');
					if (JRequest::getCmd('view') == 'template') {
						$query
							->select('element')
							->from('#__extensions')
							->where('extension_id='.(int)$id . ' AND type=' . $db->quote('template'));
					} else {
						$query
							->select('template')
							->from('#__template_styles')
							->where('id='.(int)$id);
					}
					$db->setQuery($query);
					$name = $db->loadResult();
				}

			} else {
				$db       = JFactory::getDBO();
				$query    = $db->getQuery(true);
				$query
							->select('template')
							->from('#__template_styles')
							->where('home = 1 AND client_id = 0');
				$db->setQuery($query);
				$name = $db->loadResult();
			}
			
			if ($name) {
				// parse xml
				$filePath = JPath::clean(JPATH_ROOT.'/templates/'.$name.'/templateDetails.xml');
				if (is_file ($filePath)) {
					$xml = JInstaller::parseXMLInstallFile($filePath);
					if (strtolower($xml['group']) == 'yt_framework') {
						$yt_templatename = $name;
					}
				}
			}
		}
		return $yt_templatename;
	}
}