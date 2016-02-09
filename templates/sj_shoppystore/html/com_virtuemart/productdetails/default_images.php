<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 8657 2015-01-19 19:16:02Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Showing The Additional Images
if (!empty($this->product->images) and count ($this->product->images)>1) {   ?>
    <div id="thumb-slider" class="thumb-vertical-outer">
       <div id="thumb-slider-next"><i class="fa fa-angle-up"></i></div>
        <ul class="thumb-vertical">
                <?php
                // List all Images
                if (count($this->product->images) > 0) {
                    foreach ($this->product->images as $key=>$image) {
                    $imageslarge = YTTemplateUtils::resize($image->file_url, '600', '600', 'fill');
                    $imagesradditional = YTTemplateUtils::resize($image->file_url, '450', '450', 'fill');
                    ?>
                    <li class="owl2-item">
                        <a href="#" class="img thumbnail" data-image="<?php echo $imagesradditional;?>" data-zoom-image="<?php echo $imageslarge;?>"  >
                            <img src="<?php echo $imagesradditional;?>" alt="" />
                        </a>
                    </li>
                <?php }
                }
                ?>
        </ul>
        <div id="thumb-slider-prev"><i class="fa fa-angle-down"></i></div>
    </div>

<?php }

// Product Main Image
if (!empty($this->product->images[0])) {
    $imagesrcmain = YTTemplateUtils::resize($this->product->images[0]->file_url, '600', '600', 'fill');
?>
    <div class="main-images">
        <div class="large-image">
            <img id="zoom_img_large" itemprop="image" class="product-image-zoom" data-zoom-image="<?php echo $imagesrcmain;?>" src="<?php echo $imagesrcmain;?>" title="" alt="" />
        </div>
        <span id="zimgex"><i class="fa fa-search-plus"></i></span>
    </div>

    <div class="main-images-quickview">
            <img src="<?php echo $imagesrcmain;?>" title="" alt="" />
    </div>

<?php } ?>

<?php
$document = JFactory::getDocument();
$app = JFactory::getApplication();
$templateDir = JURI::base() . 'templates/' . $app->getTemplate();
?>
<script type="text/javascript" src="<?php echo $templateDir.'/js/jquery.elevateZoom-3.0.8.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo $templateDir.'/js/lightslider/lightslider.js' ?>"></script>
<link rel="stylesheet" href="<?php echo $templateDir.'/js/lightslider/lightslider.css'?>" type="text/css">

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var zoomCollection = '.large-image img';
        $(zoomCollection).elevateZoom({
            gallery:'thumb-slider',
            galleryActiveClass: "active",
            zoomType    : "inner",
            cursor: "crosshair",
            easing:true
        });
        $("#zimgex").bind("click", function(e) {
            var ez = $('#zoom_img_large').data('elevateZoom');
            $._fancybox(ez.getGalleryList());
            return false;
        });

        var _isMobile = {
          iOS: function() {

           return navigator.userAgent.match(/iPhone/i);
          },
          any: function() {
           return (_isMobile.iOS());
          }
        };


        var thumbslider = $(".thumb-vertical-outer .thumb-vertical").lightSlider({
            item: 4,
            autoWidth: false,
            vertical:true,
            slideMargin: 0,
            verticalHeight:440,
            pager: false,
            controls: false,
            //rtl: true,
            prevHtml: '<i class="fa fa-angle-up"></i>',
            nextHtml: '<i class="fa fa-angle-down"></i>',
            responsive: [
                {
                    breakpoint: 1199,
                    settings: {
                        verticalHeight: 300,
                        item: 3,
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        verticalHeight: 400,
                        item: 4,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        verticalHeight: 360,
                        item: 3,
                    }
                },
                {
                    breakpoint: 567,
                    settings: {
                        verticalHeight: 210,
                        item: 2,
                    }
                },
                {
                    breakpoint: 360,
                    settings: {
                        verticalHeight: 100,
                        item: 1,
                    }
                }

            ]

        });
        $('#thumb-slider-prev').click(function(){
            thumbslider.goToPrevSlide();
        });
        $('#thumb-slider-next').click(function(){
            thumbslider.goToNextSlide();
        });
    });
</script>
