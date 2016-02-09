<?php
/**
 * @package Sj Super Categories for JoomShopping
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;
if (!empty($list)) {
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/style.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/animate.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/owl.carousel.css');
    if (!defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1") {
        JHtml::script('modules/' . $module->module . '/assets/js/jquery-1.8.2.min.js');
        JHtml::script('modules/' . $module->module . '/assets/js/jquery-noconflict.js');

        define('SMART_JQUERY', 1);
    }
    JHtml::script('modules/' . $module->module . '/assets/js/owl.carousel.js');

    ImageHelper::setDefault($params);

    $instance = rand() . time();
    $tag_id = 'sj_sp_cat_' . rand() . time();
    $options = $params->toObject();

    $array = array();
    foreach ($list['tab'] as $index=>$items) {
        $array[] = $items->countp;
    }
    $_count_item = (int)$array[0];
    $limit = $params->get('source_limit',5);
    $nbcolumn1 = ($params->get('nb-column1', 6) >= $_count_item) ? $_count_item : $params->get('nb-column1', 6);
    $nbcolumn2 = ($params->get('nb-column2', 4) >= $_count_item) ? $_count_item : $params->get('nb-column2', 4);
    $nbcolumn3 = ($params->get('nb-column3', 2) >= $_count_item) ? $_count_item : $params->get('nb-column3', 2);
    $nbcolumn4 = ($params->get('nb-column4', 1) >= $_count_item) ? $_count_item : $params->get('nb-column4', 1);
    $class_spcat = 'spcat00-' . $nbcolumn1 . ' spcat01-' . $nbcolumn1 . ' spcat02-' . $nbcolumn2 . ' spcat03-' . $nbcolumn3 . ' spcat04-' . $nbcolumn4;

    $count_item = count($list['category_tree']);
    $nb_column1 = ($params->get('nb_column1', 6) >= $count_item) ? $count_item : $params->get('nb_column1', 6);
    $nb_column2 = ($params->get('nb_column2', 4) >= $count_item) ? $count_item : $params->get('nb_column2', 4);
    $nb_column3 = ($params->get('nb_column3', 2) >= $count_item) ? $count_item : $params->get('nb_column3', 2);
    $nb_column4 = ($params->get('nb_column4', 1) >= $count_item) ? $count_item : $params->get('nb_column4', 1);

    ?>
    <!--[if lt IE 9]>
    <div id="<?php echo $tag_id; ?>" class="sj-sp-cat msie lt-ie9 first-load"><![endif]-->
    <!--[if IE 9]>
    <div id="<?php echo $tag_id; ?>" class="sj-sp-cat msie first-load"><![endif]-->
    <!--[if gt IE 9]><!-->
    <div id="<?php echo $tag_id; ?>" class="sj-sp-cat first-load"><!--<![endif]-->
        <div class="spcat-wrap ">
            <?php require JModuleHelper::getLayoutPath($module->module, $layout . '_cat'); ?>

            <div class="spcat-tabs-container"
                 data-delay="<?php echo $params->get('delay', 300); ?>"
                 data-duration="<?php echo $params->get('duration', 600); ?>"
                 data-effect="<?php echo $params->get('effect'); ?>"
                 data-ajaxurl="<?php echo (string)JURI::getInstance(); ?>" data-modid="<?php echo $module->id; ?>">
                <!--Begin Tabs-->
                <?php require JModuleHelper::getLayoutPath($module->module, $layout . '_tabs'); ?>
                <!-- End Tabs-->
            </div>

            <?php if (!empty($list['tab'])) { ?>
           <?php if (!empty($options->pretext)) { ?>
               <div class="clearfix"></div>
                <div class="pre-text"><?php echo $options->pretext; ?></div>
            <?php } ?>
                <div class="spcat-items-container"><!--Begin Items-->
                    <?php foreach ($list['tab'] as $items) {
                        $child_items = isset($items->child) ? $items->child : '';
                        $cls_device = $class_spcat;
                        $cls_animate = $params->get('effect');
                        $cls = (isset($items->sel) && $items->sel == "sel") ? ' spcat-items-selected spcat-items-loaded' : '';
                        $cls .= ' items-category-' . $items->_odering;
                        ?>

                        <div class="spcat-items <?php echo $cls; ?>">
                           <div class="control-slide-product">
                <div class="btn-prev"><i class="fa fa-angle-left"></i></div>
                <div class="btn-next"><i class="fa fa-angle-right"></i></div>
            </div>
                            <div class="spcat-items-inner <?php echo $cls_device . ' ';
                            echo $cls_animate; ?>"
                                >

                                <?php if (!empty($child_items)) {
                                    require JModuleHelper::getLayoutPath($module->module, $layout . '_items');
                                } else {
                                    ?>
                                    <div class="spcat-loading"></div>
                                <?php } ?>
                            </div>
                            <?php $classloaded = ( $params->get('source_limit', 5) >= $items->countp || $params->get('source_limit', 5) == 0) ? 'loaded' : '';?>
                            <div class="spcat-loadmore"
                                 data-active-content=".items-category-<?php echo $items->_odering; ?>"
                                 data-field_order="<?php echo $items->_odering; ?>"
                                 data-rl_start="<?php echo $params->get('source_limit', 2) ?>"
                                 data-rl_total="<?php echo $items->countp ?>"
                                 data-rl_allready="<?php echo JText::_('ALL_READY_LABEL'); ?>"
                                 data-ajaxurl="<?php echo (string)JURI::getInstance(); ?>"
                                 data-modid="<?php echo $module->id; ?>"
                                 data-rl_load="<?php echo $params->get('source_limit', 2) ?>">
                                <div class="spcat-loadmore-btn <?php echo $classloaded ?>"
                                     data-label="<?php echo ($classloaded) ? JText::_('ALL_READY_LABEL') : JText::_('LOAD_MORE_LABEL'); ?>">
                                    <span class="spcat-image-loading"></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            <?php
            } else {
                echo 'Has no conten to show';
            }; ?>
            <!--End Items-->

        </div>
        <?php if (!empty($options->posttext)) { ?>
            <div class="post-text"><?php echo $options->posttext; ?></div>
        <?php } ?>
    </div>
<?php } ?>


