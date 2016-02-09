<?php
/**
 * NoNumber Framework Helper File: Assignments: HikaShop
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
 * Assignments: HikaShop
 */
class nnFrameworkAssignmentsHikaShop
{
	function passPageTypes(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if ($parent->params->option != 'com_hikashop')
		{
			return $parent->pass(0, $assignment);
		}

		$type = $parent->params->view;
		if (
			($type == 'product' && in_array($parent->params->layout, array('contact', 'show')))
			|| ($type == 'user' && in_array($parent->params->layout, array('cpanel')))
		)
		{
			$type .= '_' . $parent->params->layout;
		}

		return $parent->passSimple($type, $selection, $assignment);
	}

	function passCategories(&$parent, &$params, $selection = array(), $assignment = 'all', $article = 0)
	{
		if ($parent->params->option != 'com_hikashop')
		{
			return $parent->pass(0, $assignment);
		}

		$pass = (
			($params->inc_categories
				&& ($parent->params->view == 'category')
			)
			|| ($params->inc_items && $parent->params->view == 'product')
		);

		if (!$pass)
		{
			return $parent->pass(0, $assignment);
		}

		$cats = $this->getCategories($parent);

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

	function passProducts(&$parent, &$params, $selection = array(), $assignment = 'all')
	{
		if (!$parent->params->id || $parent->params->option != 'com_hikashop' || $parent->params->view != 'product')
		{
			return $parent->pass(0, $assignment);
		}

		return $parent->passSimple($parent->params->id, $selection, $assignment);
	}

	function getCategories(&$parent)
	{
		switch (true)
		{
			case ($parent->params->view == 'category' && $parent->params->id):
				return array($parent->params->id);

			case ($parent->params->view == 'category'):
				include_once JPATH_ADMINISTRATOR . '/components/com_hikashop/helpers/helper.php';
				$menuClass = hikashop_get('class.menus');
				$menuData = $menuClass->get($parent->params->Itemid);

				return $parent->makeArray($menuData->hikashop_params['selectparentlisting']);

			case ($parent->params->id):
				$parent->q->clear()
					->select('c.category_id')
					->from('#__hikashop_product_category AS c')
					->where('c.product_id = ' . (int) $parent->params->id);
				$parent->db->setQuery($parent->q);
				$cats = $parent->db->loadColumn();

				return $parent->makeArray($cats);

			default:
				return array();
		}
	}

	function getCatParentIds(&$parent, $id = 0)
	{
		return $parent->getParentIds($id, 'hikashop_category', 'category_parent_id', 'category_id');
	}
}
