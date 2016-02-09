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

class JchOptimizeCssParserBase extends JchOptimize\CSS_Optimize
{

        /**
         * 
         * @return type
         */
        public static function staticFiles()
        {
                return array();
        }

        /**
         * 
         * @return type
         */
        public static function fontFiles()
        {
                return array();
        }

        /**
         * 
         * @param type $sContents
         * @param type $sHtml
         * @return string
         */
        public function optimizeCssDelivery($sContents, $sHtml)
        {
                $aContents = array(
                        'font-face'   => '',
                        'criticalcss' => ''
                );

                return $aContents;
        }

}

/**
 * 
 * 
 */
class JchOptimizeCssParser extends JchOptimizeCssParserBase
{

        public $sLnEnd      = '';
        public $params;
        protected $bBackend = FALSE;
        public $e           = '';
        public $u           = '';

        /**
         * 
         * @param type $sLnEnd
         * @param type $bBackend
         */
        public function __construct($params = NULL, $bBackend = 'FALSE')
        {
                $this->sLnEnd = is_null($params) ? "\n" : JchPlatformUtility::lnEnd();
                $this->params = is_null($params) ? NULL : $params;

                $this->bBackend = $bBackend;
                $this->e        = self::ESC_CHARS . '|' . self::DOUBLE_QUOTE_STRING . '|' . self::SINGLE_QUOTE_STRING . '|' . self::BLOCK_COMMENTS . '|'
                        . self::LINE_COMMENTS;
                $this->u        = self::URI . '|' . $this->e;
        }

        /**
         * 
         * @param type $sContent
         * @return type
         */
        public function handleMediaQueries($sContent, $sParentMedia = '')
        {
                if ($this->bBackend)
                {
                        return $sContent;
                }

                if (isset($sParentMedia) && ($sParentMedia != ''))
                {
                        $obj = $this;

                        $sContent = preg_replace_callback(
                                "#(?>@?[^\\\\@'\"/]*+(?:{$this->u}|/|\()?)*?\K(?:@media ([^{]*+)|\K$)#i",
                                function($aMatches) use ($sParentMedia, $obj)
                        {
                                return $obj->_mediaFeaturesCB($aMatches, $sParentMedia);
                        }, $sContent
                        );

                        $a = $this->nestedAtRulesRegex();

                        $sContent = preg_replace(
                                "#(?>(?:\|\"[^|]++(?<=\")\||$a)\s*+)*\K"
                                . "(?>(?:$this->u|/|\(|@(?![^{};]++(?1)))?(?:[^|@'\"/(]*+|$))*+#i",
                                '@media ' . $sParentMedia . ' {' . $this->sLnEnd . '$0' . $this->sLnEnd . '}', trim($sContent)
                        );

                        $sContent = preg_replace("#(?>@?[^@]*+)*?\K(?:@media[^{]*+{((?>\s*+|$this->e)++)}|$)#i", '$1', $sContent);
                }

                return $sContent;
        }

        /**
         * 
         * @return string
         */
        public static function nestedAtRulesRegex()
        {
                return '@[^{};]++({(?>[^{}]++|(?1))*+})';
        }

        /**
         * 
         * @param type $aMatches
         * @return type
         */
        public function _mediaFeaturesCB($aMatches, $sParentMedia)
        {
                if (!isset($aMatches[1]) || $aMatches[1] == '' || preg_match('#^(?>\(|/(?>/|\*))#', $aMatches[0]))
                {
                        return $aMatches[0];
                }

                return '@media ' . $this->combineMediaQueries($sParentMedia, trim($aMatches[1]));
        }

