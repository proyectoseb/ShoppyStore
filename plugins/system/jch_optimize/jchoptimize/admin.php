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

class JchOptimizeAdmin
{

        protected $bBackend;
        protected $params;
        protected $links = array();

        /**
         * 
         * @param type $params
         * @param type $bBackend
         */
        public function __construct(JchPlatformSettings $params, $bBackend = FALSE)
        {
                $this->params   = $params;
                $this->bBackend = $bBackend;
        }

        /**
         * 
         * @param type $oObj
         * @param type $iItemid
         * @param type $sCss
         * @return type
         */
        public function getAdminLinks($oObj, $iItemid, $sCss = '')
        {
                if (empty($this->links))
                {
                        $hash      = $iItemid . $this->params->get('pro_searchBody', 0) . $this->params->get('pro_cookielessdomain', 0);
                        $sId       = md5('getAdminLinks' . JCH_VERSION . serialize($hash));
                        $aFunction = array($this, 'generateAdminLinks');
                        $aArgs     = array($oObj, $sCss);
                        $iLifeTime = (int) $this->params->get('lifetime', '30') * 24 * 60 * 60;

                        $this->links = JchPlatformCache::getCallbackCache($sId, $iLifeTime, $aFunction, $aArgs);
                }

                return $this->links;
        }

        /**
         * 
         * @param type $oObj
         * @param type $sCss
         * @return type
         */
        public function generateAdminLinks($oObj, $sCss)
        {
                JCH_DEBUG ? JchPlatformProfiler::start('GenerateAdminLinks') : null;
                
                $params = clone $this->params;
                $params->set('javascript', '1');
                $params->set('css', '1');
                $params->set('excludeAllExtensions', '0');
                $params->set('css_minify', '0');
                $params->set('debug', '0');
                $params->set('bottom_js', '2');
                

                $sHtml   = $oObj->getOriginalHtml();
                $oParser = new JchOptimizeParser($params, $sHtml, JchOptimizeFileRetriever::getInstance());

                $aLinks = $oParser->getReplacedFiles();

                if ($sCss == '' && !empty($aLinks['css'][0]))
                {
                        $oCombiner  = new JchOptimizeCombiner($params, $this->bBackend);
                        $oCssParser = new JchOptimizeCssParser($params, $this->bBackend);

                        $oCombiner->combineFiles($aLinks['css'][0], 'css', $oCssParser);
                        $sCss = $oCombiner->css;
                }

                $oSpriteGenerator = new JchOptimizeSpriteGenerator($params);
                $aLinks['images'] = $oSpriteGenerator->processCssUrls($sCss, TRUE);

                

                JCH_DEBUG ? JchPlatformProfiler::stop('GenerateAdminLinks', TRUE) : null;

                return $aLinks;
        }

        /**
         * 
         * @param type $sExcludeParams
         * @param type $sField
         * @return type
         */
        public function prepareFieldOptions($sType, $sExcludeParams, $sGroup = '')
        {
                if ($sType == 'lazyload')
                {
                        $sGroup        = 'file';
                        $aFieldOptions = $this->getLazyLoad();
                }
                elseif ($sType == 'images')
                {
                        $sGroup        = $sType;
                        $aM            = explode('_', $sExcludeParams);
                        $aFieldOptions = $this->getImages($aM[1]);
                }
                else
                {
                        $aFieldOptions = $this->getOptions($sType, $sGroup . 's');
                }

                $aOptions  = array();
                $oParams   = $this->params;
                $aExcludes = JchOptimizeHelper::getArray($oParams->get($sExcludeParams, array()));

                foreach ($aExcludes as $sExclude)
                {
                        $aOptions[$sExclude] = $this->{'prepare' . ucfirst($sGroup) . 'Values'}($sExclude);
                }

                return array_unique(array_merge($aFieldOptions, $aOptions));

                return $aFieldOptions;
        }

