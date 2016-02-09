<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

?>
<dd class="create"><i class="fa fa-calendar"></i><?php echo JText::sprintf( JHtml::_('date', $displayData['item']->created, JText::_('DATE_FORMAT_LC3'))); ?></dd>