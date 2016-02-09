<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2014 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="cpanel_wrapper" style="direction:ltr" class="hidden-xs">
    <div class="accordion" id="ytcpanel_accordion">
        <div class="cpanel-head">
            Template Settings
            <div class="cpanel-reset">
                <a class="btn btn-info" href="#" onclick="javascript: onCPResetDefault(TMPL_COOKIE);" ><i class="fa fa-refresh fa-spin"></i> Reset</a>
            </div>
        </div>

        <!--Body-->
        <div class="cpanel-theme">
            <!-- Style Color -->
            <div class="panel-group">
                <h4 class="panel-heading">Theme Colors</h4>

                <div class="panel-theme-color">
                    <span data-placement="left" title="Blue"     class="theme-color blue<?php echo ($templateColor=='blue')?' active':'';?>">Blue</span>
                    <span data-placement="top" title="Purple"      class="theme-color purple<?php echo ($templateColor=='purple')?' active':'';?>">Purple</span>
                    <span data-placement="top" title="Green"    class="theme-color green<?php echo ($templateColor=='green')?' active':'';?>">Green</span>
                    <span data-placement="top" title="Orange"  class="theme-color orange<?php echo ($templateColor=='orange')?' active':'';?>">Orange</span>
                    <span data-placement="top" title="Red"   class="theme-color red<?php echo ($templateColor=='red')?' active':'';?>">Red</span>
                    <span data-placement="top" title="Pink"   class="theme-color pink<?php echo ($templateColor=='pink')?' active':'';?>">Pink</span>
                </div>
            </div>

            <!-- Layouts -->
            <div class="panel-group visible-lg">
                <h4 class="panel-heading">Layout</h4>
                <!-- Layout Style -->
                <div class="panel-layout typeLayout">
                    <span data-value="layout-wide"  class="layout-item btn <?php echo ($typelayout=='wide')?' active':'';?>">Wide</span>
                    <span data-value="layout-boxed" class="layout-item btn <?php echo ($typelayout=='boxed')?' active':'';?>">Boxed</span>
                    <span data-value="layout-framed"  class="layout-item btn <?php echo ($typelayout=='framed')?' active':'';?>">Framed</span>
                    <span data-value="layout-rounded" class="layout-item btn <?php echo ($typelayout=='rounded')?' active':'';?>">Rounded</span>
                </div>
            </div>

            <div class="panel-group nomarginbottom visible-lg">
                <div class="panel-desc" style="margin:10px 0 3px;"> Patterns for Layour: Boxed, Framed, Rounded </div>
                <div class="body-bg">
                <?php
                $path = JPATH_ROOT.'/templates/'.$yt->template.'/images/pattern/body';
                $files = JFolder::files($path, '.');
                foreach($files as $file) {
                    $file = JFile::stripExt($file); ?>
                    <a href="#"  title="<?php echo $file; ?>" class="pattern <?php echo $file; ?><?php echo ($yt->getParam('bgimage')==$file)?' active':''?>"><?php echo $file; ?></a>
                    <?php
                }
                ?>
                </div>
                <input type="hidden" name="ytcpanel_bgimage" value="<?php echo $yt->getParam('bgimage'); ?>"/>

            </div>

        </div>

    </div>
    <div id="cpanel_btn" class="isDown visible-md visible-lg"><i class="fa fa-wrench"></i></div>

</div>
