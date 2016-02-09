<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function flip_boxYTShortcode($atts,$content = null)
{
	$atts = ytshortcode_atts( array(
            'animation_style' => 'horizontal_flip_left',
			'width'		      => '100',
            'class'           => '',
            'radius'          => ''
          ), $atts, 'flip_box' );
		// Add style
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/flip_box/css/flip-box.css",'text/css',"screen");
        $return = '
        <div class="flip-box-wrap' . $atts['class'] . '" style="width:'.$atts['width'].'%; border-radius:'.$atts['radius'].'px"><div class="yt-flip-box '.$atts['animation_style'].'">
            '.parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)).'<div style="clear:both;height:0"></div>
        </div></div>';
        return $return;
}
?>