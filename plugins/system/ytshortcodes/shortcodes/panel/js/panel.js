jQuery(document).ready(function ($) {

	// Photo/Icon panel
	$('body:not(.yt-extra-loaded)').on('click', '.yt-panel-clickable', function (e) {
		document.location.href = $(this).data('url');
	});
	$('body').addClass('yt-extra-loaded');
});