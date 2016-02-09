<?php
/**
 *TODO Improve the CSS , ADD CATCHA ?
 * Show the form Ask a Question
 *
 * @package	VirtueMart
 * @subpackage
 * @author Kohl Patrick, Maik K�nnemann
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2014 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
* @version $Id: default.php 2810 2011-03-02 19:08:24Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$min = VmConfig::get('asks_minimum_comment_length', 50);
$max = VmConfig::get('asks_maximum_comment_length', 2000) ;
vmJsApi::JvalideForm();
vmJsApi::addJScript('askform','
	jQuery(function($){
			$("#askform").validationEngine("attach");
			$("#comment").keyup( function () {
				var result = $(this).val();
					$("#counter").val( result.length );
			});
	});
');
/* Let's see if we found the product */
if (empty ( $this->product )) {
	echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_NOT_FOUND' );
	echo '<br /><br />  ' . $this->continue_link_html;
} else {
	$session = JFactory::getSession();
	$sessData = $session->get('askquestion', 0, 'vm');
	if(!empty($this->login)){
		echo $this->login;
	}
	if(empty($this->login) or VmConfig::get('recommend_unauth',false)){
		?>
		<div class="ask-a-question-view">
			<h1><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ASK_QUESTION')  ?></h1>

			<div class="product-summary">
				<div class="width70 floatleft">
					<h2><?php echo $this->product->product_name ?></h2>

					<?php // Product Short Description
					if (!empty($this->product->product_s_desc)) { ?>
						<div class="short-description">
							<?php echo $this->product->product_s_desc ?>
						</div>
					<?php } // Product Short Description END ?>

				</div>

				<div class="width30 floatleft center">
					<?php // Product Image
					echo $this->product->images[0]->displayMediaThumb('class="product-image"',false); ?>
				</div>

				<div class="clear"></div>
			</div>

			<div class="form-field">

				<form method="post" class="form-validate" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$this->product->virtuemart_product_id.'&virtuemart_category_id='.$this->product->virtuemart_category_id.'&tmpl=component', FALSE) ; ?>" name="askform" id="askform">

					<table class="askform">
						<tr>
							<td><label for="name"><?php echo vmText::_('COM_VIRTUEMART_USER_FORM_NAME')  ?> : </label></td>
							<td><input type="text" class="validate[required,minSize[3],maxSize[64]]" value="<?php echo $this->user->name ? $this->user->name : $sessData['name'] ?>" name="name" id="name" size="30"  validation="required name"/></td>
						</tr>
						<tr>
							<td><label for="email"><?php echo vmText::_('COM_VIRTUEMART_USER_FORM_EMAIL')  ?> : </label></td>
							<td><input type="text" class="validate[required,custom[email]]" value="<?php echo $this->user->email ? $this->user->email : $sessData['email'] ?>" name="email" id="email" size="30"  validation="required email"/></td>
						</tr>
						<tr>
							<td colspan="2"><label for="comment"><?php echo vmText::sprintf('COM_VIRTUEMART_ASK_COMMENT', $min, $max); ?></label></td>
						</tr>
						<tr>
							<td colspan="2"><textarea title="<?php echo vmText::sprintf('COM_VIRTUEMART_ASK_COMMENT', $min, $max) ?>" class="validate[required,minSize[<?php echo $min ?>],maxSize[<?php echo $max ?>]] field" id="comment" name="comment" rows="8"><?php echo $sessData['comment'] ?></textarea></td>
						</tr>
					</table>

					<div class="submit">
						<?php // captcha addition
						if(VmConfig::get ('ask_captcha')){
							JHTML::_('behavior.framework');
							JPluginHelper::importPlugin('captcha');
							$dispatcher = JDispatcher::getInstance(); $dispatcher->trigger('onInit','dynamic_recaptcha_1');
							?>
							<div id="dynamic_recaptcha_1"></div>
						<?php 
						}
						// end of captcha addition 
						?>
            <div>
              <div class="floatleft width50">
                <input class="highlight-button" type="submit" name="submit_ask" title="<?php echo vmText::_('COM_VIRTUEMART_ASK_SUBMIT')  ?>" value="<?php echo vmText::_('COM_VIRTUEMART_ASK_SUBMIT')  ?>" />
              </div>
              <div class="floatleft width50 text-right">
                <label for="counter"><?php echo vmText::_('COM_VIRTUEMART_ASK_COUNT')  ?></label>
  							<input type="text" value="0" size="4" class="counter" id="counter" name="counter" maxlength="4" readonly="readonly" />
              </div>
              <div class="clear"></div>
            </div>
					</div>

					<input type="hidden" name="virtuemart_product_id" value="<?php echo vRequest::getInt('virtuemart_product_id',0); ?>" />
					<input type="hidden" name="tmpl" value="component" />
					<input type="hidden" name="view" value="productdetails" />
					<input type="hidden" name="option" value="com_virtuemart" />
					<input type="hidden" name="virtuemart_category_id" value="<?php echo vRequest::getInt('virtuemart_category_id'); ?>" />
					<input type="hidden" name="task" value="mailAskquestion" />
					<?php echo JHTML::_( 'form.token' ); ?>
				</form>

			</div>
		</div>
<?php
	}
} ?>
