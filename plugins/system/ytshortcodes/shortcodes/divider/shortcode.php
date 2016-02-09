<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function dividerYTShortcode($atts = null, $content = null) {
        extract(ytshortcode_atts(array(
            'style'         => 1,
            'align'         => 'center',
            'icon_divider'  => '',
            'icon_style'    => '1',
            'icon_color'    => '#E5E5E5',
            'icon_size'     => '16px',
            'icon_align'    => 'center',
            'bottom_top'    => '0',
            'color'         => '#999',
            'width'         => 100,
            'margin_top'    => 10,
            'margin_bottom' => 10,
            'margin_left'   => '',
            'margin_right'  => '',
            'text'          => '',
			'border_width'  => '1'
            ), $atts));

            $id = uniqid('ytd').rand().time();
            $classes = array('yt-divider', 'yt-divider-style-'. $style, 'yt-icon-style-'.$icon_style, 'yt-divider-align-'.$align);
            $margin = '';
            $icon = '';
            $css = '';
        if($text !='')
        {
            $icon = '<span style="margin: 0 20px;">'.$text.'</span>';
        }
        else{
            if ($icon_divider !== '') {
            	
                	if (strpos($icon_divider, '/') !== false) {
			            $icon = '<img src="' . yt_image_media($icon_divider) . '" style="width:' .$icon_size. ';" alt="" />';
			        }
			        else if (strpos($icon_divider, 'icon:') !== false) {
			            $icon = '<i class="fa fa-' . trim(str_replace('icon:', '', $icon_divider)) . '"></i>';
			        }
                    $css .= '#'.$id.'.yt-divider > span {font-size:' . $icon_size . 'px;border-color:'.$color.';}';
                    $css .= '#'.$id.'.yt-divider i {color:' . $icon_color.';height:' . $icon_size.';width:' . $icon_size.';padding:' . round($icon_size / 2) . 'px;}';
                     
                }
          
            if ($bottom_top == '2') {
                    $icon = '<span id="'.$id.'_top">' . $icon . ' </span>';
                    $icon .="<script>
                        jQuery(document).ready(function() {
                            var offset = 250;
                            var duration = 300;
                            jQuery('#".$id."_top').click(function(event) {
                                event.preventDefault();
                                jQuery('html, body').animate({scrollTop: 0}, duration);
                                return false;
                            });
                        });
                        </script>";

                }else if($bottom_top =='1'){
                $icon = '<span id="'.$id.'_bottom">' . $icon . '</span>';
                $icon .="<script>
                    jQuery(document).ready(function() {
                        var offset = 250;
                        var duration = 300;
                        jQuery('#".$id."_bottom').click(function(event) {
                            event.preventDefault();
                            jQuery('html, body').animate({scrollTop: jQuery(document).height()}, duration);
                            return false;
                        });
                    });
                    </script>";
            }
            
        }
        if ($style == 7) {
            $css .= '#'.$id.'.yt-divider-style-7 span.divider-left { background-image: -webkit-linear-gradient(45deg, '.$color.' 25%, transparent 25%, transparent 50%, '.$color.' 50%, '.$color.' 75%, transparent 75%, transparent);
            background-image: linear-gradient(45deg, '.$color.' 25%, transparent 25%, transparent 50%, '.$color.' 50%, '.$color.' 75%, transparent 75%, transparent);}';

            $css .= '#'.$id.'.yt-divider-style-7 span.divider-right {background-image: -webkit-linear-gradient(45deg, '.$color.' 25%, transparent 25%, transparent 50%, '.$color.' 50%, '.$color.' 75%, transparent 75%, transparent);
            background-image: linear-gradient(45deg, '.$color.' 25%, transparent 25%, transparent 50%, '.$color.' 50%, '.$color.' 75%, transparent 75%, transparent);}';
        }


        if (($margin_top) or ($margin_right) or ($margin_bottom) or ($margin_left)) {
            $margin  = 'margin: ';
            $margin .= ($margin_top) ? intval($margin_top).'px ' : '0 ';
            $margin .= ($margin_right) ? intval($margin_right) . 'px ' : 'auto ';
            $margin .= ($margin_bottom) ? intval($margin_bottom).'px ' : '0 ';
            $margin .= ($margin_left) ? intval($margin_left) . 'px;' : 'auto;';
        }

        // Get Css in $css variable
        $css .= '#'.$id.'.yt-divider { width:'. intval($width). '%;'.$margin.';text-align: '.$icon_align.';}';
        $css .= '#'.$id.'.yt-divider span:before, #'.$id.' span:after { border-color: '.$color.'; border-width:'.$border_width.'px;}';
        $css .= '#'.$id.'.yt-icon-style-2 > span { background: '.$color.';border-radius: 50%;}';


        // Add CSS in head
        $doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/divider/css/divider.css",'text/css');
        // Output HTML
        return '<div id="'.$id.'" class="'.yt_acssc($classes).'">
                    <span>
                        <span class="divider-left"></span>
                           '. $icon .'
                        <span class="divider-right"></span>
                    </span>
                </div>';
    }
?>