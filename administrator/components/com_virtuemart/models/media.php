<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved by the author.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: media.php 8994 2015-09-17 18:41:25Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(VMPATH_ADMIN.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model for VirtueMart Product Files
 *
 * @package		VirtueMart
 */
class VirtueMartModelMedia extends VmModel {

	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct('virtuemart_media_id');
		$this->setMainTable('medias');
		$this->addvalidOrderingFieldName(array('ordering'));
		$this->_selectedOrdering = 'created_on';

	}

	/**
	 * Gets a single media by virtuemart_media_id
	 * @Todo must be adjusted to new pattern, using first param as id to get
	 * @param string $type
	 * @param string $mime mime type of file, use for exampel image
	 * @return mediaobject
	 */
	function getFile($type=0,$mime=0){

		if (empty($this->_data)) {

			$data = $this->getTable('medias');
			$data->load((int)$this->_id);

			if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');

			$this->_data = VmMediaHandler::createMedia($data,$type,$mime);
		}

		return $this->_data;

	}

	/**
	 * Kind of getFiles, it creates a bunch of image objects by an array of virtuemart_media_id
	 *
	 * @author Max Milbers
	 * @param int $virtuemart_media_id
	 * @param string $type
	 * @param string $mime
	 */
	function createMediaByIds($virtuemart_media_ids,$type='',$mime='',$limit =0){

		if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');

		$app = JFactory::getApplication();

		$medias = array();

		static $_medias = array();

		if(!empty($virtuemart_media_ids)){
			if(!is_array($virtuemart_media_ids)) $virtuemart_media_ids = explode(',',$virtuemart_media_ids);

			$data = $this->getTable('medias');
			foreach($virtuemart_media_ids as $k => $virtuemart_media_id){
				if($limit!==0 and $k==$limit and !empty($medias)) break; // never break if $limit = 0
				if(is_object($virtuemart_media_id)){
					$id = $virtuemart_media_id->virtuemart_media_id;
				} else {
					$id = $virtuemart_media_id;
				}
				if(!empty($id)){
					if (!array_key_exists ($id, $_medias)) {

						$data->load((int)$id);
						if($app->isSite()){
							if($data->published==0){
								$_medias[$id] = $this->createVoidMedia($type,$mime);
								continue;
							}
						}
						$file_type 	= empty($data->file_type)? $type:$data->file_type;
						$mime		= empty($data->file_mimetype)? $mime:$data->file_mimetype;
						if($app->isSite()){
							$selectedLangue = explode(",", $data->file_lang);
							//vmdebug('selectedLangue',$selectedLangue);
							$lang =  JFactory::getLanguage();
							if(in_array($lang->getTag(), $selectedLangue) || $data->file_lang == '') {
								$_medias[$id] = VmMediaHandler::createMedia($data,$file_type,$mime);
								if(is_object($virtuemart_media_id) && !empty($virtuemart_media_id->product_name)) $_medias[$id]->product_name = $virtuemart_media_id->product_name;
							}
						} else {
							$_medias[$id] = VmMediaHandler::createMedia($data,$file_type,$mime);
							if(is_object($virtuemart_media_id) && !empty($virtuemart_media_id->product_name)) $_medias[$id]->product_name = $virtuemart_media_id->product_name;
						}
					}
					if (!empty($_medias[$id])) {
						$medias[] = $_medias[$id];
					}
				}
			}
		}

		if(empty($medias)){
			$medias[] = $this->createVoidMedia($type,$mime);
		}

		return $medias;
	}

	function createVoidMedia($type,$mime){

		static $voidMedia = null;
		if(empty($voidMedia)){
			$data = $this->getTable('medias');

			//Create empty data
			$data->virtuemart_media_id = 0;
			$data->virtuemart_vendor_id = 0;
			$data->file_title = '';
			$data->file_description = '';
			$data->file_meta = '';
			$data->file_class = '';
			$data->file_mimetype = '';
			$data->file_type = '';
			$data->file_url = '';
			$data->file_url_thumb = '';
			$data->published = 0;
			$data->file_is_downloadable = 0;
			$data->file_is_forSale = 0;
			$data->file_is_product_image = 0;
			$data->shared = 0;
			$data->file_params = 0;
			$data->file_lang = '';

			$voidMedia = VmMediaHandler::createMedia($data,$type,$mime);
		}
		return $voidMedia;
	}

	/**
	* Retrieve a list of files from the database. This is meant only for backend use
	*
	* @author Max Milbers
	* @param boolean $onlyPublished True to only retrieve the published files, false otherwise
	* @param boolean $noLimit True if no record count limit is used, false otherwise
	* @return object List of media objects
	*/

	function getFiles($onlyPublished=false, $noLimit=false, $virtuemart_product_id=null, $cat_id=null, $where=array(),$nbr=false){

		$this->_noLimit = $noLimit;

		if(empty($db)) $db = JFactory::getDBO();

		$query = '';

		$selectFields = array();

		$joinTables = array();
		$joinedTables = '';
		$whereItems= array();
		$groupBy ='';
		$orderByTable = '';

		if(!empty($virtuemart_product_id)){
			$mainTable = '`#__virtuemart_product_medias`';
			$selectFields[] = ' `#__virtuemart_medias`.`virtuemart_media_id` as virtuemart_media_id ';
			$joinTables[] = ' LEFT JOIN `#__virtuemart_medias` ON `#__virtuemart_medias`.`virtuemart_media_id`=`#__virtuemart_product_medias`.`virtuemart_media_id` and `virtuemart_product_id` = "'.$virtuemart_product_id.'"';
			$whereItems[] = '`virtuemart_product_id` = "'.$virtuemart_product_id.'"';

			if($this->_selectedOrdering=='ordering'){
				$orderByTable = '`#__virtuemart_product_medias`.';
			} else{
				$orderByTable = '`#__virtuemart_medias`.';
			}
		}

		else if(!empty($cat_id)){
			$mainTable = '`#__virtuemart_category_medias`';
			$selectFields[] = ' `#__virtuemart_medias`.`virtuemart_media_id` as virtuemart_media_id';
			$joinTables[] = ' LEFT JOIN `#__virtuemart_medias` ON `#__virtuemart_medias`.`virtuemart_media_id`=`#__virtuemart_category_medias`.`virtuemart_media_id` and `virtuemart_category_id` = "'.$cat_id.'"';
			$whereItems[] = '`virtuemart_category_id` = "'.$cat_id.'"';
			if($this->_selectedOrdering=='ordering'){
				$orderByTable = '`#__virtuemart_category_medias`.';
			} else{
				$orderByTable = '`#__virtuemart_medias`.';
			}
		}

		else {
			$mainTable = '`#__virtuemart_medias`';
			$selectFields[] = ' `virtuemart_media_id` ';

			$user = JFactory::getUser();
			if(!$user->authorise('core.admin','com_virtuemart') and !$user->authorise('core.manager','com_virtuemart')){
				$vendorId = vmAccess::isSuperVendor();
				$whereItems[] = '(`virtuemart_vendor_id` = "'.$vendorId.'" OR `shared`="1")';
			}

		}

		if ($onlyPublished) {
			$whereItems[] = '`#__virtuemart_medias`.`published` = 1';
		}

		if ($search = vRequest::getString('searchMedia', false)){
			$search = '"%' . $db->escape( $search, true ) . '%"' ;
			$where[] = ' (`file_title` LIKE '.$search.'
								OR `file_description` LIKE '.$search.'
								OR `file_meta` LIKE '.$search.'
								OR `file_url` LIKE '.$search.'
								OR `file_url_thumb` LIKE '.$search.'
							) ';
		}
		if ($type = vRequest::getCmd('search_type')) {
			$where[] = 'file_type = "'.$type.'" ' ;
		}

		if ($role = vRequest::getCmd('search_role')) {
			if ($role == "file_is_downloadable") {
				$where[] = '`file_is_downloadable` = 1';
				$where[] = '`file_is_forSale` = 0';
			} elseif ($role == "file_is_forSale") {
				$where[] = '`file_is_downloadable` = 0';
				$where[] = '`file_is_forSale` = 1';
			} else {
				$where[] = '`file_is_downloadable` = 0';
				$where[] = '`file_is_forSale` = 0';
			}
		}
		
		if (!empty($where)) $whereItems = array_merge($whereItems,$where);


		if(count($whereItems)>0){
			$whereString = ' WHERE '.implode(' AND ', $whereItems );
		} else {
			$whereString = ' ';
		}


		$orderBy = $this->_getOrdering($orderByTable);#

		if(count($selectFields)>0){

			$select = implode(', ', $selectFields ).' FROM '.$mainTable;
			//$selectFindRows = 'SELECT COUNT(*) FROM '.$mainTable;
			if(count($joinTables)>0){
				foreach($joinTables as $table){
					$joinedTables .= $table;
				}
			}

		} else {
			vmError('No select fields given in getFiles','No select fields given');
			return false;
		}

		$this->_data = $this->exeSortSearchListQuery(2, $select, $joinedTables, $whereString, $groupBy, $orderBy,'',$nbr);
		if(empty($this->_data)){
			return array();
		}

		if( !is_array($this->_data)){
			$this->_data = explode(',',$this->_data);
		}

		$this->_data = $this->createMediaByIds($this->_data);
		return $this->_data;

	}

	/**
	 * This function stores a media and updates then the refered table
	 *
	 * @author Max Milbers
	 * @author Patrick Kohl
	 * @param array $data Data from a from
	 * @param string $type type of the media  category,product,manufacturer,shop, ...
	 */
	function storeMedia($dataI,$type){

		vRequest::vmCheckToken('Invalid Token, while trying to save media '.$type);

		$data = $dataI;
		if(isset($dataI['media'])){
			$data = array_merge($data,$dataI['media']);
		}

		if(empty($data['media_action'])){
			$data['media_action'] = 'none';
		}
		vmdebug('storeMedia',$data);
		//the active media id is not empty, so there should be something done with it
		if( (!empty($data['active_media_id']) and isset($data['virtuemart_media_id']) ) || $data['media_action']=='upload'){

			$oldIds = $data['virtuemart_media_id'];

			$data['file_type'] = $type;

			$this -> setId($data['active_media_id']);

			$virtuemart_media_id = $this->store($data);

			if($virtuemart_media_id){
				//added by Mike
				$this->setId($virtuemart_media_id);

				if(!empty($oldIds)){
					if(!is_array($oldIds)) $oldIds = array($oldIds);

					if(!empty($data['mediaordering']) && $data['media_action']=='upload'){
						$data['mediaordering'][$virtuemart_media_id] = count($data['mediaordering']);
					}
					$virtuemart_media_ids = array_merge( (array)$virtuemart_media_id,$oldIds);
					$data['virtuemart_media_id'] = array_unique($virtuemart_media_ids);
				} else {
					$data['virtuemart_media_id'] = $virtuemart_media_id;
				}
			}

		}

		if(!empty($data['mediaordering'])){
			asort($data['mediaordering']);
			$sortedMediaIds = array();
			foreach($data['mediaordering'] as $k=>$v){
				$sortedMediaIds[] = $k;
			}
			$data['virtuemart_media_id'] = $sortedMediaIds;
		}

		//set the relations
		$table = $this->getTable($type.'_medias');
		// Bind the form fields to the country table
		$table->bindChecknStore($data);

		return $table->virtuemart_media_id;

	}

	/**
	 * Store an entry of a mediaItem, this means in end effect every media file in the shop
	 * images, videos, pdf, zips, exe, ...
	 *
	 * @author Max Milbers
	 */
	public function store(&$data) {

		$data['virtuemart_media_id'] = $this->getId();
		if(!vmAccess::manager('media.edit')){
			vmWarn('Insufficient permission to store media');
			return false;
		} else if( empty($data['virtuemart_media_id']) and !vmAccess::manager('media.create')){
			vmWarn('Insufficient permission to create media');
			return false;
		}

		VmConfig::loadJLang('com_virtuemart_media');
		if (!class_exists('VmMediaHandler')) require(VMPATH_ADMIN.DS.'helpers'.DS.'mediahandler.php');

		$table = $this->getTable('medias');

		$table->bind($data);
		$data = VmMediaHandler::prepareStoreMedia($table,$data,$data['file_type']); //this does not store the media, it process the actions and prepares data

		// workarround for media published and product published two fields in one form.
		$tmpPublished = false;
		if (isset($data['media_published'])){
			$tmpPublished = $data['published'];
			$data['published'] = $data['media_published'];
		}

		$table->bindChecknStore($data);

		if($tmpPublished){
			$data['published'] = $tmpPublished;
		}
		return $table->virtuemart_media_id;
	}

	public function attachImages($objects,$type,$mime='',$limit=0){
		if(!empty($objects)){
			if(!is_array($objects)) $objects = array($objects);
			foreach($objects as $k => $object){
				if(!is_object($object)){
					$object = $this->createVoidMedia($type,$mime);
				}

				if(empty($object->virtuemart_media_id)) $virtuemart_media_id = null; else $virtuemart_media_id = $object->virtuemart_media_id;
				$object->images = $this->createMediaByIds($virtuemart_media_id,$type,$mime,$limit);

				//This should not be used in fact. It is for legacy reasons there.
				if(isset($object->images[0]->file_url_thumb)){
					$object->file_url_thumb = $object->images[0]->file_url_thumb;
					$object->file_url = $object->images[0]->file_url;
				}
			}
		}
	}

	function remove($ids){

		if(!vmAccess::manager('media.delete')){
			vmWarn('Insufficient permissions to delete media');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
