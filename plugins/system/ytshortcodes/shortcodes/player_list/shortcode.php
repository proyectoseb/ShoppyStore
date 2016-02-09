<?php 
/*
* @package   YouTech Shortcodes
* @author    YouTech Company http://smartaddons.com/
* @copyright Copyright (C) 2015 YouTech Company
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
defined('_JEXEC') or die;
function player_listYTShortcode($atts, $content = null){
	global $playerlist_count ;
	extract(ytshortcode_atts(array(
		"title"		=> '',
		"src"		=> ''
	), $atts));
	$playerlist_count ++;

	if($playerlist_count == 1){
		JHtml::_('jquery.framework');
		$audio_script   = JHtml::script("plugins/system/ytshortcodes/shortcodes/player_list/js/audiojs/audio.min.js");
		$audio_script  .= "<script>
			jQuery(document).ready(function($) {
				// Setup the player to autoplay the next track
				var a = audiojs.createAll({
				  trackEnded: function() {
					var next = $('ul.yt-playlist li.playing').next();
					if (!next.length) next = $('ul.yt-playlist li').first();
					next.addClass('playing').siblings().removeClass('playing');
					audio.load($('a', next).attr('data-src'));
					audio.play();
				  }
				});

				// Load in the first track
				var audio = a[0];
					first = $('ul.yt-playlist a').attr('data-src');
				$('ul.yt-playlist li').first().addClass('playing');
				audio.load(first);

				// Load in a track on click
				$('ul.yt-playlist li').click(function(e) {
				  e.preventDefault();
				  $(this).addClass('playing').siblings().removeClass('playing');
				  audio.load($('a', this).attr('data-src'));
				  audio.play();
				});
			});
		</script>";
	}
	JHtml::stylesheet(JUri::base()."plugins/system/ytshortcodes/shortcodes/player_list/css/play_list.css");
	$playerlist	= $audio_script;
	$playerlist  .= '<div class="audio_player">';
	$playerlist  .= (!empty($title) && $title != null) ?'<h4>'.$title.'</h4>' : '';
	$playerlist  .= '<audio src="'.$src.'" preload="auto"/></div>';
	$playerlist  .= '<ul class="yt-playlist">'.parse_shortcode(str_replace(array("<br/>", "<br>", "<br />"), " ", $content)) .'</ul>';
	return $playerlist;
}

?>