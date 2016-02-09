jQuery(document).ready(function($) {
    setTimeout(function(){
		$('.yt-frame-align-center, .yt-frame-align-none').each(function() {
			var frame_width = $(this).find('img').width();
			$(this).css('width', frame_width + 12);
		});
	}, 1000);
        
});