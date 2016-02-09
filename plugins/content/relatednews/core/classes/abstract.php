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

if (!class_exists('_AbstractReader')){
	abstract class _AbstractReader {
		protected $_items;
		protected $_item_fields = array();
		abstract function getItemsFromDb($ids, $overload=true);
		abstract function getItemsIn($cids, $params);

		protected function addItemFieldToSelect($field){
			if (isset($this->_item_fields['*'])){
				return false;
			} else if (is_string($field)){
				$field = trim($field);
				if ( $field=='*' ){
					$this->_item_fields = array('*'=>'');
				} else {
					$this->_item_fields[$field] = '';
				}
			} else if (is_array($field) && count($field)){
				foreach ($field as $key => $value) {
					$this->_item_fields[$key] = $value;
					break;
				}
			}
		}

		protected function getItemFields($alias='e'){
			if ( !empty($this->_item_fields) ){
				$field_array = array();
				foreach ($this->_item_fields as $field => $field_alias){
					$field			= trim($field);
					$field_alias	= trim($field_alias);
					$select_field	= '';
					if (strpos($field, '.')!==false || ((strpos($field, '(')!==false) && (strpos($field, ')')!==false))){
						$select_field .= $field;
					} else {
						$select_field .= "$alias.$field";
					}
					if ( !empty($field_alias) ){
						$select_field .= " AS $field_alias";
					}
					array_push($field_array, $select_field);
				}
				return implode(',', $field_array);
			}
			return "$alias.*";
		}

		public function getItem($id){
			$item = null;
			if ( isset($this->_items[$id]) || $this->getItemsFromDb($id, false) ){
				$item = &$this->_items[$id];
			}
			return $item;
		}

		public function getItems($ids){
			$items = array();
			if ( is_string($ids) ){
				$ids = explode(',', $ids);
			}
			if ( is_array($ids) ){
				array_map('intval', $ids);
				$ids = array_unique($ids);
				$missing = array();

				foreach ($ids as $id) {
					if (!isset($this->_items[$id])){
						$missing[$id] = $id;
					}
				}
					
				empty($missing) OR $this->getItemsFromDb($missing, false);
					
				foreach ($ids as $id){
					if (isset($this->_items[$id])){
						$items[$id] = &$this->_items[$id];
					}
				}
			}
			return $items;
		}



		protected $_categories = null;
		protected $_category_fields = array();

		protected function addCategoryFieldToSelect($field){
			if (isset($this->_category_fields['*'])){
				return false;
			} else if (is_string($field)){
				$field = trim($field);
				if ( $field=='*' ){
					$this->_category_fields = array('*'=>'');
				} else {
					$this->_category_fields[$field] = '';
				}
			} else if (is_array($field) && count($field)){
				foreach ($field as $key => $value) {
					$this->_category_fields[$key] = $value;
					break;
				}
			}
		}

		protected function getCategoryFields($alias='e'){
			if ( !empty($this->_category_fields) ){
				$field_array = array();
				foreach ($this->_category_fields as $field => $field_alias){
					$field			= trim($field);
					$field_alias	= trim($field_alias);
					$select_field	= '';
					if (strpos($field, '.')!==false || ((strpos($field, '(')!==false) && (strpos($field, ')')!==false))){
						$select_field .= $field;
					} else {
						$select_field .= "$alias.$field";
					}
					if ( !empty($field_alias) ){
						$select_field .= " AS $field_alias";
					}
					array_push($field_array, $select_field);
				}
				return implode(',', $field_array);
			}
			return "$alias.*";
		}

		abstract function getCategoriesFromDb();
		abstract function buildCategoriesTree();

		public function getCategory($cid){
			$categories = &$this->getCategoriesFromDb();
			$category = null;
			if ( isset($categories[$cid]) ){
				$category = &$categories[$cid];
			}
			return $category;
		}

		public function getPublishedCategories($params){
			$category_ids = null;
			if (isset($params['source']) && !empty($params['source'])){
				$category_ids = $params['source'];
				if (is_string($category_ids)){
					$category_ids = explode(',', $category_ids);
				}
				if (!is_array($category_ids)){
					$category_ids = array($category_ids);
				}

				// load published categories
				$this->getCategoriesFromDb($category_ids);

				foreach ($category_ids as $i => $cid) {
					if (!isset($this->_categories[$cid])){
						unset($category_ids[$i]);
					}
				}
			}

			return $category_ids;
		}

		public function getChildCategoryIds($cid, $self_include=false, $deep=0){
			$return = array();
			$categories = &$this->getCategoriesFromDb();
			if (is_array($cid)) {
				$cid = array_shift($cid);
			} else if (is_string($cid)) {
				$cid = (int)$cid;
			}

			if( isset($categories[$cid]) ){

				if ($deep){
					$tmplevel = array();
				}
				// prepare
				$stack = array($categories[$cid]);
				$tmplevel[$cid] = $self_include ? 1 : 0;
				// start
				while( count($stack)>0 ){
					$top = array_pop($stack);
					if ($self_include || $cid!=$top->id){
						in_array($top->id, $return) || array_push($return, $top->id);
					}

					if (($deep==0 || $tmplevel[$top->id]<$deep) && count($top->child_category)){
						foreach (array_reverse($top->child_category) as $id => $child) {
							array_push($stack, $child);
							$tmplevel[$child->id] = $tmplevel[$top->id] + 1;
						}
					}
				}
			}
			return $return;
		}

		public function getCategoryItems($cid, $params, $only_ids=false){
			if (isset($params['subcategories']) && (int)$params['subcategories']){
				// include sub categories's items
				$inc_category_ids =array();
				if (is_string($cid)){
					$cid = explode(",",$cid);
				}
				if (!is_array($cid)){
					$cid = array($cid);
				}
				foreach($cid as $id){
					$temp = $this->getChildCategoryIds($id, true);
					foreach($temp as $tempid){
						$inc_category_ids[] = $tempid;
					}
				}
				$inc_category_ids = array_unique($inc_category_ids);
			} else {
				if (is_string($cid)){
					$cid = explode(",",$cid);
				}
				if (!is_array($cid)){
					$cid = array($cid);
				}
				$inc_category_ids = $cid;
			}
			if ($only_ids){
				return $this->getItemsIn($inc_category_ids, $params);
			} else {
				$item_ids = $this->getItemsIn($inc_category_ids, $params);
				return $this->getItems($item_ids, $params);
			}
		}
	}
}