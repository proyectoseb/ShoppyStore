<?php
/**
 * NoNumber Framework Helper File: Assignments: K2
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

/**
 * Assignments: K2
 */
class nnFrameworkAssignmentsK2
{
	function passPageTypes(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		return $parent->passPageTypes('com_k2', $selection, $assignment);
	}

	function passCategories(&$parent, &$params, $selection = array(), $assignment = 'all', $article = 0)
	{
		if ($parent->params->option != 'com_k2')
		{
			return $parent->pass(0, $assignment);
		}

		$pass = (
			($params->inc_categories
				&& (($parent->params->view == 'itemlist' && $parent->params->task == 'category')
					|| $parent->params->view == 'latest'
				)
			)
			|| ($params->inc_items && $parent->params->view == 'item')
		);

		if (!$pass)
		{
			return $parent->pass(0, $assignment);
		}

		$cats = $parent->makeArray(
			$this->getCategories($parent, $article), 1
		);

		$pass = $parent->passSimple($cats, $selection, 'include');

		if ($pass && $params->inc_children == 2)
		{
			return $parent->pass(0, $assignment);
		}
		else if (!$pass && $params->inc_children)
		{
			foreach ($cats as $cat)
			{
				$cats = array_merge($cats, self::getCatParentIds($parent, $cat));
			}
		}

		return $parent->passSimple($cats, $selection, $assignment);
	}

	private function getCategories(&$parent, &$item)
	{
		switch ($parent->params->view)
		{
			case 'item' :
				return $this->getCategoryIDFromItem($parent, $item);
				break;

			case 'itemlist' :
				return $this->getCategoryID($parent);
				break;

			default:
				return '';
		}
	}

	private function getCategoryID(&$parent)
	{
		return $parent->params->id ?: JFactory::getApplication()->getUserStateFromRequest('com_k2itemsfilter_category', 'catid', 0, 'int');
	}

	private function getCategoryIDFromItem(&$parent, &$item)
	{
		if ($item && isset($item->catid))
		{
			return $item->catid;
		}

		$parent->q->clear()
			->select('i.catid')
			->from('#__k2_items AS i')
			->where('i.id = ' . (int) $parent->params->id);
		$parent->db->setQuery($parent->q);

		return $parent->db->loadResult();
	}

	function passTags(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if ($parent->params->option != 'com_k2')
		{
			return $parent->pass(0, $assignment);
		}

		$tag = trim(JFactory::getApplication()->input->getString('tag', ''));
		$pass = (
			($params->inc_tags && $tag != '')
			|| ($params->inc_items && $parent->params->view == 'item')
		);

		if (!$pass)
		{
			return $parent->pass(0, $assignment);
		}

		if ($params->inc_tags && $tag != '')
		{
			$tags = array(trim(JFactory::getApplication()->input->getString('tag', '')));
		}
		else
		{
			$parent->q->clear()
				->select('t.name')
				->from('#__k2_tags_xref AS x')
				->join('LEFT', '#__k2_tags AS t ON t.id = x.tagID')
				->where('x.itemID = ' . (int) $parent->params->id)
				->where('t.published = 1');
			$parent->db->setQuery($parent->q);
			$tags = $parent->db->loadColumn();
		}

		return $parent->passSimple($tags, $selection, $assignment, 1);
	}

	function passItems(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if (!$parent->params->id || $parent->params->option != 'com_k2' || $parent->params->view != 'item')
		{
			return $parent->pass(0, $assignment);
		}

		return $parent->passSimple($parent->params->id, $selection, $assignment);
	}

	function getCatParentIds(&$parent, $id = 0)
	{
		return $parent->getParentIds($id, 'k2_categories', 'parent');
	}
}
