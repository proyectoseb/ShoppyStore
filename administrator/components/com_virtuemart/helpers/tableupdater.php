<?php
defined('_JEXEC') or die('Restricted access');
/**
 * @version $Id: tableupdater.php 4657 2011-11-10 12:06:03Z Milbo $
 * @package VirtueMart
 * @subpackage core
 * @author Max Milbers
 * @copyright Copyright (C) 2014 by the virtuemart team - All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL 2, see COPYRIGHT.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 * http://virtuemart.net
 */


/**
 * Class to update the tables according to the install.sql db file
 *
 * @author Milbo
 *
 */
if(!class_exists('VmModel')) require VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php';

class GenericTableUpdater extends VmModel{

	public function __construct(){

		$this->_app = JFactory::getApplication();
		$this->_db = JFactory::getDBO();
		// 		$this->_oldToNew = new stdClass();
		$this->starttime = microtime(true);

		$max_execution_time = VmConfig::getExecutionTime();
		$jrmax_execution_time= vRequest::getInt('max_execution_time',300);

		if(!empty($jrmax_execution_time)){
			// 			vmdebug('$jrmax_execution_time',$jrmax_execution_time);
			if($max_execution_time!==$jrmax_execution_time) @ini_set( 'max_execution_time', $jrmax_execution_time );
		}

		$this->maxScriptTime = VmConfig::getExecutionTime() * 0.90-1;	//Lets use 10% of the execution time as reserve to store the progress

		VmConfig::ensureMemoryLimit(128);

		$this->maxMemoryLimit = $this->return_bytes(ini_get('memory_limit')) * 0.85;

		$config = JFactory::getConfig();
		$this->_prefix = $config->get('dbprefix');

		$this->reCreaPri = VmConfig::get('reCreaPri',0);
		$this->reCreaKey = VmConfig::get('reCreaKey',1);
	}

	public function reOrderChilds(){

		vmdebug('I am in reOrderChilds');
	}

	var $tables = array( 	'products'=>'virtuemart_product_id',
									'vendors'=>'virtuemart_vendor_id',
									'categories'=>'virtuemart_category_id',
									'manufacturers'=>'virtuemart_manufacturer_id',
									'manufacturercategories'=>'virtuemart_manufacturercategories_id',

									'paymentmethods'=>'virtuemart_paymentmethod_id',
									'shipmentmethods'=>'virtuemart_shipmentmethod_id');

