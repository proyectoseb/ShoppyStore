<?php
/**
* @copyright Copyright (C) 2008 JoomlaPraise. All rights reserved.
*/

// no direct access
defined('_JEXEC') or die;

// getting document object
$doc = JFactory::getDocument();

// Check for the print page
$print = JRequest::getCmd('print');

// Check for the mail page
$mailto = JRequest::getCmd('option') == 'com_mailto';

$params = JFactory::getApplication()->getTemplate(true)->params;
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>">
<head>

	
	<jdoc:include type="head" />
	
	<?php if($mailto == true) : ?>     
		<?php $this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/mail.css'); ?>
	<?php endif; ?>
	
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/general.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/system.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/editor.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/protostar/css/template.css" type="text/css" />
	
	<?php $this->addScript(JURI::base() . 'templates/' . $this->template . '/asset/bootstrap/js/bootstrap.min.js'); ?>
	
	<?php if($print == 1) : ?>     
		<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/print.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/template-red.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/asset/fonts/awesome/css/font-awesome.min.css" type="text/css"/>
	<?php endif; ?>
</head>
<body class="contentpane">
	<?php 
		if($print == 1) : 
			$logo_text = $params->get('logoText', '') != '' ? $params->get('logoText', '') : $params->getPageName();
			$logo_slogan = $params->get('sloganText', '');
	?>    
	<div id="print-top">
		<img src="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/images/logo_print.png" alt="<?php echo $logo_text . ' - ' . $logo_slogan; ?>" />
	</div>
	<?php endif; ?>
	
	<jdoc:include type="message" />
	<jdoc:include type="component" />
	
	
	<?php if($print == 1) : ?>     
	<div id="print-bottom">
		<?php if($params->get('ytcopyright', '') == '') : ?>
			&copy; Smartaddons - <a href="http://smartaddons.com/" title="Free Joomla! 3.0 Template">Beautiful Joomla! and WordPress Themes</a> <?php echo date('Y');?>
		<?php else : ?>
			<?php 
				$ytcopyright =  $params->get('ytcopyright', '');
				$ytcopyright = str_replace('{year}', date('Y'), $ytcopyright);
				echo $ytcopyright; 
			?>
		<?php endif; ?> 
	</div>
	<?php endif; ?>
	
</body>
</html>
