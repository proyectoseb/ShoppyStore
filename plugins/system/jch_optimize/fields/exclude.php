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

JFormHelper::loadFieldClass('Textarea');


if (version_compare(JVERSION, '3.0', '>='))
{

        abstract class JchTextarea extends JFormFieldTextarea
        {

                protected $aOptions = array();

                public function setup(SimpleXMLElement $element, $value, $group = NULL)
                {
                        $this->getParams();
                        
                        JCH_DEBUG ? JchPlatformProfiler::mark('beforeSetup' . $this->type) : null;

                        $this->getAdminObject();
                        $this->setOptions();

                        $value = $this->castValue($value);

                        JCH_DEBUG ? JchPlatformProfiler::mark('afterSetup' . $this->type) : null;

                        return parent::setup($element, $value, $group);
                }

                protected function castValue($value)
                {
                        
                }

        }

}
else
{

        abstract class JchTextarea extends JFormFieldTextarea
        {

                protected $aOptions = array();

                public function setup(&$element, $value, $group = NULL)
                {
                        $this->getParams();
                        
                        JCH_DEBUG ? JchPlatformProfiler::mark('beforeSetup - ' . $this->type) : null;

                        $this->getAdminObject();
                        $this->setOptions();
                        
                        $value = $this->castValue($value);

                        JCH_DEBUG ? JchPlatformProfiler::mark('afterSetup - ' . $this->type) : null;

                        return parent::setup($element, $value, $group);
                }

                protected function castValue($value)
                {
                        
                }

        }

}

abstract class JFormFieldExclude extends JchTextarea
{

        protected static $oParams = null;
        protected static $oParser = null;

        /**
         * 
         * @param type $value
         * @return type
         */
        protected function castValue($value)
        {
//                if (is_array($value) && $GLOBALS['bTextArea'])
//                {
//                        $value = implode(', ', $value);
//                }

                if (!is_array($value)/* && !$GLOBALS['bTextArea'] */)
                {
                        $value = JchOptimizeHelper::getArray($value);
                }

                return $value;
        }

        /**
         * 
         * @return type
         */
        protected function getParams()
        {
                if (!isset($GLOBALS['oJchParams']))
                {
                        if (!defined('JCH_VERSION'))
                        {
                                define('JCH_VERSION', '4.2.4');
                        }
                        
                        $GLOBALS['oJchParams'] = JchPlatformPlugin::getPluginParams();
                }

                return $GLOBALS['oJchParams'];
        }

        /**
         * 
         * @return type
         */
        public function getOriginalHtml()
        {
                JCH_DEBUG ? JchPlatformProfiler::mark('beforeGetHtml') : null;

                try
                {
                        $oFileRetriever = JchOptimizeFileRetriever::getInstance();

                        $response = $oFileRetriever->getFileContents($this->getMenuUrl());

                        if ($oFileRetriever->response_code != 200)
                        {
                                throw new Exception(
                                JText::_('Failed fetching front end HTML with response code ' . $oFileRetriever->response_code)
                                );
                        }

                        JCH_DEBUG ? JchPlatformProfiler::mark('afterGetHtml') : null;

                        return $response;
                }
                catch (Exception $e)
                {
                        JchOptimizeLogger::log($this->getMenuUrl() . ': ' . $e->getMessage(), $this->getParams());

                        JCH_DEBUG ? JchPlatformProfiler::mark('afterGetHtml') : null;

                        throw new RunTimeException(JText::_('Turn the plugin on, load or refresh the front-end site first then refresh this page '
                                . 'to populate the multi select exclude lists.'));
                }
        }

        /**
         * 
         * @return string
         */
        protected function getMenuUrl()
        {
                $oParams     = $this->getParams();
                $iMenuLinkId = $oParams->get('jchmenu');

                if (!$iMenuLinkId)
                {
                        require_once dirname(__FILE__) . '/jchmenuitem.php';
                        $iMenuLinkId = JFormFieldJchmenuitem::getHomePageLink();
                }

                $app       = JFactory::getApplication();
                $oMenu     = $app->getMenu('site');
                $oMenuItem = $oMenu->getItem($iMenuLinkId);

                $oUri = clone JUri::getInstance();

                $router = $app->getRouter('site', array('mode' => $app->get('sef')));

                $uri = $router->build($oMenuItem->link . '&Itemid=' . $oMenuItem->id . '&jchbackend=2');

                $uri->setScheme($uri->isSSL() ? 'https' : 'http');
                $uri->setHost($oUri->getHost());
                $uri->setPort($oUri->getPort());

                $sMenuUrl = str_replace('/administrator', '', $uri->toString());

                return $sMenuUrl;
        }

        /**
         * 
         * @return type
         */
        protected function setOptions()
        {
                $this->aOptions = $this->getFieldOptions();
        }

        /**
         * 
         * @return type
         */
        protected function getInput()
        {
                $attributes = 'class="inputbox chzn-custom-value input-xlarge" multiple="multiple" size="5" data-custom_group_text="Custom Position" data-no_results_text="Add custom item"';

                $sField = JHTML::_('select.genericlist', $this->aOptions, $this->name . '[]', $attributes, 'id', 'name', $this->value, $this->id) .
                        '
                                <button type="button" onclick="addJchOption(\'jform_params_' . $this->jch_params . '\')">Add item</button>';

                return $sField;
        }

        /**
         * 
         * @param type $oParams
         * @return type
         */
        protected function getAdminObject()
        {
                if (!isset($GLOBALS['oAdmin']))
                {
                        $params = $this->getParams();
                        $GLOBALS['oAdmin'] = $oAdmin = new JchOptimizeAdmin($params, TRUE);
                        
                        try
                        {
                                $oAdmin->getAdminLinks($this, $params->get('jchmenu'));
                        }
                        catch (RunTimeException $ex)
                        {
                                JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'Notice');
                        }
                        catch (Exception $ex)
                        {
                                JchOptimizeLogger::log($ex->getMessage(), $this->getParams());

                                JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'Warning');
                        }
                }

                return $GLOBALS['oAdmin'];
        }

        /**
         * 
         * @param type $sType
         * @param type $sExcludeParams
         * @param type $sGroup
         */
        protected function prepareFieldOptions($sType, $sExcludeParams, $sGroup = '')
        {
                $oAdmin = $this->getAdminObject();
                
                return $oAdmin->prepareFieldOptions($sType, $sExcludeParams, $sGroup);        
        }
        
        /**
         * 
         */
        protected function getFieldOptions()
        {
                
        }

}
