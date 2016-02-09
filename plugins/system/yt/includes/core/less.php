<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
include_once('lessphp/lessc.inc.php');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
class YTLess extends lessc{

    public static function addStylesheet($pfile){
        global $app;
        $doc = JFactory::getDocument();

            if( JRequest::getVar('less2css')=='all' ){
                $cssfile = str_replace('/less/', '/css/', str_replace('.less', '.css', $pfile));
                // For *-rtl.css
                $path_ = str_replace('.less', 'template-rtl.less', $pfile);
                if( is_file($path_) ){
                    $topath_ = str_replace('.css', 'template-rtl.css', $cssfile);
                    YTLess::complieToCss($path_, $topath_);
                }
                // For template-*.css
                if(basename($pfile)=='template.less'){
                    YTLess::complieToCss(dirname($pfile).'/template-ie9.less', dirname($cssfile).'/template-ie9.css');

                    YTLess::complieToCss(dirname($pfile).'/template-ie10.less', dirname($cssfile).'/template-ie10.css');
                    YTLess::complieToCss(dirname($pfile).'/cpanel.less', dirname($cssfile).'/cpanel.css');
                    YTLess::complieToCss(dirname($pfile).'/sticky.less', dirname($cssfile).'/sticky.css');
                    $dircolor = scandir(dirname($pfile).'/color');
                    for($i=0; $i<count($dircolor); $i++){

                        if($dircolor[$i] =='variables_color.less') continue;
                        if(is_file(dirname($pfile).'/color/'.$dircolor[$i])){
                            $tcontent = JFile::read(JPATH_ROOT.'/'.dirname($pfile).'/color/'.$dircolor[$i]);
                            JFile::write(JPATH_ROOT.'/'.dirname($pfile).'/color/variables_color.less', $tcontent);
                            $path_ = $pfile;
                            $topath_ = dirname($pfile).'/template-'.$dircolor[$i];
                            $topath_ = str_replace('/less/', '/css/', str_replace('.less', '.css', $topath_));
                            YTLess::complieToCss($path_, $topath_);
                        }
                    }
                }else{
                    YTLess::complieToCss($pfile, $cssfile);
                }
            }else{
                $cssfile = YTLess::buidStyleSheet($pfile);
            }

            $doc->addStylesheet($cssfile);

    }
    public static function buidStyleSheet($pfile){
        global $app;
        $fileName = str_replace('/', '_', $pfile);
        $cssfile = $app->getTemplate(true)->params->get('optimizeFolder', 'yt-assets').'/developing/'.$fileName;
        $cssurl = str_replace('.less', '.css', $cssfile);

        if (!is_file ($cssurl)|| (filemtime($cssurl) < filemtime($pfile)) ) {
            YTLess::complieToCss($pfile, $cssurl);
        }else{
            YTLess::hasNew($pfile, $cssurl, $pfile);
        }
        return $cssurl;
    }
    public static function hasNew($pfile, $cssurl, $pthfile){
        $content = JFile::read($pthfile);
        $arrImport = preg_split ('#^\s*@import\s+"([^"]*)"\s*;#im', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        $import = false;
        foreach ($arrImport as $s) {
            if ($import) {
                $import = false;
                $url = YTPath::cleanPath (dirname ($pthfile).'/'.$s);
                //echo $url.' vs '.$cssurl.' ||||||| '.filemtime($cssurl).' vs '.filemtime($url).'<br/>';
                if(is_file($cssurl) && filemtime($cssurl) < filemtime($url)){
                    YTLess::complieToCss($pfile, $cssurl);
                    return;
                }
                YTLess::hasNew($pfile, $cssurl, $url);
            } else {
                $import = true;
                $s = trim ($s);
            }
        }
        return;
    }
    public static function complieToCss($path, $topath){

        $realpath = realpath(JPATH_ROOT.'/'.$path); //echo 'dungnv:'.$realpath.'<br/>';
        // check path
        if(!is_file($realpath)){
        //if (!JPath::check ($realpath)){
            return;
        }
        // Get file content
        $content = JFile::read($path);
        // remove comments
        //$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
        // split into array, separated by the import
        $arrImport = preg_split ('#^\s*@import\s+"([^"]*)"\s*;#im', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        // compile chuck
        $import = false;
        $output = '';
        foreach ($arrImport as $s) {
            if ($import) {
                $import = false;
                // process import file
                $url = YTPath::cleanPath (dirname ($path).'/'.$s);
                $importcontent = JFile::read(JPATH_ROOT.'/'.$url);
                $output .= "#less-file-path{content: \"$url\";}\n".$importcontent . "\n\n";
            } else {
                $import = true;
                $s = trim ($s);
                if ($s) {
                    $output .= "#less-file-path{content: \"$path\";}\n" . $s . "\n\n";
                }
            }
        }
        $less = new lessc;
        try{
            $output = $less->compile($output);
        }catch(Exception $e){
            echo "fatal error: ".$e->getMessage().'<br/>';
        }
        $arr = preg_split ('#^\s*\#less-file-path\s*{\s*[\r\n]*\s*content:\s*"([^"]*)";\s*[\r\n]*\s*}#im', $output, -1, PREG_SPLIT_DELIM_CAPTURE);
        $output = '';
        $file = '';
        $isfile = false;
        foreach ($arr as $s) {
            if ($isfile) {
                $isfile = false;
                $file = $s;
                $relpath = $topath ? YTPath::relativePath(dirname($topath), dirname($file)) : JURI::base(true).'/'.dirname($file);
            } else {
                $output .= ($file ? YTPath::updateUrl ($s, $relpath) : $s) . "\n\n";
                $isfile = true;
            }
        }
        $output = str_replace("url('/", "url('", $output);
        // remove the dupliate clearfix at the beggining if not bootstrap.css file
        if (!preg_match ('#bootstrap.less#', $path)) {
            $arr = preg_split('/[\r?\n]{2,}/', $output);
            // ignore first one, it's clearfix
            array_shift($arr);
            $output = implode("\n", $arr);
        }

        if ($topath) {
            $tofile = JPATH_ROOT.'/'.$topath;
            if (!is_dir (dirname($tofile))) {
                JFolder::create (dirname($tofile));
            }
            return JFile::write($tofile, $output);
        }

        return $output;
    }
    public static function compileAll ($theme = null) {

        $less = new self;
        // compile all css files
        $files = array ();
        $lesspath = 'templates/'.YT_TEMPLATENAME.'/less/';
        $csspath = 'templates/'.YT_TEMPLATENAME.'/css/';

        // get single files need to compile
        $lessFiles = JFolder::files (JPATH_ROOT.'/'.$lesspath, '.less'); var_dump($lessFiles);
        $lessContent = '';
        foreach ($lessFiles as $file) {
            $lessContent .= JFile::read (JPATH_ROOT.'/'.$lesspath.$file)."\n";
            // get file imported in this list
        }
        if (preg_match_all('#^\s*@import\s+"([^"]*)"#im', $lessContent, $matches)) {
            foreach ($lessFiles as $f) {
                if (!in_array($f, $matches[1])) $files[] = substr($f, 0, -5);
            }
        }
        echo '<br/>'; var_dump($files); die();
        if (!$theme || $theme == 'default') {
            self::buildVars('');
            // compile default
            foreach ($files as $file) {
                $less->compileCss ($lesspath.$file.'.less', $csspath.$file.'.css');
            }
        }
        // compile themes css
        if (!$theme) {
            // get themes
            $themes = JFolder::folders (JPATH_ROOT.'/'.$lesspath.'/themes');
        } else {
            $themes = (array) ($theme);
        }
        foreach ($themes as $t) {
            self::buildVars($t);
            // compile
            foreach ($files as $file) {
                $less->compileCss ($lesspath.$file.'.less', $csspath.'themes/'.$t.'/'.$file.'.css');
            }
        }
    }
}

class YTPath extends JObject{

    /**
     * Store current source value for updateUrl function
     */
    protected static $srcurl = '';


    public static function cleanPath ($path) {
        $pattern = '/\w+\/\.\.\//';
        while(preg_match($pattern,$path)){
            $path = preg_replace($pattern, '', $path);
        }
        return $path;
    }

    public static function relativePath($path1, $path2='') {
        // config params
        if ($path2 == '') {
            $path2 = $path1;
            $path1 = getcwd();
        }

        // absolute path         //has protocol                        //data protocol
        if ($path2[0] === '/' || strpos($path2, '://') !== false || strpos($path2, 'data:') ===  0){
            return $path2;
        }

        //Remove starting, ending, and double / in paths
        $path1 = trim($path1,'/');
        $path2 = trim($path2,'/');
        while (substr_count($path1, '//')) $path1 = str_replace('//', '/', $path1);
        while (substr_count($path2, '//')) $path2 = str_replace('//', '/', $path2);

        //create arrays
        $arr1 = explode('/', $path1);
        if ($arr1 == array('')) $arr1 = array();
        $arr2 = explode('/', $path2);
        if ($arr2 == array('')) $arr2 = array();
        $size1 = count($arr1);
        $size2 = count($arr2);

        //now the hard part :-p
        $path='';
        for($i=0; $i<min($size1,$size2); $i++)
        {
            if ($arr1[$i] == $arr2[$i]) continue;
            else $path = '../'.$path.$arr2[$i].'/';
        }
        if ($size1 > $size2)
            for ($i = $size2; $i < $size1; $i++)
                $path = '../'.$path;
        else if ($size2 > $size1)
            for ($i = $size1; $i < $size2; $i++)
                $path .= $arr2[$i].'/';

        return rtrim ($path, '/');
    }

    public static function updateUrl ($css, $src) {
        self::$srcurl = $src;

        $css = preg_replace_callback('/@import\\s+([\'"])(.*?)[\'"]/', array('YTPath', 'replaceurl'), $css);
        $css = preg_replace_callback('/url\\(\\s*([^\\)\\s]+)\\s*\\)/', array('YTPath', 'replaceurl'), $css);

        return $css;
    }

    public static function replaceurl ($matches) {
        $isImport = ($matches[0][0] === '@');
        // determine URI and the quote character (if any)
        if ($isImport) {
            $quoteChar = $matches[1];
            $uri = $matches[2];
        } else {
            // $matches[1] is either quoted or not
            $quoteChar = ($matches[1][0] === "'" || $matches[1][0] === '"')
                ? $matches[1][0]
                : '';
            $uri = ($quoteChar === '')
                ? $matches[1]
                : substr($matches[1], 1, strlen($matches[1]) - 2);
        }

        // root-relative       protocol (non-data)             data protocol
        if ($uri[0] !== '/' && strpos($uri, '://') === false && strpos($uri, 'data:') !==  0){
            $uri = self::cleanPath (self::$srcurl.'/'.$uri);
        }

        return $isImport
            ? "@import {$quoteChar}{$uri}{$quoteChar}"
            : "url({$quoteChar}{$uri}{$quoteChar})";
    }
}
?>
