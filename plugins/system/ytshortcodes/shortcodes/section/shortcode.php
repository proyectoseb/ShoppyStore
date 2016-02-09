<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function sectionYTShortcode($atts = null, $content = null) {
    $atts = ytshortcode_atts(array(
        'background'  => '#ffffff',
        'image'       => '',
        'repeat'      => 'repeat',
        'parallax'    => 'yes',
        'speed'       => '10',
        'max_width'   => '960',
        'margin'      => '0px 0px 0px 0px',
        'padding'     => '30px 0px 30px 0px',
        'border'      => 'none',
        'color'       => '#333333',
        'text_align'  => 'inharit',
        'text_shadow' => 'none',
        'url_youtube' => '',
        'url_webm'    => '',
        'url_vimeo'   => '',
        'font_size'   => '12px',
        'class'		  => ''
            ), $atts, 'section');
    $id_section = uniqid('sec_').rand().time();
    $id_video = uniqid('svb_sec_').rand().time();
    $background = ( $atts['image'] ) ? sprintf('%s %s url(\'%s\') repeat %s', $atts['background'], '50% 0', yt_image_media($atts['image']), ( $atts['parallax'] === 'yes' ) ? 'fixed' : 'scroll' ) : $atts['background'];

    if ($atts['image'] && $atts['parallax'] === 'yes') {
        $atts['class'] .= ' yt-section-parallax';
    }
    if ($atts['url_youtube']) {
        $atts['class'] .= ' yt-panel-clickable';
    }
	JHtml::_('jquery.framework');
    if ($atts['image'] && $atts['parallax'] === 'yes' or $atts['url_youtube']) {
         JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/section/js/section.js");
    }
    
    JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/section/js/modernizr.video.js");
    JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/section/js/swfobject.js");
    JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/section/js/video_background.js");
    JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/section/css/section.css",'text/css');
    $atts['text_align']  = ($atts['text_align'] !='inherit') ? 'text-align:' . $atts['text_align'].';' : '';
    $atts['text_shadow'] = ($atts['text_shadow']) ? ' -webkit-text-shadow:' . $atts['text_shadow'] . ';-moz-text-shadow:' . $atts['text_shadow'] . ';text-shadow:' . $atts['text_shadow'].';' : '';

	if($atts['url_youtube'] !='')
	{
		
		return '<div class="yt-section-forcefullwidth"><div id="'.$id_section.'" class="yt-section-wrapper" data-id="'.$id_section.'"><div class="yt-section '.trim($atts['class']).' " data-speed="' . $atts['speed'] . '" style="background:' . $background . ';margin:' . $atts['margin'] . ';padding:' . $atts['padding'] . ';border-top:' . $atts['border'] . ';border-bottom:' . $atts['border'] . '"><div class="yt-section-content yt-content-wrap" style="max-width:' . $atts['max_width'] . 'px; '.$atts['text_align'].' color:' . $atts['color'] . ';'.$atts['text_shadow'].' font-size:'.$atts['font_size'].'">' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</div><div class="yt-section-overlay"></div><div class="yt-section-video" id="'.$id_video.'" data-id="'.$id_video.'" data-loop="true" data-muted="true" data-autoplay="true" data-ratio="1.77" data-overlay="" data-swfpath=""  data-youtube="'.$atts['url_youtube'].'"><div id="video_background_video_1" style="z-index: 0; position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; overflow: hidden;"></div></div></div></div></div>';
	}
	else if($atts['url_webm'] !='')	
	{
		return '<div class="yt-section-forcefullwidth"><div id="'.$id_section.'" class="yt-section-wrapper" data-id="'.$id_section.'"><div class="yt-section '.trim($atts['class']).' " data-speed="' . $atts['speed'] . '" style="background:' . $background . ';margin:' . $atts['margin'] . ';padding:' . $atts['padding'] . ';border-top:' . $atts['border'] . ';border-bottom:' . $atts['border'] . '"><div class="yt-section-content yt-content-wrap" style="max-width:' . $atts['max_width'] . 'px; '.$atts['text_align'].' color:' . $atts['color'] . ';'.$atts['text_shadow'].' font-size:'.$atts['font_size'].'">' . parse_shortcode(str_replace(array("<br/>","<br />"), " ", $content)) . '</div><div class="yt-section-overlay"></div><div class="yt-section-video" id="'.$id_video.'" data-id="'.$id_video.'" data-loop="true" data-muted="true" data-autoplay="true" data-ratio="1.77" data-overlay="" data-swfpath=""  data-webm="'.$atts['url_webm'].'"><div id="video_background_video_1" style="z-index: 0; position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; overflow: hidden;"><video  autoplay="" loop="" onended="this.play()"><source src="'.$atts['url_webm'].'" type="video/webm"></video></div></div></div></div></div>';
	}
	else
	{
    return '<div class="yt-section '.trim($atts['class']).' " data-speed="' . $atts['speed'] . '" style="background:' . $background . ';margin:' . $atts['margin'] . ';padding:' . $atts['padding'] . ';border-top:' . $atts['border'] . ';border-bottom:' . $atts['border'] . '"><div class="yt-section-content yt-content-wrap" style="max-width:' . $atts['max_width'] . 'px; '.$atts['text_align'].' color:' . $atts['color'] . ';'.$atts['text_shadow'].' font-size:'.$atts['font_size'].'">' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</div></div>';
    }
}
?>