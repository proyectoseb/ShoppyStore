<?php
/**
 * @package SJ Accordion for Virtuemart
 * @version 1.1.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

$tag_id = 'sj_accordion_'.time().rand();
JHtml::stylesheet('modules/'.$module->module.'/assets/css/accordion.css');

    if(class_exists('vmJsApi')){
        vmJsApi::jQuery();
    }else {
        if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
            JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
            JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
            define('SMART_JQUERY', 1);
        }
    }
JHtml::script('modules/'.$module->module.'/assets/js/jquery.jaccordion.js');
ImageHelper::setDefault($params);
$currency = CurrencyDisplay::getInstance( );

$tab_active = (int)$params->get('tab_active');
if($tab_active <=0 || $tab_active > count($list)){
    $tab_active = 0;
}?>
<?php if($params->get('pretext') != null){?>
<div class="acd-pretext">
    <?php echo $params->get('pretext'); ?>
</div>
<?php }
if(!empty($list)){ ?>
    <div id="<?php echo $tag_id; ?>" class="sj-accordion">
        <div class="acd-items">
            <?php  foreach($list as $item){
            ?>
            <div class="acd-item">
                <div class="acd-header">
                    <?php echo AccordionHelper::truncate($item->product_name, $params->get('item_title_max_characs'))?>


                </div>
                <div class="acd-content-wrap cf">
                    <div class="acd-content-wrap-inner cf">

                        <?php $img = AccordionHelper::getVmImage($item, $params);
                        if($img){
                        ?>
                        <div class="acd-image item-quick-view cf">
                            <a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo AccordionHelper::parseTarget($params->get('item_link_target')); ?> >
                                <?php echo AccordionHelper::imageTag($img); ?>
                            </a>
                        </div>

                        <?php } ?>

                         <div class="acd-title cf">
                            <a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo AccordionHelper::parseTarget($params->get('item_link_target')); ?> >
                                <?php echo $item->title; ?>
                            </a>
                        </div>
                        <?php require JModuleHelper::getLayoutPath($module->module, $layout . '_rating'); ?>
                        <div class="item-price">
                    <?php if (!empty($item->prices['salesPrice'])) {
                        echo $currency->createPriceDiv('salesPrice', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                    }
                    if (!empty($item->prices['discountAmount'])) {
                        echo $currency->createPriceDiv('discountAmount', '', $item->prices, FALSE, FALSE, 1.0, TRUE);
                          } ?>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
<?php } else{
    echo '<div class="no-item">'.JText::_('Has no content to show!').'</div>';
}?>
<?php if($params->get('posttext') != null){?>
<div class="acd-posttext">
    <?php echo $params->get('posttext'); ?>
</div>
<?php }?>
<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function($) {
        ;(function(element){
            var $element = $(element);
            $element.jaccordion({
                items : '.acd-item',
                heading : '.acd-header',
                content : '.acd-content-wrap',
                active_class : 'selected',
                event : '<?php echo $params->get('pager_event','click');?>',
                delay : 200,
                duration : 400,
                active : <?php echo $tab_active; ?>
            });

        })('#<?php echo $tag_id; ?>');
    });
//]]>
</script>

