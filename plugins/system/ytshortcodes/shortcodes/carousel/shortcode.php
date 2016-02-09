<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function carouselYTShortcode($atts, $content = null){
$return = '';
    $atts = ytshortcode_atts(array(
        'style'            => '1',
        'source'           => '',
        'limit'            => 5,
        'items'            => 4,
        'image'            => 'yes',
        'quality'          => 95,
        'title'            => 'yes',
        'title_link'       => 'yes',
        'title_limit'      => '',
        'intro_text'       => 'yes',
        'intro_text_limit' => '60',
        'background'       => '',
        'color'            => '',
        'title_color'      => '',
        'date'             => 'no',
        'category'         => 'no',
        'image_width'      => 360,
        'image_height'     => 320,
        'margin'           => 10,
        'scroll'           => 1,
        'arrows'           => 'no',
        'arrow_position'   => 'default',
        'pagination'       => 'yes',
        'autoplay'         => 'no',
        'delay'            => 4,
        'speed'            => 0.35,
        'hoverpause'       => 'no',
        'lazyload'         => 'no',
        'loop'             => 'yes',
        'class'            => ''
    ), $atts, 'yt_carousel');

    $id = uniqid('ytc').rand().time();
    $title = "";
    $image = "";
    $intro_text='';
    $css = '';
    $background = '';
    $color = '';

    $lang = JFactory::getLanguage();
    $lang = ($lang->isRTL()) ? 'true' : 'false';

    $slides = (array)get_slides($atts);

    if (($atts['background']) or ($atts['color'])) {

        $background = ($atts['background']) ? 'background-color:'.$atts['background'].';' : '';
        $color = ($atts['color']) ? 'color:'.$atts['color'].';' : '';

        $css .= '.'.$id.' .yt-carousel-slide {' . $background . $color .'}';

        if ($atts['style'] == 3) {
            $css .= '.'.$id.'.yt-carousel-style-3 .yt-carousel-caption:after {border-bottom-color: '.$atts['background'].';}';
        }
    }

    if ($atts['title_color']) {
        $css .= '.'.$id.'.yt-carousel-slide .yt-carousel-slide-title a {color: '.$atts['title_color'].';}';
        $css .= '.'.$id.'.yt-carousel-slide .yt-carousel-slide-title a:hover {color: '.yt_lighten($atts['title_color'],'10%').';}';
    }

    if (count($slides) and ($atts['title'] == 'yes' or $atts['image']  == 'yes' or  $atts['intro_text'] === 'yes')) {
        $source = substr($atts['source'], 0, 5);
        if ($source == 'media'){
            $atts['class'] .= ' yt-carousel-media';
        }
        $return .= '<div id="' . $id . '" class="yt-clearfix ' . $id . ' '.$atts['class'].' yt-carousel yt-carousel-style-'.$atts['style'].' yt-carousel-title-' . $atts['title'] .' arrow-'. $atts['arrow_position'].'" data-autoplay="' . $atts['autoplay'] .'" data-delay="' . $atts['delay'] . '" data-speed="' . $atts['speed'] . '" data-arrows="' . $atts['arrows'] .'" data-pagination="' . $atts['pagination'] . '" data-lazyload="' . $atts['lazyload'] . '" data-hoverpause="' . $atts['hoverpause'] . '" data-items="' . $atts['items'] . '" data-margin="' . $atts['margin'] . '" data-scroll="' . $atts['scroll'] . '" data-loop="' . $atts['loop'] . '" data-rtl="' . $lang . '"  ><div class="'.$id.' yt-carousel-slides">';
        $limit = 1;

        foreach ((array) $slides as $slide) {
			$image_url ='';
			if($slide['image'])
			{
            	$image_url = yt_image_resize($slide['image'], $atts['image_width'], $atts['image_height'], $atts['quality']);
			}
			else
			{
				$image_url =yt_image_resize('plugins/system/ytshortcodes/assets/images/URL_IMAGES.png',$atts['image_width'], $atts['image_height'], $atts['quality']);
			}

            if($atts['title'] == 'yes' && $slide['title'] ) {

                $title = stripslashes($slide['title']);

                if ($atts['title_limit']) {
                    $title = yt_char_limit($title, $atts['title_limit']);
                }

                if ($atts['title_link'] == "yes") {
                    $title = '<a href="'.$slide['link'].'">'.$title.'</a>';
                }
                $title = '<h3 class="yt-carousel-slide-title">' . $title . '</h3>';
            }

            if ($atts['intro_text'] === 'yes' and isset($slide['introtext'])) {

                $intro_text = $slide['introtext'];

                if ($atts['intro_text_limit']) {
                    $intro_text = yt_char_limit($intro_text, $atts['intro_text_limit']);
                }

                $intro_text =  '<div class="yt-carousel-item-text">'.parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $intro_text)).'</div>';
            }
            $return .= '<div class="'.$id.' yt-carousel-slide">';

                if (isset($image_url) && $atts['image']  == 'yes') {
                    $return .= '<div class="yt-carousel-image">';

                        if (isset($image_url)) {
                            $return .= '<div class="yt-carousel-links">
                                <a class="yt-lightbox-item" href="'. yt_image_media($slide['image']) .'" title="'. strip_tags($title).'">
                                    <i class="fa fa-search"></i>
                                </a>';

                                if ($source != 'media') {
                                    $return .= '<a class="yt-carousel-link" href="'.$slide['link'].'" title="'. strip_tags($title).'">
                                        <i class="fa fa-link"></i>
                                    </a>';
                                }
                            $return .= '</div>';
                        }

                        $return .= '<img src="' . yt_image_media($image_url['url']) . '" alt="' . strip_tags($title) . '" />';
                    $return .= '</div>';
                }

                if (($title) or ($intro_text)) {
                    $return .= '<div class="yt-carousel-caption">'.$title . $intro_text.'</div>';
                }


            $return .= '</div>';

            if ($limit++ == $atts['limit']) break;
        }

        $return .= '</div>';
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/magnific-popup.css",'text/css',"screen");
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/owl.carousel.css",'text/css',"screen");
        JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/carousel/css/carousel.css");
		JHtml::_('jquery.framework');
        JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/magnific-popup.js");
        JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/owl.carousel.min.js");
        $return .= '</div>';
        $doc = JFactory::getDocument();
		$doc->addStyleDeclaration($css);
        $doc->addScriptDeclaration('
		    jQuery(document).ready(function ($) {
				// Enable carousels
					jQuery(\'.'.$id.'.yt-carousel\').each(function () {
						// Prepare data
						var $carousel = $(this),
							$slides = $(\'.'.$id.'.yt-carousel-slides\'),
							$slide = $(\'.'.$id.'.yt-carousel-slide\'),
							data = $carousel.data();
						// Apply Swiper
						var $owlCarousel = $slides.owlCarousel({
							responsiveClass: true,
							mouseDrag: true,
							autoplayTimeout: data.delay * 1000,
							smartSpeed: data.speed * 1000,
							lazyLoad: (data.lazyload == \'yes\') ? true : false,
							autoplay: (data.autoplay == \'yes\') ? true : false,
							autoplayHoverPause: (data.hoverpause == \'yes\') ? true : false,
							center: (data.center == \'yes\') ? true : false,
							loop: (data.loop == \'yes\') ? true : false,
							margin: data.margin,
							navText: [\'\',\'\'],
							rtl: data.rtl,
							responsive:{
						        0:{
						            items:1,
						            margin: 0,
						            dots: (data.pagination == \'yes\') ? true : false,
				            		nav: (data.arrows == \'yes\') ? true : false
						        },
						        768:{
						            items:$carousel.data(\'items\'),
						            dots: (data.pagination == \'yes\') ? true : false,
				            		nav: (data.arrows == \'yes\') ? true : false
						        },
						        1000:{
						            items: $carousel.data(\'items\'),
						            dots: (data.pagination == \'yes\') ? true : false,
				            		nav: (data.arrows == \'yes\') ? true : false
						        }
						    }
						});

						// Lightbox for galleries (slider, carousel, custom_gallery)
						$(this).find(\'.yt-lightbox-item\').magnificPopup({
							type: \'image\',
							mainClass: \'mfp-zoom-in mfp-img-mobile\',
							tLoading: \'\', // remove text from preloader
							removalDelay: 400, //delay removal by X to allow out-animation
							gallery: {
								enabled: true,
								navigateByImgClick: true,
								preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
							},
							callbacks: {
								open: function() {
									//overwrite default prev + next function. Add timeout for css3 crossfade animation
									$.magnificPopup.instance.next = function() {
										var self = this;
										self.wrap.removeClass(\'mfp-image-loaded\');
										setTimeout(function() { $.magnificPopup.proto.next.call(self); }, 120);
									}
									$.magnificPopup.instance.prev = function() {
										var self = this;
										self.wrap.removeClass(\'mfp-image-loaded\');
										setTimeout(function() { $.magnificPopup.proto.prev.call(self); }, 120);
									}
								},
								imageLoadComplete: function() {
									var self = this;
									setTimeout(function() { self.wrap.addClass(\'mfp-image-loaded\'); }, 16);
								}
							}
						});

					});
				});
		');
    }

    else
        $return = yt_alert_box('Carousel content not found, please check carousel source settings.', 'warning');
    return $return;
}
?>