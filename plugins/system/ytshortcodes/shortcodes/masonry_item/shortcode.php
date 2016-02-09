<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function masonry_itemYTShortcode( $atts, $content = null ) {
		global $intense_visions_options;
		extract(ytshortcode_atts(array(
		"width"            =>'',
		"size"             => '',
		"medium_size"      => '',
		"small_size"       => '',
		"extra_small_size" => '',
	), $atts));

		global $intense_masonry_gutter;

		$size_class = '';

		if ( empty( $width ) && empty( $size ) && empty( $medium_size ) && empty( $small_size ) && empty( $extra_small_size ) ) {
			$width = 50 * ( rand(0,1) ? .5 : 1 ); //50% or 25% randomly
		}

		if ( is_numeric( $width ) ) $width .= '%';

		if ( !empty( $size ) || !empty( $medium_size ) || !empty( $small_size ) || !empty( $extra_small_size ) ) {
			$size_class = "col-lg-$size " .
				( isset( $extra_small_size ) ? "col-xs-$extra_small_size ": "col-xs-12 " ) .
				( isset( $small_size ) ? "col-sm-$small_size " : "col-sm-12 " ) .
				( isset( $medium_size ) ? "col-md-$medium_size " : "col-md-$size " ) .
				"nogutter ";
			$width = null;
		}

		return '<div class="item' . $size_class . '" style="float:left; ' . ( !empty( $width ) ? 'width: ' . $width  . ';' : '' ) . '"><div style="margin: 0 ' . $intense_masonry_gutter / 2 . 'px ' . ( $intense_masonry_gutter ). 'px 0; ">' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</div></div>';
	}
?>