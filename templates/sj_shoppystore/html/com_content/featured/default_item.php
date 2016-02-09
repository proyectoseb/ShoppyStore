<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// includes placehold
$yt_temp = JFactory::getApplication()->getTemplate();
include (JPATH_BASE . '/templates/'.$yt_temp.'/includes/placehold.php');

// Create a shortcut for params.
$params = $this->item->params;
$images = json_decode($this->item->images);
$canEdit = $this->item->params->get('access-edit');
$info    = $this->item->params->get('info_block_position', 0);


global $leadingFlag;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();

?>

<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?> 


	
<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
    <?php
	// Begin:  The way to resize your image.
	$templateParams = JFactory::getApplication()->getTemplate(true)->params;
	YTTemplateUtils::getImageResizerHelper(array(
		'background' => $templateParams->get('thumbnail_background', '#000'), 
		'thumbnail_mode' => $templateParams->get('thumbnail_mode', 'fit')
		)
	);
	
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
		<a  class="listingimg" title="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>" >
			<?php echo $thumb_img; ?>
		</a>
		
    </figure>
<?php endif; ?>
	
	
<div class="article-text">
	<?php if ($params->get('show_title')) : ?>
		<header class="article-header">
			<h3>
			<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
				<?php echo $this->escape($this->item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
			<?php endif; ?>
			</h3>
		</header>
	<?php endif; ?>
	
	<?php if (!$params->get('show_intro')) : ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php endif; ?>
	
	<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit || 
		  $params->get('show_author') || $params->get('show_category') || $params->get('show_create_date') || 
		  $params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_parent_category') || 
		  $params->get('show_hits') ) { ?>
		<aside class="article-aside">

		<?php // to do not that elegant would be nice to group the params ?>

		<?php if (($params->get('show_author')) or ($params->get('show_category')) or ($params->get('show_create_date')) or ($params->get('show_modify_date')) or ($params->get('show_publish_date')) or ($params->get('show_parent_category')) or ($params->get('show_hits'))) : ?>
			<dl class="article-info  muted">
				<?php if ($params->get('show_create_date')) : ?>
				<dt></dt>
						<dd class="create">
							<i class="fa fa-calendar"></i>
							<time datetime="<?php echo JHtml::_('date', $this->item->created, 'c'); ?>" >
								<?php echo JText::sprintf(JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
							</time>
						</dd>
					<?php endif; ?>
				<?php if ($params->get('show_publish_date')) : ?>
				
					<dd class="published">
						<i class="fa fa-calendar"></i>
						<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'c'); ?>" >
							<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
						</time>
					</dd>
				<?php endif; ?>

			
				<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
				
					<dd class="parent-category-name">
						<?php $title = $this->escape($this->item->parent_title); ?>
						<?php if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) : ?>
							<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '" >' . $title . '</a>'; ?>
							<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
						<?php else : ?>
							<?php echo JText::sprintf('COM_CONTENT_PARENT', '<span itemprop="genre">' . $title . '</span>'); ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>

				
				<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
					
					<dd class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
						<i class="fa fa-user"></i>
						<?php $author = ($this->item->created_by_alias) ? $this->item->created_by_alias : $this->item->author; ?>
						<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
						<?php if (!empty($this->item->contact_link) && $params->get('link_author') == true) : ?>
							<?php echo JText::sprintf( JHtml::_('link', $this->item->contact_link, $author, array('itemprop' => 'url'))); ?>
						<?php else: ?>
							<?php echo JText::sprintf( $author); ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>

				<?php if ($params->get('show_category')) : ?>
					
					<dd class="category-name">
						<?php $title = $this->escape($this->item->category_title); ?>
						<?php if ($params->get('link_category') && $this->item->catslug) : ?>
							<?php $url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '" >' . $title . '</a>'; ?>
							<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
						<?php else : ?>
							<?php echo JText::sprintf('COM_CONTENT_CATEGORY', '<span >' . $title . '</span>'); ?>
						<?php endif; ?>
					</dd>
				<?php endif; ?>

				<?php if ($info == 0) : ?>
					<?php if ($params->get('show_modify_date')) : ?>
						<dd class="modified">
							<i class="fa fa-calendar"></i>
							<time datetime="<?php echo JHtml::_('date', $this->item->modified, 'c'); ?>" >
								<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
							</time>
						</dd>
					<?php endif; ?>

					

					<?php if ($params->get('show_hits')) : ?>
					
						<dd class="hits">
							<i class="fa fa-bar-chart"></i>
							
							<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
						</dd>
					<?php endif; ?>

				<?php endif; ?>
			</dl>
		<?php endif; ?>
			
		</aside>
		<?php } ?>
		
	<?php echo $this->item->event->beforeDisplayContent; ?>
	
	<?php if ($params->get('show_intro')) : ?>
		<div class="article-intro">
			<?php echo $this->item->introtext; ?>
		</div>
	<?php endif; ?>
    
<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif;
?>
			
			<a class="button" href="<?php echo $link; ?>">
			
				<?php if (!$params->get('access-view')) :
					echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
				elseif ($readmore = $this->item->alternative_readmore) :
					echo $readmore;
					if ($params->get('show_readmore_title', 0) != 0) :
					    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
					endif;
				elseif ($params->get('show_readmore_title', 0) == 0) :
					echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');	
				else :
					echo JText::_('COM_CONTENT_READ_MORE');
					echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
				endif; ?></a>
				
			
			<?php if ($this->params->get('show_tags', 1)) : ?>
			<div class="item-tags clearfix">
				<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
				<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
			</div>
			<?php endif; ?>
			
<?php endif; ?>

<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>
</div>
<?php

?>
<?php echo $this->item->event->afterDisplayContent; ?>
