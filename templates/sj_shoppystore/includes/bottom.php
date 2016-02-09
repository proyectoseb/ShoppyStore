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


/****************************
*  Google Font & Body Font
****************************/


/**
 * Add Google font
 * @param     string              $font       name font
 * @param     string              $selectors  name selectors
 * @return    string              url google fonts
 */
function ytfont($font, $selectors){
    $doc = JFactory::getDocument();
    $font = trim($font);
    $font_boolean = strrpos($font, "'");

    if($font !='0'){
        if ($font_boolean ) {
            $doc->addStyleDeclaration($selectors.'{font-family:'.$font.'}');
        }else{
            $doc->addStyleSheet('http://fonts.googleapis.com/css?family='.$font.'&amp;subset=latin,latin-ext');
            $font = str_replace("+"," ",(explode(':',$font)));
            if(trim($selectors)!=""){
                $doc->addStyleDeclaration($selectors.'{font-family:'.$font[0].'}');
            }
        }
    }
}
ytfont($bodyFont,$bodySelectors);
ytfont($menuFont,$menuSelectors);
ytfont($headingFont,$headingSelectors);
ytfont($otherFont,$otherSelectors);
?>

<?php
// Add Background image Typelayout
if($yt->getParam('typelayout') != 'wide') {?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        typelayout = '<?php echo $yt->getParam('typelayout') ?>';
        switch(typelayout) {
            case "boxed":
                bodybgimage = '<?php echo $yt->getParam('bgbox') ?>';
                break;
            case "framed":
                 bodybgimage = '<?php echo $yt->getParam('bgframed') ?>';
                break;
            case "rounded":
                bodybgimage = '<?php echo $yt->getParam('bgrounded') ?>';
                break;

        }
        if(bodybgimage) $('#bd').addClass(bodybgimage);
    });
</script>
<?php } ?>

<?php
// Setting Cpanel
if($showCpanel) {
    include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'cpanel.php');
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){


        patternClick('.body-bg .pattern', 'bgimage', Array('#bd'));

        var array                         = Array('bgimage');
        var array_blue                  = Array('pattern8');
        var array_green                 = Array('pattern8');
        var array_orange                 = Array('pattern8');
        var array_red                = Array('pattern8');
        var array_purple                 = Array('pattern8');
        var array_pink                 = Array('pattern8');


        //1.Color Blue
        $('.theme-color.blue').click(function(){
            $($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
            createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
            setCpanelValues(array_blue);
            onCPApply();
        });

        //2.Color Purple
        $('.theme-color.purple').click(function(){
            $($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
            createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
            setCpanelValues(array_purple);
            onCPApply();
        });

        //3.Color Green
        $('.theme-color.green').click(function(){
            $($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
            createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
            setCpanelValues(array_green);
            onCPApply();
        });

        //4.Color Orange
        $('.theme-color.orange').click(function(){
            $($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
            createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
            setCpanelValues(array_orange);
            onCPApply();
        });

        //5.Color Red
        $('.theme-color.red').click(function(){
            $($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
            createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
            setCpanelValues(array_red);
            onCPApply();
        });

        //5.Color Pink
        $('.theme-color.pink').click(function(){
            $($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
            createCookie(TMPL_NAME+'_'+'themecolor', $(this).html().toLowerCase(), 365);
            setCpanelValues(array_pink);
            onCPApply();
        });


        /* miniColorsCPanel */
        function miniColorsCPanel(elC, elT, selector){
            $(elC).miniColors({
                change: function(hex, rgb) {
                    if(typeof(elT)!='string'){
                        for(i=0;i<elT.length;i++){
                            $(elT[i]).css(selector, hex);
                        }
                    }else{
                        $(elT).css(selector, hex);
                    }
                    createCookie(TMPL_NAME+'_'+($(this).attr('name').match(/^ytcpanel_(.*)$/))[1], hex, 365);
                }
            });
        }

        /* Begin: Set click pattern */
        function patternClick(elC, paramCookie, elT){
            $(elC).click(function(){
                oldvalue = $(this).parent().find('.active').html();
                $(elC).removeClass('active');
                $(this).addClass('active');
                value = $(this).html();
                if(elT.length > 0){
                    for($i=0; $i < elT.length; $i++){
                        $(elT[$i]).removeClass(oldvalue);
                        $(elT[$i]).addClass(value);
                    }
                }
                if(paramCookie){
                    $('input[name$="ytcpanel_'+paramCookie+'"]').attr('value', value);
                    createCookie(TMPL_NAME+'_'+paramCookie, value, 365);
                }
            });
        }
        function setCpanelValues(array){
            // Remove the # from the hash, as different browsers may or may not include it
            // append /remove anchor name from current url without refresh
            if(array['0']){
                $('.body-backgroud-image .pattern').removeClass('active');
                $('.body-backgroud-image .pattern.'+array['3']).addClass('active');
                $('input[name$="ytcpanel_bgimage"]').attr('value', array['3']);
            }

        }
    });
    </script>
<?php } ?>

<?php
// Show Back To Top
if( $yt->getParam('showBacktotop'))  { ?>
    <a id="yt-totop" class="backtotop" href="#"><i class="fa fa-angle-up"></i> Top </a>
    <script type="text/javascript">
        jQuery('.backtotop').click(function () {
            jQuery('body,html').animate({
                    scrollTop:0
                }, 1200);
            return false;
        });

    </script>
<?php } ?>

<?php // Slide Menu Mobile?>
<script type="text/javascript">

    //var slideout = new Slideout({
        //'panel': document.getElementById('yt_wrapper'),
        //'menu': document.getElementById('menu')
    //});
  //  window.onload = function() {
     //       document.querySelector('.js-slideout-toggle').addEventListener('click', function() {
     //         slideout.toggle();
       // });
//};

</script>


