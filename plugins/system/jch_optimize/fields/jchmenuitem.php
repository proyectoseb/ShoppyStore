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

include_once dirname(dirname(__FILE__)) . '/jchoptimize/loader.php';

if (version_compare(JVERSION, '3.0', '>'))
{

        class JFormFieldJchmenuitemcompat extends JFormFieldMenuitem
        {

                public function setup(SimpleXMLElement $element, $value, $group = NULL)
                {
                        $sValue = $this->setupJchMenuItem($element, $value, $group);

                        try
                        {
                                $this->checkPcreVersion();
                                $oFileRetriever = JchOptimizeFileRetriever::getInstance();
                        }
                        catch (Exception $ex)
                        {
                                $GLOBALS['bTextArea'] = TRUE;

                                JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'warning');

                                return FALSE;
                        }

                        if (!$oFileRetriever->isHttpAdapterAvailable())
                        {
                                return FALSE;
                        }

                        return parent::setup($element, $sValue, $group);
                }

        }

}
else
{
        JFormHelper::loadFieldClass('MenuItem');

        class JFormFieldJchmenuitemcompat extends JFormFieldMenuitem
        {

                public function setup(&$element, $value, $group = NULL)
                {
                        if (version_compare(PHP_VERSION, '5.3.0', '>='))
                        {
                                $sValue = $this->setupJchMenuItem($element, $value, $group);

                                try
                                {
                                        $this->checkPcreVersion();
                                        $oFileRetriever = JchOptimizeFileRetriever::getInstance();
                                }
                                catch (Exception $ex)
                                {
                                        $GLOBALS['bTextArea'] = TRUE;

                                        JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'error');

                                        return FALSE;
                                }

                                if (!$oFileRetriever->isHttpAdapterAvailable())
                                {
                                        return FALSE;
                                }

                                return parent::setup($element, $sValue, $group);
                        }
                        else
                        {

                                JFactory::getApplication()->enqueueMessage(JText::_('This plugin requires PHP 5.3.0 or greater to run. '
                                                . 'Your installed version is ' . PHP_VERSION), 'error');

                                JFormHelper::loadFieldClass('Textarea');

                                return FALSE;
                        }
                }

        }

}

/**
 * 
 */
class JFormFieldJchmenuitem extends JFormFieldJchmenuitemcompat
{

        public $type = 'jchmenuitem';

        /**
         * 
         * @param type $element
         * @param type $value
         * @param type $group
         * @return type
         */
        public function setupJchMenuItem($element, $value, $group = null)
        {
                $GLOBALS['bTextArea'] = FALSE;

                $this->loadResources();

                if (!$value)
                {
                        $value = $this->getHomePageLink();
                }

                return $value;
        }

        /**
         * 
         * @throws Exception
         */
        protected function checkPcreVersion()
        {
                $pcre_version = preg_replace('#(^\d++\.\d++).++$#', '$1', PCRE_VERSION);

                if (version_compare($pcre_version, '7.2', '<'))
                {
                        throw new Exception('This plugin requires PCRE Version 7.2 or higher to run. Your installed version is ' . PCRE_VERSION);
                }
        }

        /**
         * 
         * @return type
         */
        public static function getHomePageLink()
        {
                $oMenu            = JFactory::getApplication()->getMenu('site');
                $oDefaultMenuItem = $oMenu->getDefault();

                return $oDefaultMenuItem->id;
        }

        /**
         * 
         */
        protected function loadResources()
        {
                $oDocument = JFactory::getDocument();
                $sScript   = '';

                if (version_compare(JVERSION, '3.0', '<'))
                {
                        JHtml::stylesheet('plg_jchoptimize/jquery.chosen.min.css', array(), TRUE);

                        JHtml::script('plg_jchoptimize/jquery.min.js', FALSE, TRUE);
                        JHtml::script('plg_jchoptimize/jquery.noconflict.js', FALSE, TRUE);
                        JHtml::script('plg_jchoptimize/jquery.chosen.min.js', FALSE, TRUE);

                        $sScript .= <<<JCHSCRIPT
                                
jQuery(document).ready( function() {
        jQuery(".chzn-custom-value").chosen({width: "240px"});
});
JCHSCRIPT;
                        $sStyle = <<<JCHCSS
                                
.chosen-container{
        float: left;
        margin: 0 7px 7px 0;
        font-size: 12px;
}
.chosen-container-multi .chosen-choices li.search-field input[type=text]{
        padding: 2px;
}
.chosen-container .chosen-results li{
        line-height: 12px;
}  
.pane-down, .pane-down > .panelform {
        overflow: visible !important;
        height: auto !important;
}   
.panelform {
        margin-bottom: 0 !important;
} 
.adminformlist > li:after,  .adminformlist > li:before {
        display: table;
        content: " ";
        line-height: 0;
}
.adminformlist > li:after {
        clear: both;
}

.container-icons {
        display:table; 
        max-width: 320px;
}

div.icon a {
        height: 75px; 
        width: 81px;
}
                                                
div.icon a span {
        font-size: 1.1em;
}
                                
.jchgroup{
        border: 1px #ccc solid; 
        padding: 0 10px 5px;
        background-color: #f9f9f9;
        margin:10px 0;        
}
.jchgroup fieldset{
        background-color: #f9f9f9 !important;                        
}
.jchgroup h4{
        margin: 10px 0;
}
#jform_params_pro_optimize_images-lbl{
        float:none !important;
        margin-bottom: 10px !important;                        
}                                
        
form#style-form > div.width-60{
        width: 57.5%;
} 
form#style-form > div.width-40{
        width: 42.5%;
}                         
JCHCSS;
                        $oDocument->addStyleDeclaration($sStyle);
                }

                $sScript .= <<<JCHSCRIPT
function submitJchSettings(){
        Joomla.submitbutton('plugin.apply');
}                        
jQuery(document).ready(function() {
    jQuery('.collapsible').collapsible();
  });
JCHSCRIPT;

                $oDocument->addScriptDeclaration($sScript);

                JHtml::stylesheet('plg_jchoptimize/icons.css', array(), TRUE);
                $oDocument->addStyleSheet('//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css');

                JHtml::script('plg_jchoptimize/admin-utility.js', FALSE, TRUE);
                JHtml::script('plg_jchoptimize/jquery.collapsible.js', FALSE, TRUE);

                
        }

}