        /**
         * 
         * @param type $sParentMediaQueries
         * @param type $sChildMediaQueries
         * @return type
         */
        protected function combineMediaQueries($sParentMediaQueries, $sChildMediaQueries)
        {
                $aParentMediaQueries = preg_split('#\s++or\s++|,#i', $sParentMediaQueries);
                $aChildMediaQueries  = preg_split('#\s++or\s++|,#i', $sChildMediaQueries);

                //$aMediaTypes = array('all', 'aural', 'braille', 'handheld', 'print', 'projection', 'screen', 'tty', 'tv', 'embossed');

                $aMediaQuery = array();

                foreach ($aParentMediaQueries as $sParentMediaQuery)
                {
                        $aParentMediaQuery = $this->parseMediaQuery(trim($sParentMediaQuery));

                        foreach ($aChildMediaQueries as $sChildMediaQuery)
                        {
                                $sMediaQuery = '';

                                $aChildMediaQuery = $this->parseMediaQuery(trim($sChildMediaQuery));

                                if ($aParentMediaQuery['keyword'] == 'only' || $aChildMediaQuery['keyword'] == 'only')
                                {
                                        $sMediaQuery .= 'only ';
                                }

                                if ($aParentMediaQuery['keyword'] == 'not' && $sChildMediaQuery['keyword'] == '')
                                {
                                        if ($aParentMediaQuery['media_type'] == 'all')
                                        {
                                                $sMediaQuery .= '(not ' . $aParentMediaQuery['media_type'] . ')';
                                        }
                                        elseif ($aParentMediaQuery['media_type'] == $aChildMediaQuery['media_type'])
                                        {
                                                $sMediaQuery .= '(not ' . $aParentMediaQuery['media_type'] . ') and ' . $aChildMediaQuery['media_type'];
                                        }
                                        else
                                        {
                                                $sMediaQuery .= $aChildMediaQuery['media_type'];
                                        }
                                }
                                elseif ($aParentMediaQuery['keyword'] == '' && $aChildMediaQuery['keyword'] == 'not')
                                {
                                        if ($aChildMediaQuery['media_type'] == 'all')
                                        {
                                                $sMediaQuery .= '(not ' . $aChildMediaQuery['media_type'] . ')';
                                        }
                                        elseif ($aParentMediaQuery['media_type'] == $aChildMediaQuery['media_type'])
                                        {
                                                $sMediaQuery .= $aParentMediaQuery['media_type'] . ' and (not ' . $aChildMediaQuery['media_type'] . ')';
                                        }
                                        else
                                        {
                                                $sMediaQuery .= $aChildMediaQuery['media_type'];
                                        }
                                }
                                elseif ($aParentMediaQuery['keyword'] == 'not' && $aChildMediaQuery['keyword'] == 'not')
                                {
                                        $sMediaQuery .= 'not ' . $aChildMediaQuery['keyword'];
                                }
                                else
                                {
                                        if ($aParentMediaQuery['media_type'] == $aChildMediaQuery['media_type']
                                                || $aParentMediaQuery['media_type'] == 'all')
                                        {
                                                $sMediaQuery .= $aChildMediaQuery['media_type'];
                                        }
                                        elseif ($aChildMediaQuery['media_type'] == 'all')
                                        {
                                                $sMediaQuery .= $aParentMediaQuery['media_type'];
                                        }
                                        else
                                        {
                                                $sMediaQuery .= $aParentMediaQuery['media_type'] . ' and ' . $aChildMediaQuery['media_type'];
                                        }
                                }

                                if (isset($aParentMediaQuery['expression']))
                                {
                                        $sMediaQuery .= ' and ' . $aParentMediaQuery['expression'];
                                }

                                if (isset($aChildMediaQuery['expression']))
                                {
                                        $sMediaQuery .= ' and ' . $aChildMediaQuery['expression'];
                                }

                                $aMediaQuery[] = $sMediaQuery;
                        }
                }

                return implode(', ', $aMediaQuery);
        }

        /**
         * 
         * @param type $sMediaQuery
         * @return type
         */
        protected function parseMediaQuery($sMediaQuery)
        {
                $aParts = array();

                $sMediaQuery = preg_replace(array('#\(\s++#', '#\s++\)#'), array('(', ')'), $sMediaQuery);
                preg_match('#(?:\(?(not|only)\)?)?\s*+(?:\(?(all|aural|braille|handheld|print|projection|screen|tty|tv|embossed)\)?)?(?:\s++and\s++)?(.++)?#i',
                           $sMediaQuery, $aMatches);

                $aParts['keyword'] = isset($aMatches[1]) ? strtolower($aMatches[1]) : '';

                if (isset($aMatches[2]) && $aMatches[2] != '')
                {
                        $aParts['media_type'] = strtolower($aMatches[2]);
                }
                else
                {
                        $aParts['media_type'] = 'all';
                }

                if (isset($aMatches[3]) && $aMatches[3] != '')
                {
                        $aParts['expression'] = $aMatches[3];
                }

                return $aParts;
        }

