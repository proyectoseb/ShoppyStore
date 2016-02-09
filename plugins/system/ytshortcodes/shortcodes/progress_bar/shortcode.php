<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function progress_barYTShortcode($atts,$content = null)
{
	$atts = ytshortcode_atts(array(
			'type_change'		   => '',
            'style_linear'        => 'default',
            'percent'      => 75,
            'show_percent' => 'yes',
            'text'         => '',
            'bar_color'    => '',
            'fill_color'   => '',
            'text_color'   => '',
            'animation'    => 'easeInOutExpo',
            'duration'     => 1.5,
            'delay'        => 0.3,
            'class'        => '',
			'data_type'    =>'',
			'style_circle' =>'circle1',
			'icon_circle'  =>''
        ), $atts, 'progress_bar');
		$id = uniqid('ytp').rand().time();
		$css='';
		$return ='';
        $classes = array('yt-progress-bar', 'yt-progress-bar-style-' . $atts['style_linear'] );
        if ($atts['bar_color']) {
            $css .= '#'.$id.'.yt-progress-bar { background-color:' . $atts['bar_color'] . '; border-color:' .darken($atts['bar_color'], '10%') . ';'.'}';
        }
        if (($atts['fill_color']) or ($atts['text_color'])) {
            $fill_color = ($atts['fill_color']) ? 'background-color:' . $atts['fill_color'] . ';' : '';
            $text_color = ($atts['text_color']) ? 'color:' . $atts['text_color'] . ';' : '';
            $css .= '#'.$id.'.yt-progress-bar > span {'.$fill_color. $text_color . '}';
        }

        $text = ($atts['text']) ? '<span class="yt-pb-text">' . $atts['text'] . '</span>' : '';
        $show_percent = ($atts['show_percent'] !== 'no') ? '<span class="yt-pb-percent">'. $atts['percent'] . '%</span>' : '';
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/progress_bar/css/progress-bar.css",'text/css',"screen");
		if($atts['type_change']=="circle")
		{
			
			if($atts['style_circle']=="circle4")
			{
				$return .='<div class="circle4"><div class="'.$atts['style_circle'].' progress-radial progress-'.$atts['percent'].'"></div>
								<div class="overlay">
									<ul>
										<li><i class="fa fa-'.$atts['icon_circle'].'"></i><b>'.$atts['percent'].'</b>%</li>
										<li>'.$atts['text'].'</li>
									</ul>
								</div></div>';
			}else{
				$return .='<div class="'.$atts['style_circle'].' progress-radial progress-'.$atts['percent'].'">
							<div class="overlay">
								<ul>
									<li><i class="fa fa-'.$atts['icon_circle'].'"></i><b>'.$atts['percent'].'</b>%</li>
									<li>'.$atts['text'].'</li>
								</ul>
							</div>
					   </div>';
			}
		}else
		{
        // Add CSS and JS in head
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/jquery.easing.js");
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/jquery.appear.js");
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/progress_bar/js/progress-bar.js");
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
        	$return = '<div id="'.$id.'" class="'.yt_acssc($classes).'"><span class="yt-pb-fill" data-percent="' . $atts['percent'] . '" data-animation="' . $atts['animation'] . '" data-duration="' . $atts['duration'] . '" data-delay="' . $atts['delay'] . '">'.$text.$show_percent.'</span></div>';
		}

        return $return;
}
?>