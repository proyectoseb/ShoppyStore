<?php
/**
 * @package Sj Vm Listing Tabs
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
if (!empty($list)) {
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/sj-listing-tabs.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/animate.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/owl.carousel.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/chosen.css');
    JHtml::stylesheet('http://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css');
    if (class_exists('vmJsApi')) {
        vmJsApi::jPrice();
        vmJsApi::cssSite();
        echo vmJsApi::writeJS();
    } else {
        if (!defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1") {
            JHtml::script('modules/' . $module->module . '/assets/js/jquery-1.8.2.min.js');
            JHtml::script('modules/' . $module->module . '/assets/js/jquery-noconflict.js');
            define('SMART_JQUERY', 1);
        }
    }
    JHtml::script('modules/' . $module->module . '/assets/js/owl.carousel.js');
    JHtml::script('modules/' . $module->module . '/assets/js/chosen.jquery.min.js');
    ImageHelper::setDefault($params);

    $instance = rand() . time();
    $tag_id = 'sj_listing_tabs_' . $module->id;
    $options = $params->toObject();
    $class_ltabs = 'ltabs00-' . $params->get('nb-column1', 6) . ' ltabs01-' . $params->get('nb-column1', 6) . ' ltabs02-' . $params->get('nb-column2', 4) . ' ltabs03-' . $params->get('nb-column3', 2) . ' ltabs04-' . $params->get('nb-column4', 1)
    ?>
    <!--[if lt IE 9]>
    <div id="<?php echo $tag_id; ?>" class="sj-listing-tabs msie lt-ie9 first-load"><![endif]-->
    <!--[if IE 9]>
    <div id="<?php echo $tag_id; ?>" class="sj-listing-tabs msie first-load"><![endif]-->
    <!--[if gt IE 9]><!-->
    <div id="<?php echo $tag_id; ?>" class="sj-listing-tabs first-load"><!--<![endif]-->
        <?php if (!empty($options->pretext)) { ?>
            <div class="pre-text"><?php echo $options->pretext; ?></div>
        <?php } ?>
        <div class="ltabs-wrap ">

            <!--Begin Tabs-->
            <div class="ltabs-tabs-container" data-delay="<?php echo $params->get('delay', 300); ?>"
                 data-duration="<?php echo $params->get('duration', 600); ?>"
                 data-effect="<?php echo $params->get('effect'); ?>"
                 data-ajaxurl="<?php echo (string)JURI::getInstance(); ?>" data-modid="<?php echo $module->id; ?>">
                <?php require JModuleHelper::getLayoutPath($module->module, $layout . '_tabs'); ?>
            </div>
            <!-- End Tabs-->

            <div class="ltabs-items-container"><!--Begin Items-->
                <?php foreach ($list as $items) {
                $child_items = isset($items->child) ? $items->child : '';
                $cls_device = $class_ltabs;
                $cls_animate = $params->get('effect');
                $cls = (isset($items->sel) && $items->sel == "sel") ? ' ltabs-items-selected ltabs-items-loaded' : '';
                $cls .= ($items->virtuemart_category_id == "*") ? ' items-category-all' : ' items-category-' . $items->virtuemart_category_id;
                $tab_id = isset($items->sel) ? $items->virtuemart_category_id : '';
                $tab_id = $tab_id == '*' ? 'all' : $tab_id;
                $nb_rows = $params->get('nb_rows');
                ?>
                <div class="ltabs-items <?php echo $cls; ?>" data-total="<?php echo $params->get('type_show') == 'slider' ?  floor($items->count/$nb_rows) : $items->count ;?>">
                    <?php if ($params->get('type_show') == 'loadmore') { ?>
                        <div class="ltabs-items-inner <?php echo $params->get('type_show') == 'loadmore' ? $cls_device. ' '. $cls_animate : '  ' ;?>">
                    <?php } ?>
                        <?php if (!empty($child_items)) {
                            require JModuleHelper::getLayoutPath($module->module, $layout . '_items');
                        } else {
                            ?>
                            <div class="ltabs-loading"></div>
                        <?php } ?>
                    <?php if ($params->get('type_show') == 'loadmore') { ?>
                        </div>
                    <?php } ?>
                <?php
                $classloaded = ($params->get('source_limit', 2) >= $items->count || $params->get('source_limit', 2) == 0) ? 'loaded' : '';
                if($params->get('type_show')=='loadmore'){
                ?>
                <div class="ltabs-loadmore"
                     data-active-content=".items-category-<?php echo ($items->virtuemart_category_id == "*") ? 'all' : $items->virtuemart_category_id; ?>"
                     data-categoryid="<?php echo $items->virtuemart_category_id; ?>"
                     data-rl_start="<?php echo $params->get('source_limit', 2) ?>"
                     data-rl_total="<?php echo $items->count; ?>"
                     data-rl_allready="<?php echo JText::_('ALL_READY_LABEL'); ?>"
                     data-ajaxurl="<?php echo (string)JURI::getInstance(); ?>" data-modid="<?php echo $module->id; ?>"
                     data-rl_load="<?php echo $params->get('source_limit', 2) ?>"
                     data-rl-field_order="<?php echo $items->field_order; ?>">
                    <div class="ltabs-loadmore-btn <?php echo $classloaded ?>"
                         data-label="<?php echo ($classloaded) ? JText::_('ALL_READY_LABEL') : JText::_('LOAD_MORE_LABEL'); ?>">
                        <span class="ltabs-image-loading"></span>
                        <img class="add-loadmore" alt="Load More"
                             src="<?php echo JURI::base(); ?>/modules/<?php echo $module->module; ?>/assets/images/add.png">
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <!--End Items-->
    </div>
    <?php if (!empty($options->posttext)) { ?>
        <div class="post-text"><?php echo $options->posttext; ?></div>
    <?php } ?>
    </div>
<?php
} else {
    echo JText::_('Has no item to show!');
} ?>



