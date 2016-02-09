<?php
/**
 * sublayout products
 *
 * @package    VirtueMart
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');
$products_per_row = $viewData['products_per_row'];
$currency = $viewData['currency'];
$showRating = $viewData['showRating'];
$verticalseparator = " vertical-separator";
$verticalseparator2 = " vertical-separator2";
echo shopFunctionsF::renderVmSubLayout('askrecomjs');
$product_model = VmModel::getModel('product');
$ItemidStr = '';
$Itemid = shopFunctionsF::getLastVisitedItemId();
if(!empty($Itemid)){
    $ItemidStr = '&Itemid='.$Itemid;
}

foreach ($viewData['products'] as $type => $products ) {

    $rowsHeight = shopFunctionsF::calculateProductRowsHeights($products,$currency,$products_per_row);

    if(!empty($type) and count($products)>0){
        $productTitle = vmText::_('COM_VIRTUEMART_'.strtoupper($type).'_PRODUCT'); ?>
<div class="<?php echo $type ?>-view">
  <h4><?php echo $productTitle ?></h4>
        <?php // Start the Output
    }

    // Calculating Products Per Row
    $cellwidth = ' width'.floor ( 100 / $products_per_row );

    $BrowseTotalProducts = count($products);

    $col = 1;
    $nb = 1;
    $row = 1; ?>
    <div class="row">
<?php
    foreach ( $products as $product ) {

        // Show the horizontal seperator
        if ($col == 1 && $nb > $products_per_row) { ?>
    <!--<div class="horizontal-separator"></div>-->
        <?php }

        // this is an indicator wether a row needs to be opened or not
        if ($col == 1) { ?>

        <?php }

        // Show the vertical seperator
        if ($nb == $products_per_row or $nb % $products_per_row == 0) {
            $show_vertical_separator = $verticalseparator2;
        } else if ($nb == $products_per_row or $nb % $products_per_row == 1) {
            $show_vertical_separator = $verticalseparator;
        }
        else {
            $show_vertical_separator = '';
        }

    // Show Products ?>
    <div class="product <?php echo $show_vertical_separator ?> col-sm-6 col-md-<?php echo round((12 / $products_per_row));?>">
        <div class="spacer">
<div class="vm-product-media-container item-quick-view product-left">

                    <a class="browseProductImage" title="<?php echo $product->product_name ?>" href="<?php echo $product->link.$ItemidStr; ?>">
                        <?php
                                        //front Images
                                        $product_model->addImages($product);
                                        echo $product->images[0]->displayMediaThumb('class="img-front"', false);
                                        if (count($product->images) >= 2 ) {
                                        //Back Images
                                            $ImgLink = $product->images[1]->file_url_thumb;
                                            echo '<img class="img-back" src="'.JURI::base().$ImgLink.'"  alt="" />';
                                        }
                                    ?>
                    </a>

            </div>
<div class="vm-product-content-container product-right">
                <div class="vm-product-descr-container vm-product-descr-container-<?php echo $rowsHeight[$row]['product_s_desc'] ?>">
                    <h2><?php echo JHtml::link ($product->link.$ItemidStr, $product->product_name); ?></h2>

                    <div class="vm-product-rating-container">
                <?php echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$showRating, 'product'=>$product));
                if ( VmConfig::get ('display_stock', 1)) { ?>
                    <span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
                <?php }
                echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$product));
                ?>
            </div>
                    <?php //echo $rowsHeight[$row]['price'] ?>
            <div class="vm-product-prices-container vm3pr-<?php echo $rowsHeight[$row]['price'] ?>"> <?php
                echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$product,'currency'=>$currency)); ?>
                <div class="clearfix"></div>
            </div>

                    <?php if(!empty($rowsHeight[$row]['product_s_desc'])){
                    ?>
                    <p class="product_s_desc">
                        <?php // Product Short Description
                        if (!empty($product->product_s_desc)) {
                            echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 260, '') ?>
                        <?php } ?>
                    </p>
            <?php  } ?>
               <div class="clearfix"></div>
                </div>
            <?php //echo $rowsHeight[$row]['customs'] ?>
            <div class="vm-product-addcart vm3pr-<?php echo $rowsHeight[$row]['customfields'] ?>"> <?php
                echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product,'rowHeights'=>$rowsHeight[$row])); ?>
            </div>

            <div class="vm-details-button">
                <?php // Product Details Button
                $link = empty($product->link)? $product->canonical:$product->link;
                echo JHtml::link($link.$ItemidStr,vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ), array ('title' => $product->product_name, 'class' => 'product-details' ) );
                //echo JHtml::link ( JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id , FALSE), vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ), array ('title' => $product->product_name, 'class' => 'product-details' ) );
                ?>
            </div>
                                          </div>
        </div>
    </div>

    <?php
    $nb ++;

      // Do we need to close the current row now?
      if ($col == $products_per_row || $nb>$BrowseTotalProducts) { ?>
      <?php
          $col = 1;
        $row++;
    } else {
      $col ++;
    }
  }

      if(!empty($type)and count($products)>0){
        // Do we need a final closing row tag?
        //if ($col != 1) {
      ?>
    <?php
    // }
    }?>
        <div class="clearfix"></div>
  </div>
  <?php }
