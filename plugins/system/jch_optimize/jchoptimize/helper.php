<?php

use JchOptimize\JS_Optimize;
use JchOptimize\HTML_Optimize;

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

class JchOptimizeHelperBase
{

        /**
         * 
         */
        public static function cookieLessDomain($params)
        {
                return '';
        }

}

/**
 * Some helper functions
 * 
 */
class JchOptimizeHelper extends JchOptimizeHelperBase
{

        /**
         * Checks if file (can be external) exists
         * 
         * @param type $sPath
         * @return boolean
         */
        public static function fileExists($sPath)
        {
                if ((strpos($sPath, 'http') === 0))
                {
                        $sFileHeaders = @get_headers($sPath);

                        return ($sFileHeaders !== FALSE && strpos($sFileHeaders[0], '404') === FALSE);
                }
                else
                {
                        return file_exists($sPath);
                }
        }

        /**
         * Get local path of file from the url if internal
         * If external or php file, the url is returned
         *
         * @param string  $sUrl  Url of file
         * @return string       File path
         */
        public static function getFilePath($sUrl)
        {
                $sUriBase = JchPlatformUri::base();
                $sUriPath = JchPlatformUri::base(TRUE);

                $oUri = clone JchPlatformUri::getInstance();

                $aUrl = parse_url($sUrl);

                if (JchOptimizeHelper::isInternal($sUrl) && preg_match('#\.(?>css|js|png|gif|jpe?g)$#i', $aUrl['path']))
                {
                        $sUrl = preg_replace(
                                array(
                                '#^' . preg_quote($sUriBase, '#') . '#',
                                '#^' . preg_quote($sUriPath, '#') . '/#',
                                '#\?.*?$#'
                                ), '', $sUrl);

                        return JchPlatformPaths::absolutePath($sUrl);
                }
                else
                {
                        switch (TRUE)
                        {
                                case preg_match('#://#', $sUrl):

                                        break;

                                case (substr($sUrl, 0, 2) == '//'):

                                        $sUrl = $oUri->toString(array('scheme')) . substr($sUrl, 2);
                                        break;

                                case (substr($sUrl, 0, 1) == '/'):

                                        $sUrl = $oUri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . $sUrl;
                                        break;

                                default:

                                        $sUrl = $sUriBase . $sUrl;
                                        break;
                        }

                        return html_entity_decode($sUrl);
                }
        }

        /**
         * Gets the name of the current Editor
         * 
         * @staticvar string $sEditor
         * @return string
         */
        public static function getEditorName()
        {
                static $sEditor;

                if (!isset($sEditor))
                {
                        $sEditor = JchPlatformUtility::getEditorName();
                }

                return $sEditor;
        }

        /**
         * Determines if file is internal
         * 
         * @param string $sUrl  Url of file
         * @return boolean
         */
        public static function isInternal($sUrl)
        {
                $oUrl = clone JchPlatformUri::getInstance($sUrl);
                //trying to resolve bug in php with parse_url before 5.4.7
                if (preg_match('#^//([^/]+)(/.*)$#i', $oUrl->getPath(), $aMatches))
                {
                        if (!empty($aMatches))
                        {
                                $oUrl->setHost($aMatches[1]);
                                $oUrl->setPath($aMatches[2]);
                        }
                }

                $sUrlBase = $oUrl->toString(array('scheme', 'user', 'pass', 'host', 'port', 'path'));
                $sUrlHost = $oUrl->toString(array('scheme', 'user', 'pass', 'host', 'port'));

                $sBase = JchPlatformUri::base();

                if (stripos($sUrlBase, $sBase) !== 0 && !empty($sUrlHost))
                {
                        return FALSE;
                }

                return TRUE;
        }

        /**
         * 
         * @staticvar string $sContents
         * @return boolean
         */
        public static function checkModRewriteEnabled($params)
        {
                JCH_DEBUG ? JchPlatformProfiler::start('CheckModRewriteEnabled') : null;

                $oFileRetriever = JchOptimizeFileRetriever::getInstance();

                if (!$oFileRetriever->isHttpAdapterAvailable())
                {
                        $params->set('htaccess', 0);
                }
                else
                {
                        $oUri  = JchPlatformUri::getInstance();
                        $sUrl  = $oUri->toString(array('scheme', 'user', 'pass', 'host', 'port')) . JchPlatformPaths::assetPath(TRUE);
                        $sUrl2 = JchPlatformPaths::rewriteBase() . 'test_mod_rewrite';

                        try
                        {
                                $sContents = $oFileRetriever->getFileContents($sUrl . $sUrl2);

                                if ($sContents == 'TRUE')
                                {
                                        $params->set('htaccess', 1);
                                }
                                else
                                {
                                        $sContents2 = $oFileRetriever->getFileContents($sUrl . '3' . $sUrl2);

                                        if ($sContents2 == 'TRUE')
                                        {
                                                $params->set('htaccess', 3);
                                        }
                                        else
                                        {
                                                $params->set('htaccess', 0);
                                        }
                                }
                        }
                        catch (Exception $e)
                        {
                                $params->set('htaccess', 0);
                        }
                }


                JchPlatformPlugin::saveSettings($params);

                JCH_DEBUG ? JchPlatformProfiler::stop('CheckModRewriteEnabled', TRUE) : null;
        }

