<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2012 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

// Class YtTemplate
if (!class_exists('YtFrameworkTemplate')){
abstract class YtFrameworkTemplate {
	var $_tpl = null;
	var $template = '';
	var $layout = '';
	var $browser = '';
	var $override = false;
	var $_params_cookie = array();
	
	function YtFrameworkTemplate ($template =null, $cookie) {
		$this->_tpl = $template;
		$this->template = $template->template;
		if(!defined('YT_TEMPLATENAME')){
			define('YT_TEMPLATENAME', $this->template);
		}
		$this->override = $this->isOverrideTemplate(); 
		$_params_cookie = $cookie;
		foreach ($_params_cookie as $k) {
			$this->_params_cookie[$k] = $this->_tpl->params->get($k);
		}
		$this->getUserSetting();
		
		
		if(is_file(JPATH_ROOT.'/templates/'.$this->template.'/less/color/'. $this->getParam('themecolor').'.less')){
			$colorcontent1 = JFile::read('templates/'.$this->template.'/less/color/variables_color.less');
			$colorcontent2 = JFile::read('templates/'.$this->template.'/less/color/'. $this->getParam('themecolor').'.less');
			
			if($colorcontent1 != $colorcontent2){
				JFile::write(JPATH_ROOT.'/templates/'.$this->template.'/less/color/variables_color.less', $colorcontent2);
			}
		}
	}
	
	// Get setting of user
	/* Save setting of user on frontend to cookie*/
	function getUserSetting(){
		$exp = time() + 60*60*24*355;
		if (isset($_COOKIE[$this->template.'_tpl']) && $_COOKIE[$this->template.'_tpl'] == $this->template){
			foreach($this->_params_cookie as $k=>$v) {
				$kc = $this->template.'_'.$k;
				if (JRequest::getVar($k)!= null){
					$v = JRequest::getVar($k);
					@setcookie ($kc, $v, $exp, '/');
				}else{
					if (isset($_COOKIE[$kc])){
						$v = $_COOKIE[$kc];
					}
				}
				$this->setParam($k, $v);
			}
		}else{
			@setcookie ($this->template.'_tpl', $this->template, $exp, '/');
		}
		return $this;
	}
	
	// Get param template
	function getParam ($param, $default='') {
		if (isset($this->_params_cookie[$param]) && $this->override==false) {
			return $this->_params_cookie[$param];
		}
		return $this->_tpl->params->get($param, $default);
	}
	
	// Set param template
	function setParam ($param, $value) {
		$this->_params_cookie[$param] = $value;
	}
	
	// Set cookie for param
	function set_cookie($name, $value = "") {
		$expires = time() + 60*60*24*365;
		setcookie($name, $value, $expires,"/","");
	}
	
	// Get cookie of param
	function get_cookie($item) {
		if (isset($_COOKIE[$item]))
			return urldecode($_COOKIE[$item]);
		else
			return false;
	}
	
	/**
	* Set Themes Color
	* 
	*/
	public static function Preset() {
		$name = self::getInstance()->theme() . '_themecolor';
		self::getInstance()->resetCookie($name);

		$require = JRequest::getVar('themecolor',  ''  , 'get');
		if( !empty( $require ) ){
			setcookie( $name, $require, time() + 3600, '/');
			$current = $require;
		} 
		elseif( empty( $require ) and  isset( $_COOKIE[$name] )) {
			$current = $_COOKIE[$name];
		} else {
			$current = self::getInstance()->Param('themecolor');
		}

		return $current;
	}
	
	public static function PresetParam($name) {
		return self::getInstance()->param( self::getInstance()->Preset().$name );
	}
	
	/**
	* Set Layout Type
	* 
	*/
	public static function Typelayout() {
		$name = self::getInstance()->theme() . '_typelayout';
		self::getInstance()->resetCookie($name);

		$require = JRequest::getVar('typelayout',  ''  , 'get');
		if( !empty( $require ) ){
			setcookie( $name, $require, time() + 3600, '/');
			$current = $require;
		} 
		elseif( empty( $require ) and  isset( $_COOKIE[$name] )) {
			$current = $_COOKIE[$name];
		} else {
			$current = self::getInstance()->Param('typelayout');
		}

		return $current;
	}
	
	public static function TypelayoutParam($name) {
		return self::getInstance()->param( self::getInstance()->Typelayout().$name );
	}
	
	
	// Return url of site
	function baseurl(){
		 return JURI::base();
	}
	
	// Return url of template
	function templateurl() {
		return JURI::base()."templates/".$this->template.'/';
	}
	
	// Check version template
	function templateVersion(){
		$t_filePath = JURI::base()."templates/".$this->template.'/templateDetails.xml';
		$t_xml = JInstaller::parseXMLInstallFile($t_filePath);
		return $t_xml['version'];
	}
	
	// Check page is home page or not
	function isHomePage(){
		$db  = JFactory::getDBO();
		$db->setQuery("SELECT home FROM #__menu WHERE id=" . intval(JRequest::getCmd( 'Itemid' )));
		$db->query();
		return  $db->loadResult();
	}
	
	function isOverrideTemplate(){
		if(JRequest::getVar('tmpl')!='component'){ 
			$menuid = '';
			if(is_object(JFactory::getApplication()->getMenu()->getActive())) $menuid = JFactory::getApplication()->getMenu()->getActive()->id;
			$db = JFactory::getDBO();
			if($menuid!=''){
				$query = "SELECT template_style_id FROM #__menu WHERE id = ".$menuid;
				$db->setQuery($query);
				$templateIdActive = $db->loadResult();

				$query = "SELECT id FROM #__template_styles WHERE home = 1 AND client_id = 0";
				$db->setQuery($query);
				$templateIdDefault = $db->loadResult(); //echo $templateIdDefault.' vs '.$templateIdActive; //die();

				if($templateIdActive > 0){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}
	
	function ytStyleSheet($url){
		$doc = JFactory::getDocument();
		if(!file_exists($url) && (strpos($url, 'http:')== false || strpos($url, 'https:')== false))
			$url = 'templates/'.$this->template.'/'.$url;
		$lessurl = str_replace('.css', '.less', str_replace('/css/', '/less/', $url));
		if(($this->getParam('developing', 0)==1 || JRequest::getVar('less2css')=='all') && file_exists($lessurl)){
			YTLess::addStyleSheet($lessurl);
		}elseif(file_exists($url)){
			$doc->addStyleSheet($url);
		}elseif(basename($url)=='template.css'){
			$doc->addStyleSheet(str_replace('template.css', 'template-'.$this->getParam('themecolor').'.css', $url));
		}else{
			die($url.' not exists');
		}
	}
	
	// Render logo
	function getLogo(){
		global $overimg;
		
		$app = JFactory::getApplication();
		$logoWidth  = 'width:'.$this->getParam('logoWidth').'px;';
		$logoHeight = 'height:'.$this->getParam('logoHeight').'px;';
		
		
		if ($this->getParam('logoType')=='image'):  
			if($this->getParam('overrideLogoImage')!=''):
				$url = $this->baseurl().$this->getParam('overrideLogoImage');
			else:
				
				if(!empty($overimg)){
					
					if(is_file('templates/'.$this->template.'/images/styling/'.$this->getParam('themecolor').'/'.$overimg.'.png')){
						
						$url = $this->templateurl().'images/styling/'.$this->getParam('themecolor').'/'.$overimg.'.png';
					}else{
						$url = $this->templateurl().'images/styling/'.$this->getParam('themecolor').'/logo.png';
					}
				}else{
					if(is_file('templates/'.$this->template.'/images/styling/'.$this->getParam('themecolor').'/logo.png')){
						$url = $this->templateurl().'images/styling/'.$this->getParam('themecolor').'/logo.png';
					}else{
						$url = $this->templateurl().'images/logo.png';
					}
				}
				
				
			endif;
		?>
			
			<a class="logo" href="" title="<?php echo $app->getCfg('sitename'); ?> ">
				<img data-placeholder="no" src="<?php echo $url; ?>" alt="<?php echo $app->getCfg('sitename'); ?>" style="<?php echo $logoWidth. $logoHeight  ?>"  />
			</a>
           
        <?php
		else:
            $logoText = (trim($this->getParam('logoText'))=='') ? $app->getCfg('sitename') : $this->getParam('logoText');
            $sloganText = (trim($this->getParam('sloganText'))=='') ? JText::_('SITE SLOGAN') : $this->getParam('sloganText');	?>
			<div class="logo-text" style="<?php echo $logoWidth. $logoHeight  ?>">
				<a class="site-text" href="index.php" title="<?php echo $app->getCfg('sitename'); ?>"><?php echo $logoText; ?></a>
				<p class="site-slogan"><?php echo $sloganText;?></p>
			</div>
        <?php 
		endif;
	}
	
	// Render menu
	function getMenu(){
		$menubase = J_TEMPLATEDIR.J_SEPARATOR.'menusys';
		include_once $menubase .J_SEPARATOR.'ytloader.php';
		if(isset($_COOKIE[$this->template.'_direction'])){
			$direction = $_COOKIE[$this->template.'_direction'];
		}else{
			$direction = $this->getParam('direction');
		}
		
		$templateMenuBase = new YTMenuBase(
		array(
			'menutype'	=> $this->getParam('menutype'),
			'menustyle'	=> $this->getParam('menustyle'),
			'moofxduration'	=> $this->getParam('moofxduration'),
			'moofx'		=> $this->getParam('moofx'),
			'jsdropline'=> $this->getParam('jsdropline', 0),
			'activeslider'=> $this->getParam('activeslider', 0),
			'startlevel'=> $this->getParam('startlevel',0),
			'endlevel'	=> $this->getParam('endlevel',9),
			'direction'	=> $direction,
			'basepath'	=> str_replace('\\', '/', $menubase)
		));
		$templateMenuBase->getMenu()->getContent();	
		
	}
	
	// render possition has attribute group in positions of block
	function renPositionsGroup($position, $type='', $special = null){
		$elStyle   = '';
		$class     = '';
		$more_attr = '';
		$doc       = JFactory::getDocument();
		// Element style
		if($position['group']=='main'){
			$prefx = $special['mainprefix'];
		}else{
			$prefx = '';
		}
		
		// Element class
		$_countpos = $this->_countPosGroup($this->_tagPG, $position['value']);
		if($_countpos == 1) {
			$class .= ' class="col-sm-12"';
		}
		elseif(isset($position[$prefx.'class']) && $position[$prefx.'class']!=''){
			$class .= ' class="'.$position[$prefx.'class'].'"';
		}
		
		// Element attribute
		if ( $position['type'] == 'modules' ) { 
			$has_suffix = false;
			if ( isset($position['group']) && $position['group'] == 'main' && $this->getParam('layoutsuffix') != '' )
				if ( $doc->countModules($position['value'] . '-' . $this->getParam('layoutsuffix')) )
					$has_suffix = true;
			if ( $has_suffix ) {
				$this->renModulePos($position, $elStyle, $class, $position['value'] . '-' . $this->getParam('layoutsuffix'));
			} else {
				if ( $doc->countModules($position['value']) )
					$this->renModulePos($position, $elStyle, $class, '');
			}	
		} elseif ($position['type'] == 'component' && $type=='main'){ 
			if ( $this->getParam('hideComponentHomePage')=='1'
				&& $this->isHomePage() ){
			?>
            	<p style="display:none">Hide Main content block</p>
            <?php
			} else {
				$this->renComponent($elStyle, $class);
			}
		} elseif ( $position['type'] == 'html' ) { 
			$this->renHTMLPos($position, $elStyle, $class);
		} elseif ( $position['type'] == 'feature' ) {
			$this->renFeaturePos($position, $elStyle, $class);
		} elseif ( $position['type']=='message' ) { ?>
			<jdoc:include type="message" />
        <?php
		}
	}
	
	function _countPosGroup($tagBD, $povalue = null) {
		$doc = JFactory::getDocument();
		$this->_tagPG = $tagBD;
		$countposgroup = 0;		
		if(!empty($tagBD) &&  $povalue != null) {
			$_value0 = $povalue;
			$_value = preg_replace('/\d+/', '', $_value0 );
			foreach($tagBD as $position):
				if($position['group'] == $_value &&  $doc->countModules($position['value']) ){
					 $countposgroup++;
				}
				
			endforeach;
		}
		return $countposgroup;
	}
	
	public function get_next($array, $key) {
	   $currentKey = key($array);
	   while ($currentKey !== null && $currentKey != $key) {
		   next($array);
		   $currentKey = key($array);
	   }
	   return next($array);
	}
	
	// render possition no attribute group in positions of block nomarl
	function renPositionsNormal($positions, $countModules, $countTag){
		$doc = JFactory::getDocument();
		$k = 0;
		$_total = 0;
		
		foreach( $positions as $position ){
			$elStyle   = '';
			$class     = '';
			
			// set the grid class eg: col-sm-6
			if($position['class']!=''){
				if($countModules < $countTag){
					if ($doc->countModules($position['value']) != 0 || $position['type'] == 'feature' ) {
						$k++;
						if($k < $countModules ) {
							$_total += substr($position['class'], 7 , strlen($position['class']) - 1);
						}
						if($k == $countModules) {
							$cls_end = 12 - $_total;
							$class .= 'class="col-sm-'.$cls_end.'"';
						}else{
							$class .= 'class="'.$position['class'].'"';
						}
						
					}
					
				}else{
					$class .= 'class="'.$position['class'].'"';
				}
			}
			
			
			if($position['type'] == 'modules'){
				if( $doc->countModules($position['value']) )
					$this->renModulePos($position, $elStyle, $class);
			}elseif($position['type'] == 'html'){
				$this->renHTMLPos($position, $elStyle, $class);
			}elseif($position['type'] == 'feature'){
				
				$this->renFeaturePos($position, $position['overlogo'], $class);
			}elseif($position['type']=='message'){ ?>
            	<div <?php echo $class; ?> >
                	<jdoc:include type="message" />
                </div>
            <?php
            }
		}
		
	}
	
	// render possition no attribute group in positions of block content
	function renPositionsContentNoGroup($position){
		$doc       = JFactory::getDocument();
		$elStyle   = '';
		$class     = '';
		
		$_total = '';
		$k = 0 ; 
		
		// set the grid class eg: col-sm-6
		if($position['class']!=''){
			if($countModules < $countTag){
				if ($doc->countModules($position['value']) != 0 || $position['type'] == 'feature' ) {
					$k++;
					if($k < $countModules ) {
						$_total += substr($position['class'], 7 , strlen($position['class']) - 1);
					}
					if($k == $countModules) {
						$cls_end = 12 - $_total;
						$class .= 'class="col-sm-'.$cls_end.'"';
					}else{
						$class .= 'class="'.$position['class'].'"';
					}
					
				}
				
			}else{
				$class .= 'class="'.$position['class'].'"';
			}
		}
			
		
		if($position['type'] == 'modules'){
			if( $doc->countModules($position['value']) ){
				$this->renModulePos($position, $elStyle, $class);
			}
		}elseif($position['type'] == 'html'){
			$this->renHTMLPos($position, $elStyle, $class);
		}elseif($position['type'] == 'feature'){
			$this->renFeaturePos($position, $elStyle, $class);
		}elseif($position['type']=='message'){ ?>
        	<div<?php echo $class; ?>>
				<jdoc:include type="message" />
            </div>
		<?php
		}
	}
	
	//render position with type: modules
	function renModulePos ($position, $elementstyle, $elementclass='', $more_attr='', $positionnamesuffix='', $yorn='1' ) {
		if($yorn == '1'){
		?>
		<div id="<?php echo $position['value']; ?>" <?php echo $elementstyle; ?> <?php echo $elementclass; ?> <?php echo $more_attr;?>>
			<jdoc:include type="modules" name="<?php echo ($positionnamesuffix=='')?$position['value']:$positionnamesuffix; ?>" style="<?php echo $position['style'];?>" />
		</div>
		<?php
		}else{ ?>
			<jdoc:include type="modules" name="<?php echo ($positionnamesuffix=='')?$position['value']:$positionnamesuffix; ?>" style="<?php echo $position['style'];?>" />
        <?php    
		}
	}
	//render position with type: html
	function renHTMLPos ($position, $elementstyle, $elementclass='' ) {
		?>
		<div <?php echo $elementclass; ?><?php echo $elementstyle; ?> >
			<?php echo $position['value']; ?>
        </div>
		<?php
	}
	
	//render position with type: feature
	function renFeaturePos ($position, $elementstyle, $elementclass='' ) {
		global $overimg;
		 $overimg = $elementstyle;
		
		?>
		<div id="<?php echo "yt_".strtolower(substr($position['value'], 1));?>" <?php echo $elementclass; ?>  >
			<?php
            if($position['value'] == '@logo'){
                echo $this->getLogo();
            }elseif($position['value'] == '@fontsize'){
                echo $this->getControlFontSize();
            }elseif($position['value'] == '@mainmenu'){
                 $this->getMenu();
            }elseif($position['value'] == '@linkFooter'){
                $this->getLinkFooter();
            }elseif($position['value'] == '@copyright'){
                $this->getCopyright();
            }
            ?>
        </div>
		<?php
	}
	
	//render position with type: component
	function renComponent ($elementstyle, $elementclass='', $more_attr='') {
		?>
        <div id="yt_component"<?php echo $elementclass; ?><?php echo $elementstyle; ?><?php echo $more_attr; ?>>
             <jdoc:include type="component" />
        </div>
		<?php 
	}
	
	
	// Check version of IE
	static function ieversion() {
		preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
		if(count($matches)<2){
		  preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
		}
		if (count($matches)>1){
		  //Then we're using IE
			  if(!isset($matches[1])) {
				return -1;
			} else {
				return floatval($matches[1]);
			}
		}
		
	}
	
}
}
?>