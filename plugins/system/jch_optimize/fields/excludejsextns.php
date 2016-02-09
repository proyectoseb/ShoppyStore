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
        class JFormFieldExcludejsextns extends JFormFieldTextarea
        {
                public $type = 'excludejsextns';
        }
}
else
{
        include_once dirname(__FILE__) . '/exclude.php';

        class JFormFieldExcludejsextns extends JFormFieldExclude
        {
                public $type = 'excludejsextns';
                protected $jch_params = 'excludeJsComponents';

                /**
                 * 
                 * @return type
                 */
                protected function getFieldOptions()
                {
                        return $this->prepareFieldOptions('js', $this->jch_params, 'extension');
                }

        }

}