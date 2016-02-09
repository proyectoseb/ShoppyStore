<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function portfolioYTShortcode($atts,$content = null)
{
	$atts = ytshortcode_atts(array(
            'style'            => 1,
            'source'           => '',
            'limit'            => 15,
            'color'            => '#cccccc',
            'intro_text_limit' => 50,
            'grid_type'        => 0,
            'animation'        => 'fade',
            'speed'            => 600,
            'rotate'           => 99,
            'delay'            => 20,
            'border'           => 0,
            'padding'          => 10,
            'thumb_width'      => 640,
            'thumb_height'     => 480,
            'class'            => ''
                ), $atts, 'portfolio');
	$return = '';
	$slides = (array)get_slides($atts);
        $intro_text='';
        $title = '';

        if ( count($slides) ) {

            $id = uniqid('ytpor_').rand().time();
            $return .='<script>jQuery(document).ready(function($) {
				$(\'#'.$id.' .megafolio-container\').each(function () {
					data = $(this).data();

					var api=$(this).megafoliopro({
							filterChangeAnimation: data.animation,	// fade, rotate, scale, rotatescale, pagetop, pagebottom,pagemiddle
							filterChangeSpeed: data.speed,			// Speed of Transition
							filterChangeRotate:data.rotate,			// If you ue scalerotate or rotate you can set the rotation (99 = random !!)
							filterChangeScale:0.6,			// Scale Animation Endparameter
							delay: data.delay,
							defaultWidth:980,
							paddingHorizontal:data.padding,
							paddingVertical:data.padding,
							layoutarray:[data.grid_types]		// Defines the Layout Types which can be used in the Gallery. 2-9 or "random". You can define more than one, like {5,2,6,4} where the first items will be orderd in layout 5, the next comming items in layout 2, the next comming items in layout 6 etc... You can use also simple {9} then all item ordered in Layout 9 type.
						});

					// CALL FILTER FUNCTION IF ANY FILTER HAS BEEN CLICKED
					$(\'#'.$id.' .filter\').click(function() {
						$(\'#'.$id.' .filter\').each(function() { $(this).removeClass("selected")});
						api.megafilter($(this).data(\'category\'));
						$(this).addClass("selected");
					});

					// Lightbox for galleries (slider, carousel, custom_gallery)
					$(this).find(\'.yt-lightbox-item\').magnificPopup({
						type: \'image\',
						mainClass: \'mfp-zoom-in mfp-img-mobile\',
						closeBtnInside: false,
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
			</script>';

            $return .= '<div id="' . $id . '" class="yt-portfolio sup-style'.$atts['style'].' '. $atts['class']. '" >
                <div class="filter_padder" >
                    <div class="filter_wrapper">
                        <div class="filter selected" data-category="cat-all">All</div>';

                            $category = array();
                            foreach ((array) $slides as $slide) {
                                if (in_array($slide['category'], $category) ) {
                                    continue;
                                }
                                $category[] = $slide['category'];
                                $return .= '<div class="filter" data-category="' . str_replace(' ', '-', strtolower($slide['category'])).'">'.$slide['category'].'</div>';
                            }

                            $return .= '
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            <div class="megafolio-container"
                data-grid_types="'.$atts['grid_type'].'"
                data-speed="'.$atts['speed'].'"
                data-delay="'.$atts['delay'].'"
                data-rotate="'.$atts['rotate'].'"
                data-padding="'.intval($atts['padding']).'"
                data-animation="'.$atts['animation'].'" >';


            $limit = 1;
            foreach ((array) $slides as $slide) {
                $thumb_url = yt_image_resize($slide['image'], $atts['thumb_width'], $atts['thumb_height'], 95);

                // Title condition
                
                if($slide['title'] )
                    $title = stripslashes($slide['title']);

                if (isset($slide['introtext'])) {
                    $intro_text = $slide['introtext'];
                    if ($atts['intro_text_limit']) {
                        $atts['intro_text'] = yt_char_limit($intro_text, $atts['intro_text_limit']);
                    }
                }
                $category = str_replace(' ', '-', strtolower($slide['category']));

                if ($atts['style'] == 2) {
                    $return .= '
                            <div class="mega-entry cat-all '.$category.'" data-src="'. yt_image_media($thumb_url['url']) .'" data-width="500" data-height="500">
                                <div class="links-container">
                                    <a class="hoverlink project-link" href="'.$slide['link'].'" title="'. strip_tags($title ).'">
                                        <i class=" fa fa-link"></i>
                                        <span></span>
                                    </a>
                                    <a class="hoverlink yt-lightbox-item" href="'. yt_image_media($slide['image']) .'" title="'. strip_tags($title ).'">
                                        <i class=" fa fa-search"></i>
                                        <span></span>
                                    </a>
                                </div>
                                <div class="rollover-content mega-covercaption mega-square-bottom mega-portrait-bottom">

                                    <div class="rollover-content-container">
                                        <h3 class="entry-title">'. $title .'</h3>

                                        <div class="entry-meta">
                                            <div class="yt-portfolio-date">
                                                <span class="yt-pdate">'.JHTML::_('date', $slide['created'], JText::_('DATE_FORMAT_LC3')).'</span>
                                            </div>
                                            <div class="portfolio-categories">
                                                <span class="category">'.$category.'</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                } elseif ($atts['style'] == 3) {
                    $return .= '
                        <div class="mega-entry cat-all '.$category.'" data-src="'. yt_image_media($thumb_url['url']) .'" data-width="500" data-height="500">
                            <div class="mega-hover notitle">
                                <a class="yt-lightbox-item" href="'. yt_image_media($slide['image']) .'" title="'. strip_tags($title ).'">
                                    <div class="mega-hoverview fa fa-search"></div>
                                </a>
                                <a class="hoverlink project-link" href="'.$slide['link'].'" title="'. strip_tags($title ).'">
                                    <i class="mega-hoverlink fa fa-link"></i>
                                </a>
                            </div>

                            <div class="gallerycaption-bottom">
                                '. $title .'
                                <div class="gallerysubline">'.$category.'</div>
                            </div>
                        </div>';
                } elseif ($atts['style'] == 4) {
                    $return .= '
                        <div class="mega-entry portfolio-style4 cat-all '.$category.'" data-src="'. yt_image_media($thumb_url['url']) .'" data-width="500" data-height="500">

                            <div class="portfolio-links">
                                <a class="yt-lightbox-item" href="'. yt_image_media($slide['image']) .'" title="'. strip_tags($title ).'">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a class="portfolio-link" href="'.$slide['link'].'" title="'. strip_tags($title ).'">
                                    <i class="fa fa-link"></i>
                                </a>
                            </div>
                            <div class="portfolio-content">
                                <div class="portfolio-title">'. $title .'</div>
                                <div class="portfolio-desc">'.$atts['intro_text'].'</div>
                            </div>
                        </div>';
                }
                else {
                    $return .= '
                        <div class="mega-entry cat-all '.$category.'" data-src="'. yt_image_media($thumb_url['url']) .'" data-width="500" data-height="500">
                            <div class="mega-hover">
                                <div class="mega-hovertitle">'. $title .'
                                    <div class="mega-hoversubtitle">'.$atts['intro_text'].'</div>
                                </div>
                                <a href="'.$slide['link'].'" title="'. strip_tags($title ).'">
                                    <i class="mega-hoverlink fa fa-link"></i>
                                </a>
                                <a class="yt-lightbox-item" href="'. yt_image_media($slide['image']) .'" title="'. strip_tags($title ).'">
                                    <i class="mega-hoverview fa fa-search"></i>
                                </a>
                            </div>
                        </div>';
                }

                if ($limit++ == $atts['limit']) break;
            }
            $return .= '</div></div>';


            $css = '
              #'.$id.' .mega-hoversubtitle { color: '.$atts['color'].';}
              #'.$id.' .mega-entry .mega-entry-innerwrap { border: '.$atts['border'].';}
            ';



			JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/portfolio/css/portfolio.css",'text/css',"screen");
			JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/assets/css/magnific-popup.css",'text/css',"screen");
			JHtml::_('jquery.framework');
			JHtml::script(JUri::base()."plugins/system/ytshortcodes/assets/js/magnific-popup.js");
			JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/portfolio/js/themepunch_tools.js");
			JHtml::script(JUri::base()."plugins/system/ytshortcodes/shortcodes/portfolio/js/themepunch_megafoliopro.js");
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration($css);
        }
        else
            $return = yt_alert_box('Please select your correct article source.', 'warning');
        return $return;
}
?>