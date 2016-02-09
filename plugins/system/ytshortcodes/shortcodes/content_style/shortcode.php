<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function Content_styleYTShortcode($atts = null, $content = null) {
       $atts = ytshortcode_atts(array(
				'type_change'                     =>'single_column',
				'source'                          => '',
				'limit'                           => 20,
				'image'                           => 'yes',
				'title'                           => 'yes',
				'link_title'                      => 'yes',
				'intro_text'                      => 'yes',
				'intro_text_limit'                => '',
				'date'                            => 'yes',
				'time'                            => 'yes',
				'read_more'                       => 'no',
				'order'                           => 'created',
				'order_by'                        => 'desc',
				'highlight_year'                  => 'yes',
				'icon_bg'                         => '',
				'before_text'                     => '',
				'after_text'                      => '',
				'class'                           => '',
				'gutter'                          => '',
				'sticky_mode'                     => '',
				'masonry_sticky_size'             =>'',
				'masonry_size'                    =>'',
				'masonry_sticky_medium_size'      =>'',
				'masonry_medium_size'             =>'',
				'masonry_sticky_small_size'       =>'',
				'masonry_small_size'              =>'',
				'masonry_sticky_extra_small_size' =>'',
				'masonry_extra_small_size'        =>'',
				'masonry_sticky_width'            =>'',
				'masonry_width'                   =>'',
				'grid'                            =>'no',
				'offset'                          =>'',
				'column'                          =>'',
				'style_slider'                    =>'',
				'transitionin'                    =>'bounceIn', 
				'transitionout'                   =>'bounceOut', 
				'arrows'                          =>'yes', 
				'arrow_position'                  =>'arrow-default',
				'pagination'                      =>'no', 
				'autoplay'                        =>'yes' ,
				'autoheight'                      =>'yes', 
				'hoverpause'                      =>'yes'
          ), $atts, 'yt_content_style');
       	$blogid = 'cpt_list_' . rand();
        $slides = (array) get_slides($atts);

        $return = '';
		
        switch ($atts['type_change']) {
        	case 'masonry':
        		$masonry = '';
        		$masonry .= ' [yt_masonry id="' . $blogid  . '" gutter="' . $atts['gutter'] . '"] ';
				$limit = 1;

		            foreach ($slides as $slide) {
						if ($atts['intro_text_limit']) {
							$slide['introtext'] = yt_char_limit($slide['introtext'], $atts['intro_text_limit']);
						}
						$title = $slide['title'];
						$masonry .= ' [yt_masonry_item ' .
						'size="' .  $atts['masonry_sticky_size'] . '" ' .
						'medium_size="' . $atts['masonry_sticky_medium_size'] . '" ' .
						'small_size="' . $atts['masonry_sticky_small_size'] . '" ' .
						'extra_small_size="' . $atts['masonry_sticky_extra_small_size'] . '" ' .
						'width="' .  $atts['masonry_sticky_width'] . '"]';
						$masonry .='<div class="yt_content_style_masonry">';
						if ($slide['image'] and $atts['image'] === 'yes') {
							$masonry .=  '<div class=""><img src="'. yt_image_media($slide['image']).'" alt="" /></div>';
						}
						if ($atts['title'] === 'yes' and isset($slide['title'])) {
							$masonry .=  '<h1>';
								if ($atts['link_title'] === 'yes') { $masonry .=  '<a href="'. $slide['link'].'">'; }
									$masonry .= $title;
								if ($atts['link_title'] === 'yes') { $masonry .=  '</a>'; }
							$masonry .= '</h1>';
						}
						if ($atts['intro_text'] === 'yes' and isset($slide['introtext'])) {
							
							$masonry .=  '<div class="yt-masonry-item-text">'.$slide['introtext'].'</div>';
						}
						$masonry .= '<div style="margin:15px;"> <i class="fa fa-calendar"></i> '.JHTML::_('date', $slide['created'], JText::_('DATE_FORMAT_LC3')).' // <i class="fa fa-book"></i> '.$slide['category'].','.$slide['category_alias'].' // <i class="fa fa-user"></i> by '.$slide['author_name'].'. </div>';
						$masonry .='</div>';
						
						$masonry .=' [/yt_masonry_item] ';
						if ($limit++ == $atts['limit']) break;
					}
				$masonry .= ' [/yt_masonry] ';
				$return .= parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $masonry));
        		break;
        	case 'timeline':
        		if ($atts['before_text']) {
		            $return .= '<div class="yt-timeline-before-text"><span>'.$atts['before_text'].'</span></div>';
		        }

		        $date = date('Y');

		        $return .= '<div class="yt-timeline animated">';
		        if (count($slides)) {
		            $limit = 1;
		            foreach ($slides as $slide) {
						if ($atts['intro_text_limit']) {
							$slide['introtext'] = yt_char_limit($slide['introtext'], $atts['intro_text_limit']);
						}
		                $title = $slide['title'];
		                $icon = $title ? explode('|| fa-', $title) : array();
		                if (count($icon) == 2){
		                    $title = trim($icon[0]);
		                    $icon = '<i class="fa fa-'.trim($icon[1]).'"></i>';
		                } else {
		                    $title = $slide['title'];
		                    $icon = '<i class="fa fa-circle"></i>';
		                }
		                $has_icon = '';
		                if (isset($icon[1])) {
		                  $has_icon = 'has-ta-icon';
		                }
		                $icon_bg = ($atts['icon_bg']) ? 'style="background-color:'.$atts['icon_bg'].';"' : '';

		                    if ($date != JHTML::_('date', $slide['created'], "Y") && $atts['highlight_year'] == 'yes') {
		                        $return .= '<div class="yt-timeline-row yt-timeline-has-year">'."\n";
		                        $date = JHTML::_('date', $slide['created'], "Y");
		                        $return .= '<div class="yt-timeline-year"><span>'."\n";
		                        $return .= $date . "\n";
		                        $return .= '</span></div>'."\n";
		                    } else {
		                       $return .= '<div class="yt-timeline-row">'."\n";
		                    }

		                    $return .= '<div class="yt-timeline-icon '.$has_icon.'"><div class="bg-primary" '.$icon_bg.'>'.$icon.'</div></div>';
		                    $return .= '<div class="yt-timeline-time">';

		                    if ($atts['date'] == 'yes') {
		                        $return .= '<small>'.JHTML::_('date', $slide['created'], JText::_('DATE_FORMAT_LC3')).'</small>';
		                    }

		                    if ($atts['time'] == 'yes') {
		                        $return .= JHTML::_('date', $slide['created'], "g:i A");
		                    }

		                    $return .= '</div>';


		                    $return .= '<div class="yt-timeline-content">'."\n";
		                        $return .= '<div class="yt-timeline-content-body">'."\n";
		                            if ($atts['title'] === 'yes' and isset($slide['title'])) {
		                                $return .=  '<h3 class="yt-timeline-item-title">';
		                                    if ($atts['link_title'] === 'yes') { $return .=  '<a href="'. yt_image_media($slide['link']).'">'; }
		                                        $return .= $title;
		                                    if ($atts['link_title'] === 'yes') { $return .=  '</a>'; }
		                                $return .= '</h3>';
		                            }
		                            if ($slide['image'] and $atts['image'] === 'yes') {
		                                    $return .=  '<div class="yt-timeline-item-image"><img src="'. yt_image_media($slide['image']).'" alt="" /></div>';
		                            }
		                            if ($atts['intro_text'] === 'yes' and isset($slide['introtext'])) {
		                                $return .=  '<div class="yt-timeline-item-text">'.parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $slide['introtext'])).'</div>';
		                            }
		                            if ($atts['read_more'] === 'yes') {
		                                $return .=  '<a class="yt-timeline-readmore readon" href="'. yt_image_media($slide['link']).'">Read More...</a>';
		                            }
		                        $return .= '</div>'."\n";
		                    $return .= '</div>'."\n";
		                $return .= '</div>'."\n";
		              if ($limit++ == $atts['limit']) break;
		            }

			        $return .= '</div>';

			        if ($atts['after_text']) {
			            $return .= '<div class="yt-timeline-after-text"><span>'.$atts['after_text'].'</span></div><br/>';
			        }
					JHtml::_('jquery.framework');
			        JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/content_style/js/content_style.js");
		        }
		        else {
		          $return .= yt_alert_box('Timeline not work, Please select the correct source and settings.', 'warning');
		        }
        		break;
			case 'multiple_column':
					$multiple_column='';
					$limit = 1;
					$i=1;
					$multiple_column .= ' [yt_columns grid="'.$atts['grid'].'"] ';
		            foreach ($slides as $slide) {
						if ($atts['intro_text_limit']) {
							$slide['introtext'] = yt_char_limit($slide['introtext'], $atts['intro_text_limit']);
						}
						$title = $slide['title'];
							$multiple_column .= ' [yt_columns_item offset="'.$atts['offset'].'" col="'.floor(12/$atts['column']).'"] ';
							if($slide['image'])
							{
								$multiple_column .= '<img src="'. yt_image_media($slide['image']).'" alt="" style="margin-bottom:15px;"/>';
							}else
							{
								$multiple_column .= '<img src="'. yt_image_media(JURI::base().'plugins/system/ytshortcodes/assets/images/URL_IMAGES.png').'" alt="" />';
							}
							 if ($atts['title'] === 'yes' and isset($slide['title'])) {
								$multiple_column .=  '<h1>';
									if ($atts['link_title'] === 'yes') { $multiple_column .=  '<a href="'. yt_image_media($slide['link']).'">'; }
										$multiple_column .= $title;
									if ($atts['link_title'] === 'yes') { $multiple_column .=  '</a>'; }
								$multiple_column .= '</h1>';
							}
							if ($atts['date'] == 'yes') {
								$multiple_column .= '<i class="fa fa-calendar"></i> '.JHTML::_('date', $slide['created'], JText::_('DATE_FORMAT_LC3')).' // <i class="fa fa-book"></i> '.$slide['category'].','.$slide['category_alias'].' // <i class="fa fa-user"></i> by '.$slide['author_name'].'.';
							}
							$multiple_column .= '<div class="yt_contentstyle_content">'.$slide['introtext'].'</div>';
							$multiple_column .= ' [/yt_columns_item] ';
						if ($i++ == $atts['column']) break;
						if ($limit++ == $atts['limit']) break;
					}
				$multiple_column .= ' [/yt_columns] ';
				$return = parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $multiple_column));
				break;
			case 'slider':
				$slider='';
					$limit = 1;
					$slider .= ' [yt_content_slider style="'.$atts['style_slider'].'" transitionin="'.$atts['transitionin'].'" transitionout="'.$atts['transitionout'].'" arrows="'.$atts['arrows'].'" arrow_position="'.$atts['arrow_position'].'" pagination="'.$atts['pagination'].'" autoplay="'.$atts['autoplay'].'" autoheight="'.$atts['autoheight'].'" hoverpause="'.$atts['hoverpause'].'"] ';
		            foreach ($slides as $slide) {
						$title = $slide['title'];
							$slider .= ' [yt_content_slider_item] ';
							if($slide['image'])
							{
								$slider .= '<img src="'. yt_image_media($slide['image']).'" alt="" />';
							}else
							{
								$slider .= '<img src="'. yt_image_media(JURI::base().'plugins/system/ytshortcodes/assets/images/URL_IMAGES.png').'" alt="" />';
							}
							$slider .= ' [/yt_content_slider_item] ';
						if ($limit++ == $atts['limit']) break;
					}
				$slider .= ' [/yt_content_slider] ';
				$return = parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $slider));
				break;
        	default:
        		$single_column = '';
				$limit = 1;
		            foreach ($slides as $slide) {
						if ($atts['intro_text_limit']) {
							$slide['introtext'] = yt_char_limit($slide['introtext'], $atts['intro_text_limit']);
						}
						$title = $slide['title'];
						$single_column .= ' [yt_columns grid="'.$atts['grid'].'"] ';
						$single_column .= ' [yt_columns_item offset="'.$atts['offset'].'" col="12"] ';
						if($slide['image'])
						{
							$single_column .= '<div class="col-sm-6" style="width:50%; margin:0;"><img src="'. yt_image_media($slide['image']).'" alt="" /></div><div class="col-sm-6" style="width:50%; margin:0;">';
						}else{
							$single_column .= '<div class="col-sm-12">';
						}
							 if ($atts['title'] === 'yes' and isset($slide['title'])) {
								$single_column .=  '<h1 class="yt-timeline-item-title">';
									if ($atts['link_title'] === 'yes') { $single_column .=  '<a href="'. yt_image_media($slide['link']).'">'; }
										$single_column .= $title;
									if ($atts['link_title'] === 'yes') { $single_column .=  '</a>'; }
								$single_column .= '</h1>';
							}
							if ($atts['date'] == 'yes') {
								$single_column .= '<i class="fa fa-calendar"></i> '.JHTML::_('date', $slide['created'], JText::_('DATE_FORMAT_LC3')).' // <i class="fa fa-book"></i> '.$slide['category'].','.$slide['category_alias'].' // <i class="fa fa-user"></i> by '.$slide['author_name'].'.';
							}
							
							$single_column .= '<div class="yt_contentstyle_content">'.$slide['introtext'].'</div>';
							$single_column .= '</div> [/yt_columns_item] ';
						$single_column .= ' [/yt_columns] ';
						if ($limit++ == $atts['limit']) break;
					}

				$return = parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $single_column));
        		break;
        }
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/content_style/css/content_style.css");
      return $return;
    }
?>