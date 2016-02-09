<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function documentYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'url'        => '',
            'file'       => null,
            'width'      => 600,
            'height'     => 600,
            'responsive' => 'yes',
            'class'      => ''
                ), $atts, 'yt_document');
        if ($atts['file'] !== null)
            $atts['url'] = $atts['file'];
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/document/css/document.css",'text/css');
        return '<div class="yt-document yt-responsive-media-' . $atts['responsive'] . '"><iframe src="http://docs.google.com/viewer?embedded=true&amp;url=' . yt_image_media($atts['url']) . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" class="yt-document"></iframe></div>';
    }
?>