	/**
	 *
	 *
	 * @author Max Milbers
	 * @param unknown_type $config
	 */
	public function createLanguageTables($langs=0){

		if(empty($langs)){
			$langs = VmConfig::get('active_languages');
			if(empty($langs)){
				$params = JComponentHelper::getParams('com_languages');
				$langs = (array)$params->get('site', 'en-GB');
			}
		}

		$langTables = array();
		//Todo add the mb_ stuff here
		// 		vmTime('my langs <pre>'.print_r($langs,1).'</pre>');
		$i = 0;
		foreach($this->tables as $table=>$tblKey){

// 			if($i>1) continue;
			$className = 'Table'.ucfirst ($table);
			if(!class_exists($className)) require(VMPATH_ADMIN.DS.'tables'.DS.$table.'.php');
			$tableName = '#__virtuemart_'.$table;

			$langTable = $this->getTable($table);
			$translatableFields = $langTable->getTranslatableFields();
			if(empty($translatableFields)) continue;

			$fields = array();
			$lines = array();
			$linedefault = "NOT NULL DEFAULT ''";
			//Text has no default
			$linedefaulttext = "NOT NULL";

			$fields[$tblKey] = 'int(1) UNSIGNED NOT NULL';
// 			vmdebug('createLanguageTables ',$translatableFields);
			//set exceptions from normal shema here !
			//Be aware that you can use this config settings, when declaring them in the virtuemart.cfg
			if(VmConfig::get('dblayoutstrict',true)){
				if($table=='products'){
					$fields['product_s_desc'] = 'varchar('.VmConfig::get('dbpsdescsize',2000).') '.$linedefault;
					$fields['product_desc'] = 'varchar('.VmConfig::get('dbpdescsize',18400).') '.$linedefault;

					$key = array_search('product_desc', $translatableFields);
					unset($translatableFields[$key]);

					$key = array_search('product_s_desc', $translatableFields);
					unset($translatableFields[$key]);

				} else if($table=='vendors'){
					//This makes too much trouble with the vendor stuff, so we use simply text for it
// 					$fields['vendor_store_desc'] = 'varchar('.VmConfig::get('dbvdescsize',1800).') '.$linedefault;
// 					$fields['vendor_terms_of_service'] = 'varchar('.VmConfig::get('dbtossize',18100).') '.$linedefault;
// 					$fields['vendor_legal_info'] = 'varchar('.VmConfig::get('dblegalsize',1100).') '.$linedefault;

					$fields['vendor_store_desc'] = 'text '.$linedefaulttext;
					$fields['vendor_terms_of_service'] = 'text '.$linedefaulttext;
					$fields['vendor_legal_info'] = 'text '.$linedefaulttext;

					$fields['vendor_letter_css'] = 'text '.$linedefaulttext;
					$fields['vendor_letter_header_html'] = "varchar(8000) NOT NULL DEFAULT '<h1>{vm:vendorname}</h1><p>{vm:vendoraddress}</p>'";
					$fields['vendor_letter_footer_html'] = "varchar(8000) NOT NULL DEFAULT '<p>{vm:vendorlegalinfo}<br />Page {vm:pagenum}/{vm:pagecount}</p>'";


					$key = array_search('vendor_store_desc', $translatableFields);
					unset($translatableFields[$key]);

					$key = array_search('vendor_terms_of_service', $translatableFields);
					unset($translatableFields[$key]);

					$key = array_search('vendor_legal_info', $translatableFields);
					unset($translatableFields[$key]);
					
					$key = array_search('vendor_letter_css', $translatableFields);
					unset($translatableFields[$key]);

					$key = array_search('vendor_letter_header_html', $translatableFields);
					unset($translatableFields[$key]);

					$key = array_search('vendor_letter_footer_html', $translatableFields);
					unset($translatableFields[$key]);

				}
			} else {
				$fields['vendor_terms_of_service'] = 'text '.$linedefaulttext;
				$key = array_search('vendor_terms_of_service', $translatableFields);
				unset($translatableFields[$key]);

				$fields['vendor_legal_info'] = 'text '.$linedefaulttext;
				$key = array_search('vendor_legal_info', $translatableFields);
				unset($translatableFields[$key]);
			}

// 		vmdebug('createLanguageTables ',$translatableFields);
			foreach($translatableFields as $k => $name){
				if(strpos($name,'name') !==false ){
					$fields[$name] = 'char('.VmConfig::get('dbnamesize',180).') '.$linedefault;
				} else if(strpos($name,'metadesc')!==false ){
					$fields[$name] = 'varchar('.VmConfig::get('dbmetasize',400).') '.$linedefault;
				} else if(strpos($name,'metatitle')!==false ){
					$fields[$name] = 'char('.VmConfig::get('dbmetasize',100).') '.$linedefault;
				} else if(strpos($name,'metakey')!==false ){
					$fields[$name] = 'varchar('.VmConfig::get('dbmetasize',400).') '.$linedefault;
				} else if(strpos($name,'metaauthor')!==false ){
					$fields[$name] = 'char(64) '.$linedefault;
				} else if(strpos($name,'slug')!==false ){
					$fields[$name] = 'char('.VmConfig::get('dbslugsize',192).') '.$linedefault;
					$slug = true;
				}else if(strpos($name,'phone')!==false) {
					$fields[$name] = 'char(26) '.$linedefault;
				}else if(strpos($name,'desc')!==false) {
					if(VmConfig::get('dblayoutstrict',true)){
						$fields[$name] = 'varchar('.VmConfig::get('dbdescsize',19000).') '.$linedefault;
					} else {
						$fields[$name] = 'text '.$linedefaulttext;
					}

				} else {
					$fields[$name] = 'char(255) '.$linedefault;
				}

			}
			$lines[0] =	$fields;


			if($slug){
				$lines[1][$tblKey] = 'PRIMARY KEY (`'.$tblKey.'`)';
				$lines[1]['slug'] = 'UNIQUE KEY `slug` (`slug`)';
				//a slug must anyway be unique and so one index for both is faster
				//testing revealed that it is slower
				//$lines[1][$tblKey] = 'PRIMARY KEY (`'.$tblKey.'`,`slug`)';
			} else {
				$lines[1][$tblKey] = 'PRIMARY KEY (`'.$tblKey.'`)';
			}

			$table[3] = '';
			foreach($langs as $lang){
				// 				$lang = strtr($lang,'-','_');
				$lang = strtolower(strtr($lang,'-','_'));
				$tbl_lang = $tableName.'_'.$lang;
				$langTables[$tbl_lang] = $lines;
			}

			$i++;

		}
		$this->reCreaPri = 1;
		$ret = $this->updateMyVmTables($langTables);
		// 		vmTime('done creation of lang tables');
		return $ret;

	}

