<?php
/** 
 * YouTech menu template file.
 * 
 * @author The YouTech JSC
 * @package menusys
 * @filesource default.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */
global $yt;
$typelayout = $yt->getParam('layouttype');

?>

<?php
if ($this->isRoot()){
	if($typelayout=='res'){ ?>
		<div id="resmenu" class="slideout-menu hidden-md hidden-lg">
			<ul class="nav resmenu">
			<?php
			if($this->haveChild()){
				$idx = 0;
				foreach($this->getChild() as $child){
					++$idx;
					$child->getContent('collapse');
				}
			}
			?>
			</ul>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			var slideout = jQuery('#resmenu');
			var widthMenu = $('.slideout-menu').width(); 
			
			slideout.css({left:-widthMenu});
			
			var bd = jQuery('<div class="slide-modal modal-backdrop fade in"></div>');
			jQuery('.js-slideout-toggle').on('click', function() {
				slideout.animate({left:"0px"});
				bd.appendTo(document.body);
			});
			
			jQuery('body').on('touchstart click','.slide-modal', function(e){
				e.stopPropagation(); e.preventDefault();
				jQuery(this).closest('.slide-modal').remove();
				slideout.animate({left:-widthMenu});
			});

		});
		</script>
	<?php
	}
}
?>
