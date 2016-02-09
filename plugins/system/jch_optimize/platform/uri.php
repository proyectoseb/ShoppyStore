<?php

/**
 * JCH Optimize - Joomla! plugin to aggregate and minify external resources for
 * optmized downloads
 *
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2014 Samuel Marshall
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
defined('_JEXEC') or die('Restricted access');

class JchPlatformUri implements JchInterfaceUri
{
        private $oUri;

        /**
         * 
         * @param type $path
         */
        public function setPath($path)
        {
                $this->oUri->setPath($path);
        }
        
        /**
         * 
         * @return type
         */
        public function getPath()
        {
                return $this->oUri->getPath();
        }

        /**
         * 
         * @param array $parts
         * @return type
         */
        public function toString(array $parts = array('scheme', 'user', 'pass', 'host', 'port', 'path', 'query', 'fragment'))
        {
                return $this->oUri->toString($parts);
        }

        /**
         * 
         * @param type $pathonly
         * @return type
         */
        public static function base($pathonly = FALSE)
        {
                if ($pathonly)
                {
                        return str_replace('/administrator', '', JUri::base(TRUE));
                }
                
                return str_replace('/administrator/', '', JUri::base());
        }

        /**
         * 
         * @param type $uri
         * @return \JchPlatformUri
         */
        public static function getInstance($uri = 'SERVER')
        {
                return new JchPlatformUri($uri);
        }
        
        /**
         * 
         * @param type $uri
         * @return type
         */
        private function __construct($uri)
        {
                $this->oUri = clone JUri::getInstance($uri);
                
                return $this->oUri;
        }

        /**
         * 
         * @param type $query
         */
        public function setQuery($query)
        {
                $this->oUri->setQuery($query);
        }

        /**
         * 
         * @return type
         */
        public static function currentUrl()
        {
                return JUri::current();
        }
        
        /**
         * 
         * @param type $host
         */
        public function setHost($host)
        {
                $this->oUri->setHost($host);
        }
        
        /**
         * 
         */
        public function getHost()
        {
                return $this->oUri->getHost();
        }
}
