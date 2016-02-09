<?php
/**
 * Class YtMenu
 *
 * @author The YouTech JSC
 * @package menusys
 * @filesource ytmenu.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */

if (!class_exists('YtMenu')){
	class YtMenu extends YtObject{
		var $id = null;
		var $_child = array();
		var $_isRoot = false;
		var $_classes = array();
		function __construct($item=null, $params=null){
			$this->params = new YtParams($params);
			
			if (!isset($item)){
				$this->_isRoot= true;
				$this->level = 0;
				
			} else {
				
				$this->id 			= $item->id;			// itemid
				$this->title 		= $item->title;			// title
				
				$this->alias 		= $item->alias;			// alias
				$this->link 		= $item->link;			// link
				$this->type 		= $item->type;			// type: component/url/separator
				$this->parent	 	= $item->parent_id;		// id of parent item
				
				//$this->sublevel 	= $menuitem->sublevel;
				$this->browserNav 	= $item->browserNav;	// browser nav -> targets type: _blank, popup,...
				$this->access 		= $item->access;		// public, registered, special
				$this->home 		= $item->home;			// is homepage
				$this->tree 		= $item->tree;			// array: parents nodes
				$this->route 		= $item->route;
				$this->query  		= $item->query;			// array:
				$this->params->bind($item->params->toArray());
				
			}
				
		}

		/**
		 * child operators
		 * @return $mixed
		 */
		public function getChild(){
			return $this->_child;
		}
		public function addChild($item){
			if (!$this->_child){
				$this->_child = array();
			}
			$item->set('level', $this->get('level', 0)+1);
			$this->_child[$item->get('id')] = $item;
			return $item;
		}
		public function removeChild($item='all'){
			if ($item=='all'){
				$this->_child = array();
			} else {
				$itemid = (gettype($item)=='object') ? $item->get('id') : $item;
				if (!empty($itemid) && isset($this->_child[$itemid])){
					unset($this->_child[$itemid]);
				}
			}
			return $this;
		}
		public function haveChild(){
			return count($this->_child)>0;
		}
		public function countChild(){
			return count($this->_child);
		}

		/**
		 * css class for menu item
		 * @param string $css classname
		 * @return $this
		 */
		public function addClass($css=''){
			if(isset($css) && !empty($css)){
				$this->_classes[$css] = $css;
			}
			return $this;
		}
		public function removeClass($css=''){
			if(isset($css) && !empty($css)){
				if (isset($this->_classes[$css])){
					unset($this->_classes[$css]);
				}
			}
			return $this;
		}
		public function removeAllClass($exclude=array()){
			if (count($this->_classes)){
				foreach ($this->_classes as $clazz){
					if (!in_array($clazz, $exclude)){
						$this->removeClass($clazz);
					}
				}
			}
			return $this;
		}
		public function getClass(){
			$liClass = '';
			$mega_menu_class = $this->params->get('ytmenu_class');
			
			if ($this->get('active')==1 && !in_array('active', $this->_classes)){
				array_unshift($this->_classes, 'active');
			}
			if(count($this->_classes)>0){
				$liClass = implode(' ', $this->_classes);
				$liClass = $liClass.' '.$mega_menu_class;
			}
			return $liClass;
		}
		public function haveClass(){
			return ($this->get('active')==1 || count($this->_classes)>0);
		}

		public function getContent($tpl='default'){
			$menustyle = strtolower($this->params->get('menustyle', 'basic'));
			$overload = $this->params->get('basepath') . J_SEPARATOR . 'class' . J_SEPARATOR . 'common' . J_SEPARATOR . 'html' . J_SEPARATOR . $menustyle .J_SEPARATOR . $tpl . '.php';
			$template = $this->params->get('basepath') . J_SEPARATOR . 'class' . J_SEPARATOR . $menustyle . J_SEPARATOR . 'tmpl' . J_SEPARATOR . $tpl . '.php';

			if (file_exists($overload)){
				include($overload);
			} else if (file_exists($template)){
				include($template);
			} else {
				die('menu template <b>' . $template . '</b> not found!');
			}
		}
		public function addStylesheet($files){
			if($this->_isRoot){
				$menustyle = strtolower($this->params->get('menustyle', 'basic'));
				$assets = $this->_getAssetsPath();
				$document =& JFactory::getDocument();
				foreach ($files as $css){
					$ytcsskey = $menustyle.$css;
					if (!isset($document->$ytcsskey)){
						$document->$ytcsskey = true;
						JHTML::stylesheet($css, $assets);
					}
				}
			}
		}
		public function addScript($files){
			if($this->_isRoot){
				$menustyle = strtolower($this->params->get('menustyle', 'basic'));
				$assets = $this->_getAssetsPath();
				$document =& JFactory::getDocument();
				foreach ($files as $js){
					$ytjskey = $menustyle.$js;
					if (!isset($document->$ytjskey)){
						$document->$ytjskey = true;
						JHTML::script($js, $assets);
					}
				}
			}
		}
		
		private function _getAssetsPath(){
			$menustyle = strtolower($this->params->get('menustyle', 'basic'));
			$abspath = $this->params->get('basepath') . J_SEPARATOR . 'class' . J_SEPARATOR . $menustyle . J_SEPARATOR . 'assets' . J_SEPARATOR;
			$abspath = realpath($abspath);
			!empty($abspath) or die($this->params->get('basepath') . ' does not exits. Please kindly set basepath for menusys');
			
			if(JPATH_BASE!='/'){
				$relpath = array_pop( explode(JPATH_BASE, realpath($abspath), 2) );
				$relpath = str_replace(J_SEPARATOR, "/", $relpath . J_SEPARATOR);
			}else{
				$relpath = str_replace(J_SEPARATOR.J_SEPARATOR, "/", $abspath . J_SEPARATOR);
			}
			
			return substr($relpath, 1);
		}

		public function isRoot(){
			return $this->_isRoot;
		}

		public function canAccess(){
			$user = JFactory::getUser();
			return isset($this->access) && in_array($this->access, $user->getAuthorisedViewLevels());
		}
		

		public function getLinkInMobile($level){
			$itemtype = $this->get('type', 'url');
			if ($itemtype=='menulink' && false!=$this->_aliasitem){
				$tmp =& $this->_aliasitem;
				$tmp->parent = $this->parent;
				$itemtype = $tmp->get('type', 'url');
			} else if ($itemtype=='alias'){
				$tmp =& $this;
				$tmp->id = $this->params->get('aliasoptions');
			} else {
				$tmp =& $this;
			}

			$menu_title =  htmlspecialchars($this->title);
			$icon_menu = (trim($tmp->params->get('menu-anchor_css'))!='')?trim($tmp->params->get('menu-anchor_css')):'';
			$icon_menu = ($icon_menu!='')?'<i class="'.$icon_menu.'"></i>':'';

			$title_attr = (trim($tmp->params->get('menu-anchor_title'))!='')?trim($tmp->params->get('menu-anchor_title')):htmlspecialchars($this->title);
			$title_attr = ($title_attr!='')?' title="'.$title_attr.'"':'';

			$anchor_html = "";
			$anchor_href = "";
			
			switch($itemtype){
				case 'separator':
					$anchor_html = '<a'.$title_attr.' href="#1">' .$icon_menu.$menu_title .'</a>';
					break;
				case 'url':
					if ((strpos($tmp->link, "index.php?") === 0) && (strpos($tmp->link, "Itemid=") === false)) {
						$anchor_href = $tmp->link."&amp;Itemid=".$tmp->id;
					} else {
						$anchor_href = empty($tmp->link) ? '#' : $tmp->link;
					}
					break;
				default:
					$router = JSite::getRouter();
					if ($router->getMode() == JROUTER_MODE_SEF) {
						$anchor_href = "index.php?Itemid=".$tmp->id;
					} else {
						$anchor_href = $tmp->link . "&Itemid=".$tmp->id ;
					}
			}

			if (!empty($anchor_href)){
				// Handle SSL links
				$iSecure = $tmp->params->get('secure', 0);
				if ($tmp->home == 1) {
					$anchor_href = JURI::base();
				} elseif (strcasecmp(substr($anchor_href, 0, 4), 'http') && (strpos($tmp->link, "index.php?") !== false)) {
					$anchor_href = JRoute::_($anchor_href, true, $iSecure);
				} else {
					$anchor_href = str_replace('&', '&amp;', $anchor_href);
				}
				
				$class = ($this->get('active')==1)?' class="active"':'';
				$anchor_html = "<a".$title_attr." href='".$anchor_href."'".$class.">".$icon_menu.$menu_title."</a>";
			}

			return $anchor_html;
		}
		
		
		public function getLink(){
			$itemtype = $this->get('type', 'url');
			if ($itemtype=='menulink' && false!=$this->_aliasitem){
				$tmp =& $this->_aliasitem;
				$tmp->parent = $this->parent;
				$itemtype = $tmp->get('type', 'url');
			} else if ($itemtype=='alias'){
				$tmp =& $this;
				$tmp->id = $this->params->get('aliasoptions');
			} else {
				$tmp =& $this;
			}

			$menu_image = $tmp->params->get('menu_image');
			if (isset($menu_image) && $menu_image!='-1' && !empty($menu_image)){
			
				$menu_arrow ="";
				if(count($this->_child) >= 1 || count($this->loadModules() ) ){
					//if($this->level==1) $menu_arrow = "<i class=\"fa fa-angle-down\"></i>";
					if ($this->level > 1) $menu_arrow = "<i class=\"fa fa-angle-right\"></i>";
				}
				
				$menu_image_url = JURI::base(true) . "/{$menu_image}";
				$menu_image_open = "<span class=\"menu-icon\">";
				$menu_image_child= ( (count($this->_child) > 1 || count($this->loadModules() )))? " <img src=\"{$menu_image_url}\" alt=\"\" />" : "";
				$menu_image_child .= $menu_arrow  ;
				$menu_image_close= "</span>";
			} else {
				$menu_arrow ="";
				if(count($this->_child) >= 1 || count($this->loadModules() ) ){
					if($this->level==1) $menu_arrow = "<i class=\"fa fa-angle-down\"></i>";
					else if ($this->level > 1) $menu_arrow = "<i class=\"fa fa-angle-right\"></i>";
				}

				$menu_image_open  = "";
				$menu_image_child = $menu_arrow  ;
				$menu_image_close = "";
			}
			
			$icon_menu = (trim($tmp->params->get('menu-anchor_css'))!='')?trim($tmp->params->get('menu-anchor_css')):'';
			$icon_menu = ($icon_menu!='')?'<i class="'.$icon_menu.'"></i>':'';

			$title_attr = (trim($tmp->params->get('menu-anchor_title'))!='')?trim($tmp->params->get('menu-anchor_title')):htmlspecialchars($this->title);
			$title_attr = ($title_attr!='')?' title="'.$title_attr.'"':'';

			$menu_title = "<span class=\"menu-title\">" .$icon_menu. htmlspecialchars($this->title) . "</span>";
			$menu_desc  = $this->params->get('ytext_desc', null);
			$menu_desc  = isset($menu_desc)&&!empty($menu_desc) ? "<span class=\"menu-desc\">" . htmlspecialchars($menu_desc) . "</span>" : "";
			$show_desc  = empty($menu_desc) ? "" : " showdesc";
			if (!empty($show_desc)){
				$this->addClass('showdesc');
			}
			
			$anchor_innerHTML = $menu_image_open . $menu_title . $menu_desc .$menu_image_child. $menu_image_close;
			$anchor_html = "";
			$anchor_href = "";
			$anchor_class= $this->getClass().' item-link';
			switch($itemtype){
				case 'separator':
					$anchor_html = "<div".$title_attr." class=\"$anchor_class separator\">$anchor_innerHTML</div>";
					break;
				case 'url':
					if ((strpos($tmp->link, "index.php?") === 0) && (strpos($tmp->link, "Itemid=") === false)) {
						$anchor_href = $tmp->link."&amp;Itemid=".$tmp->id;
					} else {
						$anchor_href = empty($tmp->link) ? '#' : $tmp->link;
					}
					break;
				default:
					$router = JSite::getRouter();
					if ($router->getMode() == JROUTER_MODE_SEF) {
						$anchor_href = "index.php?Itemid=".$tmp->id;
					} else {
						$anchor_href = $tmp->link . "&Itemid=".$tmp->id;
					}
			}

			if (!empty($anchor_href)){
				// Handle SSL links
				$iSecure = $tmp->params->get('secure', 0);
				if ($tmp->home == 1) {
					$anchor_href = JURI::base();
				} elseif (strcasecmp(substr($anchor_href, 0, 4), 'http') && (strpos($tmp->link, "index.php?") !== false)) {
					$anchor_href = JRoute::_($anchor_href, true, $iSecure);
				} else {
					$anchor_href = str_replace('&', '&amp;', $anchor_href);
				}
				//echo "<pre>$anchor_href</pre>";die();
				switch ($tmp->browserNav){
					default:
					case 0:
						// _top
						$anchor_html = "<a".$title_attr." class=\"$anchor_class\" href=\"$anchor_href\">$anchor_innerHTML</a>";
						break;
					case 1:
						// _blank
						$anchor_html = "<a".$title_attr." class=\"$anchor_class\" href=\"$anchor_href\" target=\"_blank\">$anchor_innerHTML</a>";
						break;
					case 2:
						// window.open
						$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$this->params->get('window_open');

						// hrm...this is a bit dickey
						$link = str_replace('index.php', 'index2.php', $anchor_href);
						$anchor_html = "<a".$title_attr." class=\"$anchor_class\" href=\"$link\" onclick=\"window.open(this.href,'targetWindow','$attribs');return false;\">$anchor_innerHTML</a>";
						break;
				}
			} else if ($itemtype!='separator'){
				$anchor_html = "<a>$anchor_innerHTML</a>";
			}

			return $anchor_html;
		}

		public function getSubmenuWidth(){
			$subWidth = $this->params->get('ytext_submenuwidth');
			$subStyle = "";
			if (!empty($subWidth) && is_numeric($subWidth)){
				$subWidth = intval($subWidth);
				$subStyle = $subWidth>0 ? "style=\"width:{$subWidth}px;min-width:{$subWidth}px\"" : "";
			}
			return $subStyle;
		}

		public function haveMegaContent(){
			$haveContent = false;
			$contentType = $this->params->get('ytext_contenttype');
			switch($contentType){
				default:
				case 'menu':
				case 'megachild':
					$haveContent = count($this->_child)>0;
					break;
				case 'mod':
					$ytext_modules     = $this->params->get('ytext_modules',   '');
					$haveContent = !empty($ytext_modules) && count($this->loadModules())>0;
					break;
				case 'pos':
					$ytext_positions   = $this->params->get('ytext_positions', '');
					$haveContent = !empty($ytext_positions) && count($this->loadModules())>0;
					break;
			}
			return $haveContent;
		}

		public function loadModules(){
			if (!isset($this->__modules)){
				$item_content_type = $this->params->get('ytext_contenttype', 'menu');
				$ytext_modules     = $this->params->get('ytext_modules',   '');
				$ytext_positions   = $this->params->get('ytext_positions', '');
				$user = JFactory::getUser();
				$modules = array();
					
				if ($item_content_type=='mod'){
					if (is_array($ytext_modules)){
						$moduleid = $ytext_modules;
					} else {
						$moduleid = preg_split("/[|\s]+/", $ytext_modules, -1, true);
					}
					$sql_id_set = "(" . implode(",", $moduleid) . ")";
					
					$access_condition = "";
					$user = JFactory::getUser();
					$groups = $user->getAuthorisedViewLevels();
					$access_condition = "m.access IN (" . implode(",", $groups) . ")";
					
					$query = "SELECT id,title,module,position,content,showtitle,params FROM #__modules AS m WHERE $access_condition AND m.published=1 AND m.id IN $sql_id_set;";
					$db = JFactory::getDBO();
					$db->setQuery($query);
					$rows = $db->loadObjectList();
					if (!empty($rows)){
						foreach ($rows as $row){
							$id = $row->id;
							$modname = substr($row->module, 4);
							$row->name = $modname;
							$row->user = 0;
							$row->style= '';
							$modules[$id] = $row;
						}
					}
				} else if ($item_content_type=='pos'){
					if (is_array($ytext_positions)){
						$posname = $ytext_positions;
					} else {
						$posname = preg_split("/[|\s]+/", $ytext_positions, -1, true);
					}
					if (!empty($posname)){
						foreach ($posname as $pos){
							if ($pos == $this->params->get('exclude_positions', false)){
								continue;
							}
							$db = JFactory::getDBO();
							$pos_condition = "m.position=" . $db->quote($pos);
							
							$access_condition = "";
							$user = JFactory::getUser();
							$groups = $user->getAuthorisedViewLevels();
							$access_condition = "m.access IN (" . implode(",", $groups) . ")";
							
							$query = "SELECT id,title,module,position,content,showtitle,params FROM #__modules AS m WHERE $access_condition AND m.published=1 AND $pos_condition;";
							
							$db->setQuery($query);
							$rows = $db->loadObjectList();
							if (!empty($rows)){
								foreach ($rows as $row){
									$id = $row->id;
									$modname = substr($row->module, 4);
									$row->name = $modname;
									$row->user = 0;
									$row->style= '';
									$modules[$id] = $row;
								}
							}
						}
					}
				}
				$this->__modules = $modules;
			}
			return $this->__modules;
		}

		public function getMegaCols(){
			$ytext_cols = $this->params->get('ytext_cols', 1);
			$ytext_width = $this->params->get('ytext_width', 0);
			$ytext_columns_width = $this->params->get('ytext_colwidth', '');

			$cols = array();

			if ($ytext_cols=='auto'){
				$item_content_type = $this->params->get('ytext_contenttype', 'menu');
				$n = ($item_content_type=='megachild') ? $this->countChild() : 1;
			} else {
				$n = ((int)$ytext_cols <= 1) ? 1 : $ytext_cols; // number cols
			}

			$gw = $ytext_width; // min-Width of col
			$cw = preg_split("/[,]+/", $ytext_columns_width, $n, true); // specific column width

			$t = 0;
			//$t2 = 0;
			$auto = false;
			$autoWidth = -1;
			for ($i = 0; $i < $n; $i++) {
				if ( isset($cw[$i]) && !$auto ){
					$cols[$i] = intval($cw[$i]);
					$t += $cols[$i];
					//$t2 = $t;
				} else {
					if($autoWidth<0 && $autoWidth!='auto'){
						if ((int)$gw>0){
							$autoWidth = round(($gw-$t)/($n-$i));
						} else {
							$autoWidth = 'auto';
						}
					}
					$cols[$i] = $autoWidth;
					//$t += $cols[$i];
				}
			}
			//if ($t-$ytext_width) $this->params->set('ytext_width', $t);
			return $cols;
		}
	}
}