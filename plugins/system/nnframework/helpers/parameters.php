<?php
/**
 * NoNumber Framework Helper File: Parameters
 *
 * @package         NoNumber Framework
 * @version         14.11.6
 *
 * @author          Peter van Westen <peter@nonumber.nl>
 * @link            http://www.nonumber.nl
 * @copyright       Copyright © 2014 NoNumber All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/cache.php';

$classes = get_declared_classes();
if (!in_array('NNePparameters', $classes))
{
	class NNePparameters extends nnParameters
	{
		// for backward compatibility
	}
}

class nnParameters
{
	public static $instance = null;

	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new nnFrameworkParameters;
		}

		return self::$instance;
	}

	public static function getParameters()
	{
		// backward compatibility
		return self::getInstance();
	}
}

class nnFrameworkParameters
{
	function getParams($params, $path = '', $default = '')
	{
		$hash = md5('getParams_' . json_encode($params) . '_' . $path . '_' . $default);

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		$xml = $this->loadXML($path, $default);

		if (empty($params))
		{
			return nnCache::set($hash,
				(object) $xml
			);
		}

		if (!is_object($params))
		{
			$params = json_decode($params);
		}
		elseif (method_exists($params, 'toObject'))
		{
			$params = $params->toObject();
		}

		if (!$params)
		{
			return nnCache::set($hash,
				(object) $xml
			);
		}

		if (empty($xml))
		{
			return nnCache::set($hash,
				$params
			);
		}

		foreach ($xml as $key => $val)
		{
			if (isset($params->$key) && $params->$key != '')
			{
				continue;
			}

			$params->$key = $val;
		}

		return nnCache::set($hash,
			$params
		);
	}

	function getComponentParams($name, $params = '')
	{
		$name = 'com_' . preg_replace('#^com_#', '', $name);

		$hash = md5('getComponentParams_' . $name . '_' . json_encode($params));

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		if (empty($params))
		{
			$params = JComponentHelper::getParams($name);
		}

		return nnCache::set($hash,
			$this->getParams($params, JPATH_ADMINISTRATOR . '/components/' . $name . '/config.xml')
		);
	}

	function getModuleParams($name, $admin = 1, $params = '')
	{
		$name = 'mod_' . preg_replace('#^mod_#', '', $name);

		$hash = md5('getModuleParams_' . $name . '_' . json_encode($params));

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		if (empty($params))
		{
			$params = null;
		}

		return nnCache::set($hash,
			$this->getParams($params, ($admin ? JPATH_ADMINISTRATOR : JPATH_SITE) . '/modules/' . $name . '/' . $name . '.xml')
		);
	}

	function getPluginParams($name, $type = 'system', $params = '')
	{
		$hash = md5('getPluginParams_' . $name . '_' . $type . '_' . json_encode($params));

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		if (empty($params))
		{
			$plugin = JPluginHelper::getPlugin($type, $name);
			$params = (is_object($plugin) && isset($plugin->params)) ? $plugin->params : null;
		}

		return nnCache::set($hash,
			$this->getParams($params, JPATH_PLUGINS . '/' . $type . '/' . $name . '/' . $name . '.xml')
		);
	}

	// Deprecated: use getPluginParams
	function getPluginParamValues($name, $type = 'system')
	{
		return $this->getPluginParams($name, $type);
	}

	private function loadXML($path, $default = '')
	{
		$hash = md5('loadXML_' . $path . '_' . $default);

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		jimport('joomla.filesystem.file');
		if (!$path
			|| !JFile::exists($path)
			|| !$file = JFile::read($path)
		)
		{
			return nnCache::set($hash,
				array()
			);
		}

		$xml = array();

		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $file, $fields);
		xml_parser_free($xml_parser);

		$default = $default ? strtoupper($default) : 'DEFAULT';
		foreach ($fields as $field)
		{
			if ($field['tag'] != 'FIELD'
				|| !isset($field['attributes'])
				|| (!isset($field['attributes']['DEFAULT']) && !isset($field['attributes'][$default]))
				|| !isset($field['attributes']['NAME'])
				|| $field['attributes']['NAME'] == ''
				|| $field['attributes']['NAME']['0'] == '@'
				|| !isset($field['attributes']['TYPE'])
				|| $field['attributes']['TYPE'] == 'spacer'
			)
			{
				continue;
			}

			if (isset($field['attributes'][$default]))
			{
				$field['attributes']['DEFAULT'] = $field['attributes'][$default];
			}

			if ($field['attributes']['TYPE'] == 'textarea')
			{
				$field['attributes']['DEFAULT'] = str_replace('<br />', "\n", $field['attributes']['DEFAULT']);
			}

			$xml[$field['attributes']['NAME']] = $field['attributes']['DEFAULT'];
		}

		return nnCache::set($hash,
			$xml
		);
	}

	function getObjectFromXML(&$xml)
	{
		$hash = md5('getObjectFromXML_' . json_encode($xml));

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		if (!is_array($xml))
		{
			$xml = array($xml);
		}

		$object = new stdClass;
		foreach ($xml as $item)
		{
			$key = $this->_getKeyFromXML($item);
			$val = $this->_getValFromXML($item);

			if (isset($object->$key))
			{
				if (!is_array($object->$key))
				{
					$object->$key = array($object->$key);
				}
				$object->{$key}[] = $val;
			}
			$object->$key = $val;
		}

		return nnCache::set($hash,
			$object
		);
	}

	function _getKeyFromXML(&$xml)
	{
		if (!empty($xml->_attributes) && isset($xml->_attributes['name']))
		{
			$key = $xml->_attributes['name'];
		}
		else
		{
			$key = $xml->_name;
		}

		return $key;
	}

	function _getValFromXML(&$xml)
	{
		if (!empty($xml->_attributes) && isset($xml->_attributes['value']))
		{
			$val = $xml->_attributes['value'];
		}
		else if (empty($xml->_children))
		{
			$val = $xml->_data;
		}
		else
		{
			$val = new stdClass;
			foreach ($xml->_children as $child)
			{
				$k = $this->_getKeyFromXML($child);
				$v = $this->_getValFromXML($child);

				if (isset($val->$k))
				{
					if (!is_array($val->$k))
					{
						$val->$k = array($val->$k);
					}
					$val->{$k}[] = $v;
				}
				else
				{
					$val->$k = $v;
				}
			}
		}

		return $val;
	}
}
