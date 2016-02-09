<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function gallery_itemYTShortcode($atts, $content = null){
	global $gwidth, $gheight, $gcolumns,$galleryArray,$cation_gallery,$border_gallery,$padding_item,$id_uniq,$hover_gallery ;
	$galleryArray[] = array(
			'src'     =>(isset($atts['src'])?$atts['src']:''),
			'tag'     =>(isset($atts['tag']) && $atts['tag'] !='')?$atts['tag']:'',
			'content' =>$content
		);
	extract(ytshortcode_atts(array(
		"title"      => '',
		"src"        => '',
		"video_addr" => '',
		"tag"        =>''
	), $atts));
	if(strpos($video_addr, 'youtube.com'))
	{
		$src_pop = $video_addr;
		if($src=="" || !is_file($src)) 
		{
			$src = 'plugins/system/ytshortcodes/assets/images/youtube.png';
		}
	}
	elseif(strpos($video_addr, 'vimeo.com'))
	{
		$src_pop = $video_addr;
		if($src=="" || !is_file($src)) 
		{
			$src = 'plugins/system/ytshortcodes/assets/images/vimeo.jpg';
		}
	}
	else
	{
		$src_pop = "";
		if($src=="" || !is_file($src)) 
		$src = 'plugins/system/ytshortcodes/assets/images/URL_IMAGES.png';
	}
	$small_image = array(
		'width' => $gwidth,
		'height' => $gheight,
		'function' => 'resize',
		'function_mode' => 'fill'
	);
	
	if(strpos($src,'http://')!== false){
		$simage = $src;
	}else if( is_file($src) && strpos($src,'http://')!== true){
		$simage = JURI::base().ImageHelper::init($src, $small_image)->src();
	}else
	{
		$simage =JURI::base(true).'/'.$src;
	}
	$gallery_item='';
		
	$gallery_item .= "<li class='".$id_uniq." masonry-brick ".strtolower(str_replace(","," ",$tag)).$id_uniq."' ";
	if($gcolumns>0){

		$gallery_item .=" style=' ".$border_gallery."'";
	}
	$gallery_item .=">";
	$gallery_item .="<div class='item-gallery' style='".$padding_item."'>";
	$gallery_item .= "<a title='" . $title . "' href='" . $simage . "' data-rel='prettyPhoto[bkpGallery]'>";
	$gallery_item .= "<div class='item-gallery-hover".$hover_gallery."'></div>";

	$gallery_item .= "<h3 class='item-gallery-title ".$cation_gallery." '>". parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) ."</h3><div class='image-overlay'></div>";
	$gallery_item .= "<img src='" .$simage."' title='" . $title . "' alt='" . $title . "' />";
	$gallery_item .= "</a>";
	$gallery_item .= "</div><h4 class='item-gallery-title ".$cation_gallery." '>" . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . "</h4>";
	$gallery_item .= "</li>";
	
	return str_replace("<br/>", " ", $gallery_item);
}
?>