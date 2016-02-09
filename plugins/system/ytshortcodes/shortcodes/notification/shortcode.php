<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function notificationYTShortcode( $atts = null, $content = null ) {
        $atts = ytshortcode_atts( array(
                'effect' => 1,
                'button_text'    => 'Open notification',
                'button_class'  => '',
                'close_button'  => '',
                'title' => 'notification Title',
                'title_background' => 'rgba(0,0,0,0.1)',
                'title_color' => '#222222',
                'background' => '#ffffff',
                'color' => '#666666',
                'border' => 'none',
                'shadow' => '0 solid #000000',
                'width' => '640px',
                'height' => 'auto',
                'overlay_background' => '#000',
                'class' => ''
            ), $atts, 'notification' );

        $id = uniqid('ytmodal_').rand().time();
        $id_over = uniqid().rand().time();
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/notification/css/notification.css",'text/css');
        JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/notification/js/notification.js");


        $css = '
          #'.$id.' .yt-modal-content-wrapper { height: '.$atts['height'].'; width: '.$atts['width'].'; background: '.$atts['background'].'; color: '.$atts['color'].'; border: '.$atts['border'].'; box-shadow: '.$atts['shadow'].'; }
          #'.$id.' .yt-modal-title-wrapper { background: '.$atts['title_background'].'; }
          #'.$id.' .yt-modal-title-wrapper h3 { color: '.$atts['title_color'].'; }
        ';
        $doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
        $overlay_style = 'style=" background-color: '.$atts['overlay_background'].';"';
        $close_button = ($atts['close_button'] === 'yes') ? '<a href="javascript:void(0);" class="yt-modal-close"><i class="fa fa-remove"></i></a>' : '';

        $return = '<div class="yt-modal-wrapper">';
        $return .= '<a href="javascript:void(0);" class="yt-modal-trigger '.$atts['button_class'].'" data-modal="'.$id.'">'.$atts['button_text'].'</a>';
        $return .='<div class="yt-modal yt-modal-effect-'.$atts['effect'].'" id="'.$id.'">
            <div class="yt-modal-content-wrapper">';
                $return .= '<div class="yt-modal-title-wrapper">';
                    $return .= '<h3>'.$atts['title'].'</h3>';
                    $return .= $close_button;
                $return .= '</div>';
                $return .= '<div class="yt-content">';
                    $return .= parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content));
                $return .= '</div>
            </div>
        </div>
        <div class="yt-modal-overlay '.$id.'" '.$overlay_style.'></div>';
        $return .= '</div>';

        return $return;
    }
?>