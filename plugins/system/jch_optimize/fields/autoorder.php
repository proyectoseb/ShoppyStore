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

if (version_compare(PHP_VERSION, '5.3.0', '<'))
{
        require_once dirname(__FILE__) . '/compat.php';

        class JFormFieldAutoorder extends JFormFieldCompat
        {

                public $type = 'autoorder';

                protected function getInput()
                {
                        
                }

        }

}
else
{
        include_once dirname(__FILE__) . '/auto.php';

        class JFormFieldAutoorder extends JFormFieldAuto
        {

                protected $type = 'autoorder';

                protected function getInput()
                {
                        $cache_path = JPATH_SITE . '/cache/plg_jch_optimize/';

                        if (file_exists($cache_path))
                        {
                                $fi = new FilesystemIterator($cache_path, FilesystemIterator::SKIP_DOTS);

                                $size = 0;

                                foreach ($fi as $file)
                                {
                                        $size += $file->getSize();
                                }

                                $decimals = 2;
                                $sz       = 'BKMGTP';
                                $factor   = (int) floor((strlen($size) - 1) / 3);
                                $size     = sprintf("%.{$decimals}f", $size / pow(1024, $factor)) . $sz[$factor];

                                $no_files = number_format(iterator_count($fi));
                        }
                        else
                        {
                                $no_files = 0;
                                $size     = '0B';
                        }

                        $sField = parent::getInput();

                        $sField .= '<div><br><div><em>' . sprintf(JText::_('Files: %s'), $no_files) . '</em></div>'
                                . '<div><em>' . sprintf(JText::_('Size: %s'), $size) . '</em></div></div>';


                        return $sField;
                }

                protected function getButtons()
                {
                        if (JFactory::getApplication()->input->get('jchtask') == 'orderplugins')
                        {
                                $this->orderPlugins();
                        }
                        elseif (JFactory::getApplication()->input->get('jchtask') == 'cleancache')
                        {
                                $this->cleanCache();
                        }

                        $aButton              = array();
                        $aButton[0]['link']   = JURI::getInstance()->toString() . '&amp;jchtask=orderplugins';
                        $aButton[0]['icon']   = 'fa-sort-numeric-asc';
                        $aButton[0]['color']  = '#278EB1';
                        $aButton[0]['text']   = 'Order Plugin';
                        $aButton[0]['script'] = '';
                        $aButton[0]['class']  = 'enabled';

                        $aButton[1]['link']   = JURI::getInstance()->toString() . '&amp;jchtask=cleancache';
                        $aButton[1]['icon']   = 'fa-times-circle';
                        $aButton[1]['color']  = '#C0110A';
                        $aButton[1]['text']   = 'Clean Cache';
                        $aButton[1]['script'] = '';
                        $aButton[1]['class']  = 'enabled';

                        return $aButton;
                }

                /**
                 * 
                 */
                protected function cleanCache()
                {
                        $oJchCache     = JchPlatformCache::getCacheObject();

                        $oController = new JControllerLegacy();

                        if ($oJchCache->clean('plg_jch_optimize') === FALSE || $oJchCache->clean('page') === FALSE)
                        {
                                $oController->setMessage(JText::_('JCH_CACHECLEAN_FAILED'), 'error');
                        }
                        else
                        {
                                $oController->setMessage(JText::_('JCH_CACHECLEAN_SUCCESS'));
                        }

                        $this->display($oController);
                }

                /**
                 * 
                 * @return type
                 */
                protected function getPlugins()
                {
                        $oDb    = JFactory::getDbo();
                        $oQuery = $oDb->getQuery(TRUE);
                        $oQuery->select($oDb->quoteName(array('extension_id', 'ordering', 'element')))
                                ->from($oDb->quoteName('#__extensions'))
                                ->where(array(
                                        $oDb->quoteName('type') . ' = ' . $oDb->quote('plugin'),
                                        $oDb->quoteName('folder') . ' = ' . $oDb->quote('system')
                                        ), 'AND');

                        $oDb->setQuery($oQuery);

                        return $oDb->loadAssocList('element');
                }
        }
}