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

// Note. It is important to remove spaces between elements.
if( $item->params->get('xmp_showlink') == "1" ){
	$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
	
$show_image = '';
$show_descr = $item->params->get('xmp_desc', '');
$show_descr = trim($show_descr);
$show_title = $item->title;

if ( $item->menu_image ) {
	$show_image = '<img class="menu-image" src="'.$item->menu_image.'" alt="'.$item->title.'" />';
	
	// add text (title)
	if( !$item->params->get('menu_text', 1) ){
		$show_title = '';
	}
}

$linktype = $show_image;
if ($show_title || $show_descr){
	$linktype .= '<span class="menu-title">';
	if ($show_title){
		$linktype .= '<span>'.$show_title.'</span>';
	}
	if ($show_descr){
		$linktype .= '<em>'.$show_descr.'</em>';
	}
	$linktype .= '</span>';
}

$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

$anchor_htm5 = '';
$anchor_htm5 .= $show_image ? ' data-image="on"' : ' data-image="off"';
$anchor_htm5 .= $show_title ? ' data-title="on"' : ' data-title="off"';
$anchor_htm5 .= $show_descr ? ' data-description="on"' : ' data-description="off"';?>
	
	<a class="separator" href="#" <?php echo $anchor_htm5.$title;?>><?php echo $linktype; ?></a>
<?php }?>

