<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ('_JEXEC') or die;

foreach ($_group_other_fields as $key => $_grp_others){
	$group_name = strtolower ($key);
	$group_name = preg_replace ('/\s+/','-',$group_name);
	$_cls_open_close = ((int)$params->get ('openform_custom',1))?' ft-open ':' ft-close ';
	if (strpos ($group_name,'color') !== false){
		$group_name = 'color';
	}

	?>
	<div class=" ft-group <?php echo $_cls_open_close; ?> ft-group-<?php echo $group_name; ?>">
		<div class="ft-heading  ">
			<div class="ft-heading-inner">
				<?php echo $key; ?>
				<span class="ft-open-close"></span>
			</div>
		</div>

		<div class="ft-content ft-content-<?php echo $group_name; ?>">
			<ul class="ft-select cf">
				<?php
				foreach ($_grp_others as $_grp_other){
					if ($_grp_other->_countProduct > 0){
						?>
						<li class="ft-option ft-<?php echo $group_name; ?> ">
							<label
								for="ft-<?php echo $group_name.'-'.$_grp_other->virtuemart_custom_id.'-'.$module->id; ?>"
								class="ft-opt-inner custom-id-<?php echo $_grp_other->virtuemart_custom_id ?>-<?php echo $_grp_other->name_replace; ?>">
								<input type="checkbox"
								       name="custom_id[<?php echo $_grp_other->virtuemart_custom_id ?>][]"
								       value="<?php echo $_grp_other->cat_manu_name; ?>">
								<?php
								if (strpos ($group_name,'color') !== false){
									?>
									<span style="background-color: <?php echo $_grp_other->cat_manu_name; ?>"
									      class="ft-color-value">
										<?php if ((int)$params->get ('dsp_countproduct_custom',1)){ ?>
											<span class="ft-opt-count"><?php echo '('.$_grp_other->_countProduct.')'; ?></span>
										<?php } ?>
									</span>
								<?php
								}
								else{
									?>
									<span class="ft-opt-name"><?php echo $_grp_other->cat_manu_name; ?></span>
									<?php if ((int)$params->get ('dsp_countproduct_custom',1)){ ?>
										<span class="ft-opt-count"><?php echo '('.$_grp_other->_countProduct.')'; ?></span>
									<?php } ?>
									<span class="ft-opt-close"></span>
								<?php
								}
								?>
							</label>
						</li>

					<?php
					}
				}
				?>
			</ul>
			<button type="button" class="ft-opt-clearall">Clear All</button>
		</div>

	</div>
<?php
}
?>