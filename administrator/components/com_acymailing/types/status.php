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

class statusType{
	function statusType(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '-1', JText::_('UNSUBSCRIBED') );
		$this->values[] = JHTML::_('select.option', '0', JText::_('NO_SUBSCRIPTION') );
		$this->values[] = JHTML::_('select.option', '2', JText::_('PENDING_SUBSCRIPTION') );
		$this->values[] = JHTML::_('select.option', '1', JText::_('SUBSCRIBED') );
	}

	function display($map,$value){
		static $i = 0;
		return JHTML::_('acyselect.radiolist', $this->values, $map , 'class="radiobox" size="1"', 'value', 'text', (int) $value,'status'.$i++);
	}

}
