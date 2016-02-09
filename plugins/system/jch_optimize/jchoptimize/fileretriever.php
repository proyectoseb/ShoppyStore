<?php

/**
 * JCH Optimize - Aggregate and minify external resources for optmized downloads
 * 
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall
 * @license GNU/GPLv3, See LICENSE file
 * 
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


defined('_JCH_EXEC') or die('Restricted access');

/**
 * 
 * 
 */
class JchOptimizeFileRetriever
{

        protected static $instance = FALSE;
        protected static $oHttpAdapter = Null;
        
        public $response_code = null;
        public $allow_400 = FALSE;

        /**
         * 
         */
        private function __construct()
        {
                
        }

        /**
         * 
         * @param type $sPath
         * @return type
         */
        public function getFileContents($sPath, $aPost = null, $aHeader = null, $sOrigPath = '')
        {
                $oHttpAdapter = $this->getHttpAdapter();
                
                if (strpos($sPath, 'http') === 0)
                {
                        if (!$oHttpAdapter->available())
                        {
                                throw new Exception(JchPlatformUtility::translate('No Http Adapter available'));
                        }
                        
                        $this->response_code = 0;

                        try
                        {
                                $response = $oHttpAdapter->request($sPath, $aPost, $aHeader);
                                
                                $this->response_code = $response['code'];
                                
                                if (!isset($response) || $response === FALSE)
                                {
                                        throw new RuntimeException(
                                        sprintf(JchPlatformUtility::translate('Failed getting file contents from %s'), $sPath)
                                        );
                                }
                        }
                        catch (RuntimeException $ex)
                        {
                                JchOptimizelogger::log($sPath . ': ' . $ex->getMessage(), JchPlatformPlugin::getPluginParams());
                        }
                        catch (Exception $ex)
                        {
                                throw new Exception($sPath . ': ' . $ex->getMessage());
                        }

                        if ($this->response_code != 200 && !$this->allow_400)
                        {
                                $sPath     = $sOrigPath == '' ? $sPath : $sOrigPath;
                                $sContents = $this->notFound($sPath);
                        }
                        else
                        {
                                $sContents = $response['body'];
                        }
                }
                else
                {
                        if (file_exists($sPath))
                        {
                                $sContents = @file_get_contents($sPath);
                        }
                        elseif ($oHttpAdapter->available())
                        {
                                $sUriPath = JchPlatformPaths::path2Url($sPath);

                                $sContents = $this->getFileContents($sUriPath, null, null, $sPath);
                        }
                        else
                        {
                                $sContents = $this->notFound($sPath);
                        }
                }

                return $sContents;
        }

        /**
         * 
         * @return type
         */
        public static function getInstance()
        {
                if (!self::$instance)
                {
                        self::$instance = new JchOptimizeFileRetriever();
                }

                return self::$instance;
        }

        /**
         * 
         * @return type
         */
        public function isHttpAdapterAvailable()
        {
                $oHttpAdapter = $this->getHttpAdapter();
                
                return $oHttpAdapter->available();
        }
        
        /**
         * 
         */
        private function getHttpAdapter()
        {
                if(is_null(self::$oHttpAdapter))
                {
                        self::$oHttpAdapter = JchPlatformHttp::getHttpAdapter();
                }
                
                return self::$oHttpAdapter;
        }

        /**
         * 
         * @param type $sPath
         * @return type
         */
        public function notFound($sPath)
        {
                return '|"COMMENT_START File [' . $sPath . '] not found COMMENT_END"|';
        }

}
