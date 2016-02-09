<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function vimeoYTShortcode($atts, $content = null){
	 $return = array();
        $atts = ytshortcode_atts(array(
            'url'        => false,
            'width'      => 600,
            'height'     => 400,
            'autoplay'   => 'no',
            'responsive' => 'yes',
            'class'      => ''
        ), $atts, 'vimeo');

        if (!$atts['url'])
            return yt_alert_box(JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VIMEO_CU'), 'warning');
        $id = ( preg_match('~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix', $atts['url'], $match) ) ? $match[1] : false;
        if (!$id)
            return yt_alert_box(JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_VIMEO_CI'), 'warning');

        $autoplay = ( $atts['autoplay'] === 'yes' ) ? '&amp;autoplay=1' : '';
       
        $return[] = '<div class="yt-vimeo yt-responsive-media-' . $atts['responsive'] . '">';
        $return[] = '<iframe width="' . $atts['width'] . '" height="' . $atts['height'] . '" src="//player.vimeo.com/video/' . $id . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff' . $autoplay . '" frameborder="0" allowfullscreen="true"></iframe>';
        $return[] = '</div>';

		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/vimeo/css/vimeo.css",'text/css');
        return implode('', $return);
}
?>