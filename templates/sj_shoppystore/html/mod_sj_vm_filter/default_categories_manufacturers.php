<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ('_JEXEC') or die;

foreach ($_group_cats_manus as $key => $cats_manuafs){
	$_cls_open_close = ((int)$params->get ('openform_'.$key,1))?' ft-open ':' ft-close ';
	if (!empty($cats_manuafs)){
		?>
		<div class=" ft-group <?php echo $_cls_open_close; ?> ft-group-<?php echo $key; ?>">
			<div class="ft-heading   ">
				<div class="ft-heading-inner">
					<?php echo $key; ?>
					<span class="ft-open-close"></span>
				</div>
			</div>

			<div class="ft-content ft-content-<?php echo $key; ?>">
				<ul class="ft-select">
					<?php
					foreach ($cats_manuafs as $cat_manu){
						if ($cat_manu->_countProduct > 0){
							?>
							<li class="ft-option ft-<?php echo $key; ?> "
							    data-catmanu="<?php echo $cat_manu->cat_manu_id; ?>">
								<label for="ft-<?php echo $key.'-'.$cat_manu->cat_manu_id.'-'.$module->id; ?>"
								       class="ft-opt-inner <?php echo $key.'-'.$cat_manu->cat_manu_id; ?>">
									<input type="checkbox" name="<?php echo $key; ?>[]"
									       value="<?php echo $cat_manu->cat_manu_id; ?>">
									<span class="ft-opt-name"><?php echo $cat_manu->cat_manu_name; ?></span>
									<?php
									if ((int)$params->get ('dsp_countproduct_'.$key,1)){
										?>
										<span
											class="ft-opt-count"><?php echo '('.$cat_manu->_countProduct.')'; ?></span>
									<?php
									} ?>
									<span class="ft-opt-close"></span>
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
}
?>