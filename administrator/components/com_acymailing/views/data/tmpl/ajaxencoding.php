<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><span class="acyblocktitle"><?php echo JText::_('ACY_MATCH_DATA'); ?></span>
<?php
$config = acymailing_config();
$encodingHelper = acymailing_get('helper.encoding');
$filename = strtolower(JRequest::getCmd('filename'));
$encoding = JRequest::getCmd('encoding');
jimport('joomla.filesystem.file');
$extension = '.'.JFile::getExt($filename);
$uploadPath = ACYMAILING_MEDIA.'import'.DS.str_replace(array('.', ' '), '_', substr($filename, 0, strpos($filename, $extension))).$extension;

if(!file_exists($uploadPath)){
	acymailing_display(JText::sprintf('FAIL_OPEN', '<b><i>'.htmlspecialchars($uploadPath, ENT_COMPAT, 'UTF-8').'</i></b>'), 'error');
	return;
}
$this->config = acymailing_config();
$this->content = file_get_contents($uploadPath);
if(empty($encoding)){
	$encoding = $encodingHelper->detectEncoding($this->content);
}
$content = $encodingHelper->change($this->content, $encoding, 'UTF-8');

$content = str_replace(array("\r\n", "\r"), "\n", $content);
$this->lines = explode("\n", $content);

$this->separator = ',';
$listSeparators = array("\t", ';', ',');
foreach($listSeparators as $sep){
	if(strpos($this->lines[0], $sep) !== false){
		$this->separator = $sep;
		break;
	}
}

$nbPreviewLines = 0;
$i = 0;

while(isset($this->lines[$i])){
	if(empty($this->lines[$i])){
		unset($this->lines[$i]);
		continue;
	}else $nbPreviewLines++;

	if(strpos($this->lines[$i], '"') !== false){
		$j = $i + 1;
		$position = -1;

		while($j < ($i + 30)){
			$quoteOpened = substr($this->lines[$i], $position + 1, 1) == '"';

			if($quoteOpened){
				$nextQuotePosition = strpos($this->lines[$i], '"', $position + 2);
				if($nextQuotePosition === false){
					if(!isset($this->lines[$j])) break;

					$this->lines[$i] .= "\n".rtrim($this->lines[$j], $this->separator);
					unset($this->lines[$j]);
					$j++;
					continue;
				}else{
					$quoteOpened = false;

					if(strlen($this->lines[$i]) - 1 == $nextQuotePosition){
						break;
					}

					$position = $nextQuotePosition + 1;
				}
			}else{
				$nextSeparatorPosition = strpos($this->lines[$i], $this->separator, $position + 1);
				if($nextSeparatorPosition === false){
					break;
				}else{ // If found the next separator, add the value in $data and change the position
					$position = $nextSeparatorPosition;
				}
			}
		}

		$this->lines = array_merge($this->lines);
	}

	if($nbPreviewLines == 10) break;

	if($nbPreviewLines != 1){
		$i++;
		continue;
	}

	if(strpos($this->lines[$i], '@')){
		$noHeader = 1;
	}else $noHeader = 0;

	$columnNames = explode($this->separator, $this->lines[$i]);
	$nbColumns = count($columnNames);
	if(!empty($i)) unset($this->lines[$i]);
	ksort($this->lines);
}
$this->lines = array_values($this->lines);
$nbLines = count($this->lines);

$app = JFactory::getApplication();

