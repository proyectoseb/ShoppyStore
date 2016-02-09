<?php
/**
 * @package Sj Flat Menu
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

$tag_id = 'sj_flat_menu_'.rand().time();


JHtml::stylesheet('modules/'.$module->module.'/assets/css/styles-responsive.css');
JHtml::stylesheet('modules/'.$module->module.'/assets/css/styles.css');
if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/'.$module->module.'/tmpl/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/tmpl/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
} 
$menu_direction = $params->get("menu_direction");
$style_layout = ($type_menu == 'flyout')?$params->get('stype_layout'):'';
$cls =  $type_menu.'-menu'.' '.$style_layout;
?>

<?php if( $params->get('pretext') != '' ) { ?>
    <div class="pretext"><?php echo $params->get('pretext'); ?></div>
<?php } ?>
<?php
if(isset($menus) && count($menus)){
	$countUlOpened = 0; $level = 1; 
	for($i = 0; $i < count($menus); $i++){
		if($i == 0){ ?>

			<!--[if lt IE 9]><ul class="sj-flat-menu <?php echo $cls ; ?> lt-ie9 " id="<?php echo $tag_id;?>"><![endif]-->
			<!--[if IE 9]><ul class="sj-flat-menu <?php echo $cls ; ?>" id="<?php echo $tag_id;?>"><![endif]-->
			<!--[if gt IE 9]><!--><ul class="sj-flat-menu <?php echo $cls ; ?>" id="<?php echo $tag_id;?>"><!--<![endif]-->
            <?php
			$countUlOpened++;
		}
		$class = "";
		if($menus[$i]->id == $itemID){
			$class	.= ' class=" fm-active " ';
			$class	.= ' ';
		}
		$li = "<li ".$class.">";
 			$li .= "<div class='fm-item '>";
				$divMenuButton = "<div class='fm-button' >";
				if($i < count($menus)-1 && $menus[$i+1]->level > $menus[$i]->level){
					$divMenuButton.="<img class='fm-icon' title='".$menus[$i]->title."' alt='".$menus[$i]->title."' src='".$icon_normal."'/>";
					//$divMenuButton.="<span class='fm-icon'>&raquo;</span>";
				}
				$divMenuButton .= "</div>";
				$li.=$divMenuButton;
				$style = "";
				$target = "";
				$link_target = $params->get('link_target','_self');
				switch($link_target){
					default:
					case '_self':
						break;
					case '_blank':
					case '_parent':
					case '_top':
						$target = 'target="'.$link_target.'"';
						break;
					case '_windowopen':
						$target = "onclick=\"window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,false');return false;\"";
						break;
					case '_modal':
						// user process
						break;
				}
				$icon_menu = ($menus[$i]->menu_image != '')?'<img src='.JURI::base().$menus[$i]->menu_image.' alt='.$menus[$i]->title.'  title='.$menus[$i]->title.' />':'';
				$divLink = "<div class='fm-link' ".$style."><a ".$target." href='".$menus[$i]->flink."'>".$icon_menu.$menus[$i]->title."</a></div>";
			$li.=$divLink;
			$li.= "</div>";
			echo $li;
				if($type_menu == 'flyout') {
					if($i < count($menus)-1  && $menus[$i+1]->level > $menus[$i]->level ){
						if($params->get('stype_layout') == 'vertical'){
							echo "<div  class='fm-container direction-".$menu_direction." '><ul>";
						}else{
							echo "<div  class='fm-container direction-".$menu_direction."'><ul>";	
						}
						$countUlOpened++;
						$level++;
					}
				} else {
					if($i < count($menus)-1  && $menus[$i+1]->level > $menus[$i]->level ){
						echo "<div class='fm-container'><ul>";
						$countUlOpened++;
						$level++;
					}
				}
				if($i < count($menus)-1 && $menus[$i+1]->level < $menus[$i]->level ){
					echo "</li>";
					echo"</ul></div></li>";
					$countUlOpened--;
					$level--;
					for($j = 1; $j < $menus[$i]->level - $menus[$i+1]->level; $j++){
						echo "</ul></div></li>";
						$countUlOpened--;
						$level--;
					}
					
				}
				
				if( $i < count($menus)-1 && $menus[$i+1]->level == $menus[$i]->level){
					echo "</li>";
				}
	}
	for($i=0; $i< $countUlOpened - 1; $i++){
			echo "</li></ul></div>";
	}
	?>
    </li>
</ul>
	<?php 
}
?>

<?php if( $params->get('posttext') != '' ) { ?>
    <div class="posttext"><?php echo $params->get('posttext'); ?></div>
<?php } ?>
