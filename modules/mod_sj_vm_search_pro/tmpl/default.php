<?php
/**
 * @package SJ Search Pro for VirtueMart
 * @version 3.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2015 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */

defined('_JEXEC') or die;

JHtml::stylesheet('modules/' . $module->module . '/assets/css/styles.css');

if (!defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1") {
	JHtml::script('modules/' . $module->module . '/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/' . $module->module . '/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
$tag_id = 'sj_vm_search_pro_' . rand() . time();
$module_id = $module->id;
?>

<?php if ($params->get('pretext') != '') { ?>
	<div class="sj-searchpro-pre-text"><?php echo $params->get('pretext'); ?></div>
<?php } ?>

<form method="get" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0'); ?>">
<div id="sj-search-pro<?php echo $module->id?>" class="sj-search-pro-wrapper <?php echo $params->get('moduleclass_sfx'); ?>">
	<div id="search<?php echo $module->id?>" class="search input-group">
	    <?php if(!empty($categories)) { ?>
		<div class="select_category filter_type  icon-select">
			<select class="no-border" name="virtuemart_category_id">
				<option value="0"><?php echo JText::_('ALLCATEGORY');?></option>
				<?php foreach ($categories as $category) {?>
				<option value="<?php echo $category->virtuemart_category_id; ?>" <?php if($category->virtuemart_category_id == $category_id) echo "selected='selected'";?>>
					<?php
						 $multiplier = $category->level - 1;
						 $indent = $multiplier ? str_repeat('- - ', $multiplier) : ''; 
						 $text = $indent . $category->category_name;
						echo $text; ?>
				</option>
		        <?php } ?>
			</select>
		</div>
		<?php } ?>	
		<div class="sj-seach-autocomplete">
			<input class="autosearch-input form-control" type="text" size="50" autocomplete="off" placeholder="<?php echo JText::_('SEARCH');?>" name="keyword" value="<?php echo $search_name; ?>">
			<span class="input-group-btn">
				<button type="submit" class="button-search btn btn-default btn-lg" name="submit_search"><i class="icon-search"></i></button>
			</span>
		</div>
	</div>
		<input type="hidden" name="limitstart" value="0" />
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="view" value="category" />
</div>
</form>
<?php if ($params->get('posttext') != '') { ?>
	<div class="sj-ft-posttext"><?php echo $params->get('posttext'); ?></div>
<?php } ?>