        /**
         * 
         * @param string $sContent
         * @param type $sAtRulesRegex
         * @param type $sUrl
         * @return string
         */
        public function removeAtRules($sContent, $sAtRulesRegex, $sUrl = array('url' => 'CSS'))
        {
                if (preg_match_all($sAtRulesRegex, $sContent, $aMatches) === FALSE)
                {
                        //JchOptimizeLogger::log(sprintf(JchPlatformUtility::translate('Error parsing for at rules in %s'), $sUrl['url']), $this->params);

                        return $sContent;
                }

                $m = array_filter($aMatches[0]);

                if (!empty($m))
                {
                        $m = array_unique($m);

                        $sAtRules = implode($this->sLnEnd, $m);

                        $sContentReplaced = str_replace($m, '', $sContent);

                        $sContent = $sAtRules . $this->sLnEnd . $this->sLnEnd . $sContentReplaced;
                }

                return $sContent;
        }

        /**
         * Converts url of background images in css files to absolute path
         * 
         * @param string $sContent
         * @return string
         */
        public function correctUrl($sContent, $aUrl)
        {
                $obj = $this;

                $sCorrectedContent = preg_replace_callback(
                        "#(?>[(]?[^('\\\\/\"]*+(?:{$this->e}|/)?)*?(?:(?<=url)\(\s*+\K['\"]?((?<!['\"])[^\s)]*+|(?<!')[^\"]*+|[^']*+)['\"]?|\K$)#i",
                        function ($aMatches) use ($aUrl, $obj)
                {
                        return $obj->_correctUrlCB($aMatches, $aUrl);
                }, $sContent);

                if (is_null($sCorrectedContent))
                {
                        throw new Exception(JchPlatformUtility::translate('Failed correcting urls of background images'));
                }

                $sContent = $sCorrectedContent;

                return $sContent;
        }

        /**
         * Callback function to correct urls in aggregated css files
         *
         * @param array $aMatches Array of all matches
         * @return string         Correct url of images from aggregated css file
         */
        public function _correctUrlCB($aMatches, $aUrl)
        {
                if (!isset($aMatches[1]) || $aMatches[1] == '' || preg_match('#^(?:\(|/(?:/|\*))#', $aMatches[0]))
                {
                        return $aMatches[0];
                }

                $sUriBase    = JchPlatformUri::base(TRUE);
                $sImageUrl   = $aMatches[1];
                $sCssFileUrl = isset($aUrl['url']) ? $aUrl['url'] : '/';

                if (!preg_match('#^data:#', $sImageUrl))
                {
                        if (!preg_match('#^/|://#', $sImageUrl))
                        {
                                $aCssUrlArray = explode('/', $sCssFileUrl);
                                array_pop($aCssUrlArray);
                                $sCssRootPath = implode('/', $aCssUrlArray) . '/';
                                $sImagePath   = $sCssRootPath . $sImageUrl;
                                $oImageUri    = clone JchPlatformUri::getInstance($sImagePath);

                                if (JchOptimizeHelper::isInternal($sCssFileUrl))
                                {
                                        $sUriPath  = preg_replace('#^' . preg_quote($sUriBase, '#') . '/#', '', $oImageUri->getPath());
                                        $oImageUri->setPath($sUriBase . '/' . $sUriPath);
                                        $sImageUrl = $oImageUri->toString();
                                }
                                else
                                {
                                        $oImageUri->setPath($oImageUri->getPath());
                                        $sImageUrl = ($oImageUri->toString(array('scheme')) ? '' : '//') . $oImageUri->toString();
                                }
                        }

                        if (JchOptimizeHelper::isInternal($sCssFileUrl) && JchOptimizeHelper::isInternal($sImageUrl))
                        {
                                $oImageUri = clone JchPlatformUri::getInstance($sImageUrl);

                                $aStaticFiles = $this->staticFiles();
                                $sStaticFiles = implode('|', $aStaticFiles);

                                $aFontFiles = $this->fontFiles();
                                $sFontFiles = implode('|', $aFontFiles);

                                if (preg_match('#\.(?>' . $sStaticFiles . ')#', $oImageUri->getPath()))
                                {
                                        $sImageUrl = JchOptimizeHelper::cookieLessDomain($this->params) . $oImageUri->toString(array('path'));
                                }
                                elseif (preg_match('#\.php#', $oImageUri->getPath()))
                                {
                                        $sImageUrl = $oImageUri->toString(array('path', 'query', 'fragment'));
                                }
                                elseif ($this->params->get('pro_cookielessdomain', '') && preg_match('#\.(?>' . $sFontFiles . ')#',
                                                                                                     $oImageUri->getPath()))
                                {
                                        $oUri = clone JchPlatformUri::getInstance();

                                        $sImageUrl = '//' . $oUri->toString(array('host', 'port')) .
                                                $oImageUri->toString(array('path'));
                                }
                                else
                                {
                                        $sImageUrl = $oImageUri->toString(array('path'));
                                }
                        }
                }
                $sImageUrl = preg_match('#(?<!\\\\)[\s\'"(),]#', $sImageUrl) ? '"' . $sImageUrl . '"' : $sImageUrl;

                return $sImageUrl;
        }

