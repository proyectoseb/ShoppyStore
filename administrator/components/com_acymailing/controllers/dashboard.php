<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class DashboardController extends acymailingController{

	var $aclCat = 'dashboard';

	function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('listing','display');

		$this->registerDefaultTask('listing');

	}

	function display($cachable = false, $urlparams = false){
		if(!empty($this->aclCat) AND !$this->isAllowed($this->aclCat,'manage')) return;
		return parent::display($cachable, $urlparams);
	}
}
