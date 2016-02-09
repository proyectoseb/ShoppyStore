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

$big_image_config = array(
    'type' => $params->get('imgcfgcat_type'),
    'width' => $params->get('imgcfgcat_width'),
    'height' => $params->get('imgcfgcat_height'),
    'quality' => 90,
    'function' => ($params->get('imgcfgcat_function') == 'none') ? null : 'resize',
    'function_mode' => ($params->get('imgcfgcat_function') == 'none') ? null : substr($params->get('imgcfgcat_function'), 7),
    'transparency' => $params->get('imgcfgcat_transparency', 1) ? true : false,
    'background' => $params->get('imgcfgcat_background'));

//effect
$center = $options->center == 1 ? 'true' : 'false';
$nav = $options->nav == 1 ? 'true' : 'false';
$loop = $options->loop == 1 ? 'true' : 'false';

$margin = ( (int)$options->margin >= 0 ) ? $options->margin : 5;
$autoplay = $options->autoplay == 1 ? 'true' : 'false';
$autoplayTimeout = ( $options->autoplayTimeout >= 0 && is_int($options->margin) ) ? $options->autoplayTimeout : 5000;
$autoplayHoverPause = ($options->autoplayHoverPause == 1) ? 'true' : 'false';
$autoplaySpeed = ($options->autoplaySpeed >= 0 && is_int($options->margin)) ? $options->autoplaySpeed : 5000;
$navSpeed = ($options->navSpeed >= 0 && is_int($options->margin)) ? $options->navSpeed : 5000;
$smartSpeed = ( $options->smartSpeed >= 0 && is_int($options->margin) ) ? $options->smartSpeed : 5000;
$mouseDrag = $options->mouseDrag == 1 ? 'true' : 'false';
$touchDrag = $options->touchDrag == 1 ? 'true' : 'false';
$pullDrag = $options->pullDrag == 1 ? 'true' : 'false';

?>

<div class="category-wrap-cat">
    <?php 
    if (!empty($list['category_parent']) && $params->get('show_categories_slide',1) == 1) {
        ?>
        <div class="title-imageslider  sp-cat-title-parent"
             >
			 
            <a title="<?php echo $list['category_parent'][0]->category_name; ?>"
               href="<?php echo $list['category_parent'][0]->link; ?>"
                <?php echo SjVMSuperCategoriesHelper::parseTarget($params->get('target')); ?> >
                <?php echo SjVMSuperCategoriesHelper::truncate($list['category_parent'][0]->category_name, $params->get('category_title_max_characs', 25)); ?>
            </a>
        </div>
   <?php } ?>

    <?php $tag = 'cat_slider_' . rand() . time();
    if (!empty($list['category_tree'])) {
        ?>
        <div id="<?php echo $tag ?>" class="slider">
		<?php if(isset($list['category_tree']) && count($list['category_tree']) > 0){?>
		 <?php if (!empty($list['category_parent']) && $params->get('show_categories_slide',1) == 1) {?>
			<?php if ($params->get('show_categories_type_slide',1) == 0 ){$cls_type=" sj_category_type_default";}else{$cls_type = "";}?>
            <div class="cat_slider_inner<?php echo $cls_type;?>">
                <?php
                foreach ($list['category_tree'] as $index => $cat_tree) {
					if($list['category_parent'][0]->category_name == $cat_tree->category_name)continue;
                    ?>
                    <div class="item">
                        <div class="item-inner">

                            <div class="cat_slider_image">
                                <a href="<?php echo $cat_tree->link; ?>"
                                   title="<?php echo $cat_tree->category_name; ?>" <?php echo SjVMSuperCategoriesHelper::parseTarget($params->get('target')); ?>>
                                    <?php $img = SjVMSuperCategoriesHelper::getVMCImage($cat_tree, $params, 'imgcfgcat');
                                    echo SjVMSuperCategoriesHelper::imageTag($img, $big_image_config);?>
                                </a>
                            </div>

                            <?php if ($params->get('category_sub_title_display', 1) && count($list['category_tree']) > 1) { ?>
                                <div class="cat_slider_title">
                                    <a href="<?php echo $cat_tree->link; ?>"
                                       title="<?php echo $cat_tree->category_name; ?>" <?php echo SjVMSuperCategoriesHelper::parseTarget($params->get('target')); ?>>
                                        <?php echo SjVMSuperCategoriesHelper::truncate($cat_tree->category_name, $params->get('category_sub_title_max_characs', 25)); ?>
                                    </a>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                <?php
                } ?>

            </div>
			 <?php } ?>
		<?php }?>
        </div>
    <?php } ?>
    
</div>
