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

class frequencyType{
	var $valuesEvery = array();
	var $valuesFrequency = array();
	var $valuesOnThe = array();
	var $valuesOnTheDay = array();

	var $txtDays = array();
	var $days = array();
	var $txtPos = array();

	function frequencyType(){
		$this->txtDays = array(JText::_('MONDAY'), JText::_('TUESDAY'), JText::_('WEDNESDAY'), JText::_('THURSDAY'), JText::_('FRIDAY'), JText::_('SATURDAY'), JText::_('SUNDAY'));
		$this->txtPos = array(JText::_('FREQUENCY_FIRST'), JText::_('FREQUENCY_SECOND'), JText::_('FREQUENCY_THIRD'), JText::_('FREQUENCY_LAST'));
		$this->days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

		$js = "function updateFrequency(){";
			$js .= "frequencyType = window.document.getElementById('frequencyType');";
			$js .= "everyFields = window.document.getElementById('everyFields');";
			$js .= "onTheFields = window.document.getElementById('onTheFields');";
			$js .= "delayvar = window.document.getElementById('delayvar');";
			$js .= "if(frequencyType.value == 'asap'){";
				$js .= "everyFields.style.display='none'; onTheFields.style.display='none';";
			$js .= "}";
			$js .= "if(frequencyType.value == 'onthe'){";
				$js .= "everyFields.style.display='none'; onTheFields.style.display='inline';";
			$js .= "}";
			$js .= "if(frequencyType.value == 'every'){";
				$js .= "everyFields.style.display='inline'; onTheFields.style.display='none';";
			$js .= "}";
			$js .= "updateDelay();";
		$js .= '}';

		$js .= "function updateDelay(){";
			$js .= "frequencyType = window.document.getElementById('frequencyType');";
			$js .= "delayvar = window.document.getElementById('delayvar');";
			$js .= "if(frequencyType.value == 'asap'){";
				$js .= "delayvar.value = 0;";
			$js .='}';
			$js .= "if(frequencyType.value == 'onthe'){";
				$js .= "valuesOnThe = window.document.getElementById('valuesOnThe').value;";
				$js .= "valuesOnTheDay = window.document.getElementById('valuesOnTheDay').value;";
				$js .= "delayvar.value = valuesOnThe+'_'+valuesOnTheDay;";
			$js .='}';
			$js .= "if(frequencyType.value == 'every'){";
				$js .= "delaytype = window.document.getElementById('delaytype').value;";
				$js .= "delayvalue = window.document.getElementById('delayvalue');";
				$js .= "realValue = delayvalue.value;";
				$js .= "if(delaytype == 'minute'){realValue = realValue*60; }";
				$js .= "if(delaytype == 'hour'){realValue = realValue*3600; }";
				$js .= "if(delaytype == 'day'){realValue = realValue*86400; }";
				$js .= "if(delaytype == 'week'){realValue = realValue*604800; }";
				$js .= "if(delaytype == 'month'){realValue = realValue*2592000; }";
				$js .= "delayvar.value = realValue;";
			$js .='}';
		$js .= '}';

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );
	}

	function displayFrequency($map,$value,$type = 1){
		if(empty($value) || (is_numeric($value) && $value == 0) || (!is_numeric($value) && strpos($value, '_') === false)){
			$defaultVal = 'asap';
			$styleEvery = 'style="display:none"';
			$styleOnThe = 'style="display:none"';
			$value_array = array('first','Monday');
		} elseif(is_numeric($value)){
			$defaultVal = 'every';
			$styleEvery = '';
			$styleOnThe = 'style="display:none"';
			$value_array = array('first','Monday');
		} else{
			$defaultVal = 'onthe';
			$styleEvery = 'style="display:none"';
			$styleOnThe = '';
			$value_array = explode('_', $value);
		}
		$this->valuesFrequency[] = JHTML::_('select.option', 'asap', JText::_('ACY_ASAP'));
		$this->valuesFrequency[] = JHTML::_('select.option', 'onthe', JText::_('ACY_ONTHE'));
		$this->valuesFrequency[] = JHTML::_('select.option', 'every', JText::_('EVERY'));
		$returnFrequency = JHTML::_('select.genericlist', $this->valuesFrequency, 'frequencyType', 'class="inputbox" size="1" onchange="updateFrequency();" style="width:160px"', 'value', 'text', $defaultVal);

		$this->valuesEvery[] = JHTML::_('select.option', 'hour', JText::_('HOURS'));
		$this->valuesEvery[] = JHTML::_('select.option', 'day', JText::_('DAYS'));
		$this->valuesEvery[] = JHTML::_('select.option', 'week', JText::_('WEEKS'));
		$this->valuesEvery[] = JHTML::_('select.option', 'month', JText::_('MONTHS'));
		$return = $this->get($value,$type);
		$everyValue = '<input class="inputbox" onchange="updateDelay();" type="text" id="delayvalue" style="width:50px" value="'.$return->value.'" /> ';
		$everyType = JHTML::_('select.genericlist', $this->valuesEvery, 'delaytype', 'class="inputbox" size="1" style="width:100px" onchange="updateDelay();"', 'value', 'text', $return->type ,'delaytype');
		$everyFields = '<span id="everyFields" ' . $styleEvery . '>' . $everyValue . $everyType . '</span>';

		$this->valuesOnThe[] = JHTML::_('select.option', 'first', $this->txtPos[0]);
		$this->valuesOnThe[] = JHTML::_('select.option', 'second', $this->txtPos[1]);
		$this->valuesOnThe[] = JHTML::_('select.option', 'third', $this->txtPos[2]);
		$this->valuesOnThe[] = JHTML::_('select.option', 'last', $this->txtPos[3]);
		$onTheNumber = JHTML::_('select.genericlist', $this->valuesOnThe, 'valuesOnThe', 'class="inputbox" size="1" onchange="updateDelay();" style="width:80px;"', 'value', 'text', $value_array[0]);
		for($i=0; $i<7;$i++){
			$this->valuesOnTheDay[] = JHTML::_('select.option', $this->days[$i], $this->txtDays[$i]);
		}
		$onTheDay = JHTML::_('select.genericlist', $this->valuesOnTheDay, 'valuesOnTheDay', 'class="inputbox" size="1" onchange="updateDelay();" style="width:120px;"', 'value', 'text', $value_array[1]);
		$onTheFields = '<span id="onTheFields" ' . $styleOnThe . '>' . $onTheNumber . $onTheDay . ' ' . JText::_('ACY_DAYOFMONTH'). '</span>';

		$delayVar = '<input type="hidden" name="'.$map.'" id="delayvar" value="'.$value.'" />';
		return $returnFrequency . $onTheFields . $everyFields . $delayVar;
	}

	function get($value,$type){
		$return = new stdClass();

		if(!is_numeric($value)){
			$return->value = 0;
			$return->type = 'hour';
			return $return;
		}

		$return->value = $value;
		if($type == 0){
			$return->type = 'second';
		}else{
			$return->type = 'minute';
		}

		if($return->value >= 60  AND $return->value%60 == 0){
			$return->value = (int) $return->value / 60;
			$return->type = 'minute';
			if($type != 0 AND $return->value >=60 AND $return->value%60 == 0){
				$return->type = 'hour';
				$return->value = $return->value / 60;
				if($type != 2 AND $return->value >=24 AND $return->value%24 == 0){
					$return->type = 'day';
					$return->value = $return->value / 24;
					if($type >= 3 AND $return->value >=30 AND $return->value%30 == 0){
						$return->type = 'month';
						$return->value = $return->value / 30;
					}elseif($return->value >=7 AND $return->value%7 == 0){
						$return->type = 'week';
						$return->value = $return->value / 7;
					}
				}
			}
		}
		return $return;
	}

	function display($value){
		if(is_numeric($value)){
			if($value == 0){
				return JText::_('ACY_ASAP');
			} else{
				if(empty($value)) return JText::_('ACY_ASAP');
				$type = 'ACY_SECONDS';
				if($value >= 60  AND $value%60 == 0){
					$value = (int) $value / 60;
					$type = 'ACY_MINUTES';
					if($value >=60 AND $value%60 == 0){
						$type = 'HOURS';
						$value = $value/ 60;
						if($value >=24 AND $value%24 == 0){
							$type = 'DAYS';
							$value = $value / 24;
							if($value >= 30 AND $value%30 == 0){
								$type = 'MONTHS';
								$value = $value / 30;
							}elseif($value >=7 AND $value%7 == 0){
								$type = 'WEEKS';
								$value = $value / 7;
							}
						}
					}
				}
				return JText::_('EVERY') . ' ' . $value.' '.JText::_($type);
			}
		}

		$arrayValue = explode('_', $value);
		return JText::_('ACY_ONTHE') . ' ' . $arrayValue[0] . ' ' . $arrayValue[1] . ' ' . JText::_('ACY_DAYOFMONTH');
	}
}
?>
