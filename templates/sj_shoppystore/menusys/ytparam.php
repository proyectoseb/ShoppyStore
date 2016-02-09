<?php
/**
 * Class YtParams
 * 
 * @author The YouTech JSC
 * @package menusys
 * @filesource ytparam.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */

if (!class_exists('YtParams')){
	class YtParams extends YtObject{
		public function __construct($params){
			if (gettype($params)=='object' || gettype($params)=='array'){
				$this->_loadObject($params);
			} else if ((gettype($params)=='string') && ('{' == substr($params, 0, 1))){
				$this->_loadJSON($params);
			} else {
				$this->_loadINI($params);
			}
		}
		public function _loadObject($params){
			foreach ($params as $key => $value){
				$this->set($key, $value);
			}
			return true;
		}
		public function _loadJSON($params){
			$source = json_decode($params);
			return $this->bind($source);
		}
		public function _loadINI($params){
			try {
				$parts = preg_split('/\n/', $params, -1, true);
				if(!empty($parts)){
					foreach ($parts as $part){
						$kvp = explode('=', $part, 2);// $part
						if (count($kvp)==2){
							if (!empty($kvp[0])){
								$this->set($kvp[0], $kvp[1]);
							}
						}
					}
				}
				return true;
			} catch (Exception $e) {
				var_dump($e);
				return false;
			}
		}
		public function bind($params){
			if (gettype($params)=='object' || gettype($params)=='array'){
				return $this->_loadObject($params);
			} else if ((gettype($params)=='string') && ('{' == substr($params, 0, 1))){
				return $this->_loadJSON($params);
			} else {
				return $this->_loadINI($params);
			}
			return false;
		}
		public function def($key, $default=''){
			if (!isset($this->$key)){
				$this->set($key, $default);
			}
		}
	}
}