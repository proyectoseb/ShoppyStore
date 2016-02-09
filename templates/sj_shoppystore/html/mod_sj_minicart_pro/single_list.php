<?php


defined('_JEXEC') or die;
$image_config = array(
	'type' => $params->get('imgcfg_type'),
	'width' => $params->get('imgcfg_width'),
	'height' => $params->get('imgcfg_height'),
	'quality' => 90,
	'function' => ($params->get('imgcfg_function') == 'none') ? null : 'resize',
	'function_mode' => ($params->get('imgcfg_function') == 'none') ? null : substr($params->get('imgcfg_function'), 7),
	'transparency' => $params->get('imgcfg_transparency', 1) ? true : false,
	'background' => $params->get('imgcfg_background')
);
$vm_currency_display = CurrencyDisplay::getInstance();
$options = $params->toObject();
?>
<div class="mc-product-wrap">
	<?php foreach ($cart->products as $key => $product) { ?>
		<div class="mc-product " data-index="<?php echo $key; ?>" data-product-id="<?php echo $product->virtuemart_product_id; ?>" data-old-quantity="<?php echo $product->quantity ?>">
			<div class="mc-product-inner">
				<div class="mc-image">
					<?php $img = SjMiniCartProHelper::getVMPImage($product, $image_config); ?>
					<a href="<?php echo $product->link ?>"
					   title="<?php echo $product->product_name; ?>" <?php echo SjMiniCartProHelper::parseTarget($params->get('item_link_target')); ?>>
						<?php echo SjMiniCartProHelper::imageTag($img, $image_config); ?>
					</a>
				</div>
				<div class="mc-attribute">
					<div class="attr-name attr">
					<span class="label">
						<?php echo JText::_('PRODUCT_NAME_LABLE'); ?>
					</span>
					<span class="value">
						<a href="<?php echo $product->link ?>" <?php echo SjMiniCartProHelper::parseTarget($params->get('item_link_target')); ?>
						   title="<?php echo $product->product_name; ?>">
							<?php echo $product->product_name; ?>
						</a>
					</span>
					</div>
					<?php if ($options->product_attribute_display == 1) {
						$match_count = preg_match_all('#(\d+):(\d+);#', $product->virtuemart_product_id, $matchs);
						$cvp = array();
						if ($match_count) {
							for ($i = 0; $i < $match_count; $i++) {
								if (version_compare(vmVersion::$RELEASE, '2.0.7', 'lt')) {
									$cvp[$matchs[1][$i]] = $matchs[2][$i];
								} else {
									$cvp[$matchs[2][$i]] = $matchs[1][$i];
								}
							}
						}
						
						$customfields = $product->customfields;
						$customProductData = $product->customProductData;
						if(!empty($customfields) && !empty($customProductData)) {
							foreach($customfields as $key => $cutom){
								if(isset($customProductData[$cutom->virtuemart_custom_id]) && $customProductData[$cutom->virtuemart_custom_id] == $cutom->virtuemart_customfield_id ){
								?>	
									<div class="attr-<?php echo $cutom->custom_title;?> attr">
									 <span class="label">
											<?php echo ucfirst($cutom->custom_title);?> :
									 </span>

									<span class="value">
										<?php echo ucfirst($cutom->customfield_value); ?>
									</span>
									<span class="custom-field-price">
										<?php echo $vm_currency_display->priceDisplay($cutom->customfield_price); ?>
									</span>	
									</div>
								<?php	
								}
							}
						}
					} ?>
					<div class="attr-quantity attr">
					<span class="label">
						<?php echo JText::_('PRODUCT_QUANTITY_LABEL'); ?>
					</span>
					<span class="value">
						<input type="text" maxlength="4" size="2" name="mc-quantity" class="mc-quantity" value="<?php echo $product->quantity ?>"/>
					</span>
					<span class="quantity-control">
						<span class="quantity-plus"></span>
						<span class="quantity-minus"></span>
					</span>
					</div>
					<div class="attr-price attr">
					<span class="label">
						<?php echo JText::_('PRODUCT_PRICE_LABEL'); ?>
					</span>
					
					<span class="value">
					<?php
					echo $vm_currency_display->priceDisplay($product->prices['subtotal_with_tax']);
					?>

					</span>

					</div>
					<div class="mc-remove">
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php } ?>
</div>

	
	
