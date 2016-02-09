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

class detailstatsbounceType{
	function display($map,$value){
		$query = 'SELECT DISTINCT bouncerule FROM '.acymailing_table('userstats') .' WHERE bouncerule IS NOT NULL';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$bouncerules = $db->loadObjectList();
		if(empty($bouncerules)) return '';
		$valueBounce = array();
		$valueBounce[] = JHTML::_('select.option', 0, JText::_('ALL_RULES') );
		foreach($bouncerules as $oneRule){
			$valueBounce[] = JHTML::_('select.option', $oneRule->bouncerule, $oneRule->bouncerule);
		}
		return JHTML::_('select.genericlist', $valueBounce, $map, 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $value );
	}
}
