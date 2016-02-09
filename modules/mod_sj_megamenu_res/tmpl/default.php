<?php
/**
 * @package Sj Megamenu
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;
$instance	= rand().time();
$menu_class_sfx = $params->get('moduleclass_sfx');
$menu_id = $menu_tag_id."_".$instance;
$detect = new Mobile_Detect();?>

<!--[if lt IE 9]><div id="<?php echo $menu_id; ?>" class="sambar msie lt-ie9 " data-sam="<?php echo $instance; ?>"><![endif]-->
<!--[if IE 9]><div id="<?php echo $menu_id; ?>" class="sambar msie  " data-sam="<?php echo $instance; ?>"><![endif]-->
<!--[if gt IE 9]><!--><div id="<?php echo $menu_id; ?>" class="sambar" data-sam="<?php echo $instance; ?>"><!--<![endif]-->
	<div class="sambar-inner">
		<a class="btn-sambar" data-sapi="collapse" href="<?php echo '#'.$menu_id; ?>">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
	
<ul class="sj-megamenu sj-megamenu<?php echo $wrap_cls;?> <?php echo $menu_class_sfx; ?> <?php if( $params->get('menu_event') == 'hover' ){
	if ( !$detect->isMobile() && !$detect->isTablet() ) { echo "sj-megamenu-hover";}}?>" data-jsapi="on">

	<?php 
	foreach ($list as $i => &$item) {

		// id for itemid
		$id = $item->params->get('xmp_tag_id', '');
		if (!empty($id)) {
			$id = ' id="'.trim($id) .'"';
		}

		// class for itemid
		$class = 'item-'.$item->id;
		if ($item->id == $active_id) {
			$class .= ' current';
		}

		// class for active state
		if (in_array($item->id, $path)) {
			$class .= ' active';
		} elseif ($item->type == 'alias') {
			// alias of active item.
			$aliasToId = $item->params->get('aliasoptions');
			if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
				$class .= ' active';
			} elseif (in_array($aliasToId, $path)) {
				$class .= ' alias-parent-active';
			}
		}

		// class for leel
		$class .= ' level-'.$item->level;

		if ($item->level == 1 && $item->params->get('xmp_float')=='right' ){
			$class .= ' mega-right';
		}

		$fixed_width = (int)$item->params->get('xmp_fixed_width', 0);

		$show_as = $item->params->get('xmp_show_as', 'mega-dropofly');
		if ($item->level >= 2){
			$fly = $item->params->get('xmp_fly_dir', 'right'); // left / right
			if ($show_as == 'mega-dropofly'){
				if ($fly=='left'){
					$class .= ' fly-left';
				} else if ($fly=='right'){
					// default
					$class .= ' fly-right';
				}
			} else {
				$fixed_width = 0;
				$class .= ' '.$show_as;
			}
		} else {
			
		}
		
		if ($item->id==540){
			
		}

		if ($item->parent) {
			$class .= ' parent';
		}

		if($item->params->get('xmp_tag_class') != null ){
			$col = $item->params->get('xmp_tag_class');
			$class .= " ".$col;
		}

		// TODO: best way params get method
		$xmp_type = $item->params->get('xmp_contenttype', '');
		if( $xmp_type ){
			$class .= " ".$xmp_type;
			if (!$item->parent && $xmp_type=='mod'){
				$class .= ' parent';
			}
		}

		//$event = $item->params->get('xmp_events');
		//$class .= " ".$event;

		if (!empty($class)) {
			$class = ' class="'.trim($class) .'"';
		}?>

	<li <?php echo $id;?> <?php echo $class;?>>
	<?php
		if ( empty($xmp_type) ){
			// Render the menu item.
			switch ($item->type){
				case 'separator':
				case 'url':
				case 'component':
					require JModuleHelper::getLayoutPath($module->module, $layout.'_'.$item->type);
					break;
				default:
					require JModuleHelper::getLayoutPath($module->module, $layout.'_url');
					break;
			}
		} else {
			switch ($xmp_type){
				case 'mod':
					require JModuleHelper::getLayoutPath($module->module, $layout.'_module');
					break;
				case 'divider':
					break;
			}
		}

	// The next item is deeper.
	$showchild = (int)$item->params->get('xmp_showchild');
	$fly = $item->params->get('xmp_position');
	if ($item->deeper) {?>
		<div class="sj-megamenu-child"
			<?php if ( $fixed_width ): ?>
			style="width: <?php echo $fixed_width-20;?>px"
			<?php endif; ?>
		>
		<ul class="submenu <?php echo $item->params->get('xmp_cols');?>">
			<?php
	} else if ($item->shallower) {
		// The next item is shallower.
		echo '</li>';
		echo str_repeat('</ul></div></li>', $item->level_diff);
	} else {
		// The next item is on the same level.
		echo '</li>';
	}}?>
</ul>
	</div>
</div>
<?php if( $params->get('css_style') == 0 ){?>
	<script type="text/javascript">
	jQuery(function($){		
		$('#<?php echo $menu_id; ?> .sj-megamenu').each(function(){
			var $menu = $(this), clearMenus = function( el ){
				var parents = $(el).parents();
				$menu.find('.actived').not(parents).not(el).each( function(){
					$(this).removeClass('actived');
					$(this).children('.sj-megamenu-child').slideUp(0);
				});
			};
			var window_w = $(window).width();

				<?php if( $params->get('menu_event') == 'click'  && $params->get('type_show') == 'horizontal' ){ ?>
					//console.log($menu);					
					$('.level-1 > a', $menu).click( function(e){
						e.preventDefault();
						$parent = $(this).parent();						
						$sub = $(this).next();						
						clearMenus($parent);
						$parent.toggleClass('actived');						
						if($parent.hasClass('actived')){							
							$sub.hide().slideDown(300);							
						}else{
							$sub.hide().slideUp(300);
						}				
						return false;
					});
					
					$('.level-4 > a', $menu).click( function(e){
						e.preventDefault();
						$parent = $(this).parent();						
						$sub = $(this).next();						
						clearMenus($parent);
						$parent.toggleClass('actived');
						
						if($parent.hasClass('actived')){
							$sub.hide().slideDown(300);										
						}else{
							$sub.hide().slideUp(300);
						}
					
						return false;
					});
					
					
					/*$menu.on('click', '.parent.level-1', function(e){
						e.preventDefault();
						var $this = $(this), data = $this.data(), $dropdown = $this.children('.sj-megamenu-child');
						clearMenus($this);
						$this.toggleClass('actived');
						if($this.hasClass('actived')){
							$dropdown.hide().slideDown(300);
						}else{
							$dropdown.hide().slideUp(300);
						}
						return false;
					});*/

				<?php } else { ?>
					if(window_w <= 980)
					{
						$('.level-1 > a', $menu).click( function(e){
							e.preventDefault();
							$parent = $(this).parent();						
							$sub = $(this).next();						
							clearMenus($parent);
							$parent.toggleClass('actived');
							
							if($parent.hasClass('actived')){
								$sub.hide().slideDown(300);							
							}else{
								$sub.hide().slideUp(300);
							}
						
							return false;
						});
					}
					else
					{
						$( '#<?php echo $menu_id; ?> .sj-megamenu .parent > a' ).hover(function(){
							var $this = $(this), data = $this.data(), $dropdown = $this.children( '.sj-megamenu-child' );
							$parent = $(this).parent();
							if( $this.hasClass('actived') && !$this.hasClass('mega-pinned') ){
								$dropdown.hide().slideDown(300);
							}
						},function(){
							var $this = $(this), data = $this.data(), $dropdown = $this.children( '.sj-megamenu-child' );
							$this.removeClass('actived');
							if( !$this.hasClass('mega-pinned') ){
								$dropdown.hide().slideUp(300);
							}
						});
						return false;
					}
				<?php } ?>
			});
			
			<?php if($params->get('type_show') != 'horizontal' ){?>
				var window_w = $(window).width();
				if(window_w <= 980){
					$('.sj-megamenu-vertical .level-1').click(function(){
						if($(this).hasClass('slideUp')){
							$(this).find('.sj-megamenu-child').slideUp();
							$(this).removeClass('slideUp');
						}else{
							$(this).find('.sj-megamenu-child').slideDown();
							$(this).addClass('slideUp');
						}
						
					});
				}
			<?php } ?>
		});
	</script>
<?php } ?>


