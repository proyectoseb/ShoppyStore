;(function($){
    $.fn.extend({
        jaccordion_expand: function(options){
            $context = $(this);
            // if ( $context.hasClass(options.active_class) ) {
                // //return;
            // }
            var $content_inner = $(options.content, $context);
            $content_inner.filter(':animated').stop();

            $height_to_fit = 0;
            $content_inner.children().each(function(){
                if ($(this).height()>0){
                    $height_to_fit += $(this).height();
                }
            });

            $context.addClass(options.active_class);
            $content_inner.animate({
                height: $height_to_fit
            }, {
                duration: options.duration,
                complete: function(){
                    $content_inner.css('height','auto').css('display','block');
                }
            });
        },
        jaccordion_close: function(options){
            $context = $(this);
            $context.removeClass(options.active_class);
            var $content_inner = $(options.content, $context);

            $content_inner.filter(':animated').stop();

            $content_inner.animate({
                height: 0
            }, {
                duration: options.duration,
                complete: function(){
                    $content_inner.css('display','none');
                }
            });

        },
        jaccordion: function(options){
            var defaults = {};
            var options =  $.extend(defaults, options);

            return this.each(function(){
                var panel = $(this);
                var items = $(options.items, this);
                var stack = [];
                var stackProcess = function(){
                    if (stack.length>0){
                        var item = stack.pop();
                        items.not(item).jaccordion_close(options);
                        if($(item).hasClass(options.active_class)){
                            $(item).jaccordion_close(options);
                        }else{
                            $(item).jaccordion_expand(options);
                        }

                    }
                };
                var stackTimeout = null;
                items.each(function(i, item){
                    if (options.active && options.active==(i+1)){
                        $(item).jaccordion_expand(options);
                    }

                    if (options.event=='click'){
                        $(options.heading,$(item)).click(function(){
                            items.not(item).jaccordion_close(options);
                            if($(item).hasClass(options.active_class)){
                                $(item).jaccordion_close(options);
                            }else{
                                $(item).jaccordion_expand(options);
                            }
                        });
                    } else {
                        var delay = parseInt(options.delay);
                        if (!delay) delay = 100;
                        $(options.heading,$(item)).hover(function(){
                            if (stackTimeout){
                                clearTimeout(stackTimeout);
                            }
                            stackTimeout = setTimeout(stackProcess, delay);
                            stack.push(item);
                        }, function(){
                            stack.pop();
                        });
                    }
                });
            });
        }
    });
})(jQuery)
