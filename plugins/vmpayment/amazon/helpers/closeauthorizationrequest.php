<?php

defined('_JEXEC') or die('Direct Access to ' . basename(__FILE__) . 'is not allowed.');

/**
 *
 * @package    VirtueMart
 * @subpackage vmpayment
 * @version $Id$
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - September 18 2015 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 */
class amazonHelperCloseAuthorizationRequest extends amazonHelper {

	public function __construct (OffAmazonPaymentsService_Model_CloseAuthorizationRequest $closeAuthorizationRequest, $method) {
		parent::__construct($closeAuthorizationRequest, $method);
	}



	function getContents () {

		$contents = $this->tableStart("captureRequest");
		$contents .= $this->getRow("Dump: ", var_export($this->amazonData, true));
		$contents .= $this->tableEnd();

		return $contents;
	}


}