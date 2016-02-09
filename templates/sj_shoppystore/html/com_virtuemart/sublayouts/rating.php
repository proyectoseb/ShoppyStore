<?php defined('_JEXEC') or die('Restricted access');

$product = $viewData['product'];

if ($viewData['showRating']) {
    $maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
    if (empty($product->rating)) {
    ?><div class="inforating">
        <div class="ratingbox" title="<?php echo vmText::_('COM_VIRTUEMART_UNRATED'); ?>" ></div>
        <span class="info-text">
            <?php
                echo "0&nbsp;";
                echo (vmText::_("COM_VIRTUEMART_REVIEWS"));
                echo "&nbsp;|&nbsp;";
            ?>
            <a href="#addreview" title="<?php echo (vmText::_("COM_VIRTUEMART_REVIEW_SUBMIT")); ?>"><?php echo (vmText::_("COM_VIRTUEMART_REVIEW_SUBMIT")); ?></a></span>
        </div>
    <?php
    } else {
        $ratingwidth = $product->rating * 12;
  ?>
<div class="inforating">
<div title=" <?php echo (vmText::_("COM_VIRTUEMART_RATING_TITLE") . round($product->rating) . '/' . $maxrating) ?>" class="ratingbox" >
  <div class="stars-orange" style="width:<?php echo $ratingwidth.'px'; ?>"></div>
</div><span class="info-text">
    <?php
        echo intval($product->rating);
        echo "&nbsp;";
        echo (vmText::_("COM_VIRTUEMART_REVIEWS"));
        echo "&nbsp;|&nbsp;";
    ?>
    <a href="#add-reviews" title="<?php echo (vmText::_("COM_VIRTUEMART_REVIEW_SUBMIT")); ?>"><?php echo (vmText::_("COM_VIRTUEMART_REVIEW_SUBMIT")); ?></a></span>
</div>
    <?php
    }
}
