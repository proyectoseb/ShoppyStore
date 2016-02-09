<?php
/**
 * @package Sj Vm Listing Tabs
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
$type_show=$params->get('type_show');
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

        var $element = $(element),
            $tab = $('.ltabs-tab', $element),
            $tab_label = $('.ltabs-tab-label', $tab),
            $tabs = $('.ltabs-tabs', $element),
            ajax_url = $tabs.parents('.ltabs-tabs-container').attr('data-ajaxurl'),
            effect = $tabs.parents('.ltabs-tabs-container').attr('data-effect'),
            delay = $tabs.parents('.ltabs-tabs-container').attr('data-delay'),
            duration = $tabs.parents('.ltabs-tabs-container').attr('data-duration'),
            rl_moduleid = $tabs.parents('.ltabs-tabs-container').attr('data-modid'),
            $items_content = $('.ltabs-items', $element),
            $items_inner = $('.ltabs-items-inner', $items_content),
            $items_first_active = $('.ltabs-items-selected', $element),
            $load_more = $('.ltabs-loadmore', $element),
            $btn_loadmore = $('.ltabs-loadmore-btn', $load_more),
            $select_box = $('.ltabs-selectbox', $element),
            $tab_label_select = $('.ltabs-tab-selected', $element),
            type_show = '<?php echo $type_show; ?>';

        enableSelectBoxes();
        function enableSelectBoxes() {
            $tab_wrap = $('.ltabs-tabs-wrap', $element),
                $tab_label_select.html($('.ltabs-tab', $element).filter('.tab-sel').children('.ltabs-tab-label').html());
            if ($(window).innerWidth() <= 479) {
                $tab_wrap.addClass('ltabs-selectbox');
            } else {
                $tab_wrap.removeClass('ltabs-selectbox');
            }
        }

        $('span.ltabs-tab-selected, span.ltabs-tab-arrow', $element).click(function () {
            if ($('.ltabs-tabs', $element).hasClass('ltabs-open')) {
                $('.ltabs-tabs', $element).removeClass('ltabs-open');
            } else {
                $('.ltabs-tabs', $element).addClass('ltabs-open');
            }
        });

        $(window).resize(function () {
            if ($(window).innerWidth() <= 479) {
                $('.ltabs-tabs-wrap', $element).addClass('ltabs-selectbox');
            } else {
                $('.ltabs-tabs-wrap', $element).removeClass('ltabs-selectbox');
            }
        });

        function showAnimateItems(el) {
            var $_items = $('.new-ltabs-item', el), nub = 0;
            $('.ltabs-loadmore-btn', el).fadeOut('fast');
            $_items.each(function (i) {
                nub++;
                switch (effect) {
                    case 'none' :
                        $(this).css({'opacity': '1', 'filter': 'alpha(opacity = 100)'});
                        break;
                    default:
                        animatesItems($(this), nub * delay, i, el);
                }
                if (i == $_items.length - 1) {
                    $('.ltabs-loadmore-btn', el).fadeIn(delay);
                }
                $(this).removeClass('new-ltabs-item');
            });
        }

        function animatesItems($this, fdelay, i, el) {
            var $_items = $('.ltabs-item', el);
            $this.attr("style",
                "-webkit-animation:" + effect + " " + duration + "ms;"
                + "-moz-animation:" + effect + " " + duration + "ms;"
                + "-o-animation:" + effect + " " + duration + "ms;"
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
                $(".ltabs-items-inner").addClass("play");
            }
        }
        if (type_show == 'loadmore') {
            showAnimateItems($items_first_active);
        }
        $tab.on('click.tab', function () {
            var $this = $(this);
            $this.addClass('tin');
            if ($this.hasClass('tab-sel')) return false;
            if ($this.parents('.ltabs-tabs').hasClass('ltabs-open')) {
                $this.parents('.ltabs-tabs').removeClass('ltabs-open');
            }
            $tab.removeClass('tab-sel');
            $this.addClass('tab-sel');
            var items_active = $this.attr('data-active-content');
            var _items_active = $(items_active, $element);
            $items_content.removeClass('ltabs-items-selected');
            _items_active.addClass('ltabs-items-selected');
            $tab_label_select.html($tab.filter('.tab-sel').children('.ltabs-tab-label').html());
            var $loading = $('.ltabs-loading', _items_active);
            var loaded = _items_active.hasClass('ltabs-items-loaded');
            if (!loaded && !_items_active.hasClass('ltabs-process')) {
                _items_active.addClass('ltabs-process');
                var category_id = $this.attr('data-category-id');
                var field_order = $this.attr('data-field_order');
                $loading.show();
                $.ajax({
                    type: 'POST',
                    url: ajax_url,
                    data: {
                        listing_tabs_moduleid: rl_moduleid,
                        is_ajax_listing_tabs: 1,
                        ajax_reslisting_start: 0,
                        categoryid: category_id,
                        time_temp: '<?php echo time().rand(); ?>',
                        field_order: field_order,
                    },
                    success: function (data) {
                        if (data.items_markup != '') {
                            if (type_show == 'loadmore') {
                                $('.ltabs-items-inner', _items_active).html(data.items_markup);
                            }else{
                                $('.ltabs-loading', _items_active).replaceWith(data.items_markup);
                            }
                            console.log(_items_active);
                            _items_active.addClass('ltabs-items-loaded').removeClass('ltabs-process');
                            $loading.remove();
                            if (type_show == 'loadmore') {
                                showAnimateItems(_items_active);
                            }
                            updateStatus(_items_active);
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
                    },
                    dataType: 'json'
                });

            } else {
                if (type_show == 'loadmore') {
                    $('.ltabs-item', $items_content).removeAttr('style').addClass('new-ltabs-item').css('opacity', 0);
                    showAnimateItems(_items_active);
                }else{
                    var owl = $('.owl2-carousel' , _items_active);
                    owl = owl.data('owlCarousel2');
                    if (typeof owl !== 'undefined') {
                        owl.onResize();
                    }
                }
            }
        });

        function updateStatus($el) {
            $('.ltabs-loadmore-btn', $el).removeClass('loading');
            var countitem = $('.ltabs-item', $el).length;
            $('.ltabs-image-loading', $el).css({display: 'none'});
            $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_start', countitem);
            var rl_total = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_total');
            var rl_load = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_load');
            var rl_allready = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_allready');

            if (countitem >= rl_total) {
                $('.ltabs-loadmore-btn', $el).addClass('loaded');
                $('.ltabs-image-loading', $el).css({display: 'none'});
                $('.ltabs-loadmore-btn', $el).attr('data-label', rl_allready);
                $('.ltabs-loadmore-btn', $el).removeClass('loading');
            }
        }

        $btn_loadmore.on('click.loadmore', function () {
            var $this = $(this);
            if ($this.hasClass('loaded') || $this.hasClass('loading')) {
                return false;
            } else {
                $this.addClass('loading');
                $('.ltabs-image-loading', $this).css({display: 'inline-block'});
                var rl_start = $this.parent().attr('data-rl_start'),
                    rl_moduleid = $this.parent().attr('data-modid'),
                    rl_ajaxurl = $this.parent().attr('data-ajaxurl'),
                    effect = $this.parent().attr('data-effect'),
                    category_id = $this.parent().attr('data-categoryid'),
                    items_active = $this.parent().attr('data-active-content');
                field_order = $this.parent().attr('data-rl-field_order');
                var _items_active = $(items_active, $element);

                $.ajax({
                    type: 'POST',
                    url: rl_ajaxurl,
                    data: {
                        listing_tabs_moduleid: rl_moduleid,
                        is_ajax_listing_tabs: 1,
                        ajax_reslisting_start: rl_start,
                        categoryid: category_id,
                        time_temp: '<?php echo time().rand(); ?>',
                        field_order: field_order
                    },
                    success: function (data) {
                        if (data.items_markup != '') {
                            $(data.items_markup).insertAfter($('.ltabs-item', _items_active).nextAll().last());
                            $('.ltabs-image-loading', $this).css({display: 'none'});
                            showAnimateItems(_items_active);
                            updateStatus(_items_active);
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
                    }, dataType: 'json'
                });
            }
            return false;
        });
    })('#<?php echo $tag_id; ?>');
});
//]]>
</script>