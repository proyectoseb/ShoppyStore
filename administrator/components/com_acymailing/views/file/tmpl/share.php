<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content">
	<form action="index.php?tmpl=component&amp;option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm" id="adminForm" autocomplete="off">

		<div class="acyblockoptions">
			<?php acymailing_display(JText::_('SHARE_CONFIRMATION_1').'<br />'.JText::_('SHARE_CONFIRMATION_2').'<br />'.JText::_('SHARE_CONFIRMATION_3'), 'info'); ?><br/>
			<textarea rows="8" name="mailbody" style="width:620px;height: 100px;">Hi Acyba team,
Here is a new version of the language file, I translated few more strings...</textarea>
		</div>
		<div class="clr"></div>

		<input type="hidden" name="code" value="<?php echo $this->file->name; ?>"/>
		<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="ctrl" value="file"/>
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>
