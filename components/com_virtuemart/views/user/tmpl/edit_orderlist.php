<?php
/**
*
* User details, Orderlist
*
* @package	VirtueMart
* @subpackage User
* @author Oscar van Eijk
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id$
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access'); ?>

<div id="editcell">
	<table class="adminlist">
	<thead>
	<tr>
		<th>
			<?php echo vmText::_('COM_VIRTUEMART_ORDER_LIST_ORDER_NUMBER'); ?>
		</th>
		<th>
			<?php echo vmText::_('COM_VIRTUEMART_ORDER_LIST_CDATE'); ?>
		</th>
		<th>
			<?php echo vmText::_('COM_VIRTUEMART_ORDER_LIST_MDATE'); ?>
		</th>
		<th>
			<?php echo vmText::_('COM_VIRTUEMART_ORDER_LIST_STATUS'); ?>
		</th>
		<th>
			<?php echo vmText::_('COM_VIRTUEMART_ORDER_LIST_TOTAL'); ?>
		</th>
	</tr>
	</thead>
	<?php
		$k = 0;
		foreach ($this->orderlist as $i => $row) {
			$editlink = JRoute::_('index.php?option=com_virtuemart&view=orders&layout=details&order_number=' . $row->order_number);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="left">
					<a href="<?php echo $editlink; ?>" rel="nofollow"><?php echo $row->order_number; ?></a>
				</td>
				<td align="left">
					<?php echo JHtml::_('date', $row->created_on); ?>
				</td>
				<td align="left">
					<?php echo JHtml::_('date', $row->modified_on); ?>
				</td>
				<td align="left">
					<?php echo ShopFunctionsF::getOrderStatusName($row->order_status); ?>
				</td>
				<td align="left">
					<?php echo $this->currency->priceDisplay($row->order_total); ?>
				</td>
			</tr>
	<?php
			$k = 1 - $k;
		}
	?>
	</table>
</div>
