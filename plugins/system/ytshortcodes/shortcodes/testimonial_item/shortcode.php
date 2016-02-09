<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function testimonial_itemYTShortcode($atts, $content = null){
	global $id_testimonial,$border_testimonial,$column_testimonial;
	extract(ytshortcode_atts(array(
		"author" => '',
		"position" => '',
		"avatar" => ''
	), $atts));
    $css='';
	$testimonial_item ='';
	$testimonial_avatar = '';
	$css_add ='';
	$style ='';
	if($avatar != '') {
        if(strpos($avatar,'http://')!== false){
            $avatar = $avatar;
        }else if( is_file($avatar) && strpos($avatar,'http://')!== true){
            $avatar = JURI::base().$avatar;
        }
        $testimonial_avatar .='<img src="' . $avatar . '" alt="'.$atts['author'].'" width="150" height="150" style="border-radius:50%; width:auto; margin:0 auto; max-width:150px; max-height:150px"/> ';
    };
	
	if($column_testimonial == 1)
	{
		$style = 'width:70%; margin-left:15%;';
		if($avatar != '')
		{
			$css_add ='#'.$id_testimonial.' .owl-controls .owl-nav .owl-prev{top:40%; left:40%}#'.$id_testimonial.' .owl-controls .owl-nav .owl-next{top:40%; right:40%}';
			$css_add .='@media all and (max-width: 479px){#'.$id_testimonial.' .owl-controls .owl-nav .owl-prev{top:33%; left:17%}#'.$id_testimonial.' .owl-controls .owl-nav .owl-next{top:33%; right:17%} }@media all and (min-width: 480px) and (max-width: 1199px){#'.$id_testimonial.' .owl-controls .owl-nav .owl-prev{top:38%; left:30%}#'.$id_testimonial.' .owl-controls .owl-nav .owl-next{top:38%; right:30%}}';
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration($css_add);
		}
	}
	elseif($column_testimonial == 2)
	{
		$css = 'float: left;width:50% ; margin:0; text-align:left;';
	}
	$testimonial_item .= '<div class="all_testimonial" style=" border:'.$border_testimonial.'; padding:15px;text-align:center;color:#000; background:#fff; overflow:auto; margin-left:1px;'.$style.' "> ';
	if($avatar != '')
	{
		$testimonial_item .= '<div class="image_testimo" style="'.$css.'">';
		$testimonial_item .= $testimonial_avatar;
		$testimonial_item .= '</div>';
	}
	$testimonial_item .= '<div class="content_testimon" style="'.($avatar != '' ? "margin-top:15px;" : "").' text-align:center;'.$css.'">'.parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)).'<br>';
	$testimonial_item .= '<span style="font-weight:bold; ">'.$author.'</span>  <small style="color:#ccc">'.$position.'</small>';
	$testimonial_item .= '</div></div>';
	
	return $testimonial_item;
}
?>
