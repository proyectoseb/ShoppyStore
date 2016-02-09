<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */
defined ('_JEXEC') or die;

ini_set ('xdebug.var_display_max_depth',20);
ini_set ('xdebug.var_display_max_children',1024);
ini_set ('xdebug.var_display_max_data',4096);
require_once dirname (__FILE__).'/vmloader.php';

class VmFilterHelper
{
	protected $_params;
	protected $_module;

	public function __construct($params,$module)
	{
		$this->_params = $params;
		$this->_module = $module;
		if (!class_exists ('VmConfig')) return;
		VmConfig::loadConfig ();
		VmConfig::loadJLang ('com_virtuemart',true);
		return $this;
	}

	public function _getCustomCartVariant($customids = null)
	{
		$query = ' * FROM `#__virtuemart_customs` WHERE field_type = "S"  ';
		$query .= 'AND `custom_parent_id` = 0';
		$_custom_model = VmModel::getModel ('custom');
		$customs = $_custom_model->exeSortSearchListQuery (0,$query,'','',$_custom_model->_getOrdering ());
		$list = array();
		$_list = array();
		$_customids = $this->_params->get ('customids');
		!is_array ($_customids) && settype ($_customids,'array');
		if (empty($_customids)) return;
		if (empty($customs)) return;

		foreach ($customs as $custom){
			$_cus_field = $this->_getCustomFields ($custom->virtuemart_custom_id);
			if (!empty($_cus_field)){
				if (is_array ($customids) && !empty($customids)){
					foreach ($customids as $key => $custid){
						if ($key == $custom->virtuemart_custom_id){
							$_list[$custom->custom_title] = $this->_getCustomFields ($custom->virtuemart_custom_id,$custid);
						}
					}
				}

				foreach ($_customids as $_cus){
					if ($_cus == $custom->virtuemart_custom_id){
						$list[$custom->custom_title] = $this->_getCustomFields ($custom->virtuemart_custom_id);
					}
				}

			}

		}

		if (is_array ($customids) && !empty($customids)){
			return $_list;
		}

		return $list;
	}

	private function _getCustomFields($virtuemart_custom_id,$cus_values = null)
	{
		$query = ' DISTINCT  field.`customfield_value`, C.* , field.*
					FROM `#__virtuemart_product_customfields` AS field
					LEFT JOIN `#__virtuemart_customs` AS C ON C.`virtuemart_custom_id` = field.`virtuemart_custom_id`';
		if ($virtuemart_custom_id){
			$query .= 'WHERE  field.`virtuemart_custom_id` ='.(int)$virtuemart_custom_id;
		}
		$query .= ' GROUP BY field.`customfield_value`';
		$_customf_model = VmModel::getModel ('customfields');
		$list = array();
		$_list = array();
		$customsf = $_customf_model->exeSortSearchListQuery (0,$query,'','',$_customf_model->_getOrdering ());
		if (!empty($customsf)){
			foreach ($customsf as $i => $cusf){
				$productExtModel = new VirtuemartModelProductFilter();
				$productExtModel->searchcustoms = array($cusf->virtuemart_custom_id => array($i => $cusf->customfield_value));
				$productExtModel->sortSearchListQuery (true,0);
				//$cusf->_countProduct = $productExtModel->getTotal();
				$cusf->_countProduct = self::Count_CustomField_Valude ($cusf->customfield_value, $cusf->virtuemart_custom_id);
				$cusf->cat_manu_id = $cusf->virtuemart_custom_id;
				$cusf->cat_manu_name = $cusf->customfield_value;
				$group_name = preg_replace ('/\s+/','-',$cusf->custom_title);
				$cusf->name_replace = $cusf->virtuemart_customfield_id;
				$list[$cusf->virtuemart_customfield_id] = $cusf;
				if (is_array ($cus_values) && !empty($cus_values)){
					foreach ($cus_values as $cus){
						if ($cus == $cusf->customfield_value){
							$_list[$cusf->virtuemart_customfield_id] = $cusf;
						}
					}
				}
			}
		}
		if (is_array ($cus_values) && !empty($cus_values)){
			return $_list;
		}
		return $list;
	}

