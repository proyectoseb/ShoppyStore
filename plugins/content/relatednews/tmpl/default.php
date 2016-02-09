<?php
/**
 * @package Content - Related News
 * @version 3.3.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

// includes placehold
$yt_temp = JFactory::getApplication()->getTemplate();
include (JPATH_BASE . '/templates/'.$yt_temp.'/includes/placehold.php');

ImageHelper::setDefault($_params);
if (count($items)){
    if ((int)$this->params->get('usecss', 1)){
        $css_url = JURI::root() . 'plugins/content/relatednews/assets/css/relatednews.css';
        $document = JFactory::getDocument();
        $document->addStyleSheet($css_url);
    }

    if ($this->params->get('title')){
        echo '<h3 class="related-title">';
        echo $this->params->get('title');
        echo '</h3>';
    }
    echo '<ul class="related-items row">';
    foreach ($items as $id => $item) {
        if($item->id != $article_id){?>
            <li class="col-sm-6"  >
            <?php
            if ((int)$this->params->get('item_image_display', 1)){ ?>
                <div class="img-fulltext pull-left">

                    <?php
                        $img = BaseHelper::getArticleImage($item, $_params);
                        //Create placeholder images
                        if (isset($img['src'])) $src = $img['src'];
                        if (file_exists($src )) {
                            echo BaseHelper::imageTag($img);
                        } else if ($is_placehold) {
                            echo yt_placehold($placehold_size['related_items'] );
                        }
                    ?>

                </div>
            <?php }?>

            <?php
            if ((int)$_params->get('item_date_display', 1) == 1): ?>
                <span class="related-item-date"><?php echo JHtml::date($item->created, 'd mm Y'); ?></span>

            <?php
            endif;
            ?>
                <h3 class="related-item-title">
                    <a href="<?php echo $item->link; ?>" <?php echo BaseHelper::parseTarget($_params->get('item_link_target'));?> >
                    <?php echo $item->title;; ?>
                    </a>
                </h3>

            <?php
            if ((int)$_params->get('item_date_display', 1) == 2): ?>
                <div class="related-item-date"><i class="fa fa-clock-o"></i><?php echo JHtml::date($item->created, 'd M Y'); ?></div>
            <?php
            endif;
            ?>
            </li>
    <?php
    }}
    echo '</ul>';
}

?>
