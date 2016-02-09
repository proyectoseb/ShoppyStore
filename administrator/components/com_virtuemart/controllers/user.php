<?php
/**
*
* User controller
*
* @package	VirtueMart
* @subpackage User
* @author Oscar van Eijk
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: user.php 8970 2015-09-06 23:19:17Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Controller class for the user
 *
 * @package    	VirtueMart
 * @subpackage 	User
 * @author     	Oscar van Eijk
 * @author 		Max Milbers
 */
class VirtuemartControllerUser extends VmController {

	/**
	 * Method to display the view
	 *
	 * @access public
	 * @author
	 */
	function __construct(){

		parent::__construct('virtuemart_user_id');
	}

	/**
	 * Handle the edit task
	 */
	function edit($view=0){

		//We set here the virtuemart_user_id, when no virtuemart_user_id is set to 0, for adding a new user
		//In every other case the virtuemart_user_id is sent.
		$cid = vRequest::getVar('virtuemart_user_id');
		if(!isset($cid)) vRequest::setVar('virtuemart_user_id', (int)0);

		parent::edit('edit');
	}

	function addST(){

		$this->edit();
	}

	function removeAddressST(){

		$virtuemart_userinfo_id = vRequest::getInt('virtuemart_userinfo_id');
		$virtuemart_user_id = vRequest::getInt('virtuemart_user_id');

		//Lets do it dirty for now
		$userModel = VmModel::getModel('user');
		vmdebug('removeAddressST',$virtuemart_user_id,$virtuemart_userinfo_id);
		$userModel->setId($virtuemart_user_id[0]);
		$userModel->removeAddress($virtuemart_userinfo_id);

		$layout = vRequest::getCmd('layout','edit');
		$this->setRedirect( 'index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]='.$virtuemart_user_id[0] );
	}

	function editshop(){

		$user = JFactory::getUser();
		//the virtuemart_user_id var gets overriden in the edit function, when not set. So we must set it here
		vRequest::setVar('virtuemart_user_id', (int)$user->id);
		$this->edit();

	}
	function cancel(){

		$lastTask = vRequest::getCmd('last_task');
		if ($lastTask == 'edit_shop') $this->setRedirect('index.php?option=com_virtuemart');
		else $this->setRedirect('index.php?option=com_virtuemart&view=user');
	}

	/**
	 * Handle the save task
	 * Checks already in the controller the rights todo so and sets the data by filtering the post
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView('user', $viewType);

		$_currentUser = JFactory::getUser();
// TODO sortout which check is correctt.....
//		if (!$_currentUser->authorise('administration', 'manage', 'components', 'com_users')) {
		if (!vmAccess::manager('user.edit')) {
			$msg = vmText::_('_NOT_AUTH');
		} else {
			$model = VmModel::getModel('user');

			if($data===0) $data = vRequest::getRequest();

			// Store multiple selectlist entries as a ; separated string
			if (array_key_exists('vendor_accepted_currencies', $data) && is_array($data['vendor_accepted_currencies'])) {
			    $data['vendor_accepted_currencies'] = implode(',', $data['vendor_accepted_currencies']);
			}
			// TODO disallow vendor_store_name as HTML ?
			$data['vendor_store_name'] = vRequest::getHtml('vendor_store_name');
			$data['vendor_store_desc'] = vRequest::getHtml('vendor_store_desc');
			$data['vendor_terms_of_service'] = vRequest::getHtml('vendor_terms_of_service');
			$data['vendor_legal_info'] = vRequest::getHtml('vendor_legal_info');
			$data['vendor_letter_css'] = vRequest::getHtml('vendor_letter_css');
			$data['vendor_letter_header_html'] = vRequest::getHtml('vendor_letter_header_html');
			$data['vendor_letter_footer_html'] = vRequest::getHtml('vendor_letter_footer_html');

			$ret=$model->store($data);
			if(!$ret){
				$msg = '';
			} else {
				$msg = $ret['message'];
			}

		}
		$cmd = vRequest::getCmd('task');
		$lastTask = vRequest::getCmd('last_task');
		if($cmd == 'apply'){
			if ($lastTask == 'editshop') $redirection = 'index.php?option=com_virtuemart&view=user&task=editshop';
			else $redirection = 'index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]='.$ret['newId'];
		} else {
			if ($lastTask == 'editshop') $redirection = 'index.php?option=com_virtuemart';
			else $redirection = 'index.php?option=com_virtuemart&view=user';
		}
// 		$this->setRedirect($redirection, $ret['message']);
		$this->setRedirect($redirection);
	}


}

//No Closing tag
