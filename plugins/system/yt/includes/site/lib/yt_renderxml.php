<?php
/*
 * ------------------------------------------------------------------------
 * Yt FrameWork for Joomla 3.0
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2012 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
if (!class_exists('YtFrameworkRenderXML')){
class YtFrameworkRenderXML {		
	// layout array of xml
	var $layout = array();
	// array Tag Head
	var $arr_TH = array();
	// array Tag Body
	var $arr_TB = array();
	// array Group Info
	var $arr_GI = array();
	// layout type ex: left-main, main-right, ...
	var $layouttype ='';
	//
	var $cinfo = array();
	// main prefix
	var $mainprefix;


	function YtFrameworkRenderXML($xmlfile){	
		$dom = new DOMDocument();
		
		if (strpos($xmlfile, '.xml.xml') !== false ) {
			//Remove double xml extension
			$xmlfile = substr($xmlfile, 0, strlen($xmlfile) - 4 );
		}
		
		//load file layout
		if(!$dom->load(J_TEMPLATEDIR.J_SEPARATOR.'layouts'.J_SEPARATOR.$xmlfile)){
			echo '<h2>structure or name of file: <span style="color:red">'.$xmlfile.'</span> is not exactly</h2>'; die();
		}
		$this->layout = $dom->getElementsByTagName("layout");

		//$this->groupInfo(); 
		foreach($this->layout as $layout):
			$this->layouttype = $layout->getAttribute('type');
		endforeach;
		$this->getTagHead();  
		$this->getTagBody();
	}	
	
	function groupInfo($prefix){
		$group_info = array();
		foreach($this->layout as $layout):
			foreach($layout->childNodes as $childLayout):
			  if($childLayout->nodeName=='groups'){ 
				  foreach($childLayout->childNodes as $group): if($group->nodeName!='#text'){
					  $group_i['name'] = $group->nodeValue;
					  $group_i['height'] = $group->getAttribute('height');
					  $group_i['width'] = $group->getAttribute('width');
					  $group_i['overlogo'] = $group->getAttribute('overlogo');					
					  if(strtolower($group->nodeValue)=='main'){
						  $prefx = $prefix;
					  }else{
						  $prefx = '';
					  }
					  if($group->hasAttribute($prefx.'class')){
					  	$group_i['class'] = $group->getAttribute($prefx.'class');
					  }elseif($group->hasAttribute('class')){
						$group_i['class'] = $group->getAttribute('class');
					  }
					  if($group->hasAttribute($prefx.'data-wide')){
						$posit['data-wide'] = $group->getAttribute($prefx.'data-wide');
					  }
					  if($group->hasAttribute($prefx.'data-normal')){
						$group_i['data-normal'] = $group->getAttribute($prefx.'data-normal');
					  }
					  if($group->hasAttribute($prefx.'data-tablet')){
						$group_i['data-tablet'] = $group->getAttribute($prefx.'data-tablet');
					  }
					  if($group->hasAttribute($prefx.'data-stablet')){
						$group_i['data-stablet'] = $group->getAttribute($prefx.'data-stablet');
					  }
					  if($group->hasAttribute($prefx.'data-mobile')){
						$group_i['data-mobile'] = $group->getAttribute($prefx.'data-mobile');
					  }
					  
					  $group_info[$group->nodeValue] = $group_i; $group_i =null;	
				  }
				  endforeach;		      
			  }
			endforeach;
		endforeach;
		//var_dump($group_info); die();
		return $this->arr_GI = $group_info;     
	}
	function getTagHead() {
		$arr_head = array();
		foreach($this->layout as $layout):
			foreach($layout->childNodes as $childLayout):
			  if($childLayout->nodeName=='head'){
				  foreach($childLayout->childNodes as $head): if($head->nodeName!='#text'){
						  $arr_head[$head->nodeName][] = $head->nodeValue;
				  }
				  endforeach;				      
			  }
			endforeach;
		endforeach;
		return $this->arr_TH = $arr_head;          
	}
	
	function getTagBody(){
		foreach($this->layout as $layout):
			foreach($layout->childNodes as $childLayout):
			  if($childLayout->nodeName=='blocks'){	 
				$this->getBlocks($childLayout);	      
			  }
			endforeach;
		endforeach;	
	}
	
	function getBlocks($blocks){
		$doc = JFactory::getDocument();
		/* Array of block have field
			[name] => 
			[order] => 
			[id] => 
			[positions] => Array
			...
		*/
		$tagBody = array();
		foreach($blocks->childNodes as $block): if($block->nodeName != '#text'){
			$tagBody['name'] = $block->nodeName;
			$tagBody['html5tag'] = ($block->getAttribute('html5tag')!='')?$block->getAttribute('html5tag'):'div';
			$tagBody['order'] = (int)$block->getAttribute('order');
			$tagBody['id'] = $block->getAttribute('id');
			
			foreach($block->childNodes as $positions ): if($positions->nodeName!='#text'){
				$count = 0;
				foreach($positions->childNodes as $position):
					if($position->nodeName!='#text'){
						$posit['type'] = $position->getAttribute('type');
						$posit['class'] = ($position->getAttribute('class')!="")?$position->getAttribute('class'):"";
						$posit['style'] = $position->getAttribute('style');
						$posit['height'] = $position->getAttribute('height');
						$posit['width'] = $position->getAttribute('width');
						$posit['overlogo'] = $position->getAttribute('overlogo');
						$posit['value'] = $position->nodeValue;					
						$posit['group'] = $position->getAttribute('group');						
							
						if($position->hasAttribute('data-wide')){
							$posit['data-wide'] = $position->getAttribute('data-wide');
						}
						if($position->hasAttribute('data-normal')){
							$posit['data-normal'] = $position->getAttribute('data-normal');
						}
						if($position->hasAttribute('data-tablet')){
							$posit['data-tablet'] = $position->getAttribute('data-tablet');
						}
						if($position->hasAttribute('data-stablet')){
							$posit['data-stablet'] = $position->getAttribute('data-stablet');
						}
						if($position->hasAttribute('data-mobile')){
							$posit['data-mobile'] = $position->getAttribute('data-mobile');
						}
						// No left
						$posit['noleft-class'] = ($position->hasAttribute('noleft-class'))?$position->getAttribute('noleft-class'):$position->getAttribute('class');
						
						// No right
						$posit['noright-class'] = ($position->hasAttribute('noright-class'))?$position->getAttribute('noright-class'):$position->getAttribute('class');
						
						// No leftright
						$posit['noleftright-class'] = ($position->hasAttribute('noleftright-class'))?$position->getAttribute('noleftright-class'):$position->getAttribute('class');
						
										
						if( $position->getAttribute('type') == 'html' || 
							$position->getAttribute('type') == 'feature' ||
							$position->getAttribute('type') == 'component' ||
							$doc->countModules($position->nodeValue) ){
								$count = $count+1; 
						}
						$arr_po[] = $posit; $posit = null;
						
					}
				endforeach; 
				// field countModules of tagBody array 
				$tagBody['countModules'] = $count;     
				// field positions of tagBody array
				$tagBody['positions'] = $arr_po; $arr_po = null;	
													 
				if($tagBody['name']!='content'){ // not content			
					$tagBody = $this->updateTagBodyNormal($tagBody);
				}else{ // is content
					$tagBody = $this->updateTagBodyContent($tagBody);									
				}											 
				$this->arr_TB[] = $tagBody; $tagBody = null;                               		 
			}
			endforeach;
		}
		endforeach;
		$this->arr_TB = $this->sortArr($this->arr_TB, 'order');   //echo '<pre>'; print_r($this->arr_TB);	 die();		
		return $this->arr_TB;
	}
	function updateTagBodyNormal($tagBody){
		$doc = JFactory::getDocument();
		$flag=''; $countPosTag=0; $hasGroup = 0;
		$clas_tag = '';
		if( isset($tagBody['positions']) ){
			foreach($tagBody['positions'] as $atb):
				if($atb['group']==''){ 
					$countPosTag = $countPosTag + 1;
				}elseif($atb['group']!='' && $atb['group']!=$flag){ 
					$countPosTag = $countPosTag + 1;
					$hasGroup = 1;
					$flag = $atb['group'];
				}
			endforeach;  
		}
		if($hasGroup == 1){ 
			$class_groupnormal = '';
			$tagBody['hasGroup'] = 1; 
			for($i=0; $i<count($tagBody['positions']); $i++){	
				if($doc->countModules($tagBody['positions'][$i]['value'])<=0 
					&& $tagBody['positions'][$i]['type']=='modules'){
					$class_groupnormal .= ' nopos-'.$tagBody['positions'][$i]['value'];
				}
				if( isset($this->arr_GI[$tagBody['positions'][$i]['group']]['width']) ){
					$tagBody['width-'.$tagBody['positions'][$i]['group']] = $this->arr_GI[$tagBody['positions'][$i]['group']]['width'];			
				}
								
			} //end for
			$tagBody['class_groupnormal'] = $class_groupnormal;
		}
		return $tagBody;
	}
	function updateTagBodyContent($tagBody){
		$doc = JFactory::getDocument();
		// $countLP: count position in goup left
		// $countRP: count position in goup right
		// $countMP: count position in goup main
		// $countL: count position in group left - Enable
		// $countR: count position in group right - Enable
		// $countM: count position in group main - Enable
		// $tagBody['class_content']: class for div#content
		// $arr_group_nonespe array group is not:left, main, right layout-'.$this->layouttype
		//$countL = $countR = $countM = 0;
		$countLP = $countRP = $countMP = 0;
		$widthTotalEnableL = $widthTotalEnableR =0;
		$tagBody['class_content'] = ' ';
		$tagBody['yt_col1-contain'] = '';
		$arr_group_nonespe = array();
		
		foreach($tagBody['positions'] as $posi):
			if($posi['group']=='left'){ $countLP++;	if(!isset($countL)) $countL = 0;								
				if($doc->countModules($posi['value'])){ // case: position type=modules, module
					$countL++;
				}elseif($posi['value']){
					$tagBody['class_content'] .=' no-'.$posi['value'];
				}
			}elseif($posi['group']=='right'){ $countRP++; if(!isset($countR)) $countR = 0;
				if($doc->countModules($posi['value'])){ // case: position type=modules, module
					$countR++;
				}elseif($posi['value']){
					$tagBody['class_content'] .=' no-'.$posi['value'];
				} 	
			}elseif($posi['group']=='main'){ $countMP++; if(!isset($countM)) $countM = 0;
				if($doc->countModules($posi['value'])){ // case: position type=modules, module
					$countM++;
				}elseif($posi['value'] && $posi['type']!='component'){
					$tagBody['class_content'] .=' ';
				}
			}else{ // Group other in block content
				if( !isset($arr_group_nonespe[$posi['group']]) ){
					$arr_group_nonespe[$posi['group']] = 0;
				}
				$arr_group_nonespe[$posi['group']] ++;
			}
		endforeach;
		if(!empty($arr_group_nonespe)){
			foreach($arr_group_nonespe as $k => $v):
				$tagBody['count-'.$k]=$v;
			endforeach;
		}
		
		$tagBody['count-group-left'] = isset($countLP)?$countLP:0; 
		$tagBody['count-group-right'] = isset($countRP)?$countRP:0;
		$tagBody['count-group-main'] = $countMP;
		if( isset($countL) && $countL==0 && isset($countR) && $countR==0 ){
			
			$this->mainprefix = 'noleftright-';
			$this->groupInfo($this->mainprefix);
			if(isset($this->arr_GI['left']['class'])) $this->arr_GI['left']['class'] .=' hidden';
			if(isset($this->arr_GI['right']['class'])) $this->arr_GI['right']['class'] .=' hidden';
			
		}elseif( isset($countL) && $countL==0 ){
			
			$this->mainprefix = 'noleft-';
			$this->groupInfo($this->mainprefix);
			if(isset($this->arr_GI['left']['class'])) $this->arr_GI['left']['class'] .=' hidden';			
		}elseif( isset($countR) && $countR==0 ){
			
			$this->mainprefix = 'noright-';
			$this->groupInfo($this->mainprefix);
			if(isset($this->arr_GI['right']['class'])) $this->arr_GI['right']['class'] .=' hidden';
		}else{
			$this->mainprefix = '';
			$this->groupInfo($this->mainprefix);
		}
		//echo $countL.' vs '.$countLP.' vs '.$this->mainprefix; die();
		$tagBody['mainprefix'] = $this->mainprefix;
		return $tagBody;
	}
	function updateClass($aclass, $bclass){
		$newclass = '';
		
		return '';
	}
	function sortArr($a = array(), $key){
		$tmp = array();
		foreach($a as &$ma) $tmp[] = &$ma[$key];
		array_multisort($tmp, $a); 
		return $a;
	}
	
	function ytSubStr($str, $key){
		return (int)substr($str, 0, strpos($str, $key));
	}

}
}
?>