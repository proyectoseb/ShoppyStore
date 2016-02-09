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
$class = $item->anchor_css ? 'class="'.$item->anchor_css.'" ' : '';
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

<?php if( $item->params->get('xmp_showlink', '1') == "1" ){
switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $class.$title.$anchor_htm5; ?> href="<?php echo $flink; ?>"><?php echo $linktype; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class.$title.$anchor_htm5; ?> href="<?php echo $flink; ?>" target="_blank"><?php echo $linktype; ?></a><?php
		break;
	case 2:
		// window.open
		$options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$params->get('window_open');
			?><a <?php echo $class.$title.$anchor_htm5; ?> href="<?php echo $flink; ?>" onclick="window.open(this.href,'targetWindow','<?php echo $options;?>');return false;"><?php echo $linktype; ?></a><?php
		break;
endswitch;
}?>


