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
?>
<script type="text/javascript">
//<![CDATA[
<?php if ($params->get('item_addtocart_display', 1) == 1 && VmConfig::get('addtocart_popup') == 1) { ?>
        if (typeof  Virtuemart !== 'undefined') {
        Virtuemart.addtocart_popup = 1;
        usefancy = "<?php echo VmConfig::get('usefancy')?>";
        vmLang = "<?php echo VmConfig::get('vmLang') != ''?VmConfig::get('vmLang'):"";?>";
        window.vmSiteurl = "<?php echo JUri::base();?>";
		var _carts =  jQuery("#<?php echo $module_id;?>").find('form');
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
			
jQuery(document).ready(function ($) {
	
	 $("#<?php echo $module_id;?> .addtocart-button input").val('');
	 $("#<?php echo $module_id;?> .addtocart-button input").parent().append('<i class="fa fa-gift"></i>');
	 var total_product = '<?php echo $params->get('source_limit');?>';
	var owl = $("#<?php echo $module_id;?> .owl-carousel");
			var nb_column1 = <?php echo $params->get('nb-column1'); ?>,
			nb_column2 = <?php echo $params->get('nb-column2'); ?>,
			nb_column3 = <?php echo $params->get('nb-column3'); ?>,
			nb_column4 = <?php echo $params->get('nb-column4'); ?>;
			owl.owlCarousel({
                nav: <?php echo $params->get('display_nav_s') ; ?>,
                dots: false,
                margin: 0,
                loop:  <?php echo $params->get('display_loop_s') ; ?>,
                autoplay: <?php echo $params->get('autoplay_s'); ?>,
                autoplayHoverPause: <?php echo $params->get('pausehover_s') ; ?>,
                autoplayTimeout: <?php echo $params->get('autoplay_timeout_s') ; ?>,
                autoplaySpeed: <?php echo $params->get('autoplay_speed_s') ; ?>,
                mouseDrag: <?php echo  $params->get('mousedrag_s'); ?>,
                touchDrag: <?php echo $params->get('touchdrag_s'); ?>,
                navRewind: true,
                navText: [ '<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>' ],
                responsive: {
                    0: {
                        items: nb_column4,
                        nav: total_product <= nb_column4 ? false : ((<?php echo $params->get('display_nav_s') ; ?>) ? true: false),
                    },
                    480: {
                        items: nb_column3,
                        nav: total_product <= nb_column3 ? false : ((<?php echo $params->get('display_nav_s') ; ?>) ? true: false),
                    },
                    768: {
                        items: nb_column2,
                        nav: total_product <= nb_column2 ? false : ((<?php echo $params->get('display_nav_s') ; ?>) ? true: false),
                    },
                    1200: {
                        items: nb_column1,
                        nav: total_product <= nb_column1 ? false : ((<?php echo $params->get('display_nav_s') ; ?>) ? true: false),
                    },
                }
            });

	  $("#<?php echo $module_id;?> .item .item-rating").each(function(){
		  var item = $(this);
		  var rating = item.attr('data-rating');
		  if(parseFloat(rating) > 4.5){
			   item.find('.item-star').addClass('item-rating-chose');
		  }else{
			  for(var i = 0; i < 5; i++){
				  var a = i + 1;
				  var b = parseFloat(a) + 0.5;
				  var c = parseFloat(a) - 0.5;
				  if((c < parseFloat(rating)  && parseFloat(rating) < b) || b < parseFloat(rating)){
					  item.find('.item-star').eq(i).addClass('item-rating-chose');
				  }
			  }			  
		  }
		  
	  });
	  <?php if($type != 'POP'){?>
	 function mktime(time){
		 var array_time = time.split(' ');
		 var array_year = array_time[0].split('-');
		 var array_minus = array_time[1].split(':');
		  a=new Date()
		  a.setHours(array_minus[0]);
		  a.setSeconds(array_minus[2]);
		  a.setMinutes(array_minus[1]);
		  a.setDate(array_year[2]);
		  a.setYear(array_year[0]);
		  a.setMonth(array_year[1]);
		  return a.getTime();
	}
	var array_time = new Array();
	var time_now = mktime('<?php echo $time_now;?>');
	var i = 0;
	$("#<?php echo $module_id;?> .owl-carousel .item").each(function(){
		var mk_time = $(this).find('.item-deals').attr('data-deals');
		if(typeof mk_time != 'undefined'){
		array_time[i] = mktime(mk_time);
		var minutes = 1000 * 60;
		var hours = minutes * 60;
		var days = hours * 24;
		var years = days * 365;
		var html_day = parseInt((parseInt(array_time[i]) - parseInt(time_now))/days);
		var html_hours =  parseInt((parseInt(array_time[i]) - parseInt(time_now) - html_day*days)/hours);
		var html_minus = parseInt((parseInt(array_time[i]) - parseInt(time_now) - html_day*days - html_hours*hours)/minutes);
		var html_seconds =  parseInt((parseInt(array_time[i]) - parseInt(time_now) - html_day*days - html_hours*hours - html_minus*minutes)/1000);
		$(this).find('.item-deals').html('<div class="sj_deals_cd_day">'+(html_day)+'<p>Days</p></div><div class="sj_deals_cd_day">'+html_hours+'<p>Hours</p></div><div class="sj_deals_cd_day">'+html_minus+'<p>Mins</p></div><div class="sj_deals_cd_day">'+html_seconds+'<p>Secs</p></div>');
		i++;
		}
	});
		var mk_time = $("#<?php echo $module_id;?> .sj_vm_deals_first_product .item").find('.item-deals').attr('data-deals');
		if(typeof mk_time != 'undefined'){
		var mk_time_first_product = mktime(mk_time);
		var minutes = 1000 * 60;
		var hours = minutes * 60;
		var days = hours * 24;
		var years = days * 365;
		var html_day = parseInt((parseInt(mk_time_first_product) - parseInt(time_now))/days);
		var html_hours =  parseInt((parseInt(mk_time_first_product) - parseInt(time_now) - html_day*days)/hours);
		var html_minus = parseInt((parseInt(mk_time_first_product) - parseInt(time_now) - html_day*days - html_hours*hours)/minutes);
		var html_seconds =  parseInt((parseInt(mk_time_first_product) - parseInt(time_now) - html_day*days - html_hours*hours - html_minus*minutes)/1000);
		$("#<?php echo $module_id;?> .sj_vm_deals_first_product .item").find('.item-deals').html('<div class="sj_deals_cd_day">'+(html_day)+'<p>Days</p></div><div class="sj_deals_cd_day">'+html_hours+'<p>Hours</p></div><div class="sj_deals_cd_day">'+html_minus+'<p>Mins</p></div><div class="sj_deals_cd_day">'+html_seconds+'<p>Secs</p></div>');
		}
	setInterval(function(){
		time_now = time_now + 1000;
		var i = 0;
		$("#<?php echo $module_id;?> .owl-carousel .item").each(function(){
			var minutes = 1000 * 60;
			var hours = minutes * 60;
			var days = hours * 24;
			var years = days * 365;
			var html_day = parseInt((parseInt(array_time[i]) - parseInt(time_now))/days);
			var html_hours =  parseInt((parseInt(array_time[i]) - parseInt(time_now) - html_day*days)/hours);
			var html_minus = parseInt((parseInt(array_time[i]) - parseInt(time_now) - html_day*days - html_hours*hours)/minutes);
			var html_seconds =  parseInt((parseInt(array_time[i]) - parseInt(time_now) - html_day*days - html_hours*hours - html_minus*minutes)/1000);
			$(this).find('.item-deals').find('.sj_deals_cd_day').eq(0).html(html_day + '<p>Days</p>');
			$(this).find('.item-deals').find('.sj_deals_cd_day').eq(1).html(html_hours + '<p>Hours</p>');
			$(this).find('.item-deals').find('.sj_deals_cd_day').eq(2).html(html_minus + '<p>Mins</p>');
			$(this).find('.item-deals').find('.sj_deals_cd_day').eq(3).html(html_seconds + '<p>Secs</p>');
			i++;
		});
			var minutes = 1000 * 60;
			var hours = minutes * 60;
			var days = hours * 24;
			var years = days * 365;
			var html_day = parseInt((parseInt(mk_time_first_product) - parseInt(time_now))/days);
			var html_hours =  parseInt((parseInt(mk_time_first_product) - parseInt(time_now) - html_day*days)/hours);
			var html_minus = parseInt((parseInt(mk_time_first_product) - parseInt(time_now) - html_day*days - html_hours*hours)/minutes);
			var html_seconds =  parseInt((parseInt(mk_time_first_product) - parseInt(time_now) - html_day*days - html_hours*hours - html_minus*minutes)/1000);
			$("#<?php echo $module_id;?> .sj_vm_deals_first_product .item").find('.sj_deals_cd_day').eq(0).html(html_day + '<p>Days</p>');
			$("#<?php echo $module_id;?> .sj_vm_deals_first_product .item").find('.sj_deals_cd_day').eq(1).html(html_hours + '<p>Hours</p>');
			$("#<?php echo $module_id;?> .sj_vm_deals_first_product .item").find('.sj_deals_cd_day').eq(2).html(html_minus + '<p>Mins</p>');
			$("#<?php echo $module_id;?> .sj_vm_deals_first_product .item").find('.sj_deals_cd_day').eq(3).html(html_seconds + '<p>Secs</p>');
	}, 1000);
	  <?php }?>
	  setTimeout(function(){
			$('.sj_quickview_handler').html('<i class="fa fa-search"></i>');
	  },1500);
	  
	  $("#<?php echo $module_id;?> .sj_percentage_discount_display").each(function(){
		  if($(this).find('a').length > 0){
			  var html = $(this).find('a').html();
			  $(this).html(html);
		  }		  
	  })
	  <?php if($params->get('display_nav_s') == 0){ ?>
		   $("#<?php echo $module_id;?> .owl-nav").css('display','none');
	  <?php } ?>
});
//]]>
</script>