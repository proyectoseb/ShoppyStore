<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function headingYTShortcode($atts,$content = null)
{
	 $atts = ytshortcode_atts(array(
          'style'   => 'default',
          'size'    => 16,
          'align'   => 'center',
          'margin'  => '20',
          'width'   => '',
          'heading' => 'h3',
          'color'   => '#666',
          'class'   => ''
        ), $atts, 'heading');
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/heading/css/heading.css",'text/css',"screen");
      $heading_wrapper_style = 'style="';
      if ($atts["width"]) {
        $heading_wrapper_style .= 'width: ' . intVal($atts['width']) . '%;';
      }

      if ($atts["align"] == 'center') {
        $heading_wrapper_style .= 'margin-left: auto; margin-right: auto;';
      } elseif ($atts["align"] == 'left') {
        $heading_wrapper_style .= 'margin-right: auto;';
      } elseif ($atts["align"] == 'right') {
        $heading_wrapper_style .= 'margin-left: auto;';
      }
      $heading_wrapper_style .= 'margin-bottom: ' . intVal($atts['margin']) . 'px;';
      $heading_wrapper_style .= '"';

      $heading_style = 'style="';
      $heading_style .= 'font-size: ' . intVal($atts['size']) . 'px;';
      $heading_style .= ($atts['color']) ? ' color: ' . $atts['color'] . ';' : '';
      $heading_style .= '"';

    return '<div class="yt-heading yt-heading-style-' . $atts['style'] . ' yt-heading-align-' . $atts['align'] . '" '.$heading_wrapper_style.'><'.$atts['heading'].' class="yt-heading-inner" '.$heading_style.'>' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</'.$atts['heading'].'></div>';
}
?>