        /**
         * 
         * @param type $sType
         * @param type $sExclude
         * @return type
         */
        protected function getOptions($sType, $sExclude = 'files')
        {
                $aLinks = $this->links;

                $aOptions = array();

                if (!empty($aLinks[$sType][0]))
                {
                        foreach ($aLinks[$sType][0] as $aLink)
                        {
                                if (isset($aLink['url']) && $aLink['url'] != '')
                                {
                                        if ($sExclude == 'files')
                                        {
                                                $sFile = $this->prepareFileValues($aLink['url'], 'pre');

                                                $sFile            = $aOptions[$sFile] = $this->prepareFileValues($sFile, 'post');
                                        }
                                        elseif ($sExclude == 'extensions')
                                        {
                                                $sExtension = $this->prepareExtensionValues($aLink['url'], FALSE);

                                                if ($sExtension === FALSE)
                                                {
                                                        continue;
                                                }

                                                $aOptions[$sExtension] = $sExtension;
                                        }
                                }
                                elseif (isset($aLink['content']) && $aLink['content'] != '')
                                {
                                        if ($sExclude == 'scripts')
                                        {
                                                $sScript = JchOptimize\HTML_Optimize::cleanScript($aLink['content'], 'js');
                                                $sScript = trim(JchOptimize\JS_Optimize::optimize($sScript));

                                                if (strlen($sScript) > 60)
                                                {
                                                        $sScript = substr($sScript, 0, 60);
                                                }

                                                $aOptions[addslashes($sScript)] = $this->prepareScriptValues($sScript);
                                        }
                                }
                        }
                }

                return $aOptions;
        }

        /**
         * 
         * @return type
         */
        public function getLazyLoad()
        {
                $aLinks = $this->links;

                $aFieldOptions = array();

                if (!empty($aLinks['lazyload']))
                {
                        foreach ($aLinks['lazyload'] as $sImage)
                        {
                                $aFieldOptions[$sImage] = $this->prepareFileValues($sImage);
                        }
                }

                return array_filter($aFieldOptions);
        }

        /**
         * 
         * @param type $sAction
         * @return type
         */
        protected function getImages($sAction = 'exclude')
        {
                $aLinks = $this->links;

                $aOptions = array();

                if (!empty($aLinks['images'][$sAction]))
                {
                        foreach ($aLinks['images'][$sAction] as $sImage)
                        {
                                $aImage = explode('/', $sImage);
                                $sImage = array_pop($aImage);

                                $aOptions[$sImage] = $sImage;
                        }
                }

                return array_unique($aOptions);
        }

        /**
         * 
         * @param type $sContent
         */
        protected function prepareScriptValues($sScript)
        {
                $sEps = '';

                if (strlen($sScript) > 52)
                {
                        $sScript = substr($sScript, 0, 52);
                        $sEps    = '...';
                        $sScript = $sScript . $sEps;
                }

                if (strlen($sScript) > 26)
                {
                        $sScript = str_replace($sScript[26], $sScript[26] . "\n", $sScript);
                }

                return $sScript;
        }

        /**
         * 
         * @param type $sUrl
         * @return type
         */
        public static function prepareFileValues($sFile, $sLevel = '', $iLen = 27)
        {
                if ($sLevel != 'post')
                {
                        $sFile = preg_replace('#[?\#].*$#', '', $sFile);

                        if ($sLevel == 'pre')
                        {
                                return $sFile;
                        }
                }

                $sEps = '';

                if (strlen($sFile) > $iLen)
                {
                        $sFile = substr($sFile, -$iLen);
                        $sFile = preg_replace('#^[^/]*+/#', '/', $sFile);
                        $sEps  = '...';
                }

                return $sEps . $sFile;
        }

