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

class templateClass extends acymailingClass{

	var $tables = array('template');
	var $pkey = 'tempid';
	var $namekey = 'alias';
	var $templateNames = array();
	var $archiveSection = false;
	var $proposedAreas = false;
	var $templateId = "";
	var $checkAreas = true;

	function get($tempid, $default = null){
		$column = is_numeric($tempid) ? 'tempid' : 'name';
		$this->database->setQuery('SELECT * FROM '.acymailing_table('template').' WHERE '.$column.' = '.$this->database->Quote($tempid).' LIMIT 1');
		$template = $this->database->loadObject();
		return $this->_prepareTemplate($template);
	}

	function getDefault(){
		$queryDefaultTemp = 'SELECT * FROM '.acymailing_table('template').' WHERE premium = 1 AND published = 1 ORDER BY ordering ASC LIMIT 1';
		if(acymailing_level(3)){
			$my = JFactory::getUser();
			if(!ACYMAILING_J16){
				$groups = $my->gid;
				$condGroup = ' OR access LIKE (\'%,'.$groups.',%\')';
			}else{
				jimport('joomla.access.access');
				$groups = JAccess::getGroupsByUser($my->id, false);
				$condGroup = '';
				foreach($groups as $group){
					$condGroup .= ' OR access LIKE (\'%,'.$group.',%\')';
				}
			}
			$queryDefaultTemp = 'SELECT * FROM '.acymailing_table('template').' WHERE premium = 1 AND published = 1  AND (access = \'all\' '.$condGroup.') ORDER BY ordering ASC LIMIT 1';
		}

		$this->database->setQuery($queryDefaultTemp);
		$template = $this->database->loadObject();
		return $this->_prepareTemplate($template);
	}

	private function _prepareTemplate($template){
		if(!isset($template->styles)) return $template;

		if(empty($template->styles)){
			$template->styles = array();
		}else{
			$template->styles = unserialize($template->styles);
		}

		return $template;
	}

	function saveForm(){

		$template = new stdClass();
		$template->tempid = acymailing_getCID('tempid');

		$formData = JRequest::getVar('data', array(), '', 'array');

		if(!empty($formData['template']['category']) && $formData['template']['category'] == -1){
			$formData['template']['category'] = JRequest::getString('newcategory', '');
		}

		foreach($formData['template'] as $column => $value){
			acymailing_secureField($column);
			$template->$column = strip_tags($value);
		}

		$styles = JRequest::getVar('styles', array(), '', 'array');
		foreach($styles as $class => $oneStyle){
			$styles[$class] = str_replace('"', "'", $oneStyle);
			if(empty($oneStyle)) unset($styles[$class]);
		}

		$newStyles = JRequest::getVar('otherstyles', array(), '', 'array');
		if(!empty($newStyles)){
			foreach($newStyles['classname'] as $id => $className){
				if(!empty($className) AND $className != JText::_('CLASS_NAME') AND !empty($newStyles['style'][$id]) AND $newStyles['style'][$id] != JText::_('CSS_STYLE')){
					$className = str_replace(array(',', ' ', ':', '.', '#'), '', $className);
					$styles[$className] = str_replace('"', "'", $newStyles['style'][$id]);
				}
			}
		}
		$template->styles = serialize($styles);

		if(empty($template->thumb)){
			unset($template->thumb);
		}elseif($template->thumb == 'delete') $template->thumb = '';

		if(empty($template->readmore)){
			unset($template->readmore);
		}elseif($template->readmore == 'delete') $template->readmore = '';

		$template->body = JRequest::getVar('editor_body', '', '', 'string', JREQUEST_ALLOWRAW);
		if(ACYMAILING_J25) $template->body = JComponentHelper::filterText($template->body);

		if(!empty($styles['color_bg'])){
			$pat1 = '#^([^<]*<[^>]*background-color:)([^;">]{1,30})#i';
			$found = false;
			if(preg_match($pat1, $template->body)){
				$template->body = preg_replace($pat1, '$1'.$styles['color_bg'], $template->body);
				$found = true;
			}
			$pat2 = '#^([^<]*<[^>]*bgcolor=")([^;">]{1,10})#i';
			if(preg_match($pat2, $template->body)){
				$template->body = preg_replace($pat2, '$1'.$styles['color_bg'], $template->body);
				$found = true;
			}
			if(!$found){
				$template->body = '<div style="background-color:'.$styles['color_bg'].';" width="100%">'.$template->body.'</div>';
			}
		}

		$acypluginsHelper = acymailing_get('helper.acyplugins');
		$acypluginsHelper->cleanHtml($template->body);

		$template->description = JRequest::getVar('editor_description', '', '', 'string', JREQUEST_ALLOWHTML);

		$tempid = $this->save($template);
		if(!$tempid) return false;

		if(empty($template->tempid)){
			$orderClass = acymailing_get('helper.order');
			$orderClass->pkey = 'tempid';
			$orderClass->table = 'template';
			$orderClass->reOrder();
		}

		$this->createTemplateFile($tempid);

		JRequest::setVar('tempid', $tempid);
		return true;
	}

