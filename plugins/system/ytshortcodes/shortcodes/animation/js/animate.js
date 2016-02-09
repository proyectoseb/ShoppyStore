jQuery(document).ready(function($) {
    $('.yt-animate').each(function() {
        $(this).appear(function(e) {
            var data = $(this).data()
            $(this).addClass('animated').css('visibility', 'visible');
            $(this).addClass(data.animation);
        });
    });
});