        /**
         * Sorts @import and @charset as according to w3C <http://www.w3.org/TR/CSS2/cascade.html> Section 6.3
         *
         * @param string $sCss       Combined css
         * @return string           CSS with @import and @charset properly sorted
         * @todo                     replace @imports with media queries
         */
        public function sortImports($sCss)
        {
                $sCssMediaImports = preg_replace_callback("#(?>@?[^@'\"/]*+(?:{$this->u}|/|\()?)*?\K(?:@media\s([^{]++)({(?>[^{}]++|(?2))*+})|\K$)#i",
                                                          array(__CLASS__, '_sortImportsCB'), $sCss);

                if (is_null($sCssMediaImports))
                {
                        //JchOptimizeLogger::log(JchPlatformUtility::translate('Failed matching for imports within media queries in css'), $this->params);

                        return $sCss;
                }

                $sCss = $sCssMediaImports;

                $sCss = preg_replace('#@charset[^;}]++;?#i', '', $sCss);
                $sCss = $this->removeAtRules($sCss, '#(?>[/@]?[^/@]*+(?:/\*(?>\*?[^\*]*+)*?\*/)?)*?\K(?:@import[^;}]++;?|\K$)#i');

                return $sCss;
        }

        /**
         * Callback function for sort Imports
         * 
         * @param type $aMatches
         * @return string
         */
        protected function _sortImportsCB($aMatches)
        {
                if (!isset($aMatches[1]) || $aMatches[1] == '' || preg_match('#^(?>\(|/(?>/|\*))#', $aMatches[0]))
                {
                        return $aMatches[0];
                }

                $sMedia = $aMatches[1];

                $sImports = preg_replace_callback('#(@import\surl\([^)]++\))([^;}]*+);?#',
                                                  function($aM) use ($sMedia)
                {
                        if (!empty($aM[2]))
                        {
                                return $aM[1] . ' ' . $this->combineMediaFeatures($sMedia, $aM[2]) . ';';
                        }
                        else
                        {
                                return $aM[1] . ' ' . $sMedia . ';';
                        }
                }, $aMatches[2]);

                $sCss = str_replace($aMatches[2], $sImports, $aMatches[0]);

                return $sCss;
        }

        /**
         * 
         * @param type $sCss
         * @return type
         */
        public function addRightBrace($sCss)
        {
                if (preg_match('#{#', $sCss))
                {
                        preg_match_all("#(?>[^\\\\{}'\"/]*+(?:{$this->u}|/|\()?)+?(?:(?<b>{(?>[^{}]++|(?&b))*+})?)#", rtrim($sCss) . '}}', $m,
                                                                                                                        PREG_PATTERN_ORDER);

                        $sCss = implode('', $m[0]);
                }

                return $sCss;
        }

        
}
