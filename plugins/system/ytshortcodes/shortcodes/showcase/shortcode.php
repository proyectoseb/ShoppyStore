<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function ShowcaseYTShortcode($atts = null, $content = null) {
        $atts = ytshortcode_atts(array(
            'source'            => '',
            'limit'             => 12,
            'loading_animation' => 'default',
            'filter_animation'  => 'rotateSides',
            'caption_style'     => 'overlayBottomPush',
            'horizontal_gap'    => 10,
            'vertical_gap'      => 10,
            'filter'            => 'yes',
            'filter_deeplink'   => 'no',
            'page_deeplink'     => 'no',
            'popup_position'    => 'below',
            'popup_category'    => 'yes',
            'popup_date'        => 'yes',
            'popup_image'       => 'yes',
            'large'             => 4,
            'medium'            => 3,
            'small'             => 1,
            'thumb_width'       => 640,
            'thumb_height'      => 480,
            'class'             => ''
                ), $atts, 'showcase');

        $slides = (array) get_slides($atts);
        $id = uniqid('ytsc').rand().time();
        $intro_text='';
        $title = '';
        $return = '';
        $atts['filter_deeplink'] = ($atts['filter_deeplink'] === 'yes') ? 'true' : 'false';
        $atts['page_deeplink'] = ($atts['page_deeplink'] === 'yes') ? 'true' : 'false';

        if ( count($slides) ) {
            $return .= '<div id="' . $id . '" class="yt-showcase" data-scid="' . $id . '" data-loading_animation="'.$atts['loading_animation'].'" data-filter_animation="'.$atts['filter_animation'].'" data-caption_style="'.$atts['caption_style'].'" data-horizontal_gap="'.intval($atts['horizontal_gap']).'" data-vertical_gap="'.intval($atts['vertical_gap']).'" data-popup_position="'.$atts['popup_position'].'" data-large="'.$atts['large'].'" data-medium="'.$atts['medium'].'" data-small="'.$atts['small'].'" data-filter_deeplink="'.$atts['filter_deeplink'].'" data-page_deeplink="'.$atts['page_deeplink'].'" >';
                    if ($atts['filter'] !== 'no') {
                        $return .= '<div id="' . $id . '_filter" class="cbp-l-filters-dropdown">
                            <div class="cbp-l-filters-dropdownWrap">
                                <div class="cbp-l-filters-dropdownHeader">Sort Showcase</div>
                                <div class="cbp-l-filters-dropdownList">
                                    <div data-filter="*" class="cbp-filter-item-active cbp-filter-item">
                                        All Items (<div class="cbp-filter-counter"></div> items)
                                    </div>';
                                    $category = array();
                                    foreach ((array) $slides as $slide) {
                                        if (in_array($slide['category'], $category) ) {
                                            continue;
                                        }
                                        $category[] = $slide['category'];
                                        $return .= '<div class="cbp-filter-item" data-filter=".' . str_replace(' ', '-', strtolower($slide['category'])).'">'.$slide['category'].' (<div class="cbp-filter-counter"></div> items)</div>';
                                    } 
                                $return .='</div>
                            </div>
                        </div>';
                    }

            $return .= '<div id="' . $id . '_container" class="cbp-l-grid-gallery">';

            $limit = 1;
            foreach ((array) $slides as $slide) {

                $thumb_url = yt_image_resize($slide['image'], $atts['thumb_width'], $atts['thumb_height'], 95);   

                // Title condition 
                if($slide['title'])
                    $title = stripslashes($slide['title']);                

                $category = str_replace(' ', '-', strtolower($slide['category']));
                $itemid = uniqid().rand().time();
                $return .= '
                    <div class="cbp-item '.$category.' motion">
                         <a href="'.$slide['link'].'" class="cbp-caption cbp-singlePageInline"
                           data-title="'.$title.' // '.$slide['category'].'">
                            <div class="cbp-caption-defaultWrap">';
				if($slide['image']){
                     $return .= '<img src="'. yt_image_media($thumb_url['url']) .'" alt="'. $title .'">';
                }else {
                     $return .= '<img src="'. yt_image_media(JURI::base().'plugins/system/ytshortcodes/assets/images/URL_IMAGES.png').'" alt="'. $title .'" width="640" height="480" />';
                }
                $return .= '</div>
                            <div class="cbp-caption-activeWrap">
                                <div class="cbp-l-caption-alignLeft">
                                    <div class="cbp-l-caption-body">
                                        <div class="cbp-l-caption-title">'. $title .'</div>
                                        <div class="cbp-l-caption-desc">'.$slide['category'].'</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>';
                if ($limit++ == $atts['limit']) break;
            }
            $return .= '</div><div class="clearfix"></div>';

            $return .= '<div id="'.$id.'_inlinecontents" style="display: none;">';

            foreach ((array) $slides as $slide) {
                $date = JHTML::_('date', $slide['created'], JText::_('DATE_FORMAT_LC3'));
                $textImg = yt_all_images($slide['fulltext']);
                $return .= '
                    <div>
                        <div class="cbp-l-inline">
                            <div class="cbp-l-inline-left">';

                            if ($atts['popup_image'] === 'yes' and ($textImg != null)) {
                                $return .='
                                    <div class="cbp-slider">
                                        <ul class="cbp-slider-wrap">
                                            <li class="cbp-slider-item"><img src="'.yt_image_media($slide['image']).'" alt="'.$slide['title'].'"></li>';
                                            foreach ($textImg as $img) {
                                                $return .= '<li class="cbp-slider-item"><img src="'.yt_image_media($img).'" alt="'.$slide['title'].'"></li>';
                                            }
                                    $return .='</ul>
                                </div>';
                            } elseif ($atts['popup_image'] === 'yes') {
                                $return .='<img src="'.yt_image_media($slide['image']).'" alt="'.$slide['title'].'">';
                            }

                            $return .= '</div>
                            <div class="cbp-l-inline-right">
                                <div class="cbp-l-inline-title">'. $slide['title'] .'</div>
                                <div class="cbp-l-inline-subtitle">'.$slide['category'].' // '.$date.'</div>

                                <div class="cbp-l-inline-desc">'.parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $slide['introtext'])).'</div>

                                <a href="'.$slide['link'].'" class="cbp-l-inline-view">View Details</a>
                            </div>
                        </div>
                    </div>';
            }

            $return .='</div></div>';
            JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/showcase/css/cubeportfolio.min.css",'text/css');
            JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/showcase/css/showcase.css",'text/css');
            JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/magnific-popup.css",'text/css');
            JHtml::_('jquery.framework');
			JHtml::script("plugins/system/ytshortcodes/assets/js/magnific-popup.js");
            JHtml::script("plugins/system/ytshortcodes/shortcodes/showcase/js/cubeportfolio.min.js");
            JHtml::script("plugins/system/ytshortcodes/shortcodes/showcase/js/showcase.js");
        }
        else
            $return = yt_alert_box('Error: Something wrong there, please check your showcase source.', 'warning');
        return $return;
    }
?>