<?php
/**
*
* calc_manufacturers table ( to map calc rules to manufacturers)
*
* @package	VirtueMart
* @subpackage Calculation tool
* @author Max Milbers, Modified by <mediaDESIGN> St.Kraft 2013-02-24 Herstellerrabatt
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2013 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: calc_shoppergroups.php 3002 2011-04-08 12:35:45Z alatak $
*/

defined('_JEXEC') or die();

if(!class_exists('VmTableXarray'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmtablexarray.php');

class TableCalc_manufacturers extends VmTableXarray {

	function __construct(&$db){
		parent::__construct('#__virtuemart_calc_manufacturers', 'id', $db);

		$this->setPrimaryKey('virtuemart_calc_id');
		$this->setSecondaryKey('virtuemart_manufacturer_id');
	}

}
