<?php
/**
 * @package Sj Super Categories for JoomShopping
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function ($) {
    ;
    (function (element) {
		<?php if ($params->get('item_addtocart_display', 1) == 1 && VmConfig::get('addtocart_popup') == 1) { ?>
        if (typeof  Virtuemart !== 'undefined') {
            Virtuemart.addtocart_popup = "<?php echo VmConfig::get('addtocart_popup'); ?>";
            usefancy = "<?php echo VmConfig::get('usefancy');?>";
            vmLang = "<?php echo VmConfig::get('vmLang') != ''?VmConfig::get('vmLang'):"";?>";
            window.vmSiteurl = "<?php echo JUri::base();?>";
        }
        <?php } ?>
         var type_show = '<?php echo $params->get('type_show');?>';
        var $element = $(element),
            $tab = $('.spcat-tab', $element),
            $tab_label = $('.spcat-tab-label', $tab),
            $tabs = $('.spcat-tabs', $element),
            ajax_url = $tabs.parents('.spcat-tabs-container').attr('data-ajaxurl'),
            effect = $tabs.parents('.spcat-tabs-container').attr('data-effect'),
            delay = $tabs.parents('.spcat-tabs-container').attr('data-delay'),
            duration = $tabs.parents('.spcat-tabs-container').attr('data-duration'),
            rl_moduleid = $tabs.parents('.spcat-tabs-container').attr('data-modid'),
            $items_content = $('.spcat-items', $element),
            $items_inner = $('.spcat-items-inner', $items_content),
            $items_first_active = $('.spcat-items-selected', $element),
            $load_more = $('.spcat-loadmore', $element),
            $btn_loadmore = $('.spcat-loadmore-btn', $load_more),
            $select_box = $('.spcat-selectbox', $element),
            $id_cate = $('.spcat-tab', $element).attr('data-id-cate'),
            $tab_label_select = $('.spcat-tab-selected', $element),
            category_id = $('.sp-cat-title-parent', $element).attr('data-catids');

        enableSelectBoxes();
        function enableSelectBoxes() {
            $tab_wrap = $('.spcat-tabs-wrap', $element);
                $tab_label_select.html($('.spcat-tab', $element).filter('.tab-sel').children('.spcat-tab-label').html());
            if ($(window).innerWidth() <= 479) {
                $tab_wrap.addClass('spcat-selectbox');
            } else {
                $tab_wrap.removeClass('spcat-selectbox');
            }
        }

        $('span.spcat-tab-selected, span.spcat-tab-arrow', $element).click(function () {
            if ($('.spcat-tabs', $element).hasClass('spcat-open')) {
                $('.spcat-tabs', $element).removeClass('spcat-open');
            } else {
                $('.spcat-tabs', $element).addClass('spcat-open');
            }
        });

        $(window).resize(function () {
            if ($(window).innerWidth() <= 479) {
                $('.spcat-tabs-wrap', $element).addClass('spcat-selectbox');
            } else {
                $('.spcat-tabs-wrap', $element).removeClass('spcat-selectbox');
            }
        });

        function showAnimateItems(el) {
            var $_items = $('.new-spcat-item', el), nub = 0;
            $('.spcat-loadmore-btn', el).fadeOut('fast');
            $_items.each(function (i) {
                nub++;
                switch(effect) {
                    case 'none' : $(this).css({'opacity':'1','filter':'alpha(opacity = 100)'}); break;
                    default: animatesItems($(this),nub*delay,i,el);
                }
                if (i == $_items.length - 1) {
                    $('.spcat-loadmore-btn', el).fadeIn(delay);
                }
                $(this).removeClass('new-spcat-item');
            });
        }

        function animatesItems($this,fdelay,i,el) {
            var $_items = $('.spcat-item', el);
            $this.attr("style",
                "-webkit-animation:" + effect +" "+ duration +"ms;"
                + "-moz-animation:" + effect +" "+ duration +"ms;"
                + "-o-animation:" + effect +" "+ duration +"ms;"
                + "-moz-animation-delay:" + fdelay + "ms;"
                + "-webkit-animation-delay:" + fdelay + "ms;"
                + "-o-animation-delay:" + fdelay + "ms;"
                + "animation-delay:" + fdelay + "ms;").delay(fdelay).animate({
                    opacity: 1,
                    filter: 'alpha(opacity = 100)'
                }, {
                    delay: 100
                });
            if (i == ($_items.length - 1)) {
                $(".spcat-items-inner").addClass("play");
            }
        }

        showAnimateItems($items_first_active);
        $tab.on('click.tab', function () {
            var $this = $(this);
            if ($this.hasClass('tab-sel')) return false;
            if ($this.parents('.spcat-tabs').hasClass('spcat-open')) {
                $this.parents('.spcat-tabs').removeClass('spcat-open');
            }
            $tab.removeClass('tab-sel');
            $this.addClass('tab-sel');
            var items_active = $this.attr('data-active-content');

            var _items_active = $(items_active,$element);
            $items_content.removeClass('spcat-items-selected');
            _items_active.addClass('spcat-items-selected');
            $tab_label_select.html($tab.filter('.tab-sel').children('.spcat-tab-label').html());
            var $loading = $('.spcat-loading', _items_active);
            var loaded = _items_active.hasClass('spcat-items-loaded');
            if (!loaded && !_items_active.hasClass('spcat-process')) {
                _items_active.addClass('spcat-process');
                var field_order = $this.attr('data-field_order');
                $loading.show();
                $.ajax({
                    type: 'POST',
                    url: ajax_url,
                    data: {
                        spcat_module_id: rl_moduleid,
                        is_ajax_listing_tabs: 1,
                        ajax_limit_start: 0,
                        categoryid: category_id,
                        fieldorder:field_order,
                        tag_id:'<?php echo $tag_id;?>'
                    },
                    success: function (data) {
                        if (data.items_markup != '') {
                            $('.spcat-items-inner', _items_active).html(data.items_markup);
                            _items_active.addClass('spcat-items-loaded').removeClass('spcat-process');
                            $loading.remove();
                            showAnimateItems(_items_active);
                            updateStatus(_items_active);
                            if(type_show == 'slider'){
                            var $tag_id = $('#<?php echo $tag_id; ?>'),
                                    parent_active =     $('.spcat-items-selected', $tag_id),
                                    total_product = parent_active.data('total'),
                                    tab_active = $('.ltabs-items-inner',parent_active),
                                    nb_column1 = <?php echo $params->get('nb-column1'); ?>,
                                    nb_column2 = <?php echo $params->get('nb-column2'); ?>,
                                    nb_column3 = <?php echo $params->get('nb-column3'); ?>,
                                    nb_column4 = <?php echo $params->get('nb-column4'); ?>;

                                    tab_active.owlCarousel({
                                    dots: false,
                                    margin: 30,
                                    loop:  <?php echo $params->get('display_loop_s') ; ?>,
                                    autoplay: <?php echo $params->get('autoplay_s'); ?>,
                                    autoplayHoverPause: <?php echo $params->get('pausehover_s') ; ?>,
                                    autoplayTimeout: <?php echo $params->get('autoplay_timeout_s') ; ?>,
                                    autoplaySpeed: <?php echo $params->get('autoplay_speed_s') ; ?>,
                                    mouseDrag: <?php echo  $params->get('mousedrag_s'); ?>,
                                    touchDrag: <?php echo $params->get('touchdrag_s'); ?>,
                                    responsive: {
                                        0: {
                                            items: nb_column4,

                                        },
                                        480: {
                                            items: nb_column3,

                                        },
                                        768: {
                                            items: nb_column2,

                                        },
                                        1200: {
                                            items: nb_column1,

                                        },
                                    }
                                });

                                $('.btn-next',parent_active).click(function() {
                                    tab_active.trigger('next.owl');
                                })
                                $('.btn-prev',parent_active).click(function() {
                                    tab_active.trigger('prev.owl');
                                })
								if(typeof Virtuemart !== 'undefined'){
                                Virtuemart.addtocart_popup = "<?php echo VmConfig::get('addtocart_popup'); ?>";
                                usefancy = "<?php echo VmConfig::get('usefancy');?>";
                                vmLang = "<?php echo VmConfig::get('vmLang') != ''?VmConfig::get('vmLang'):"";?>";
                                window.vmSiteurl = "<?php echo JUri::base();?>";
                                var _carts = $element.find('form');
                                _carts.each(function(){
                                    var _cart = $(this),
                                        _addtocart = _cart.find('input.addtocart-button');
                                    _addtocart.on('click',function(e) {
                                        Virtuemart.sendtocart(_cart);
                                        return false;
                                    });
                                });
								}
                             }
                        }
                    },
                    dataType: 'json'
                });

            } else {
                $('.spcat-item', $items_content).removeAttr('style').addClass('new-spcat-item').css('opacity',0);
                showAnimateItems(_items_active);
            }
        });

        function updateStatus($el) {
            $('.spcat-loadmore-btn', $el).removeClass('loading');
            var countitem = $('.spcat-item', $el).length;
            $('.spcat-image-loading', $el).css({display: 'none'});
            $('.spcat-loadmore-btn', $el).parent().attr('data-rl_start', countitem);
            var rl_total = $('.spcat-loadmore-btn', $el).parent().attr('data-rl_total');
            var rl_load = $('.spcat-loadmore-btn', $el).parent().attr('data-rl_load');
            var rl_allready = $('.spcat-loadmore-btn', $el).parent().attr('data-rl_allready');

            if (countitem >= rl_total) {
                $('.spcat-loadmore-btn', $el).addClass('loaded');
                $('.spcat-image-loading', $el).css({display: 'none'});
                $('.spcat-loadmore-btn', $el).attr('data-label', rl_allready);
                $('.spcat-loadmore-btn', $el).removeClass('loading');
            }
        }

        $btn_loadmore.on('click.loadmore', function () {
            var $this = $(this);
            if ($this.hasClass('loaded') || $this.hasClass('loading')) {
                return false;
            } else {
                $this.addClass('loading');
                $('.spcat-image-loading', $this).css({display: 'inline-block'});
                var rl_start = $this.parent().attr('data-rl_start'),
                    rl_moduleid = $this.parent().attr('data-modid'),
                    rl_ajaxurl = $this.parent().attr('data-ajaxurl'),
                    effect = $this.parent().attr('data-effect'),
                    field_order = $this.parent().attr('data-field_order'),
                    items_active = $this.parent().attr('data-active-content');
                var _items_active = $(items_active,$element);
                $.ajax({
                    type: 'POST',
                    url: rl_ajaxurl,
                    data: {
                        spcat_module_id: rl_moduleid,
                        is_ajax_listing_tabs: 1,
                        ajax_reslisting_start: rl_start,
                        categoryid: category_id,
                        fieldorder: field_order,
                        load_more:1
                    },
                    success: function (data) {
                        if (data.items_markup != '') {
                            $(data.items_markup).insertAfter($('.spcat-item', _items_active).nextAll().last());
                            $('.spcat-image-loading', $this).css({display: 'none'});
                            showAnimateItems(_items_active);
                            updateStatus(_items_active);
                        }
                    }, dataType: 'json'
                });
            }
            return false;
        });
        <?php if($params->get('show_categories_type_slide',1) == 1 && $params->get('show_categories_slide',1) == 1){?>
        var $cat_slider_inner = $('.cat_slider_inner',element);

        $cat_slider_inner.owlCarousel({
            center: <?php echo $center; ?>,
            loop: <?php echo $loop; ?>,
            margin: 0,
            autoplay: <?php echo $autoplay; ?>,
            autoplayHoverPause: <?php echo $autoplayHoverPause; ?>,
            autoplayTimeout: <?php echo $autoplayTimeout; ?>,
            autoplaySpeed: <?php echo $autoplaySpeed; ?>,
            navSpeed: <?php echo $navSpeed; ?>,
            smartSpeed: <?php echo $smartSpeed;?>,
            mouseDrag:<?php echo $mouseDrag; ?>,
            touchDrag:<?php echo $touchDrag; ?>,
            pullDrag:<?php echo $pullDrag; ?>,
            dots: false,
            autoWidth: false,
            responsive: {
                0: {
                    items:<?php echo $nb_column4;?> },
                480: {
                    items:<?php echo $nb_column3;?> },
                768: {
                    items:<?php echo $nb_column2;?> },
                1200: {
                    items:<?php echo $nb_column1;?> }
            }
        });
        <?php }?>
    })('#<?php echo $tag_id; ?>');
    <?php if ($params->get('item_addtocart_display', 1) == 1 && VmConfig::get('addtocart_popup') == 1) { ?>
            if (typeof  Virtuemart !== 'undefined') {
            Virtuemart.addtocart_popup = 1;
            usefancy = "<?php echo VmConfig::get('usefancy')?>";
            vmLang = "<?php echo VmConfig::get('vmLang') != ''?VmConfig::get('vmLang'):"";?>";
            window.vmSiteurl = "<?php echo JUri::base();?>";
            var _carts =  jQuery("#<?php echo $tag_id;?>").find('form');
            _carts.each(function(){
            var _cart = jQuery(this),
            _addtocart = _cart.find('input.addtocart-button');
            _addtocart.on('click',function(e) {
                Virtuemart.sendtocart(_cart);
                return false;
                });
            });
            }
         <?php } ?>
});
//]]>
</script>
<?php if ($params->get('type_show') == 'slider') { ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            var $tag_id = $('#<?php echo $tag_id; ?>'),
                parent_active =     $('.spcat-items-selected', $tag_id),
                total_product = parent_active.data('total'),
                tab_active = $('.ltabs-items-inner',parent_active),
                nb_column1 = <?php echo $params->get('nb-column1'); ?>,
                nb_column2 = <?php echo $params->get('nb-column2'); ?>,
                nb_column3 = <?php echo $params->get('nb-column3'); ?>,
                nb_column4 = <?php echo $params->get('nb-column4'); ?>;

            tab_active.owlCarousel({
                dots: false,
                margin: 30,
                loop:  <?php echo $params->get('display_loop_s') ; ?>,
                autoplay: <?php echo $params->get('autoplay_s'); ?>,
                autoplayHoverPause: <?php echo $params->get('pausehover_s') ; ?>,
                autoplayTimeout: <?php echo $params->get('autoplay_timeout_s') ; ?>,
                autoplaySpeed: <?php echo $params->get('autoplay_speed_s') ; ?>,
                mouseDrag: <?php echo  $params->get('mousedrag_s'); ?>,
                touchDrag: <?php echo $params->get('touchdrag_s'); ?>,
                responsive: {
                    0: {
                        items: nb_column4,

                    },
                    480: {
                        items: nb_column3,

                    },
                    768: {
                        items: nb_column2,

                    },
                    1200: {
                        items: nb_column1,

                    },
                }
            });
        $('.btn-next',parent_active).click(function() {
                    tab_active.trigger('next.owl');
               })
        $('.btn-prev',parent_active).click(function() {
            tab_active.trigger('prev.owl');
        })
        });
    </script>
<?php } ?>
