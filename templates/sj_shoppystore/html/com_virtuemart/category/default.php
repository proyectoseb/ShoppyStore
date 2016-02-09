<?php

defined ('_JEXEC') or die('Restricted access');
JHtml::_ ('behavior.modal');

$js = "
jQuery(document).ready(function () {
    jQuery('.orderlistcontainer').hover(
        function() { jQuery(this).find('.orderlist').stop().show()},
        function() { jQuery(this).find('.orderlist').stop().hide()}
    )

    // Click Button
    function display(view) {
        jQuery('.browse-view .row').removeClass('vm-list vm-grid').addClass(view);
        jQuery('.icon-list-grid .vm-view').removeClass('active');
        if(view == 'vm-list') {

            jQuery('.browse-view .product').addClass('col-lg-12 product-full');
            jQuery('.browse-view .product .product-left').addClass('col-md-4');
            jQuery('.browse-view .product .product-right').addClass('col-md-8');
            jQuery('.icon-list-grid .' + view).addClass('active');
        }else{
            jQuery('.browse-view .product').removeClass('col-lg-12 product-full');
            jQuery('.browse-view .product .product-left').removeClass('col-md-4');
            jQuery('.browse-view .product .product-right').removeClass('col-md-8');
            jQuery('.icon-list-grid .' + view).addClass('active');
        }
    }

    jQuery('.vm-view-list .vm-view').each(function() {
        var ua = navigator.userAgent,
        event = (ua.match(/iPad/i)) ? 'touchstart' : 'click';
         jQuery('.vm-view-list .vm-view').bind(event, function() {
            jQuery(this).addClass(function() {
                if(jQuery(this).hasClass('active')) return '';
                return 'active';
            });
            jQuery(this).siblings('.vm-view').removeClass('active');
            catalog_mode = jQuery(this).data('view');
            display(catalog_mode);

        });

    });
});
";

vmJsApi::addJScript('vm.hover',$js);
if(!function_exists('loadImg')) {
            function loadImg($path, $replacement = 'nophoto.jpg'){
            return (file_exists($path) || @getimagesize($path) !== false ) ? $path : 'images/'.$replacement;
        }
    }

if (empty($this->keyword) and !empty($this->category) and !empty($this->category->category_name)) {
    ?>
<div class="category_description">
    <h1 class="cat_title"><?php echo $this->category->category_name; ?></h1>
    <?php echo $this->category->category_description; ?>
    <div class="clearfix"></div>
</div>
<?php
}

// Show child categories
if (VmConfig::get ('showCategory', 1) and empty($this->keyword)) {
    if (!empty($this->category->haschildren)) {

        echo ShopFunctionsF::renderVmSubLayout('categories',array('categories'=>$this->category->children));

    }
}

if($this->showproducts){
?>
<div class="browse-view">
<?php

if (!empty($this->keyword)) {
    //id taken in the view.html.php could be modified
    $category_id  = vRequest::getInt ('virtuemart_category_id', 0); ?>


    <form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=false&limitstart=0' ); ?>" method="get">

        <!--BEGIN Search Box -->
        <div class="virtuemart_search">
            <?php echo $this->searchcustom ?>
            <?php echo $this->searchCustomValues ?>
            <input name="keyword" class="inputbox" type="text" size="40" value="<?php echo $this->keyword ?>"/>
            <input type="submit" value="<?php echo vmText::_ ('COM_VIRTUEMART_SEARCH') ?>" class="button" onclick="this.form.keyword.focus();"/>
        </div>
        <input type="hidden" name="search" value="true"/>
        <input type="hidden" name="view" value="category"/>
        <input type="hidden" name="option" value="com_virtuemart"/>
        <input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>

    </form>
    <!-- End Search Box -->
<?php  } ?>

<?php // Show child categories

    ?>
<div class="orderby-displaynumber top">
   <div class="row">
    <div class="vm-view-list col-md-2 col-sm-2">
        <div class="icon-list-grid">
            <div class="vm-view vm-grid active" data-view="vm-grid"><i class="listing-icon"></i></div>
            <div class="vm-view vm-list" data-view="vm-list"><i class="listing-icon"></i></div>
        </div>
    </div>
    <div class="toolbar-center col-md-6 col-sm-10">
        <div class="vm-order-list">
            <?php echo $this->orderByList['orderby']; ?>
            <?php //echo $this->orderByList['manufacturer']; ?>
        </div>
        <div class="display-number">
        <div class="title"><?php echo 'Show';?></div>
        <div class="display-number-list"><?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?></div></div>
        <div class="clearfix"></div>
    </div>
    <div class="vm-pagination vm-pagination-top col-md-4 col-sm-12">
        <?php echo $this->vmPagination->getPagesLinks (); ?>
        <div class="clearfix"></div>
    </div>
    </div>
    <div class="clearfix"></div>
</div> <!-- end of orderby-displaynumber -->

   <!--<h1><?php //echo $this->category->category_name; ?></h1>-->
    <?php
    if (!empty($this->products)) {
    $products = array();
    $products[0] = $this->products;
    echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));

    ?>
    <div class="orderby-displaynumber bottom">
   <div class="row">
    <div class="vm-view-list col-md-2 col-sm-2">
      <div class="icon-list-grid">
            <div class="vm-view vm-grid active" data-view="vm-grid"><i class="listing-icon"></i></div>
            <div class="vm-view vm-list" data-view="vm-list"><i class="listing-icon"></i></div>
        </div>
    </div>
    <div class="toolbar-center col-md-6 col-sm-10">
        <div class="vm-order-list">
            <?php echo $this->orderByList['orderby']; ?>
            <?php //echo $this->orderByList['manufacturer']; ?>
        </div>
        <div class="display-number">
        <div class="title"><?php echo 'Show';?></div>
        <div class="display-number-list"><?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?></div></div>
        <div class="clearfix"></div>
    </div>
    <div class="vm-pagination vm-pagination-top col-md-4 col-sm-12">
        <?php echo $this->vmPagination->getPagesLinks (); ?>
        <div class="clearfix"></div>
    </div>
    </div>
    <div class="clearfix"></div>
</div> <!-- end of orderby-displaynumber -->
    </div>



    <?php
} elseif (!empty($this->keyword)) {
    echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>

<?php } ?>

<!-- end browse-view -->
