<?php
/**
 *
 * Paypal payment plugin
 *
 * @author Valerie Isaksen
 * @version $Id: paypal.php 7217 2013-09-18 13:42:54Z alatak $
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
?>




<div>
    <?php
    if ($viewData['sandbox'] ) {
        ?>
		<span style="color:red;font-weight:bold">Sandbox (<?php echo $viewData['virtuemart_paymentmethod_id'] ?>)</span>
    <?php
    }
    ?>
<a href="<?php echo $viewData['link'] ?>" title="<?php echo $viewData['text'] ?>">
    <img src="<?php echo $viewData['img'] ?>" align="left"   alt="<?php echo $viewData['text']?>" title="<?php echo $viewData['text']?>"  >
</a>



    </div>
<div class="clear"></div>