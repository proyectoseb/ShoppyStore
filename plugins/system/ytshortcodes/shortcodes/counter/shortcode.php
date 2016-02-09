<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function counterYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
			'align'         => 'none',
			'text_align'    => 'top',
			'width'         => '',
			'count_start'   => 1,
			'count_end'     => 5000,
			'counter_speed' => 5,
			'separator'     => 'no',
			'prefix'        => '',
			'suffix'        => '',
			'count_color'   => '',
			'count_size'    => '32px',
			'text_color'    => '',
			'text_size'     => '14px',
			'icon'          => '',
			'icon_color'    => '',
			'icon_size'     => '24px',
			'border'        => '',
			'background'    => '',
			'border_radius' => '',
			'class'         => ''
            ), $atts);

        $id = uniqid('ytc').rand().time();
        $css = '';

        if (strpos($atts['icon'], '/') !== false) {
            $atts['icon'] = '<img src="' . yt_image_media($atts['icon']) . '" style="width:' . $atts['icon_size'] . ';" alt="" />';
        }
        else if (strpos($atts['icon'], 'icon:') !== false) {
            $atts['icon'] = '<i class="fa fa-' . trim(str_replace('icon:', '', $atts['icon'])) . '"></i>';
        }

        $icon = ($atts['icon']) ? '<div class="yt-counter-icon" style="float:left; margin-right:10px">'. $atts['icon'] .'</div>' : '';
        $border = ($atts['border']) ? 'border:'.$atts['border'].';' : '';
        $background = ($atts['background']) ? 'background-color:'.$atts['background'].';' : '';

        if ($border or $background) {
            $css .= '#'.$id.' {' .$background.$border.'}';
        }

        $count_color = ($atts['count_color']) ? 'color:' . $atts['count_color'] . ';' : '';
        $text_color = ($atts['text_color']) ? 'color:' . $atts['text_color'] . ';' : '';
        $icon_color = ($atts['icon_color']) ? 'color:' . $atts['icon_color'] . ';' : '';

        $css .= '#'.$id.' .yt-counter-number {font-size: '.$atts['count_size'].'; '. $count_color .' }';
        $css .= '#'.$id.' .yt-counter-text {'. $text_color .' font-size: '.$atts['text_size'].';}';
        $css .= '#'.$id.' i {' . $icon_color .'' . 'font-size:' . $atts['count_size'] . '; line-height: 1.42857;}';
		 // Add css file in head
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/counter/css/counter.css",'text/css',"screen");
        // Add javascript file in head
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/jquery.appear.js");
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/counter/js/countUp.js");

        $return = '<div id="'. $id .'" class="yt-counter-wrapper clearfix yt-counter-'.$atts['align'].' '. trim($atts['class']) . ' pull-'.$atts['text_align'].'" style="width:'.$atts['width'].'; overflow:auto; border-radius:'.$atts['border_radius'].'" data-id="'.$id.'" data-from="'.$atts['count_start'].'" data-to="'.$atts['count_end'].'" data-speed="'.$atts['counter_speed'].'" data-separator="'.$atts['separator'].'" data-prefix="'.$atts['prefix'].'" data-suffix="'.$atts['suffix'].'">';
        $return .= $icon;
        $return .= '<div class="yt-counter-desc" >
                <div id="'. $id .'_count"  class="yt-counter-number">
                </div>
                <div class="yt-counter-text">'. parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) .'</div>
            </div>';
		$return .= '</div>';

        return $return;
    }
?>