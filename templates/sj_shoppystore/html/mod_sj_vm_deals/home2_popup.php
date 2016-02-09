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
	$currency = CurrencyDisplay::getInstance();
	$time_now = $date = date('Y-m-d H:i:s');
	$small_image_config = array(
    'type' => $params->get('imgcfg_type'),
    'width' => $params->get('imgcfg_width'),
    'height' => $params->get('imgcfg_height'),
    'quality' => 90,
    'function' => ($params->get('imgcfg_function') == 'none') ? null : 'resize',
    'function_mode' => ($params->get('imgcfg_function') == 'none') ? null : substr($params->get('imgcfg_function'), 7),
    'transparency' => $params->get('imgcfg_transparency', 1) ? true : false,
    'background' => $params->get('imgcfg_background'));
	if($params->get('item_first_product_display',1) == 1){
		$first_image_config = array(
		'type' => $params->get('imgcfgfd_type'),
		'width' => $params->get('imgcfgfd_width'),
		'height' => $params->get('imgcfgfd_height'),
		'quality' => 90,
		'function' => ($params->get('imgcfgfd_function') == 'none') ? null : 'resize',
		'function_mode' => ($params->get('imgcfgfd_function') == 'none') ? null : substr($params->get('imgcfgfd_function'), 7),
		'transparency' => $params->get('imgcfgfd_transparency', 1) ? true : false,
		'background' => $params->get('imgcfgfd_background'));
		echo '<div class="sj_vm_deals_first_product">';
			echo '<div class="item">';
				echo '<div class="sj_relative">';
				if($params->get('item_percentage_discount_display',1) == 1){
					echo '<div class="sj_percentage_discount_display sj_absolute">';
						$percent = (int)(((((int)$list[0]->prices['product_price'] - (int)$list[0]->prices['salesPrice']))/$list[0]->prices['product_price'])*100);						
						echo $percent . ' %';
					echo '</div>';
				}
				echo '<div class="item-inner">';
					$item_img = VMDealsPHelper::getVmImage($list[0], $params, 'imgcfgfd');
					if ($item_img) {
						echo '<div class="item-image">
									<a href="'.$list[0]->link.'"
										title="'.$list[0]->title.'" '.VMDealsPHelper::parseTarget($params->get('link_target')).' >'.VMDealsPHelper::imageTag($item_img, $first_image_config).'
										<span class="image-border"></span>
									</a>
								</div>';
					}
					if($params->get('item_countdown_display') != 'POP'){
						if($list[0]->prices['product_price_publish_down'] && $list[0]->prices['product_price_publish_down'] != null){
							echo '<div class="item-deals" data-deals="'.$list[0]->prices['product_price_publish_down'].'">';
							
							echo '</div>';
						}
					}
					
					if($params->get('item_rating_display', 1) == 1){
						if(!isset($list[0]->rating->rating) || $list[0]->rating->rating == null){
							$rating = 0;
						}else{
							$rating = $list[0]->rating->rating;
						}
						echo '<div class="item-rating" data-rating="'.$rating.'">';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
						echo '</div>';
					}		
					if ($params->get('item_title_display', 1) == 1) { 
						echo '<div class="item-title">';
							echo '<a href="'.$list[0]->link.'" title="'.$list[0]->title.'" '.VMDealsPHelper::parseTarget($params->get('link_target')).' >';      
								echo VMDealsPHelper::truncate($list[0]->title, (int)$params->get('item_title_max_characters', 25));
							echo '</a>';
						echo '</div>';
					} 
					 if ((int)$params->get('item_prices_display', 1) && ( !empty($list[0]->prices['salesPrice']) || !empty($list[0]->prices['salesPriceWithDiscount'])) ) { 
						echo '<div class="item-prices">';
							echo '<div class="item-prices-override">';
							if (!empty($list[0]->prices['salesPrice'])) {
								echo $currency->createPriceDiv('salesPrice', JText::_("Price: "), $list[0]->prices, false, false, 1.0, true);
							}
							if (!empty($list[0]->prices['salesPriceWithDiscount'])) {
								echo $currency->createPriceDiv('salesPriceWithDiscount', JText::_("Price: "), $list[0]->prices, false, false, 1.0, true);
							} 
							echo '</div>';
							echo '<div class="item-prices-final">';
							$price = (int)$list[0]->prices['product_price'] - (int)$list[0]->prices['salesPrice'];
							if (!empty($list[0]->prices['salesPrice'])) {
								echo $currency->createPriceDiv('salesPrice', JText::_("Price: "), $price, false, false, 1.0, true);
							}
							if (!empty($list[0]->prices['salesPriceWithDiscount'])) {
								echo $currency->createPriceDiv('salesPriceWithDiscount', JText::_("Price: "), $price, false, false, 1.0, true);
							}
							echo '</div>';
						echo '</div>';
					}
				echo '</div>';
				if($params->get('item_popup_display') == 1){
					echo '<div class="sj_vm_deals_popup sj_absolute">';
						if ($params->get('item_addtocart_display', 1) == 1) {
							$_item['product'] = $list[0]; 
							echo '<div class="item-addtocart">';
								echo shopFunctionsF::renderVmSubLayout('addtocart', $_item);
							echo '</div>';
						} 
						if ($params->get('item_quickview_display', 1) == 1) {
							echo '<div class="item-quick-view">';
								echo '<a href="'.$list[0]->link.'" title="'.$list[0]->title.'" '.VMDealsPHelper::parseTarget($params->get('link_target')).' style="display:none;"></a>';  
							echo '</div>';
						} 
					echo '</div>';
				}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		
		echo '<div class="sj_vm_deals_slide_product">';
	}
	echo '<div class="owl-carousel owl-theme">';
		foreach($list as $index => $item){
			if($params->get('item_first_product_display',1) == 1){
				if($index == 0)continue;
			}
			echo '<div class="item">';
				echo '<div class="sj_relative">';
				if($params->get('item_percentage_discount_display',1) == 1){
					echo '<div class="sj_percentage_discount_display sj_absolute">';
						$percent = (int)(((((int)$item->prices['product_price'] - (int)$item->prices['salesPrice']))/$item->prices['product_price'])*100);						
						echo $percent . ' %';
					echo '</div>';
				}
				echo '<div class="item-inner">';
					$item_img = VMDealsPHelper::getVmImage($item, $params, 'imgcfg');
					if ($item_img) {
						echo '<div class="item-image">
									<a href="'.$item->link.'"
										title="'.$item->title.'" '.VMDealsPHelper::parseTarget($params->get('link_target')).' >'.VMDealsPHelper::imageTag($item_img, $small_image_config).'
										<span class="image-border"></span>
									</a>
								</div>';
					}
					if($params->get('item_countdown_display') != 'POP'){
						if($item->prices['product_price_publish_down'] && $item->prices['product_price_publish_down'] != null){
							echo '<div class="item-deals" data-deals="'.$item->prices['product_price_publish_down'].'">';
							
							echo '</div>';
						}
					}
					
					if($params->get('item_rating_display', 1) == 1){
						if(!isset($item->rating->rating) || $item->rating->rating == null){
							$rating = 0;
						}else{
							$rating = $item->rating->rating;
						}
						echo '<div class="item-rating" data-rating="'.$rating.'">';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
							echo '<div class="item-star"><i class="fa fa-star"></i></div>';
						echo '</div>';
					}		
					if ($params->get('item_title_display', 1) == 1) { 
						echo '<div class="item-title">';
							echo '<a href="'.$item->link.'" title="'.$item->title.'" '.VMDealsPHelper::parseTarget($params->get('link_target')).' >';      
								echo VMDealsPHelper::truncate($item->title, (int)$params->get('item_title_max_characters', 25));
							echo '</a>';
						echo '</div>';
					} 
					if ((int)$params->get('item_prices_display', 1) && ( !empty($item->prices['salesPrice']) || !empty($item->prices['salesPriceWithDiscount'])) ) { 
						echo '<div class="item-prices">';
							echo '<div class="item-prices-override">';
							if (!empty($item->prices['salesPrice'])) {
								echo $currency->createPriceDiv('salesPrice', JText::_("Price: "), $item->prices, false, false, 1.0, true);
							}
							if (!empty($item->prices['salesPriceWithDiscount'])) {
								echo $currency->createPriceDiv('salesPriceWithDiscount', JText::_("Price: "), $item->prices, false, false, 1.0, true);
							} 
							echo '</div>';
							echo '<div class="item-prices-final">';
							$price = (int)$item->prices['product_price'] - (int)$item->prices['salesPrice'];
							if (!empty($item->prices['salesPrice'])) {
								echo $currency->createPriceDiv('salesPrice', JText::_("Price: "), $price, false, false, 1.0, true);
							}
							if (!empty($item->prices['salesPriceWithDiscount'])) {
								echo $currency->createPriceDiv('salesPriceWithDiscount', JText::_("Price: "), $price, false, false, 1.0, true);
							}
							echo '</div>';
						echo '</div>';
					}
				echo '</div>';
				if($params->get('item_popup_display') == 1){
					echo '<div class="sj_vm_deals_popup sj_absolute">';
						if ($params->get('item_addtocart_display', 1) == 1) {
							$_item['product'] = $item; 
							echo '<div class="item-addtocart">';
								echo shopFunctionsF::renderVmSubLayout('addtocart', $_item);
							echo '</div>';
						} 
						if ($params->get('item_quickview_display', 1) == 1) {
							echo '<div class="item-quick-view">';
								echo '<a href="'.$item->link.'" title="'.$item->title.'" '.VMDealsPHelper::parseTarget($params->get('link_target')).' style="display:none;"></a>';  
							echo '</div>';
						} 
					echo '</div>';
				}
			echo '</div>';
			echo '</div>';
			
		}
				
	echo '</div>';
	if($params->get('item_first_product_display',1) == 1){
		echo '</div>';
	}

?>