	public function getTablesBySql($file){

		if(!file_exists($file)){
			vmError('Could not execute sql, could not find file '.$file);
			return false;
		}
		$data = fopen($file, 'r');

		$tables = array();
		$tableDefStarted = false;
		while ($line = fgets ($data)) {
			$line = trim($line);
			if (empty($line)) continue; // Empty line

			if (strpos($line, '#') === 0) continue; // Commentline
			if (strpos($line, '--') === 0) continue; // Commentline

			if(strpos($line,'CREATE TABLE IF NOT EXISTS')!==false){
				$tableDefStarted = true;
				$fieldLines = array();
				$tableKeys = array();
				$start = strpos($line,'`');

				$tablename = trim(substr($line,$start+1,-3));
				// 				vmdebug('my $tablename ',$start,$end,$line);
			} else if($tableDefStarted && strpos($line,'KEY')!==false){

				$start = strpos($line,"`");
				$temp = substr($line,$start+1);
				$end = strpos($temp,"`");
				$keyName = substr($temp,0,$end);

				if(strrpos($line,',')==strlen($line)-1){
					$line = substr($line,0,-1);
				}
				$tableKeys[$keyName] = $line;

			} else if(strpos($line,'ENGINE')!==false){
				$tableDefStarted = false;

				$tl = strtolower($line);
				if(strpos($tl,'myisam')!==false){
					$engine = 'MyISAM';
				} else if(strpos($tl,'innodb')!==false){
					$engine = 'InnoDB';
				} else if(strpos($tl,'memory')!==false){
					$engine = 'Memory';
				} else {
					$engine = '';
				}

				$start = strpos($line,"COMMENT='");
				$temp = substr($line,$start+9);
				$end = strpos($temp,"'");
				$comment = substr($temp,0,$end);

				$tables[$tablename] = array($fieldLines, $tableKeys,$comment,$engine);
			} else if($tableDefStarted){

				$start = strpos($line,"`");
				$temp = substr($line,$start+1);
				$end = strpos($temp,"`");
				$keyName = substr($temp,0,$end);

				if(empty($keyName)){
					$m = 'getTablesBySql empty $keyName line: '.$line .' file: '. $file;
					//vmError($m,$m);
					//$tableDefStarted = false;
				} else {

					$line = trim(substr($line,$end+2));
					if(strrpos($line,',')==strlen($line)-1){
						$line = substr($line,0,-1);
					}

					$fieldLines[$keyName] = $line;
				}
			}
		}
		fclose($data);
		return $tables;
	}

