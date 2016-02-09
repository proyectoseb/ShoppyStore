<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function iconYTShortcode($atts, $content = null ){
		$atts = ytshortcode_atts(array(
            'icon'       => 'icon: heart',
            'background' => '#eeeeee',
            'color'      => '#333333',
            'size'       => '20',
            'shape_size' => '', // dep 1.6.7
            'padding'    => '15px',
            'radius'     => '3px',
            'border'     => 'none',
            'margin'     => '',
            'url'        => '',
            'target'     => 'blank',
            'class'      => ''
                ), $atts, 'icon');

        $id = uniqid('ytico_').rand().time();
        $css = '';

        // dep 1.6.7
        if ($atts['shape_size']) {
            $atts['padding'] = $atts['shape_size'];
        }


        if ($atts['margin']) {
            $css .= '#'.$id.'.yt-icon{margin:' . $atts['margin'] .'}';
        }
        if (strpos($atts['icon'], '/') !== false) {
            $css .= '#'.$id.'.yt-icon img{ width:' . $atts['size'] . 'px;height:' . $atts['size'] . 'px;background:' . $atts['background'] . ';-webkit-border-radius:' . $atts['radius'] . ';border-radius:' . $atts['radius'] . ';border: '. $atts['border'].';padding:' . $atts['padding'] . '; }';
        }
        else if (strpos($atts['icon'], 'icon:') !== false) {
            $css .= '#'.$id.'.yt-icon i{ font-size:' . $atts['size'] . 'px;line-height:' . $atts['size'] . 'px;background:' . $atts['background'] . ';color:' . $atts['color'] . ';-webkit-border-radius:' . $atts['radius'] . ';border-radius:' . $atts['radius'] . ';border: '. $atts['border'].'; padding:' . $atts['padding'] . '; }';
        }


        if (strpos($atts['icon'], '/') !== false) {
            $atts['icon'] = '<img src="' . yt_image_media($atts['icon']) . '" alt="" width="' . intval($atts['size']) . 'px;" height="' . intval($atts['size']) . 'px" />';
        }
        // Font-Awesome icon
        else if (strpos($atts['icon'], 'icon:') !== false) {
            $atts['icon'] = '<i class="fa fa-' . trim(str_replace('icon:', '', $atts['icon'])) . '"></i>';
        }

        // Prepare text
        if ($content)
            $content = '<span class="yt-icon-text">' . $content . '</span>';

        if (!$atts['url']) {
            $icon = '<span id="'.$id.'" class="yt-icon">' . $atts['icon'] . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</span>';
        } else {
            $icon = '<a id="'.$id.'" href="' . $atts['url'] . '" class="yt-icon" target="_' . $atts['target'] . '">' . $atts['icon'] . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</a>';
        }
        // Asset added
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/icon/css/icon.css",'text/css');
        $doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
        return $icon;
    }
?>