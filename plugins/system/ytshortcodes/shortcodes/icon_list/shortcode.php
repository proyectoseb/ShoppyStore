<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function icon_listYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'title'           => 'Icon List Heading',
            'title_color'     => '',
            'title_size'      => '',
            'color'           => '',
            'icon_background' => 'transparent',
            'icon'            => 'icon: heart',
            'icon_color'      => '',
            'icon_size'       => 32,
            'icon_animation'  => '',
            'icon_border'     => '',
            'icon_shadow'     => '',
            'icon_radius'     => '',
            'icon_align'      => 'left',
            'icon_padding'    => '',
            'connector'       => 'no',
            'class'           => ''
        ), $atts, 'icon_list_item');

        $id = uniqid('ytil').rand().time();
        $css = '';
        $has_connector='';
        $icon_animation='';
        $shadow = '';
        $icon = '';
        $lang = JFactory::getLanguage();
        
        if ($lang->isRTL()) {
            if ($atts['icon_align'] === 'left') {
                $atts['icon_align'] =  'right'; 
            } elseif ($atts['icon_align'] === 'right') {
                $atts['icon_align'] =  'left';
            }
        }

        $has_connector = ($atts['connector'] == 'yes') ? ' has-connector ':'';
        $icon_animation = ($atts['icon_animation']) ? 'yt-il-animation-'.$atts['icon_animation'].'' : '';
        $title_color = ($atts['title_color']) ? 'color:' . $atts['title_color'] . ';' : '';
        $title_size = ($atts['title_size']) ? 'font-size: '.$atts['title_size'].';' : ''; 
        $radius = ($atts['icon_radius']) ? '-webkit-border-radius:' . $atts['icon_radius'] . '; border-radius:' . $atts['icon_radius'] . ';' : '';
        $shadow = ($atts['icon_shadow']) ? '-webkit-box-shadow:' . $atts['icon_shadow'] . '; box-shadow:' . $atts['icon_shadow'] . ';' : '';
        $padding = ($atts['icon_padding']) ? 'padding:' . $atts['icon_padding'] . ';' : ''; 
        $border = ($atts['icon_border']) ? 'border:' . $atts['icon_border'] . ';' : '';
        $icon_color = ($atts['icon_color']) ? 'color:' . $atts['icon_color'] . ';' : '';
        $icon_size = ($atts['icon_size']) ? 'font-size: '.intval($atts['icon_size']).'px;' : ''; 


        $classes = array('yt-icon-list', 'yt-icon-align-'. $atts['icon_align'], $has_connector, $icon_animation);

        if (strpos($atts['icon'], 'icon:') !== false) {
            $icon = '<i class="list-img-icon fa fa-' . trim(str_replace('icon:', '', $atts['icon'])) . '"></i>';
            if ($icon_color or $icon_size) {
                $css .= '#'.$id.' .icon_list_icon .list-img-icon {'.$icon_color.$icon_size.'}';
            }

        } else {
            $icon = '<img class="list-img-icon"src="'.yt_image_media($atts['icon']).'" style="width:'.$atts['icon_size'].'px" alt="" />';
        }

        if ($atts['icon_align'] == 'right') {
            if (($atts['icon_background']=='transparent' || $atts['icon_background']=='') and ($atts['icon_border']=='' || substr($atts['icon_border'],0,1) =='0')) {
                $description_margin = 'margin-right: '.(($atts['icon_size'])+30).'px;'; 
            }
            else {
                $description_margin = 'margin-right: '.(($atts['icon_size']*2)+35+($atts['icon_border']*2)).'px;';
            }

        } elseif ($atts['icon_align'] == 'top') {
           $description_margin="";
        } elseif ($atts['icon_align'] == 'title') {
           $description_margin="";
           $padding = 'padding: 0;';
        } elseif ($atts['icon_align'] == 'top_left') {
           $description_margin="";
           if (($atts['icon_background']=='transparent' || $atts['icon_background']=='') and ($atts['icon_border']=='' || substr($atts['icon_border'],0,1) =='0')) {
               $padding = 'padding: 0;';
           }
        } elseif ($atts['icon_align'] == 'top_right') {
           $description_margin="";
           if (($atts['icon_background']=='transparent' || $atts['icon_background']=='') and ($atts['icon_border']=='' || substr($atts['icon_border'],0,1) =='0')) {
               $padding = 'padding: 0;';
           }
        } else { 
            if (($atts['icon_background']=='transparent' || $atts['icon_background']=='') and ($atts['icon_border']=='' || substr($atts['icon_border'],0,1) =='0')) {
                $description_margin = 'margin-left: '.(($atts['icon_size'])+20).'px;';
                $padding = 'padding: 0;';
            }
            else {
                $description_margin = 'margin-left: '.(($atts['icon_size']*2)+30+($atts['icon_border']*2)).'px;'; 
            }
        }

        if ($atts['icon_animation'] == 6) {
            $css .= '#'.$id.'.su-il-animation-6 .icon_list_icon i:before { margin-right: -' . round($atts['icon_size'] / 2) .'px}';
        }

        $icon_list_connector = ($atts['connector']=="yes" and ($atts['icon_align'] == 'right' or $atts['icon_align'] == 'left')) ? 'icon_list_connector' : '' ;

        $css .= '#'.$id.' .icon_list_icon { background:' . $atts['icon_background'] . '; font-size:' . $atts['icon_size'] . 'px; max-width:' . $atts['icon_size'] . 'px; height:' . $atts['icon_size'] . 'px;' .$border.$padding.$radius.$shadow.'}';
        $css .= '#'.$id.' .icon_description { '.$description_margin.'}';
        if ($title_color or $title_size) {
            $css .= '#'.$id.' .icon_description h3 {' .$title_color.$title_size.'}';
        }
        if ($atts['color']) {
            $css .= '#'.$id.' .icon_description_text { color:' . $atts['color'] . ';}';
        }

        // Add CSS in head
        $doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/icon_list/css/icon-list.css",'text/css');

        $return = '
            <div id="'.$id.'" class="'.yt_acssc($classes).' ">
                <div class="icon_list_item">
                    <div class="icon_list_wrapper '. $icon_list_connector .'">
                        <div class="icon_list_icon">'
                            . $icon . '
                        </div>
                    </div>

                    <div class="icon_description">
                        <h3>'.$atts['title'].'</h3>
                        <div class="icon_description_text">'
                         . parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) .
                        '</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>';

        return $return;
    }
?>