	private static function Count_CustomField_Valude($customfield_value, $virtuemart_custom_id)
	{
		$db = JFactory::getDbo ();
		$q = "
			SELECT pc.virtuemart_product_id
			FROM #__virtuemart_product_customfields as pc
			WHERE pc.customfield_value = '".$customfield_value."' AND pc.virtuemart_custom_id = '".$virtuemart_custom_id."'
		";
		$db->setQuery ($q);
		$row = $db->loadObjectList ();

		return count ($row);
	}

	public function _getCategories($catids = null)
	{
		$categoryModel = VmModel::getModel ('category');
		$categoryModel->_noLimit = true;

		$_catids = $this->_params->get ('catids');
		settype ($_catids,'array');
		if (empty($_catids)) return;

		$categories = array();
		foreach ($_catids as $catid){
			$__categories = $categoryModel->getCategory ($catid,false);
			$categories[] = $__categories;
		}

		if (empty($categories)) return;
		usort ($categories,create_function ('$a, $b','return $a->ordering > $b->ordering;'));
		$_categories = array();

		foreach ($categories as $category){
			$category->_countProduct = $categoryModel->countProducts ($category->virtuemart_category_id);
			$_categories[$category->virtuemart_category_id] = $category;
		}

		$list = array();
		$_list = array();
		foreach ($_categories as $key => $category){
			$category->cat_manu_id = $category->virtuemart_category_id;
			$category->cat_manu_name = $category->category_name;
			foreach ($_catids as $_cat){
				if ($_cat == $key){
					$list[$key] = $category;
				}
			}
			if ($catids != null && is_array ($catids)){
				foreach ($catids as $catid){
					if ($catid == $category->virtuemart_category_id){
						$_list[$catid] = $category;
					}
				}
			}
		}

		if ($catids != null && is_array ($catids)){
			return $this->_oderByCategory ($_list);
		}
		return $this->_oderByCategory ($list);
	}

	private function _oderByCategory($obj)
	{
		$_oderby = $this->_params->get ('cat_orderby');
		switch ($_oderby){
			case 1:
				usort ($obj,create_function ('$a, $b','return $a->category_name > $b->category_name;'));
				break;
			case 2:
				usort ($obj,create_function ('$a, $b','return $a->_countProduct < $b->_countProduct;'));
				break;
			case 3:
				usort ($obj,create_function ('$a, $b','return $a->ordering > $b->ordering;'));
				break;
		}
		return $obj;
	}

	private function _getManuafactures($mnuids = null)
	{
		$manufacturersModel = VmModel::getModel ('Manufacturer');
		$manufacturers = $manufacturersModel->getManufacturers (true,true,true);
		$list = array();
		$_list = array();
		if (!empty($manufacturers)){
			$productExtModel = new VirtuemartModelProductFilter();
			foreach ($manufacturers as $manu){
				$productExtModel->virtuemart_manufacturer_id = $manu->virtuemart_manufacturer_id;
				$productExtModel->sortSearchListQuery (true,0);
				$manu->_countProduct = $productExtModel->getTotal ();
				$manu->cat_manu_id = $manu->virtuemart_manufacturer_id;
				$manu->cat_manu_name = $manu->mf_name;
				if (is_array ($mnuids) && !empty($mnuids)){
					foreach ($mnuids as $mnuid){
						if ($mnuid == $manu->cat_manu_id){
							$_list[$mnuid] = $manu;
						}
					}
				}
				$list[$manu->cat_manu_id] = $manu;
			}
		}
		if (is_array ($mnuids) && !empty($mnuids)){
			return $_list;
		}
		return $list;
	}