	function save($element){
		if(empty($element->tempid)){
			if(empty($element->namekey)) $element->namekey = time().JFilterOutput::stringURLSafe($element->name);
		}else{
			if(file_exists(ACYMAILING_TEMPLATE.'css'.DS.'template_'.intval($element->tempid).'.css')){
				jimport('joomla.filesystem.file');
				if(!JFile::delete(ACYMAILING_TEMPLATE.'css'.DS.'template_'.intval($element->tempid).'.css')){
					echo acymailing_display('Could not delete the file '.ACYMAILING_TEMPLATE.'css'.DS.'template_'.intval($element->tempid).'.css', 'error');
				}
			}
		}

		if(!empty($element->styles) AND !is_string($element->styles)) $element->styles = serialize($element->styles);

		if(!empty($element->stylesheet)){
			$element->stylesheet = preg_replace('#:(active|current|visited)#i', '', $element->stylesheet);
		}

		return parent::save($element);
	}

	function detecttemplates($folder){
		$allFiles = JFolder::files($folder);
		if(!empty($allFiles)){
			foreach($allFiles as $oneFile){
				if(preg_match('#^.*(html|htm)$#i', $oneFile)){
					if($this->installtemplate($folder.DS.$oneFile)) return true;
				}
			}
		}

		$status = false;
		$allFolders = JFolder::folders($folder);
		if(!empty($allFolders)){
			foreach($allFolders as $oneFolder){
				$status = $this->detecttemplates($folder.DS.$oneFolder) || $status;
			}
		}

		return $status;
	}

	function buildCSS($styles, $stylesheet){
		$inline = '';

		if(preg_match_all('#@import[^;]*;#is', $stylesheet, $results)){
			foreach($results[0] as $oneResult){
				$inline .= trim($oneResult)."\n";
				$stylesheet = str_replace($oneResult, '', $stylesheet);
			}
		}

		if(!empty($styles)){
			foreach($styles as $class => $style){
				if(preg_match('#^tag_(.*)$#', $class, $result)){
					if(!empty($style)) $inline .= $result[1].' { '.$style.' } '."\n";
				}elseif($class != 'color_bg'){
					if(!empty($style)) $inline .= '.'.$class.' {'.$style.'} '."\n";
				}else{
					if(!empty($style)) $inline .= 'body{background-color:'.$style.';} '."\n";
				}
			}
		}

		if(version_compare(PHP_VERSION, '5.0.0', '>=') && class_exists('DOMDocument') && function_exists('mb_convert_encoding')){
			$inline .= 'a img{ border:0px; text-decoration:none;} '."\n";
			$inline .= $stylesheet;
		}

		return $inline;
	}

	function createTemplateFile($id){
		if(empty($id)) return '';
		$cssfile = ACYMAILING_TEMPLATE.'css'.DS.'template_'.$id.'.css';
		if(file_exists($cssfile)) return $cssfile;

		$template = $this->get($id);
		if(empty($template->tempid)) return '';
		$css = $this->buildCSS($template->styles, $template->stylesheet);

		if(empty($css)) return '';

		jimport('joomla.filesystem.file');

		acymailing_createDir(ACYMAILING_TEMPLATE.'css');

		if(JFile::write($cssfile, $css)){
			return $cssfile;
		}else{
			acymailing_enqueueMessage('Could not create the file '.$cssfile, 'error');
			return '';
		}
	}

