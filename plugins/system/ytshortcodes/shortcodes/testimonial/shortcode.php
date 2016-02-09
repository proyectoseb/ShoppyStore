<?php
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function testimonialYTShortcode($atts,$content = null){
    global $id_testimonial, $border_testimonial,$column_testimonial;
    extract (ytshortcode_atts(array(
        "column"    =>'1',
        "border"    =>'',
        "background"=>'',
        "title"     =>'',
        "title_color" => '#ccc',
        "style" => ''
    ),$atts));
    $testimonial ='';
    $border_testimonial = $border;
    $column_testimonial = $column;
    $src_thumb='';
    $css_add ='';
    if(strpos($background,'http://')!== false){
        $src_thumb = $background;
    }else if( is_file($background) && strpos($background,'http://')!== true){

        //$src_thumb = JURI::base().ImageHelper::init($background, $small_image)->background();
        $src_thumb = JURI::base().$background;
    }
    $id_testimonial = uniqid('yt_tes').rand().time();
    JHtml::_('jquery.framework');
    JHtml::script("plugins/system/ytshortcodes/shortcodes/testimonial/js/owl.carousel.js");
    JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/testimonial/css/style.css");
    JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/testimonial/css/css3.css");
    JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/testimonial/css/owl.carousel.css");
    JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/testimonial/css/testimonial.css");
    $testimonial .= '<div style="background: url('.$src_thumb.'); padding-top:20px;">';
    $testimonial .= '<h3 style="color:'.$title_color.'; text-align:center;">'.$title.'</h3>';
    $testimonial .='<div id="'.$id_testimonial.'" class="yt-testimonial-slider buttom-type1 button-type1">
        <div class="extraslider-inner" data-effect="flip" style="border:none">';
    $testimonial .= parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content));
    $testimonial .='</div></div></div>';
    if($column == 1)
    {
        $css_add ='#'.$id_testimonial.' .owl-controls .owl-nav .owl-dots{display:none !important}';
    }else{
        $css_add ='#'.$id_testimonial.' .owl-controls .owl-nav .owl-prev,#'.$id_testimonial.' .owl-controls .owl-nav .owl-next{display:none !important}';
    }

    $doc = JFactory::getDocument();
    $doc->addStyleDeclaration($css_add);
    $testimonial .=' <script type="text/javascript">
                    //<![CDATA[
                    jQuery(document).ready(function ($) {
                        ;
                        (function (element) {
                            var $element = $(element),
                                    $extraslider = $(\'.extraslider-inner\', $element),
                                    _delay = 500,
                                    _duration = 800,
                                    _effect = \'starwars\';

                            $extraslider.on(\'initialized.owl.carousel\', function () {
                                var $item_active = $(\'.owl-item.active\', $element);
                                if ($item_active.length > 1 && _effect != \'none\') {
                                    _getAnimate($item_active);
                                }
                                else {
                                    var $item = $(\'.owl-item\', $element);
                                    $item.css({\'opacity\': 1, \'filter\': \'alpha(opacity = 100)\'});
                                }
                                if ($(\'.owl-dot\', $element).length < 2) {
                                    $(\'.owl-prev\', $element).css(\'display\', \'none\');
                                    $(\'.owl-next\', $element).css(\'display\', \'none\');
                                    $(\'.owl-dot\', $element).css(\'display\', \'none\');
                                }
                                $(\'.owl-controls\', $element).insertBefore($extraslider);
                                $(\'.owl-dots\', $element).insertAfter($(\'.owl-prev\', $element));


                            });

                            $extraslider.owlCarousel({
                                margin: 5,
                                slideBy: 1,
                                autoplay: false,
                                autoplay_hover_pause: true,
                                autoplay_timeout: 1000,
                                autoplaySpeed: 1000,
                                smartSpeed: 1000,
                                startPosition: 0,
                                mouseDrag: true,
                                touchDrag:true,
                                pullDrag:true,
                                autoWidth: false,
                                responsive: {
                                    0: {items :1},
                                    480: {items: 1},
                                    768: {items: '.$column.'},
                                    1200: {items: '.$column.'}
                                },
                                dotClass: \'owl-dot\',
                                dotsClass: \'owl-dots\',
                                dots: true,
                                dotsSpeed:500,
                                nav: true,
                                loop: true,
                                navSpeed: 500,
                                navText: [\'&#139;\', \'&#155;\'],
                                navClass: [\'owl-prev\', \'owl-next\']

                            });

                            $extraslider.on(\'translate.owl.carousel\', function (e) {
                                var $item_active = $(\'.owl-item.active\', $element);
                                _UngetAnimate($item_active);
                                _getAnimate($item_active);
                            });

                            $extraslider.on(\'translated.owl.carousel\', function (e) {
                                var $item_active = $(\'.owl-item.active\', $element);
                                var $item = $(\'.owl-item\', $element);

                                _UngetAnimate($item);

                                if ($item_active.length > 1 && _effect != \'none\') {
                                    _getAnimate($item_active);
                                } else {
                                        $item.css({\'opacity\': 1, \'filter\': \'alpha(opacity = 100)\'});

                                }
                            });

                            function _getAnimate($el) {
                                if (_effect == \'none\') return;
                                //if ($.browser.msie && parseInt($.browser.version, 10) <= 9) return;
                                $extraslider.removeClass(\'extra-animate\');
                                $el.each(function (i) {
                                    var $_el = $(this);
                                    $(this).css({
                                        \'-webkit-animation\': _effect + \' \' + _duration + "ms ease both",
                                        \'-moz-animation\': _effect + \' \' + _duration + "ms ease both",
                                        \'-o-animation\': _effect + \' \' + _duration + "ms ease both",
                                        \'animation\': _effect + \' \' + _duration + "ms ease both",
                                        \'-webkit-animation-delay\': +i * _delay + \'ms\',
                                        \'-moz-animation-delay\': +i * _delay + \'ms\',
                                        \'-o-animation-delay\': +i * _delay + \'ms\',
                                        \'animation-delay\': +i * _delay + \'ms\',
                                        \'opacity\': 1
                                    }).animate({
                                        opacity: 1
                                    });

                                    if (i == $el.size() - 1) {
                                        $extraslider.addClass("extra-animate");
                                    }
                                });
                            }

                            function _UngetAnimate($el) {
                                $el.each(function (i) {
                                    $(this).css({
                                        \'animation\': \'\',
                                        \'-webkit-animation\': \'\',
                                        \'-moz-animation\': \'\',
                                        \'-o-animation\': \'\',
                                        \'opacity\': 0
                                    });
                                });
                            }

                        })(\'#'.$id_testimonial.'\');
                    });
                    //]]>
                </script>';
    return $testimonial;
}
?>
