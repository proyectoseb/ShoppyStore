<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function content_slider_itemYTShortcode($atts,$content = null)
{
    extract(ytshortcode_atts(array(
        'class' => '',
        'src' =>'',
        'caption' => 'no',
        'link' => ''
    ),$atts));
    $image = ($src !='') ? '<img src='.JURI::base().$src.' alt='.uniqid("title_").rand().time().'>' : 'plugins/system/ytshortcodes/assets/images/URL_IMAGES.png';
    if($link != '')
    {
        $image = '<a href="'.$link.'" title="" target="brank">'.$image.'</a>';
    }
    if($caption == "yes")
    {
        return '<div class="yt-content-slide yt-clearfix yt-content-wrap' . $class . '"> '.$image.' <div class="caption">'.parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)).'</div></div>';
    }
    else{
        return '<div class="yt-content-slide yt-clearfix yt-content-wrap' . $class . '"> '.$image.' '.parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)).'</div>';
    }

}
?>
