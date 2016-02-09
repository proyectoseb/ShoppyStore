<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz, Max Galt
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/* Let's see if we found the product */
if (empty($this->product)) {
    echo vmText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
    echo '<br /><br />  ' . $this->continue_link_html;
    return;
}

echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));



if(vRequest::getInt('print',false)){ ?>
<body onload="javascript:print();">
<?php } ?>

<div class="productdetails-view productdetails">
  <div class=" vm-product-container">
    <div class="col-md-6 vm-product-images-container">
<?php
        echo $this->loadTemplate('images');
?>
    </div>

    <div class="col-md-6 vm-product-details-container">

    <?php // Product Title   ?>
    <h1 class="product_title" itemprop="name"><?php echo $this->product->product_name; ?></h1>
    <?php // Product Title END   ?>

    <?php // afterDisplayTitle Event
    echo $this->product->event->afterDisplayTitle ?>

   <div class="product_tool">
    <?php
    // Product Edit Link
    echo $this->edit_link;
    // Product Edit Link END
    ?>

    <?php
    // PDF - Print - Email Icon
    if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) {
    ?>
        <div class="icons">
        <?php

        $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;

        echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
        //echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
        echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon',false,true,false,'class="printModal"');
        $MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';
        echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend', false,true,false,'class="recommened-to-friend"');
        ?>
        <div class="clearfix"></div>
        </div>
    <?php } // PDF - Print - Email Icon END
    ?>
        </div>
<div class="product_rating"><?php echo shopFunctionsF::renderVmSubLayout('rating',array('showRating'=>$this->showRating,'product'=>$this->product));?></div>
<div class="stockhandle">
            <?php  echo shopFunctionsF::renderVmSubLayout('stockhandle',array('product'=>$this->product)); ?>
        </div>

   <?php     // Product Short Description
    if (!empty($this->product->product_s_desc)) {
    ?>
        <div class="product-short-description">
        <?php
        /** @todo Test if content plugins modify the product description */
        echo nl2br($this->product->product_s_desc);
        ?>
        </div>
    <?php
    } // Product Short Description END
        echo shopFunctionsF::renderVmSubLayout('prices',array('product'=>$this->product,'currency'=>$this->currency));
        ?>
        <div class="clearfix"></div>
       <?php
        echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$this->product));
        ?>
        <div class="product-other-container">
        <div class="display_payment">
        <?php
        // TODO in Multi-Vendor not needed at the moment and just would lead to confusion
        /* $link = JRoute::_('index2.php?option=com_virtuemart&view=virtuemart&task=vendorinfo&virtuemart_vendor_id='.$this->product->virtuemart_vendor_id);
          $text = vmText::_('COM_VIRTUEMART_VENDOR_FORM_INFO_LBL');
          echo '<span class="bold">'. vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS_VENDOR_LBL'). '</span>'; ?><a class="modal" href="<?php echo $link ?>"><?php echo $text ?></a><br />
         */
        ?>

        <?php

        if (is_array($this->productDisplayShipments)) {
            foreach ($this->productDisplayShipments as $productDisplayShipment) {
            echo $productDisplayShipment . '';
            }
        }
        if (is_array($this->productDisplayPayments)) {
            foreach ($this->productDisplayPayments as $productDisplayPayment) {
            echo $productDisplayPayment . '';
            }
        }

        //In case you are not happy using everywhere the same price display fromat, just create your own layout
        //in override /html/fields and use as first parameter the name of your file

            ?></div>

        <div class="display-ask-mf">
       <?php
        // Manufacturer of the Product
        if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
            echo $this->loadTemplate('manufacturer');
        }
        ?>
        <?php
        // Ask a question about this product
        if (VmConfig::get('ask_question', 0) == 1) {
            $askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
            ?>
            <div class="ask-a-question">
                <a class="question" href="<?php echo $askquestion_url ?>" rel="nofollow" ><i class="fa fa-question-circle"></i><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>
            </div>
        <?php
        }
            ?></div>

            <div class="display-nav">
                <?php
    // Product Navigation
    if (VmConfig::get('product_navigation', 1)) {
    ?>
        <div class="product-neighbours">
	    <?php
	    if (!empty($this->product->neighbours ['previous'][0])) {
		$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
		echo JHtml::_('link', $prev_link, $this->product->neighbours ['previous'][0]
			['product_name'], array('rel'=>'prev', 'class' => 'previous-page','data-dynamic-update' => '1'));
	    }
	    if (!empty($this->product->neighbours ['next'][0])) {
		$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
		echo JHtml::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'], array('rel'=>'next','class' => 'next-page','data-dynamic-update' => '1'));
	    }
	    ?>
    	<div class="clear"></div>
        </div>
    <?php } // Product Navigation END
    ?>

    <?php // Back To Category Button
    if ($this->product->virtuemart_category_id) {
        $catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
        $categoryName = vmText::_($this->product->category_name) ;
    } else {
        $catURL =  JRoute::_('index.php?option=com_virtuemart');
        $categoryName = vmText::_('COM_VIRTUEMART_SHOP_HOME') ;
    }
    ?>
    <div class="back-to-category">
        <a href="<?php echo $catURL ?>" class="product-details" title="<?php echo $categoryName ?>"><?php echo vmText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO',$categoryName) ?></a>
    </div>

            </div>
       <?php

    // event onContentBeforeDisplay
    echo $this->product->event->beforeDisplayContent; ?>

        </div>
        <div class="clearfix"></div>
        
    </div>
    <div class="clearfix"></div>
    </div>

<div class="vm-product-container">
<?php (isset($this->product->customfieldsSorted['related_products'])) ? $col_ptabs = 'col-md-9' :  $col_ptabs = 'col-md-12';?>
    <div class="col-md-3 product-related">
        <?php echo shopFunctionsF::renderVmSubLayout('related_products',array('product'=>$this->product,'position'=>'related_products','class'=> 'product-related-products','customTitle' => true ));?>
    </div>
<div class="<?php echo $col_ptabs; ?> product-tabs">
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
    if (!empty($this->product->product_desc)) {
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
</div>

</div>










<?php
// Show child categories
if (VmConfig::get('showCategory', 1)) {
    echo $this->loadTemplate('showcategory');
}

$j = 'jQuery(document).ready(function($) {
    Virtuemart.product(jQuery("form.product"));

    $("form.js-recalculate").each(function(){
        if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
            var id= $(this).find(\'input[name="virtuemart_product_id[]"]\').val();
            Virtuemart.setproducttype($(this),id);

        }
    });
});';
//vmJsApi::addJScript('recalcReady',$j);

/** GALT
     * Notice for Template Developers!
     * Templates must set a Virtuemart.container variable as it takes part in
     * dynamic content update.
     * This variable points to a topmost element that holds other content.
     */
$j = "Virtuemart.container = jQuery('.productdetails-view');
Virtuemart.containerSelector = '.productdetails-view';";

vmJsApi::addJScript('ajaxContent',$j);

echo vmJsApi::writeJS();

if ($this->product->prices['salesPrice'] > 0) {
  echo shopFunctionsF::renderVmSubLayout('snippets',array('product'=>$this->product, 'currency'=>$this->currency, 'showRating'=>$this->showRating));
}

?>
</div>



