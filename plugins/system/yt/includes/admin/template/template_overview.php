<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die;
jimport('joomla.updater.update');
JHTML::_('behavior.modal'); 

// Get array Template info, Framework info
$t_filePath = JPath::clean(JPATH_ROOT.'/templates/'.$this->nameOfSJTemplate().'/templateDetails.xml');
$f_filePath = JPath::clean(JPATH_ROOT.'/'.YT_PLUGIN_REL_URL.'/'.YT_PLUGIN.'.xml');
if (is_file ($t_filePath)) {
    $t_xml = JInstaller::parseXMLInstallFile($t_filePath);
}
if (is_file ($f_filePath)) {
    $f_xml = JInstaller::parseXMLInstallFile($f_filePath);
}

// Template name
$t_name = $this->nameOfSJTemplate();
// Framework name
$f_name = YT_PLUGIN;

// Template has new version
$t_hasnew = false;

// Template curent version, template new version
$t_curVersion = $t_newVersion = $t_xml['version'];

// Framework has new version
$f_hasnew = false;

// Framework curent version, framework new version
$f_curVersion = $f_newVersion = $f_xml['version'];

// Get object template styles
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query
	->select('id, title')
	->from('#__template_styles')
	->where('template='. $db->quote($t_name));
$db->setQuery($query);
$styles = $db->loadObjectList();

// Check more for Template version, Framework version
$query = $db->getQuery(true);
$query
  ->select('*')
  ->from('#__updates')
  ->where('(element = ' . $db->q($t_name) . ') OR (element = ' . $db->q($f_name) . ')');
$db->setQuery($query);
$results = $db->loadObjectList('element');
//var_dump($results); die();
if(count($results)){
  if(isset($results[$t_name])){
    $t_hasnew = true;
    $t_newVersion = $results[$t_name]->version;
  }
  if(isset($results[$f_name])){
    $f_hasnew = true;
    $f_newVersion = $results[$f_name]->version;
  }
}
$hasperm = JFactory::getUser()->authorise('core.manage', 'com_installer');

