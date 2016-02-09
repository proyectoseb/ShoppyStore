<?php
/**
 *
 * Paybox payment plugin
 *
 * @author Valerie Isaksen
 * @version $Id$
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2004-2015 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
defined('_JEXEC') or die();
vmJsApi::css(  'paybox','plugins/vmpayment/paybox/paybox/assets/css/');

?>

<div class="paybox response">


	<?php if ( $viewData['success']) { ?>
		<div class="status_confirmed">
			<?php echo vmText::sprintf('VMPAYMENT_'.$this->_name.'_PAYMENT_STATUS_CONFIRMED', $viewData['amount']." ".$viewData['currency'],  $viewData["order_number"]); ?>
		</div>
		<div class="transaction_id">
			<?php echo vmText::_('VMPAYMENT_'.$this->_name.'_RESPONSE_S') . ' ' .$viewData['transactionId'];
			?>
		</div>
<?php if ( !empty($viewData['extra_comment']))  { ?>
<div class="extra_comment">
			<?php echo $viewData['extra_comment'];
			?>
</div>
	<?php
}
	?>
		<div class="vieworder">
			<a class="vm-button-correct" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number=' . $viewData["order_number"] . '&order_pass=' . $viewData["order_pass"], false) ?>"><?php echo vmText::_('COM_VIRTUEMART_ORDER_VIEW_ORDER'); ?></a>
		</div>
	<?php } else { ?>
		<div class="">
			<span class=""><?php echo $viewData['not_success']; ?></span>
		</div>
	<?php } ?>
</div>