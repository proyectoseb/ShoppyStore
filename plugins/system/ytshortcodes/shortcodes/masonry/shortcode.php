<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function masonryYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'id_masonry'        => '',
            'gutter'    => null,
            'rtl'       => 'no'
        ), $atts, 'yt_masonry');
        global $intense_masonry_gutter;
		$intense_masonry_gutter = $atts['gutter'];
		if ( empty( $intense_masonry_gutter ) ) $intense_masonry_gutter = '0';
		if ( empty( $id_masonry ) ) $id_masonry = rand();
       // intense_add_script( 'packery' );
        JHtml::_('jquery.framework');
		JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/masonry/js/masonry.js");
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/masonry/css/masonry.css",'text/css');
		$init_script = '<script>
			function intenseInitMasonry() {
				var $ = jQuery;
					var $container = $(".intense.masonry");
					$container.packery({
					  itemSelector: ".item",
					  gutter: 0,
					  isOriginLeft: ' . ( ($atts['rtl'] =="no") ? 'false' : 'true' ) . '
					});
				return $container;
			}
			jQuery(function($){ $(window).load(function() {
				setTimeout(intenseInitMasonry, 250);
				intenseInitMasonry();
            }); });</script>';
		return $init_script . '<div id="' . $id_masonry . '" class="intense masonry' . ( !empty( $class ) ? ' ' . $class : '' ) . '" style="position: relative;">' . parse_shortcode(str_replace(array("<br/>","<br />"), " ", $content)) . '</div><div class="clearfix"></div>';
    }
?>