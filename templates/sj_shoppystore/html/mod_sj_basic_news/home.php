<?php
/**
* @package Sj Basic News
* @version 3.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @copyright (c) 2012 YouTech Company. All Rights Reserved.
* @author YouTech Company http://www.smartaddons.com
*
*/
defined('_JEXEC') or die;

if(!empty($list)){
    $uniquied = 'sj_basic_news_'.time().rand();
    JHtml::stylesheet('modules/mod_sj_basic_news/assets/css/sj-basic-news.css');
    ImageHelper::setDefault($params);
?>
    <div id="<?php echo $uniquied?>" class="sj-basic-news home">
        <?php if($params->get('pretext') != null) {?>
            <div class="bs-pretext">
                <h3 class="modtitle"><?php echo $params->get('pretext'); ?></h3>
            </div>
        <?php }?>
        <ul class="bs-items">
            <?php  $i = 0;  foreach($list as $item){ $i++;
                $show_line = ($params->get('showline') == 1)?' bs-show-line':'';
                $last_class = ($i == count($list))?' last':'';
                $img = SjBasicNewsHelper::getAImage($item, $params);
            ?>
            <li class="bs-item cf <?php echo $show_line.' '.$last_class; ?>">
                <?php if($img){?>
                <div class="bs-image">
                    <a href="<?php echo $item->link ?>" title="<?php echo $item->title; ?>" <?php echo SjBasicNewsHelper::parseTarget($params->get('link_target')); ?>>
                        <?php
                             echo SjBasicNewsHelper::imageTag($img);
                        ?>
                    </a>
                </div>
                <?php } ?>
                <div class="bs-content">
                   <?php if($params->get('cat_title_display') == 1 || $params->get('item_date_display') == 1 ){?>
                    <div class="bs-cat-date">
                        <?php if($params->get('item_date_display') == 1 ){?>
                        <span class="bs-date" data-author="<?php echo  $item->author ;?>">
                        <?php echo  JHTML::_('date', $item->created,JText::_('d M, Y')) ;?>
                        </span>
                        <?php }?>
                        <?php if($params->get('cat_title_display') == 1 ){?>
                        <span class="bs-cat-title">
                            <?php echo JText::_('Category: ');?>
                            <a href="<?php echo $item->catlink; ?>" title="<?php echo $item->category_title; ?>" <?php echo SjBasicNewsHelper::parseTarget($params->get('link_target')); ?> >
                                <?php echo $item->category_title; ?>
                            </a>
                        </span>
                        <?php } ?>

                    </div>
                    <?php }?>
                    <?php if($params->get('title_display') == 1) {?>
                    <div class="bs-title">
                        <a href="<?php echo $item->link ?>" title="<?php echo $item->title; ?>" <?php echo SjBasicNewsHelper::parseTarget($params->get('link_target')); ?>>
                            <?php echo SjBasicNewsHelper::truncate($item->title, $params->get('item_title_max_characs',25)); ?>
                        </a>
                    </div>
                    <?php }?>
                    <?php if($params->get('item_desc_display') == 1) {?>
                    <div class="bs-description">
                        <div class="description"><?php echo SjBasicNewsHelper::truncate($item->introtext, $params->get('item_desc_max_characs',200)); ?></div>
                    </div>
                    <?php } ?>

                    <?php if($params->get('item_readmore_display') == 1){?>
                    <div class="bs-readmore">
                        <a href="<?php echo $item->link ?>" title="<?php echo $item->title; ?>" <?php echo SjBasicNewsHelper::parseTarget($params->get('link_target')); ?>>
                            <?php echo $params->get('item_readmore_text'); ?>
                        </a>
                    </div>
                    <?php }?>
                </div>
            </li>
            <?php } ?>
        </ul>
        <?php if($params->get('posttext') != null) {?>
            <div class="bs-posttext">
                <?php echo $params->get('posttext'); ?>
            </div>
        <?php }?>
    </div>
<?php
}else{
    echo JText::_('Has no content to show!');
}?>
