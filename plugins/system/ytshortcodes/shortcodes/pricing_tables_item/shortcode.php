<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function pricing_tables_itemYTShortcode($atts, $content = null ){
	global $pcolumns, $per,$type;

	extract(ytshortcode_atts(array(
		"title" 		=> '',
		"button_link" 	=> '',
		"button_label" 	=> '',
		"price" 		=> '',
		"featured" 		=> '',
		"text"			=>'Popular',
		"background"	=>'#4e9e41',
		"color"			=>'#fff',
		"icon_name"		=>''
	), $atts));
	$return='';
	if($icon_name != '')
	{
		if (strpos($icon_name, 'icon:') !== false) 
		{ 
		  $icon_name = '<i class="fa fa-' . trim(str_replace('icon:', '', $icon_name)) . '"></i>';
		}
        else { 
          $icon_name = '<img src="' . yt_image_media($icon_name) . '" width="16" height="16" alt="" />'; 
       	}
	}
	switch ($type) {
		case 'style1':
			$text= (strtolower($featured)=="yes" ? $text : '');
			$return = '<div class="col-xs-12 col-sm-6 col-md-'.round(12/$pcolumns).' col-lg-'.round(12/$pcolumns).'">
				<div class="'.$type.' column '.(('yes' == strtolower($featured)) ? ' featured' : '') .'">
				<span class="pricing-featured">'.$text.'</span>
				<div class="pricing-basic " style=""><h2>' . $title . '</h2></div>' .
				'<div class="pricing-money block " style="" ><h5>' . $price . '</h5></div>' .
				parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) .
				'<div class="pricing-bottom">
		<div class="signup"><a href="'.$button_link.'">'.$button_label.'<div class="iconsignup">'.$icon_name.'</div></a></div>

	</div> ' .
			 '</div></div>';
			break;

		default:
		case 'style2':
			//$text= (strtolower($featured)=="true" ? $text : '');
			$return = '<div class="col-xs-12 col-sm-6 col-md-'.round(12/$pcolumns).' col-lg-'.round(12/$pcolumns).'">
				<div class="'.$type.' column '.(('true' == strtolower($featured)) ? ' featured' : '') .'">
				<div class="pricing-basic " style="background:'.$background.'; color:'.$color.'"><h2>' . $title . '</h2><span class="pricing-featured">'.$text.'</span></div>' .
				parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) .
				'<div class="pricing-money block ">' . $price . '</div>' .
				'<div class="pricing-bottom" >
		<a class="signup" style="background:'.$background.';color:'.$color.'" href="'.$button_link.'">'.$button_label.'</a>
			</div> ' .
			 '</div></div>';
			break;
		case 'style3':
			$text= (strtolower($featured)=="true" ? $text : '');
			$return = '<div class="col-xs-12 col-sm-6 col-md-'.round(12/$pcolumns).' col-lg-'.round(12/$pcolumns).'">
				<div class="'.$type.' column '.(('true' == strtolower($featured)) ? ' featured' : '') .'">
				<div class="pricing-basic " style="background:#e74847; color:'.$color.'"><h2>' . $title . '</h2><span class="pricing-featured">'.$text.'</span></div>' .
				'<div class="pricing-money block "><h1>' . $price . '</h1></div>'.
				parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content))  .
				'<div class="pricing-bottom" >
		<a class="signup" style="background:#e74847;color:'.$color.'" href="'.$button_link.'">'.$button_label.'</a>
			</div> ' .
			 '</div></div>';

	}
	
	return $return;
}
?>