	public function _getCategoriesManuafactures()
	{
		$list = array();
		if ((int)$this->_params->get ('display_category',1)){
			$_categories = $this->_getCategories ();
			$list['categories'] = (!empty($_categories))?$_categories:null;
		}

		if ((int)$this->_params->get ('display_manuafactures',1)){
			$_manufacturers = $this->_getManuafactures ();
			$list['manufacturers'] = (!empty($_manufacturers))?$_manufacturers:null;
		}

		return $list;
	}

	public function _productFiltering($_arr_datas)
	{
		$list = array();
		if (!empty($_arr_datas)){
			$_categories = isset($_arr_datas['categories'])?$_arr_datas['categories']:0;
			if ($_categories){
				$list['categories'] = $this->_getCategories ($_categories);
			}
			$_manufacturers = isset($_arr_datas['manufacturers'])?$_arr_datas['manufacturers']:0;
			if ($_manufacturers){
				$list['manufacturers'] = $this->_getManuafactures ($_manufacturers);
			}
			$_price_min = isset($_arr_datas['ft_price_min'])?$_arr_datas['ft_price_min']:'';
			$_price_max = isset($_arr_datas['ft_price_max'])?$_arr_datas['ft_price_max']:'';
			$currency = CurrencyDisplay::getInstance ();
			$symbol = $currency->getSymbol ();
			if ($_price_min != '' && $_price_max != ''){
				$list['prices'] = array(array('cls' => 'ft-price-input','value' => 'From: '.$_price_min.' '.$symbol.' to '.$_price_max.' '.$symbol));
			}

			if ($_price_min != '' && $_price_max == ''){
				$list['prices'] = array(array('cls' => 'ft-price-min','value' => ' >= '.$_price_min.' '.$symbol));
			}

			if ($_price_min == '' && $_price_max != ''){
				$list['prices'] = array(array('cls' => 'ft-price-max','value' => ' <= '.$_price_max.' '.$symbol));
			}

			$_customfields = isset($_arr_datas['custom_id'])?$_arr_datas['custom_id']:0;
			if ($_customfields){
				$list_array = $this->_getCustomCartVariant ($_customfields);
				if (!empty($list_array)){
					foreach ($list_array as $key => $_list){
						$list[$key] = $_list;
					}
				}
			}
		}
		return $list;
	}