	public function updateMyVmTables($file = 0, $like ='_virtuemart_'){

		if(empty($file)){
			$file = VMPATH_ADMIN.DS.'install'.DS.'install.sql';
		}

		if(is_array($file)){
			$tables = $file;
		} else {

			$tables = $this->getTablesBySql($file);
		}

 		//vmdebug('updateMyVmTables $tables',$tables); return false;
		// 	vmdebug('Parsed tables',$tables); //return;
		$this->_db->setQuery('SHOW TABLES LIKE "%'.$like.'%"');
		if (!$existingtables = $this->_db->loadColumn()) {
			vmError('updateMyVmTables '.$this->_db->getErrorMsg());
			return false;
		}

		$i = 0;
		$demandedTables = array();
		//TODO ignore admin menu table
		foreach ($tables as $tablename => $table){

// 			if($i>2) continue;

			$tablename = str_replace('#__',$this->_prefix,$tablename);
			$demandedTables[] = $tablename;
			if(in_array($tablename,$existingtables)){

				/*$q = 'LOCK TABLES `'.$tablename.'` WRITE';
				$this->_db->setQuery($q);
				$this->_db->execute();*/

				if($this->reCreaPri!=0){
					$this->alterColumns($tablename,$table[0],true);
					$this->alterKey($tablename,$table[1],true);
					$this->alterColumns($tablename,$table[0],false);
				} else {
					if(!isset($table[3])) $table[3] = 'MyISAM';
					$this->alterColumns($tablename,$table[0],false,$table[3]);
					if($this->reCreaKey!=0){
						$this->alterKey($tablename,$table[1],false);
					}
				}
				usleep(10);
				$this->optimizeTable($tablename);
				usleep(10);

				/*$q = 'UNLOCK TABLES';
				$this->_db->setQuery($q);
				$this->_db->execute();*/
			} else {
				//vmdebug('Table not existing?',$tablename,$existingtables);
				$this->createTable($tablename,$table);

			}
			$i++;

		}

		//We need first a method here to register valid plugin tables
/* 		$tablesWithLang = array_keys($this->tables); //('categories','manufacturercategories','manufacturers','paymentmethods','shipmentmethods','products','vendors');

// 		$alangs = VmConfig::get('active_languages');
// 		if(empty($alangs)) $alangs = array(VmConfig::setdbLanguageTag());
// 		foreach($alangs as $lang){
// 			foreach($tablesWithLang as $tablewithlang){
// 				$demandedTables[] = $this->_prefix.'virtuemart_'.$tablewithlang.'_'.$lang;
// 			}
// 		}
// 		$demandedTables[] = $this->_prefix.'virtuemart_configs';


// 		$todelete = array();
// 		foreach ($existingtables as $tablename){
// 			if(!in_array($tablename,$demandedTables) and strpos($tablename,'_plg_')===false){
// 				$todelete[] = $tablename;
// 			}
// 		}
// 		$this->dropTables($todelete);
*/
	}

	public function optimizeTable($tablename){
		//There is a bug, which can make your table unaccessable
		/*$q ='OPTIMIZE TABLE '.$tablename;
		$this->_db->setQuery($q);
		$res1 = $this->_db->execute();*/

		$q = 'Show Index FROM '.$tablename;
		$this->_db->setQuery($q);
		$res2 = $this->_db->loadAssocList();
		//vmdebug('Optimised table '.$tablename,$res1,$res2);
		/*foreach($res2 as $m){
			vmdebug($tablename.': '.$m['Key_name'].' '.$m['Cardinality']);
		}*/
	}

	public function createTable($tablename,$table){

		$q = 'CREATE TABLE IF NOT EXISTS `'.$tablename.'` (
				';
		foreach($table[0] as $fieldname => $alterCommand){
			$q .= '`'.$fieldname.'` '.$alterCommand.',
			';
		}

		foreach($table[1] as $name => $value){
				$q .= $value.',
						';
		}

		$q = substr(trim($q),0,-1);
		$comment = '';
		if(!empty($table[3])){
			$comment = " COMMENT='".$table[3]."'";
		}
		$q .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 ".$comment." AUTO_INCREMENT=1 ;";

		$this->_db->setQuery($q);
		if(!$this->_db->execute()){
			vmError('createTable ERROR :'.$this->_db->getErrorMsg() );
		} else {
			vmInfo('created table '.$tablename);
		}
// 		$this->_app->enqueueMessage($q);
	}

