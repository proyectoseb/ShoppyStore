<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function social_likeYTShortcode($atts,$content = null)
{
	extract(ytshortcode_atts(array(
		'button'           => 'google',
		'button_animation' => 'to_left',
		'url'			   => '',
		'align'			   => '',
		'class'            => ''
	),$atts));
		JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/social_like/css/social-like.css",'text/css',"screen");
        $return = "<div class='social-like pull-".$align." ". $button_animation.' '.trim($class) ."'>";

        if($button == 'facebook') {
            $return .= "<div class='sl_facebook sl_button '>";
            $return .= "<i class='sl_icon'>";
            $return .= "<i class='fa fa-facebook'>";
            $return .= "</i>";
            $return .= "</i>";
            $return .= "<div class='sl_slide'>";
            $return .= "<p>Facebook</p>";
            $return .= "</div>  ";
            $return .= "<script type='text/javascript'>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = '//connect.facebook.net/en_US/all.js#xfbml=1'; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk')); </script>";
            $return .= "<div class='fb-like' data-href='".$url."' data-layout='standard' data-width='80' data-height='20' data-colorscheme='light' data-action='like' data-show-faces='false' data-share='false'></div></div>";
        }

        if($button == 'linkedin') {
            $return .= "<div class='sl_linkedin sl_button'>";
            $return .= "<i class='sl_icon'><i class='fa fa-linkedin'></i></i>";
            $return .= "<div class='sl_slide'>";
            $return .= "<p>Linkedin</p>";
            $return .= "</div>";
            $return .= "<script src=\"//platform.linkedin.com/in.js\" type=\"text/javascript\">  /* lang: en_US */ </script>
			<script type=\"IN/Share\" data-counter=\"right\" data-url=\"".$url."\"></script>";
            $return .= "</div>";
        }

        if($button == 'google') {
            $return .= "<div class='sl_google sl_button'>";
            $return .= "<i class='sl_icon'><i class='fa fa-google-plus'></i></i>";
            $return .= "<div class='sl_slide'>";
            $return .= "<p>Google +</p>";
            $return .= "</div>";
            $return .= "<div class='g-plusone' data-size='medium' data-href=\"".$url."\"></div>";
            $return .= "<script type='text/javascript'>";
            $return .= "(function() {var po = document.createElement('script');po.type = 'text/javascript';po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po, s);})();";
            $return .= "</script></div>";
        }

        if($button == 'twitter') {
            $return .= "<div class='sl_twitter sl_button'>";
            $return .= "<i class='sl_icon'><i class='fa fa-twitter'></i></i>";
            $return .= "<div class='sl_slide'>";
            $return .= "<p>Twitter</p>";
            $return .= "</div>";
            $return .= "<a href='https://twitter.com/share' class='twitter-share-button'>Tweet</a>";
            $return .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
            $return .= "<script type='text/javascript' src='//platform.twitter.com/widgets.js'></script>";
            $return .= "</div> ";
        }

        $return .= "</div>";
        return $return;
}
?>