        /**
         * 
         * @staticvar string $sUriBase
         * @staticvar string $sUriPath
         * @param type $sUrl
         * @return boolean
         */
        protected function prepareExtensionValues($sUrl, $bReturn = TRUE)
        {
                if ($bReturn)
                {
                        return $sUrl;
                }

                static $sHost = '';

                $oUri  = JchPlatformUri::getInstance();
                $sHost = $sHost == '' ? $oUri->toString(array('host')) : $sHost;

                $result     = preg_match('#^(?:https?:)?//([^/]+)#', $sUrl, $m1);
                $sExtension = isset($m1[1]) ? $m1[1] : '';

                if ($result === 0 || $sExtension == $sHost)
                {
                        $result2 = preg_match('#' . JchPlatformExcludes::extensions() . '([^/]+)#', $sUrl, $m);

                        if ($result2 === 0)
                        {
                                return FALSE;
                        }
                        else
                        {
                                $sExtension = $m[1];
                        }
                }

                return $sExtension;
        }

        /**
         * 
         * @param type $sImage
         * @return type
         */
        protected function prepareImagesValues($sImage)
        {
                return $sImage;
        }

        /**
         * 
         * @param type $aButtons
         * @return string
         */
        public static function generateIcons($aButtons)
        {
                $sField = '<div class="container-icons clearfix">';

                foreach ($aButtons as $sButton)
                {
                        $sField .= <<<JFIELD
<div class="icon {$sButton['class']}">
        <a href="{$sButton['link']}"  {$sButton['script']}  >
                <div style="text-align: center;">
                        <i class="fa {$sButton['icon']} fa-3x" style="margin: 7px 0; color: {$sButton['color']}"></i>
                </div>
                <span >{$sButton['text']}</span><br>
                <i id="toggle" class="fa"></i>
        </a>
</div>
JFIELD;
                }

                $sField .= '</div>';

                return $sField;
        }

        /**
         * 
         * @return string
         */
        public static function getSettingsIcons()
        {
                $aButton = array();

                $aButton[0]['link']   = '';
                $aButton[0]['icon']   = 'fa-wrench';
                $aButton[0]['text']   = 'Minimum';
                $aButton[0]['color']  = '#FFA319';
                $aButton[0]['script'] = 'onclick="applyAutoSettings(1, 0); return false;"';
                $aButton[0]['class']  = 'enabled settings-1';

                $aButton[1]['link']   = '';
                $aButton[1]['icon']   = 'fa-cog';
                $aButton[1]['text']   = 'Intermediate';
                $aButton[1]['color']  = '#FF32C7';
                $aButton[1]['script'] = 'onclick="applyAutoSettings(2, 0); return false;"';
                $aButton[1]['class']  = 'enabled settings-2';

                $aButton[2]['link']   = '';
                $aButton[2]['icon']   = 'fa-cogs';
                $aButton[2]['text']   = 'Average';
                $aButton[2]['color']  = '#CE3813';
                $aButton[2]['script'] = 'onclick="applyAutoSettings(3, 0); return false;"';
                $aButton[2]['class']  = 'enabled settings-3';

                $aButton[3]['link']   = '';
                $aButton[3]['icon']   = 'fa-forward';
                $aButton[3]['text']   = 'Deluxe';
                
                $aButton[3]['color']  = '#CCC';
                  $aButton[3]['script'] = '';
                  $aButton[3]['class']  = 'disabled';

                $aButton[4]['link']   = '';
                $aButton[4]['icon']   = 'fa-fast-forward';
                $aButton[4]['text']   = 'Premium';
                
                $aButton[4]['color']  = '#CCC';
                  $aButton[4]['script'] = '';
                  $aButton[4]['class']  = 'disabled';

                $aButton[5]['link']   = '';
                $aButton[5]['icon']   = 'fa-dashboard';
                $aButton[5]['text']   = 'Optimum';
                
                $aButton[5]['color']  = '#CCC';
                  $aButton[5]['script'] = '';
                  $aButton[5]['class']  = 'disabled';

                return $aButton;
        }

}
