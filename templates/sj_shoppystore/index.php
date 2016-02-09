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
defined( '_JEXEC' ) or die( 'Restricted access' );
header('X-UA-Compatible: IE=edge');

// Object of class YtTemplate
$doc     = JFactory::getDocument();
$app     = JFactory::getApplication();
$option = $app->input->get('option');

// Check yt plugin
if(!defined('YT_FRAMEWORK')) throw new Exception(JText::_('INSTALL_YT_PLUGIN'));
if(!defined('J_TEMPLATEDIR') )define('J_TEMPLATEDIR', JPATH_SITE.J_SEPARATOR.'templates'.J_SEPARATOR.$this->template);

// Include file: frame_inc.php
 include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'frame_inc.php');

// Check direction for html
$dir = ($doc->direction == 'rtl') ? ' dir="rtl"' : '';

/** @var YTFramework */
$responsive = $yt->getParam('layouttype');
$favicon     = $yt->getParam('favicon');
$layoutType    = $yt->getParam('layouttype');

?>
<!DOCTYPE html>
<html <?php echo $dir; ?> lang="<?php echo $this->language; ?>">
<head>
    <jdoc:include type="head" />

    <meta name="HandheldFriendly" content="true"/>
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="YES" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <!-- META FOR IOS & HANDHELD -->
    <?php if($responsive=='res'): ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
    <?php endif ?>

    <!-- LINK FOR FAVICON -->
    <?php if($favicon) : ?>
        <link rel="icon" type="image/x-icon" href="<?php echo $favicon?>" />
    <?php endif; ?>

    <?php
    // Include css, js
    include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'head.php');
    ?>

</head>


<?php
    //sub Menu Home page
    $menu = JFactory::getApplication()->getMenu();
    if (is_object( $menu->getActive() ) ) {
        $getMenu_route = $menu->getActive()->route;
        $subMenu_home = strpos($getMenu_route,'home/');
    }else{
        $subMenu_home ='';
    }


    //render a class for home page
    $cls_body = '';
    $cls_body .= $yt->isHomePage() || is_numeric($subMenu_home) ? 'homepage ' : '';

    //Add Layout
    $cls_body .= 'home-'.$layout. ' ';

    //For RTL direction
    $cls_body .= ($doc->direction == 'rtl') ? 'rtl' . ' ' : '';

    //add a class according to the template name
    $cls_body .= ($layoutType !='res') ? 'no-res'.'':'res';

    $type_layout = 'layout-'.$yt->getParam('typelayout');
?>
<body id="bd" class="<?php echo $cls_body; ?>" >
    <jdoc:include type="modules" name="debug" />
    <div id="yt_wrapper" class="<?php echo $type_layout; ?>">

        <?php
        /*render blocks. for positions of blocks, please refer layouts folder. */
        foreach($yt_render->arr_TB as $tagBD) {
            //BEGIN Check if position not empty
            if( $tagBD["countModules"] > 0 ) {

                // BEGIN: Content Area
                if( ($tagBD["name"] == 'content') ) {
                    //class for content area
                    $cls_content  = $tagBD['class_content'];
                    $cls_content  .= ' block '. $option ;
                    echo "<{$tagBD['html5tag']} id=\"{$tagBD['id']}\" class=\"{$cls_content}\">";
                    ?>

                        <div  class="container">
                            <div  class="row">
                                <?php
                                $countL = $countR = $countM = 0;
                                // BEGIN: foreach position of block content
                                $yt->_countPosGroup($tagBD['positions']);
                                foreach($tagBD['positions'] as $position):
                                    include(J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'block-content.php');
                                endforeach;
                                // END: foreach position of block content
                                ?>
                            </div >
                        </div >

                    <?php
                    echo "</{$tagBD['html5tag']}>";
                    ?>
                    <?php
                // END: Content Area

                // BEGIN: For other blocks
                } elseif ($tagBD["name"] != 'content'){
                    echo "<{$tagBD['html5tag']} id=\"{$tagBD['id']}\" class=\"block\">";
                    ?>
                        <div class="container">
                            <div class="row">
                            <?php
                            if( !empty($tagBD["hasGroup"]) && $tagBD["hasGroup"] == "1"){
                                // BEGIN: For Group attribute
                                $flag = '';
                                $openG = 0;
                                $c = 0;
                                foreach( $tagBD['positions'] as $posFG ):
                                    $c = $c + 1;
                                    if( $posFG['group'] != "" && $posFG['group'] != $flag){
                                        $flag = $posFG['group'];
                                        if ($openG == 0) {
                                            $openG = 1;
                                            $groupnormal = 'group-' . $flag.$tagBD['class_groupnormal'];
                                            echo '<div class="' . $groupnormal . ' ' . $yt_render->arr_GI[$posFG['group']]['class'] . '">' ;
                                            echo $yt->renPositionsGroup($posFG);
                                            if($c == count( $tagBD['positions']) ) {
                                                echo '</div>';
                                            }
                                        } else {
                                            $openG = 0;
                                            $groupnormal = 'group-' . $flag;
                                            echo '</div>';
                                            echo '<div class="' . $groupnormal . ' '. $yt_render->arr_GI[$posFG['group']]['class'] . '">' ;
                                            echo $yt->renPositionsGroup($posFG);
                                        }
                                    } elseif ($posFG['group'] != "" && $posFG['group'] == $flag){
                                        echo $yt->renPositionsGroup($posFG);
                                        if($c == count( $tagBD['positions']) ) {
                                            echo '</div>';
                                        }
                                    }elseif($posFG['group']==""){
                                        if($openG ==1){
                                            $openG = 0;
                                            echo '</div>';
                                        }
                                        echo $yt->renPositionsGroup($posFG);
                                    }
                                endforeach;
                                // END: For Group attribute
                            }else{
                                // BEGIN: for Tags without group attribute
                                if(isset($tagBD['positions'])){

                                    echo $yt->renPositionsNormal($tagBD['positions'], $tagBD["countModules"],count($tagBD['positions']));
                                }
                                // END: for Tags without group attribute
                            }
                            ?>
                            </div>
                        </div>

                    <?php
                    echo "</{$tagBD['html5tag']}>";
                    ?>
            <?php
               }
               // END: For other blocks
            }
            // END Check if position not empty
        }
        //END: For
        ?>


    </div>
    <?php
        $menubase = J_TEMPLATEDIR.J_SEPARATOR.'menusys';
        $templateMenuBase = new YTMenuBase(
        array(
            'menutype'    => $yt->getParam('menutype'),
            'menustyle'    => 'mobile',
            'basepath'    => str_replace('\\', '/', $menubase)
        ));
        $templateMenuBase->getMenu()->getContent();
        include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'special-position.php');
        include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'bottom.php');
    ?>


</body>
</html>