        /**
         * 
         * @param type $aArray
         * @param type $sString
         * @return boolean
         */
        public static function findExcludes($aArray, $sString, $bScript = FALSE)
        {
                foreach ($aArray as $sValue)
                {
                        if ($bScript)
                        {
                                $sString = JS_Optimize::optimize($sString);
                        }

                        if ($sValue && strpos($sString, $sValue) !== FALSE)
                        {
                                return TRUE;
                        }
                }

                return FALSE;
        }

        /**
         * 
         * @return type
         */
        public static function getBaseFolder()
        {
                return JchPlatformUri::base(true) . '/';
        }

        /**
         * 
         * @param string $search
         * @param string $replace
         * @param string $subject
         * @return type
         */
        public static function strReplace($search, $replace, $subject)
        {
                return str_replace(self::cleanPath($search), $replace, self::cleanPath($subject));
        }

        /**
         * 
         * @param type $str
         * @return type
         */
        public static function cleanPath($str)
        {
                return str_replace(array('\\\\', '\\'), '/', $str);
        }

        /**
         * If parameter is set will minify HTML before sending to browser; 
         * Inline CSS and JS will also be minified if respective parameters are set
         * 
         * @return string                       Optimized HTML
         * @throws Exception
         */
        public static function minifyHtml($sHtml, $oParams)
        {
                JCH_DEBUG ? JchPlatformProfiler::start('MinifyHtml') : null;


                if ($oParams->get('html_minify', 0))
                {
                        $aOptions = array();

                        if ($oParams->get('css_minify', 0))
                        {
                                $aOptions['cssMinifier'] = array('JchOptimize\CSS_Optimize', 'optimize');
                        }

                        if ($oParams->get('js_minify', 0))
                        {
                                $aOptions['jsMinifier'] = array('JchOptimize\JS_Optimize', 'optimize');
                        }

                        $aOptions['minify_level'] = $oParams->get('html_minify_level', 2);

                        $sHtmlMin = HTML_Optimize::optimize($sHtml, $aOptions);

                        if ($sHtmlMin == '')
                        {
                                JchOptimizeLogger::log(JchPlatformUtility::translate('Error while minifying HTML'), $oParams);

                                $sHtmlMin = $sHtml;
                        }

                        $sHtml = $sHtmlMin;

                        JCH_DEBUG ? JchPlatformProfiler::stop('MinifyHtml', TRUE) : null;
                }

                return $sHtml;
        }

        /**
         * Splits a string into an array using any regular delimiter or whitespace
         *
         * @param string  $sString   Delimited string of components
         * @return array            An array of the components
         */
        public static function getArray($sString)
        {
                if (is_array($sString))
                {
                        $aArray = $sString;
                }
                else
                {
                        $aArray = explode(',', trim($sString));
                }

                $aArray = array_map(function($sValue)
                {
                        return trim($sValue);
                }, $aArray);

                return array_filter($aArray);
        }

        /**
         * 
         * @param type $url
         * @param array $params
         */
        public static function postAsync($url, $params, array $posts)
        {
                foreach ($posts as $key => &$val)
                {
                        if (is_array($val))
                        {
                                $val = implode(',', $val);
                        }

                        $post_params[] = $key . '=' . urlencode($val);
                }

                $post_string = implode('&', $post_params);

                $parts = parse_url($url);

                if (isset($parts['scheme']) && ($parts['scheme'] == 'https'))
                {
                        $protocol     = 'ssl://';
                        $default_port = 443;
                }
                else
                {
                        $protocol     = '';
                        $default_port = 80;
                }

                $fp = fsockopen($protocol . $parts['host'], isset($parts['port']) ? $parts['port'] : $default_port, $errno, $errstr, 1);

                if (!$fp)
                {
                        JchOptimizeLogger::log($errno . ': ' . $errstr, $params);
                }
                else
                {
                        $out = "POST " . $parts['path'] . '?' . $parts['query'] . " HTTP/1.1\r\n";
                        $out.= "Host: " . $parts['host'] . "\r\n";
                        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
                        $out.= "Content-Length: " . strlen($post_string) . "\r\n";
                        $out.= "Connection: Close\r\n\r\n";

                        if (isset($post_string))
                        {
                                $out.= $post_string;
                        }

                        fwrite($fp, $out);
                        fclose($fp);
                }
        }
        
        /**
         * 
         * @param type $sHtml
         */
        public static function validateHtml($sHtml)
        {
                return preg_match('#^(?><?[^<]*+)+?<html(?><?[^<]*+)+?<head(?><?[^<]*+)+?</head(?>'
                        . '<?[^<]*+)+?<body(?><?[^<]*+)+?</body(?><?[^<]*+)+?</html.*+$#i', $sHtml);
        }


}
