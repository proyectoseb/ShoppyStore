<?php

defined('_JEXEC') or die('Direct Access to ' . basename(__FILE__) . 'is not allowed.');

/**
 *
 * @package    VirtueMart
 * @subpackage vmpayment
 * @version $Id: confirmorderreferencerequest.php 8949 2015-08-12 08:30:57Z alatak $
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - September 18 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 */
class amazonHelperConfirmOrderReferenceRequest extends amazonHelper {

	public function __construct (OffAmazonPaymentsService_Model_ConfirmOrderReferenceRequest $confirmOrderReferenceRequest, $method) {
		parent::__construct($confirmOrderReferenceRequest, $method);
	}






	function getContents () {
		$contents = $this->tableStart("ConfirmOrderReferenceRequest");
		$contents .= $this->getRow("Dump: ", var_export($this->amazonData, true));

		$contents .= $this->tableEnd();
		return $contents;
	}

}