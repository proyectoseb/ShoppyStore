<?php
/**
 * @package Sj Flat Menu
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

abstract class FlatMenuHelper
{
	public static function getList(&$params)
	{
		$list		= array();
		$db		= JFactory::getDbo();
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();
		$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();
		$path		= $active->tree;
		$startLevel = $params->get("startlevel", 1);
		$endLevel = $params->get("endlevel", "all");
		$showSub = $params->get("showsub", "true");
		$menuType = $params->get("menutype");
		$items 		= $menu->getItems('menutype',$menuType);
		$lastitem	= 0;
		
		if ($items) {
			foreach($items as $i => $item)
			{
				if (($startLevel && $item->level < $startLevel)
					|| ($endLevel && $endLevel != "all" && $item->level > $endLevel  )
					|| ($showSub == "false" && $item->level > $startLevel)
				) {
					unset($items[$i]);
					continue;
				}
				
				
				//print_r($active);
				//die;
				//echo $item->parent_id . '' . $active . '<br/>';. 
				
				if ($item->level > 1 && !in_array($item->parent_id, $path)) {
					unset($items[$i]);
					continue;
				}
				$path[] = $item->id;
				$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);
				$lastitem			= $i;
				$item->active		= false;
				$item->flink = $item->link;
				
				switch ($item->type)
				{
					case 'separator':
						continue;
					case 'url':
						if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
							$item->flink = $item->link.'&Itemid='.$item->id;
						}
						break;
					case 'alias':
						$item->flink = 'index.php?Itemid='.$item->params->get('aliasoptions');
						break;
					default:
						$router = JSite::getRouter();
						if ($router->getMode() == JROUTER_MODE_SEF) {
							$item->flink = 'index.php?Itemid='.$item->id;							
						}
						else {
							$item->flink .= '&Itemid='.$item->id;
						}
						break;
				}
				if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {
					$item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));					
				}
				else {
					$item->flink = JRoute::_($item->flink);
				}
				$item->menu_image = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', '')) : '';
			}
		}
		
		return array_values($items);
	}	
}
