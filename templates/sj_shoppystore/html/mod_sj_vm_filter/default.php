<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ('_JEXEC') or die;

JHtml::stylesheet ('modules/'.$module->module.'/assets/css/styles.css');
JHtml::stylesheet ('modules/'.$module->module.'/assets/css/jquery-ui-1.9.2.custom.css');
JHtml::stylesheet ('modules/'.$module->module.'/assets/css/responsive.css');
if (!defined ('SMART_JQUERY') && $params->get ('include_jquery',0) == "1"){
	JHtml::script ('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
	JHtml::script ('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY',1);
}

JHtml::script ('modules/'.$module->module.'/assets/js/jquery-ui-1.9.2.custom.js');
vmJsApi::cssSite ();
$tag_id = 'sj_vm_filter_'.rand ().time ();
$module_id = $module->id;

?>

<?php if ($params->get ('pretext') != ''){ ?>
	<div class="sj-ft-pre-text"><?php echo $params->get ('pretext'); ?></div>
<?php } ?>


<div class="sj-vm-filter" id="<?php echo $tag_id; ?>">
	<div class="ft-wrap">
		<form class="ft-form">
			<input class="config-limit" name="limit" type="hidden" value=""/>
			<input class="config-limitstart" name="limitstart" type="hidden"/>
			<input class="config-orderby" name="orderby" type="hidden"/>
			<?php
			require JModuleHelper::getLayoutPath ($module->module,$layout.'_product_filter');

			$_group_cats_manus = $ft_helper->_getCategoriesManuafactures ();
			if (!empty($_group_cats_manus)){
				require JModuleHelper::getLayoutPath ($module->module,$layout.'_categories_manufacturers');
			}

			if ((int)$params->get ('display_prices',1)){
				require JModuleHelper::getLayoutPath ($module->module,$layout.'_prices');
			}

			$_group_other_fields = $ft_helper->_getCustomCartVariant ();
			if (!empty($_group_other_fields) && (int)$params->get ('display_customfields',1)){
				require JModuleHelper::getLayoutPath ($module->module,$layout.'_customfields');
			}
			?>
		</form>
	</div>
</div>

<?php if ($params->get ('posttext') != ''){ ?>
	<div class="sj-ft-posttext"><?php echo $params->get ('posttext'); ?></div>
<?php } ?>


