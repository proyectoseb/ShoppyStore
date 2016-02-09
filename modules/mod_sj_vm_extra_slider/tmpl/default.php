<?php
/**
 * @package Sj VM Extra Slider
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
if (!empty($list)) {
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/style.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/css3.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/animate.css');
    if (class_exists('vmJsApi')) {
        vmJsApi::jPrice();
        vmJsApi::cssSite();
        echo vmJsApi::writeJS();
    } else {
        if (!defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1") {
            JHtml::script('modules/' . $module->module . '/assets/js/jquery-2.1.4.min.js');
            JHtml::script('modules/' . $module->module . '/assets/js/jquery-noconflict.js');
            define('SMART_JQUERY', 1);
        }
    }
    if (!defined ('OWL_CAROUSEL'))
    {
        JHtml::stylesheet('modules/' . $module->module . '/assets/css/owl.carousel.css');
        JHtml::script('modules/' . $module->module . '/assets/js/owl.carousel.js');
        define( 'OWL_CAROUSEL', 1 );
    }
    ImageHelper::setDefault($params);
    $instance = rand() . time();
    $tag_id = 'sj_extra_slider_' . $module->id;
    $options = $params->toObject();

    $count_item = count($list);
    $cls_btn_page = ($params->get('button_page') == 'top') ? 'buttom-type1':'button-type2';
    $btn_type 	  = ($params->get('button_page') == 'top') ? 'button-type1':'button-type2';

    $currency = CurrencyDisplay::getInstance();
    $nb_column0 = $params->get('nb-column0', 6);
    $nb_column1 = $params->get('nb-column1', 4);
    $nb_column2 = $params->get('nb-column2', 3);
    $nb_column3 = $params->get('nb-column3', 2);
    $nb_column4 = $params->get('nb-column4', 1);
    $class_respl = 'extra-resp00-' . $nb_column0 . ' extra-resp01-' . $nb_column1 . ' extra-resp02-' . $nb_column2 . ' extra-resp03-' . $nb_column3 . ' extra-resp04-' . $nb_column4;
    $btn_prev = ($params->get('button_page') == 'top') ? '&#171;':'&#139;';
    $btn_next = ($params->get('button_page') == 'top') ? '&#187;':'&#155;';
    $nb_rows = $params->get('nb_rows');
    $items_style = $params->get('theme');
    $class_suffix = $params->get('moduleclass_sfx');
    $effect = $params->get('effect');
    $delay = (int)$params->get('delay') ? (int)$params->get('delay') : '300';
    $duration = (int)$params->get('duration') ? (int)$params->get('duration') : '600';
    $title_slider_display = $params->get('title_slider_display');
    $title_slider = $params->get('title_slider');
    $nav = $params->get('navs') == 1 ? "true" : "false";
    $dots = $params->get('dots') == 1 ? "true" : "false";
    $margin = (int)$params->get('margin') ? (int)$params->get('margin') : '5';
    $slideBy = (int)$params->get('slideBy') ? (int)$params->get('slideBy') : '1';
    $autoplay_timeout = (int)$params->get('autoplay_timeout') ? (int)$params->get('autoplay_timeout') : '5000';
    $autoplay_speed = (int)$params->get('autoplay_speed') ? (int)$params->get('autoplay_speed') : '2000';
    $startPosition = (int)$params->get('startPosition') ? (int)$params->get('startPosition') : '0';
    $dotsSpeed = (int)$params->get('dotsSpeed') ? (int)$params->get('dotsSpeed') : '500';
    $navSpeed = (int)$params->get('navSpeed') ? (int)$params->get('navSpeed') : '500';
    $i = 0;
    ?>
    <div class="moduletable <?php echo $class_suffix; ?>">
        <!--[if lt IE 9]>
        <div id="<?php echo $tag_id;?>"
             class="sj-extra-slider msie lt-ie9 first-load <?php echo $cls_btn_page; ?> <?php echo $class_respl; ?> <?php echo $btn_type; ?>" ><![endif]-->
        <!--[if IE 9]>
        <div id="<?php echo $tag_id;?>"
             class="sj-extra-slider msie first-load <?php echo $cls_btn_page; ?> <?php echo $class_respl; ?> <?php echo $btn_type; ?>"><![endif]-->
        <!--[if gt IE 9]><!-->
        <div id="<?php echo $tag_id ; ?>"
             class="sj-extra-slider first-load <?php echo $cls_btn_page; ?> <?php echo $class_respl; ?> <?php echo $btn_type; ?>"><!--<![endif]-->

            <!-- Begin extraslider-inner -->
            <?php if($title_slider_display) { ?>
                <div class="heading-title"><?php echo $title_slider; ?></div>
            <?php } ?>
            <div class="extraslider-inner" data-effect="<?php echo $effect; ?>">
                <?php  foreach ($list as $item) {
                    $i++;
                    ?>
                    <?php if ($i % $nb_rows == 1 || $nb_rows == 1) { ?>
                        <div class="item ">
                    <?php } ?>
                    <div class="item-wrap <?php echo $items_style; ?>">
                        <div class="item-wrap-inner">
                            <?php if($item->product_special == 1 && $options->show_front == 'show') { ?>
                                <span class="new">New</span>
                            <?php } ?>
                            <?php $img = VmExtrasliderHelper::getVmImage($item, $params);
                            if ($img) {
                                ?>
                                <div class="item-image">
                                    <div class="item-img-info">
                                        <a href="<?php echo $item->link; ?>"
                                           title="<?php echo $item->title ?>" <?php echo VmExtrasliderHelper::parseTarget($params->get('item_link_target')); ?>>
                                            <?php echo VmExtrasliderHelper::imageTag($img); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($options->item_title_display == 1 || $options->item_desc_display == 1 || $options->item_readmore_display == 1 || $options->item_addtocart_display == 1) { ?>
                                <div class="item-info">
                                    <?php if ($options->item_title_display == 1) { ?>
                                        <div class="item-title">
                                            <a href="<?php echo $item->link; ?>"
                                               title="<?php echo $item->title ?>" <?php echo VmExtrasliderHelper::parseTarget($params->get('item_link_target')); ?>>
                                                <?php echo VmExtrasliderHelper::truncate($item->title, $params->get('item_title_max_characs', 25)); ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php require JModuleHelper::getLayoutPath($module->module, $layout . '_rating'); ?>
                                    <?php if ($params->get('item_addtocart_display', 1)) {
                                        $_item['product'] = $item; ?>
                                        <div class="item-addtocart">
                                            <?php echo shopFunctionsF::renderVmSubLayout('addtocart', $_item); ?>
                                        </div>
                                    <?php } ?>

                                    <?php if ($options->item_desc_display == 1 && !empty($item->_description) || $params->get('item_price_display', 1) || $options->item_readmore_display == 1) { ?>
                                        <div class="item-content">

                                            <?php if ($options->item_desc_display == 1) { ?>
                                                <div class="item-description">
                                                    <?php echo $item->_description; ?>
                                                </div>
                                            <?php } ?>

                                            <?php if ((int)$params->get('item_price_display', 1)) { ?>
                                                <div class="item-price">
                                                    <?php
                                                    if (!empty($item->prices['salesPrice'])) {
                                                        echo $currency->createPriceDiv('salesPrice', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                                                    }
                                                    if (!empty($item->prices['discountAmount'])) {
                                                        echo $currency->createPriceDiv('discountAmount', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                                                     ?>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($item->prices['discountAmount'])) { ?>
                                                <span class="sale">Sale</span>
                                            <?php } ?>
                                            <?php if ($options->item_readmore_display == 1) { ?>
                                                <div class="item-readmore">
                                                    <a href="<?php echo $item->link; ?>"
                                                       title="<?php echo $item->title ?>" <?php echo VmExtrasliderHelper::parseTarget($params->get('item_link_target')); ?>>
                                                        <?php echo $options->item_readmore_text; ?>
                                                    </a>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- End item-wrap -->
                    <?php if ($i % $nb_rows == 0 || $i == $count_item) { ?>
                        </div>
                    <?php } ?>
                <?php } ?>

            </div>
            <!--End extraslider-inner -->
        </div>
        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready(function ($) {
                ;(function (element) {
                    var $element = $(element),
                        $extraslider = $(".extraslider-inner", $element),
                        _delay = <?php echo $delay; ?>,
                        _duration = <?php echo $duration; ?>,
                        _effect = '<?php echo $effect; ?>';

                    $extraslider.on("initialized.owl.carousel", function () {
                        var $item_active = $(".owl-item.active", $element);
                        if ($item_active.length > 1 && _effect != "none") {
                            _getAnimate($item_active);
                        }
                        else {
                            var $item = $(".owl-item", $element);
                            $item.css({"opacity": 1, "filter": "alpha(opacity = 100)"});
                        }
                        <?php if($params->get('dots') == "true") { ?>
                        if ($(".owl-dot", $element).length < 2) {
                            $(".owl-prev", $element).css("display", "none");
                            $(".owl-next", $element).css("display", "none");
                            $(".owl-dot", $element).css("display", "none");
                        }
                        <?php }?>

                        <?php if($params->get('button_page') == "top"){ ?>
                        $(".owl-controls", $element).insertBefore($extraslider);
                        $(".owl-dots", $element).insertAfter($(".owl-prev", $element));
                        <?php }else{ ?>
                        $(".owl-nav", $element).insertBefore($extraslider);
                        $(".owl-controls", $element).insertAfter($extraslider);
                        <?php }?>

                    });

                    $extraslider.owlCarousel({

                        margin: <?php echo $margin; ?>,
                        slideBy: <?php echo $slideBy; ?>,
                        autoplay: <?php echo $params->get('autoplay'); ?>,
                        autoplayHoverPause: <?php echo $params->get('pausehover'); ?>,
                        autoplayTimeout: <?php echo $autoplay_timeout; ?>,
                        autoplaySpeed: <?php echo $autoplay_speed; ?>,
                        startPosition: <?php echo $startPosition; ?>,
                        mouseDrag: <?php echo $params->get('mousedrag');?>,
                        touchDrag: <?php echo $params->get('touchdrag'); ?>,
                        autoWidth: false,
                        responsive: {
                            0: 	{ items: <?php echo $nb_column4;?> } ,
                            480: { items: <?php echo $nb_column3;?> },
                            768: { items: <?php echo $nb_column2;?> },
                            992: { items: <?php echo $nb_column1;?> },
                            1200: {items: <?php echo $nb_column0;?>}
                        },
                        dotClass: "owl-dot",
                        dotsClass: "owl-dots",
                        dots: <?php echo $dots; ?>,
                        dotsSpeed:<?php echo $dotsSpeed; ?>,
                        nav: <?php echo $nav; ?>,
                        loop: true,
                        navSpeed: <?php echo $navSpeed; ?>,
                        navText: ["<?php echo $btn_prev; ?>", "<?php echo $btn_next; ?>"],
                        navClass: ["owl-prev", "owl-next"]
                    });

                    $extraslider.on("translate.owl.carousel", function (e) {
                        <?php if($params->get('dots') == "true") { ?>
                        if ($(".owl-dot", $element).length < 2) {
                            $(".owl-prev", $element).css("display", "none");
                            $(".owl-next", $element).css("display", "none");
                            $(".owl-dot", $element).css("display", "none");
                        }
                        <?php } ?>

                        var $item_active = $(".owl-item.active", $element);
                        _UngetAnimate($item_active);
                        _getAnimate($item_active);
                    });

                    $extraslider.on("translated.owl.carousel", function (e) {

                        <?php if($params->get('dots') == "true") { ?>
                        if ($(".owl-dot", $element).length < 2) {
                            $(".owl-prev", $element).css("display", "none");
                            $(".owl-next", $element).css("display", "none");
                            $(".owl-dot", $element).css("display", "none");
                        }
                        <?php } ?>

                        var $item_active = $(".owl-item.active", $element);
                        var $item = $(".owl-item", $element);

                        _UngetAnimate($item);

                        if ($item_active.length > 1 && _effect != "none") {
                            _getAnimate($item_active);
                        } else {

                            $item.css({"opacity": 1, "filter": "alpha(opacity = 100)"});

                        }
                    });

                    function _getAnimate($el) {
                        if (_effect == "none") return;
                        //if ($.browser.msie && parseInt($.browser.version, 10) <= 9) return;
                        $extraslider.removeClass("extra-animate");
                        $el.each(function (i) {
                            var $_el = $(this);
                            $(this).css({
                                "-webkit-animation": _effect + " " + _duration + "ms ease both",
                                "-moz-animation": _effect + " " + _duration + "ms ease both",
                                "-o-animation": _effect + " " + _duration + "ms ease both",
                                "animation": _effect + " " + _duration + "ms ease both",
                                "-webkit-animation-delay": +i * _delay + "ms",
                                "-moz-animation-delay": +i * _delay + "ms",
                                "-o-animation-delay": +i * _delay + "ms",
                                "animation-delay": +i * _delay + "ms",
                                "opacity": 1
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
                                "animation": "",
                                "-webkit-animation": "",
                                "-moz-animation": "",
                                "-o-animation": "",
                                "opacity": 0
                            });
                        });
                    }

                })("#<?php echo $tag_id ; ?>");
            });
            //]]>
        </script>
    </div>
    <?php
} else {
    echo JText::_('Has no item to show!');
} ?>

