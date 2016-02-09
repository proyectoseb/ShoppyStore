<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

defined('_JEXEC') or die; 
function audioYTShortcode($atts, $content = null){
	$atts = ytshortcode_atts(array(
            'style'      => 'dark',
            'url'      => false,
            'width'    => '100%',
            'title'    => '',
            'autoplay' => 'no',
            'volume' => 50,
            'loop'     => 'no',
            'class'    => ''
        ), $atts, 'audio');

        $id = uniqid('ytap').rand().time();

        // Audio URL check
        if (!$atts['url'])
            return yt_alert_box(JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_AUDIO_CU'), 'warning');
        
        $width = ( $atts['width'] !== 'auto' ) ? 'max-width:' . $atts['width'] : '';

        // Add CSS JS file in head
		$cssFile = JUri::base()."plugins/system/ytshortcodes/shortcodes/audio/css/jplayer.skin.css";
		
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/audio/css/jplayer.skin.css");
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/audio/js/jplayer.js");
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/audio/js/audio.js");
        // Output HTML
        $output = '<div id="' . $id . '_container" class="yt-clearfix yt-audio jPlayer audioPlayer jPlayer-'.$atts['style'].'" data-id="' . $id . '" data-audio="'.yt_image_media($atts['url']) . '" data-swf="' . (JUri::root() . 'plugins/system/ytshortcodes/other/jplayer.swf') . '" data-autoplay="' . $atts['autoplay'] . '" data-volume="' . $atts['volume'] . '" data-loop="' . $atts['loop'] . '">
                        <div class="playerScreen">
                            <div id="' . $id . '" class="jPlayer-container"></div>
                        </div>
                        <div class="controls">
                            <div class="controlset left">
                                <a tabindex="1" href="#" class="play smooth"><i class="fa fa-play"></i></a>
                                <a tabindex="1" href="#" class="pause smooth"><i class="fa fa-pause"></i></a>
                            </div>
                            <div class="controlset right-volume">
                                <a tabindex="1" href="#" class="mute smooth"><i class="fa fa-volume-up"></i></a>
                                <a tabindex="1" href="#" class="unmute smooth"><i class="fa fa-volume-off"></i></a>
                            </div>
                            <div class="volumeblock">
                                <div class="volume-control"><div class="volume-value"></div></div>
                            </div>
                            <div class="jpprogress-block">
                                <div class="timer current"></div>
                                <div class="timer duration"></div>
                                <div class="jpprogress">
                                    <div class="seekBar">
                                        <div class="playBar"></div>
                                    </div>
                                </div>
                            </div>
         
                        </div>
                    </div>';

        return $output;
}
?>