	public function dropTables($todelete){
		if(empty($todelete)) return;
		$q = 'DROP ';// .implode(',',$todelete);
		foreach($todelete as $tablename){
			$tablename = str_replace('#__',$this->_prefix,$tablename);
			$q .= $tablename.', ';
		}
		$q = substr($q,0,-1);

		// 		$this->_db->setQuery($q);
		// 		if(!$this->_db->query()){
		// 			$this->_app->enqueueMessage('dropTables ERROR :'.$this->_db->getErrorMsg() );
		// 		}
		$this->_app->enqueueMessage($q);
	}


	private function alterKey($tablename,$keys,$reCreatePrimary){

		if((microtime(true)-$this->starttime) >= ($this->maxScriptTime)){
			vmWarn('compareUpdateTable alterKey not finished, please rise execution time and update tables again');
			return false;
		}

		$demandedFieldNames = array();
		foreach($keys as $i=>$line){
			$demandedFieldNames[] = $i;
		}

		$query = "SHOW INDEXES  FROM `".$tablename."` ";	//SHOW {INDEX | INDEXES | KEYS}
		$this->_db->setQuery($query);
		$eKeys = $this->_db->loadObjectList();

		$ok=true;

		foreach($eKeys as $i => $eKey) {

			if(strpos( $eKey->Key_name, 'PRIMARY' ) !== false) {
				if(!$reCreatePrimary) {
					continue;
				}
			}
			if(empty($eKey->Key_name)) continue;

			$query = "SHOW INDEXES  FROM `".$tablename."` ";
			$this->_db->setQuery($query);
			$eKeyNamesNOW = $this->_db->loadColumn(2);

			if(!in_array($eKey->Key_name,$eKeyNamesNOW)) continue;

			$query = 'ALTER TABLE `'.$tablename.'` DROP INDEX `'.$eKey->Key_name.'` ';

			$this->_db->setQuery($query);

			$ok =$this->_db->execute();
			if(!$ok){
				$this->_app->enqueueMessage('alterTable DROP INDEX '.$tablename.'.'.$eKey->Key_name.' :'.$this->_db->getErrorMsg() );
			} else {
				//$dropped++;
				//vmdebug('alterKey: Dropped KEY `'.$eKey->Key_name.'` in table `'.$tablename.'`');
			}
		}

		foreach($keys as $name =>$value){

			if(!$reCreatePrimary){
				if(strpos($value,'PRIMARY')!==false){
					continue;
				}
			}

			$query = "ALTER TABLE `".$tablename."` ADD ".$value ;
			$action = 'ADD';

			if(!empty($query)){
				$this->_db->setQuery($query);
				if(!$this->_db->execute()){
					$this->_app = JFactory::getApplication();
					$this->_app->enqueueMessage('alterKey '.$action.' INDEX '.$name.': '.$this->_db->getErrorMsg() );
				} else {
 					//vmdebug('alterKey: a:'.$action.' KEY `'.$name.'` in table `'.$tablename.'` '.$this->_db->getQuery());
				}
			}
		} //*/

	}

	function reCreateKeyByTableAttributes($keyAttribs){

		$oldkey ='';

		if(!empty($keyAttribs->Key_name) && !empty($keyAttribs->Column_name) ){
			if(strpos($keyAttribs->Key_name,'PRIMARY')!==false){
				$oldkey = 'PRIMARY KEY (`'.$keyAttribs->Column_name.'`)';
			} else {
				$oldkey = 'KEY `'.$keyAttribs->Key_name.'` (`'.$keyAttribs->Column_name.'`)';
			}
		} else {
			vmdebug('reCreateKeyByTableAttributes $keyAttribs empty?',$keyAttribs);
		}

		// 		if(empty($keyAttribs->Cardinality)){
		// 			vmdebug('Cardinality : '.$keyAttribs->Cardinality.' '.$oldkey);
		// 		}

		return $oldkey;
	}

