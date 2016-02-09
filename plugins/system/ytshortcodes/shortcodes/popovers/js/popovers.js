jQuery(document).ready(function($) {
    $('.yt-tooltip').each(function() {
        var $tt = $(this),
            $content = $tt.find('.yt-tooltip-content'),
            is_advanced = $content.length > 0,
            data = $tt.data(),
            config = {
                style: {
                    classes: data.classes
                },
                position: {
                    my: data.my,
                    at: data.at,
                    viewport: $(window)
                },
                content: {
                    title: '',
                    text: ''
                }
            };
        if (data.title !== '') config.content.title = data.title;
        if (is_advanced) config.content.text = $content;
        else config.content.text = $tt.attr('title');
        if (data.close === 'yes') config.content.button = true;
        if (data.behavior === 'click') {
            config.show = 'click';
            config.hide = 'click';
            $tt.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
            $(window).on('scroll resize', function() {
                $tt.qtip('reposition');
            });
        } else if (data.behavior === 'always') {
            config.show = true;
            config.hide = false;
            $(window).on('scroll resize', function() {
                $tt.qtip('reposition');
            });
        } else if (data.behavior === 'hover' && is_advanced) {
            config.hide = {
                fixed: true,
                delay: 600
            }
        }
        $tt.qtip(config);
    });

    $('body').addClass('yt-other-shortcodes-loaded');
});