?>
<div class="yt-template-desc row-fluid">
   
    <div class="temp-desc span7">
        <?php define('THEME_URL', JURI::root(true).'/templates/'.$t_xml['name'] );?>
		<div class="img-desc">
			<a href="<?php echo THEME_URL; ?>/template_preview.png" class="modal">
				<img class="img-polaroid pull-left" style="margin-right:15px;" src="<?php echo THEME_URL?>/template_thumbnail.png" alt=" " />
			</a>
			<!--<ul class="yt-social blank">
				<li><a title="facebook" target="_blank" href="https://www.facebook.com/SmartAddons.page"><span class="icon-facebook"></span></a></li>
				<li><a title="twitter" target="_blank" href="http://twitter.com/smartaddons"><span class="icon-twitter"></span></a></li>
				<li><a title="rss" target="_blank" href="/component/k2/itemlist?format=feed&amp;type=rss"><span class="icon-google-plus"></span></a></li>
				<li><a target="_blank" href="http://www.linkedin.com/in/smartaddons"><span title="linkedin" class="icon-linkedin"></span></a></li>
				<li><a target="_blank" href="http://www.linkedin.com/in/smartaddons"><span title="linkedin" class="icon-flickr"></span></a></li>
			</ul>-->
		</div>
		
		<?php echo $t_xml['description'] ?>
		
    </div>
	
	
    
	<div class="temp-overview span5">
		<ul class="yt-accordion">
		
			<!-- Template info -->
			<li class="accordion-group">
				<h3 class="accordion-heading"><i class="fa fa-plus-square-o"></i> Templates
					<?php if($t_hasnew) {?>
						<span class="label label-warning">Update</span>
					<?php } ?>
				</h3>
				<div class="accordion-inner">
					<div class="info">
						<dl>
							<dt><?php echo JText::_('Name')?>:</dt><dd><?php echo $t_xml['name'] ?></dd>
							<dt><?php echo JText::_('Version')?>:</dt><dd><?php echo $t_xml['version'] ?></dd>
							<dt><?php echo JText::_('Create date')?>:</dt><dd><?php echo $t_xml['creationDate'] ?></dd>
							<dt><?php echo JText::_('Author')?>:</dt><dd><a href="<?php echo $t_xml['authorUrl'] ?>" title="<?php echo $t_xml['author'] ?>"><?php echo $t_xml['author'] ?></a></dd>
						</dl>
					</div>
					<div class="update<?php echo ($t_hasnew)?' notice':'' ?>">
						<h4><?php  echo $t_hasnew ? JText::sprintf('TEMPLATE_NEW_HEADING', $t_xml['name']):JText::sprintf('TEMPLATE_HEADING', $t_xml['name'])?></h4>
						<p style="margin-bottom:1em;"><?php echo $t_hasnew ? JText::sprintf('TEMPLATE_NEW_MESSAGE', $t_xml['name'], $t_newVersion) : JText::sprintf('TEMPLATE_MESSAGE', $t_curVersion) ?></p>
						
						<?php if($hasperm): ?>
							<?php if($t_hasnew) { ?>
								<a class="button btn-success" href="<?php JURI::base() ?>index.php?option=com_installer&view=update" title="<?php echo JText::_( $t_hasnew ? 'GO_UPDATE' : 'CHECK_UPDATE') ?>">
									<i class="icon-download"></i>
									<?php echo JText::_( 'GO_UPDATE') ?>
								</a>
							<?php } ?>
							
							<span  id="popup-warning" class="button btn-warning" title="" data-content="<?php echo JText::sprintf('TEMPLATE_QUEST_DESC', $t_xml['name'], $t_xml['name'], $t_xml['name'], $t_xml['name'], $t_xml['name'], $t_xml['name'], $t_xml['name'], $t_xml['name'], $t_xml['name']); ?>" data-placement="top" data-toggle="popover" data-original-title="<?php echo JText::_('TEMPLATE_QUEST_LABEL'); ?>">
								<i class="fa fa-question-circle"></i><?php echo JText::_('Notice'); ?>
							</span>
							<script>
								jQuery(function ($){ 
									jQuery("#popup-warning").popover();
								});
							</script>
					  <?php endif; ?>
					</div>
					
				</div>
					
				
			</li>
			
			<!-- Framework info -->
			<li class="accordion-group">
				<h3 class="accordion-heading"><i class="fa fa-plus-square-o"></i> Framework 
					<?php if($f_hasnew) {?>
						<span class="label label-warning">Update</span>
					<?php } ?>
				</h3>
				<div class="accordion-inner">
					<div class="info">
						<dl>
							<dt><?php echo JText::_('Name')?>:</dt><dd><?php echo $f_xml['name'] ?></dd>
							<dt><?php echo JText::_('Version')?>:</dt><dd><?php echo $f_xml['version'] ?></dd>
							<dt><?php echo JText::_('Create date')?>:</dt><dd><?php echo $f_xml['creationDate'] ?></dd>
							<dt><?php echo JText::_('Author')?>:</dt><dd><a href="<?php echo $f_xml['authorUrl'] ?>" title="<?php echo $f_xml['author'] ?>"><?php echo $f_xml['author'] ?></a></dd>
						</dl>
					</div>
					<div class="update<?php echo ($f_hasnew)?' notice':'' ?>">
						<h4><?php echo $f_hasnew ? JText::sprintf('FRAMEWORK_NEW_HEADING', $f_xml['name']):JText::sprintf('FRAMEWORK_HEADING', $f_xml['name'])?></h4>
						<p style="margin-bottom:1em;"><?php echo $f_hasnew ? JText::sprintf('FRAMEWORK_NEW_MESSAGE', $f_xml['name'], $f_newVersion) : JText::sprintf('FRAMEWORK_MESSAGE', $f_curVersion) ?></p>
						
						<?php if($hasperm): ?>
							<?php if($f_hasnew) { ?>
								<a class="button btn-success" href="<?php JURI::base() ?>index.php?option=com_installer&view=update" title="<?php echo JText::_( $f_hasnew ? 'GO_UPDATE' : 'CHECK_UPDATE') ?>">
									<i class="fa fa-download"></i><?php echo JText::_( 'GO_UPDATE') ?>
								</a>
							<?php } ?>
					  <?php endif; ?>
					  
					</div>
				</div>
				
			</li>
			<li class="accordion-group">
				<!-- Documentation info -->
				<?php include_once 'documentation.html'; ?>
			</li>
		</ul>
		
	
		
	</div>
</div>

