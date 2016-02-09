<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ('_JEXEC') or die;

$currency = CurrencyDisplay::getInstance ();
$symbol = $currency->getSymbol ();
$_cls_open_close = ((int)$params->get ('openform_prices',1))?' ft-open ':' ft-close ';
?>

<div class=" ft-group <?php echo $_cls_open_close; ?> ft-group-prices">
	<div class="ft-heading ">
		<div class="ft-heading-inner">
			<?php echo JText::_ ('PRICE'); ?>
			<span class="ft-open-close"></span>
		</div>
	</div>

	<div class="ft-content ft-content-prices">
		<ul class="ft-select">
			<li class="ft-option ft-prices ">
				<div class="ft-opt-inner">
					<span class="ft-price-value ft-price-left">
						<input type="text" maxlength="6" class="ft-price-min ft-price-input" name="ft_price_min"
						       value="<?php echo $params->get ('price_min'); ?>"/>
						<span class="ft-price-curent">
							<?php echo $symbol; ?>
						</span>
					</span>
					<span class="ft-price-label">to</span>
					<span class="ft-price-value ft-price-right">
						<input type="text" maxlength="6" class="ft-price-max ft-price-input" name="ft_price_max"
						       value="<?php echo $params->get ('price_max'); ?>"/>
						<span class="ft-price-curent">
							<?php echo $symbol; ?>
						</span>
					</span>
				</div>
				<div class="ft-slider-price"></div>
			</li>
		</ul>
		<button type="button" class="ft-opt-clearall">Clear All</button>
	</div>
</div>
