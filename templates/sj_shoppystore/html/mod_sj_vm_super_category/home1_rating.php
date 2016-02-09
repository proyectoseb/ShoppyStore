<?php defined('_JEXEC') or die('Restricted access');

$product = $item->rating;
$maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
if (empty($product->rating)) {
?><div class="inforating">
    <div class="ratingbox" title="<?php echo vmText::_('COM_VIRTUEMART_UNRATED'); ?>" ></div>
    </div>
<?php
} else {
    $ratingwidth = $product->rating * 12;
?>
<div class="inforating">
<div title=" <?php echo (vmText::_("COM_VIRTUEMART_RATING_TITLE") . round($product->rating) . '/' . $maxrating) ?>" class="ratingbox" >
  <div class="stars-orange" style="width:<?php echo $ratingwidth.'px'; ?>"></div>
</div></div>
<?php
}
