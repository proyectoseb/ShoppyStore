<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function BoxYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'style'       => 'default',
            'title'       => 'Box Title',
            'title_color' => '#FFFFFF',
            'box_color'   => '#333333',
            'color'       => null, 
            'radius'      => '',
            'class'       => ''
        ), $atts, 'box');
        // Initioal Variables
        $id = uniqid('yt_box_').rand().time();
        $radius ='';
        $css = '';
        // Color Manage
        if ($atts['color'] !== null)
            $atts['box_color'] = $atts['color'];

        // Radius Manage
        if ($atts['radius']) {
            $radius = ( $atts['radius'] != '0' ) ? 'border-radius:' . $atts['radius'] . 'px;' : '';
        }

        // Get Css in $css variable
        $css .= '#'.$id.'{'.$radius.'border-color:' . $atts['box_color']. ';} #'.$id.' .yt-box-title { background-color:' . $atts['box_color'] . ';color:' . $atts['title_color'] . ';}';
        // Add CSS in head
        $doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/box/css/box.css");
        // Output HTML
        $return = '<div id="'.$id.'" class="yt-clearfix yt-box yt-box-style-' . $atts['style'] .'">
                    <div class="yt-box-title">'. $atts['title'] . '
                    </div>
                    <div class="yt-box-content yt-clearfix">' . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)).'</div>
                </div>';
        return $return;
    }
?>