	/**
	 * @author Max Milbers
	 * @param unknown_type $tablename
	 * @param unknown_type $fields
	 * @param unknown_type $command
	 */
	public function alterColumns($tablename,$fields,$reCreatePrimary,$engine='MyISAM'){


		$after =' FIRST';
		$dropped = 0;
		$altered = 0;
		$added = 0;
		$this->_app = JFactory::getApplication();

		$demandFieldNames = array();
		foreach($fields as $i=>$line){
			$demandFieldNames[] = $i;
		}



		$q = 'SHOW FULL COLUMNS  FROM `'.$tablename.'` ';	//$q = 'SHOW CREATE TABLE '.$this->_tbl;
		$this->_db->setQuery($q);
		$fullColumns = $this->_db->loadObjectList();
		$columns = $this->_db->loadColumn(0);
		//vmdebug('alterColumns',$fullColumns);
		//Attention user_infos is not in here, because it an contain customised fields. #__virtuemart_order_userinfos #__virtuemart_userinfos
		//This is currently not working as intended, because the config is not deleted before, it is better to create an extra command for this, when we need it later
		$upDelCols = (int) VmConfig::get('updelcols',0);
		if($upDelCols==1 and !($tablename==$this->_prefix.'virtuemart_userfields' or $tablename==$this->_prefix.'virtuemart_userinfos' or $tablename==$this->_prefix.'virtuemart_order_userinfos')){

				foreach($columns as $fieldname){

					if(!in_array($fieldname, $demandFieldNames)){
						$query = 'ALTER TABLE `'.$tablename.'` DROP COLUMN `'.$fieldname.'` ';
						$action = 'DROP';
						$dropped++;

						$this->_db->setQuery($query);
						if(!$this->_db->execute()){
							vmError('alterTable '.$action.' '.$tablename.'.'.$fieldname.' :'.$this->_db->getErrorMsg() );
						}
					}
				}
			}


		foreach($fields as $fieldname => $alterCommand){

			if((microtime(true)-$this->starttime) >= ($this->maxScriptTime)){
				vmWarn('alterColumns alterKey not finished, please rise execution time and update tables again');
				return false;
			}
			$query='';
			$action = '';

			if(empty($alterCommand)){
				vmdebug('empty alter command '.$fieldname);
				continue;
			}
			// we remove the auto_increment, to be free to set the primary key
			if(strpos($alterCommand,'AUTO_INCREMENT')!==false and $reCreatePrimary){
				$alterCommand = str_replace('AUTO_INCREMENT', '',$alterCommand);
			}

			if(in_array($fieldname,$columns)){

				$key=array_search($fieldname, $columns);
				$oldColumn = $this->reCreateColumnByTableAttributes($fullColumns[$key]);

				//Attention, we give for a primary the auto_increment back, so we cant decide if a key is used as primary,
				//but has no auto increment, so wie alter it anytime
				if(strpos($alterCommand,'AUTO_INCREMENT')!==false and $reCreatePrimary) {

					$query = 'ALTER TABLE `'.$tablename.'` CHANGE COLUMN `'.$fieldname.'` `'.$fieldname.'` '.$alterCommand;
					$action = 'CHANGE';
					$altered++;
// 					vmdebug('$fieldname just auto '.$fieldname,$alterCommand,$oldColumn);
				} else {

// 					while (strpos($oldColumn,'  ')){
// 						str_replace('  ', ' ', $oldColumn);
// 					}
					while (strpos($alterCommand,'  ')){
						$alterCommand = str_replace('  ', ' ', trim($alterCommand));
					}
// 					str_replace('  ', ' ', $alterCommand);
// 					$compare = strcasecmp( $oldColumn, $alterCommand);
// 					$compare = strcasecmp( $oldColumn, $alterCommand);

// 					if (!empty($compare)) {
					$oldColumn = strtoupper($oldColumn);
					$alterCommand = strtoupper(trim($alterCommand));
				//	vmdebug('reCreateColumnByTableAttributes ',$fullColumns[$key]);
					if ($oldColumn != $alterCommand ) {

						$query = 'ALTER TABLE `'.$tablename.'` CHANGE COLUMN `'.$fieldname.'` `'.$fieldname.'` '.$alterCommand. $after;
						$action = 'CHANGE';
						$altered++;
						vmdebug($tablename.' Alter field '.$fieldname.' oldcolumn ',$oldColumn,$alterCommand,$fullColumns[$key]);

// 						vmdebug('Alter field new column ',$fullColumns[$key]);
// 						vmdebug('Alter field new column '.$this->reCreateColumnByTableAttributes($fullColumns[$key])); //,$fullColumns[$key]);
					}
				}
			}
			else {
				$query = 'ALTER TABLE `'.$tablename.'` ADD '.$fieldname.' '.$alterCommand.' '.$after;
				$action = 'ADD';
				$added++;
 				vmdebug('$fieldname '.$fieldname);
			}
			if (!empty($query)) {
				$this->_db->setQuery($query);
				$msg = 'alterTable '.$action.' '.$tablename.'.'.$fieldname.' : ';
				if(!$this->_db->execute() ){
					vmError( $msg, $msg.$query );
				} else {
					vmInfo( $msg );
				}
			}
			$after = ' AFTER `'.$fieldname.'`';
		}

		$q = 'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_NAME = "'.$tablename.'" ';
		$this->_db->setQuery($q);
		$exEngine = $this->_db->loadResult();

		if(!empty($engine) and strtoupper($exEngine)!=strtoupper($engine)){
			$q = 'ALTER TABLE '.$tablename.' ENGINE='.$engine;
			$this->_db->setQuery($q);
			$this->_db->execute();
			vmdebug('Changed engine '.$exEngine.' of table '.$tablename.' to '.$engine,$exEngine);
		}

		if($dropped != 0 or $altered !=0 or $added!=0){
			$this->_app->enqueueMessage('Table updated: Tablename '.$tablename.' dropped: '.$dropped.' altered: '.$altered.' added: '.$added);
			$err = $this->_db->getErrorMsg();
			if(!empty($err)){
				vmError('Tableupdater updating table '.$tablename.' throws error '.$err);
			}
		}

		return true;

	}


