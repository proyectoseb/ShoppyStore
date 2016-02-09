<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="config_languages">
	<div class="onelineblockoptions">
		<span class="acyblocktitle"><?php echo JText::_('LANGUAGES') ?></span>
		<table class="acymailing_table" cellpadding="1">
			<thead>
			<tr>
				<th class="title titlenum">
					<?php echo JText::_('ACY_NUM'); ?>
				</th>
				<th class="title titletoggle">
					<?php echo JText::_('ACY_EDIT'); ?>
				</th>
				<th class="title">
					<?php echo JText::_('ACY_NAME'); ?>
				</th>
				<th class="title titletoggle">
					<?php echo JText::_('ACY_ID'); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$k = 0;

			for($i = 0, $a = count($this->languages); $i < $a; $i++){
				$row =& $this->languages[$i];
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center" style="text-align:center">
						<?php echo $i + 1; ?>
					</td>
					<td align="center" style="text-align:center">
						<?php echo $row->edit; ?>
					</td>
					<td>
						<?php echo $row->name; ?>
					</td>
					<td align="center" style="text-align:center">
						<?php echo $row->language; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
	</div>
</div>
