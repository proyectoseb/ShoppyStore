<?php
/**
 * Class YtObject
 * 
 * @author The YouTech JSC
 * @package menusys
 * @filesource ytobject.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */

if (!class_exists('YtObject')){
	class YtObject
	{
		function YtObject()
		{
			$args = func_get_args();
			call_user_func_array(array(&$this, '__construct'), $args);
		}

		
		function get($property, $default=null)
		{
			if(isset($this->$property)) {
				return $this->$property;
			}
			return $default;
		}

		function getProperties( $public = true )
		{
			$vars  = get_object_vars($this);

			if($public)
			{
				foreach ($vars as $key => $value)
				{
					if ('_' == substr($key, 0, 1)) {
						unset($vars[$key]);
					}
				}
			}

			return $vars;
		}

		function set( $property, $value = null )
		{
			$previous = isset($this->$property) ? $this->$property : null;
			$this->$property = $value;
			return $previous;
		}

		function setProperties( $properties )
		{
			$properties = (array) $properties; //cast to an array

			if (is_array($properties))
			{
				foreach ($properties as $k => $v) {
					$this->$k = $v;
				}

				return true;
			}

			return false;
		}

		function toString()
		{
			return get_class($this);
		}

		function getPublicProperties()
		{
			return $this->getProperties();
		}
	}
}
