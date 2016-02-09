`<?php
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
   
	
    class JFormFieldGfonts extends JFormField
    {
       
    protected $type = 'Gfonts';
    protected function getInput(){
	
		global $expose;

        $html = '';
        $fonts = '';
        $options = array();

        $url = JURI::getInstance();
        $id = $url->getVar('id');

        // Initialize some field attributes.
        $class = $this->element['class'];
        $selectClass = 'class="gfonts"';

        $pretext        = ($this->element['pretext'] != NULL) ? '<span class="pre-text hasTip" title="::'. JText::_(($this->element['pre-desc']) ? $this->element['pre-desc'] : $this->description) .'">'. JText::_($this->element['pretext']). '</span>' : '';

        $posttext       = ($this->element['posttext'] != NULL) ? '<span class="post-text">'.JText::_($this->element['posttext']).'</span>' : '';

        $wrapstart  = '<div class="field-wrap fonts-list clearfix '.$class.'">';
        $wrapend    = '</div>';

        //create a google font list file on admin folder to avoid api call and slow down the admin panel
        $fileName = 'gfonts.txt';
		$template = $this->form->getValue('template');
        $path = JPATH_ROOT . '/templates/' .$template. '/asset/' . $fileName;
				
        if(!JFile::exists($path) OR JFile::read($path) == NULL){
            $this->createFontList($path);
        }
		

        $fontsPath = JPATH_ROOT . '/templates/' .$template. '/fonts/';
		//var_dump( $path);die();
        if ( JFolder::exists($fontsPath) )
        {
            $fontFolders = JFolder::listFolderTree($fontsPath, $filter = '', $maxLevel = 1, $level = 0, $parent = 0);
        }

        $data = JFile::read($path);
        $data = explode(';', $data);

        //add none
        $options[] = JHtml::_('select.option', '0', JText::alt('JOPTION_DO_NOT_USE', 'language'));

        $options[] = JHtml::_('select.option', '-1', JText::alt('---------- General fonts ----------', 'language'));

        $options[] = JHtml::_('select.option', '\'Arial\', Helvetica, sans-serif', JText::alt('"Arial", Helvetica, sans-serif','language'));
        $options[] = JHtml::_('select.option', '\'Times New Roman\', Times, serif', JText::alt('"Times New Roman", Times, serif', 'language'));
        $options[] = JHtml::_('select.option', '\'Courier New\', Courier, monospace', JText::alt('"Courier New", Courier, monospace', 'language'));
        $options[] = JHtml::_('select.option', '\'Georgia\',Times New Roman, Times, serif', JText::alt('"Georgia",Times New Roman, Times, serif', 'language'));
        $options[] = JHtml::_('select.option', '\'Verdana\', Arial, Helvetica, sans-serif', JText::alt('"Verdana", Arial, Helvetica, sans-serif', 'language'));

       
        $options[] = JHtml::_('select.option', '-1', JText::alt('---------- Google fonts ----------',preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)));
        foreach($data as $val){
			//var_dump($val);
            list($fontVal, $fontName) = explode('=', "$val=");
            $fontVal = str_replace(' ','+',$fontVal);
            $options[] = JHtml::_('select.option',$fontVal, $fontName);
        }

        //pop the last empty array
        array_pop($options);


        $html .= '<a class="link-gfont" href="http://www.google.com/webfonts" target="_blank">'. JText::_('GOOGLE_FONT_LINK_LABLE') .'</a>';
        $html .= JHtml::_('select.genericlist', $options, $this->name , $selectClass, 'value', 'text', $this->value, $this->id);

        return $wrapstart . $pretext. $html . $posttext . $wrapend;
    }

    private function createFontList($path){
        //output buffer
        $buffer = '';

        //Google webfont api key. you can change it with yours to avoide api limit
        $apiKey = 'AIzaSyCCRvYb-KRB-uyWAgTDyrRRHxitVfK7aFE';
        $url = "https://www.googleapis.com/webfonts/v1/webfonts?key=$apiKey";

       // curl check
		if (!function_exists('curl_version')) {
			$msg = "<strong>cURL is required to load Google Fonts. </strong> Learn more at <a href='http://www.php.net/manual/en/book.curl.php'>http://www.php.net</a>";

			return "
				<div class='alert-message error'>
                    <p>$msg</p>
				</div>
			";
		}

        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        @curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        @curl_setopt($process, CURLOPT_MAXREDIRS, 20);
        $data = curl_exec($process);
        curl_close($process);
        $data = json_decode($data);

        $variants = array(
            'regular' => array('400'=> ''),
            '300'=>' Light ',
			'600' => ' Semi Bold',
            '700' => ' Bold'
        );

        foreach($data->items as $font){
            foreach($font->variants as $variant){
                if(array_key_exists($variant,$variants)){
                    if(is_array($variants[$variant])){
                        foreach($variants[$variant] as $key => $val){
                            $fontName = $font->family . $val;
                            $fontVal = $key;
                        }
                    }else{
                        $fontName = $font->family . $variants[$variant];
                        $fontVal = $variant;
                    }

                    //regular or 400 variation will only have the name
                    if($fontVal == 'regular' OR $fontVal == '400'){
                        $fontVal = $font->family;
                    }else{
                        $fontVal = $font->family . ':' . $fontVal;
                    }

                    $buffer .= "$fontVal=$fontName".',';
                }
            }
        }

        JFile::write($path,$buffer);

    }
}