	public function _processDataAjax($datas,$params)
	{
		$_arr_datas = array();
		parse_str ($datas,$_arr_datas);
		if (!empty($_arr_datas)){

			$list = array();
			$_categories = isset($_arr_datas['categories'])?$_arr_datas['categories']:0;
			$_manufacturers = isset($_arr_datas['manufacturers'])?$_arr_datas['manufacturers']:0;
			$_customfields = isset($_arr_datas['custom_id'])?$_arr_datas['custom_id']:0;
			$_price_min = isset($_arr_datas['ft_price_min'])?$_arr_datas['ft_price_min']:'';
			$_price_max = isset($_arr_datas['ft_price_max'])?$_arr_datas['ft_price_max']:'';
			$_orderby = isset($_arr_datas['orderby'])?$_arr_datas['orderby']:'ordering';
			$limit_result = $params->get ('limit_results',5) <= 0?5:$params->get ('limit_results',5);
			$_limit = isset($_arr_datas['limit']) && $_arr_datas['limit'] != ''?(int)$_arr_datas['limit']:$limit_result;
			$_start = isset($_arr_datas['limitstart']) && $_arr_datas['limitstart'] != ''?(int)$_arr_datas['limitstart']:0;

			if ($_categories == 0 && $_manufacturers == 0 && $_customfields == 0 && $_price_min == '' && $_price_max == ''){
				die (json_encode ('noresults'));
			}

			$productExtModel = new VirtuemartModelProductFilter();

			$productExtModel->filter_order = $_orderby;
			JRequest::setVar ('orderby',$_orderby);
			$productExtModel->updateRequests ();
			$virtuemart_category_id = $_categories;
			$productExtModel->virtuemart_manufacturer_id = $_manufacturers;
			$productExtModel->searchcustoms = '';
			$productExtModel->searchcustoms = $_customfields;
			$productExtModel->_virtuemart_product_price = ($_price_min == '' && $_price_max == '')?false:array($_price_min,$_price_max);
			$productExtModel->_limitStart = $_start;
			$productExtModel->_limit = $_limit;
			//$productExtModel->_noLimit = true;

			$ids = $productExtModel->sortSearchListQuery (true,$virtuemart_category_id);

			$result = new stdClass();

			//$path_template = JPATH_VM_SITE . DS . 'views' . DS . 'category' . DS . 'tmpl' . DS . 'default.php';
			$ratingModel = VmModel::getModel ('ratings');
			$showRating = $ratingModel->showRating ();
			$productExtModel->withRating = $showRating;
			$show_prices = VmConfig::get ('show_prices',1);
			$this->show_prices = $show_prices;
			vmJsApi::jPrice ();
			$this->showRating = $showRating;

			$this->products = $productExtModel->getProducts ($ids);

			$currency = CurrencyDisplay::getInstance ();
			$this->currency = $currency;
			$productExtModel->addImages ($this->products,1);
			$orderByList = $productExtModel->getOrderByList (0);
			if (isset($orderByList['manufacturer'])){
				$orderByList['manufacturer'] = '';
			}
			$this->orderByList = $orderByList;

			$this->perRow = VmConfig::get ('products_per_row',3);
			$productExtModel->_total = $productExtModel->getTotal ();
			$pagination = $productExtModel->getPagination ($this->perRow);
			//$pagination->set('pages.current',1);
			$this->vmPagination = $pagination;

			$this->category = new stdClass();
			$this->category->category_name = 'Results';
			$this->category->category_description = '';

			$this->category->limit_list_step = '0';
			$this->category->limit_list_initial = '0';

			foreach ($this->products as $item){
				$item->stock = $productExtModel->getStockIndicator ($item);
			}

			$this->showproducts = 1;
			$this->productsLayout = 'products';

			ob_start ();

			require JModuleHelper::getLayoutPath ($this->_module->module,'default'.'_results');
			$buffer = ob_get_contents ();
			$result->filter_product = preg_replace (
				array(
					'/ {2,}/',
					'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
				),
				array(
					' ',
					''
				),
				$buffer
			);
			ob_end_clean ();

			$list = $this->_productFiltering ($_arr_datas);

			ob_start ();
			require JModuleHelper::getLayoutPath ($this->_module->module,'default'.'_product_filter');
			$buffer2 = ob_get_contents ();
			$result->items_markup = preg_replace (
				array(
					'/ {2,}/',
					'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
				),
				array(
					' ',
					''
				),
				$buffer2
			);
			ob_end_clean ();
			die (json_encode ($result));
		}
	}

}

