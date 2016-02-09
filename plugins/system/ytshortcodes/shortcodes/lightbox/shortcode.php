<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function lightboxYTShortcode($atts){
	global $index_lightbox;
	JHtml::_('jquery.framework');
	$script_prettyphoto  = JHtml::script("plugins/system/ytshortcodes/assets/js/jquery.prettyPhoto.js");

	extract(ytshortcode_atts(array(
		"type"        => '',
		"src"         => '#',
		"video_addr"  =>'',
		"width"       => '100%',
		"height"      => '100%',
		"title"       => '',
		'align'       => 'none',
		'lightbox'    => 'yes',
		'style'       => '',
		'description' =>'',
		'margin'      =>''
	), $atts));
	$small_image = array(
		'width' => $width,
		'height' => $height,
		'function' => 'resize',
		'function_mode' => 'fill'
	);
	if(strpos($src,'http://')!== false){
		$src_thumb = $src;
	}else if( is_file($src) && strpos($src,'http://')!== true){

		$src_thumb = JURI::base().ImageHelper::init($src, $small_image)->src();
		$src = JURI::base().$src;

	}
	if(strpos($video_addr, 'youtube.com')){
		$src_pop = $video_addr;

		if($src=="" || !is_file($src)) $src2 = 'plugins/system/ytshortcodes/assets/images/youtube.png';
	}elseif(strpos($video_addr, 'vimeo.com')){
		$src_pop = $video_addr;
		if($src=="" || !is_file($src)) $src2 = 'plugins/system/ytshortcodes/assets/images/vimeo.jpg';
	}else{
		$src_pop = "";
		if($src=="" || !is_file($src)) $src2 = 'plugins/system/ytshortcodes/assets/images/URL_IMAGES.png';
	}
	if($src_pop==""){$src_pop = (strpos($src, "http://") === false) ? JURI::base(true).'/'.$src2 : $src;}
	if($width > 0 && $width!='auto' && $height > 0 && $height!='auto'){
		$simage = JURI::base().ImageHelper::init($src, $small_image)->src();
	}else{
		$simage = JURI::base().$src;
	}
	if(strpos($src,'http://')!== false){
		$simage = $src;
	}else if( is_file($src) && strpos($src,'http://')!== true){
		$simage = JURI::base().ImageHelper::init($src, $small_image)->src();
		$src = JURI::base().$src;
	}else if($src=="")
	{
		$simage =JURI::base(true).'/'.$src2;
	}
	$frame ='';
	$tag_id = 'inline_demo'. rand(). time();
	if($type=="inline")
		{
            $frame .='<a href="#inline_demo" data-rel="prettyPhoto">'.$title.'</a>';
			$frame .='<div id="inline_demo" style="display:none;">';
			$frame .='<p>'.$description.'</p>';
			$frame .='</div>';
		}
		else
		{
			$frame = "<img src='". $simage . "' alt='" . $title . "' />";
            $titles = ($title != '') ? "<h3 class='img-title'>". $title ."</h3>" : '';
            $borderinner  = ($style == "borderInner" || $style == "borderinner") ? "<div class='transparent-border'> </div>" : " " ;
            $image = "<span class='lightbox-hover'></span>";
            if(strtolower($lightbox) == 'yes') {
                $frame = "<a href='". $src_pop . "' data-rel='prettyPhoto' title='" . $title . "' >" .$image . $frame . $titles. $borderinner. "</a>";
		}
	}

	$frame = "<div id='yt-lightbox".$index_lightbox."' class='yt-clearfix yt-lightbox curved  image-". $align." ".$style."' style='width:".$width."; height:".$height."; margin:".$margin."'>" . $frame . "</div>".$script_prettyphoto;
	$index_lightbox ++;

	return $frame;
}
?>