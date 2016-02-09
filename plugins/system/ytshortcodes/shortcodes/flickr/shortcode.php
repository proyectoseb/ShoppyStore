<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function flickrYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'id_flickr'       => '95572727@N00',
            'limit'    => '9',
            'lightbox' => 'no',
            'radius'   => '0px',
            'class'    => ''
                ), $atts, 'yt_flickr');
        $rounded = ($atts['radius']) ? 'border-radius: ' . $atts['radius'] . ';' : '';

        $style = 'style="' . $rounded . '"';

        $image = ($atts['lightbox'] == 'yes') ? '<a class="yt-lightbox" data-mfp-type="image" href="{{image_b}}" title="{{title}}"' . $style . '> ' : '';
        $image .= '<img ' . $style . ' src="{{image_s}}" alt="{{title}}" />';
        $image .= ($atts['lightbox'] == 'yes') ? '</a> ' : '';

        $unique_id = uniqid("ytflickr_").rand().time();
		JHtml::_('jquery.framework');
        if ($atts['lightbox'] == 'yes') {
            $atts['class'] .= ' yt-flickr-lightbox';
            JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/magnific-popup.css",'text/css');
            JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/magnific-popup.js");
           	JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/flickr/js/flickr-lightbox.js");
        }
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/flickr/css/flickr.css",'text/css');
        JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/flickr/js/flickr.js");
        $return = "<ul id='".$unique_id."' class='flickrfeed ".$atts['class']."'></ul> <div class='clear'></div>";

        echo "<script type='text/javascript'>
              jQuery(document).ready(function() {
                      jQuery('#".$unique_id."').jflickrfeed({
                        limit: " . $atts['limit'] . ", qstrings: {
                          id: '" . $atts['id_flickr'] . "'},
                          itemTemplate: '<li>" . addslashes($image) . "</li>' });
                    });
              </script> ";

        return $return;
    }
?>