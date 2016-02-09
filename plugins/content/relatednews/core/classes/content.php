<?php
/**
 * @package Content - Related News
 * @version 1.7.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

include 'abstract.php';

if (!class_exists('_ContentReader')){
	class _ContentReader extends _AbstractReader{

		public function __construct(){

			$this->addItemFieldToSelect('id');
			$this->addItemFieldToSelect('title');
			$this->addItemFieldToSelect('alias');
			$this->addItemFieldToSelect(array('introtext'=>'description'));
			$this->addItemFieldToSelect('created');
			$this->addItemFieldToSelect('modified');
			$this->addItemFieldToSelect('hits');
			$this->addItemFieldToSelect('featured');
			$this->addItemFieldToSelect(array('catid'=>'category_id'));
			$this->addItemFieldToSelect(array('created_by'=>'author_id'));
			$this->addItemFieldToSelect('images');
			//$this->addItemFieldToSelect('*');

			$this->addCategoryFieldToSelect('id');
			$this->addCategoryFieldToSelect('title');
			$this->addCategoryFieldToSelect('level');
			$this->addCategoryFieldToSelect('alias');
			$this->addCategoryFieldToSelect('description');
			$this->addCategoryFieldToSelect('params');
			$this->addCategoryFieldToSelect(array('created_user_id'=>'author_id'));
			$this->addCategoryFieldToSelect(array('created_time' => 'created'));
			$this->addCategoryFieldToSelect('hits');
			$this->addCategoryFieldToSelect('parent_id');
			$this->addCategoryFieldToSelect(array('(SELECT COUNT(count_table.id) FROM #__content AS count_table WHERE e.id=count_table.catid)' => 'news_count'));

		}

		public function getItemsFromDb($ids, $overload = false){
			if (!is_array($ids)){
				$ids = array((int)$ids);
			}

			$db = &JFactory::getDbo();
			$query = "SELECT " . $this->getItemFields() . " FROM #__content AS e WHERE e.id IN (" . implode(',', $ids)  . ") GROUP BY e.id;";
			// SjTools::dump($query);
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$item_count = 0;
			if ( !is_null($rows) ){
				foreach($rows as $item){
					if ($overload || !isset($this->_items[$item->id])){
						$this->_items[$item->id] = $item;
						$item_count++;
					}
				}
			}

			return $item_count;
		}

		public function getItemsIn($cids, $params){
			$db = &JFactory::getDbo();
			$now = JFactory::getDate()->toMySQL();
			$nulldate = $db->getNullDate();

			if (is_array($cids)){
				$category_filter_set = implode(',', $cids);
			}

			$query = "
			SELECT e.id
			FROM #__content as e
			WHERE
			e.state IN(1)
			AND e.catid IN ($category_filter_set)
			" . ($this->_getContentAccessFilter() ? 'AND '.$this->_getContentAccessFilter() : '') . " -- Access condition
			AND (e.publish_up   = {$db->quote($nulldate)} OR e.publish_up   <= {$db->quote($now)})
			AND (e.publish_down = {$db->quote($nulldate)} OR e.publish_down >= {$db->quote($now)})
			AND e.language IN ({$db->quote(JFactory::getLanguage()->getTag())} , {$db->quote('*')})
			{$this->_itemFilter($params)}
			GROUP BY e.id
			ORDER BY {$this->_itemOrders($params)}
			{$this->_queryLimit($params)}
			";
			$db->setQuery($query);
			$ids = $db->loadResultArray();
			return $ids;
		}

		public function getCategoriesFromDb(){
			if (is_null($this->_categories)){
				$db = &JFactory::getDbo();
				$query = "
				SELECT " . $this->getCategoryFields() . "
				FROM #__categories AS e
				WHERE
				e.published IN (1)
				AND e.extension = 'com_content'
				AND e.parent_id > 0
				" . ($this->_getContentAccessFilter() ? 'AND '.$this->_getContentAccessFilter() : '') . " -- Access condition
				AND e.language IN ({$db->quote(JFactory::getLanguage()->getTag())} , {$db->quote('*')})
				GROUP BY e.id
				ORDER BY e.lft
				";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				if ( !is_null($rows) ){
					foreach($rows as $category){
						$category->child_category = array();
						$this->_categories[$category->id] = $category;
					}
					$this->buildCategoriesTree();
				}
			}
			return $this->_categories;
		}

		public function buildCategoriesTree(){
			if(count($this->_categories)){
				foreach ($this->_categories as $cid => $category) {
					if (isset($this->_categories[$category->parent_id])){
						$parent_category = &$this->_categories[$category->parent_id];
						if (!isset($parent_category->child_category[$category->id])){
							$parent_category->child_category[$category->id] = $category;
						}
					}
					//$title = $category->title . ' <b style="color:red">(' . $category->id . ')</b> <b style="color:blue">[' . $category->parent_id . ']</b>  <b style="color:green">[' . $category->news_count . ']</b>';
					//$title = str_repeat('- - ', $category->level) . $title;
					//echo "<br>$title";
				}
				//echo "<hr>";
			}
		}
		protected function _itemFilter($params, $alias='e'){
			$join_filter="";
			if ( isset($params['source_filter']) ){
				// frontpage filter.
				switch ($params['source_filter']){
					default:
					case '0':
						$join_filter = "";
					break;
					case '1':
						$join_filter = "AND $alias.featured=0";
						break;
					case '2':
						$join_filter = "AND $alias.featured=1";
						break;
				}
			}
			return $join_filter;
		}

		protected function _itemOrders($params, $alias='e'){
			// set order by default
			$item_order_by = "$alias.ordering";

			if ( isset($params['source_order_by']) ){
				$string_order_by = trim($params['source_order_by']);
				switch ($string_order_by){
					default:
					case 'ordering':
						$item_order_by = "$alias.ordering";
					break;
					case 'mostview':
					case 'hits':
						$item_order_by = "$alias.hits DESC";
						break;
					case 'recently_add':
					case 'created':
						$item_order_by = "$alias.created DESC";
						break;
					case 'recently_mod':
					case 'modified':
						$item_order_by = "$alias.modified DESC";
						break;
					case 'title':
						$item_order_by = "$alias.title";
						break;
					case 'random':
						$item_order_by = 'rand()';
						break;
				}
			}
			return $item_order_by;
		}

		protected function _queryLimit($params){
			$source_limit = '';
			if (isset($params['source_limit']) && (int)$params['source_limit']){
				$source_limit_start = 0;
				if (isset($params['source_limit_start']) && (int)$params['source_limit_start']){
					$source_limit_start = (int)$params['source_limit_start'];
				}
				$source_limit_total = (int)$params['source_limit'];
				$source_limit = "LIMIT $source_limit_start, $source_limit_total";
			}
			return $source_limit;
		}

		protected function _getContentAccessFilter($alias='e'){
			$condition = false;
			$app  = &JFactory::getApplication();
			$params = $app->getParams();
			if ($params instanceof JRegistry && !$params->get('show_noauth', 0)){
				$user = &JFactory::getUser();
				$condition = $alias . '.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';
			}
			return $condition;
		}

		protected $_image4=array();
		public function getItemImage(&$item){
			if (is_int($item)){
				$item = $this->getItem($item);
			}
			if (!isset($this->_image4[$item->id])){
				// image extract
				if (strpos($item->images, '{')!==false){
					$item_images = json_decode($item->images);
					$item->images = null;
					if (isset($item_images->image_intro)
							&& (SjTools::isUrl($item_images->image_intro)
									|| file_exists($item_images->image_intro))){
						$item->image = $item_images->image_intro;
					} else if (isset($item_images->image_fulltext)
							&& (SjTools::isUrl($item_images->image_fulltext)
									|| file_exists($item_images->image_fulltext))){
						$item->image = $item_images->image_fulltext;
					}
				}

				if (!isset($item->image_extracted)){
					$item_images = SjTools::extractImages($item->description);
					$item->image_extracted = true;
				}

				if (!isset($item->image) && count($item_images)){
					// get first exists image
					foreach ($item_images as $i => $image_url) {
						if (SjTools::isUrl($image_url) || file_exists($image_url)){
							$item->image = $image_url;
							break;
						}
					}
				}
				$this->_image4[$item->id] = isset($item->image);
			}
			return $this->_image4[$item->id];
		}
		public function getItemUrl(&$item){
			if (!class_exists('ContentHelperRoute')){
				include_once JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php';
			}
			if (!isset($item->url)){
				$item->url = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->category_id));
			}
			return true;
		}
	}
}