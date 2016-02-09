//<![CDATA[
jQuery(document).ready(function ($) {
	; (function (element) {
		var $element = $(element);
		$('.sl2-loading', $element).remove();
		$element.removeClass('sl2-preload');

		/* slide navigation for theme 1, 2, 3, 4
		*/
		function setSlidingTo(index, options) {
			var tabs = $(options.pager);
			var tabs_holder = tabs.eq(0).parent();
			var tabs_container = tabs_holder.parent();
			if (tabs_container && tabs_holder) {
				tabs_container.css({
					height: function () {
						return tabs_container.height();
					}
				});
				tabs_container.css({ position: 'absolute' });
				tabs_holder.css({ position: 'absolute' });
			}

			var viewport = {};
			viewport.top = 0;
			viewport.bottom = tabs_container.height();

			var visible = {};
			visible.top = viewport.top - parseInt(tabs_holder.position().top);
			visible.bottom = viewport.bottom - parseInt(tabs_holder.position().top);

			var posUpdate = function () {
				visible.top = viewport.top - parseInt(tabs_holder.position().top);
				visible.bottom = viewport.bottom - parseInt(tabs_holder.position().top);
			};

			var slidingTo = function (index, d) {
				if (!d) {
					d = 500
				}
				if (index >= 0) {
					if (index == 0) {
						index = 1;
					}
					var prevTop = tabs.eq(index - 1).position().top;
					if (prevTop < visible.top) {
						tabs_holder.animate({
							top: '+=' + (visible.top - prevTop) + 'px'
						}, {
							duration: d,
							complete: posUpdate,
							queue: false
						});
					}
				}
				if (index < tabs.length) {
					if (index == tabs.length - 1) {
						index = tabs.length - 2;
					}
					var nextBottom = tabs.eq(index + 1).position().top + tabs.eq(index + 1).height();
					if (nextBottom > visible.bottom && index != 1) {
						tabs_holder.animate({
							top: '-=' + (nextBottom - visible.bottom) + 'px'
						}, {
							duration: d,
							complete: posUpdate,
							queue: false
						});
					}
				}
			};
			slidingTo(index);
		}

		/* slide navigation for theme 5
		*/
		function setSlidingToX(index, options) {
			var tabs = $(options.pager);
			var tabs_holder = tabs.eq(0).parent();
			var tabs_container = tabs_holder.parent();
			if (tabs_container && tabs_holder) {
				tabs_container.css({
					height: function () {
						return tabs_container.height();
					}
				});
				tabs_container.css({ position: 'absolute' });
				tabs_holder.css({ position: 'absolute' });
			}
			var viewport = {};
			viewport.left = 0;
			viewport.right = tabs_container.width();

			var visible = {};
			visible.left = viewport.left - parseInt(tabs_holder.position().left);
			visible.right = viewport.right - parseInt(tabs_holder.position().left);

			var posUpdate = function () {
				visible.left = viewport.left - parseInt(tabs_holder.position().left);
				visible.right = viewport.right - parseInt(tabs_holder.position().left);
			};

			var slidingTo = function (index, d) {
				if (!d) {
					d = 500
				}
				if (index >= 0) {
					if (index == 0) {
						index = 1;
					}
					var prevLeft = tabs.eq(index - 1).position().left;
					if (prevLeft < visible.left) {
						tabs_holder.animate({
							left: '+=' + (visible.left - prevLeft) + 'px'
						}, {
							duration: d,
							complete: posUpdate,
							queue: false
						});
					}
				}
				if (index < tabs.length) {
					if (index == tabs.length - 1) {
						index = tabs.length - 2;
					}
					var nextRight = tabs.eq(index + 1).position().left + tabs.eq(index + 1).width();
					if (nextRight > visible.right) {
						tabs_holder.animate({
							left: '-=' + (nextRight - visible.right) + 'px'
						}, {
							duration: d,
							complete: posUpdate,
							queue: false
						});
					}
				}
			};
			slidingTo(index);
		}
		$('.sl2-items', $element).cycle({
			slideExpr: '.sl2-item',
			before: function (curr, next, options) {
				$('.sl2-item', $element).eq(options.startingSlide).addClass('curr');
				if (typeof options.inited == 'undefined') {
					var slidingTo = options.startingSlide;
					options.inited = true;
					if (typeof options.fxs != 'undefined') {
						var tmp = [];
						var j = 0;
						for (var i = 0; i < options.fxs.length; i++) {
							if (options.fxs[i] == 'none') {
								continue;
							}

							tmp[j++] = options.fxs[i];
						}
						if (tmp.length != options.fxs.length) {
							options.fxs = tmp;
						}
					}
				} else {
					var slidingTo = options.nextSlide;
					$('.sl2-item', $element).removeClass('curr');
					$('.sl2-item', $element).eq(options.nextSlide).addClass('curr');
				}
				setSlidingTo(slidingTo, options);
			},
			fx: 'fade',
			speed: 800,
			timeout: 4000,
			pause: 1,
			pauseOnPagerHover: 1,
			startingSlide: 0,
			pager: '#sj_slideshowii_19739039261426496551 .slide-items .slide-item',
			pagerAnchorBuilder: function (i, el) {
				return $('#sj_slideshowii_19739039261426496551  .slide-items .slide-item:eq(' + i + ')');
			},
			updateActivePagerLink: function (pager, i, clsName) {
				$(pager).removeClass('active').eq(i).addClass('active');
			},
			next: '#sj_slideshowii_19739039261426496551 .sl2-control .next',
			prev: '#sj_slideshowii_19739039261426496551 .sl2-control .previous',
			skipInitializationCallbacks: false,
			pagerEvent: 'click'

		});

		$('.play-pause', $element).bind('click', function () {
			if ($(this).hasClass('pause')) {
				$(this).addClass('play');
				$(this).removeClass('pause');
				$('.sl2-items', $element).cycle('pause');
			} else {
				$(this).removeClass('play');
				$(this).addClass('pause');
				$('.sl2-items', $element).cycle('resume');
			}
		});

		$('.sl2-items', $element).cycle('pause');
	})('#sj_slideshowii_19739039261426496551');
});
//]]>