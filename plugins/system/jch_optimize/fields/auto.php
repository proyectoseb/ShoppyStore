<?php

/**
 * JCH Optimize - Joomla! plugin to aggregate and minify external resources for
 * optmized downloads
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall
 * @license GNU/GPLv3, See LICENSE file
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/compat.php';
include_once dirname(dirname(__FILE__)) . '/jchoptimize/loader.php';

abstract class JFormFieldAuto extends JFormFieldCompat
{

        protected $bResources = FALSE;

        /**
         * 
         * @return string
         */
        protected function getInput()
        {
                //JCH_DEBUG ? JchPlatformProfiler::mark('beforeGetInput - ' . $this->type) : null;

                $aButtons = $this->getButtons();
                $sField   = JchOptimizeAdmin::generateIcons($aButtons);

                // JCH_DEBUG ? JchPlatformProfiler::mark('beforeGetInput - ' . $this->type) : null;

                return $sField;
        }

        /**
         * 
         * @param type $oController
         */
        protected function display($oController)
        {
                $oUri = clone JUri::getInstance();
                $oUri->delVar('jchtask');
                $oUri->delVar('jchdir');
                $oUri->delVar('status');
                $oUri->delVar('msg');
                $oUri->delVar('dir');
                $oUri->delVar('cnt');
                $oController->setRedirect($oUri->toString());
                $oController->redirect();
        }

        /**
         * 
         * @return type
         */
        protected function orderPlugins()
        {
                $aOrder = array(
                        'jscsscontrol',
                        'eorisis_jquery',
                        'jqueryeasy',
                        'jch_optimize',
                        'plugin_googlemap3',
                        'cdnforjoomla',
                        'bigshotgoogleanalytics',
                        'GoogleAnalytics',
                        'jat3',
                        'cache',
                        'jSGCache',
                        'jotcache',
                        'vmcache_last'
                );

                $aPlugins = $this->getPlugins();

                $aLowerPlugins = array_values(array_filter($aOrder,
                                                           function($aVal) use ($aPlugins)
                        {
                                return (array_key_exists($aVal, $aPlugins));
                        }
                ));

                $iNoPlugins      = count($aPlugins);
                $iNoLowerPlugins = count($aLowerPlugins);
                $iBaseOrder      = $iNoPlugins - $iNoLowerPlugins;

                $cid   = array();
                $order = array();

                foreach ($aPlugins as $key => $value)
                {
                        if (in_array($key, $aLowerPlugins))
                        {
                                $value['ordering'] = $iBaseOrder + 1 + array_search($key, $aLowerPlugins);
                        }
                        elseif ($value['ordering'] >= $iBaseOrder)
                        {
                                $value['ordering'] = $iBaseOrder - 1;
                        }

                        $cid[]   = $value['extension_id'];
                        $order[] = $value['ordering'];
                }

                JArrayHelper::toInteger($cid);
                JArrayHelper::toInteger($order);

                $aOrder          = array();
                $aOrder['cid']   = $cid;
                $aOrder['order'] = $order;

                $oController = new JControllerLegacy;

                $oController->addModelPath(JPATH_ADMINISTRATOR . '/components/com_plugins/models', 'PluginsModel');
                $oPluginModel = $oController->getModel('Plugin', 'PluginsModel');

                if ($oPluginModel->saveorder($aOrder['cid'], $aOrder['order']) === FALSE)
                {
                        $oController->setMessage(JText::sprintf('JLIB_APPLICATION_ERROR_REORDER_FAILED', $oPluginModel->getError()), 'error');
                }
                else
                {
                        $oController->setMessage(JText::_('JLIB_APPLICATION_SUCCESS_ORDERING_SAVED'));
                }

                $this->display($oController);
        }

        abstract protected function getButtons();
}
