<?php
/**
 * @package SJ Search Pro for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */
defined ('_JEXEC') or die;

ini_set ('xdebug.var_display_max_depth',20);
ini_set ('xdebug.var_display_max_children',1024);
ini_set ('xdebug.var_display_max_data',4096);
require_once dirname(__FILE__).'/helper_base.php';
require_once dirname (__FILE__).'/vmloader.php';
class VmSearchProHelper extends SjVmSearchProBaseHelper
{
	protected $_params;
	protected $_module;
	protected $categories = null;
	public function getList(){
		$categories = array();
		if ($this->vm_require()) {
			$categories = $this->getCategories();
		}
		return $categories;
	}
	public function _autocomplete($search_category_id,$search_name, $params)
	{
		VmConfig::loadJLang('com_virtuemart', true);
		VmConfig::loadConfig();
		
		$list = array();
		$limitation = (int)$params->get('limit',8);
		$source_group = null;
		$catids = ($search_category_id == 0 ? 0 : $search_category_id);
		
		$query = ' * , pp.product_price FROM `#__virtuemart_products_en_gb` p LEFT JOIN #__virtuemart_product_prices pp ON p.virtuemart_product_id = pp.virtuemart_product_id';
		
		if($search_category_id != 0)
		{
			$query .= " LEFT JOIN `#__virtuemart_product_categories` pc ON (p.virtuemart_product_id = pc.virtuemart_product_id) WHERE pc.virtuemart_category_id = ".$search_category_id." AND p.product_name LIKE '%".$search_name."%'";
		}else{
			$query .= " WHERE p.product_name LIKE '%".$search_name."%'";
		}
		//$source_group = null;
		$productModel = VmModel::getModel('Product');
		//$productModel = new VirtuemartModelProductExtend();
		$items = $productModel->exeSortSearchListQuery (0,$query,'','','','','',$limitation);
		if($limitation == 0){
			$productModel->_noLimit = true;
		}
		else{
			$productModel->_noLimit = false;
			
		}
		//$productModel->addImages($items,1);
		$ratingModel = VmModel::getModel('ratings');
		$small_image_config = array(
			'type' => $params->get('imgcfg_type'),
			'width' => $params->get('imgcfg_width'),
			'height' => $params->get('imgcfg_height'),
			'quality' => 90,
			'function' => ($params->get('imgcfg_function') == 'none') ? null : 'resize',
			'function_mode' => ($params->get('imgcfg_function') == 'none') ? null : substr($params->get('imgcfg_function'), 7),
			'transparency' => $params->get('imgcfg_transparency', 1) ? true : false,
			'background' => $params->get('imgcfg_background')
		);
		if (!class_exists('CurrencyDisplay'))
			require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
			$currency = CurrencyDisplay::getInstance( );
		foreach($items as $item){
			
			$virtuemart_product_id = $item->virtuemart_product_id;
			$quantity = 1;
			$product_info = $productModel->getProduct($virtuemart_product_id,TRUE,TRUE,TRUE,$quantity);
			$productModel->addImages($product_info);
			$item_img = VmSearchProHelper::getVmImage($product_info, $params);
			$image = VmSearchProHelper::imageTag($item_img, $small_image_config);
			$salesPrice = "";
			$discountAmount = "";
			if (!empty($product_info->prices['salesPrice'])) {
				$salesPrice = $currency->createPriceDiv ('salesPrice', JText::_("SALES_PRICE"), $product_info->prices, false, false, 1.0);
			}
			if (!empty($product_info->prices['discountAmount'])) {
				$discountAmount = $currency->createPriceDiv('discountAmount', JText::_("DISCOUNT_AMOUNT"), $product_info->prices, false, false, 1.0);
			} 
			$list[] = array(
				'name' 			=> $item->product_name,	
				'product_id' 	=> $item->virtuemart_product_id,
				'salesPrice' 	=> $salesPrice,
				'discountAmount' => $discountAmount,
				'image'			=> $image,
				'link'			=> JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_category_id=' . $product_info->virtuemart_category_id.'&virtuemart_product_id=' . $product_info->virtuemart_product_id.'&keyword='.$search_name.''),
				'category_name' => $product_info->category_name,
				
			);
		}
		die (json_encode ($list));
	}
	protected function vm_require()
	{
		if (!class_exists('VmConfig')) {
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php')) {
				require JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
			} else {
				$this->error = 'Could not find VmConfig helper';
				return false;
			}
		}
		if (!class_exists('VmModel')) {
			if (defined('JPATH_VM_ADMINISTRATOR') && file_exists(JPATH_VM_ADMINISTRATOR . '/helpers/vmmodel.php')) {
				require JPATH_VM_ADMINISTRATOR . '/helpers/vmmodel.php';
			} else {
				$this->error = 'Could not find VmModel helper';
				return false;
			}
		}
		if (defined('JPATH_VM_ADMINISTRATOR')) {
			JTable::addIncludePath(JPATH_VM_ADMINISTRATOR . '/tables');
		}
		return true;
	}
	protected function getCategories()
	{
		if (is_null($this->categories)) {
			$this->categories = array();
			VmConfig::loadJLang('com_virtuemart', true);
			VmConfig::loadConfig();
			$categoryModel = VmModel::getModel('category');
			$categoryModel->_noLimit = true;
			$categories = $categoryModel->getCategories(true, false, false, "", true);
			if (!count($categories)) return $this->categories;
			$_categories = array();
			$_children = array();
			foreach ($categories as $i => $category) {
				$_categories[$category->virtuemart_category_id] = &$categories[$i];
			}
			foreach ($categories as $i => $category) {
				$cid = $category->virtuemart_category_id;
				$pid = $category->category_parent_id;
				if (isset($_categories[$pid])) {
					if (!isset($_children[$pid])) {
						$_children[$pid] = array();
					}
					$_children[$pid][$cid] = $cid;
				}
			}
			if (!count($_categories)) return $this->categories;

			$__categories = array();
			$__levels = array();
			foreach ($_categories as $cid => $category) {
				$pid = $category->category_parent_id;
				if (!isset($_categories[$pid])) {
					$queue = array($cid);
					$_categories[$cid]->level = 1;
					while (count($queue) > 0) {
						$qid = array_shift($queue);
						$__categories[$qid] = &$_categories[$qid];
						if (isset($_children[$qid])) {
							foreach ($_children[$qid] as $child) {
								$_categories[$child]->level = $_categories[$qid]->level + 1;
								array_push($queue, $child);
							}
						}
					}
				}
			}
			$this->categories = $__categories;
		}
		return $this->categories;
	}
	
	
}
if (!class_exists('VirtuemartModelProductExtend') && class_exists('VirtueMartModelProduct')) {
    class VirtuemartModelProductExtend extends VirtueMartModelProduct
    {
        function sortSearchListQuery($onlyPublished = TRUE, $virtuemart_category_id = FALSE, $group = FALSE, $nbrReturnProducts = FALSE, $langFields = Array())
        {
            $app = JFactory::getApplication();
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
                $isSite = $app->isSite();
                if ($onlyPublished) {
                    $where[] = ' p.`published`="1" ';
                }
                if ($isSite and !VmConfig::get('use_as_catalog', 0)) {
                    if (VmConfig::get('stockhandle', 'none') == 'disableit_children') {
                        $where[] = ' (p.`product_in_stock` - p.`product_ordered` >"0" OR children.`product_in_stock` - children.`product_ordered` > "0") ';
                        $joinChildren = TRUE;
                    } else if (VmConfig::get('stockhandle', 'none') == 'disableit') {
                        $where[] = ' p.`product_in_stock` - p.`product_ordered` >"0" ';
                    }
                }

                if ($virtuemart_category_id !== false) {
                    $joinCategory = TRUE;

                    if (is_string($virtuemart_category_id) && preg_match('/[\s|,]+/', $virtuemart_category_id)) {
                        $virtuemart_category_id = preg_split('/[\s|,]+/', $virtuemart_category_id);
                    }
                    if (!is_array($virtuemart_category_id)) {
                        settype($virtuemart_category_id, 'array');
                    }

                    $where[] = ' `pc`.`virtuemart_category_id` IN (' . implode(',', $virtuemart_category_id) . ')';

                }

                if ($isSite and !VmConfig::get('show_uncat_child_products', TRUE)) {
                    $joinCategory = TRUE;
                    $where[] = ' `pc`.`virtuemart_category_id` > 0 ';
                }

                if ($this->product_parent_id) {
                    $where[] = ' p.`product_parent_id` = ' . $this->product_parent_id;
                }

                if ($isSite) {
                    $usermodel = VmModel::getModel('user');
                    $currentVMuser = $usermodel->getUser();
                    $virtuemart_shoppergroup_ids = (array)$currentVMuser->shopper_groups;

                    if (is_array($virtuemart_shoppergroup_ids)) {
                        $sgrgroups = array();
                        foreach ($virtuemart_shoppergroup_ids as $key => $virtuemart_shoppergroup_id) {
                            $sgrgroups[] = 's.`virtuemart_shoppergroup_id`= "' . (int)$virtuemart_shoppergroup_id . '" ';
                        }
                        $sgrgroups[] = 's.`virtuemart_shoppergroup_id` IS NULL ';
                        $where[] = " ( " . implode(' OR ', $sgrgroups) . " ) ";

                        $joinShopper = TRUE;
                    }
                }

                if ($this->virtuemart_manufacturer_id) {
                    $joinMf = TRUE;
                    $where[] = ' `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` = ' . $this->virtuemart_manufacturer_id;
                }

                switch ($this->specail_product) {
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

                if ($this->filter_order) {
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
                            $orderBy = ' ORDER BY l.`product_name` ';
                            $joinPrice = TRUE;
                            break;
                        case 'product_price':
                            $orderBy = ' ORDER BY pp.`product_price` ';
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
            } else {
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

            if (count($where) > 0) {
                $whereString = ' WHERE (' . implode(' AND ', $where) . ') ';
            } else {
                $whereString = '';
            }

            //$this->orderByString = $orderBy;
            $product_ids = $this->_exeSortSearchListQuery(2, $select, $joinedTables, $whereString, $groupBy, $orderBy, $this->filter_order_Dir, $nbrReturnProducts);
            return $product_ids;

        }

        public function _exeSortSearchListQuery($object, $select, $joinedTables, $whereString = '', $groupBy = '', $orderBy = '', $filter_order_Dir = '', $nbrReturnProducts = false){

            $db = JFactory::getDbo();
            //and the where conditions
            $joinedTables .="\n".$whereString."\n".$groupBy."\n".$orderBy.' '.$filter_order_Dir ;
            //$joinedTables .= $whereString .$groupBy .$orderBy .$filter_order_Dir ;
            // 			$joinedTables .= $whereString .$groupBy .$orderBy;

            if($nbrReturnProducts){
                $limitStart = 0;
                $limit = $nbrReturnProducts;
                $this->_withCount = false;
            } else if($this->_noLimit){
                $this->_withCount = false;
                $limitStart = 0;
                $limit = 0;
            } else {
                $limits = $this->setPaginationLimits();
                $limitStart = $limits[0];
                $limit = $limits[1];
            }

            if($this->_withCount){
                $q = 'SELECT SQL_CALC_FOUND_ROWS '.$select.$joinedTables;
            } else {
                $q = 'SELECT '.$select.$joinedTables;
            }
            $app = JFactory::getApplication();
            //$page = $app->input->getInt('page',1);
            $_count = $nbrReturnProducts;
            $start = $app->input->getInt('ajax_reslisting_start',0);
            $limitStart = $start;
            $limit = $_count;
            if($this->_noLimit or empty($limit)){
                $db->setQuery($q);
            } else {
                $db->setQuery($q,$limitStart,$limit);
            }

            if($object == 2){
                $this->ids = $db->loadColumn();
            } else if($object == 1 ){
                $this->ids = $db->loadAssocList();
            } else {
                $this->ids = $db->loadObjectList();
            }
            if($err=$db->getErrorMsg()){
                vmError('exeSortSearchListQuery '.$err);
            }
            //vmdebug('my $limitStart '.$limitStart.'  $limit '.$limit.' q '.$db->getQuery() );

            if($this->_withCount){

                $db->setQuery('SELECT FOUND_ROWS()');
                $count = $db->loadResult();

                if($count == false){
                    $count = 0;
                }
                $this->_total = $count;
                if($limitStart>=$count){
                    if(empty($limit)){
                        $limit = 1.0;
                    }
                    $limitStart = floor($count/$limit);
                    $db->setQuery($q,$limitStart,$limit);
                    if($object == 2){
                        $this->ids = $db->loadColumn();
                    } else if($object == 1 ){
                        $this->ids = $db->loadAssocList();
                    } else {
                        $this->ids = $db->loadObjectList();
                    }
                }
// 			$this->getPagination(true);

            } else {
                $this->_withCount = true;
            }

            //print_r( $db->_sql );
            // 			vmdebug('my $list',$list);
            if(empty($this->ids)){
                $errors = $db->getErrorMsg();
                if( !empty( $errors)){
                    vmdebug('exeSortSearchListQuery error in class '.get_class($this).' sql:',$db->getErrorMsg());
                }
                if($object == 2 or $object == 1){
                    $this->ids = array();
                }
            }
            // 			vmTime('exeSortSearchListQuery SQL_CALC_FOUND_ROWS','exe');

            return $this->ids;

        }

    }
}

