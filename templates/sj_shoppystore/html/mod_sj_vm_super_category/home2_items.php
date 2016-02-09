<?php
/**
 * @package Sj Super Categories for JoomShopping
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
if (!empty($child_items)) {
    $app = JFactory::getApplication();
    $k = $app->input->getInt('ajax_limit_start', 0);
    $i = 0;
    $count = count($child_items);
    if ($params->get('type_show') == 'slider') {
        echo '<div class="ltabs-items-inner owl2-carousel ltabs-slider ">';
    }
    foreach ($child_items as $item) {
        $i++;$k++; ?>
        <?php if($params->get('type_show') == 'slider' && ($i % $params->get('nb_rows') == 1 || $params->get('nb_rows') == 1)) { ?>
            <div class="ltabs-item ">
        <?php }?>
          <?php if ($params->get('type_show') == 'loadmore'){ ?>
          <div class="spcat-item new-spcat-item">
            <?php } ?>

            <div class="item-inner">
               <?php
                            $product_available_date = substr($item->product_available_date,0,10);
                            $current_date = date("Y-m-d");
                            if ($product_available_date != '0000-00-00' and $current_date < $product_available_date) { ?>
                                <span class="sale">Sale</span>
                            <?php } ?>
                            <?php if($item->product_special == 1) { ?>
                                <span class="new">New</span>
                            <?php } ?>

                <?php
                $item_img = SjVMSuperCategoriesHelper::getVmImage($item, $params, 'imgcfg');
                if ($item_img) {
                    ?>
                    <div class="item-image item-quick-view">
                        <a href="<?php echo $item->link ?>"
                           title="<?php echo $item->title; ?>" <?php echo SjVMSuperCategoriesHelper::parseTarget($params->get('link_target')); ?> >
                            <?php echo SjVMSuperCategoriesHelper::imageTag($item_img, $small_image_config); ?>
                            <span class="image-border"></span>
                        </a>
                    </div>
                <?php } ?>

                <?php if ($params->get('item_title_display', 1) == 1) { ?>
                    <div class="item-title">
                        <a href="<?php echo $item->link; ?>"
                           title="<?php echo $item->title ?>" <?php echo SjVMSuperCategoriesHelper::parseTarget($params->get('link_target')); ?> >
                            <?php echo SjVMSuperCategoriesHelper::truncate($item->title, (int)$params->get('item_title_max_characters', 25)); ?>
                        </a>
                    </div>
                <?php } ?>
                <?php require JModuleHelper::getLayoutPath($module->module, $layout . '_rating'); ?>
                <?php if ((int)$params->get('item_description_display', 1) && SjVMSuperCategoriesHelper::_trimEncode($item->_description) != '') { ?>
                    <div class="item-desc">
                        <?php echo $item->_description;?>
                    </div>
                <?php } ?>

                <div class="item-price">
                    <?php
                    if (!empty($item->prices['salesPrice'])) {
                        echo $currency->createPriceDiv('salesPrice', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                    }
                    if (!empty($item->prices['discountAmount'])) {
                        echo $currency->createPriceDiv('discountAmount', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                     ?>
                    <?php } ?>
                </div>
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
                           title="<?php echo $item->title ?>" <?php echo SjVMSuperCategoriesHelper::parseTarget($params->get('link_target')); ?> >
                                <?php echo $params->get('item_readmore_text','Read More'); ?>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if ($params->get('item_addtocart_display', 1)) {
                    $_item['product'] = $item; ?>
                    <div class="item-addtocart">
                        <?php echo shopFunctionsF::renderVmSubLayout('addtocart', $_item); ?>
                    </div>
                <?php } ?>
                </div>
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
    <?php if ($params->get('type_show') == 'slider') { ?>
                </div>
            <?php } ?>
<?php
}else{ echo 'Has no content to show';}?>



