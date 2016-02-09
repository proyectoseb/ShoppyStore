<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// includes placehold
$yt_temp = JFactory::getApplication()->getTemplate();
include (JPATH_BASE . '/templates/'.$yt_temp.'/includes/placehold.php');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

// Get the user object.
$user = JFactory::getUser();

// Begin: dungnv added
global $leadingFlag;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
// End: dungnv added

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
$items = $this->items;
$n = count($this->items);

?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="row">
	<?php if ( $this->params->get('filter_field') !== '0' || $this->params->get('show_pagination_limit')) :?>
	<fieldset class="filters btn-toolbar">
		<?php if ($this->params->get('filter_field') != 'hide') :?>
			<div class="btn-group">
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>" />
			</div>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="btn-group pull-right">
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>

		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
		<input type="hidden" name="task" value="" />
		<div class="clearfix"></div>
	</fieldset>
	<?php endif; ?>

	<?php if ($this->items == false || $n == 0) : ?>
		<p> <?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php else : ?>

	<ul class="blank items-row">
		<?php foreach ($items as $i => $item) : ?>
				<li class="item col-sm-12">
					
					
					<?php $images  = json_decode($item->core_images);?>
					<?php if ($this->params->get('tag_list_show_item_image', 1) == 1 && !empty($images->image_intro)) :?>
							<?php
							$imgfloat = (empty($images->float_intro)) ?  'none' :$images->float_intro; 
							$imgW = (isset($leadingFlag) && $leadingFlag)?$templateParams->get('leading_width', '300'):$templateParams->get('intro_width', '200');
							$imgH = (isset($leadingFlag) && $leadingFlag)?$templateParams->get('leading_height', '300'):$templateParams->get('intro_height', '200');
							$imgsrc = YTTemplateUtils::resize($images->image_intro, $imgW, $imgH);
							
							//Create placeholder items images
							$src = $images->image_intro;
							if (file_exists(JPATH_BASE . '/' . $src) || strpos($src,'http://')!== false) {		
								
								$thumb_img = '<img src="'.$imgsrc.'" alt="'.$images->image_intro_alt.'" />';
							} else if ($is_placehold) {					
								$thumb_img = yt_placehold($placehold_size['listing']);
							}	
							?>
							<figure class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image" >
									<?php echo $thumb_img; ?>
							</figure>
						
					<?php endif; ?>
					
					<div class="article-text">
						
						<header class="article-header">
							<h2>
								<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
									<?php echo $this->escape($item->core_title); ?>
								</a>
							</h2>
						</header>
						<aside class="article-aside">
							<dl class="article-info  muted">
								<dd class="create"><i class="fa fa-clock-o"></i><?php echo JText::sprintf( JHTML::_('date',$item->tag_date, 'M d Y')); ?></dd>
							</dl>
						</aside>
						<?php if ($this->params->get('all_tags_show_tag_descripion', 1)) : ?>
							<div class="article-intro">
								<?php echo JHtml::_('string.truncate', $item->core_body, $this->params->get('tag_list_item_maximum_characters')); ?>
							</div>
						
						<?php endif; ?>
						
						
					</div>
		
				</li>
			
		<?php endforeach; ?>
	</ul>

	
</form>

<?php endif; ?>
