<?php
/**
 *
 * Orders controller
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: orders.php 8933 2015-07-30 10:17:11Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Orders Controller
 *
 * @package    VirtueMart
 * @author
 */
class VirtuemartControllerOrders extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access	public
	 * @author
	 */
	function __construct() {
		VmConfig::loadJLang('com_virtuemart_orders',TRUE);
		parent::__construct();

	}

	/**
	 * Shows the order details
	 */
	public function edit($layout='order'){

		parent::edit($layout);
	}

	public function updateCustomsOrderItems(){

		$q = 'SELECT `product_attribute` FROM `#__virtuemart_order_items` LIMIT ';
		$do = true;
		$db = JFactory::getDbo();
		$start = 0;
		$hunk  = 1000;
		while($do){
			$db->setQuery($q.$start.','.$hunk);
			$items = $db->loadColumn();
			if(!$items){
				vmdebug('updateCustomsOrderItems Reached end after '.$start/$hunk.' loops');
				break;
			}
			//The stored result in vm2.0.14 looks like this {"48":{"textinput":{"comment":"test"}}}
			//{"96":"18"} download plugin
			// 46 is virtuemart_customfield_id
			//{"46":" <span class=\"costumTitle\">Cap Size<\/span><span class=\"costumValue\" >S<\/span>","110":{"istraxx_customsize":{"invala":"10","invalb":"10"}}}
			//and now {"32":[{"invala":"100"}]}
			foreach($items as $field){
				if(strpos($field,'{')!==FALSE){
					$jsField = json_decode($field);
					$fieldProps = get_object_vars($jsField);
					vmdebug('updateCustomsOrderItems',$fieldProps);
					$nJsField = array();
					foreach($fieldProps as $k=>$props){
						if(is_object($props)){

							$props = (array)$props;
							foreach($props as $ke=>$prop){
								if(!is_numeric($ke)){
									vmdebug('Found old param style',$ke,$prop);
									if(is_object($prop)){
										$prop = (array)$prop;
										$nJsField[$k] = $prop;
										/*foreach($prop as $name => $propvalue){
											$nJsField[$k][$name] = $propvalue;
										}*/
									}
								}
								 else {
									//$nJsField[$k][$name] = $prop;
								}
							}
						} else {
							if(is_numeric($k) and is_numeric($props)){
							$nJsField[$props] = $k;
							} else {
								$nJsField[$k] = $props;
							}
						}
					}
					$nJsField = vmJsApi::safe_json_encode($nJsField);
					vmdebug('updateCustomsOrderItems json $field encoded',$field,$nJsField);
				} else {
					vmdebug('updateCustomsOrderItems $field',$field);
				}

			}
			if(count($items)<$hunk){
				vmdebug('Reached end');
				break;
			}
			$start += $hunk;
		}
		// Create the view object
		$view = $this->getView('orders', 'html');
		$view->display();
	}

	/**
	 * NextOrder
	 * renamed, the name was ambigous notice by Max Milbers
	 * @author Kohl Patrick
	 */
	public function nextItem($dir = 'ASC'){
		$model = VmModel::getModel('orders');
		$id = vRequest::getInt('virtuemart_order_id');
		if (!$order_id = $model->getOrderId($id, $dir)) {
			$order_id  = $id;
			$msg = vmText::_('COM_VIRTUEMART_NO_MORE_ORDERS');
		} else {
			$msg ='';
		}
		$this->setRedirect('index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='.$order_id ,$msg );
	}

	/**
	 * NextOrder
	 * renamed, the name was ambigous notice by Max Milbers
	 * @author Kohl Patrick
	 */
	public function prevItem(){

		$this->nextItem('DESC');
	}
	/**
	 * Generic cancel task
	 *
	 * @author Max Milbers
	 */
	public function cancel(){
		// back from order
		$this->setRedirect('index.php?option=com_virtuemart&view=orders' );
	}
	/**
	 * Shows the order details
	 * @deprecated
	 */
	public function editOrderStatus() {

		$view = $this->getView('orders', 'html');

		if($this->getPermOrderStatus()){
			$model = VmModel::getModel('orders');
			$model->updateOrderStatus();
		} else {
			vmInfo('Restricted');
		}

		$view->display();
	}

	function getPermOrderStatus(){

		if(vmAccess::manager('orders.status')){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Update an order status
	 *
	 * @author Max Milbers
	 */
	public function updatestatus() {

		$app = Jfactory::getApplication();
		$lastTask = vRequest::getCmd('last_task');

		/* Load the view object */
		$view = $this->getView('orders', 'html');

		if(!$this->getPermOrderStatus()){
			vmInfo('Restricted');
			$view->display();
			return true;
		}

		/* Update the statuses */
		$model = VmModel::getModel('orders');

		if ($lastTask == 'updatestatus') {
			// single order is in POST but we need an array
			$order = array() ;
			$virtuemart_order_id = vRequest::getInt('virtuemart_order_id');
			$order[$virtuemart_order_id] = (vRequest::getRequest());

			$result = $model->updateOrderStatus($order);
		} else {
			$result = $model->updateOrderStatus();
		}

		$msg='';
		if ($result['updated'] > 0)
		$msg = vmText::sprintf('COM_VIRTUEMART_ORDER_UPDATED_SUCCESSFULLY', $result['updated'] );
		else if ($result['error'] == 0)
		$msg .= vmText::_('COM_VIRTUEMART_ORDER_NOT_UPDATED');
		if ($result['error'] > 0)
		$msg .= vmText::sprintf('COM_VIRTUEMART_ORDER_NOT_UPDATED_SUCCESSFULLY', $result['error'] , $result['total']);
		if ('updatestatus'== $lastTask ) {
			$app->redirect('index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='.$virtuemart_order_id , $msg);
		}
		else {
			$app->redirect('index.php?option=com_virtuemart&view=orders', $msg);
		}
	}


	/**
	 * Save changes to the order item status
	 *
	 */
	public function saveItemStatus() {

		$mainframe = Jfactory::getApplication();

		$data = vRequest::getRequest();
		$model = VmModel::getModel();
		$model->updateItemStatus(JArrayHelper::toObject($data), $data['new_status']);

		$mainframe->redirect('index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='.$data['virtuemart_order_id']);
	}


	/**
	 * Display the order item details for editing
	 */
	public function editOrderItem() {

		vRequest::setVar('layout', 'orders_editorderitem');

		parent::display();
	}


	/**
	 * correct position, working with json? actually? WHat ist that?
	 *
	 * Get a list of related products
	 * @author Max Milbers
	 */
	public function getProducts() {

		$view = $this->getView('orders', 'json');
		$view->setLayout('orders_editorderitem');

		$view->display();
	}


	/**
	 * Update status for the selected order items
	 */
	public function updateOrderItemStatus()
	{

		$mainframe = Jfactory::getApplication();
		$model = VmModel::getModel();


		$_items = vRequest::getVar('item_id',  0, '', 'array');

		$_orderID = vRequest::getInt('virtuemart_order_id', false);
		$model->updateStatusForOneOrder($_orderID,$_items,true);

		$model->deleteInvoice($_orderID);
		$mainframe->redirect('index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='.$_orderID);
	}

	public function updateOrderHead()
	{
		$mainframe = Jfactory::getApplication();
		$model = VmModel::getModel();
		$_items = vRequest::getVar('item_id',  0, '', 'array');
		$_orderID = vRequest::getInt('virtuemart_order_id', '');
		$model->UpdateOrderHead((int)$_orderID, vRequest::getRequest());
		$model->deleteInvoice($_orderID);
		$mainframe->redirect('index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='.$_orderID);
	}

	public function CreateOrderHead()
	{
		$mainframe = Jfactory::getApplication();
		$model = VmModel::getModel();
		$orderid = $model->CreateOrderHead();
		$mainframe->redirect('index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='.$orderid );
	}

	public function newOrderItem() {

		$orderId = vRequest::getInt('virtuemart_order_id', '');
		$model = VmModel::getModel();
		$msg = '';
		$data = vRequest::getRequest();
		$model->saveOrderLineItem($data);
		$model->deleteInvoice($orderId);
		$editLink = 'index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id=' . $orderId;
		$this->setRedirect($editLink, $msg);
	}

	/**
	 * Removes the given order item
	 */
	public function removeOrderItem() {

		$model = VmModel::getModel();
		$msg = '';
		$orderId = vRequest::getInt('orderId', '');
		// TODO $orderLineItem as int ???
		$orderLineItem = vRequest::getVar('orderLineId', '');

		$model->removeOrderLineItem($orderLineItem);

		$model->deleteInvoice($orderId);
		$editLink = 'index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id=' . $orderId;
		$this->setRedirect($editLink, $msg);
	}

}
// pure php no closing tag

