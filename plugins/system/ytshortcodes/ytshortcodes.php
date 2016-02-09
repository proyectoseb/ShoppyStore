<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/


defined('_JEXEC') or die('Restricted access');

define('YT_SITE_URL', JUri::root());
define('YT_SC_ROOT', dirname(__FILE__));
define('YT_SC_URI', YT_SITE_URL.'plugins/system/ytshortcodes');
define('YT_SC_IMG', YT_SC_URI.'/assets/images/');
// Import Joomla core library
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.plugin.plugin');
jimport('joomla.version');

// include shortcode function
	require_once dirname(__FILE__) . "/includes/shortcodes-func.php";
// include shortcode prepa
	require_once dirname(__FILE__) . "/includes/shortcodes-prepa.php";
// include google map
	require_once dirname(__FILE__) . "/includes/libs/googlemap/googleMaps.lib.php";
// Include data
	require_once dirname(__FILE__) . '/core/data.php';

class plgSystemYtshortcodes extends JPlugin{
	var $document = NULL;
	var $baseurl  = NULL;

	public function __construct(&$subject, $config){
		parent::__construct($subject, $config);
	}
	// Function on after render
	public function onAfterRender(){
		// Add shortcodes button into editor(frontend & backend)
		$this->addBtnShortCodes();
	}

	// Enable shortcodes in Articles content
	public function onContentPrepare($context, &$article, &$params, $page=0){

		$param = new stdClass;
        $param->api_key = $this->params->get('google_map_api_key');
        $param->width   = $this->params->get('google_map_width', '400');
        $param->height  = $this->params->get('google_map_height', '400');
        $param->zoom    = $this->params->get('google_map_zoom', '15');
        $is_mod = 1;
        $plugin = new Plugin_googleMaps($article, $param, $is_mod);
		$article->text = parse_shortcode($article->text);
		return true;
	}

