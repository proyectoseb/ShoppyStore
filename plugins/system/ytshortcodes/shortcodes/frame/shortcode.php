<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function frameYTShortcode($atts = null, $content = null) {
    $atts = ytshortcode_atts(array(
        'style' => 'default',
        'align' => 'left',
        'class' => ''
            ), $atts, 'yt_frame');
    JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/frame/css/frame.css",'text/css');
    JHtml::_('jquery.framework');
	JHtml::script("plugins/system/ytshortcodes/shortcodes/frame/js/frame.js");
    return '<span class="yt-frame yt-frame-align-' . $atts['align'] . ' yt-frame-style-' . $atts['style'] .'"><span class="yt-frame-inner">' . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</span></span><div class="clear clearfix"></div>';
}
?>