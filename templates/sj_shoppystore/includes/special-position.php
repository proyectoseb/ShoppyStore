<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
defined('_JEXEC') or die;
?>

	<?php
	// Sticky SlideBar
	$stickyBar = $yt->getParam('stickyBar');
	if($doc->countModules('stickyBar') && $stickyBar !='no'){ ?>
    <div id="yt_sticky_<?php echo $stickyBar; ?>" class="yt-slideBar hidden-sm hidden-xs">
        <div class="yt-notice container">
			<jdoc:include type="modules" name="stickyBar" style="ytmod" />
        </div>
    </div>
    <?php } ?>
	
    <?php
	// Sticky SlidePanel
	$stickyPanel = $yt->getParam('stickyPanel');
	if($doc->countModules('stickyPanel') && $stickyPanel !='no' ):
	?>
		<div id="yt_sticky_<?php echo $stickyPanel; ?>" class="yt-slidePanel hidden-sm hidden-xs" >
			<div class="yt-sticky">
				<jdoc:include type="modules" name="stickyPanel" style="special" />
			</div>
		</div>
		
    <?php endif;?>
    
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var events = '<?php echo $yt->getParam("eventsSpecialPostion", "click")?>';
			
			<?php 
			$stickyBar = $yt->getParam('stickyBar');
			$countModBar = $doc->countModules('stickyBar');
			$stickyPanel = $yt->getParam('stickyPanel');
			$countModPanel = $doc->countModules('stickyPanel');
			
			// Sticky SlideBar
			if ($stickyBar == 'top' && $countModBar){ ?>
				YTScript.slidePositionNotice('#yt_sticky_top','top', 'activeNotice');
			<?php } else if  ($stickyBar == 'bottom' && $countModBar){?>
				YTScript.slidePositionNotice('#yt_sticky_bottom','bottom', 'activeNotice');
			<?php } ?>
			
			
			<?php 
			// Sticky SlidePanel
			if ($stickyPanel == 'left' && $countModPanel){ ?>
				YTScript.slidePositions('#yt_sticky_left .yt-sticky', 'left', events);
			<?php } else if  ($stickyPanel == 'right' && $countModPanel){?>
				YTScript.slidePositions('#yt_sticky_right .yt-sticky', 'right', events);
			<?php } ?>
		});
	</script>





