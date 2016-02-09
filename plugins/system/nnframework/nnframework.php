<?php
/**
 * Main Plugin File
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

if (JFactory::getApplication()->isAdmin())
{
	// load the NoNumber Framework language file
	require_once JPATH_PLUGINS . '/system/nnframework/helpers/functions.php';
	nnFrameworkFunctions::loadLanguage('plg_system_nnframework');
}

/**
 * Plugin that loads Framework
 */
class plgSystemNNFramework extends JPlugin
{
	public function onAfterRoute()
	{
		$this->updateDownloadKey();

		$this->loadSearchHelper();

		if (!JFactory::getApplication()->input->getInt('nn_qp', 0))
		{
			return;
		}

		// Include the Helper
		require_once JPATH_PLUGINS . '/system/nnframework/helper.php';
		$helper = new plgSystemNNFrameworkHelper;

		$helper->render();
	}

	function updateDownloadKey()
	{
		// Save the download key from the NoNumber Extension Manager config to the update sites
		if (
			JFactory::getApplication()->isSite()
			|| JFactory::getApplication()->input->get('option') != 'com_config'
			|| JFactory::getApplication()->input->get('task') != 'config.save.component.apply'
			|| JFactory::getApplication()->input->get('component') != 'com_nonumbermanager'
		)
		{
			return;
		}

		$form = JFactory::getApplication()->input->post->get('jform', array(), 'array');

		if (!isset($form['key']))
		{
			return;
		}

		$key = $form['key'];

		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->update('#__update_sites as u')
			->set('u.extra_query = ' . $db->q(''))
			->where('u.location LIKE ' . $db->q('http://download.nonumber.nl%'));
		$db->setQuery($query);
		$db->execute();

		$query = $query->clear()
			->update('#__update_sites as u')
			->set('u.extra_query = ' . $db->q('k=' . $key))
			->where('u.location LIKE ' . $db->q('http://download.nonumber.nl%'))
			->where('u.location LIKE ' . $db->q('%&pro=1%'));
		$db->setQuery($query);
		$db->execute();
	}

	function loadSearchHelper()
	{
		// Only in frontend search component view
		if (!JFactory::getApplication()->isSite() || JFactory::getApplication()->input->get('option') != 'com_search')
		{
			return;
		}

		$classes = get_declared_classes();

		if (in_array('SearchModelSearch', $classes) || in_array('searchmodelsearch', $classes))
		{
			return;
		}

		require_once JPATH_PLUGINS . '/system/nnframework/helpers/search.php';
	}
}

