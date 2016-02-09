<?php
/**
 * @package        Joomla.Site
 * @subpackage    mod_login
 * @copyright    Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>

<div class="yt-loginform">
    <div class="yt-login">

        <div id="myLogin" class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <h3 class="title"><?php echo JText::_('MOD_LOGIN'); ?>  </h3>
                    <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
                        <?php if ($params->get('pretext')): ?>
                            <div class="pretext">
                            <p><?php echo $params->get('pretext'); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="userdata">
                            <div id="form-login-username" class="form-group">
                                <label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
                                <input id="modlgn-username" type="text" name="username" class="inputbox"  size="40" />
                            </div>
                            <div id="form-login-password" class="form-group">
                                <label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
                                <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="40"  />
                            </div>

                            <div id="form-login-remember" class="form-group ">
                                <input id="modlgn-remember" type="checkbox" name="remember" value="1"/>
                                <label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
                            </div>


                            <div id="form-login-submit" class="control-group">
                                <div class="controls">
                                    <button type="submit" tabindex="3" name="Submit" class="button"><?php echo JText::_('JLOGIN') ?></button>
                                </div>
                            </div>

                            <input type="hidden" name="option" value="com_users" />
                            <input type="hidden" name="task" value="user.login" />
                            <input type="hidden" name="return" value="<?php echo $return; ?>" />
                            <?php echo JHtml::_('form.token'); ?>
                        </div>
                        <ul class="listinline listlogin">
                            <li>
                                <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                                <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                                <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
                            </li>

                        </ul>
                        <?php if ($params->get('posttext')): ?>
                            <div class="posttext">
                                <p><?php echo $params->get('posttext'); ?></p>
                            </div>
                        <?php endif; ?>

                    </form>

                    <a href="<?php echo JRoute::_("index.php?option=com_users&view=registration");?>" onclick="showBox('yt_register_box','jform_name',this, window.event || event);return false;" class="btReverse">Create an account</a>
                </div>
            </div>
        </div>
        <a data-toggle="modal" href="#myLogin" class="visible-xs" title="<?php echo JText::_('MOD_LOGIN');?>">
          <i class="icon-lock"></i>
          <?php echo JText::_('MOD_LOGIN');?>
        </a>
        <div class="hmb">
        <i class="icon-lock"></i>
        <a data-toggle="modal" href="#myLogin" title="<?php echo JText::_('MOD_LOGIN');?>">

          <?php echo JText::_('MOD_LOGIN');?>
        </a>
        <span><?php echo "or"; ?></span>
        <a href="<?php echo JRoute::_("index.php?option=com_users&view=registration");?>" title="<?php echo JText::_('MOD_LOGIN_REGISTER');?>">
        <?php echo JText::_('MOD_LOGIN_REGISTER');?>
        </a></div>

    </div>

</div>