	private function reCreateColumnByTableAttributes($fullColumn){

		$oldColumn = $fullColumn->Type;

		if(!empty($fullColumn->Null)){
			$oldColumn .= $this->notnull($fullColumn->Null).$this->getdefault($fullColumn->Default);
		}
		$oldColumn .= $this->formatExtra($fullColumn->Extra).$this->formatComment($fullColumn->Comment);

		return trim($oldColumn);
	}

	private function formatComment($comment){
		if(!empty($comment)){
			return ' COMMENT \''.$comment.'\'';
		} else {
			return '';
		}

	}

	private function notnull($string){
		if ($string=='NO') {
			return  ' NOT NULL';
		} else {
			return '';
		}
	}

	private function formatExtra($extra){
		if (!empty($extra)) {
			return ' '.strtoupper(trim($extra));
		} else {
			return '';
		}
	}

	private function primarykey($string){

		if ($string=='PRI') {
			return  ' AUTO_INCREMENT';
		} else {
			return '';
		}
	}

	private function getdefault($string){
		if (isset($string)) {
			if(strpos($string,'CURRENT_TIMESTAMP')!==FALSE){
				return  " DEFAULT ".trim($string);
			} else {
				return  " DEFAULT '".trim($string)."'";
			}

		} else {
			return '';
		}
	}

	private function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}


	function loadCountListContinue($q,$startLimit,$maxItems,$msg){

		$continue = true;
		$this->_db->setQuery($q);
		if(!$this->_db->execute()){
			vmError($msg.' db error '. $this->_db->getErrorMsg());
			vmError($msg.' db error '. $this->_db->getQuery());
			$entries = array();
			$continue = false;
		} else {
			$entries = $this->_db->loadAssocList();
			$count = count($entries);
			vmInfo($msg. ' found '.$count.' vm1 entries for migration ');
			$startLimit += $maxItems;
			if($count<$maxItems){
				$continue = false;
			}
		}

		return array($entries,$startLimit,$continue);
	}
}


