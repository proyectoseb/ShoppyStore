<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function shadowYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'style'  => 'default',
            'inline' => 'no',
            'class'  => ''
                ), $atts, 'yt_shadow');
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/shadow/css/shadow.css",'text/css');
        return '<div class="yt-shadow-wrap yt-content-wrap yt-shadow-inline-' . $atts['inline'] . trim($atts['class']) . '"><div class="yt-shadow yt-shadow-style-' . $atts['style'] . '">' . parse_shortcode(str_replace(array("<br/>","<br />"), " ", $content)) . '</div></div>';
}
?>