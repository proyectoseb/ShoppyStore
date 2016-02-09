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
JHTML::_('behavior.modal','a.modal');
if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php')){
	echo 'This module can not work without the AcyMailing Component';
}

if(!ACYMAILING_J16){

	class JElementTestplug extends JElement
	{
		function fetchElement($name, $value, &$node, $control_name)
		{
			$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=cpanel&amp;task=plgtrigger&amp;plg='.$value.'&amp;plgtype='.$name;
			return '<a class="modal" title="Click here"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}"><button class="btn" onclick="return false">Click here</button></a>';
		}
	}
}else{
	class JFormFieldTestplug extends JFormField
	{
		var $type = 'testplug';

		function getInput() {
			$link = 'index.php?option=com_acymailing&amp;tmpl=component&amp;ctrl=cpanel&amp;task=plgtrigger&amp;plg='.$this->value.'&amp;plgtype='.$this->fieldname;
			return '<a class="modal" title="Click here"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}"><button class="btn" onclick="return false">Click here</button></a>';
		}
	}
}
