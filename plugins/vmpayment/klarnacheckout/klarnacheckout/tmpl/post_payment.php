<?php
defined ('_JEXEC') or die();
/**
 * @author Valérie Isaksen
 * @version $Id$
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 2004-Copyright (C) 2004-2015 Virtuemart Team. All rights reserved.   - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

?>
<div class="post_payment_order_number" style="width: 100%">
	<span class=post_payment_order_number_title"><?php echo vmText::_ ('COM_VIRTUEMART_ORDER_NUMBER'); ?> </span>
	<?php echo  $viewData['order']['details']['BT']->order_number; ?>
</div>
<div class="post_payment_transaction" style="width: 100%">
	<span class="post_payment_transaction_title"><?php echo vmText::_ ('VMPAYMENT_SOFORT_RESPONSE_TRANSACTION'); ?> </span>
	<?php echo  $viewData['paymentInfos']->sofort_response_transaction; ?>
</div>
<div class="post_payment_order_total" style="width: 100%">
	<span class="post_payment_order_total_title"><?php echo vmText::_ ('COM_VIRTUEMART_ORDER_PRINT_TOTAL'); ?> </span>
	<?php echo  $viewData['totalInPaymentCurrency']; ?>
</div>