	public function onBeforeCompileHead(){
		//get language and direction
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$document = JFactory::getDocument();
		$this->direction = $document->direction;
		$this->baseurl = str_replace("/administrator", "", JURI::base());
		
		if( $app->isSite()){
			// include Bootstrap
			if($this->params->get('show_sjbootstrap', 0)=='1'){
				$this->ytStyleSheet('plugins/system/ytshortcodes/assets/css/bootstrap/bootstrap.css');	
			}
			$this->ytStyleSheet("plugins/system/ytshortcodes/assets/css/shortcodes.css");
			if($this->params->get('show_sjfont-awesome', 0)=='1'){
				$document->addStyleSheet($this->baseurl."plugins/system/ytshortcodes/assets/css/font-awesome.min.css");	
			}
			
		}
		$valRequest = JRequest::get();
		
		$user = JFactory::getUser();		
		if(( $app->isSite() && isset($valRequest['view']) && $valRequest['view'] == 'item' && isset($valRequest['task']) && $valRequest['task'] == 'edit') || ($app->isSite() && isset($valRequest['view']) && $valRequest['view'] == 'form' && isset($valRequest['layout']) && $valRequest['layout'] == 'edit') || $app->isAdmin() || $valRequest['option'] == 'com_config'){
			if(!defined('FONT_AWESOME')){
				$document->addStyleSheet($this->baseurl."plugins/system/ytshortcodes/assets/css/font-awesome.min.css");
				define('FONT_AWESOME', 1);
			}
			$document->addStyleSheet($this->baseurl."plugins/system/ytshortcodes/assets/css/shortcodes-backend.css");
			//$document->addStyleSheet($this->baseurl . 'plugins/system/ytshortcodes/assets/css/loadconfig/farbtastic.css');
			//$document->addStyleSheet($this->baseurl . 'plugins/system/ytshortcodes/assets/css/loadconfig/simpleslider.css');
			$document->addStyleSheet($this->baseurl . 'plugins/system/ytshortcodes/assets/css/loadconfig/pluginShortcode.css');
			//$document->addScript($this->baseurl . 'plugins/system/ytshortcodes/assets/js/loadconfig/farbtastic.js');
			//$document->addScript($this->baseurl . 'plugins/system/ytshortcodes/assets/js/loadconfig/simpleslider.js');
			$document->addScript($this->baseurl . 'plugins/system/ytshortcodes/assets/js/loadconfig/shortcodes-backend.js');
			$document->addScriptDeclaration('
			function jModalClose() {
				SqueezeBox.close();
			}

			    function appendHtml(targetC, htmldata) {
			    var theDiv = document.getElementById(targetC);
			    theDiv.innerHTML = theDiv.innerHTML + htmldata;
			}
			
		    function jInsertFieldValue(value,id) 
		    {
		    	if(id == "yt-generator-attr-source")
		    	{
		     		var old_id = document.getElementById(id).value;
		            if (old_id != "none") 
		            {
		                document.getElementById(id).value = document.getElementById(id).value + ","  + value;
		            }else
		            {
		            	var theDiv = document.getElementById("yt-generator-attr-image").innerHTML ="";
		            	document.getElementById(id).value = "media: "  + value;
		            }
		            value1 =  \'<span data-id="\' + value + \'" title="\' + this.title + \'"><img src="'.JUri::root().'\' + value + \'" alt="" /><i class="fa fa-times"></i></span>\';
		             appendHtml( "yt-generator-attr-image",value1);
			    	jQuery("#"+id).trigger(\'keyup\');
		           
		        }
		        else
		        {
		            var old_id = document.getElementById(id).value;
		            if (old_id != id) 
		            {
		                document.getElementById(id).value = value;
		            }
			    		jQuery("#"+id).trigger(\'keyup\');
		        }
		    }
		');
			
		}
		if ($this->direction == 'rtl'){
			if($app->isSite()){
				$this->ytStyleSheet("plugins/system/ytshortcodes/assets/css/shortcodes-rtl.css");
			}
		}
		// include Jquery Joomla25
		$version = new JVersion();
		if($this->params->get('show_sjjquery', 0)==1 && $version->RELEASE=='2.5' ){
			$document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/jquery.min.js");
			$document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/jquery-noconflict.js");
		}
		// include Jquery
		if($this->params->get('show_sjbootstrap', 0)==1 && $app->isSite()){
			$document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/bootstrap.min.js");
		}
		if($app->isSite()){
			if($this->params->get('show_sjprettify', 1)==1){
				$document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/prettify.js");
			}
			$document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/shortcodes.js");
		}
	}
	public function ytStyleSheet($url){
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$lessurl = str_replace('.css', '.less', str_replace('/css/', '/less/', $url));
		if(($app->getTemplate(true)->params->get('developing', 0)==1 || JRequest::getVar('less2css')=='all') && file_exists($lessurl)){
			YTLess::addStyleSheet($lessurl);
		}elseif(file_exists($url)){
			$doc->addStyleSheet($url);
		}else{
			die($url.' not exists');
		}
	}
	// Function add shortcodes button into editor
	public function addBtnShortCodes()
	{
		$page   = JResponse::GetBody();
		$button = $this->listShortCodes();
		$stext  = '<script  type="text/javascript">
						function jSelectShortcode(text) {
							jQuery("#yt_shorcodes").removeClass("open");
							text = text.replace(/\'/g, \'"\');
							//1.Editor Content
							if(document.getElementById(\'jform_articletext\') != null) {
								jInsertEditorText(text, \'jform_articletext\');
							}
							if(document.getElementById(\'jform_description\') != null) {
								jInsertEditorText(text, \'jform_description\');
							}

							//2.Editor K2
							if(document.getElementById(\'description\') != null) {
								jInsertEditorText(text, \'description\');
							}
							if(document.getElementById(\'text\') != null) {
								jInsertEditorText(text, \'text\');
							}
							//3.Editor VirtueMart
							if(document.getElementById(\'category_description\') != null) {
								jInsertEditorText(text, \'category_description\');
							}
							if(document.getElementById(\'product_desc\') != null) {
								jInsertEditorText(text, \'product_desc\');
							}
							//4.Editor Contact
							if(document.getElementById(\'jform_misc\') != null) {
								jInsertEditorText(text, \'jform_misc\');
							}
							//5.Editor Easyblog
							if(document.getElementById(\'write_content\') != null) {
								jInsertEditorText(text, \'write_content\');
							}
							//6.Editor Joomshoping
							if(document.getElementById(\'description1\') != null) {
								jInsertEditorText(text, \'description1\');
							}
							//6.Editor HTML
							if(document.getElementById(\'jform_content\') != null) {
								jInsertEditorText(text, \'jform_content\');
							}
							SqueezeBox.close();
						}
				   </script>';
		$page = str_replace('<div id="editor-xtd-buttons">', '<div id="editor-xtd-buttons">' . $button, $page);
		$page = str_replace('<div id="editor-xtd-buttons" class="btn-toolbar pull-left">', '<div id="editor-xtd-buttons" class="btn-toolbar pull-left">' . $button, $page);
		$page = str_replace('</body>', $stext . '</body>', $page);
		JResponse::SetBody($page);
		
	}
	 public function onAfterRoute() {
        $lang_dir = JPATH_SITE.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'ytshortcodes';
        if (file_exists($lang_dir.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.'en-GB'.DIRECTORY_SEPARATOR.'en-GB.plg_system_ytshortcodes.ini')) {
            $lang = JFactory::getLanguage();
            $lang->load('plg_system_ytshortcodes', $lang_dir);
        }
	}
	public function onAfterDispatch(){
		$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		$is_ajax_from_shortcodes = (int)JRequest::getVar('get_form_shortcodes', 0);
		if($is_ajax && $is_ajax_from_shortcodes == 1){
			require_once dirname(__FILE__) . '/core/helper.php';
			$element = JRequest::getVar('element', 0);
			$desc = JRequest::getVar('desc', 0);
			$name = JRequest::getVar('name', 0);
			$return = array();
			$return['html'] = AddElementShortcodes::yt_shortcodes_FormElement($element,$name,$desc);
			echo json_encode($return);die;
		}
		$live_show_shortcodes = (int)JRequest::getVar('live_show_shortcodes', 0);
		if($is_ajax && $live_show_shortcodes == 1){
			require_once dirname(__FILE__) . '/core/helper.php';
			$html = JRequest::getVar('html', 0);
			$return = array();
			$return['html'] = parse_shortcode($html);
			echo json_encode($return);die;
		}
	}
	public function listShortCodes()
	{
		$shortcoders = array(
		'accordion'  => array(
			'name'		=> JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION"),
			'desc'		=> JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ACCORDION_DESC"),
			'group'     => 'box',
			'icon'		=> "list-ul"
		),
		'animation'=> array(
			'name'   => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATE"),
			'desc'   => JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_ANIMATE_DESC"),
			'group'     => 'other',
			'icon'   => "bolt"
		),
		'audio'  => array(
			'name'		=> JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_AUDIO"),
			'desc'		=> JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_AUDIO_DESC"),
			'group'     => 'media',
			'icon'		=> "play-circle"
		),
		'blockquote' => array(
			'name'		=> JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_BLOCKQUOTE"),
			'desc'		=> JText::_("PLG_SYSTEM_YOUTECH_SHORTCODES_BLOCKQUOTE_DESC"),
			'group'     => 'box',
			'icon'		=> "quote-left"
		),
		'blur'       =>array(
			'name'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLUR'),
			'desc'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BLUR_DESC'),
			'group'     => 'box',
			'icon'     => "phone"
		),
		'box'       =>array(
			'name'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOX'),
			'desc'     => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOX_DESC'),
			'group'     => 'box',
			'icon'     => "list-alt"
		),
		'buttons' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BUTTON_DESC'),
			'group'     => 'content',
			'icon'		=> "square"
		),
		'carousel' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROUSEL'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CAROUSEL_DESC'),
			'group'     => 'gallery',
			'icon'		=> "bolt"
		),
		'charts'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CHARTS_DESC'),
			'group'     => 'box',
			'icon'   => "bar-chart-o"
		),
		'clear' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLEAR'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CLEAR_DESC'),
			'group'     => 'box',
			'icon'		=> "sign-in"
		),
		'columns' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COLUMNS_DESC'),
			'group'     => 'box',
			'icon'		=> "columns"
		),
		'contact_form'=> array(
			'name'   =>	JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTACT_DESC'),
			'group'     => 'content',
			'icon'   => "envelope"
		),
		'content_slider'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_SLIDER_DESC'),
			'group'     => 'extra gallery',
			'icon'   => "desktop"
		),
		'content_style' => array(
			'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE'),
			'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT_STYLE_DESC'),
			'group'     => 'content',
			'icon'    => "sort-amount-desc"
		),
		'countdown'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTDOWN_DESC'),
			'group'     => 'box',
			'icon'   => "sort-numeric-desc"
		),
		'counter'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTER'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_COUNTER_DESC'),
			'group'     => 'box',
			'icon'   => "sort-numeric-asc"
		),
		'divider' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DIVIDER_DESC'),
			'group'     => 'content',
			'icon'		=> "minus"
		),
		'document' => array(
			'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOCUMENT'),
			'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOCUMENT_DESC'),
			'group'     => 'media',
			'icon'    => "file-text"

		),
		'dropcap' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DROPCAP'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DROPCAP_DESC'),
			'group'     => 'content',
			'icon'		=> "font"
		),
		'dummy_image' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DUMMY_IMAGE'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DUMMY_IMAGE_DESC'),
			'group'     => 'content',
			'icon'		=> "picture-o"
		),
		'dummy_text' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DUMMY_TEXT'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DUMMY_TEXT_DESC'),
			'group'     => 'content',
			'icon'		=> "text-height"
		),
		'fancy_text'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FANCY_TEXT'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FANCY_TEXT_DESC'),
			'group'     => 'extra content',
			'icon'   => "text-height"
		),
		'flickr'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLICKR'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLICKR_DESC'),
			'group'  => 'extra content',
			'syntax' => "[yt_flickr id=\'95572727@N00\' limit=\'9\' lightbox=\'yes\' radius=\'0px\'] <br/>",
			'icon'   => "flickr"
		),
		'flip_box'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLIP_BOX'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FLIP_BOX_DESC'),
			'group'  => 'extra content',
			'icon'   => "files-o"
		),
		'frame'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FRAME'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_FRAME_DESC'),
			'group'  => 'content',
			'icon'   => "picture-o"
		),
		'gallery' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY_DESC'),
			'group'     => 'box',
			'icon'		=> "photo"
		),
		'google_font' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_FONT_DESC'),
			'group'     => 'box',
			'icon'		=> "text-width"
		),
		'google_map' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GOOGLE_MAP_DESC'),
			'group'     => 'box',
			'icon'		=> "map-marker"
		),
		'heading'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEADING'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HEADING_DESC'),
			'group'     => 'content',
			'icon'   => "h-square"
		),
		'highlight'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHT'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHT_DESC'),
			'group'     => 'content',
			'icon'   => "pencil-square-o"
		),
		'highlighter' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHTER'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_HIGHLIGHTER_DESC'),
			'group'     => 'content',
			'icon'		=> "list-alt"
		),
		'icon' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_DESC'),
			'group'     => 'extra content media',
			'icon'		=> "desktop"
		),
		'icon_list' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_LIST'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ICON_LIST_DESC'),
			'group'     => 'content',
			'icon'		=> "th-list"
		),
		'image_compare'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_COMPARE'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_IMAGE_COMPARE_DESC'),
			'group'     => 'extra content',
			'icon'   => "image"
		),
		'br' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE_BREAK'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LINE_BREAK_DESC'),
			'group'     => 'box',
			'icon'		=> "cut"
		),
		'lightbox' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIGHTBOX_DESC'),
			'group'     => 'gallery',
			'icon'		=> "arrows-alt"
		),
		'list_style' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIST_STYLE'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIST_STYLE_DESC'),
			'group'     => 'box',
			'icon'		=> "list-ol"
		),
		'livicon'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIVICON'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_LIVICON_DESC'),
			'group'     => 'extra content media',
			'icon'   => "cog fa-spin"
		),
		'masonry' => array(
			'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY'),
			'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MASONRY_DESC'),
			'group'     => 'box',
			'icon'    => "file-text"

		),
		'member'=>array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MEMBER'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MEMBER_DESC'),
			'group'     => 'extra box content',
			'icon'   => "users"

		),
		'message_box' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MESSAGE_BOX'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MESSAGE_BOX_DESC'),
			'group'     => 'box',
			'icon'		=> "warning"
		),
		'modal' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODAL'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MODAL_DESC'),
			'group'     => 'box',
			'icon'		=> "external-link"
		),
		'notification'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NOTIFICATION_DESC'),
			'group'     => 'box',
			'icon'   => "list-alt"
		),
		'panel'=> array(
			'name' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PANEL'),
			'desc' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PANEL_DESC'),
			'group'     => 'extra box',
			'icon' => "pencil-square-o"
		),
		
		'player_list' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PLAYER_LIST_DESC'),
			'group'     => 'box',
			'icon'		=> "music"
		),
		'points' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POINTS_DESC'),
			'group'     => 'box',
			'icon'		=> "dot-circle-o"
		),
		'pricing_tables' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PRICING_TABLES_DESC'),
			'group'     => 'extra box',
			'icon'		=> "table"
		),
		'portfolio'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PORTFOLIO_DESC'),
			'group'     => 'gallery',
			'icon'   => "briefcase"
		),
		'popovers'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POPOVERS'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_POPOVERS_DESC'),
			'group'     => 'other',
			'icon'   => "comment-o"
		),
		'promotion_box'=>array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROMOTION_BOX_DESC'),
			'group'     => 'other',
			'icon'   =>"pencil"
		),
		'progress_bar'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROGRESS_BAR'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_PROGRESS_BAR_DESC'),
			'group'     => 'extra other',
			'icon'   => "tasks"
		),
		'qrcode'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_QR_CODE_DESC'),
			'group'     => 'content',
			'icon'   => "qrcode"
		),
		'section'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SECTION'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SECTION_DESC'),
			'group'  => 'extra box',
			'icon'   => "arrows-alt"
		),
		'shadow'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SHADOW_DESC'),
			'group'  => 'extra other',
			'icon'   => "moon-o"
		),
		'skills' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SKILLS_DESC'),
			'group'     => 'box',
			'icon'		=> "align-left"
		),
		'social_icon' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOCIAL_ICON_DESC'),
			'group'     => 'content',
			'icon'		=> "twitter"
		),
		'social_like'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SC_SOCIAL_LIKE'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SC_SOCIAL_LIKE_DESC'),
			'group'     => 'content',
			'icon'   => "thumbs-o-up"
		),
		'social_share'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SC_SOCIAL_SHARE'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SC_SOCIAL_SHARE'),
			'group'     => 'content',
			'icon'   => "share"
		),
		'spacer' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPACER'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPACER_DESC'),
			'group'     => 'box',
			'icon'		=> "arrows-v"
		),
		'splash'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPLASH'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SPLASH_DESC'),
			'group'     => 'other',
			'icon'   => "bullhorn"
		),
		'toggle_box' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOGGLE'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOGGLE_DESC'),
			'group'     => 'box',
			'icon'		=> "tasks"
		),
		'tabs' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABS_DESC'),
			'group'     => 'box',
			'icon'		=> "folder"
		),
		'tables'=> array(
			'name'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE'),
			'desc'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TABLE_DESC'),
			'group'     => 'box',
			'icon'   =>"table"
		),
		'testimonial' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TESTIMONIAL_DESC'),
			'group'     => 'content',
			'icon'		=> "comment"
		),
		'tooltip' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_TOOLTIP_DESC'),
			'group'     => 'box',
			'icon'		=> "text-height"
		),
		'vimeo' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VIMEO'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VIMEO_DESC'),
			'group'     => 'media',
			'icon'		=> "vimeo-square"
		),
		'youtube' => array(
			'name'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_YOUTUBE'),
			'desc'		=> JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_YOUTUBE'),
			'group'     => 'media',
			'icon'		=> "youtube"
		),
		'url_underline' => array(
			'name'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE'),
			'desc'    => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_URL_UNDERLINE_DESC'),
			'group'     => 'media',
			'icon'    => "underline"
		),
	);

		$text  = '';
		$linkShortcode='';
		if(count($shortcoders)){
			$text .='<div class="yt_shortcode_overlay"></div>';
			$text .= '<a href="#" class="yt_shortcodes_close"></a>';
			$text .='<div class="yt_shortcodes_plugin">';
			$text .='<div class="wapper_shortcodes_plugin">';
			$text .='<div class="header_shortcodes_plugin">';
			$text .= '<div id="yt-generator-filter">';
                foreach ((array) YT_Data::groups() as $group => $label)
                {
					$text .= '<a href="#" data-filter="' . $group . '">' . $label . '</a>';
				}
			$text .= '</div>';
			$text .= '<div id="yt-generator_box_search">';
			$text .= '<input name="yt_generator_search" id="yt-generator-search" value="" placeholder="Search for shortcodes" type="text">';
			$text .= '</div>';
			
			$text .='</div>';
			$text .='<div class="yt_shortcodes_list_shortcodes">';
			$text .= '<div id="yt-generator-choices" class="yt-generator-clearfix">';
			foreach($shortcoders as $key => $shortcoder)
			{
				$text .= '<span style="opacity: 1;" data-name="'.$shortcoder['name'].'" data-shortcode="'.strtolower($key).'" class="yt_shortcode_element" title="'.$shortcoder['desc'].'" data-desc="'.$shortcoder['desc'].'" data-group="'.$shortcoder['group'].'"><i class="fa fa-'.$shortcoder['icon'].'"></i>'.$shortcoder['name'].'</span>';
			}
			$text .= '</div>';
			$text .='</div>';
			$text .='<div class="yt_shortcode_element_config"></div>';
			
			$text .='</div>';
			$text .='</div>';
			$text .='<div id="yt_shorcodes">';
			$text .='<span class="button-shortcodes btn-act"><span class="arrow"></span></span>
						<span class="button-shortcodes btn-text">YT Shortcodes</span>';
			$text .='</div>';
		}
		return $text;
	}
}