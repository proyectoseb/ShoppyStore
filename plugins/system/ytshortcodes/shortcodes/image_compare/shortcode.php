<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function image_compareYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'before_image' => '',
            'after_image'  => '',
            'orientation'  => '',
            'before_text'  => 'Original',
            'after_text'   => 'Modified',
            'class'        => ''
        ), $atts, 'image_compare');

        // Unique Id
        $id = uniqid("ytic").rand().time();

        if (yt_image_media($atts['before_image']) && yt_image_media($atts['after_image'])) {

            $orientation = ($atts['orientation'] == 'horizontal') ? 'data-orientation="horizontal"': '';

            $css = '#'.$id.' .twentytwenty-before-label:before {content: "'. $atts['before_text'] .'"}';
            $css .= '#'.$id.' .twentytwenty-after-label:before {content: "'. $atts['after_text'] .'"}';


            // Css Adding in Head
            JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/image_compare/css/image_compare.css",'text/css');
            // JavaScipt additon in Head
			JHtml::_('jquery.framework');
            JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/jquery.twentytwenty.js");
            JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/jquery.event.move.js");
            // OUtput Structure in  here
            $return = '
            <div id="'.$id.'" class="twentytwenty-container'.trim($atts['class']).'" data-orientation="horizontal">
                <img src="'.yt_image_media($atts['before_image']).'" alt="'.$atts['before_text'].'">
                <img src="'.yt_image_media($atts['after_image']).'" alt="'.$atts['before_text'].'">
            </div>';
            $js ='jQuery(window).load(function(){
              jQuery("#'.$id.'").twentytwenty({orientation: \''.$atts['orientation'].'\'});
            });';
            $doc = JFactory::getDocument();
			$doc->addStyleDeclaration($css);
			$doc->addScriptDeclaration($js);
        } else $return = yt_alert_box('You can compare two images by using this shortcode', 'warning');
        return $return;
    }
?>