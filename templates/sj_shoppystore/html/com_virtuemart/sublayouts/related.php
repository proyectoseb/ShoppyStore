<?php defined('_JEXEC') or die('Restricted access');

$related = $viewData['related'];
$customfield = $viewData['customfield'];
$thumb = $viewData['thumb'];

    echo '<div class="related-image item-quick-view">';
    echo $thumb;
    echo '</div><div class="related-info ">';
    $maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
    if (empty($related->rating)) {
        ?>
<div class="ratingbox dummy" title="<?php echo vmText::_('COM_VIRTUEMART_UNRATED'); ?>" ></div>
<?php
    } else {
        $ratingwidth = $related->rating * 13;
        ?>

<div title=" <?php echo (vmText::_("COM_VIRTUEMART_RATING_TITLE") . round($related->rating) . '/' . $maxrating) ?>" class="ratingbox" >
<div class="stars-orange" style="width:<?php echo $ratingwidth.'px'; ?>"></div>
</div>

<?php
    }

//juri::root() For whatever reason, we used this here, maybe it was for the mails
echo JHtml::link (JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $related->virtuemart_product_id . '&virtuemart_category_id=' . $related->virtuemart_category_id), $related->product_name, array('title' => $related->product_name,'target'=>'_blank'));
if($customfield->wPrice){
    $currency = calculationHelper::getInstance()->_currencyDisplay;
    echo '<div class="prices-wrap">';
    echo $currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $related->prices);
    echo $currency->createPriceDiv ('discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $related->prices);
    //$_item['product'] = $related;
    //echo '<div class="item-addtocart">';
      //  echo shopFunctionsF::renderVmSubLayout('addtocart', $_item);
    echo '</div>';
}
    echo '</div><div class="clearfix"></div>';
//if($customfield->wDescr){
//    echo '<p class="product_s_desc">'.$related->product_s_desc.'</p>';
//}