	function installtemplate($filepath){
		$fileContent = file_get_contents($filepath);

		$newTemplate = new stdClass();
		$newTemplate->name = trim(preg_replace('#[^a-z0-9]#i', ' ', substr(dirname($filepath), strpos($filepath, '_template'))));
		if(preg_match('#< *title[^>]*>(.*)< */ *title *>#Uis', $fileContent, $results) && !empty($results[1])) $newTemplate->name = $results[1];

		if(preg_match('#< *meta *name="description" *content="([^"]*)"#Uis', $fileContent, $results) && !empty($results[1])) $newTemplate->description = $results[1];
		if(preg_match('#< *meta *name="fromname" *content="([^"]*)"#Uis', $fileContent, $results) && !empty($results[1])) $newTemplate->fromname = $results[1];
		if(preg_match('#< *meta *name="fromemail" *content="([^"]*)"#Uis', $fileContent, $results) && !empty($results[1])) $newTemplate->fromemail = $results[1];
		if(preg_match('#< *meta *name="replyname" *content="([^"]*)"#Uis', $fileContent, $results) && !empty($results[1])) $newTemplate->replyname = $results[1];
		if(preg_match('#< *meta *name="replyemail" *content="([^"]*)"#Uis', $fileContent, $results) && !empty($results[1])) $newTemplate->replyemail = $results[1];

		$newFolder = preg_replace('#[^a-z0-9]#i', '_', strtolower($newTemplate->name));
		$newTemplateFolder = $newFolder;
		$i = 1;
		while(is_dir(ACYMAILING_TEMPLATE.$newTemplateFolder)){
			$newTemplateFolder = $newFolder.'_'.$i;
			$i++;
		}
		$newTemplate->namekey = rand(0, 10000).$newTemplateFolder;
		$moveResult = JFolder::copy(dirname($filepath), ACYMAILING_TEMPLATE.$newTemplateFolder);
		if($moveResult !== true){
			acymailing_display(array('Error copying folder from '.dirname($filepath).' to '.ACYMAILING_TEMPLATE.$newTemplateFolder, $moveResult), 'error');
			return false;
		}

		if(!file_exists(ACYMAILING_TEMPLATE.$newTemplateFolder.DS.'index.html')){
			$indexFile = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write(ACYMAILING_TEMPLATE.$newTemplateFolder.DS.'index.html', $indexFile);
		}
		$fileContent = preg_replace('#(src|background)[ ]*=[ ]*\"(?!(https?://|/))(?:\.\./|\./)?#', '$1="media/com_acymailing/templates/'.$newTemplateFolder.'/', $fileContent);

		if(preg_match('#< *body[^>]*>(.*)< */ *body *>#Uis', $fileContent, $results)){
			$newTemplate->body = $results[1];
		}else{
			$newTemplate->body = $fileContent;
		}

		$newTemplate->stylesheet = '';
		if(preg_match_all('#< *style[^>]*>(.*)< */ *style *>#Uis', $fileContent, $results)){
			$newTemplate->stylesheet .= preg_replace('#(<!--|-->)#s', '', implode("\n", $results[1]));
		}
		$cssFiles = array();
		$cssFiles[ACYMAILING_TEMPLATE.$newTemplateFolder] = JFolder::files(ACYMAILING_TEMPLATE.$newTemplateFolder, '\.css$');
		$subFolders = JFolder::folders(ACYMAILING_TEMPLATE.$newTemplateFolder);
		foreach($subFolders as $oneFolder){
			$cssFiles[ACYMAILING_TEMPLATE.$newTemplateFolder.DS.$oneFolder] = JFolder::files(ACYMAILING_TEMPLATE.$newTemplateFolder.DS.$oneFolder, '\.css$');
		}

		foreach($cssFiles as $cssFolder => $cssFile){
			if(empty($cssFile)) continue;
			$newTemplate->stylesheet .= "\n".file_get_contents($cssFolder.DS.reset($cssFile));
		}

		if(!empty($newTemplate->stylesheet)){
			if(preg_match('#body *\{[^\}]*background-color:([^;\}]*)[;\}]#Uis', $newTemplate->stylesheet, $backgroundresults)){
				$newTemplate->styles['color_bg'] = trim($backgroundresults[1]);
				$newTemplate->stylesheet = preg_replace('#(body *\{[^\}]*)background-color:[^;\}]*[;\}]#Uis', '$1', $newTemplate->stylesheet);
			}

			$quickstyle = array('tag_h1' => 'h1', 'tag_h2' => 'h2', 'tag_h3' => 'h3', 'tag_h4' => 'h4', 'tag_h5' => 'h5', 'tag_h6' => 'h6', 'tag_a' => 'a', 'tag_ul' => 'ul', 'tag_li' => 'li', 'acymailing_unsub' => '\.acymailing_unsub', 'acymailing_online' => '\.acymailing_online', 'acymailing_title' => '\.acymailing_title', 'acymailing_content' => '\.acymailing_content', 'acymailing_readmore' => '\.acymailing_readmore');
			foreach($quickstyle as $styledb => $oneStyle){
				if(preg_match('#[^a-z\. ,] *'.$oneStyle.' *{([^}]*)}#Uis', $newTemplate->stylesheet, $quickstyleresults)){
					$newTemplate->styles[$styledb] = trim(str_replace(array("\n", "\r", "\t", "\s"), ' ', $quickstyleresults[1]));
					$newTemplate->stylesheet = str_replace($quickstyleresults[0], '', $newTemplate->stylesheet);
				}
			}
		}

		if(!empty($newTemplate->styles['color_bg'])){
			$pat1 = '#^([^<]*<[^>]*background-color:)([^;">]{1,10})#i';
			$found = false;
			if(preg_match($pat1, $newTemplate->body)){
				$newTemplate->body = preg_replace($pat1, '$1'.$newTemplate->styles['color_bg'], $newTemplate->body);
				$found = true;
			}
			$pat2 = '#^([^<]*<[^>]*bgcolor=")([^;">]{1,10})#i';
			if(preg_match($pat2, $newTemplate->body)){
				$newTemplate->body = preg_replace($pat2, '$1'.$newTemplate->styles['color_bg'], $newTemplate->body);
				$found = true;
			}
			if(!$found){
				$newTemplate->body = '<div style="background-color:'.$newTemplate->styles['color_bg'].';" width="100%">'.$newTemplate->body.'</div>';
			}
		}

		$foldersForPicts = array($newTemplateFolder);
		$otherFolders = JFolder::folders(ACYMAILING_TEMPLATE.$newTemplateFolder);
		foreach($otherFolders as $oneFold){
			$foldersForPicts[] = $newTemplateFolder.DS.$oneFold;
		}
		$allPictures = array();
		foreach($foldersForPicts as $oneFolder){
			$allPictures[$oneFolder] = JFolder::files(ACYMAILING_TEMPLATE.$oneFolder);
		}
		foreach($allPictures as $folder => $pictfolders){
			foreach($pictfolders as $onePict){
				if(!preg_match('#\.(jpg|gif|png|jpeg|ico|bmp)$#i', $onePict)) continue;
				if(preg_match('#(thumbnail|screenshot|muestra)#i', $onePict)){
					$newTemplate->thumb = 'media/com_acymailing/templates/'.str_replace(DS, '/', $folder).'/'.$onePict;
				}elseif(preg_match('#(readmore|lirelasuite)#i', $onePict)){
					$newTemplate->readmore = 'media/com_acymailing/templates/'.str_replace(DS, '/', $folder).'/'.$onePict;
				}
			}
		}

		$newTemplate->ordering = 0;

		$tempid = $this->save($newTemplate);
		$this->templateId = $tempid;
		if($this->checkAreas){
			$this->proposedAreas = $this->proposeApplyAreas($tempid, false) || $this->proposedAreas;
		}

		$this->createTemplateFile($tempid);

		$orderClass = acymailing_get('helper.order');
		$orderClass->pkey = 'tempid';
		$orderClass->table = 'template';
		$orderClass->reOrder();

		$this->templateNames[] = $newTemplate->name;

		return true;
	}

