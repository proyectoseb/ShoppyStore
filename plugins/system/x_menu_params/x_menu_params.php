<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemX_Menu_Params extends JPlugin {
	var $_menuid = 0;
	var $_resources = '';
	
	function plgSystemX_Menu_Params(&$subject, $pluginconfig) {
		parent::__construct($subject, $pluginconfig);
		$this->_resources = dirname(__FILE__).'/x_menu_params/xml/';
	
	}

	function appendX_Menu_Params($bodyContent, $xml) {		
		if( !file_exists($xml) ){
			return $bodyContent;
		}		
		$extraHtml = $this->getX_Menu_Params($xml);
		preg_match_all("/<div class=\"panel\">([\s\S]*?)<\/div>/i", $bodyContent, $arr);

		$bodyContent = str_replace($arr[0][count($arr[0])-1].'</div>', $arr[0][count($arr[0])-1].'</div>'.$extraHtml, $bodyContent);
		return $bodyContent;
	}
	
	function getX_Menu_Params($xmlfile = "") {
		$html_content = "";
		
		$paramObjFromXml =  new JParameter('', $xmlfile);
		$label = "Parameters (X Menu Params)";
			
		if(isset($paramObjFromXml->_xml["params"]))
		{
			$label = $paramObjFromXml->_xml["params"]->_attributes["label"];
			$paramObjFromXml = $this->bindSetValues( $paramObjFromXml );
		}
		ob_start ();
		echo $paramObjFromXml->render('params', 'params');
		$html_content = ob_get_clean ();
		ob_start ();
		
		$html_content = '<div class="panel">
				<h3 id="x-menu-params" class="jpane-toggler title" style="color: #0D9FC4;">
				<span>'.JText::_($label).'</span></h3>
				<div class="jpane-slider content" style="border-top: medium none; border-bottom: medium none; overflow: hidden; padding-top: 0px; padding-bottom: 0px;">
				'.$html_content."</div></div>";
		
		return $html_content;
	}
	
	function bindSetValues( $ext_params = null ) {
		if(!empty($this->_menuid)) {
			if(is_array($this->_menuid)){
				$menuid =$this->_menuid[0];
			} else {	
				$menuid = $this->_menuid;
			}
			$db	=& JFactory::getDBO();
			$query = "SELECT params FROM #__menu WHERE id = ".$menuid;
			$db->setQuery($query);
			$row = $db->loadObject();
			
			if(!empty($row)) {
				$params	= new JParameter($row->params);
				$list_params = $ext_params->renderToArray("params", "params");
			
				foreach($list_params as $key=>$value) {
					if(!empty($key) && strpos($key, "ytext_" ) !== false) {
						$tmp_value = $params->get( $key, "" );
						if(is_array($tmp_value)){
							$tmp_value = implode("|",$tmp_value);
						}
						$ext_params->set($key, $tmp_value);
					}
				}
			}
		}
		return $ext_params;
	}

	function onContentPrepareForm($form, $data) {
		if ($form->getName()=='com_menus.item') {	
			JForm::addFormPath($this->_resources);
			$form->loadFile('menus_edit_params', false);
		}
	}
}