?>
<table <?php echo $app->isAdmin() ? 'class="acymailing_table"' : 'class="adminlist"'; ?> cellspacing="10" cellpadding="10" align="center" id="importdata">
	<?php
	if($noHeader || !isset($this->lines[1])){
		$firstValueLine = $columnNames;
	}else{
		$firstValueLine = explode($this->separator, $this->lines[1]);
		foreach($firstValueLine as &$oneValue){
			$oneValue = trim($oneValue, '\'" ');
		}
	}

	$fieldAssignment = array();
	$fieldAssignment[] = JHTML::_('select.option', "0", '- - -');
	$fieldAssignment[] = JHTML::_('select.option', "1", JText::_('ACY_IGNORE'));
	if(acymailing_isAllowed($this->config->get('acl_extra_fields_import', 'all'))){
		$createField = JHTML::_('select.option', "2", JText::_('ACY_CREATE_FIELD'));
		if(!acymailing_level(3)){
			$createField->disable = true;
			$createField->text .= ' ('.JText::_('ONLY_FROM_ENTERPRISE').')';
		}
		$fieldAssignment[] = $createField;
	}
	$separator = JHTML::_('select.option', "3", '-------------------------------------');
	$separator->disable = true;
	$fieldAssignment[] = $separator;

	$fields = array_keys(acymailing_getColumns('#__acymailing_subscriber'));
	$fields[] = 'listids';

	foreach($fields as $oneField){
		$fieldAssignment[] = JHTML::_('select.option', $oneField, $oneField);
	}

	$fields[] = '1';

	echo '<tr class="row0"><td align="center" valign="top"><strong>'.acymailing_tooltip(JText::_('ACY_ASSIGN_COLUMNS_DESC'), null, null, JText::_('ACY_ASSIGN_COLUMNS')).'</strong>'.($nbColumns > 5 ? '<br/><a style="text-decoration:none;" href="#" onclick="ignoreAllOthers();">'.JText::_('ACY_IGNORE_UNASSIGNED').'</a>' : '').'</td>';

	$alreadyFound = array();
	foreach($columnNames as $key => &$oneColumn){
		$oneColumn = trim($oneColumn, '\'" ');
		$customValue = '';
		$default = JRequest::getCmd('fieldAssignment'.$key);
		if(empty($default) && $default !== 0){
			$default = (in_array($oneColumn, $fields) ? $oneColumn : '0');

			if(!$default && !empty($firstValueLine)){
				if(isset($firstValueLine[$key]) && strpos($firstValueLine[$key], '@')){
					$default = 'email';
				}elseif($nbColumns == 2) $default = 'name';
			}
			if(in_array($default, $alreadyFound)) $default = '0';
			$alreadyFound[] = $default;
		}elseif($default == 2){
			$customValue = JRequest::getCmd('newcustom'.$key);
		}

		echo '<td align="center" valign="top">'.JHTML::_('select.genericlist', $fieldAssignment, 'fieldAssignment'.$key, 'size="1" onchange="checkNewCustom('.$key.')" style="width:180px;"', 'value', 'text', $default).'<br />';

		echo '<input style="width:170px;'.(empty($customValue) ? 'display:none;"' : '" value="'.$customValue.'" required').' type="text" id="newcustom'.$key.'" name="newcustom" placeholder="'.JText::_('FIELD_COLUMN').'..."/></td>';
	}
	echo '</tr>';

	if(!$noHeader){
		foreach($columnNames as &$oneColumn){
			$oneColumn = htmlspecialchars($oneColumn, ENT_COMPAT | ENT_IGNORE, 'UTF-8');
		}
		echo '<tr class="row1"><td align="center"><strong>'.JText::_('ACY_IGNORE_LINE').'</strong></td><td align="center">['.implode(']</td><td align="center">[', $columnNames).']</td></tr>';
	}

	for($i = 1 - $noHeader; $i < 11 - $noHeader && $i < $nbLines; $i++){
		$values = explode($this->separator, $this->lines[$i]);

		foreach($values as &$oneValue){
			$oneValue = htmlspecialchars(trim($oneValue, '\'" '), ENT_COMPAT | ENT_IGNORE, 'UTF-8');
		}
		echo '<tr class="row'.(1 - $i % 2).'"><td align="center"><strong>'.($i + $noHeader).'</strong></td><td align="center">'.implode('</td><td align="center">', $values).'</td></tr>';
	}
	?>
</table>
