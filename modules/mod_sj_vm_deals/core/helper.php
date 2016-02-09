<?php
/**
 * @package SJ Accordion for Virtuemart
 * @version 1.1.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
 
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/vmloader.php';
require_once dirname(__FILE__).'/helper_base.php';
abstract class VMDealsPHelper extends VMDealsBaseHelper {
	private static $_category_tree;
	public static function getList(&$params,$getC = 0)
	{
		if(!class_exists('VmConfig')) return;
		VmConfig::loadConfig ();
		JFactory::getLanguage ()->load ('com_virtuemart');
		$items = array();
		$list = array();
		$_list = array();
		$catids = array();
		$catids[] = $params->get('catid');
		$categoryModel = VmModel::getModel('Category');
		$categoryModel->_noLimit = true;
		if($params->get('item_categori_title_display') == 0){

			$S_cid =$params->get('catid');
			$child_catid = self::getCategoryTree($S_cid,0);
			$catid_s = array();
			$catid_s[] = $S_cid;
			$lv_c = $params->get('levels', 0);
			if(count($child_catid) > 0){
				foreach($child_catid as $i){
					if($i->level <= $lv_c){
						$catid_s[] = $i->virtuemart_category_id;
					}
					
				}
			}
			$return_category = array();
			foreach($catid_s as $id){
				$tg = array();
				$tg[] = $id;
				$return_category[] = self::_getCategoryInfor($tg, $params)[0];
			}
			if($getC == 1){
				return $return_category;
			}
		}
		settype($catids, 'array');
		$limitation = (int)$params->get('source_limit',8);
		$desc_maxlength = $params->get('item_desc_max_characs',50);
		$source_group = null;
        $ordering_direction = $params->get('product_ordering_direction');
        $source_order = $params->get('source_order','product_name');

        $p_special =  $params->get('show_front');		
		$c_id = $params->get('catid');		
		$additional_catids = array();
		$categories = array();
		if(!empty($catids)){
			if ($params->get('show_child_category_products', 1) == 1 && (int) $params->get('levels', 0) > 0) {
				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
				foreach($catids as $catid){
					$items = $categoryModel->getCategoryTree($catid,0);
					$categoryModel->_noLimit = true;
					if(!empty($items)){
						foreach($items as $category){
							$condition = $category->level <= $levels;
							if ($condition) {
								$additional_catids[] = $category->virtuemart_category_id;
							}
						}
					}
				}
				$catids = array_unique(array_merge($catids, $additional_catids));
			}
			$productModel = VmModel::getModel('Product');
			$productModel = new VirtuemartModelProductExtend();
			$productModel->filter_order = $source_order;
			$productModel->specail_product = $p_special;
			$productModel->ordering_direction = $ordering_direction;
            if($limitation == 0){
                $productModel->_noLimit = true;
            }
            else{
                $productModel->_noLimit = false;
            }
			$items = $productModel->getProductListing( $source_group,$limitation , true, true, false, true, $catids );
			$productModel->addImages($items);
			$ratingModel = VmModel::getModel('ratings');
			foreach($items as $item){
				$item->title = $item->product_name;	
				$item->id = $item->virtuemart_product_id;
				$item->description = $item->product_desc;
				$item->short_desc = self::_cleanText($item->product_s_desc);
				$item->_description =  self::_cleanText($item->description);
				$item->_description = ($item->_description !='')?self::truncate($item->_description,$desc_maxlength):self::truncate($item->short_desc,$desc_maxlength);
				$item->vote = $ratingModel->getVoteByProduct($item->virtuemart_product_id);
				$item->rating = $ratingModel->getRatingByProduct($item->virtuemart_product_id);
				$list[] = $item;
			} 
			
		}
		return $list;
	}
	public static function _getCategoryInfor($catids, $params)
    {
        if (empty($catids)) return;
        $categoryModel = VmModel::getModel('Category');
        $categoryModel->_noLimit = true;
        $list = array();
        foreach ($catids as $_cat) {
            $category = $categoryModel->getCategory($_cat, false);
            $list[] = $category;
        }
        $categoryModel->addImages($list);
        foreach ($list as $catid) {
            $catid->title = $catid->category_name;
            $catid->link = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $catid->virtuemart_category_id);
            $catid->_description = $catid->category_description;
            $catid->_description = self::_trimEncode($catid->_description != '') ? self::truncate($catid->_description, $params->get('tab_max_characters', 20)) : '';
            $catid->field_order = $params->get('source_order');
            $catid->id = $catid->virtuemart_category_id;
            $list[$catid->id] = $catid;
        }

        $cat_order_by = $params->get('cat_order_by', null);
        $cat_ordering_direction = $params->get('cat_ordering_direction', 'ASC');
        if ($cat_ordering_direction == 'ASC') {
            switch ($cat_order_by) {
                default:
                case 'title':
                    usort($list, create_function('$a, $b', 'return strnatcasecmp( $a->category_name, $b->category_name);'));
                    break;
                case 'created':
                    usort($list, create_function('$a, $b', 'return $a->created_on < $b->created_on;'));
                    break;
                case 'random':
                    shuffle($list);
                    break;
            }
        } else {
            switch ($cat_order_by) {
                default:
                case 'title':
                    usort($list, create_function('$a, $b', 'return strnatcasecmp( $b->category_name, $a->category_name);'));
                    break;
                case 'created':
                    usort($list, create_function('$a, $b', 'return $a->created_on > $b->created_on;'));
                    break;
                case 'random':
                    shuffle($list);
                    break;
            }
        }

        return $list;
    }

    public static function _getCatIds($params)
    {
        $catids = $params->get('catid');
        !is_array($catids) && settype($catids, 'array');
        if ($catids == null) return;
        $_catids = self::_getPublishCategories($catids);
        if (empty($_catids)) return;

        return $_catids;
    }

  
    

    


    public static function _getChildCategories($catids, $params)
    {
        $additional_catids = array();
        if ($params->get('show_child_category_products', 1) == 1) {

            $levels = $params->get('levels', 1) > 0 ? $params->get('levels', 1) : 9999;
            foreach ($catids as $catid) {
                $items = self::getCategoryTree($catid, 0);
				
                if (!empty($items)) {
                    foreach ($items as $category) {
                        $condition = $category->level <= $levels;
                        if ($condition) {
                            $additional_catids[] = $category->virtuemart_category_id;
                        }
                    }
                }
            }
            $list = array_unique(array_merge($catids, $additional_catids));
            return $list;
        } else {
            return $list = $catids;
        }
    }

    private static function _getPublishCategories($catids)
    {
        $list = array();
        !is_array($catids) && settype($catids, 'array');
        $db = JFactory::getDBO();
        $q = "
            SELECT virtuemart_category_id as id
            FROM #__virtuemart_categories as c
            WHERE c.virtuemart_category_id IN ( " . implode(',', $catids) . " ) AND c.published = 1
        ";
        $db->setQuery($q);
        $_catids = $db->loadObjectList();
        foreach ($_catids as $cat) {
            $list[] = $cat->id;
        }

        return $list;
    }

    /**
     * Override function getCategoryTree in model Category
     */
    public static function getCategoryTree($parentId = 0, $level = 0, $onlyPublished = true, $keyword = '')
    {
        $sortedCats = array();
        $categoryModel = VmModel::getModel('Category');
        $limits = $categoryModel->setPaginationLimits();
        $limitStart = $limits[0];
        $limit = $limits[1];

        $categoryModel->_noLimit = true;
        if ($keyword != '') {
            $sortedCats = self::getCategories($onlyPublished, false, false, $keyword);
            if (!empty($sortedCats)) {
                $siblingCount = count($sortedCats);
                foreach ($sortedCats as $key => &$category) {
                    $category->siblingCount = $siblingCount;
                }
            }
        } else {
            self::rekurseCats($parentId, $level, $onlyPublished, $keyword, $sortedCats);
        }

        $categoryModel->_noLimit = false;
        $categoryModel->_total = count($sortedCats);

        $categoryModel->_limitStart = $limitStart;
        $categoryModel->_limit = $limit;

        $categoryModel->getPagination();

        if (empty($limit)) {
            return $sortedCats;
        } else {
            $sortedCats = array_slice($sortedCats, $limitStart, $limit);
            return $sortedCats;
        }

    }

    /**
     * Override function rekurseCats in model Category
     */
    public static function rekurseCats($virtuemart_category_id, $level, $onlyPublished, $keyword, &$sortedCats)
    {
        $level++;
        $categoryModel = VmModel::getModel('Category');
        if ($childs = $categoryModel->hasChildren($virtuemart_category_id)) {

            $childCats = self::getCategories($onlyPublished, $virtuemart_category_id, false, $keyword, true);
            if (!empty($childCats)) {

                $siblingCount = count($childCats);
                foreach ($childCats as $key => $category) {
                    $category->level = $level;
                    $category->siblingCount = $siblingCount;
                    $sortedCats[] = $category;
                    self::rekurseCats($category->virtuemart_category_id, $level, $onlyPublished, $keyword, $sortedCats);
                }
            }
        }
    }

    /**
     * Override function getCategories in model Category
     */
    public static function getCategories($onlyPublished = true, $parentId = false, $childId = false, $keyword = "", $vendorId = false)
    {
        $categoryModel = VmModel::getModel('Category');
        $select = ' c.`virtuemart_category_id`, l.`category_description`, l.`category_name`, c.`ordering`, c.`published`, cx.`category_child_id`, cx.`category_parent_id`, c.`shared` ';

        $joinedTables = ' FROM `#__virtuemart_categories_' . VmConfig::$vmlang . '` l
				  JOIN `#__virtuemart_categories` AS c using (`virtuemart_category_id`)
				  LEFT JOIN `#__virtuemart_category_categories` AS cx
				  ON l.`virtuemart_category_id` = cx.`category_child_id` ';

        $where = array();

        if ($onlyPublished) {
            $where[] = " c.`published` = 1 ";
        }
        if ($parentId !== false) {
            $where[] = ' cx.`category_parent_id` = ' . (int)$parentId;
        }

        if ($childId !== false) {
            $where[] = ' cx.`category_child_id` = ' . (int)$childId;
        }

        if ($vendorId === false) {
            $vendorId = VmConfig::isSuperVendor();
        }

        if ($vendorId == 1) {

            $where[] = ' (c.`virtuemart_vendor_id` = "' . (int)$vendorId . '" OR c.`shared` = "1") ';
        }

        if (!empty($keyword)) {
            $db = JFactory::getDBO();
            $keyword = '"%' . $db->escape($keyword, true) . '%"';
            //$keyword = $db->Quote($keyword, false);
            $where[] = ' ( l.`category_name` LIKE ' . $keyword . '
							   OR l.`category_description` LIKE ' . $keyword . ') ';
        }

        $whereString = '';
        if (count($where) > 0) {
            $whereString = ' WHERE ' . implode(' AND ', $where);
        } else {
            $whereString = 'WHERE 1 ';
        }
        $ordering = $categoryModel->_getOrdering();
        self::$_category_tree = $categoryModel->exeSortSearchListQuery(0, $select, $joinedTables, $whereString, 'GROUP BY virtuemart_category_id', $ordering);
        return self::$_category_tree;
    }
}