	function displayPreview($idArea, $tempid, $newslettersubject = ''){
		acymailing_loadMootools();

		if(isset($_SERVER["REQUEST_URI"])){
			$requestUri = $_SERVER["REQUEST_URI"];
		}else{
			$requestUri = $_SERVER['PHP_SELF'];
			if(!empty($_SERVER['QUERY_STRING'])) $requestUri = rtrim($requestUri, '/').'?'.$_SERVER['QUERY_STRING'];
		}
		$currentURL = (((!empty($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS']) == "on") || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://').$_SERVER["HTTP_HOST"].$requestUri;

		$js = "var iframecreated = false;
				function acydisplayPreview(){
					var d = document, area = d.getElementById('$idArea');
					if(!area) return;
					if(iframecreated) return;
					iframecreated = true;
					var content = area.innerHTML;
					var myiframe = d.createElement(\"iframe\");
					myiframe.id = 'iframepreview';
					myiframe.style.width = '100%';
					myiframe.style.borderWidth = '0px';
					myiframe.allowtransparency = \"true\";
					myiframe.frameBorder = '0';
					area.innerHTML = '';
					area.appendChild(myiframe);
					myiframe.onload = function(){
						var iframeloaded = false;
						try{
							if(myiframe.contentDocument != null && initIframePreview(myiframe,content) && replaceAnchors(myiframe)){
								iframeloaded = true;
							}
						}catch(err){
							iframeloaded = false;
						}

						if(!iframeloaded){
							area.innerHTML = content;
						}
					}
					myiframe.src = '';

				}
				function resetIframeSize(myiframe){


					var innerDoc = (myiframe.contentDocument) ? myiframe.contentDocument : myiframe.contentWindow.document;
					var objToResize = (myiframe.style) ? myiframe.style : myiframe;
					if(objToResize.width != '100%') return;
					var newHeight = innerDoc.body.scrollHeight;
					if(!objToResize.height || parseInt(objToResize.height,10)+10 < newHeight || parseInt(objToResize.height,10)-10 > newHeight) objToResize.height = newHeight+'px';
					setTimeout(function(){resetIframeSize(myiframe);},1000);
				}
				function replaceAnchors(myiframe){
					var myiframedoc = myiframe.contentWindow.document;
					var myiframebody = myiframedoc.body;
					var el = myiframe;
					var myiframeOffset = el.offsetTop;
					while ( ( el = el.offsetParent ) != null )
					{
						myiframeOffset += el.offsetTop;
					}

					var elements = myiframebody.getElementsByTagName(\"a\");
					for( var i = elements.length - 1; i >= 0; i--){
						var aref = elements[i].getAttribute('href');
						if(!aref) continue;
						if(aref.indexOf(\"#\") != 0 && aref.indexOf(\"".addslashes($currentURL)."#\") != 0) continue;

						if(elements[i].onclick && elements[i].onclick != \"\") continue;

						var adest = aref.substring(aref.indexOf(\"#\")+1);
						if( adest.length < 1 ) continue;

						elements[i].dest = adest;
						elements[i].onclick = function(){
							elem = myiframedoc.getElementById(this.dest);
							if(!elem){
								elems = myiframedoc.getElementsByName(this.dest);
								if(!elems || !elems[0]) return false;
								elem = elems[0];
							}
							if( !elem ) return false;

							var el = elem;
							var elemOffset = el.offsetTop;
							while ( ( el = el.offsetParent ) != null )
							{
								elemOffset += el.offsetTop;
							}
							window.scrollTo(0,elemOffset+myiframeOffset-15);
							return false;
						};
					}
					return true;
				}
				function initIframePreview(myiframe,content){
					var d = document;

					var heads = myiframe.contentWindow.document.getElementsByTagName(\"head\");
					if(heads.length == 0){
						return false;
					}

					var head = heads[0];

					var myiframebodys = myiframe.contentWindow.document.getElementsByTagName('body');
					if(myiframebodys.length == 0){
						var myiframebody = d.createElement(\"body\");
						myiframe.appendChild(myiframebody);
					}else{
						var myiframebody = myiframebodys[0];
					}
					if(!myiframebody) return false;
					myiframebody.style.margin = '0px';
					myiframebody.style.padding = '0px';
					myiframebody.innerHTML = content;

					var title1 = d.createElement(\"title\");
					title1.innerHTML = '".addslashes($newslettersubject)."';


					var base1 = d.createElement(\"base\");
					base1.target = \"_blank\";

					head.appendChild(base1);

					var existingTitle = head.getElementsByTagName(\"title\");
					if(existingTitle.length == 0){
						head.appendChild(title1);
					}
				";
		if(!empty($tempid)){
			$js .= "var link1 = d.createElement(\"link\");
					link1.type = \"text/css\";
					link1.rel = \"stylesheet\";
					link1.href =  '".(rtrim(JURI::root(), '/').'/')."media/com_acymailing/templates/css/template_".$tempid.".css?v=".@filemtime(ACYMAILING_MEDIA.'templates'.DS.'css'.DS.'template_'.$tempid.'.css')."';
					head.appendChild(link1);
				";
		}

		$js .= "var style1 = d.createElement(\"style\");
				style1.type = \"text/css\";
				style1.id = \"overflowstyle\";
				try{style1.innerHTML = 'html,body,iframe{overflow-y:hidden} ';}catch(err){style1.styleSheet.cssText = 'html,body,iframe{overflow-y:hidden} ';}
				";

		if($this->archiveSection){
			$js .= "try{style1.innerHTML += ' .hideonline{display:none;} ';}catch(err){style1.styleSheet.cssText += ' .hideonline{display:none;} ';}";
		}

		$js .= "
				head.appendChild(style1);
				resetIframeSize(myiframe);
				return true;
			}
			window.addEvent('domready', function(){acydisplayPreview();} );";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		$resize = "function previewResize(newWidth,newHeight){
			if(document.getElementById('iframepreview')){
				var myiframe = document.getElementById('iframepreview');
			}else{
				var myiframe = document.getElementById('newsletter_preview_area');
			}
			myiframe.style.width = newWidth;
			if(newHeight == '100%'){
				resetIframeSize(myiframe);
			}else{
				myiframe.style.height = newHeight;
				myiframe.contentWindow.document.getElementById('overflowstyle').media = \"print\";
			}
		}
		function previewSizeClick(elem){
			var ids = new Array('preview320','preview480','preview768','previewmax');
			for(var i=0;i<ids.length;i++){
				document.getElementById(ids[i]).className = 'previewsize '+ids[i];
			}
			elem.className += 'enabled';
		}";
		$doc->addScriptDeclaration($resize);
		$switchPict = "function switchPict(){
			var myiframe = document.getElementById('iframepreview');
			var myiframebody = myiframe.contentWindow.document.getElementsByTagName('body')[0];
			if(document.getElementById('previewpict').className == 'previewsize previewpictenabled'){
				remove = true;
				document.getElementById('previewpict').className = 'previewsize previewpict';
			}else{
				remove = false;
				document.getElementById('previewpict').className = 'previewsize previewpictenabled';
			}
			var elements = myiframebody.getElementsByTagName(\"img\");
			for( var i = elements.length - 1; i >= 0; i-- ) {
				if(remove){
					elements[i].src_temp = elements[i].src;
					elements[i].src = 'pictureremoved';
				}else{
					elements[i].src = elements[i].src_temp;
				}
			}
			if(myiframe.style.width == '100%'){
				resetIframeSize(myiframe);
			}
		}";
		$doc->addScriptDeclaration($switchPict);
	}

	function proposeApplyAreas($tempid, $addextrawarning = true){
		if(empty($tempid)) return false;

		$config = acymailing_config();
		if($config->get('editor') != 'acyeditor') return false;

		$template = $this->get($tempid);
		if(empty($template->body)) return false;
		if(strpos($template->body, 'acyeditor_')) return false;

		$messages = array('<a href="index.php?option=com_acymailing&ctrl=template&task=applyareas&tempid='.$tempid.(JRequest::getCmd('tmpl') == 'component' ? '&tmpl=component' : '').'">'.JText::_('ACYEDITOR_ADDAREAS').'</a>');
		if($addextrawarning) $messages[] = JText::_('ACYEDITOR_ADDAREAS_ONLYFINISHED');
		acymailing_enqueueMessage($messages, 'warning');
		return true;
	}

	function applyAreas(&$html){

		if(strpos($html, 'acyeditor_')) return false;

		if(preg_match_all('#(<td[^>]*>) *(<img[^>]*> *</td>)#Uis', $html, $results)){
			foreach($results[0] as $i => $oneResult){
				if(preg_match('#class=("|\'])#Uis', $results[1][$i], $charused)){
					$newTag = str_replace('class='.$charused[1], 'class='.$charused[1].'acyeditor_picture ', $results[1][$i]);
				}else{
					$newTag = str_replace('<td', '<td class="acyeditor_picture"', $results[1][$i]);
				}
				$html = str_replace($results[0][$i], $newTag.$results[2][$i], $html);
			}
		}

		$textElements = array('td', 'div');
		$divhtml = $html;
		foreach($textElements as $starttag){
			if(!preg_match_all('#(<'.$starttag.'(?:(?!>|acyeditor_).)*>)((?:(?!<td|acyeditor_|<'.$starttag.').)*</'.$starttag.'>)#Uis', $divhtml, $results)) continue;

			$class = 'acyeditor_text';
			if($starttag == 'div') $class .= ' acyeditor_delete';

			foreach($results[0] as $i => $oneResult){

				$content = trim(str_replace(array(' ', '&nbsp;', "\n", "\r"), '', strip_tags($results[0][$i])));

				if(empty($content)) continue;

				if(preg_match('#class=("|\'])#Uis', $results[1][$i], $charused)){
					$newTag = str_replace('class='.$charused[1], 'class='.$charused[1].$class.' ', $results[1][$i]);
				}else{
					$newTag = str_replace('<'.$starttag, '<'.$starttag.' class="'.$class.'"', $results[1][$i]);
				}
				$html = str_replace($results[0][$i], $newTag.$results[2][$i], $html);
				$divhtml = str_replace($results[0][$i], '', $divhtml);
			}
		}

		if(preg_match_all('#(<tr[^>]*>)((?:(?!<tr|acyeditor_delete).)*</tr>)#Uis', $html, $results)){
			foreach($results[0] as $i => $oneResult){
				if(preg_match('#class=("|\'])#Uis', $results[1][$i], $charused)){
					$newTag = str_replace('class='.$charused[1], 'class='.$charused[1].'acyeditor_delete ', $results[1][$i]);
				}else{
					$newTag = str_replace('<tr', '<tr class="acyeditor_delete"', $results[1][$i]);
				}
				$html = str_replace($results[0][$i], $newTag.$results[2][$i], $html);
			}
		}

		if(preg_match_all('#(<table[^>]*>)((?:(?!<table).)*</table>)#Uis', $html, $results)){
			foreach($results[0] as $i => $newContent){
				if(strpos($newContent, '<tbody') === false){
					$newContent = preg_replace('#(<table[^>]*>)#Uis', '$1<tbody>', $newContent);
					$newContent = preg_replace('#(< */ *table *>)#Uis', '</tbody>$1', $newContent);
				}

				if(preg_match('#(<tbody[^>]*)class=("|\'])#Uis', $newContent, $charused)){
					$newContent = str_replace($charused[0], $charused[1].'class='.$charused[2].'acyeditor_sortable ', $newContent);
				}else{
					$newContent = str_replace('<tbody', '<tbody class="acyeditor_sortable"', $newContent);
				}

				$html = str_replace($results[0][$i], $newContent, $html);
			}
		}

		return true;
	}

	function doupload(){
		$importFile = JRequest::getVar('uploadedfile', '', 'files');

		$fileError = $_FILES['uploadedfile']['error'];
		if($fileError > 0){
			switch($fileError){
				case 1:
					acymailing_enqueueMessage('The uploaded file exceeds the upload_max_filesize directive in php configuration.', 'error');
					return false;
				case 2:
					acymailing_enqueueMessage('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.', 'error');
					return false;
				case 3:
					acymailing_enqueueMessage('The uploaded file was only partially uploaded.', 'error');
					return false;
				case 4:
					acymailing_enqueueMessage('No file was uploaded.', 'error');
					return false;
				default:
					acymailing_enqueueMessage('Error uploading the file on the server, unknown error '.$fileError, 'error');
					return false;
			}
		}

		if(empty($importFile['name'])){
			acymailing_enqueueMessage(JText::_('BROWSE_FILE'), 'error');
			return false;
		}

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.path');

		$config =& acymailing_config();

		$uploadPath = JPath::clean(ACYMAILING_ROOT.'media'.DS.ACYMAILING_COMPONENT.DS.'templates');

		if(!is_writable($uploadPath)){
			@chmod($uploadPath, '0755');
			if(!is_writable($uploadPath)){
				acymailing_display(JText::sprintf('WRITABLE_FOLDER', $uploadPath), 'warning');
			}
		}

		if(!(bool)ini_get('file_uploads')){
			acymailing_display('Can not upload the file, please make sure file_uploads is enabled on your php.ini file', 'error');
			return false;
		}

		if(!extension_loaded('zlib')){
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}

		$filename = strtolower(JFile::makeSafe($importFile['name']));
		$extension = strtolower(substr($filename, strrpos($filename, '.') + 1));

		if(!in_array($extension, array('zip', 'tar.gz'))){
			acymailing_display(JText::sprintf('ACCEPTED_TYPE', $extension, 'zip,tar.gz'), 'error');
			return false;
		}

		$joomConfig = JFactory::getConfig();
		$jpath = ACYMAILING_J30 ? $joomConfig->get('tmp_path') : $joomConfig->getValue('config.tmp_path');
		$tmp_dest = JPath::clean($jpath.DS.$filename);
		$tmp_src = $importFile['tmp_name'];

		$uploaded = JFile::upload($tmp_src, $tmp_dest);
		if(!$uploaded){
			acymailing_display('Error uploading the file from '.$tmp_src.' to '.$tmp_dest, 'error');
			return false;
		}

		$tmpdir = uniqid().'_template';

		$extractdir = JPath::clean(dirname($tmp_dest).DS.$tmpdir);

		$result = JArchive::extract($tmp_dest, $extractdir);
		JFile::delete($tmp_dest);

		$allFiles = JFolder::files($extractdir, '.', true, true, array(), array());
		foreach($allFiles as $oneFile){
			if(preg_match('#\.(jpg|gif|png|jpeg|ico|bmp|html|htm|css)$#i', $oneFile)){
				continue;
			}
			if(JFile::delete($oneFile)){
				acymailing_display('File '.$oneFile.' deleted from the template pack', 'warning');
			}
		}

		if(!$result){
			acymailing_display('Error extracting the file '.$tmp_dest.' to '.$extractdir, 'error');
			return false;
		}

		if($this->detecttemplates($extractdir)){
			$messages = $this->templateNames;
			array_unshift($messages, JText::sprintf('TEMPLATES_INSTALL', count($this->templateNames)));
			acymailing_display($messages, 'success');
			if(is_dir($extractdir)) JFolder::delete($extractdir);
			return true;
		}

		acymailing_display('Error installing template', 'error');
		if(is_dir($extractdir)) JFolder::delete($extractdir);
		return false;
	}

	function export($tempid){
		if(!extension_loaded('zlib')){
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.archive');

		$template = $this->get($tempid);
		$fileDeb = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		if(!empty($template->description)){
			$fileDeb .= '
<meta name="description" content="'.str_replace('"', "'", $template->description).'" />';
		}
		if(!empty($template->fromname)){
			$fileDeb .= '
<meta name="fromname" content="'.$template->fromname.'" />';
		}
		if(!empty($template->fromemail)){
			$fileDeb .= '
<meta name="fromemail" content="'.$template->fromemail.'" />';
		}
		if(!empty($template->replyname)){
			$fileDeb .= '
<meta name="replyname" content="'.$template->replyname.'" />';
		}
		if(!empty($template->replyemail)){
			$fileDeb .= '
<meta name="replyemail" content="'.$template->replyemail.'" />';
		}
		$fileDeb .= '
<title>'.$template->name.'</title>';

		$css = '
<style type="text/css">
';
		$css .= file_get_contents(ACYMAILING_TEMPLATE.DS.'css'.DS.'template_'.$tempid.'.css');
		$css .= '
</style>';

		$indexFile = $fileDeb.$css.'
</head>
<body>
'.$template->body.'
</body>
</html>';

		$tmpdir = preg_replace('#[^a-z0-9]#i', '_', strtolower($template->name));
		$jpathURL = ACYMAILING_LIVE.'media/'.ACYMAILING_COMPONENT.'/tmp';
		$tmp_url_dest = $jpathURL.DS.$tmpdir;
		$jpath = ACYMAILING_MEDIA.'tmp';
		$tmp_dest = JPath::clean($jpath.DS.$tmpdir);
		if(!JFolder::exists($jpath)){
			if(!JFolder::create($jpath)){
				acymailing_enqueueMessage('Error creating temp folder in directory: '.$jpath, 'error');
				return false;
			}
		}
		if(!JFolder::create($tmp_dest)){
			acymailing_enqueueMessage('Error creating folder in temp directory: '.$tmp_dest, 'error');
			return false;
		}

		if(!empty($template->thumb)){
			$thumbPath = JPath::clean(ACYMAILING_ROOT.DS.$template->thumb);
			$thumbExt = JFile::getExt($thumbPath);
			$resCopyThumb = JFile::copy($thumbPath, $tmp_dest.DS.'thumbnail.'.$thumbExt);
			if(!$resCopyThumb){
				acymailing_enqueueMessage('Error copying the thumb picture', 'warning');
			}
		}
		$resHandleImages = $this->handlepict($indexFile, $tmp_dest);
		if(!$resHandleImages){
			JFolder::delete($tmp_dest);
			return false;
		}

		$resCopyIndex = JFile::write($tmp_dest.DS.'index.html', $indexFile);
		if(!$resCopyIndex){
			acymailing_enqueueMessage('Error copying the file index.html to temp directory '.$tmp_dest, 'error');
			return false;
		}
		$zipFilesArray = array();
		$dirs = JFolder::folders($tmp_dest, '.', true, true);
		array_push($dirs, $tmp_dest);
		foreach($dirs as $dir){
			$files = JFolder::files($dir, '.', false, true);
			foreach($files as $file){
				$posSlash = strrpos($file, '/');
				$posASlash = strrpos($file, '\\');
				$pos = ($posSlash < $posASlash) ? $posASlash : $posSlash;
				if(!empty($pos)) $file = substr_replace($file, DS, $pos, 1);
				$data = JFile::read($file);
				$zipFilesArray[] = array('name' => str_replace($tmp_dest.DS, '', $file), 'data' => $data);
			}
		}
		$zip = JArchive::getAdapter('zip');
		$zip->create($tmp_dest.'.zip', $zipFilesArray);

		JFolder::delete($tmp_dest);

		return $tmp_url_dest.'.zip';
	}

	function handlepict(&$content, $templatepath){

		$content = acymailing_absoluteURL($content);

		if(!preg_match_all('#<img[^>]*src="([^"]*)"#i', $content, $pictures)) return true;

		$pictFolder = rtrim($templatepath, DS).DS.'images';
		if(!acymailing_createDir($pictFolder)){
			return false;
		}

		$replace = array();
		foreach($pictures[1] as $onePict){
			if(isset($replace[$onePict])) continue;

			$location = str_replace(array(ACYMAILING_LIVE, '/'), array(ACYMAILING_ROOT, DS), $onePict);
			if(strpos($location, 'http') === 0) continue;

			if(!file_exists($location)) continue;

			$filename = basename($location);
			while(file_exists($pictFolder.DS.$filename)){
				$filename = rand(0, 99).$filename;
			}

			if(JFile::copy($location, $pictFolder.DS.$filename) !== true){
				acymailing_display('Could not copy the file from '.$location.' to '.$pictFolder.DS.$filename, 'error');
				return false;
			}

			$replace[$onePict] = 'images/'.$filename;
		}

		$content = str_replace(array_keys($replace), $replace, $content);

		return true;
	}
}
