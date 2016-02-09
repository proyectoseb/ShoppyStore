<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function youtubeYTShortcode($atts, $content = null){
	 $return = array();
        $atts = ytshortcode_atts(array(
            'url'        => false,
            'width'      => 600,
            'height'     => 400,
            'autoplay'   => 'no',
            'responsive' => 'yes',
            'class'      => ''
                ), $atts, 'youtube');
        if (!$atts['url'])
            return yt_alert_box(JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_YOUTUBE_CU'), 'warning');
        $id = ( preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $atts['url'], $match) ) ? $match[1] : false;
       
        if (!$id)
        return yt_alert_box(JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_YOUTUBE_CI'), 'warning');
        
        $autoplay = ( $atts['autoplay'] === 'yes' ) ? '?autoplay=1' : '';

        $return[] = '<div class="yt-youtube yt-responsive-media-' . $atts['responsive'] . '">';
        $return[] = '<iframe width="' . $atts['width'] . '" height="' . $atts['height'] . '" src="http://www.youtube.com/embed/' . $id . $autoplay . '" frameborder="0" allowfullscreen="true"></iframe>';
        $return[] = '</div>';
        
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/youtube.css",'text/css');
        return implode('', $return);
}
?>