<?php
/**
 * virtuemart table class, with some additional behaviours.
 *
 *
 * @package    VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

/**
 *
 * Class to provide js API of vm
 * @author Max Milbers
 */
class vmJsApi{

	private static $_jsAdd = array();
	private static $_be = null;

	private function __construct() {

	}

	private static function isAdmin(){

		if(!isset(self::$_be)){
			self::$_be = JFactory::getApplication()->isAdmin();
		}
		return self::$_be;
	}

	public static function safe_json_encode($value){
		if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
			$encoded = json_encode($value, JSON_PRETTY_PRINT);
		} else {
			$encoded = json_encode($value);
		}
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = utf8ize($value);
				return safe_json_encode($clean);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()

		}
	}

	function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = utf8ize($value);
			}
		} else if (is_string ($mixed)) {
			if (function_exists ('mb_convert_encoding')) {
				return mb_convert_encoding($mixed, "UTF-8", "auto");
			} else {
				return utf8_encode($mixed);
			}
		}
		return $mixed;
	}

	/**
	 *
	 * @param $name
	 * @param bool $script
	 * @param bool $min
	 * @param bool $defer	http://peter.sh/experiments/asynchronous-and-deferred-javascript-execution-explained/
	 * @param bool $async
	 */
	public static function addJScript($name, $script = false, $defer = true, $async = false, $inline = false, $ver = VM_REV){
		self::$_jsAdd[$name]['script'] = trim($script);
		self::$_jsAdd[$name]['defer'] = $defer;
		self::$_jsAdd[$name]['async'] = $async;
		if(!isset(self::$_jsAdd[$name]['written']))self::$_jsAdd[$name]['written'] = false;
		self::$_jsAdd[$name]['inline'] = $inline;
		self::$_jsAdd[$name]['ver'] = $ver;
	}

	public static function getJScripts(){
		return self::$_jsAdd;
	}

	public static function removeJScript($name){
		unset(self::$_jsAdd[$name]);
	}

	public static function writeJS(){

		$html = '';
		foreach(self::$_jsAdd as $name => &$jsToAdd){

			if($jsToAdd['written']) continue;
			if(!$jsToAdd['script'] or strpos($jsToAdd['script'],'/')===0 and strpos($jsToAdd['script'],'//<![CDATA[')!==0){ //strpos($script,'/')===0){

				if(!$jsToAdd['script']){
					$file = $name;
				} else {
					$file = $jsToAdd['script'];
				}

				if(strpos($file,'/')!==0){
					$file = vmJsApi::setPath($file,false,'');
				} else if(strpos($file,'//')!==0){
					$file = JURI::root(true).$file;
				}

				if(empty($file)){
					vmdebug('writeJS javascript with empty file',$name,$jsToAdd);
					continue;
				}
				$ver = '';
				if(!empty($jsToAdd['ver'])) $ver = '?vmver='.$jsToAdd['ver'];

				if($jsToAdd['inline']){
					$html .= '<script type="text/javascript" src="'.$file .$ver.'"></script>';
					/*$content = file_get_contents(VMPATH_ROOT.$file);
					$html .= '<script type="text/javascript" >'.$content.'</script>';*/
				} else {
					$document = JFactory::getDocument();
					$document->addScript( $file .$ver,"text/javascript",$jsToAdd['defer'],$jsToAdd['async'] );
				}

			} else {

				$script = trim($jsToAdd['script']);
				if(!empty($script)) {
					$script = trim($script,chr(13));
					$script = trim($script,chr(10));
					if(strpos($script,'//<![CDATA[')===false){
						$html .= '<script id="'.$name.'_js" type="text/javascript">//<![CDATA[ '.chr(10).$script.' //]]>'.chr(10).'</script>';
					} else {
						$html .= '<script id="'.$name.'_js" type="text/javascript"> '.$script.' </script>';
					}
				}

			}
			$html .= chr(13);
			$jsToAdd['written'] = true;
		}
		return $html;
	}

	/**
	 * Write a <script></script> element
	 * @deprecated
	 * @param   string   path to file
	 * @param   string   library name
	 * @param   string   library version
	 * @param   boolean  load minified version
	 * @return  nothing
	 */
	public static function js($namespace,$path=FALSE,$version='', $minified = false) {
		self::addJScript($namespace,false,false);
	}

	/**
	 * Write a <link ></link > element
	 * @param   string   path to file
	 * @param   string   library name
	 * @param   string   library version
	 * @param   boolean   library version
	 * @return  nothing
	 */

	public static function css($namespace,$path = FALSE ,$version='', $minified = NULL)
	{

		static $loaded = array();

		// Only load once
		// using of namespace assume same css have same namespace
		// loading 2 time css with this method simply return and do not load it the second time
		if (!empty($loaded[$namespace])) {
			return;
		}

		$file = vmJsApi::setPath( $namespace, $path, $version='', $minified, 'css');

		$document = JFactory::getDocument();
		$document->addStyleSheet($file.'?vmver='.VM_REV);
		$loaded[$namespace] = TRUE;

	}

	public static function loadBECSS (){

		$url = 'administrator/templates/system/css';
		self::css('system',$url);

		if(!class_exists('VmTemplate')) require(VMPATH_SITE.DS.'helpers'.DS.'vmtemplate.php');
		$template = VmTemplate::getDefaultTemplate(1);
		$url = 'administrator/templates/'.$template['template'].'/css';
		self::css('template',$url);

	}

	/**
	 * Set file path(look in template if relative path)
	 * @author Patrick
	 */
	public static function setPath( $namespace ,$path = FALSE ,$version='' ,$minified = NULL , $ext = 'js', $absolute_path=false)
	{

		$version = $version ? '.'.$version : '';
		$filemin = $namespace.$version.'.min.'.$ext ;
		$file 	 = $namespace.$version.'.'.$ext ;
		$file_exit_path='';
		if(!class_exists('VmTemplate')) require(VMPATH_SITE.DS.'helpers'.DS.'vmtemplate.php');
		$vmStyle = VmTemplate::loadVmTemplateStyle();
		$template = $vmStyle['template'];
		if ($path === FALSE) {

			$uri = VMPATH_THEMES .'/'. $template.'/'.$ext ;
			$path= 'templates/'. $template .'/'.$ext ;
		}

		if (strpos($path, 'templates/'. $template ) !== FALSE){
			// Search in template or fallback
			if (!file_exists($uri.'/'. $file)) {
				$assets_path = VmConfig::get('assets_general_path','components/com_virtuemart/assets/') ;
				$path = str_replace('templates/'. $template.'/',$assets_path, $path);
			}
			$file_exit_path = VMPATH_BASE .'/'.$path;
			if ($absolute_path) {
				$path = VMPATH_BASE .'/'.$path;
			} else {
				$path = JURI::root(TRUE) .'/'.$path;
			}

		}
		elseif (strpos($path, '//') === FALSE)
		{
			if ($absolute_path) {
				$path = VMPATH_BASE .'/'.$path;
			} else {
				$path = JURI::root(TRUE) .'/'.$path;
			}
		}

		//if (VmConfig::get('minified', false) and strpos($path, '//') === false and file_exists($file_exit_path.'/'. $filemin)) $file=$filemin;

		return $path.'/'.$file ;
	}
	/**
	 * Adds jQuery if needed
	 */
	static function jQuery($isSite=-1) {

		if(JVM_VERSION<3){
			//Very important convention with other 3rd pary developers, must be kept. DOES NOT WORK IN J3
			if (JFactory::getApplication ()->get ('jquery')) {
				return FALSE;
			} else {

			}
		} else {
			JHtml::_('jquery.framework');
			//return true;
		}

		if($isSite===-1) $isSite = !self::isAdmin();

		if (!VmConfig::get ('jquery', true) and $isSite) {
			vmdebug('Common jQuery is disabled');
			return FALSE;
		}

		if(JVM_VERSION<3){
			if(VmConfig::get('google_jquery',true)){
				self::addJScript('jquery.min','//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',false,false, false, '');
				self::addJScript( 'jquery-migrate.min',false,false,false,'');
			} else {
				self::addJScript( 'jquery.min',false,false,false,'');
				self::addJScript( 'jquery-migrate.min',false,false,false,'');
			}
		}

		self::jQueryUi();

		self::addJScript( 'jquery.noconflict',false,false,true,'');
		//Very important convention with other 3rd pary developers, must be kept DOES NOT WORK IN J3
		if(JVM_VERSION<3){
			JFactory::getApplication()->set('jquery',TRUE);
		}

		return TRUE;
	}

	static function jQueryUi(){

		if(VmConfig::get('google_jquery', false)){
			self::addJScript('jquery-ui.min', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js', false, false, false, '');
		} else {
			self::addJScript('jquery-ui.min', false, false, false,'');
		}
		self::addJScript('jquery.ui.autocomplete.html', false, false, false,'');
	}

	// Virtuemart product and price script
	static function jPrice() {

		if(!VmConfig::get( 'jprice', TRUE ) and !self::isAdmin()) {
			return FALSE;
		}
		static $jPrice;
		// If exist exit
		if($jPrice) {
			return;
		}
		vmJsApi::jQuery();

		VmConfig::loadJLang( 'com_virtuemart', true );

		vmJsApi::jSite();

		$closeimage = JURI::root( TRUE ).'/components/com_virtuemart/assets/images/fancybox/fancy_close.png';

		$jsVars = "";
		$jsVars .= "vmSiteurl = '".JURI::root()."' ;\n";
		$jsVars .= 'vmLang = "&lang='.VmConfig::$vmlangSef.'";'."\n";
		$jsVars .= 'vmLangTag = "'.VmConfig::$vmlangSef.'";'."\n";

		$Get = vRequest::getGet();
		if(!empty($Get['Itemid'])){
			$jsVars .= "Itemid = '&Itemid=".$Get['Itemid']."';\n";
		} else {
			$jsVars .= 'Itemid = "";'."\n";
		}

		if(VmConfig::get('addtocart_popup',1)){
			$jsVars .= "Virtuemart.addtocart_popup = '".VmConfig::get('addtocart_popup',1)."' ; \n";
			if(VmConfig::get('usefancy',1)){
				$jsVars .= "usefancy = true;";
				vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack',false);
				vmJsApi::css('jquery.fancybox-1.3.4');
			} else {//This is just there for the backward compatibility
				$jsVars .= "vmCartText = '". addslashes( vmText::_('COM_VIRTUEMART_CART_PRODUCT_ADDED') )."' ;\n" ;
				$jsVars .= "vmCartError = '". addslashes( vmText::_('COM_VIRTUEMART_MINICART_ERROR_JS') )."' ;\n" ;
				$jsVars .= "loadingImage = '".JURI::root(TRUE) ."/components/com_virtuemart/assets/images/facebox/loading.gif' ;\n" ;
				$jsVars .= "closeImage = '".$closeimage."' ; \n";
				//This is necessary though and should not be removed without rethinking the whole construction

				$jsVars .= "usefancy = false;";
				vmJsApi::addJScript( 'facebox' );
				vmJsApi::css( 'facebox' );
			}
		}

		self::addJScript('jsVars',$jsVars);
		vmJsApi::addJScript( 'vmprices',false,false);

		$onReady = 'jQuery(document).ready(function($) {
	Virtuemart.product(jQuery("form.product"));

	/*$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
			Virtuemart.setproducttype($(this),id);

		}
	});*/
});';
		vmJsApi::addJScript('ready.vmprices',$onReady);
		$jPrice = TRUE;
		return TRUE;
	}

	static function jSite() {
		if (!VmConfig::get ('jsite', TRUE) and !self::isAdmin()) {
			return FALSE;
		}
		self::addJScript('vmsite',false,false);
	}

	static function jDynUpdate() {
		if (!VmConfig::get ('jdynupdate', TRUE) and !self::isAdmin()) {
			return FALSE;
		}

		self::addJScript('dynupdate',false,false);
		self::addJScript('updDynamicListeners',"
jQuery(document).ready(function() { // GALT: Start listening for dynamic content update.
	// If template is aware of dynamic update and provided a variable let's
	// set-up the event listeners.
	if (Virtuemart.container)
		Virtuemart.updateDynamicUpdateListeners();

}); ");
	}

	static function JcountryStateList($stateIds, $prefix='') {
		static $JcountryStateList = array();
		if (isset($JcountryStateList[$prefix]) or !VmConfig::get ('jsite', TRUE)) {
			return;
		}
		VmJsApi::jSite();
		self::addJScript('vm.countryState'.$prefix,'
//<![CDATA[
		jQuery( function($) {
			$("#'.$prefix.'virtuemart_country_id").vm2front("list",{dest : "#'.$prefix.'virtuemart_state_id",ids : "'.$stateIds.'",prefiks : "'.$prefix.'"});
		});
//]]>
		');
		$JcountryStateList[$prefix] = TRUE;
		return;
	}

	/**
	 * Creates popup, fancy or other for TOS
	 */
	static function popup($container,$activator){
		static $jspopup;
		if (!$jspopup) {
			if(VmConfig::get('usefancy',1)){
				vmJsApi::addJScript( 'fancybox/jquery.fancybox-1.3.4.pack',false,false);
				vmJsApi::css('jquery.fancybox-1.3.4');
				$box = "
//<![CDATA[
	jQuery(document).ready(function($) {
		jQuery('div".$container."').hide();
		var con = $('div".$container."').html();
		jQuery('a".$activator."').click(function(event) {
			event.preventDefault();
			jQuery.fancybox ({ div: '".$container."', content: con });
		});
	});

//]]>
";
			} else {
				vmJsApi::addJScript ('facebox',false,false);
				vmJsApi::css ('facebox');
				$box = "
//<![CDATA[
	jQuery(document).ready(function($) {
		jQuery('div".$container."').hide();
		jQuery('a".$activator."').click(function(event) {
			event.preventDefault();
			jQuery.facebox( { div: '".$container."' }, 'my-groovy-style');
		});
	});

//]]>
";
			}

			$document = JFactory::getDocument ();
			self::addJScript('box',$box);
			//$document->addScriptDeclaration ($box);
			$document->addStyleDeclaration ('#facebox .content {display: block !important; height: 480px !important; overflow: auto; width: 560px !important; }');

			$jspopup = true;
		}
		return;
	}

	static function chosenDropDowns(){
		static $chosenDropDowns = false;

		if(!$chosenDropDowns){
			$be = self::isAdmin();
			if(VmConfig::get ('jchosen', 0) or $be){
				vmJsApi::addJScript('chosen.jquery.min',false,false);
				if(!$be) {
					//vmJsApi::jDynUpdate();
					vmJsApi::addJScript('vmprices');
				}
				vmJsApi::css('chosen');

				$selectText = 'COM_VIRTUEMART_DRDOWN_AVA2ALL';
				$vm2string = "editImage: 'edit image',select_all_text: '".vmText::_('COM_VIRTUEMART_DRDOWN_SELALL')."',select_some_options_text: '".vmText::_($selectText)."'" ;
				if($be or vRequest::getInt('manage',false)){
					$selector = 'jQuery("select")';
				} else {
					$selector = 'jQuery(".vm-chzn-select")';
				}

				$script =
	'if (typeof Virtuemart === "undefined")
	var Virtuemart = {};
	Virtuemart.updateChosenDropdownLayout = function() {
		var vm2string = {'.$vm2string.'};
		'.$selector.'.each( function () {
			var swidth = jQuery(this).css("width")+10;
			jQuery(this).chosen({enable_select_all: true,select_all_text : vm2string.select_all_text,select_some_options_text:vm2string.select_some_options_text,disable_search_threshold: 5, width: swidth});
		});
	}
	Virtuemart.updateChosenDropdownLayout();';

				self::addJScript('updateChosen',$script);
			}
			$chosenDropDowns = true;

		}
		return;
	}

	static function JvalideForm($name='#adminForm')
	{
		static $jvalideForm;
		// If exist exit
		if ($jvalideForm === $name) {
			return;
		}
		self::addJScript('vEngine', "
//<![CDATA[
			jQuery(document).ready(function() {
				jQuery('".$name."').validationEngine();
			});
//]]>
"  );
		if ($jvalideForm) {
			return;
		}
		vmJsApi::addJScript( 'jquery.validationEngine');

		$lg = JFactory::getLanguage();
		$lang = substr($lg->getTag(), 0, 2);
		$vlePath = vmJsApi::setPath('languages/jquery.validationEngine-'.$lang, FALSE , '' ,$minified = NULL ,   'js', true);
		if(!file_exists($vlePath) or is_dir($vlePath)){
			$lang = 'en';
		}
		vmJsApi::addJScript( 'languages/jquery.validationEngine-'.$lang );

		vmJsApi::css ( 'validationEngine.template' );
		vmJsApi::css ( 'validationEngine.jquery' );
		$jvalideForm = $name;
	}

	static public function vmValidator ($guest=null){

		if(!isset($guest)){
			$guest = JFactory::getUser()->guest;
		}

		// Implement Joomla's form validation
		JHtml::_ ('behavior.formvalidation');	//j2
		//JHtml::_('behavior.formvalidator');	//j3

		/*vmJsApi::addJScript('/media/system/js/core.js',false,false);
		vmJsApi::addJScript('html5fallback',false,false);
		self::jQuery();
		// Add validate.js language strings
		JText::script('JLIB_FORM_FIELD_INVALID');

		JHtml::_('script', 'system/punycode.js', false, true);
		JHtml::_('script', 'system/validate.js', false, true);*/


		$regfields = array('username', 'name');
		if($guest){
			$regfields[] = 'password';
			$regfields[] = 'password2';
		}

		$jsRegfields = implode("','",$regfields);
		$js = "function myValidator(f, r) {

		var regfields = ['".$jsRegfields."'];

		var requ = '';
		if(r == true){
			requ = 'required';
		}

		for	(i = 0; i < regfields.length; i++) {
			var elem = jQuery('#'+regfields[i]+'_field');
			elem.attr('class', requ);
		}

		if (document.formvalidator.isValid(f)) {
				if (jQuery('#recaptcha_wrapper').is(':hidden') && (r == true)) {
					jQuery('#recaptcha_wrapper').show();
				} else {
					return true;	//sents the form, we dont use js.submit()
				}
			} else {
				//dirty Hack for country dropdown
				var cField = jQuery('#virtuemart_country_id');
				if(typeof cField!=='undefined'){
					if(cField.attr('required')=='required' && cField.attr('aria-required')=='true'){
						chznField = jQuery('#virtuemart_country_id_chzn');
						var there = chznField.attr('class');
						var ind = there.indexOf('required');
						var results = 0;
						if(cField.attr('aria-invalid')=='true' && ind==-1){
							chznField.attr('class', there + ' required');
							results = 2;
						} else if(ind!=-1){
							var res = there.slice(0,ind);
							chznField.attr('class', res);
						}
						chznField = jQuery('#virtuemart_state_id_chzn');
						if(typeof chznField!=='undefined'){
							if(results===0){
								results = chznField.find('.chzn-results li').length;
							}

							there = chznField.attr('class');
							ind = there.indexOf('required');
							var sel = jQuery('#virtuemart_state_id').val();
							if(sel==0 && ind==-1 && results>1){
								chznField.attr('class', there + ' required');
							} else if(ind!=-1 && (results<2 || sel!=0)){
								var res = there.slice(0,ind);
								chznField.attr('class', res);
							}
						}
					}
				}

				if (jQuery('#recaptcha_wrapper').is(':hidden') && (r == true)) {
					jQuery('#recaptcha_wrapper').show();
				}
				var msg = '" .addslashes (vmText::_ ('COM_VIRTUEMART_MISSING_REQUIRED_JS'))."';
			alert(msg + ' ');
		}
		return false;
	}";
		vmJsApi::addJScript('vm.validator',$js);
	}

	// Virtuemart product and price script
	static function jCreditCard()
	{

		static $jCreditCard;
		// If exist exit
		if ($jCreditCard) {
			return;
		}
		VmConfig::loadJLang('com_virtuemart',true);


		$js = "
//<![CDATA[
		var ccErrors = new Array ()
		ccErrors [0] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_UNKNOWN_TYPE') ). "';
		ccErrors [1] =  '" . addslashes( vmText::_("COM_VIRTUEMART_CREDIT_CARD_NO_NUMBER") ). "';
		ccErrors [2] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_INVALID_FORMAT')) . "';
		ccErrors [3] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_INVALID_NUMBER')) . "';
		ccErrors [4] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_WRONG_DIGIT')) . "';
		ccErrors [5] =  '" . addslashes( vmText::_('COM_VIRTUEMART_CREDIT_CARD_INVALID_EXPIRE_DATE')) . "';
//]]>
		";

		self::addJScript('creditcard',$js);

		$jCreditCard = TRUE;
		return TRUE;
	}

	/**
	 * ADD some CSS if needed
	 * Prevent duplicate load of CSS stylesheet
	 * @author Max Milbers
	 */
	static function cssSite() {

		if (!VmConfig::get ('css', TRUE)) return FALSE;

		static $cssSite;
		if ($cssSite) return;

		// Get the Page direction for right to left support
		$document = JFactory::getDocument ();
		$direction = $document->getDirection ();
		$cssFile = 'vmsite-' . $direction ;

		if(!class_exists('VmTemplate')) require(VMPATH_SITE.DS.'helpers'.DS.'vmtemplate.php');
		$vmStyle = VmTemplate::loadVmTemplateStyle();
		$template = $vmStyle['template'];
		if($template){
			//Fallback for old templates
			$path= 'templates'. DS . $template . DS . 'css' .DS. $cssFile.'.css' ;
			if(file_exists($path)){
				// If exist exit
				vmJsApi::css ( $cssFile ) ;
			} else {
				$cssFile = 'vm-' . $direction .'-common';
				vmJsApi::css ( $cssFile ) ;

				$cssFile = 'vm-' . $direction .'-site';
				vmJsApi::css ( $cssFile ) ;

				$cssFile = 'vm-' . $direction .'-reviews';
				vmJsApi::css ( $cssFile ) ;
			}
			$cssSite = TRUE;
		}

		return TRUE;
	}

	// $yearRange format >> 1980:2010
	// Virtuemart Datepicker script
	static function jDate($date='',$name="date",$id=NULL,$resetBt = TRUE, $yearRange='') {

		if ($yearRange) {
			$yearRange = 'yearRange: "' . $yearRange . '",';
		}
		if ($date == "0000-00-00 00:00:00") {
			$date = 0;
		}
		if (empty($id)) {
			$id = str_replace(array('[]','[',']'),'.',$name);
			$id = str_replace('..','.',$id);
		}

		static $jDate;
		if(!class_exists('VmHtml')) require(VMPATH_ADMIN.DS.'helpers'.DS.'html.php');
		$id = VmHtml::ensureUniqueId($id);
		$dateFormat = vmText::_('COM_VIRTUEMART_DATE_FORMAT_INPUT_J16');//="m/d/y"
		$search  = array('m', 'd', 'Y');
		$replace = array('mm', 'dd', 'yy');
		$jsDateFormat = str_replace($search, $replace, $dateFormat);

		if ($date) {
			$formatedDate = JHtml::_('date', $date, $dateFormat );
		}
		else {
			$formatedDate = vmText::_('COM_VIRTUEMART_NEVER');
		}
		$display  = '<input class="datepicker-db" id="'.$id.'" type="hidden" name="'.$name.'" value="'.$date.'" />';
		$display .= '<input id="'.$id.'_text" class="datepicker" type="text" value="'.$formatedDate.'" />';
		if ($resetBt) {
			$display .= '<span class="vmicon vmicon-16-logout icon-nofloat js-date-reset"></span>';
		}

		// If exist exit
		if ($jDate) {
			return $display;
		}

		self::addJScript('datepicker','
//<![CDATA[
			jQuery(document).ready( function($) {
			$(document).on( "focus",".datepicker", function() {
				$( this ).datepicker({
					changeMonth: true,
					changeYear: true,
					'.$yearRange.'
					dateFormat:"'.$jsDateFormat.'",
					altField: $(this).prev(),
					altFormat: "yy-mm-dd"
				});
			});
			jQuery(document).on( "click",".js-date-reset", function() {
				$(this).prev("input").val("'.vmText::_('COM_VIRTUEMART_NEVER').'").prev("input").val("0");
			});
		});
//]]>
		');


		vmJsApi::css('ui/jquery.ui.all');
		$lg = JFactory::getLanguage();
		$lang = $lg->getTag();

		$vlePath = vmJsApi::setPath('i18n/jquery.ui.datepicker-'.$lang, FALSE , '' ,$minified = NULL ,   'js', true);
		if(!file_exists($vlePath) or is_dir($vlePath)){
			$lang = 'en-GB';
		}
		vmJsApi::addJScript( 'i18n/jquery.ui.datepicker-'.$lang );

		$jDate = TRUE;
		return $display;
	}


	/*
	 * Convert formated date;
	 * @ $date the date to convert
	 * @ $format Joomla DATE_FORMAT Key endding eg. 'LC2' for DATE_FORMAT_LC2
	 * @ revert date format for database- TODO ?
	 */

	static function date($date , $format ='LC2', $joomla=FALSE ,$revert=FALSE ){

		if (!strcmp ($date, '0000-00-00 00:00:00')) {
			return vmText::_ ('COM_VIRTUEMART_NEVER');
		}
		If ($joomla) {
			$formatedDate = JHtml::_('date', $date, vmText::_('DATE_FORMAT_'.$format));
		} else {

			$J16 = "_J16";

			$formatedDate = JHtml::_('date', $date, vmText::_('COM_VIRTUEMART_DATE_FORMAT_'.$format.$J16));
		}
		return $formatedDate;
	}

	static function keepAlive($minlps = 2, $maxlps=5){

		static $done = false;
		if($done) return;
		$done = true;

		$config = JFactory::getConfig();
		$refTime = ($config->get('lifetime') );

		// the longest refresh period is 30 min to prevent integer overflow.
		if ($refTime > 30 || $refTime <= 0) {
			$refTime = 30;
		}

		$url = 'index.php?option=com_virtuemart&view=virtuemart&task=keepalive';
		vmJsApi::addJScript('keepAliveTime','var sessMin = '.$refTime.';var vmAliveUrl = "'.$url.'";var maxlps = "'.$maxlps.'";var minlps = "'.$minlps.'";',false,true);
		vmJsApi::addJScript('vmkeepalive',false, true, true);
	}
}
