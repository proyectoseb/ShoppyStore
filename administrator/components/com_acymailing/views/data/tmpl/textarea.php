<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.0.1
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><textarea style="width:99%;height:180px;" rows="10" name="textareaentries">
<?php $text = JRequest::getString("textareaentries");
if(empty($text)){ ?>
name,email
Adrien,adrien@example.com
John,john@example.com
<?php }else{
	echo $text;
} ?>
</textarea>
