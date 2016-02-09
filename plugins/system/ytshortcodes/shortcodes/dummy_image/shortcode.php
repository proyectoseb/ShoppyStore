<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
    function dummy_imageYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'width'  => 500,
            'height' => 300,
            'theme'  => 'any',
            'class'  => ''
                ), $atts, 'yt_dummy_image');
        $url = 'http://lorempixel.com/' . $atts['width'] . '/' . $atts['height'] . '/';
        if ($atts['theme'] !== 'any')
            $url .= $atts['theme'] . '/' . rand(0, 10) . '/';
        return '<img src="' . $url . '" alt="Dummy Image" width="' . $atts['width'] . '" height="' . $atts['height'] . '" class="yt-dummy-image" />';
    }
?>