if(!class_exists('VirtuemartModelProductExtend') && class_exists('VirtueMartModelProduct') || class_exists('VMDealsHelper')){
	class VirtuemartModelProductExtend extends VirtueMartModelProduct{
		function sortSearchListQuery ($onlyPublished = TRUE, $virtuemart_category_id = FALSE, $group = FALSE, $nbrReturnProducts = FALSE, $langFields = Array()) {
			$app = JFactory::getApplication ();
			$groupBy = ' group by p.`virtuemart_product_id` ';

			$joinCategory = FALSE;
			$joinMf = FALSE;
			$joinPrice = FALSE;
			$joinCustom = FALSE;
			$joinShopper = FALSE;
			$joinChildren = FALSE;
			$joinLang = TRUE;
			$orderBy = ' ';

			$where = array();
			$useCore = TRUE;
			if ($useCore) {
				$isSite = $app->isSite ();
				if ($onlyPublished) {
					$where[] = ' p.`published`="1" ';
				}
				if($isSite and !VmConfig::get('use_as_catalog',0)) {
					if (VmConfig::get('stockhandle','none')=='disableit_children') {
						$where[] = ' (p.`product_in_stock` - p.`product_ordered` >"0" OR children.`product_in_stock` - children.`product_ordered` > "0") ';
						$joinChildren = TRUE;
					} else if (VmConfig::get('stockhandle','none')=='disableit') {
						$where[] = ' p.`product_in_stock` - p.`product_ordered` >"0" ';
					}
				}
			
				if ( $virtuemart_category_id !== false ){
					$joinCategory = TRUE ;
					
					if ( is_string($virtuemart_category_id) && preg_match('/[\s|,]+/', $virtuemart_category_id)){
						$virtuemart_category_id = preg_split('/[\s|,]+/', $virtuemart_category_id);
					}
					if ( !is_array($virtuemart_category_id) ){
						settype($virtuemart_category_id, 'array');
					}
					
					$where[] = ' `pc`.`virtuemart_category_id` IN ('.implode(',', $virtuemart_category_id).')';
					
				}

				if ($isSite and !VmConfig::get('show_uncat_child_products',TRUE)) {
					$joinCategory = TRUE;
					$where[] = ' `pc`.`virtuemart_category_id` > 0 ';
				}

				if ($this->product_parent_id) {
					$where[] = ' p.`product_parent_id` = ' . $this->product_parent_id;
				}

				if ($isSite) {
					$usermodel = VmModel::getModel ('user');
					$currentVMuser = $usermodel->getUser ();
					$virtuemart_shoppergroup_ids = (array)$currentVMuser->shopper_groups;

					if (is_array ($virtuemart_shoppergroup_ids)) {
						$sgrgroups = array();
						foreach ($virtuemart_shoppergroup_ids as $key => $virtuemart_shoppergroup_id) {
							$sgrgroups[] = 's.`virtuemart_shoppergroup_id`= "' . (int)$virtuemart_shoppergroup_id . '" ';
						}
						$sgrgroups[] = 's.`virtuemart_shoppergroup_id` IS NULL ';
						$where[] = " ( " . implode (' OR ', $sgrgroups) . " ) ";

						$joinShopper = TRUE;
					}
				}

				if ($this->virtuemart_manufacturer_id) {
					$joinMf = TRUE;
					$where[] = ' `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` = ' . $this->virtuemart_manufacturer_id;
				}
				
				switch ($this->specail_product){
					case 'hide':
						$where[] = ' p.`product_special`="0" '; 
						break;

					case 'only':
						$where[] = ' p.`product_special`="1" '; 
						break;

					case 'show':
					default:
						break;
				}
				
				$this->filter_order_Dir = $this->ordering_direction;
				
				if($this->filter_order){
					switch ($this->filter_order) {
						default:
						case 'id':
							$orderBy = ' ORDER BY p.`virtuemart_product_id` ';
							break;
						case 'ordering':
							$orderBy = ' ORDER BY `pc`.`ordering` ';
							$joinCategory = TRUE;
							break;
						case 'product_name':
							$orderBy = ' ORDER BY `product_name` ';
							$joinPrice = TRUE;
							break;
						case 'product_price':
							$orderBy = ' ORDER BY `product_price` ';
							$joinPrice = TRUE;
							break;	
						case 'created_on':
							$orderBy = ' ORDER BY p.`created_on` ';
							break;
						case 'latest':
							$orderBy = 'ORDER BY p.`modified_on`';
							break;
						case 'topten':
							$orderBy = ' ORDER BY p.`product_sales` '; 
							$where[] = 'pp.`product_price`>"0.0" ';
							break;
                        case 'random':
                            $orderBy = ' ORDER BY  RAND() ';
                            break;

                    }
					$joinPrice = TRUE;
				}
				if ($group) {
					$groupBy = 'group by p.`virtuemart_product_id` ';
				}
			}

			if ($joinLang) {
				$select = ' l.`virtuemart_product_id` FROM `#__virtuemart_products_' . VMLANG . '` as l';
				$joinedTables = ' JOIN `#__virtuemart_products` AS p using (`virtuemart_product_id`)';
			}
			else {
				$select = ' p.`virtuemart_product_id` FROM `#__virtuemart_products` as p';
				$joinedTables = '';
			}

			if ($joinCategory == TRUE) {
				$joinedTables .= ' LEFT JOIN `#__virtuemart_product_categories` as pc ON p.`virtuemart_product_id` = `pc`.`virtuemart_product_id`
				 LEFT JOIN `#__virtuemart_categories_' . VMLANG . '` as c ON c.`virtuemart_category_id` = `pc`.`virtuemart_category_id`';
			}
			if ($joinMf == TRUE) {
				$joinedTables .= ' LEFT JOIN `#__virtuemart_product_manufacturers` ON p.`virtuemart_product_id` = `#__virtuemart_product_manufacturers`.`virtuemart_product_id`
				 LEFT JOIN `#__virtuemart_manufacturers_' . VMLANG . '` as m ON m.`virtuemart_manufacturer_id` = `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` ';
			}

			if ($joinPrice == TRUE) {
				$joinedTables .= ' LEFT JOIN `#__virtuemart_product_prices` as pp ON p.`virtuemart_product_id` = pp.`virtuemart_product_id` ';
			}
			if ($this->searchcustoms) {
				$joinedTables .= ' LEFT JOIN `#__virtuemart_product_customfields` as pf ON p.`virtuemart_product_id` = pf.`virtuemart_product_id` ';
			}
			if ($this->searchplugin !== 0) {
				if (!empty($PluginJoinTables)) {
					$plgName = $PluginJoinTables[0];
					$joinedTables .= ' LEFT JOIN `#__virtuemart_product_custom_plg_' . $plgName . '` as ' . $plgName . ' ON ' . $plgName . '.`virtuemart_product_id` = p.`virtuemart_product_id` ';
				}
			}
			if ($joinShopper == TRUE) {
				$joinedTables .= ' LEFT JOIN `#__virtuemart_product_shoppergroups` ON p.`virtuemart_product_id` = `#__virtuemart_product_shoppergroups`.`virtuemart_product_id`
				 LEFT  OUTER JOIN `#__virtuemart_shoppergroups` as s ON s.`virtuemart_shoppergroup_id` = `#__virtuemart_product_shoppergroups`.`virtuemart_shoppergroup_id`';
			}

				

			if ($joinChildren) {
				$joinedTables .= ' LEFT OUTER JOIN `#__virtuemart_products` children ON p.`virtuemart_product_id` = children.`product_parent_id` ';
			}

			if (count ($where) > 0) {
				$whereString = ' WHERE (' . implode (' AND ', $where) . ') ';
			}
			else {
				$whereString = '';
			}
			
			$this->orderByString = $orderBy;
			$product_ids = $this->exeSortSearchListQuery (2, $select, $joinedTables, $whereString, $groupBy, $orderBy, $this->filter_order_Dir, $nbrReturnProducts);
			return $product_ids;

		}

	}
}	