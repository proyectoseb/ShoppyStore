(function($){
	$.fn.customUpdateVirtueMartCartModule = function(el, options){
		var base 	= this;
		var $this	= $(this);
		base.$el 	= $(".vmCartModule");

		base.options 	= $.extend({}, $.fn.customUpdateVirtueMartCartModule.defaults, options);		
			
		base.init = function(){
			$.ajaxSetup({ cache: false })
			$.getJSON(window.vmSiteurl + "index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json" + window.vmLang,
				function (datas, textStatus) {
					base.$el.each(function( index ,  module ) {
						if (datas.totalProduct > 0) {
							$(module).find(".vm_cart_products").html("");
							$.each(datas.products, function (key, val) {
								//jQuery("#hiddencontainer .vmcontainer").clone().appendTo(".vmcontainer .vm_cart_products");
								$(module).find(".hiddencontainer .vmcontainer .product_row").clone().appendTo( $(module).find(".vm_cart_products") );
								$.each(val, function (key, val) {
									$(module).find(".vm_cart_products ." + key).last().html(val);
								});
							});
						}
						$(module).find(".show_cart").html(		datas.cart_show);
						$(module).find(".total_products").html(	datas.totalProductTxt);
						$(module).find(".total").html(		datas.billTotal);
					});
				}
			);			
		};
		base.init();
	};
	// Definition Of Defaults
	$.fn.customUpdateVirtueMartCartModule.defaults = {
		name1: 'value1'
	};

})(jQuery);

jQuery(document).ready(function( $ ) {
	$("body").live("updateVirtueMartCartModule", function(e) {
		$().customUpdateVirtueMartCartModule();
	});
});
jQuery( document ).ready(function( $ ) {
// Code that uses jQuery's $ can follow here.
	
});