<?php

/**
 * JCH Optimize - Joomla! plugin to aggregate and minify external resources for 
 *   optmized downloads
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall. All rights reserved.
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
 * 
 * This plugin includes other copyrighted works. See individual 
 * files for details.
 */
defined('_JEXEC') or die('Restricted access');

class plgSystemJCH_OptimizeInstallerScript
{
        public function preflight($type, $parent)
        {
                $app = JFactory::getApplication();
                
                if ($type == 'install')
                {
                        if (version_compare(PHP_VERSION, '5.3.0', '<'))
                        {
                                $app->enqueueMessage(
                                        JText::_(
                                                'JCH Optimize requires PHP 5.3.0 or greater to be installed. '
                                                . 'Your currently installed PHP version is ' . PHP_VERSION
                                        ), 'error'
                                );

                                return false;
                        }
                        
                        $compatible = TRUE;
                        
                        if (version_compare(JVERSION, '3.0', '<'))
                        {
                                if(version_compare(JVERSION, '2.5.25', '<'))
                                {
                                        $compatible = FALSE;
                                        $latest_version = '2.5.x';
                                }
                        }
                        else
                        {
                                if(version_compare(JVERSION, '3.3.0', '<'))
                                {
                                        $compatible = FALSE;
                                        $latest_version = '3.x';
                                }
                        }
                        
                        if(!$compatible)
                        {
                                $app->enqueueMessage(
                                        JText::_(
                                                'JCH Optimize is not compatible with your version of Joomla. '
                                                . 'Please upgrade to the latest Joomla! ' . $latest_version . ' release. '
                                                . 'The minimum versions of Joomla that can work are 2.5.25 or 3.3.0'
                                        ), 'error'
                                );

                                return FALSE;
                        }
                }

                $manifest = $parent->get('manifest');
                $new_variant = (string) $manifest->variant;
                
                $file = JPATH_SITE . '/plugins/system/jch_optimize/jch_optimize.xml';
                
                if(file_exists($file))
                {
                        $xml = JFactory::getXML($file);
                        $old_variant = (string) $xml->variant;

                        if ($old_variant == 'PRO' && $new_variant == 'FREE')
                        {
                                $app->enqueueMessage(
                                        JText::_(
                                                'You are trying to install the FREE version of JCH Optimize but you currently have '
                                                . 'the PRO version installed. You must uninstall the PRO version before you can install the '
                                                . 'FREE version'
                                        ), 'error'
                                );

                                return false;
                        }
                }
        }
}
