<?php
/**
 * @package Sj Megamenu
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

class SjMegamenuHelper
{
	private static $parent_ignored = array();
	private static function noPrint($item){
		$is_divider = $item->params->get('xmp_contenttype') == 'divider';
		$is_module  = $item->params->get('xmp_contenttype') == 'mod';
		$parent_is_ignored = in_array($item->parent_id, self::$parent_ignored);

		if ($is_divider || $is_module){
			array_push(self::$parent_ignored, $item->id);
		}

		if ($parent_is_ignored){
			array_push(self::$parent_ignored, $item->id);
		}

		return $parent_is_ignored;
	}
	public static function getList(&$params)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		// Get active menu item
		$active = self::getActive($params);
		$user = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();
		asort($levels);
		$key = 'menu_items' . $params . implode(',', $levels) . '.' . $active->id;
		$cache = JFactory::getCache('mod_sj_megamenu_res', '');
		if (!($items = $cache->get($key)))
		{
			$path    = $active->tree;
			$start   = (int) $params->get('startLevel');
			$end     = (int) $params->get('endLevel');
			$showAll = $params->get('showAllChildren');
			$items   = $menu->getItems('menutype', $params->get('menutype'));
			$lastitem = 0;
			if ($items)
			{
				foreach($items as $i => $item)
				{
					if (($start && $start > $item->level)
							|| ($end && $item->level > $end)
							|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
							|| ($start > 1 && !in_array($item->tree[$start - 2], $path))
							|| self::noPrint($item)
					) {
						unset($items[$i]);
						continue;
					}

					$item->deeper     = false;
					$item->shallower  = false;
					$item->level_diff = 0;

					if (isset($items[$lastitem]))
					{
						$items[$lastitem]->deeper     = ($item->level > $items[$lastitem]->level);
						$items[$lastitem]->shallower  = ($item->level < $items[$lastitem]->level);
						$items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
					}

					$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);

					$lastitem     = $i;
					$item->active = false;
					$item->flink  = $item->link;

					// Reverted back for CMS version 2.5.6
					switch ($item->type)
					{
						case 'separator':
							// No further action needed.
							continue;

						case 'url':
							if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false))
							{
								// If this is an internal Joomla link, ensure the Itemid is set.
								$item->flink = $item->link . '&Itemid=' . $item->id;
							}
							break;

						case 'alias':
							// If this is an alias use the item id stored in the parameters to make the link.
							$item->flink = 'index.php?Itemid=' . $item->params->get('aliasoptions');
							break;

						default:
							$router = JSite::getRouter();
							if ($router->getMode() == JROUTER_MODE_SEF)
							{
								$item->flink = 'index.php?Itemid=' . $item->id;
							}
							else
							{
								$item->flink .= '&Itemid=' . $item->id;
							}
							break;
					}

					if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false))
					{
						$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
					}
					else
					{
						$item->flink = JRoute::_($item->flink);
					}

					// We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
					// when the cause of that is found the argument should be removed
					$item->title        = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
					$item->anchor_css   = htmlspecialchars($item->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
					$item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
					$item->menu_image   = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
				}

				if (isset($items[$lastitem]))
				{
					$items[$lastitem]->deeper     = (($start?$start:1) > $items[$lastitem]->level);
					$items[$lastitem]->shallower  = (($start?$start:1) < $items[$lastitem]->level);
					$items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start?$start:1));
				}
			}
			$cache->store($items, $key);
		}
		return $items;
	}

	/**
	 * Get active menu item.
	 *
	 * @param	JRegistry	$params	The module options.
	 *
	 * @return	object
	 * @since	3.0
	 */
	public static function getActive(&$params)
	{
		$menu = JFactory::getApplication()->getMenu();

		// Get active menu item from parameters
		if ($params->get('active')) {
			$active = $menu->getItem($params->get('active'));
		} else {
			$active = false;
		}

		// If no active menu, use current or default
		if (!$active) {
			$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();
		}

		return $active;
	}
	
	/**
	 *
	 * @param string $file
	 */
	public static function include_js($file, $framework=false, $relative=true){
		$basename = basename($file);
		if ($basename != $file){
			if (JHtml::script($basename, $framework, $relative, $pathonly = true)){
				JHtml::script($basename, $framework, $relative);
				return;
			}
		}
		// use Joomla! method
		JHtml::script($file, $framework, $relative);
	}
	
	public static function include_jquery($extension='', $framework=false, $relative=true){
		if ( version_compare(JVERSION, '3.0.0', '>=') ){
			JHtmlJquery::framework();
		} else {
			$doc = JFactory::getDocument();
			if (!isset($doc->jquery_loaded)){
				if (JHtml::script('jquery.min.js', $framework, $relative, $pathonly = true)){
					JHtml::script('jquery.min.js', $framework, $relative);
					JHtml::script('jquery.noconflict.js', $framework, $relative);
					$doc->jquery_loaded = true;
					return;
				} else if (!empty($extension)){
					$jquery   = $extension.'/jquery.min.js';
					$jqueryNC = $extension.'/jquery.noconflict.js'; // should be locate as jquery.min.js
					if (JHtml::script($jquery, $framework, $relative, $pathonly = true)){
						JHtml::script($jquery, $framework, $relative);
						JHtml::script($jqueryNC, $framework, $relative);
						$doc->jquery_loaded = true;
					}
				}
			}
		}
	}
	
	public static function include_css($file, $attribs=array(), $relative=true){
		$basename = basename($file);
		if ($basename != $file){
			if (JHtml::stylesheet($basename, $attribs, $relative, $pathonly = true)){
				JHtml::stylesheet($basename, $attribs, $relative);
				return true;
			}
		}
		// use Joomla! method
		JHtml::stylesheet($file, $attribs, $relative);
	}
}
