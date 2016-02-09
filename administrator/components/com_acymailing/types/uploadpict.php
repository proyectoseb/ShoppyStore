<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.9.4
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class uploadpictType{
	function display($map, $mapDelete, $previous){
		$result = '<input type="file" name="pictures['.$mapDelete.']" style="width:auto;"/>';
		if(!empty($previous)){
			$result .='<img src="'.ACYMAILING_LIVE.$previous.'" style="float:left;max-height:50px;margin-right:10px;" />
			<br /><input type="checkbox" name="'.$map.'" value="" id="delete'.$mapDelete.'" /> <label for="delete'.$mapDelete.'">'.JText::_('DELETE_PICT').'</label>';
		}
		return $result;
	}
}
