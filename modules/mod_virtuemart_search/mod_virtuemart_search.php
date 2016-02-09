<?php
defined ('_JEXEC') or  die('Direct Access to ' . basename (__FILE__) . ' is not allowed.');
/**
 * @version $Id: mod_virtuemart_search.php 8429 2014-10-14 12:19:39Z Milbo $
 * @package VirtueMart
 * @subpackage modules
 *
 * @copyright (C) 2010-2014 The VirtueMart Team
 * @author Valerie Isaksen, Max Milbers
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * VirtueMart is Free Software.
 * VirtueMart comes with absolute no warranty.
 *
 * www.virtuemart.net
 */

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');

VmConfig::loadConfig ();
VmConfig::loadJLang ('mod_virtuemart_search', true);

// Load the virtuemart main parse code
$button = $params->get ('button', 0);
$imagebutton = $params->get ('imagebutton', 0);
$button_pos = $params->get ('button_pos', 'left');
$button_text = $params->get ('button_text', vmText::_ ('MOD_VIRTUEMART_SEARCH_GO'));
$width = intval ($params->get ('width', 20));
$maxlength = $width > 20 ? $width : 20;
$text = $params->get ('text', vmText::_ ('MOD_VIRTUEMART_SEARCH_TEXT_TXT'));
$set_Itemid = intval ($params->get ('set_itemid', 0));
$moduleclass_sfx = $params->get ('moduleclass_sfx', '');

if ($params->get ('filter_category', 0)) {
	$category_id = vRequest::getInt ('virtuemart_category_id', 0);
} else {
	$category_id = 0;
}
require(JModuleHelper::getLayoutPath ('mod_virtuemart_search'));
?>
