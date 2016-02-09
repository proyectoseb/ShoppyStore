<?php // no direct access
defined('_JEXEC') or die('Restricted access');
vmJsApi::jQuery();
vmJsApi::chosenDropDowns();
?>

<!-- Currency Selector Module -->
<?php echo $text_before ?>
<div class="mod-currency">
    <form class="demo" action="<?php echo vmURI::getCleanUrl() ?>" method="post">

        <div class="vm-chzncur">

        <?php echo JHTML::_('select.genericlist', $currencies, 'virtuemart_currency_id', 'class="inputbox selectpicker" onchange="this.form.submit()"', 'virtuemart_currency_id', 'currency_txt', $virtuemart_currency_id) ; ?>
        </div>

    </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    // Selectpicker
    $('.selectpicker').selectpicker();
	
	 var ua = navigator.userAgent,
    _device = (ua.match(/iPad/i)||ua.match(/iPhone/i)||ua.match(/iPod/i)) ? "smartphone" : "desktop";

    if(_device == "desktop") {
        $(".bootstrap-select").bind('hover', function() {
           
            $(this).children(".dropdown-menu").slideToggle();
        });
    }else{
        $('.bootstrap-select .dropdown-menu').bind('touchstart', function(){
            $('.bootstrap-select .dropdown-menu').toggle();
        });
    }
   
});
</script>
