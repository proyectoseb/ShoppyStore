<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ('_JEXEC') or die;
$_style = (isset($list) && !empty($list))?'':'display:none;';
?>
<div class="ft-group ft-open ft-filtering" style="<?php echo $_style; ?>">
	<div class="ft-heading   ">
		<div class="ft-heading-inner">
			<?php echo JText::_ ('PRODUCTFILTERING'); ?>
		</div>
	</div>

	<div class="ft-content ">
		<?php
		if (isset($list) && !empty($list)){
			?>
			<div class="ft-current-label">
				<?php echo JText::_ ('CURRENTSEARCH'); ?>
			</div>
			<?php
			foreach ($list as $key => $fts){
				?>
				<div class="ft-current-group">
					<?php echo ucwords ($key); ?>
				</div>
				<div class="ft-content-inner">
					<ul class="ft-select">
						<?php
						foreach ($fts as $ft){
							?>
							<li class="ft-option">
								<?php if ($key != 'prices'){ ?>
									<label class="ft-opt-inner "
									       data-filter="<?php echo isset($ft->name_replace)?'custom-id-'.$ft->cat_manu_id.'-'
										       .$ft->name_replace:$key.'-'.$ft->cat_manu_id; ?>">
										<span class="ft-opt-name"><?php echo $ft->cat_manu_name; ?></span>
										<span class="ft-opt-close"></span>
									</label>
								<?php }
								else{ ?>
									<label class="ft-opt-inner " data-filter="<?php echo $ft['cls']; ?>">
										<span class="ft-opt-name"><?php echo $ft['value']; ?></span>
										<span class="ft-opt-close"></span>
									</label>
								<?php } ?>
							</li>
						<?php
						} ?>
					</ul>
				</div>
			<?php
			} ?>
			<button type="button" class="ft-opt-clearall ft-filtering-clearall">Clear All</button>
		<?php
		}
		?>
	</div>
</div>
