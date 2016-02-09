jQuery(document).ready(function($) {
    var timelineAnimate;
    timelineAnimate = function(elem) {
        return jQuery(".yt-timeline.animated .yt-timeline-row").each(function(i) {
            var bottom_of_object, bottom_of_window;
            bottom_of_object = jQuery(this).position().top + jQuery(this).outerHeight();
            bottom_of_window = jQuery(window).scrollTop() + jQuery(window).height();
            if (bottom_of_window > bottom_of_object) {
                return jQuery(this).addClass("active");
            }
        });
    };
    timelineAnimate();
    return jQuery(window).scroll(function() {
        return timelineAnimate();
    });
});