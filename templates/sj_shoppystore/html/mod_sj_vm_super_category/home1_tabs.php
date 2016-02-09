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
$cat_image_config = array(
    'type' => $params->get('imgcfgcat_type'),
    'width' => $params->get('imgcfgcat_width'),
    'height' => $params->get('imgcfgcat_height'),
    'quality' => 90,
    'function' => ($params->get('imgcfgcat_function') == 'none') ? null : 'resize',
    'function_mode' => ($params->get('imgcfgcat_function') == 'none') ? null : substr($params->get('imgcfgcat_function'), 7),
    'transparency' => $params->get('imgcfgcat_transparency', 1) ? true : false,
    'background' => $params->get('imgcfgcat_background'));
if (!empty($list['tab'])) {
    ?>
    <div class="spcat-tabs-wrap">
        <span class='spcat-tab-selected'></span>
        <span class='spcat-tab-arrow'>&#9660;</span>
        <ul class="spcat-tabs cf">
            <?php
            foreach ($list['tab'] as $key => $tab) {
                ?>

                <li class="spcat-tab <?php echo isset($tab->sel) ? '  tab-sel tab-loaded' : ''; ?>"
                    data-active-content=".items-category-<?php echo $tab->_odering; ?>"
                    data-field_order="<?php echo $tab->_odering; ?>"
                    >
					<span class="spcat-tab-label">
						<?php echo SjVMSuperCategoriesHelper::truncate($tab->title, $params->get('tab_max_characters')); ?>
					</span>
                </li>

            <?php } ?>
        </ul>
    </div>
<?php } ?>