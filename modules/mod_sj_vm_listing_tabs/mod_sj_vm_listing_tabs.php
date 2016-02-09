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

require_once dirname(__FILE__) . '/core/helper.php';

$layout = $params->get('layout', 'default');
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$is_ajax = $is_ajax || JRequest::getInt('is_ajax_listing_tabs', 0);
if ($is_ajax) {

    $listing_tabs_moduleid = JRequest::getVar('listing_tabs_moduleid', null);
    if ($listing_tabs_moduleid == $module->id) {

    if ($params->get('filter_type') == "filter_categories") {
        $categoryid = JRequest::getVar('categoryid', null);
        $field_order = JRequest::getVar('field_order');
        $child_items = VMListingTabsHelper::_getProductInfor($categoryid, $params, $field_order);
    } else {
        $field_order = JRequest::getVar('field_order');
        $child_items = VMListingTabsHelper::_getProductInfor('*', $params, $field_order);
    }

        $result = new stdClass();
        ob_start();
		$tab_id = JRequest::getVar('categoryid');
		$tab_id = $tab_id == '*' ? 'all' : $tab_id;
        require JModuleHelper::getLayoutPath($module->module, $layout . '_items');
        $buffer = ob_get_contents();
        $result->items_markup = preg_replace(
            array(
                '/ {2,}/',
                '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
            ),
            array(
                ' ',
                ''
            ),
            $buffer
        );
        ob_end_clean();
        die (json_encode($result));
    }
} else {
    $list = VMListingTabsHelper::getList($params);
    require JModuleHelper::getLayoutPath($module->module, $layout);
    require JModuleHelper::getLayoutPath($module->module, $layout . '_js');
}

?>