if (!class_exists ('VirtuemartModelProductFilter') && class_exists ('VirtueMartModelProduct')){
	class VirtuemartModelProductFilter extends VirtueMartModelProduct
	{
		var $_virtuemart_product_price = false;
		var $_limitStart = 0;
		var $_limit = 20;

		function sortSearchListQuery($onlyPublished = true,$virtuemart_category_id = false,$group = false,$nbrReturnProducts = false,$langFields = array())
		{
			$app = JFactory::getApplication ();
			$groupBy = ' group by p.`virtuemart_product_id` ';
			$joinCategory = false;
			$joinCatLang = false;
			$joinMf = false;
			$joinMfLang = false;
			$joinPrice = false;
			$joinCustom = false;
			$joinShopper = false;
			$joinChildren = false;
			$joinLang = false;
			$orderBy = ' ';
			$ff_select_price = '';
			$where = array();
			$useCore = true;
			if ($this->searchplugin !== 0){
				JPluginHelper::importPlugin ('vmcustom');
				$dispatcher = JDispatcher::getInstance ();
				$PluginJoinTables = array();
				$ret = $dispatcher->trigger ('plgVmAddToSearch',array(
					&$where,
					&$PluginJoinTables,
					$this->searchplugin
				))
				;
				foreach ($ret as $r){
					if (!$r){
						$useCore = false;
					}
				}
			}
			if ($useCore){
				$isSite = $app->isSite ();
				if (!empty($this->keyword) and $this->keyword !== '' and $group === false){
					$keyword = '"%'.str_replace (array(
							' ',
							'-'
						),'%',$this->_db->getEscaped ($this->keyword,true)).'%"';
					foreach ($this->valid_search_fields as $searchField){
						if ($searchField == 'category_name' || $searchField == 'category_description'){
							// $joinCategory = TRUE;
							$joinCatLang = true;
						}
						else if ($searchField == 'mf_name'){
							// $joinMf = TRUE;
							$joinMfLang = true;
						}
						else if ($searchField == 'product_price'){
							$joinPrice = true;
						}
						else if (!$joinLang and ($searchField == 'product_name' or $searchField == 'product_s_desc' or $searchField == 'product_desc' or $searchField == '`p`.product_sku' or $searchField == '`l`.slug')){
							$joinLang = true;
						}
						if (strpos ($searchField,'`') !== false){
							$keywords_plural = preg_replace ('/\s+/','%" AND '.$searchField.' LIKE "%',$keyword);
							$filter_search[] = $searchField.' LIKE '.$keywords_plural;
						}
						else{
							$keywords_plural = preg_replace ('/\s+/','%" AND `'.$searchField.'` LIKE "%',$keyword);
							$filter_search[] = '`'.$searchField.'` LIKE '.$keywords_plural;
							// $filter_search[] = '`' . $searchField . '` LIKE ' . $keyword;
						}
					}

					if (!empty($filter_search)){
						$where[] = '('.implode (' OR ',$filter_search).')';
					}
					else{
						$where[] = '`product_name` LIKE '.$keyword;
						$joinLang = true;
						// If they have no check boxes selected it will default to product name at least.
					}
				}

				// 		vmdebug('my $this->searchcustoms ',$this->searchcustoms);
				if (!empty($this->searchcustoms) && $this->searchcustoms != 0){
					$joinCustom = true;
					$custom_search = array();
					foreach ($this->searchcustoms as $key => $searchcustom){
						foreach ($searchcustom as $searchcust){
							$custom_search[] = '(pf.`virtuemart_custom_id`="'.(int)$key.'" and pf.`customfield_value` = "'.$this->_db->escape ($searchcust,true).'" )';
						}
					}
					if (!empty($custom_search)){
						$where[] = " ( ".implode (' OR ',$custom_search)." ) ";
					}

				}

				if ($onlyPublished){
					$where[] = ' p.`published`="1" ';
				}
				if ($isSite and !VmConfig::get ('use_as_catalog',0)){
					if (VmConfig::get ('stockhandle','none') == 'disableit_children'){
						$where[] = ' ((p.`product_in_stock` - p.`product_ordered`) >"0" OR (children.`product_in_stock` - children.`product_ordered`) > "0") ';
						$joinChildren = true;
					}
					else if (VmConfig::get ('stockhandle','none') == 'disableit'){
						$where[] = ' p.`product_in_stock` - p.`product_ordered` >"0" ';
					}
				}
				if ($virtuemart_category_id > 0){
					$joinCategory = true;
					if (is_string ($virtuemart_category_id) && preg_match ('/[\s|,]+/',$virtuemart_category_id)){
						$virtuemart_category_id = preg_split ('/[\s|,]+/',$virtuemart_category_id);
					}
					if (!is_array ($virtuemart_category_id)){
						settype ($virtuemart_category_id,'array');
					}

					$where[] = ' `pc`.`virtuemart_category_id` IN ('.implode (', ',$virtuemart_category_id).')';

				}
				else if ($isSite and !VmConfig::get ('show_uncat_child_products',true)){
					$joinCategory = true;
					$where[] = ' `pc`.`virtuemart_category_id` > 0 ';
				}
				if ($this->product_parent_id){
					$where[] = ' p.`product_parent_id` = '.$this->product_parent_id;
				}
				if ($isSite){
					$usermodel = VmModel::getModel ('user');
					$currentVMuser = $usermodel->getUser ();
					$virtuemart_shoppergroup_ids = (array)$currentVMuser->shopper_groups;
					if (is_array ($virtuemart_shoppergroup_ids)){
						$sgrgroups = array();
						foreach ($virtuemart_shoppergroup_ids as $key => $virtuemart_shoppergroup_id){
							$sgrgroups[] = '  `ps`.`virtuemart_shoppergroup_id`= "'.(int)$virtuemart_shoppergroup_id.'" ';
						}
						$sgrgroups[] = ' `ps`.`virtuemart_shoppergroup_id` IS NULL ';
						$where[] = " ( ".implode (' OR ',$sgrgroups)." ) ";
						$joinShopper = true;
					}
				}

				if ($this->virtuemart_manufacturer_id){
					$joinMf = true;
					$_virtuemart_manufacturer_id = $this->virtuemart_manufacturer_id;
					if (is_string ($_virtuemart_manufacturer_id) && preg_match ('/[\s|,]+/',$_virtuemart_manufacturer_id)){
						$_virtuemart_manufacturer_id = preg_split ('/[\s|,]+/',$_virtuemart_manufacturer_id);
					}
					if (!is_array ($_virtuemart_manufacturer_id)){
						settype ($_virtuemart_manufacturer_id,'array');
					}

					$where[] = ' `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` IN ('.implode (',',$_virtuemart_manufacturer_id).') ';
				}

				if ($this->_virtuemart_product_price){
					$joinPrice = true;
					$_mf_price = $this->_virtuemart_product_price;
					$_mf_price_min = $_mf_price[0];
					$_mf_price_max = $_mf_price[1];
					if ($_mf_price_min != '' && $_mf_price_max != ''){
						$where[] = '( pp.`product_price` >= '.$_mf_price_min.' AND pp.`product_price` <= '.$_mf_price_max.'  )';
					}

					if ($_mf_price_min == '' && $_mf_price_max != ''){
						$where[] = '( pp.`product_price` <= '.$_mf_price_max.'  )';
					}

					if ($_mf_price_min != '' && $_mf_price_max == ''){
						$where[] = '( pp.`product_price` >= '.$_mf_price_min.' )';
					}

					//$where[] = ' `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` = ' . $this->virtuemart_manufacturer_id;
				}

				// Time filter
				if ($this->search_type != ''){
					$search_order = $this->_db->getEscaped (JRequest::getWord ('search_order') == 'bf'?'<':'>');
					switch ($this->search_type){
						case 'parent':
							$where[] = 'p.`product_parent_id` = "0"';
							break;
						case 'product':
							$where[] = 'p.`modified_on` '.$search_order.' "'.$this->_db->getEscaped (JRequest::getVar ('search_date')).'"';
							break;
						case 'price':
							$joinPrice = true;
							$where[] = 'pp.`modified_on` '.$search_order.' "'.$this->_db->getEscaped (JRequest::getVar ('search_date')).'"';
							break;
						case 'withoutprice':
							$joinPrice = true;
							$where[] = 'pp.`product_price` IS NULL';
							break;
						case 'stockout':
							$where[] = ' p.`product_in_stock`- p.`product_ordered` < 1';
							break;
						case 'stocklow':
							$where[] = 'p.`product_in_stock`- p.`product_ordered` < p.`low_stock_notification`';
							break;
					}
				}
				// special  orders case
				// vmdebug('my filter ordering ',$this->filter_order);
				//$this->filter_order = 'product_price';
				switch ($this->filter_order){
					case '`p`.product_special':
						if ($isSite){
							$where[] = ' p.`product_special`="1" '; // TODO Change  to  a  individual button
							$orderBy = 'ORDER BY RAND()';
						}
						else{
							$orderBy = 'ORDER BY p.`product_special`';
						}
						break;
					case 'category_name':
						$orderBy = ' ORDER BY `category_name` ';
						$joinCategory = true;
						$joinCatLang = true;
						break;
					case 'category_description':
						$orderBy = ' ORDER BY `category_description` ';
						$joinCategory = true;
						$joinCatLang = true;
						break;
					case 'mf_name':
						$orderBy = ' ORDER BY `mf_name` ';
						$joinMf = true;
						$joinMfLang = true;
						break;
					case 'pc.ordering':
						$orderBy = ' ORDER BY `pc`.`ordering` ';
						$joinCategory = true;
						break;
					case 'product_price':
						// $filters[] = 'p.`virtuemart_product_id` = p.`virtuemart_product_id`';
						// $orderBy = ' ORDER BY `product_price` ';
						// $orderBy = ' ORDER BY `ff_final_price`, `product_price` ';
						$orderBy = ' ORDER BY `product_price` ';
						$ff_select_price = ' , IF(pp.override, pp.product_override_price, pp.product_price) as product_price ';
						$joinPrice = true;
						break;
					case 'created_on':
					case '`p`.created_on':
						$orderBy = ' ORDER BY p.`created_on` ';
						break;
					default;
						if (!empty($this->filter_order)){
							$orderBy = ' ORDER BY '.$this->filter_order.' ';
						}
						else{
							$this->filter_order_Dir = '';
						}
						break;
				}
				// Group case from the modules
				if ($group){
					$latest_products_days = VmConfig::get ('latest_products_days',7);
					$latest_products_orderBy = VmConfig::get ('latest_products_orderBy','created_on');
					$groupBy = 'group by p.`virtuemart_product_id` ';
					switch ($group){
						case 'featured':
							$where[] = 'p.`product_special`="1" ';
							$orderBy = 'ORDER BY RAND() ';
							break;
						case 'latest':
							$date = JFactory::getDate (time () - (60 * 60 * 24 * $latest_products_days));
							$dateSql = $date->toMySQL ();
							// $where[] = 'p.`' . $latest_products_orderBy . '` > "' . $dateSql . '" ';
							$orderBy = 'ORDER BY p.`'.$latest_products_orderBy.'`';
							$this->filter_order_Dir = 'DESC';
							break;
						case 'random':
							$orderBy = ' ORDER BY RAND() '; //LIMIT 0, '.(int)$nbrReturnProducts ; //TODO set limit LIMIT 0, '.(int)$nbrReturnProducts;
							break;
						case 'topten':
							$orderBy = ' ORDER BY p.`product_sales` '; //LIMIT 0, '.(int)$nbrReturnProducts;  //TODO set limitLIMIT 0, '.(int)$nbrReturnProducts;
							$joinPrice = true;
							$where[] = 'pp.`product_price`>"0.0" ';
							$this->filter_order_Dir = 'DESC';
							break;
						case 'recent':
							$rSession = JFactory::getSession ();
							$rIds = $rSession->get ('vmlastvisitedproductids',array(),'vm'); // get recent viewed from browser session
							return $rIds;
					}
					$this->searchplugin = false;
				}
			}
			$joinedTables = array();
			// This option switches between showing products without the selected language or only products with language.
			if ($app->isSite () and !VmConfig::get ('prodOnlyWLang',true)){
				// Maybe we have to join the language to order by product name, description, etc,...
				if (!$joinLang){
					$productLangFields = array(
						'product_s_desc',
						'product_desc',
						'product_name',
						'metadesc',
						'metakey',
						'slug'
					);
					foreach ($productLangFields as $field){
						if (strpos ($orderBy,$field,6) !== false){
							$joinLang = true;
							break;
						}
					}
				}
			}
			else{
				$joinLang = true;
			}
			$select = ' p.`virtuemart_product_id`'.$ff_select_price.' FROM `#__virtuemart_products` as p ';
			if ($joinLang){
				$joinedTables[] = ' INNER JOIN `#__virtuemart_products_'.VmConfig::$vmlang.'` as l using (`virtuemart_product_id`)';
			}
			if ($joinShopper == true){
				$joinedTables[] = ' LEFT JOIN `#__virtuemart_product_shoppergroups` as ps ON p.`virtuemart_product_id` = `ps`.`virtuemart_product_id` ';
				// $joinedTables[] = ' LEFT OUTER JOIN `#__virtuemart_shoppergroups` as s ON s.`virtuemart_shoppergroup_id` = `#__virtuemart_product_shoppergroups`.`virtuemart_shoppergroup_id` ';
			}
			if ($joinCategory == true or $joinCatLang){
				$joinedTables[] = ' LEFT JOIN `#__virtuemart_product_categories` as pc ON p.`virtuemart_product_id` = `pc`.`virtuemart_product_id` ';
				if ($joinCatLang){
					$joinedTables[] = ' LEFT JOIN `#__virtuemart_categories_'.VMLANG.'` as c ON c.`virtuemart_category_id` = `pc`.`virtuemart_category_id`';
				}
			}
			if ($joinMf == true or $joinMfLang){
				$joinedTables[] = ' LEFT JOIN `#__virtuemart_product_manufacturers` ON p.`virtuemart_product_id` = `#__virtuemart_product_manufacturers`.`virtuemart_product_id` ';
				if ($joinMfLang){
					$joinedTables[] = 'LEFT JOIN `#__virtuemart_manufacturers_'.VMLANG.'` as m ON m.`virtuemart_manufacturer_id` = `#__virtuemart_product_manufacturers`.`virtuemart_manufacturer_id` ';
				}
			}
			if ($joinPrice == true){
				$joinedTables[] = ' LEFT JOIN `#__virtuemart_product_prices` as pp ON p.`virtuemart_product_id` = pp.`virtuemart_product_id` ';
			}
			if ($this->searchcustoms){
				$joinedTables[] = ' LEFT JOIN `#__virtuemart_product_customfields` as pf ON p.`virtuemart_product_id` = pf.`virtuemart_product_id` ';
			}
			if ($this->searchplugin !== 0){
				if (!empty($PluginJoinTables)){
					$plgName = $PluginJoinTables[0];
					$joinedTables[] = ' LEFT JOIN `#__virtuemart_product_custom_plg_'.$plgName.'` as '.$plgName.' ON '.$plgName.'.`virtuemart_product_id` = p.`virtuemart_product_id` ';
				}
			}
			if ($joinChildren){
				$joinedTables[] = ' LEFT OUTER JOIN `#__virtuemart_products` children ON p.`virtuemart_product_id` = children.`product_parent_id` ';
			}
			if (count ($where) > 0){
				$whereString = ' WHERE ('.implode ("\n AND ",$where).') ';
			}
			else{
				$whereString = '';
			}
			$this->orderByString = $orderBy;
			if ($this->_onlyQuery){
				return (array(
					$select,
					$joinedTables,
					$where,
					$orderBy,
					$joinLang
				));
			}
			$joinedTables = " \n".implode (" \n",$joinedTables);
			$product_ids = $this->exeSortSearchListQuery (2,$select,$joinedTables,$whereString,$groupBy,$orderBy,$this->filter_order_Dir,$nbrReturnProducts);

			return $product_ids;
		}

		public function setPaginationLimits()
		{
			return array($this->_limitStart,$this->_limit);
		}

	}


}

