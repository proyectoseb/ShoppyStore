<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function liviconYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'icon'                => 'heart',
            'size'                => 32,
            'color'               => '#555555',
            'original_color'      => 'false',
            'hover_color'         => '#000000',
            'event_type'          => 'hover',
            'animate'             => 'yes',
            'loop'                => 'no',
            'parent'              => 'no',
            'morph_duration'      => 0.6,
            'duration'            => 0.6,
            'iteration'           => 1,
            'url'                 => '',
            'target'              => '_self',
            'class'               => ''
        ), $atts, 'livicon');

        $atts['animate'] = ($atts['animate'] === 'yes') ?  'true' : 'false';
        $atts['loop'] = ($atts['loop'] === 'yes') ?  'true' : 'false';
        $atts['parent'] = ($atts['parent'] === 'yes') ?  'true' : 'false';
        $atts['morph_duration'] = $atts['morph_duration'] * 1000;
        $atts['duration'] = $atts['duration'] * 1000;


        if ($atts['url']) {
            $return = '<a href="' . $atts['url'] . '" class="yt-livicon ' . trim($atts['class']) . '" target="' . $atts['target'] . '"><span class="livicon" data-name="'.$atts['icon'].'" data-size="'.intval($atts['size']).'" data-color="'.$atts['color'].'" data-hovercolor="'.$atts['hover_color'].'" data-animate="'.$atts['animate'].'" data-loop="'.$atts['loop'].'" data-iteration="'.$atts['iteration'].'" data-duration="'.$atts['duration'].'" data-eventtype="'.$atts['event_type'].'" data-onparent="'.$atts['parent'].'"></span>' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</a>';
        } else {
            $return = '<span class="yt-livicon' . trim($atts['class']) . ' livicon" data-name="'.$atts['icon'].'" data-size="'.intval($atts['size']).'" data-color="'.$atts['color'].'" data-hovercolor="'.$atts['hover_color'].'" data-animate="'.$atts['animate'].'" data-loop="'.$atts['loop'].'" data-iteration="'.$atts['iteration'].'" data-duration="'.$atts['duration'].'" data-eventtype="'.$atts['event_type'].'" data-onparent="'.$atts['parent'].'"></span>'. parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content));
        }

        // Asset added
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/livicon/css/livicon.css",'text/css');
        JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/livicon/js/raphael.min.js");
        JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/livicon/js/livicons.min.js");
        return $return;
    }
?>