<?php

defined('_JEXEC') or die;
$event = $params->get("event","click");
$duration = $params->get("duration",300);
?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($){
	;(function(element){
		$element = $(element);
		$('li:first-child',$element).addClass("fm-first");
		$('li:last-child',$element).addClass("fm-last");
		$('.fm-container',$element).each(function(){
			$('ul > li',$(this)).eq(0).addClass("fm-first");
			$('ul > li:last-child',$(this)).addClass("fm-last");
		});
		if($('li.fm-active ',$element).length > 0){
			$('li.fm-active ',$element).parents($('li',$element)).addClass('fm-active');
		}
		
		<?php if($event == "click"){?>
		$element.find(".fm-item").click(function(){
            
			var li = $(this).parent();
			if(!li.hasClass("fm-opened")){
				var fl_openedLi = li.parent().children(".fm-opened");
				var ul = li.children(".fm-container");
				if(ul.length > 0) {
					<?php if ($type_menu == 'flyout') {?>
					fl_openedLi.children(".fm-container").hide(<?php echo $duration;?>);
					<?php } else { ?>
					fl_openedLi.children(".fm-container").slideUp(<?php echo $duration;?>);	
					<?php }?>
					fl_openedLi.removeClass("fm-opened");
					fl_openedLi.children(".fm-item").children(".fm-button").children("img").attr("src", "<?php echo $icon_normal;?>");
					li.addClass("fm-opened");
					li.children(".fm-item").children(".fm-button").children("img").attr("src", "<?php echo $icon_active;?>");
					<?php if ($type_menu == 'flyout') {?>
					ul.show(<?php echo $duration;?>);
					<?php } else { ?>
					ul.slideDown(<?php echo $duration;?>);
					<?php }?>
				}
			}else{
				<?php if ($type_menu == 'flyout') {?>
				li.children(".fm-container").hide(<?php echo $duration;?>);
				<?php } else { ?>
				li.children(".fm-container").slideUp(<?php echo $duration;?>);
				<?php }?>
				li.removeClass("fm-opened");
				li.children(".fm-item").children(".fm-button").children("img").attr("src", "<?php echo $icon_normal;?>");
			}
			//return false;
		});
		// $("body").click(function(){
			// $(".fm-opened").removeClass("fm-opened");
			// $(".fm-container").hide(<?php //echo $duration;?>); 
			// $('.fm-item',$element).parent().children(".fm-item").children(".fm-button").children("img").attr("src", "<?php //echo $icon_normal;?>");
		// });	
		<?php }else{?>
		var _time = 0;
		$element.find("li").mouseenter(function(){
			var ul = $(this).children(".fm-container");
			if(ul.length > 0) {
				if(_time > 0)  clearTimeout(_time);
				$(this).addClass("fm-opened");
				<?php if ($type_menu == 'flyout') {?>
				_time = setTimeout(function(){
					ul.show(<?php echo $duration;?>);
				}, 100);
				<?php } else { ?>
				_time = setTimeout(function(){
					ul.slideDown(<?php echo $duration;?>);
				}, 100);
				<?php }?>
				$(this).children(".fm-item").children(".fm-button").children("img").attr("src", "<?php echo $icon_active;?>");
			}
		}).mouseleave(function(){
			var $this = $(this);
			if($this.children(".fm-container").length > 0) {
			if(_time > 0)  clearTimeout(_time);
			<?php if ($type_menu == 'flyout') {?>
			time = setTimeout(function(){
					$this.children(".fm-container").hide(<?php echo $duration;?>);
				}, 100);
			//$(this).children(".fm-container").hide(<?php echo $duration;?>);
			<?php } else { ?>
			time = setTimeout(function(){
					$this.children(".fm-container").slideUp(<?php echo $duration;?>);
				}, 100);
			//$(this).children(".fm-container").slideUp(<?php echo $duration;?>);
			<?php }?>
			$this.removeClass("fm-opened");
			$this.children(".fm-item").children(".fm-button").children("img").attr("src", "<?php echo $icon_normal;?>");
			//$(this).find(".fm-container").css("display","none");
			$this.find(".fm-opened").removeClass("fm-opened");
			//return false;
			}
		});
	<?php }?>	
	
	})('#<?php echo $tag_id ; ?>');
});
//]]>
</script>
