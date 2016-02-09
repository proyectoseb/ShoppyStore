<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function qrcodeYTShortcode($atts,$content = null)
{
	$atts = ytshortcode_atts(array(
                'data'       => '',
                'title'      => '',
                'size'       => 200,
                'margin'     => 0,
                'align'      => 'none',
                'link'       => '',
                'target'     => '_blank',
                'color'      => '#000000',
                'background' => '#ffffff',
                'class'      => ''
            ), $atts, 'qrcode' );

        if ( !$atts['data'] ) return 'QR code: please specify the data';
		
        $qrImg =  '<img src="https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode( $atts['data'] ) . '&amp;size=' . $atts['size'] . 'x' . $atts['size'] . '&amp;format=png&amp;margin=' . $atts['margin'] . '&amp;color=' . yt_hexToRgb($atts['color'], true, '-') . '&amp;bgcolor=' . yt_hexToRgb($atts['background'], true, '-') . '" alt="' . $atts['title'] . '" />';

        if ($atts['link']) {
            if ( $atts['link'] ) $atts['class'] .= ' yt-qrcode-clickable';
            $return = '<a href="' . $atts['link'] . '" target="' . $atts['target'] . '" title="' . $atts['title'] . '">'.$qrImg.'</a>';
        }
        else {
            $return = $qrImg;
        }

		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/qrcode/css/qr-code.css",'text/css',"screen");
        return '<span class="yt-qrcode yt-qrcode-align-' . $atts['align'] . '">'.$return.'</span>';
}
?>