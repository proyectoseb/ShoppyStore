<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function promotion_boxYTShortcode($atts,$content= null)
{
	extract(ytshortcode_atts(array(
		'type'					  => '',
		'title'                   => 'This is Call To Action Title',
		'title_color'             => '#ffffff',
		'button_text'             => 'Click Here',
		'align'                   => 'default',
		'button_link'             => '#',
		'target'                  => '_self',
		'promotion_color'         => '#ddd',
		'promotion_background'    => '#2D89EF',
		'promotion_radius'        => '0px',
		'border'				  => '1',
		'border_color'			  => '',
		'button_color'            => '#fff',
		'button_background'       => '#165194',
		'button_background_hover' => '',
		'arrow_height'			  => '52px',	
		'width'                   => '',
		'button_radius'           => '0px',
		'desc'                    => '',
		'class'                   => ''
	),$atts));
	$id = uniqid('yt_').rand().time();
		$css ='';
        $padding = '';

        $title  = ($title) ? "<h3>" . $title . "</h3>" : '';
        //$target = ( $target === 'yes' || $target === 'blank' ) ? ' target="_blank"' : '';
        $bdt_hbg = ($button_background_hover) ? $button_background_hover :'';
        $width = ($type=="arrow-box" ? "100%" : $width);

        if (intval($promotion_radius) > 40 && intval($button_radius) > 40) {
            $padding = "padding: 20px 20px 20px 40px;";
        }
        if($type=="border")
        {
        	$background = 'border:' . $promotion_background.' '.$border.'px solid; background:#fff;';					$css .= '#'.$id.' .border{'.$background.'}';
        }else if($type =="background-border")
        {
        	$background = 'border:' . $border_color.' '.$border.'px solid; background:'.$promotion_background.';';
        	$css .= '#'.$id.' .background-border{ '. $background.'}';
        }
        else
        {
        	$background = 'background-color:' . $promotion_background;
        	$css .= '#'.$id.' { ' . $background.'}';
        }
        $css .= '#'.$id.' {'.$padding.'; border-radius:' . $promotion_radius. '; margin-bottom:15px; '.$background.' }';
        $css .= '#'.$id.' a.cta-dbtn { border-radius:' . $button_radius . '; color:' . $button_color . '; background:' . $button_background . ';}';
        $css .= '#'.$id.' a.cta-dbtn:hover { background:' . $bdt_hbg . ';}';
        $css .= '#'.$id.' .cta-content > h3 { color: '.$title_color.';}';
        $css .= '#'.$id.' .cta-content div { color:' . $promotion_color.';}';
        $css .= '#'.$id.' .cta-content h3 { color:' . $promotion_color.';}';
        if($type=="arrow-box")
        {
        	$css .= '@media only screen and (min-width: 980px){';
        	$css .='#'.$id.'.arrow-box {position: relative;padding-right:'.$arrow_height.';}';
        	$css .='#'.$id.'.arrow-box:before{content: "";width: 0px;height: 0px;position: absolute;border-style: solid;border-color:transparent transparent transparent #FFF  ;border-width: '.$arrow_height.';	top:0px;left: 0;z-index: 1;}';
			$css .='#'.$id.'.arrow-box:after{content: "";	width: 0px;	height: 0px;position: absolute;	border-style: solid;border-color: #FFF #FFF #FFF transparent ;border-width: '.$arrow_height.' ;	top:0px;right: 0;z-index: 1;}';
			$css .= '#'.$id.'.arrow-box .cta-content {margin:0 '.$arrow_height.'}';
			$css .= '#'.$id.'.arrow-box a.cta-dbtn {margin-right:'.$arrow_height.'}';
			$css .='}';
        }
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/promotion_box/css/promotion.css",'text/css',"screen");
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
        $return  = '<div style="width:'.$width.';"><section id="'.$id.'" class="promotion cta-align-'. $align .' '.$type.'">';
        $return .= "<a class='cta-dbtn hidden-phone' target='" . $target . "' href='" . $button_link . "'>" . $button_text . "</a>";
        $return .= "<div class='cta-content'>" . $title ."<div>". parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) . '</div></div>';
        $return .= "<a class='cta-dbtn visible-phone' target='" . $target . "' href='" . $button_link . "'>" . $button_text . "</a>";
        $return .= '<div class="clear"></div></section></div>';
        return $return;
}

?>