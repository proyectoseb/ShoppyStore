<?php
/**
 *
 * Product controller
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Product Controller
 *
 * @package    VirtueMart
 * @author
 */
class VirtuemartControllerProduct extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		parent::__construct('virtuemart_product_id');
		$this->addViewPath( VMPATH_ADMIN . DS . 'views');
	}


	/**
	 * Shows the product add/edit screen
	 */
	public function edit($layout='edit') {
		parent::edit('product_edit');
	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		if($data===0)$data = vRequest::getRequest();

		if(vmAccess::manager('raw')){
			$data['product_desc'] = vRequest::get('product_desc','');
			$data['product_s_desc'] = vRequest::get('product_s_desc','');
			$data['customtitle'] = vRequest::get('customtitle','');

			if(isset($data['field'])){
				$data['field'] = vRequest::get('field');
			}

			if(isset($data['childs'])){
				foreach($data['childs'] as $k=>$v){
					if($n = vRequest::get('product_name',false,FILTER_UNSAFE_RAW,FILTER_FLAG_NO_ENCODE,$data['childs'][$k])){
						$data['childs'][$k]['product_name'] = $n;
					}
				}
			}

		} else  {
			if(vmAccess::manager('html')){
				$data['product_desc'] = vRequest::getHtml('product_desc','');
				$data['product_s_desc'] = vRequest::getHtml('product_s_desc','');
				$data['customtitle'] = vRequest::getHtml('customtitle','');

				if(isset($data['field'])){
					$data['field'] = vRequest::getHtml('field');
				}
			} else {
				$data['product_desc'] = vRequest::getString('product_desc','');
				$data['product_s_desc'] = vRequest::getString('product_s_desc','');
				$data['customtitle'] = vRequest::getString('customtitle','');

				if(isset($data['field'])){
					$data['field'] = vRequest::getString('field');
				}
			}

			//Why we have this?
			$multix = Vmconfig::get('multix','none');
			if( $multix != 'none' ){
				//in fact this shoudl be used, when the mode is administrated and the system is so that
				//every product must be approved by an admin.
				unset($data['published']);
				//unset($data['childs']);
			}

		}
		parent::save($data);
	}

	function saveJS(){

		vRequest::vmCheckToken();

		$model = VmModel::getModel($this->_cname);

		$data = vRequest::getRequest();
		$id = $model->store($data);

		$msg = 'failed';
		if(!empty($id)) {
			$msg = vmText::sprintf('COM_VIRTUEMART_STRING_SAVED',$this->mainLangKey);
			$type = 'message';
		}
		else $type = 'error';

		$json['msg'] = $msg;
		if ($id) {
			$json['product_id'] = $id;

			$json['ok'] = 1 ;
		} else {
			$json['ok'] = 0 ;

		}
		echo vmJsApi::safe_json_encode($json);
		jExit();

	}

	/**
	 * This task creates a child by a given product id
	 *
	 * @author Max Milbers
	 */
	public function createChild(){

		vRequest::vmCheckToken();

		$app = Jfactory::getApplication();

		$model = VmModel::getModel('product');

		$cids = vRequest::getInt($this->_cidName, vRequest::getint('virtuemart_product_id',false));
		if(!is_array($cids) and $cids > 0){
			$cids = array($cids);
		}
		$target = vRequest::getCmd('target',false);

		$msgtype = 'info';
		foreach($cids as $cid){
			if ($id=$model->createChild($cid)){
				$msg = vmText::_('COM_VIRTUEMART_PRODUCT_CHILD_CREATED_SUCCESSFULLY');


				if($target=='parent'){
					vmdebug('toParent');
					$redirect = 'index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$cids[0];
				} else {
					$redirect = 'index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$id;
				}

			} else {
				$msg = vmText::_('COM_VIRTUEMART_PRODUCT_NO_CHILD_CREATED_SUCCESSFULLY');
				$msgtype = 'error';
				$redirect = 'index.php?option=com_virtuemart&view=product';
			}
		}
		$app->redirect($redirect, $msg, $msgtype);

	}


	public function massxref_sgrps(){

		$this->massxref('massxref');
	}

	public function massxref_sgrps_exe(){

		$virtuemart_shoppergroup_ids = vRequest::getInt('virtuemart_shoppergroup_id');

		$session = JFactory::getSession();
		$cids = json_decode($session->get('vm_product_ids', array(), 'vm'),true);

		$productModel = VmModel::getModel('product');
		foreach($cids as $cid){
			$data = array('virtuemart_product_id' => $cid, 'virtuemart_shoppergroup_id' => $virtuemart_shoppergroup_ids);
			$data = $productModel->updateXrefAndChildTables ($data, 'product_shoppergroups');
		}

		$this->massxref('massxref_sgrps');
	}

	public function massxref_cats(){
		$this->massxref('massxref');
	}

	public function massxref_cats_exe(){

		$virtuemart_cat_ids = vRequest::getInt('cid', array() );

		$session = JFactory::getSession();
		$cids = json_decode($session->get('vm_product_ids', array(), 'vm'),true);

		$productModel = VmModel::getModel('product');
		foreach($cids as $cid){
			$data = array('virtuemart_product_id' => $cid, 'virtuemart_category_id' => $virtuemart_cat_ids);
			$data = $productModel->updateXrefAndChildTables ($data, 'product_categories',TRUE);
		}

		$this->massxref('massxref_cats');
	}

	public function massxref($layoutName){

		vRequest::vmCheckToken();

		$cids = vRequest::getInt('virtuemart_product_id');

		if(empty($cids)){
			$session = JFactory::getSession();
			$cids = json_decode($session->get('vm_product_ids', '', 'vm'),true);
		} else {
			$session = JFactory::getSession();
			$session->set('vm_product_ids', json_encode($cids),'vm');
			$session->set('reset_pag', true,'vm');

		}

		if(!empty($cids)){
			$q = 'SELECT `product_name` FROM `#__virtuemart_products_' . VmConfig::$vmlang . '` ';
			$q .= ' WHERE `virtuemart_product_id` IN (' . implode(',', $cids) . ')';

			$db = JFactory::getDbo();
			$db->setQuery($q);

			$productNames = $db->loadColumn();

			vmInfo('COM_VIRTUEMART_PRODUCT_XREF_NAMES',implode(', ',$productNames));
		}

		$this->addViewPath(VMPATH_ADMIN . DS . 'views');
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView($this->_cname, $viewType);

		$view->setLayout($layoutName);

		$view->display();
	}

	/**
	 * Clone a product
	 *
	 * @author Max Milbers
	 */
	public function CloneProduct() {
		$mainframe = Jfactory::getApplication();

		$view = $this->getView('product', 'html');

		$model = VmModel::getModel('product');
		$msgtype = '';

		$cids = vRequest::getInt($this->_cidName, vRequest::getInt('virtuemart_product_id'));

		foreach($cids as $cid){
			if ($model->createClone($cid)) {
				$msg = vmText::_('COM_VIRTUEMART_PRODUCT_CLONED_SUCCESSFULLY');
			} else {
				$msg = vmText::_('COM_VIRTUEMART_PRODUCT_NOT_CLONED_SUCCESSFULLY');
				$msgtype = 'error';
			}
		}

		$mainframe->redirect('index.php?option=com_virtuemart&view=product', $msg, $msgtype);
	}


	/**
	 * Get a list of related products, categories
	 * or customfields
	 * @author Max Milbers
	 * @author Kohl Patrick
	 */
	public function getData() {
		$view = $this->getView('product', 'json');
		$view->display(NULL);
	}

	/**
	 * Add a product rating
	 * @author Max Milbers
	 */
	public function addRating() {
		$mainframe = Jfactory::getApplication();

		// Get the product ID
		$cids = vRequest::getInt($this->_cidName, vRequest::getInt('virtuemart_product_id'));
		$mainframe->redirect('index.php?option=com_virtuemart&view=ratings&task=add&virtuemart_product_id='.$cids[0]);
	}


	public function ajax_notifyUsers(){

		$virtuemart_product_id = vRequest::getInt('virtuemart_product_id');
		if(is_array($virtuemart_product_id) and count($virtuemart_product_id) > 0){
			$virtuemart_product_id = (int)$virtuemart_product_id[0];
		} else {
			$virtuemart_product_id = (int)$virtuemart_product_id;
		}

		$subject = vRequest::getVar('subject', '');
		$mailbody = vRequest::getVar('mailbody',  '');
		$max_number = (int)vRequest::getVar('max_number', '');
		
		$waitinglist = VmModel::getModel('Waitinglist');
		$waitinglist->notifyList($virtuemart_product_id,$subject,$mailbody,$max_number);
		exit;
	}
	
	public function ajax_waitinglist() {

		$virtuemart_product_id = vRequest::getInt('virtuemart_product_id');
		if(is_array($virtuemart_product_id) && count($virtuemart_product_id) > 0){
			$virtuemart_product_id = (int)$virtuemart_product_id[0];
		} else {
			$virtuemart_product_id = (int)$virtuemart_product_id;
		}

		$waitinglistmodel = VmModel::getModel('waitinglist');
		$waitinglist = $waitinglistmodel->getWaitingusers($virtuemart_product_id);

		if(empty($waitinglist)) $waitinglist = array();
		
		echo vmJsApi::safe_json_encode($waitinglist);
		exit;

	}


}
// pure php no closing tag
