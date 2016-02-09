<?php
/**
 * @package SJ Search Pro for VirtueMart
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2015 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

?>
<script type="text/javascript">
// Autocomplete */

(function($) {
    $.fn.SjSearchautocomplete = function(option) {
        return this.each(function() {
            this.timer = null;
            this.items = new Array();

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Focus
            $(this).on('focus', function() {
                this.request();
            });

            // Blur
            $(this).on('blur', function() {
                setTimeout(function(object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('keydown', function(event) {
                switch(event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });

            // Click
            this.click = function(event) {
                event.preventDefault();

                value = $(event.target).parent().attr('data-value');

                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            }

            // Show
            this.show = function() {
                var pos = $(this).position();

                $(this).siblings('ul.dropdown-menu-sj').css({
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left
                });

                $(this).siblings('ul.dropdown-menu-sj').show();
            }

            // Hide
            this.hide = function() {
                $(this).siblings('ul.dropdown-menu-sj').hide();
            }

            // Request
            this.request = function() {
                clearTimeout(this.timer);

                this.timer = setTimeout(function(object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            }

            // Response
            this.response = function(json) {
                html = '';

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i]['value']] = json[i];
                    }

                    for (i = 0; i < json.length; i++) {
                        if (!json[i]['category']) {
                        html += '<li class="media" data-value="' + json[i]['value'] + '" title="' + json[i]['label'] + '">';
                        if(json[i]['image'] && json[i]['show_image'] && json[i]['show_image'] == 1 ) {
                            html += '    <a class="media-left" href="' + json[i]['link'] + '">' + json[i]['image'] + '</a>';
                        }
                        html += '<div class="media-body">';
                        html += '<a href="' + json[i]['link'] + '" title="' + json[i]['label'] + '"><span class="sj-search-name"> ' + json[i]['label'] + '</span></a>';
                        if(json[i]['cate_name'] != '' && json[i]['show_catename'])
                        {
                            html += '<span class="sj-search-catename"> <?php echo JText::_("CATEGORY_NAME")?>'+json[i]['cate_name']+'</span>';
                        }
                        if(json[i]['salesPrice'] && json[i]['show_price'] && json[i]['show_price'] == 1){
                            html += '    <div class="box-price">';
                            html += json[i]['salesPrice'];
                            html += json[i]['discountAmount'];
                            html += '    </div>';
                        }
                        html += '</div></li>';
                        html += '';
                        }
                    }

                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('ul.dropdown-menu-sj').html(html);
            }

            $(this).after('<ul class="dropdown-menu-sj"></ul>');

        });
    }
})(window.jQuery);

jQuery(document).ready(function($) {
    var selector = '#search<?php  echo $module->id ?>';
    var total = 0;
    var character = <?php  echo ((int)$params['character'] != '' ? (int)$params['character'] : 3);?>;
    var showimage = <?php echo $params['show_image'];?>;
    var showprice = <?php echo $params['show_price'];?>;
    var showcatename = <?php echo $params['show_catename'];?>;
    var $_ajax_url = '<?php echo (string)JURI::getInstance(); ?>';

    $(selector).find('input[name=\'keyword\']').SjSearchautocomplete({
        delay: 200,
        source: function(request, response) {
            var category_id = $(".select_category select[name=\"virtuemart_category_id\"]").first().val();

            if(typeof(category_id) == 'undefined')
                category_id = 0;
            var limit = <?php echo ((int)$params['limit'] != '' ? (int)$params['limit'] : 5);?>;
            if(request.length >= character){
                $.ajax({
                    type: 'POST',
                    url: $_ajax_url,
                    data: {
                        is_ajax_searchpro: 1,
                        search_module_id: <?php echo $module->id ?>,
                        search_category_id : category_id,
                        search_name : encodeURIComponent(request)
                    },
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            total = 0;
                            if(item.total){
                                total = item.total;
                            }

                            return {
                                salesPrice : item.salesPrice,
                                discountAmount: item.discountAmount,
                                label:   item.name,
                                cate_name:   item.category_name,
                                image:   item.image,
                                link:    item.link,
                                minimum:    item.minimum,
                                show_price:  showprice,
                                show_image:  showimage,
                                show_catename: showcatename    ,
                                value:   item.product_id,
                            }
                        }));
                    }
                });
            }
        },
    });
});

</script>
