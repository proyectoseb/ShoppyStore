
      <!-- Nav tabs -->
  <ul id="addreview" class="nav nav-tabs clearfix" role="tablist">
    <li role="presentation" class="clearfix active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></a></li>
    <li role="presentation"><a href="#add-reviews" aria-controls="add-reviews" role="tab" data-toggle="tab"><?php echo vmText::_ ('COM_VIRTUEMART_REVIEWS') ?></a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Others</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content clearfix">
    <div role="tabpanel" class="tab-pane clearfix active" id="home">
     <?php // Product Description
    if (!empty($product->product_desc)) {
        ?>
        <div class="product-description">
    <?php /** @todo Test if content plugins modify the product description */ ?>
    <?php echo $this->product->product_desc; ?>
        </div>
    <?php
    } // Product Description END
?>

      </div>
    <div role="tabpanel" class="tab-pane clearfix" id="add-reviews">
        <?php // onContentAfterDisplay event
            echo $this->product->event->afterDisplayContent;
            echo $this->loadTemplate('reviews');?>
    </div>
    <div role="tabpanel" class="tab-pane clearfix" id="messages">
      <?php
    // Product Packaging
    $product_packaging = '';
    if ($this->product->product_box) {
    ?>
        <div class="product-box">
        <?php
            echo vmText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX') .$this->product->product_box;
        ?>
        </div>
    <?php } // Product Packaging END
    echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'ontop'));
    echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'normal'));
    echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'onbot'));
    ?>

    </div>
  </div>
        <div class="clearfix"></div>
        <?php echo shopFunctionsF::renderVmSubLayout('related_categories',array('product'=>$this->product,'position'=>'related_categories','class'=> 'product-related-categories')); ?>
