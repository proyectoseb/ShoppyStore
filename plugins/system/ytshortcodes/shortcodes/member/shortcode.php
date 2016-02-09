<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function memberYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'style'       => '1',
            'background'   => '#ffffff',
            'color'        => '#333333',
            'shadow'       => '',
            'border'       => '1px solid #cccccc',
            'radius'       => '0',
            'text_align'   => 'left',
            'photo'        => '',
            'name'         => 'John Doe',
            'role'         => 'Designer',
            'icon_1'       => '',
            'icon_1_url'   => '',
            'icon_1_color' => '#444444',
            'icon_1_title' => '',
            'icon_2'       => '',
            'icon_2_url'   => '',
            'icon_2_color' => '#444444',
            'icon_2_title' => '',
            'icon_3'       => '',
            'icon_3_url'   => '',
            'icon_3_color' => '#444444',
            'icon_3_title' => '',
            'icon_4'       => '',
            'icon_4_url'   => '',
            'icon_4_color' => '#444444',
            'icon_4_title' => '',
            'icon_5'       => '',
            'icon_5_url'   => '',
            'icon_5_color' => '#444444',
            'icon_5_title' => '',
            'icon_6'       => '',
            'icon_6_url'   => '',
            'icon_6_color' => '#444444',
            'icon_6_title' => '',
            'url'          => '',
            'class'        => ''
        ), $atts, 'yt_member');
        $icons = array();
        $id = uniqid('ytm').rand().time();
        $css = '';

        $box_shadow = ($atts['shadow']) ? 'box-shadow:' . $atts['shadow'] . '; -webkit-box-shadow:' . $atts['shadow'] . ';' : '';
        $radius = ($atts['radius']) ? 'border-radius:' . $atts['radius'] . ';' : '';

        $css .= '#'.$id.'.yt-member {background-color:' . $atts['background'] . '; color:' . $atts['color'] . '; border:' . $atts['border'] .';'. $radius . $box_shadow .'}';
        for ($i = 1; $i <= 6; $i++) {
            if (!$atts['icon_' . $i] || !$atts['icon_' . $i . '_url'])
                continue;
            if (strpos($atts['icon_' . $i], 'icon:') !== false) { $icon = '<i class="fa fa-' . trim(str_replace('icon:', '', $atts['icon_' . $i])) . '" style="color:' . $atts['icon_' . $i . '_color'] . '"></i>';}
            else { $icon = '<img src="' . yt_image_media($atts['icon_' . $i]) . '" width="16" height="16" alt="" />'; }
            $icons[] = '<a href="' . $atts['icon_' . $i . '_url'] . '" title="' . $atts['icon_' . $i . '_title'] . '" class="yt-memeber-icon yt-m-' . trim(str_replace('icon:', '', $atts['icon_' . $i])) . '" target="_blank" style="text-align:center"> ' . $icon . ' </a>';
        }

        $icons = (count($icons)) ? '<div class="yt-member-icons" style="text-align:' . $atts['text_align'] . ';"><div class="yt-member-ic">' . implode('', $icons) . '</div></div>' : '';
        $multi_photo = array();
        $multi_photo = explode(',',$atts['photo'], 2);

        $member_photo ='';
        $member_photo = '<a href="'.$atts['url'].'" title="'.$atts['name'].'"><img src="' . yt_image_media($multi_photo[0]) . '" alt="" /></a>';

       if(isset($multi_photo[1]) )
        $member_photo .= '<a href="'.$atts['url'].'" title="'.$atts['name'].'"><img src="' . yt_image_media($multi_photo[1]) . '" alt=""  /></a>';

        $title = '<span class="yt-member-name">' . $atts['name'] . '</span><span class="yt-member-role">' . $atts['role'] . '</span>';



        // Adding asse
        $doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/member/css/membar.css",'text/css');
        // HTML Layout 
        $return = '<div id="'.$id.'" class="yt-member yt-member-style-'. $atts['style'] .'" data-url="' . $atts['url'] . '">';
            $return .= '<div class="yt-member-photo">'. $member_photo;
                if ($atts['style'] == '2' or $atts['style'] == '4') { $return .= $icons; }
            $return .= '</div>';

            $return .= '<div class="yt-member-info" style="text-align:' . $atts['text_align'] . '">';
                $return .= $title;
                $return .= '<div class="yt-member-desc yt-content-wrap">' . parse_shortcode(str_replace(array("<br/>","<br />","<p></p>"), " ", $content)) . '</div>';
            $return .= '</div>';

            if ($atts['style'] != '2' and $atts['style'] != '4') { $return .= $icons; }

        $return .= '</div>';
        return $return;
    }
?>