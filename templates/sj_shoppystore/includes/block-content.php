<?php
/*
 * ------------------------------------------------------------------------
 * Yt FrameWork for Joomla 2.5
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2012 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

$option = JRequest::getVar('option', null);
$view = JRequest::getVar('view', null);


defined( '_JEXEC' ) or die( 'Restricted access' );
if ($position['group'] == '') { // Position none group
    echo $yt->renPositionsContentNoGroup($position);

} elseif ( ($position['group'] != 'left') && ($position['group'] != 'main') && ($position['group'] != 'right') ) {     // Position has group's user created
    if (!isset($countGSpe)) {
        $countGSpe = 0;
    }
    $countGSpe ++;


    if($countGSpe == 1) {

        echo '<div id="' . $position['group'] . '" class="'.$yt_render->arr_GI['maintop']['class'].'">';
        echo $yt->renPositionsGroup($position);

        if($tagBD['count-'.$position['group']] == 1) {
            $countGSpe = null;
            echo '</div>';
        }
    } elseif ( $countGSpe == $tagBD['count-'.$position['group']] && $tagBD['count-'.$position['group']] > 1 ) {

        echo $yt->renPositionsGroup($position);

        $countGSpe = null;
        echo '</div>';
    } else {
        echo $yt->renPositionsGroup($position);
    }

} elseif ( ($position['group'] == 'left')
       ||($position['group'] == 'main')
       ||($position['group'] == 'right') ) { // Position has group's framework fixed    - left, main, right

    if($position['group'] == 'left') {
        $countL ++;
        if($countL == 1) {
            if ( !($option=='com_virtuemart'  && $view=='productdetails') )   {
                echo '<aside id="content_left" class="'.$yt_render->arr_GI['left']['class'].'">';
                echo $yt->renPositionsGroup($position, 'block-content');
                if($tagBD['count-group-left'] == 1) {
                    echo '</aside>';
                }
            }
        } elseif ($tagBD['count-group-left'] == $countL && $tagBD['count-group-left'] > 1) {
            echo $yt->renPositionsGroup($position, 'block-content');
            echo '</aside>';
        } else {
            echo $yt->renPositionsGroup($position, 'block-content');
        }
    } elseif ($position['group'] == 'main') {
        $countM++;

        if ($countM == 1) {
            if ( !($option=='com_virtuemart'  && $view=='productdetails') )   {
                echo '<div id="content_main" class="'.$yt_render->arr_GI['main']['class'].'">' ;
                echo $yt->renPositionsGroup($position, 'main');

                if($tagBD['count-group-main'] == 1 ) {
                    echo '    </div>';
                    echo '</div>';
                }
            }
        } elseif ( ($tagBD['count-group-main'] == $countM) && ($tagBD['count-group-main'] > 1) ){
            echo $yt->renPositionsGroup($position, 'main');

            echo '</div>';
        } else {
            echo $yt->renPositionsGroup($position, 'main');
        }
    } elseif ($position['group'] == 'right') {
        $countR ++;
        if($countR == 1) {
            if ( !($option=='com_virtuemart'  && $view=='productdetails') )   {
                echo '<aside id="content_right" class="'.$yt_render->arr_GI['right']['class'].'">';
                echo $yt->renPositionsGroup($position, 'block-content');
                if($tagBD['count-group-right'] == 1) {
                    echo '</aside>';
                }
            }

        } elseif ($countR == $tagBD['count-group-right'] && $tagBD['count-group-right'] > 1) {
            echo $yt->renPositionsGroup($position, 'block-content');
            echo '</aside>';
        } else {
            echo $yt->renPositionsGroup($position, 'block-content');
        }
    }
}
?>
