<?php
   /**
    * @package YT Framework
    * @author Smartaddons http://www.Smartaddons.com
    * @copyright Copyright (c) 2009 - 2014 Smartaddons
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */

    defined('JPATH_BASE') or die;
    jimport('joomla.form.formfield');
    jimport('joomla.filesystem.folder');
    jimport('joomla.filesystem.file');
    
    class JFormFieldThemecolor extends JFormField
    {
      
        protected $type = 'Themecolor';
        protected function getInput()
        {
			$doc = JFactory::getDocument();
            $yttemplate = $this->form->getValue('template');
            $templateStyleDir = JPATH_SITE.'/templates/'.$yttemplate.'/images/styling/*';
            $base_url = JURI::root(true).'/templates/'.$yttemplate.'/images/styling/';
            $root_path = JPATH_SITE.'/templates/'.$yttemplate.'/images/styling/';
            $yt_url = JURI::root(true).'/plugins/system/yt/';

            $folders = glob($templateStyleDir, GLOB_ONLYDIR);
            if( !defined('CURRENT_PRESET') ){
                define('CURRENT_PRESET', $this->value);
                $doc->addScriptDeclaration('var $current_preset = "'.$this->value.'";');
            } 

            $html = '';
            $options = array();

            natsort($folders );
         

            foreach($folders as $folder)
            {
                if( file_exists($root_path.basename($folder).'/thumbnail.png') ) $image = $base_url.basename($folder).'/thumbnail.png';
                else $image = $yt_url.'includes/admin/images/no-preview.png';
				
                $html .='<div class="presets'.(($this->value == basename($folder))?' active':'').'">';
                $html .='<div class="preset-title">';
                $html .= basename($folder);
                $html .='</div>';

                $html .='<div data-preset="'. basename($folder) .'" class="preset-contents">';
                $html .='<label>';
                $html .='<input style="display:none" '.(($this->value == basename($folder))?'checked':'').' value="'.basename($folder).'" type="radio" name="jform[params]['.$this->element['name'].']" />';
                $html .='<img  src="'.$image.'" alt="'.basename($folder).'" />';
                $html .='</div>';

                $html .='</label>';
                $html .='</div>';
            }


            $html .= '';

            return $html; 

        }

        public function getLabel()
        {
            return false;
        }

    }
