<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function fancy_textYTShortcode($atts,$content = null)
{
	$atts = ytshortcode_atts(array(
            'type'   => '1',
            'tags'   => 'Hello, Text',
            'class'  => '',
			'color'  => '',
			'size'   => ''
                ), $atts, 'fancy_text');

        // Inistial Variables
        $id = uniqid("ytft_").rand().time();
        $tags = array();
        $tag = '';

        // class manage
        $classes = array('yt-fancy-text', 'yt-fteffect'.$atts['type'], trim($atts['class']));

        // Fancy Text interchangeable tag spliting
        if($atts['tags']) {
            $tags = explode(',', $atts['tags']);
            foreach ($tags as $word) {
                $tag .='<b>'.$word.'</b>';
            }
            $tag = str_replace('<b>'.$tags['0'].'</b>', '<b class="is-visible">'.$tags['0'].'</b>' , $tag);
        }

        // Manage class for different type of Fancy Text
        if ($atts['type'] == 1 or $atts['type'] == 2 or $atts['type'] == 4 or $atts['type'] == 5)
            $classes[] = 'yt-ft-letters';

        // Add css file in head
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/fancy_text/css/fancy_text.css",'text/css',"screen");
        // Add javascript file in head
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/fancy_text/js/fancy_text.js");
        // HTML Ourput
        $return = "
            <span id='".$id."' class='".yt_acssc($classes). "' style='color:".$atts['color']."; font-size:".$atts['size']."px;'>
                <span class='yt-ft-wrap'>
                    ".$tag."
                </span>
            </span>";

        return $return;
}
?>