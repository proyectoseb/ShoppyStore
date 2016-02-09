<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die;

if (class_exists('JFormField')){
	class JFormFieldPositions extends JFormField
	{
		
		/**
		 * Element name
		 *
		 * @access	protected
		 * @var		string
		 */
		var	$_name = 'YtPositions';
		function getInput( ) { 
			$db = JFactory::getDBO();
			$query = "SELECT DISTINCT position FROM #__modules ORDER BY position ASC";
			$db->setQuery($query);
			$groups = $db->loadObjectList();
			
			$groupHTML = array();	
			if ($groups && count ($groups)) {
				foreach ($groups as $v=>$t){
					$groupHTML[] = JHTML::_('select.option', $t->position, $t->position);
				}
			}
			$lists = JHTML::_('select.genericlist', $groupHTML, $this->name.'[]', ' multiple="multiple"  size="10" ', 'value', 'text', $this->value);
			
			return $lists;
		}
	}
}
