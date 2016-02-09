<?php

/**
 *
 * offline view
 *
 * @version             1.0.0
 * @package             Gavern Framework
 * @copyright			Copyright (C) 2010 - 2011 GavickPro. All rights reserved.
 *               
 */
 
// No direct access.
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$uri = JURI::getInstance();
jimport('joomla.factory');


// get necessary template parameters
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
$pageName = JFactory::getDocument()->getTitle();

// get logo configuration
$logo_image = $templateParams->get('overrideLogoImage');
$themecolor	= $templateParams->get('themecolor')

?>
<!DOCTYPE html>
<html>
<head>
	<jdoc:include type="head" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'/>
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/asset/bootstrap/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/offline.css" type="text/css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div id="yt-Page">
	    <div id="yt-PageTop">
		     <?php 
			 if (!$app->getCfg('offline_image')) :  
				if($logo_image!=''):
					$url = JURI::base().$logo_image;
				else:
					if(is_file('templates/'.$this->template.'/images/styling/'.$themecolor.'/logo.png')){
						$url = JURI::base()."templates/".$this->template.'/images/styling/'.$themecolor.'/logo.png';
					}
				endif;
			?>
					<a class="logo" href="" title="<?php echo $app->getCfg('sitename'); ?>">
						<img src="<?php echo $url; ?>" alt="<?php echo $app->getCfg('sitename'); ?>"   />
					</a>
					<h3 class="text-logo"><?php echo $app->getCfg('sitename'); ?></h3>
			<?php else : ?>
                      <a href="./" class="logo">
                            <img src="<?php echo $app->getCfg('offline_image'); ?>" alt="<?php echo $app->getCfg('sitename'); ?>" />
                       </a>
             <?php endif; ?>
			 
			
        </div>
		<div id="yt-PageWrap">     
		    
		      
		     <div id="frame">
		            <p class="login-text"><?php echo $app->getCfg('offline_message'); ?></p>
					 <jdoc:include type="message" />
		            <div class="login-panel panel panel-default">
						<div class="panel-heading">
								<h3 class="panel-title"><?php echo JText::_('JGLOBAL_SIGNIN') ?></h3>
						</div>
		                  <div class="panel-body">
								<form action="index.php" method="post" name="login" id="form-login">
									  <fieldset >
											<div class="form-group">
												  <input name="username" id="username" type="text" class="form-control" placeholder="<?php echo JText::_('JGLOBAL_USERNAME') ?>"  />
											</div>
											<div class="form-group">
												  <input type="password" name="password" class="form-control" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" id="passwd" />
											</div>
											
											
											<div class="buttons">
												  <input type="submit" name="Submit" class="btn btn-sm btn-success" value="<?php echo JText::_('JLOGIN') ?>" />
											</div>
											<input type="hidden" name="option" value="com_users" />
											<input type="hidden" name="task" value="user.login" />
											<input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
											<?php echo JHtml::_('form.token'); ?>
									  </fieldset>
								</form>
						  </div>
		           
					</div>
		      </div>
	      </div>
	</div>
</body>
</html>