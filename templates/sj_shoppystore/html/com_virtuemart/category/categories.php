<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if ($this->category->haschildren) {

	// Calculating Categories Per Row
	//$categories_per_row = VmConfig::get ( 'categories_per_row', 3 );

	// Start the Output
	echo ShopFunctionsF::renderVmSubLayout('categories',array('categories'=> $this->category->children));

} ?>