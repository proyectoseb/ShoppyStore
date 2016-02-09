<?php
/**
 * @package SJ Extra Slider for Content
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;

$com_path = JPATH_SITE.'/components/com_content/';
require_once $com_path.'router.php';
require_once $com_path.'helpers/route.php';
if(!class_exists('JModelLegacy')){
	class JModelLegacy extends JModel{
	}
}
JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
include_once dirname(__FILE__).'/helper_base.php';


class SjExtraSliderHelper extends SjExtraSliderBaseHelper {
	public static function getList(&$params)
	{
		$db = JFactory::getDbo();
		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		// Set application parameters in model
		
		$articles->setState(
				'list.select',
				'a.id, a.title, a.alias, a.introtext, a.fulltext, ' .
				'a.checked_out, a.checked_out_time, ' .
				'a.catid, a.created, a.created_by, a.created_by_alias, ' .
				// use created if modified is 0
				'CASE WHEN a.modified = ' . $db->q($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
				'a.modified_by, uam.name as modified_by_name,' .
				// use created if publish_up is 0
				'CASE WHEN a.publish_up = ' . $db->q($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END as publish_up,' .
				'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' .
				'a.hits, a.xreference, a.featured'
		);

		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		
		$articles->setState('params', $appParams);
		// Set the filters based on the module params
		$articles->setState('list.start', 0);
		$articles->setState('list.limit', (int) $params->get('count', 0));
		$articles->setState('filter.published', 1);

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$articles->setState('filter.access', $access);
		
		// Category filter
		$catids = $params->get('catid');
		if ($catids != null) {
			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0) {
				// Get an instance of the generic categories model
				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				$categories->setState('params', $appParams);
				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
				$categories->setState('filter.get_children', $levels);
				$categories->setState('filter.published', 1);
				$categories->setState('filter.access', $access);
				$additional_catids = array();
				foreach($catids as $catid)
				{
					$categories->setState('filter.parentId', $catid);
					$recursive = true;
					$items = $categories->getItems($recursive);
					if ($items)
					{
						foreach($items as $category)
						{
							$condition = (($category->level - $categories->getParent()->level) <= $levels);
							if ($condition) {
								$additional_catids[] = $category->id;
							}
						}
					}
				}
			
				$catids = array_unique(array_merge($catids, $additional_catids));
			}
			$articles->setState('filter.category_id', $catids);
		
		// Ordering
		$articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
		$articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));

// 		// New Parameters
		$articles->setState('filter.featured', $params->get('show_front', 'show'));

		// Filter by language
		$articles->setState('filter.language', $app->getLanguageFilter());

		$items = $articles->getItems();
		//var_dump($items); die("ancnc");
		$show_introtext = $params->get('show_introtext', 0);
		$introtext_limit = $params->get('introtext_limit', 100);

		// Find current Article ID if on an article page
		$option = $app->input->get('option');
		$view = $app->input->get('view');

		if ($option === 'com_content' && $view === 'article') {
			$active_article_id = $app->input->getInt('id');
		}
		else {
			$active_article_id = 0;
		}

		// Prepare data for display using display options
		foreach ($items as &$item)
		{
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			else
			{
				$app  = JFactory::getApplication();
				$menu = $app->getMenu();
				$menuitems = $menu->getItems('link', 'index.php?option=com_users&view=login');
				if (isset($menuitems[0]))
				{
					$Itemid = $menuitems[0]->id;
				}
				elseif ($app->input->getInt('Itemid') > 0)
				{
					// Use Itemid from requesting page only if there is no existing menu
					$Itemid = $app->input->getInt('Itemid');
				}
				$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$Itemid);
			}
			if(class_exists('JHelperTags')){
				$item->tags = new JHelperTags;
				$item->tags->getItemTags('com_content.article', $item->id);
			}else{
				$item->tags = '';
			}
			if ($show_introtext) {
				$item->introtext = JHtml::_('content.prepare', $item->introtext, '', ' mod_sj_extraslider.content');
				self::getAImages($item, $params);
				$item->_introtext = self::_cleanText($item->introtext);
			} else {
				$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'mod_sj_extraslider.content');
				self::getAImages($item, $params);
			}
			$item->displayIntrotext = $show_introtext ? self::truncate($item->_introtext, $introtext_limit) : '';
			$item->displayReadmore = $item->alternative_readmore;
		}
		return $items;
		}
	}
}
