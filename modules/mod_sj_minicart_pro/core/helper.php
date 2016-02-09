<?php
/**
 * @package Sj Minicart Pro for VirtueMart
 * @version 3.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

JLoader::register('ImageHelper', dirname(__FILE__) . '/helper_image.php');
require dirname(__FILE__) . '/vmloader.php';

JFactory::getLanguage()->load('com_virtuemart');
if (!class_exists('sj_minicart_pro_helper')) {
	class SjMiniCartProHelper extends ImageHelper
	{
		protected static $image_cache = array();

		public static function getList($params)
		{
			if (class_exists('VirtueMartCart')) {
				$productModel = VmModel::getModel('product');
				$calculator = calculationHelper::getInstance();
				$customfields = VmModel::getModel('Customfields');
				$cart = VirtueMartCart::getCart(false);
				$cart->pricesUnformatted = $calculator->getCheckoutPrices($cart, true);
				$viewName = vRequest::getString('view',0);
				if($viewName=='cart'){
					$checkAutomaticPS = true;
				} else {
					$checkAutomaticPS = false;
				}
				$cart->prepareAjaxData($checkAutomaticPS);
				$productModel->addImages($cart->products);
				ini_set('xdebug.var_display_max_depth', 10);
				
				return $cart;
			}
		}

		public static function getVMPImage($item, $params, $prefix = 'imgcfg')
		{
			$images = self::getVMPImages($item, $params, $prefix);
			return is_array($images) && count($images) ? $images[0] : null;
		}

		public static function getVMPImages($item, $params, $prefix = 'imgcfg')
		{
			$hash = md5(serialize(array($params, 'product')));
			if (!isset(self::$image_cache[$hash][$item->virtuemart_product_id])) {
				$images_path = array();
				$field_image = $item->images[0]->file_url_thumb;
				if (file_exists($field_image)) {
					$image = array(
						'src' => $field_image,
						'title' => $item->product_name,
						'alt' => $item->product_name
					);
					array_push($images_path, $image);
				}

				if (count($images_path) == 0) {
					$images_path[] = array('src' => 'modules/mod_sj_minicart_pro/assets/images/nophoto.jpg', 'title' => $item->product_name, 'alt' => $item->product_name);
				}

				self::$image_cache[$hash][$item->virtuemart_product_id] = $images_path;
			}

			return self::$image_cache[$hash][$item->virtuemart_product_id];
		}

		public static function imageTag($image, $options = array())
		{
			return self::init($image, $options)->tag();
		}

		public static function parseTarget($type = '_self')
		{
			$target = '';
			switch ($type) {
				default:
				case '_self':
					break;
				case '_blank':
				case '_parent':
				case '_top':
					$target = 'target="' . $type . '"';
					break;
				case '_windowopen':
					$target = "onclick=\"window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,false');return false;\"";
					break;
				case '_modal':
					// user process
					break;
			}
			return $target;
		}
	}
}

