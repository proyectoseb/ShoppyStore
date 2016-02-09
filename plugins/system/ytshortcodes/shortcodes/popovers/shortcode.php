<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function popoversYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'style'    => 'yellow',
            'position' => 'top',
            'shadow'   => 'no',
            'rounded'  => 'no',
            'size'     => 'default',
            'title'    => '',
            'text'  => 'Your content here.',
            'behavior' => 'hover',
            'close'    => 'no',
            'class'    => ''
                ), $atts, 'popovers');
        $atts['style'] = ( in_array($atts['style'], array('light', 'dark', 'green', 'red', 'blue', 'youtube', 'tipsy', 'bootstrap', 'jtools', 'tipped', 'cluetip')) ) ? $atts['style'] : 'plain';
        $atts['position'] = str_replace(array('top', 'right', 'bottom', 'left'), array('north', 'east', 'south', 'west'), $atts['position']);
        $position = array(
            'my' => str_replace(array('north', 'east', 'south', 'west'), array('bottom center', 'center left', 'top center', 'center right'), $atts['position']),
            'at' => str_replace(array('north', 'east', 'south', 'west'), array('top center', 'center right', 'bottom center', 'center left'), $atts['position'])
        );
        $classes = array('yt-qtip qtip-' . $atts['style']);
        $classes[] = 'yt-qtip-size-' . $atts['size'];
        if ($atts['shadow'] === 'yes')
            $classes[] = 'qtip-shadow';
        if ($atts['rounded'] === 'yes')
            $classes[] = 'qtip-rounded';
        // add stylesheet
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/qtip.css",'text/css');
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/popovers/css/popovers.css",'text/css');
        // Add javascript file in head
		JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/qtip.js");
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/popovers/js/popovers.js");
        return '<span class="yt-tooltip' . trim($atts['class']) . '" data-close="' . $atts['close'] . '" data-behavior="' . $atts['behavior'] . '" data-my="' . $position['my'] . '" data-at="' . $position['at'] . '" data-classes="' . implode(' ', $classes) . '" data-title="' . $atts['title'] .
        '" title="' . ( $atts['text'] ) . '">' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</span>';
    }
?>