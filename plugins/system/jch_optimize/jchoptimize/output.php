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

class JchOptimizeOutput
{

        /**
         * 
         * @return type
         */
        public static function getCombinedFile()
        {
                $aGet = self::getArray(array(
                                'f'    => 'alnum',
                                'd'    => 'int',
                                'i'    => 'int',
                                'type' => 'word'
                ));

                $iLifetime = (int) $aGet['d'] * 24 * 60 * 60;

                $aCache = JchPlatformCache::getCache($aGet['f'], $iLifetime);

                if ($aCache === FALSE)
                {
                        die('File not found');
                }

                $aTimeMFile = self::RFC1123DateAdd($aCache['filemtime'], $aGet['d']);

                $sTimeMFile  = $aTimeMFile['filemtime'] . ' GMT';
                $sExpiryDate = $aTimeMFile['expiry'] . ' GMT';

                $sModifiedSinceTime = '';

                if (function_exists('apache_request_headers'))
                {
                        $headers = apache_request_headers();
                        
                        if (isset($headers['If-Modified-Since']))
                        {
                                $sModifiedSinceTime = strtotime($headers['If-Modified-Since']);
                        }
                        
                }
                
                if ($sModifiedSinceTime == '' && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
                {
                        $sModifiedSinceTime = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
                }

                if ($sModifiedSinceTime == strtotime($sTimeMFile))
                {
                        // Client's cache IS current, so we just respond '304 Not Modified'.
                        header('HTTP/1.1 304 Not Modified');
                        header('Connection: close');

                        return;
                }
                else
                {
                        header('Last-Modified: ' . $sTimeMFile);
                }


                $sFile = $aCache['file'][$aGet['i']];

                $sFile = JchOptimizeOutput::getCachedFile($sFile, $iLifetime);

                $aSpriteCss = $aCache['spritecss'];

                if (($aGet['type'] == 'css'))
                {
                        if (is_array($aSpriteCss) && !empty($aSpriteCss) && isset($aSpriteCss['needles']) && $aSpriteCss['replacements'])
                        {
                                $sFile = str_replace($aSpriteCss['needles'], $aSpriteCss['replacements'], $sFile);
                        }

                        if (isset($aCache['font-face']))
                        {
                                $sFile = str_replace($aCache['font-face'], '', $sFile);
                        }

                        $oCssParser = new JchOptimizeCssParser();
                        $sFile      = $oCssParser->sortImports($sFile);

                        if (function_exists('mb_convert_encoding'))
                        {
                                $sFile = '@charset "utf-8";' . $sFile;
                        }
                }

                if ($aGet['type'] == 'css')
                {
                        header('Content-type: text/css');
                }
                elseif ($aGet['type'] == 'js')
                {
                        header('Content-type: application/javascript');
                }

                header('Expires: ' . $sExpiryDate);
                header('Accept-Ranges: bytes');
                header('Cache-Control: Public');
                header('Vary: Accept-Encoding');
                
		$gzip = TRUE;
		
		if (isset($_SERVER['HTTP_USER_AGENT']))
		{
		/* Facebook User Agent
		 * facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)
		 * LinkedIn User Agent
		 * LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/3.1 +http://www.linkedin.com)
		 */
			$pattern = strtolower('/facebookexternalhit|LinkedInBot/x');

			if (preg_match($pattern, strtolower($_SERVER['HTTP_USER_AGENT'])))
			{
				$gzip = FALSE;
			}
		}                

                if (isset($aGet['gz']) && $aGet['gz'] == 'gz' && $gzip)
                {
                        $aSupported = array(
                                'x-gzip'  => 'gz',
                                'gzip'    => 'gz',
                                'deflate' => 'deflate'
                        );

                        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
                        {
                                $aAccepted  = array_map('trim', (array) explode(',', $_SERVER['HTTP_ACCEPT_ENCODING']));
                                $aEncodings = array_intersect($aAccepted, array_keys($aSupported));
                        }
                        else
                        {
                                $aEncodings = array('gzip');
                        }

                        if (!empty($aEncodings))
                        {
                                foreach ($aEncodings as $sEncoding)
                                {
                                        if (($aSupported[$sEncoding] == 'gz') || ($aSupported[$sEncoding] == 'deflate'))
                                        {
                                                $sGzFile = gzencode($sFile, 4, ($aSupported[$sEncoding] == 'gz') ? FORCE_GZIP : FORCE_DEFLATE);

                                                if ($sGzFile === FALSE)
                                                {
                                                        continue;
                                                }

                                                header('Content-Encoding: ' . $sEncoding);

                                                $sFile = $sGzFile;

                                                break;
                                        }
                                }
                        }
                }

                echo $sFile;
        }

        /**
         * 
         * @param type $sContent
         * @param type $iLifetime
         * @return type
         */
        public static function getCachedFile($sContent, $iLifetime)
        {
                $sContent = preg_replace_callback('#\[\[JCH_([^\]]++)\]\]#',
                                                  function($aM) use ($iLifetime)
                {
                        return JchPlatformCache::getCache($aM[1], $iLifetime);
                }, $sContent);

                return $sContent;
        }

        
        /**
         * 
         * @param type $array
         * @return type
         */
        private static function getArray($array)
        {
                $gz = isset($_GET['gz']) ? 'gz' : 'nz';
                
                $array[$gz] = 'word';
                
                $aGet = array();
                
                foreach($array as $key => $value)
                {
                        switch($value)
                        {
                                case 'alnum':
                                        $aGet[$key] = preg_replace('#[^0-9a-f]#', '', $_GET[$key]);
                                        
                                        break;
                                
                                case 'int':
                                        $aGet[$key] = preg_replace('#[^0-9]#', '', $_GET[$key]);
                                        
                                        break;
                                
                                case 'word':
                                default:
                                        $aGet[$key] = preg_replace('#[^a-zA-Z]#', '', $_GET[$key]);
                                        
                                        break;
                        }
                }
                
                return $aGet;
        }
        
        /**
         * 
         * @param type $filemtime
         * @param type $days
         */
        public static function RFC1123DateAdd($filemtime, $days)
        {
                $aTime = array();

                $date = new DateTime();
                $date->setTimestamp($filemtime);

                $aTime['filemtime'] = $date->format('D, d M Y H:i:s');

                $date->add(DateInterval::createFromDateString($days . ' days'));
                $aTime['expiry'] = $date->format('D, d M Y H:i:s');

                return $aTime;
        }
}
