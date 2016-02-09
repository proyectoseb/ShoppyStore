<?php
/**
 * @package Sj Filter for VirtueMart
 * @version 2.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined ('_JEXEC') or die;

?>

<div id="ft_results_<?php echo $this->_module->id; ?>" class="ft-results">
	<?php
	if (!empty($this->products)){
		//$path_template = JPATH_VM_SITE.DS.'views'.DS.'category'.DS.'tmpl'.DS.'default.php';
		$yt_temp = JFactory::getApplication()->getTemplate();
		$path_template = JPATH_BASE . '/templates/'.$yt_temp.'/html/com_virtuemart/category/default.php';
		if (file_exists ($path_template)){
			require ($path_template);
		}
	}
	else{
		echo 'No Results';
	}
	?>
</div>