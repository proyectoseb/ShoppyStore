<?php
/**
 * NoNumber Framework Helper File: Helper
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

class nnFrameworkHelper
{
	static function getPluginHelper(&$plugin, $params = null)
	{
		$hash = md5('getPluginHelper_' . $plugin->get('_type') . '_' . $plugin->get('_name') . '_' . json_encode($params));

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		if (!$params)
		{
			require_once JPATH_PLUGINS . '/system/nnframework/helpers/parameters.php';
			$params = nnParameters::getInstance()->getPluginParams($plugin->get('_name'));
		}

		require_once JPATH_PLUGINS . '/' . $plugin->get('_type') . '/' . $plugin->get('_name') . '/helper.php';
		$class = get_class($plugin) . 'Helper';

		return nnCache::set($hash,
			new $class($params)
		);
	}

	static function isCategoryList($context)
	{
		$hash = md5('isCategoryList_' . $context);

		if (nnCache::has($hash))
		{
			return nnCache::get($hash);
		}

		// Return false if it is not a category page
		if ($context != 'com_content.category' || JFactory::getApplication()->input->get('view') != 'category')
		{
			return false;
		}

		// Return false if it is not a list layout
		if (JFactory::getApplication()->input->get('layout') && JFactory::getApplication()->input->get('layout') != 'list')
		{
			return false;
		}

		// Return true if it IS a list layout
		if (JFactory::getApplication()->input->get('layout') == 'list')
		{
			return true;
		}

		// Layout is empty, so check if default layout is list (default)
		require_once JPATH_PLUGINS . '/system/nnframework/helpers/parameters.php';
		$parameters = nnParameters::getInstance();
		$parameters = $parameters->getComponentParams('content');

		return nnCache::set($hash,
			($parameters->category_layout == '_:default')
		);
	}

	static function processArticle(&$article, &$context, &$helper, $method, $params = array())
	{
		if (isset($article->description) && !empty($article->description))
		{
			call_user_func_array(array($helper, $method), array_merge(array(&$article->description), $params));
		}

		if (isset($article->title) && !empty($article->title))
		{
			call_user_func_array(array($helper, $method), array_merge(array(&$article->title), $params));
		}

		if (isset($article->created_by_alias) && !empty($article->created_by_alias))
		{
			call_user_func_array(array($helper, $method), array_merge(array(&$article->created_by_alias), $params));
		}

		if (self::isCategoryList($context))
		{
			return;
		}

		// Process texts
		if (isset($article->text) && !empty($article->text))
		{
			call_user_func_array(array($helper, $method), array_merge(array(&$article->text), $params));

			return;
		}

		if (isset($article->introtext) && !empty($article->introtext))
		{
			call_user_func_array(array($helper, $method), array_merge(array(&$article->introtext), $params));
		}

		if (isset($article->fulltext) && !empty($article->fulltext))
		{
			call_user_func_array(array($helper, $method), array_merge(array(&$article->fulltext), $params));
		}
	}
}
