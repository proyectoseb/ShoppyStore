<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

defined('_JEXEC') or die;
function animationYTShortcode($atts,$content = null)
{
	$atts = ytshortcode_atts(array(
	        'type'     => 'bounceIn',
	        'duration' => 1,
	        'delay'    => 0,
	        'inline_block'   => 'no',
	        'class'    => ''
	    ), $atts, 'animation');
	 $id = uniqid('yta').rand().time();

    $inline = ( $atts['inline_block'] === 'yes' ) ? ' display: inline-block;' : '';
    $style = array(
        'duration' => array(),
        'delay'    => array()
    );

    // CSS Prefix manage
    foreach (array('-webkit-', '-moz-', '-ms-', '-o-', '') as $vendor) {
        $style['duration'][] = $vendor . 'animation-duration:' . $atts['duration'] . 's';
        $style['delay'][]    = $vendor . 'animation-delay:' . $atts['delay'] . 's';
    }
    // Get Css in $css variable
    $css = '#'.$id.' {visibility:hidden;' .$inline . implode(';', $style['duration']) . ';' . implode(';', $style['delay']) .'}';
    // Add CSS in head
  	$doc = JFactory::getDocument();
	$doc->addStyleDeclaration($css);
    // Add CSS file in head
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/animate.css");
	JHtml::_('jquery.framework');
	JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/jquery.appear.js");
	JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/animation/js/animate.js");

    // Output HTML
    $output = '<div id="'. $id .'" class="yt-clearfix yt-animate " data-animation="'. $atts['type'] .'">'. parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) .'</div>';

    return $output;
}
	
?>