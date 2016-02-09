<?php
/**
 * @package Sj Vm Listing Tabs
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

$currency = CurrencyDisplay::getInstance();
$small_image_config = array(
    'type' => $params->get('imgcfg_type'),
    'width' => $params->get('imgcfg_width'),
    'height' => $params->get('imgcfg_height'),
    'quality' => 90,
    'function' => ($params->get('imgcfg_function') == 'none') ? null : 'resize',
    'function_mode' => ($params->get('imgcfg_function') == 'none') ? null : substr($params->get('imgcfg_function'), 7),
    'transparency' => $params->get('imgcfg_transparency', 1) ? true : false,
    'background' => $params->get('imgcfg_background'));
$class_suffix = $params->get('moduleclass_sfx');
?>
<?php if ($params->get('type_show') == 'slider') { ?>
<div class="ltabs-items-inner owl2-carousel ltabs-slider">
<?php } ?>
<?php if (!empty($child_items)) {
    $i = 0;
    $app = JFactory::getApplication();
    $k = $app->input->getInt('ajax_reslisting_start', 0);
    $count = count($child_items);
    foreach ($child_items as $item) {
        $i++; $k++; ?>
    <?php if($params->get('type_show') == 'slider' && ($i % $params->get('nb_rows') == 1 || $params->get('nb_rows') == 1)) { ?>
    <div class="ltabs-item ">
        <?php }
        if ($params->get('type_show') == 'loadmore'){ ?>
        <div class="ltabs-item new-ltabs-item">
            <?php } ?>
            <div class="item-inner">
                <?php
                $item_img = VMListingTabsHelper::getVmImage($item, $params, 'imgcfg');
                if ($item_img) {
                    ?>
                    <div class="item-image">
                        <div class="item-quick-view">
                        <a href="<?php echo $item->link ?>"
                           title="<?php echo $item->title; ?>" <?php echo VMListingTabsHelper::parseTarget($params->get('link_target')); ?> >
                            <?php echo VMListingTabsHelper::imageTag($item_img, $small_image_config); ?>
                            <span class="image-border"></span>
                        </a>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($params->get('item_title_display', 1) == 1) { ?>
                    <div class="item-title">
                        <a href="<?php echo $item->link; ?>"
                           title="<?php echo $item->title ?>" <?php echo VMListingTabsHelper::parseTarget($params->get('link_target')); ?> >
                            <?php echo VMListingTabsHelper::truncate($item->title, (int)$params->get('item_title_max_characters', 25)); ?>
                        </a>
                    </div>
                <?php } ?>

                <?php require JModuleHelper::getLayoutPath($module->module, $layout . '_rating'); ?>

                <?php if ((int)$params->get('item_prices_display', 1) && ( !empty($item->prices['salesPrice']) || !empty($item->prices['discountAmount'])) ) { ?>
                    <div class="item-prices">
                        <?php
                        if (!empty($item->prices['salesPrice'])) {
                            echo $currency->createPriceDiv('salesPrice', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                        }
                        if (!empty($item->prices['discountAmount'])) {
                            echo $currency->createPriceDiv('discountAmount', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                         ?>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if ((int)$params->get('item_description_display', 1) && VMListingTabsHelper::_trimEncode($item->_description) != '') { ?>
                    <div class="item-desc">
                        <?php echo $item->_description;?>
                    </div>
                <?php } ?>

                <?php if ($params->get('item_readmore_display') == 1) { ?>
                <div class="other-infor">
                    <?php if ($params->get('item_created_display', 1) == 1) { ?>
                        <div class="created-date ">
                            <?php
                            echo JHTML::_('date', $item->created_on, JText::_('DATE_FORMAT_LC3'));
                            ?>
                        </div>
                    <?php } ?>

                    <?php if ($params->get('item_readmore_display') == 1) { ?>
                        <div class="item-readmore">
                            <a href="<?php echo $item->link; ?>"
                               title="<?php echo $item->title ?>" <?php echo VMListingTabsHelper::parseTarget($params->get('item_link_target')); ?>>
                                <?php echo $params->get('item_readmore_text','Read More'); ?>
                            </a>
                        </div>
                    <?php } ?>

                </div>
                 <?php } ?>

                <?php if ($params->get('item_addtocart_display', 1)) {
                    $_item['product'] = $item; ?>
                    <div class="item-addtocart">
                        <?php echo shopFunctionsF::renderVmSubLayout('addtocart', $_item); ?>
                    </div>
                <?php } ?>
            </div>
        <?php if($params->get('type_show') == 'slider' && ($i % $params->get('nb_rows') == 0 || $i == $count)) { ?>
        </div>
    <?php }
    if ($params->get('type_show') == 'loadmore'){ ?>
    </div>
    <?php } ?>
        <?php
        if($params->get('type_show') == 'loadmore'){
        $clear = 'clr1';
        if ($k % 2 == 0) $clear .= ' clr2';
        if ($k % 3 == 0) $clear .= ' clr3';
        if ($k % 4 == 0) $clear .= ' clr4';
        if ($k % 5 == 0) $clear .= ' clr5';
        if ($k % 6 == 0) $clear .= ' clr6';
        ?>
        <div class="<?php echo $clear; ?>"></div>
        <?php } ?>
    <?php
    } ?>
<?php
}?>
<?php if ($params->get('type_show') == 'slider') { ?>
</div>
<?php } ?>
<?php
if ($params->get('type_show') == 'slider') { ?>
    <script type="text/javascript">

        jQuery(document).ready(function($){
            var $tag_id = $('#sj_listing_tabs_<?php echo $module->id; ?>'),
                parent_active =     $('.items-category-<?php echo $tab_id; ?>', $tag_id),
                total_product = parent_active.data('total'),
                tab_active = $('.ltabs-items-inner',parent_active),
                nb_column1 = <?php echo $params->get('nb-column1'); ?>,
                nb_column2 = <?php echo $params->get('nb-column2'); ?>,
                nb_column3 = <?php echo $params->get('nb-column3'); ?>,
                nb_column4 = <?php echo $params->get('nb-column4'); ?>;
            tab_active.owlCarousel2({
                dots: false,
                margin: 0,
                loop:  <?php echo $params->get('display_loop') ; ?>,
                autoplay: <?php echo $params->get('autoplay'); ?>,
                autoplayHoverPause: <?php echo $params->get('pausehover') ; ?>,
                autoplayTimeout: <?php echo $params->get('autoplay_timeout') ; ?>,
                autoplaySpeed: <?php echo $params->get('autoplay_speed') ; ?>,
                mouseDrag: <?php echo  $params->get('mousedrag'); ?>,
                touchDrag: <?php echo $params->get('touchdrag'); ?>,
                navRewind: true,
                navText: [ '', '' ],
                responsive: {
                    0: {
                        items: nb_column4,

                    },
                    480: {
                        items: nb_column3,

                    },
                    768: {
                        items: nb_column2,

                    },
                    1200: {
                        items: nb_column1,

                    },
                }
            });
         $('.btn-next',parent_active).click(function() {
            tab_active.trigger('next.owl');
        });
        $('.btn-prev',parent_active).click(function() {
            tab_active.trigger('prev.owl');
        });

        });
    </script>
<?php } ?>
