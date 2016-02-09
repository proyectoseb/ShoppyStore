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
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/sj-deals.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/font-awesome.min.css');
    if (!defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1") {
        JHtml::script('modules/' . $module->module . '/assets/js/jquery-1.8.2.min.js');
        JHtml::script('modules/' . $module->module . '/assets/js/jquery-noconflict.js');
        define('SMART_JQUERY', 1);
    }
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/owl.carousel.css');
    JHtml::stylesheet('modules/' . $module->module . '/assets/css/owl.theme.css');
    JHtml::script('modules/' . $module->module . '/assets/js/owl.carousel.js');
    $module_id = 'sj_vm_deal_' . $module->id;
    ImageHelper::setDefault($params);
    $cat_image_config = array(
    'type' => $params->get('imgcfgcat_type'),
    'width' => $params->get('imgcfgcat_width'),
    'height' => $params->get('imgcfgcat_height'),
    'quality' => 90,
    'function' => ($params->get('imgcfgcat_function') == 'none') ? null : 'resize',
    'function_mode' => ($params->get('imgcfgcat_function') == 'none') ? null : substr($params->get('imgcfgcat_function'), 7),
    'transparency' => $params->get('imgcfgcat_transparency', 1) ? true : false,
    'background' => $params->get('imgcfgcat_background'));

    echo '<div class="sj_vm_deals_wrap sj_relative" id="'.$module_id.'">';
        if($type != 'POP'){
            require JModuleHelper::getLayoutPath($module->module, $layout . '_deals');
        }else{
            require JModuleHelper::getLayoutPath($module->module, $layout . '_popup');
        }

    echo '</div>';

} else {

    echo JText::_('Has no item to show!');
}

?>



