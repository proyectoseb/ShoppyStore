<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function dummy_textYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'amount' => 1,
            'what'   => 'paras',
            'cache'  => 'yes',
            'class'  => ''
            ), $atts, 'dummy_text');

        $xml = simplexml_load_file('http://www.lipsum.com/feed/xml?amount=' . $atts['amount'] . '&what=' . $atts['what'] . '&start=0');
        $return = '<div class="yt-dummy-text"><p>' . str_replace("\n", "</p><p>", $xml->lipsum) . '</p></div>';
        return $return;
    }
?>