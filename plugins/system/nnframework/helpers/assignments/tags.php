<?php
/**
 * NoNumber Framework Helper File: Assignments: Tags
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
 * Assignments: Tags
 */
class nnFrameworkAssignmentsTags
{
	function passTags(&$parent, &$params, $selection = array(), $assignment = 'all', $article = 0)
	{
		$is_content = in_array($parent->params->option, array('com_content', 'com_flexicontent'));

		if (!$is_content)
		{
			return $parent->pass(0, $assignment);
		}

		$is_item = in_array($parent->params->view, array('', 'article', 'item'));
		$is_category = in_array($parent->params->view, array('category'));

		if ($is_item)
		{
			$prefix = 'com_content.article';
		}
		else if ($is_category)
		{
			$prefix = 'com_content.category';
		}
		else
		{
			return $parent->pass(0, $assignment);
		}

		// Load the tags.
		$parent->q->clear()
			->select($parent->db->quoteName('t.id'))
			->from('#__tags AS t')
			->join(
				'INNER', '#__contentitem_tag_map AS m'
				. ' ON m.tag_id = t.id'
				. ' AND m.type_alias = ' . $parent->db->quote($prefix)
				. ' AND m.content_item_id IN ( ' . $parent->params->id . ')'
			);
		$parent->db->setQuery($parent->q);
		$tags = $parent->db->loadColumn();

		if (empty($tags))
		{
			return $parent->pass(0, $assignment);
		}

		foreach ($tags as $tag)
		{
			if (!$this->passTag($tag, $selection, $parent, $params))
			{
				continue;
			}

			return $parent->pass(1, $assignment);
		}

		return $parent->pass(0, $assignment);
	}

	private function passTag($tag, $selection, $parent, $params)
	{
		$pass = in_array($tag, $selection);

		if ($pass)
		{
			// If passed, return false if assigned to only children
			// Else return true
			return ($params->inc_children != 2);
		}

		if (!$params->inc_children)
		{
			return false;
		}

		// Return true if a parent id is present in the selection
		return array_intersect(
			self::getParentIds($parent, $tag),
			$selection
		);
	}

	private function getParentIds(&$parent, $id = 0)
	{
		$parentids = $parent->getParentIds($id, 'tags');
		// Remove the root tag
		$parentids = array_diff($parentids, array('1'));

		return $parentids;
	}
}
