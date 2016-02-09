jQuery(document).ready(function ($) {

	$('.yt-progress-bar').appear(function() {
		var	$elem = $(this),
			$fill = $elem.children('.yt-pb-fill'),
			$ptext = $fill.children('.yt-pb-percent'),
			percent = $fill.attr('data-percent'),
			animation = $fill.attr('data-animation'),
			duration = $fill.attr('data-duration'),
			delay = $fill.attr('data-delay');

		setTimeout(function() {
			$fill.animate({ 'width' : percent + '%'}, duration*1000, animation,
				function() {
					$ptext.animate({ 'margin-right' : '0px', 'opacity': 1  }, duration*1000, animation);
				}).addClass('yt-pb-animated');
		}, delay*1000);
	});
});