<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function splashYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'style'   => 'dark',
            'width'   => 480,
            'opacity' => 80,
            'onclick' => 'close-bg',
            'url'     => 'http://www.bdthemes.com',
            'delay'   => 0,
            'esc'     => 'yes',
            'close'   => 'yes',
            'class'   => '',
            'background' =>'',
            'color' =>'',
            'align' =>'none'
                ), $atts, 'splash');

        $atts['opacity'] = (!is_numeric($atts['opacity']) || $atts['opacity'] > 100 || $atts['opacity'] < 0 ) ? 0.8 : $atts['opacity'] / 100;

        if (@$_REQUEST["action"] == 'yt_generator_preview') {
            return "This shortcode doesn't work in live preview. Please insert it into editor and preview on the site.";
        }
        // add stylesheet
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/magnific-popup.css",'text/css');
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/splash/css/splash.css",'text/css');
        // Add javascript file in head
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/magnific-popup.js");
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/splash/js/splash.js");
		$style='';
		if($atts['background'] != null && $atts['color'] !=null)
		{
			$style = 'style ="background:'.$atts['background'].' ; color: '.$atts['color'].'"';
		}
		if($atts['style']=="incisor")
		{
			return '<div class="yt-splash" data-esc="' . $atts['esc'] . '" data-close="' . $atts['close'] . '" data-onclick="' . $atts['onclick'] . '" data-url="' . $atts['url'] . '" data-opacity="' . (string) $atts['opacity'] . '" data-width="' . $atts['width'] . '" data-style="yt-splash-style-' . $atts['style'] . '" data-delay="' . (string) $atts['delay'] . '"><div class="yt-splash-screen yt-content-wrap pull-'.$atts['align'].'' . trim($atts['class']) . '" '.$style.'>' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '<div class="yt-splash-screen-top" style="width:'.$atts['width'].'px"></div><div class="yt-splash-screen-bottom" style="width:'.$atts['width'].'px"></div></div></div>';
		}
		else
		{
			return '<div class="yt-splash" data-esc="' . $atts['esc'] . '" data-close="' . $atts['close'] . '" data-onclick="' . $atts['onclick'] . '" data-url="' . $atts['url'] . '" data-opacity="' . (string) $atts['opacity'] . '" data-width="' . $atts['width'] . '" data-style="yt-splash-style-' . $atts['style'] . '" data-delay="' . (string) $atts['delay'] . '"><div class="yt-splash-screen yt-content-wrap pull-'.$atts['align'].'' . trim($atts['class']) . '" '.$style.'>' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</div></div>';
		}
    }
?>