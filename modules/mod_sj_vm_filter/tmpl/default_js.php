<?php
/**
 * @package SJ Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */
defined ('_JEXEC') or die;

?>
<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function ($) {
		;
		(function (element) {
			var $element = $(element),
				$_ft_form = $('.ft-form', $element),
				$_group_ft = $('.ft-group', $element),
				$_filtering = $('.ft-filtering', $element)
			$_ajax_url = '<?php echo (string)JURI::getInstance(); ?>';

			var $_gr_prices = $('.ft-group-prices', $element),
				$_slide_prices = $(".ft-slider-price", $_gr_prices),
				_min_price = <?php echo (int)$params->get('price_min',0); ?>,
				_max_price = <?php echo (int)$params->get('price_max',1000); ?>;
			var $timer = 0;
			$_slide_prices.each(function (index, element) {
				$(this)[index].slide = null;
			});
			$_slide_prices.slider({
				range: true,
				min: _min_price,
				max: _max_price,
				values: [_min_price, _max_price],
				slide: function (event, ui) {
					$(".ft-price-min", $_gr_prices).val(ui.values[0]);
					$(".ft-price-max", $_gr_prices).val(ui.values[1]);
					if ($timer) {
						clearTimeout($timer);
					}
					$timer = setTimeout(
						function () {
							processAjax()
						}, 1000);
				}
			});

			$('.ft-price-input', $_gr_prices).on('keyup', function () {
				var $that = $(this);
				if ($timer) {
					clearTimeout($timer);
				}
				var _price_tmp = parseInt($that.val());
				$timer = setTimeout(function () {
					if (!isNaN(_price_tmp) && _price_tmp >= 1) {
						_price_tmp = _price_tmp >= _max_price ? _max_price : _price_tmp;
						if ($that.hasClass('ft-price-min')) {
							var _maxp = $(".ft-price-max", $_gr_prices).val();
							_maxp = (_maxp != '' ) ? _maxp : _max_price;
							_price_tmp = _price_tmp >= _maxp ? _maxp : _price_tmp;
							$that.val(_price_tmp);
							$_slide_prices.slider("values", 0, _price_tmp);
						} else {
							var _minp = $(".ft-price-min", $_gr_prices).val();
							_minp = (_minp != ''  ) ? _minp : _min_price;
							_price_tmp = (_price_tmp >= _minp && _price_tmp <= _max_price  ) ? _price_tmp : _minp;
							$that.val(_price_tmp);
							$_slide_prices.slider("values", 1, _price_tmp);
						}
					} else {
						if ($that.hasClass('ft-price-min')) {
							$that.val('');
							$_slide_prices.slider("values", 0, _min_price);
						} else {
							$that.val('');
							$_slide_prices.slider("values", 1, _max_price);
						}

					}
					processAjax();
				}, 1000);

			});

//		var $_open_close = $('.ft-open-close ', $_group_ft);
//		$_open_close.on('click ', function () {
//			var $_parent = $(this).parents('.ft-heading');
//			$_parent.siblings('.ft-content').stop(true, false).slideToggle(400, function () {
//				if ($_parent.parent().hasClass('ft-open')) {
//					$_parent.parent().removeClass('ft-open').addClass('ft-close');
//				}
//				else {
//					$_parent.parent().removeClass('ft-close').addClass('ft-open');
//				}
//			});
//
//		});

			var $_ft_heading = $('.ft-heading', $_group_ft);
			$_ft_heading.on('click', function () {
				$(this).siblings('.ft-content').stop(true, false).slideToggle(400, function () {
					if ($(this).parent().hasClass('ft-open')) {
						$(this).parent().removeClass('ft-open').addClass('ft-close');
					} else {
						$(this).parent().removeClass('ft-close').addClass('ft-open');
					}
				});
			});

			if ($('.ft-group', $element).width() <= 200) {
				$('.sj-vm-filter .ft-content-prices .ft-price-value input[type="text"]').css('width', '38px');
			}

			//.ft-opt-count
			var $_label_opt = $('.ft-opt-name, .ft-color-value', $_group_ft);
			$_label_opt.on('click ', function () {
				var _input_check = $(this).siblings('input[type="checkbox"]'),
					_checked = _input_check.prop('checked'),
					_color_value = $(this).is('.ft-color-value');
				if (_checked) {
					if (_color_value) {
						$(this).removeClass('ft-checked');
					}
					_input_check.removeAttr('checked');
				} else {
					if (_color_value) {
						$(this).addClass('ft-checked');
					}
					_input_check.attr('checked', 'checked');
				}
				processAjax();
			});

			function showClearAll() {
				var $ft_content = $('.ft-content', $_group_ft);
				$ft_content.each(function () {
					var $that = $(this);
					var $i = 0;
					$(':input', $that).each(function () {
						if ($(this).prop('checked')) {
							$i++;
						}
					});
					if ($i > 0) {
						$('.ft-opt-clearall', $that).fadeIn(500);
					} else {
						$('.ft-opt-clearall', $that).fadeOut(500);
					}
				});
			}

			$('input[type="hidden"]', $element).val('');
			$('input[type="hidden"]', $_group_ft).val('');
			$(':checkbox  ', $_group_ft).removeAttr('checked');
			$(':checkbox  ', $_group_ft).on('click', function () {
				processAjax();
			});

			$('.ft-opt-clearall', $_group_ft).unbind('click.clearall').on('click.clearall', function () {
				var _ft_select = $(this).siblings('.ft-select');
				$('input[type="checkbox"]', _ft_select).removeAttr('checked');
				$('.ft-color-value', _ft_select).removeClass('ft-checked');
				processAjax();
			});

			var _config_global = '';

			function processAjax() {
				var fields = $(":input", $element).serialize();
				 _config_global = fields;
				showClearAll();
				var _loading = $('<div class="sj-loading" ><div class="ft-image-loading"></div></div>');
				$("body").append(_loading);
				$.ajax({
					type: 'POST',
					url: $_ajax_url,
					data: {
						is_ajax_ft: 1,
						ft_module_id: <?php echo $module->id ?>,
						_config_data: _config_global
					},
					success: function (data) {
						_loading.remove();
						if (data.items_markup != '') {
							$('.ft-filtering', $_ft_form).replaceWith(data.items_markup);
							updateAfterLoadAjax();
						}
						if (data.filter_product != '') {
							if ($('<?php echo $params->get('area_results'); ?>').length) {
								$('<?php echo $params->get('area_results'); ?>').html(data.filter_product);
								updateAfterLoadAjax();
							}
						}
						if (data == 'noresults') {
							window.location.href = $_ajax_url;
						}
					},
					error: function () {
						_loading.remove();
					},
					dataType: 'json'
				});
			}

			var parseQueryString = function (queryString) {
				var params = {}, queries, temp, i, l;
				queries = queryString.split("&");
				for (i = 0, l = queries.length; i < l; i++) {
					temp = queries[i].split('=');
					params[temp[0]] = temp[1];
				}
				return params;
			};

			function updateAfterLoadAjax() {
				if(typeof  Virtuemart !== 'undefined'){
					var $_form = $("form.product");
					Virtuemart.product($_form);
				 }
				 
				var $_ft_result = $('#ft_results_<?php echo $module->id; ?>');
				$('.orderlistcontainer', $_ft_result).hover(
					function () {
						$(this).find('.orderlist').stop().show()
					},
					function () {
						$(this).find('.orderlist').stop().hide()
					}
				)
				var $_orderList = $('.orderlist', $_ft_result);
				if ($_orderList.length > 0) {
					$_orderList.children().on('click', function () {
						var _href = $('a ', $(this)).attr('href');
						_href = _href.replace(/\?/g, '&').replace('by,', 'orderby=');
						_href = _href.replace(/\//g, '&');
						var _orderby = parseQueryString(_href);
						$('.config-orderby', $element).attr('value', _orderby.orderby);
						processAjax();
						return false;

					});
				}

				var $_selectbox = $('select.inputbox', $_ft_result);
				var _limit = $('option:selected', $_selectbox).text();
				$('option:selected', $_selectbox).each(function(){
					var _limit = $(this).text();
					$('.config-limit', $element).attr('value', _limit);
					$_selectbox.removeAttr('onchange');
					$_selectbox.on('change', function () {
						var _value = $('option:selected', $(this)).text();
						$('.config-limit', $element).attr('value', _value);
						processAjax();
						return false;
					});
				});

				//add product_load_limit last run processAjax() in select box
				var pro_load = "<?php echo $params->get('limit_results',5)?>";
				var url_option = "<?php echo JURI::base(true).'/?limit=';?>";
					var _option = '<option value="' + url_option + pro_load + '">' + pro_load + '</option>';
					var $_selectbox_2 = $('select.inputbox option:first', $_ft_result);
					if ($_selectbox_2.text() != pro_load) {
						$_selectbox_2.before(_option);
					}

				var $vm_pagination = $('.vm-pagination ul', $_ft_result);
				if ($vm_pagination.length > 0) {
					$vm_pagination.children().on('click', function () {
						var $this = $(this);
						if ($this.is('.disabled') || $this.is('.active')) {
							return false;
						} else {
							var _href = $('a ', $(this)).attr('href');
							_href = _href.replace(/\?/g, '&').replace('results,', 'limit_start=');
							_href = _href.replace(/\//g, '&');
							var _lmstart = parseQueryString(_href);
							var _start = 0;
							if (typeof _lmstart.limit_start != 'undefined') {
								_start = _lmstart.limit_start
								_start = _start.split("-");
								_start = _start[1];
							} else if (typeof _lmstart.start != 'undefined') {
								_start = _lmstart.start;
							}
							$('.config-limitstart', $element).attr('value', _start);
							processAjax();
						}
						return false;
					});
				}
				$('.vm-view-list .vm-view').on('touchstart click', function(e){
					e.preventDefault();
					var that = $(this);
					if (that.hasClass('active'))
						return;
					if (!that.hasClass('active')){
						$('.vm-view-list .vm-view').removeClass('active');
						that.addClass('active');
					}else{
						that.removeClass('active');
					}
					
					if (that.hasClass('view-list') && that.hasClass('active') ){
						$('.browse-view .product').addClass('vm-col-1');
					}else{
						$('.browse-view .product').removeClass('vm-col-1');
					}
					
					
				});

				var $_filtering = $('.ft-filtering', $element), _ft_opt_close = $('.ft-opt-close', $_filtering),
					_filtering_clearall = $('.ft-filtering-clearall', $_filtering);
				_ft_opt_close.on('click', function () {
					var _data_value = $(this).parent().attr('data-filter'),
						_cls_ft = $('.' + _data_value);
					if (_cls_ft.length > 0) {
						$(':checkbox', _cls_ft).removeAttr('checked');
						$(_cls_ft).attr('value', '');
						$('.ft-color-value', _cls_ft).removeClass('ft-checked');
						processAjax();
					}
				});

				_filtering_clearall.on('click', function () {
					var _opt_inner = $('.ft-opt-inner', $_filtering);
					if (_opt_inner.length > 0) {
						_opt_inner.each(function () {
							var _data_value = $(this).attr('data-filter'),
								_cls_ft = $('.' + _data_value);
							$(':checkbox', _cls_ft).removeAttr('checked');
							$(_cls_ft).attr('value', '');
							$('.ft-color-value', _cls_ft).removeClass('ft-checked');
						});
						processAjax();
					}
				});

			}


		})('#<?php echo $tag_id; ?>');
	});
	//]]>
</script>