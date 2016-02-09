<?php
/**
 * Class YtMenuBase
 * 
 * @author The YouTech JSC
 * @package menusys
 * @filesource ytmenubase.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */

if (!class_exists('YtMenuBase')){
	class YtMenuBase extends YtObject{
		private $params;
		protected $menu;
		public function __construct($params){
			$this->params = $this->_createMenuParams();
			$this->params->bind($params);
		}

		/**
		 * Get menu tree for joomla 1.5
		 * @return YtMenu object is root of menu tree.
		 */
		public function getMenu($params=null){
			if (!isset($this->menu) || (isset($params) && !empty($params))){
				if (isset($params) && !empty($params)){
					$this->params->bind($params);
				}

				// Initialize variables.
				$app		= JFactory::getApplication();
				$sitemenu	= $app->getMenu();

				// If no active menu, use default
				$active = ($sitemenu->getActive()) ? $sitemenu->getActive() : $sitemenu->getDefault();
				$path		= $active->tree;
				$start		= (int) $this->params->get('startlevel', 0);
				$end		= (int) $this->params->get('endlevel',   -1);
				$deep		= $end - $start + 1;

				$items 		= $sitemenu->getItems('menutype', $this->params->get('menutype', 'mainmenu'));

				$root = new YtMenu(null, $this->params);
				$k = array();

				if (isset($items) && count($items)>0){

					$itemids = array();
					$itemobj = array();
					$smallest_level = 99999;

					foreach($items as $i => $item){
						$iid = $item->id;

						$itemids[$iid] = 1;
						$itemobj[$iid] =& $items[$i];
						if (!isset($item->sublevel)){
							if ($smallest_level > $item->level){
								$smallest_level = $item->level;
							}
						} else if ($smallest_level > $item->sublevel){
							$smallest_level = $item->sublevel;
						}
						
					}

					
					foreach($items as $i => $item){
						// level filter
						$s_parent_item = false;
						$spid  = $item->tree[0];
						$iid   = $item->id;
						$ideep = count($item->tree);

						if (!isset($item->sublevel)){
							if ($start>$item->level) continue;
						} else if ($start>$item->sublevel) continue;
						// if $deep<=0 ignore endlevel check.
						if (!isset($item->sublevel)){
							if ($deep>0 && $end <$item->level) continue;
						} else if ($deep>0 && $end <$item->sublevel) continue;
						
						// if is not child of start level items.
						if (!isset($item->sublevel)){
							if ($deep>1 && $itemobj[$spid]->level>$smallest_level ) continue;
						} else if ($deep>1 && $itemobj[$spid]->sublevel>$smallest_level) continue;
						
						$k[$iid] = new YtMenu($item, $this->params);
					};

					// set active items
					foreach ($path as $id){
						if (isset($k[$id])){
							$k[$id]->set('active', 1);
						}
					}

					foreach($k as $key => $item){
						// if type is alias (value is 'menulink' in Joomla 1.5)
						if ($item->type=='menulink'){
							$refid = $item->query['Itemid'];
							$aliasitem = $sitemenu->getItem($refid);
							if ($aliasitem){
								$item->set('_aliasitem', new YtMenu($aliasitem, $this->params));
							} else {
								$item->set('_aliasitem', false);
							}
							//$item->debug();die();
						}

						// id of item's parent
						$ipid = $item->get('parent');
						$ideep = count($item->tree);

						if (!isset($k[$ipid])) {
							$root->addChild($item);
							continue;
						}
						//echo "<br>parent is  $ipid";
						$parentNode =& $k[$ipid];
						$parentNode->addChild($item);
					}
				}


				unset($itemids);
				unset($itemobj);
				$this->menu = $root;
			}
			return $this->menu;
		}

		function dumproot($root){
			$a2d = array();
			if ($root->isRoot()){
				echo "\nROOT";
				if($root->haveChild()){
					foreach ($root->getChild() as $child){
						$this->dumproot($child);
					}
				}
			} else {
				$l = $root->level;
				echo "\n";
				for ($i=0; $i<$l; $i++){
					echo "\t";
				}
				echo $root->id;
				if($root->haveChild()){
					foreach ($root->getChild() as $child){
						$this->dumproot($child);
					}
				}
			}
		}

		/**
		 * create new default params.
		 * @return YtParams is default.
		 */
		private function _createMenuParams(){
			return new YtParams(
				array(
					'menutype'		=> 'mainmenu',
					'menustyle'		=> 'basic',
					'startlevel'	=> 0,
					'endlevel'		=> -1,
					'direction'		=> 'ltr',
					'basepath'		=> dirname(__FILE__),
					'cssidsuffix'	=> ''
				)
			);
		}
	}}