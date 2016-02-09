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
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$user = JFactory::getUser();
$canDo = version_compare( JVERSION, '3.2.2', 'ge' ) ?  JHelperContent::getActions('com_templates'): TemplatesHelper::getActions();

// Form template
$form = JForm::getInstance('com_templates.style', 'style', array('control' => 'jform', 'load_data' => true));


// Get cookie of backend tab param
$tabcookie = (isset($_COOKIE[$this->nameOfSJTemplate().'_backend_tab']) && $_COOKIE[$this->nameOfSJTemplate().'_backend_tab']!="")?$_COOKIE[$this->nameOfSJTemplate().'_backend_tab']:'overview';

?>
<div class="yt-templatebackend yt-jv<?php echo J_VERSION; ?>">
    <form action="<?php echo JRoute::_('index.php?option=com_templates&layout=edit&id='.JRequest::getInt('id')); ?>" method="post" name="adminForm" id="style-form" class="form-validate">
		
    	<div class="yt-nav-template clearfix">
			<ul class="nav nav-tabs">
				
				<li class="collapse-menu"><a href="#"><?php echo JText::_('COLLAPSE_MENU_TAB_LB');?> </a></li>
            	
				<!-- Tab: Overview -->
                <li <?php echo $tabcookie == 'overview' ? ' class="active"' : ''?>><a href="#overview_params" data-toggle="tab"><?php echo JText::_('OVERVIEW_TAB_LB');?></a></li>
                <!-- Tabs: templateDetail.xml -->
				
				<?php
				/* fieldsets of templateDetail.xml */
				$fieldSets = $form->getFieldsets('params');
				foreach ($fieldSets as $name => $fieldSet) :
					$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_TEMPLATES_'.$name.'_FIELDSET_LABEL';
				?>
					<li <?php echo ($tabcookie == $name) ? ' class="active"' : ''?>><a href="#<?php echo preg_replace( '/\s+/', ' ', $name);?>_params" data-toggle="tab"> <?php echo JText::_($label) ?></a></li>
				<?php
				endforeach;
				?>
                <!-- Tab: menu assignment-->
                <?php if ($user->authorise('core.edit', 'com_menu') && ($form->getValue('client_id') == 0)):?>
					<?php if ($canDo->get('core.edit.state')) : ?>
                            <li<?php echo $tabcookie == 'assignment' ? ' class="active"' : ''?>><a href="#assignment_params" data-toggle="tab"><?php echo JText::_('MENUS_ASSIGNMENT_TAB_LB');?></a></li>
                    <?php endif; ?>
                <?php endif;?>
            </ul>
			
			<div class="form-inline form-inline-header">
				<div class="control-group ">
					<div class="control-label">
						<?php echo $form->getLabel('title'); ?>
					</div>
					<div class="controls">
						<?php echo $form->getInput('title'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $form->getLabel('template'); ?>
					</div>
					<div class="controls">
						<?php echo $form->getInput('template'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo $form->getLabel('home'); ?>
					</div>
					<div class="controls">
						<?php echo $form->getInput('home'); ?>
					</div>
				</div>
				<div class="control-group" style="display:none">
					<?php echo $form->getInput('client_id'); ?>
					<input type="text" size="35" value="<?php echo $form->getValue('client_id') == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>	" class="inputbox readonly" readonly="readonly" />
				</div>
			</div>
			
            <div class="tab-content clearfix">
            	<!-- Tab content: Overview -->
                <div class="tab-pane tab-overview clearfix<?php echo $tabcookie == 'overview' ? ' active' : ''?>" id="overview_params">
                     <?php include_once 'template_overview.php'; ?>  
                </div>
				
                <!-- Tabs content: templateDetail.xml -->
                <?php
				foreach ($fieldSets as $name => $fieldSet) :
					?>
					<div class="tab-pane<?php echo ($tabcookie == $name) ? ' active' : ''?>" id="<?php echo preg_replace( '/\s+/', ' ', $name); ?>_params">
						<?php
						if (isset($fieldSet->description) && trim($fieldSet->description)) :
							echo '<div class="fieldset-desc"><span class="icon-info-2"></span>  '.(JText::_($fieldSet->description)).'</div>';
						endif;
						$i=0;
						foreach ($form->getFieldset($name) as $field) :
						if ($field->type == 'depend') {
							echo $field->type=='Text'?$textinput:$field->input;
						}elseif($field->type == 'YtFieldset'){
							$i=0;
							echo $field->type=='Text'?$textinput:$field->input;
						}else{
							if ($field->type == 'Text') {
								// add placeholder to text input
								$textinput = str_replace ('/>', ' placeholder="'.$form->getFieldAttribute($field->fieldname, 'default', '', $field->group).'"/>', $field->input);
							}
							$i++;
							?>
							<div class="control-group row-fluid <?php echo 'row'.$i%2; ?>">
								<div class="control-label span2">
									<?php echo $field->label; ?>
								</div>
								<div class="controls span10">
									<?php echo $field->type=='Text'?$textinput:$field->input ?>
								</div>
							
							</div>
                    	<?php } ?>
					<?php endforeach; ?>
					</div>
				<?php endforeach;  ?>
				
				<!-- Tab content: menu assignment-->
                <?php if ($user->authorise('core.edit', 'com_menu') && $form->getValue('client_id') == 0):?>
					<?php if ($canDo->get('core.edit.state')) : ?>
                        <div class="tab-pane clearfix<?php echo $tabcookie == 'assignment' ? ' active' : ''?>" id="assignment_params">
                            <?php include_once 'template_assignment.php'; ?>
                        </div>
                    <?php endif; ?>
                <?php endif;?>
            </div>
			
        </div>
        <input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
    </form>
</div>