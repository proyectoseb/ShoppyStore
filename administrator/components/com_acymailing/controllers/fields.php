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

class FieldsController extends acymailingController{
	var $pkey = 'fieldid';
	var $table = 'fields';
	var $groupMap = '';
	var $groupVal = '';

	function listing(){
		if(!acymailing_level(3)){
			$acyToolbar = acymailing::get('helper.toolbar');
			$acyToolbar->setTitle(JText::_('EXTRA_FIELDS'), 'fields');
			$acyToolbar->display();
			acymailing_display(JText::_('ACY_CUSTOMFIELDS_UPGRADE').'<br /><br /><a target="_blank" href="'.ACYMAILING_REDIRECT.'acymailing-features">'.JText::_('ACY_FEATURES').'</a>','info');
			return;
		}

		return parent::listing();
	}

}
