jQuery(document).ready(function($) {
    'use strict';

    $('.yt-showcase').each(function () {
	
		var data = $(this).data(),
			showcaseid = '#' + data.scid, 
	     	gridContainer = $(showcaseid + '_container'),
	        filtersContainer = $(showcaseid + '_filter'),
	        singlePageInline = $(showcaseid + '_inlinecontents').children(),
	        wrap, filtersCallback;


	    /*******************************
	        init cubeportfolio
	     ****************************** */
	    gridContainer.cubeportfolio({
	        layoutMode: 'grid',
	        rewindNav: true,
	        scrollByPage: false,
	        mediaQueries: [{
	            width: 1100,
	            cols: data.large
	        }, {
	            width: 800,
	            cols: data.medium
	        }, {
	            width: 500,
	            cols: data.small
	        }, {
	            width: 320,
	            cols: 1
	        }],
	        defaultFilter: '*',
	        animationType: data.filter_animation,
	        gapHorizontal: data.horizontal_gap,
	        gapVertical: data.vertical_gap,
	        gridAdjustment: 'responsive',
	        caption: data.caption_style,
	        displayType: data.loading_animation,
	        displayTypeSpeed: 200,
	        filterDeeplinking: data.filter_deeplink,

	        // lightbox
	        lightboxDelegate: '.cbp-lightbox',
	        lightboxGallery: true,
	        lightboxTitleSrc: 'data-title',
	        lightboxCounter: '<div class="cbp-popup-lightbox-counter">{{current}} of {{total}}</div>',

	        // singlePage popup
	        singlePageDelegate: '.cbp-singlePage',
	        singlePageDeeplinking: true,
	        singlePageStickyNavigation: true,
	        singlePageCounter: '<div class="cbp-popup-singlePage-counter">{{current}} of {{total}}</div>',
	        singlePageCallback: function(url, element) {
	            // to update singlePage content use the following method: this.updateSinglePage(yourContent)
	        },

	        // singlePageInline
	        singlePageInlineDelegate: '.cbp-singlePageInline',
	        singlePageInlinePosition: data.popup_position,
	        singlePageInlineInFocus: true,
	        singlePageInlineCallback: function(url, element) {
	            // to update singlePage content use the following method: this.updateSinglePageInline(yourContent)
	            var indexElement = $(element).parents('.cbp-item').index(),
	                item = singlePageInline.eq(indexElement);

	            this.updateSinglePageInline(item.html());
	        }
	    });


	    /*********************************
	        add listener for filters
	     *********************************/
	    if (filtersContainer.hasClass('cbp-l-filters-dropdown')) {
	        wrap = filtersContainer.find('.cbp-l-filters-dropdownWrap');

	        wrap.on({
	            'mouseover.cbp': function() {
	                wrap.addClass('cbp-l-filters-dropdownWrap-open');
	            },
	            'mouseleave.cbp': function() {
	                wrap.removeClass('cbp-l-filters-dropdownWrap-open');
	            }
	        });

	        filtersCallback = function(me) {
	            wrap.find('.cbp-filter-item').removeClass('cbp-filter-item-active');
	            wrap.find('.cbp-l-filters-dropdownHeader').text(me.text());
	            me.addClass('cbp-filter-item-active');
	            wrap.trigger('mouseleave.cbp');
	        };
	    } else {
	        filtersCallback = function(me) {
	            me.addClass('cbp-filter-item-active').siblings().removeClass('cbp-filter-item-active');
	        };
	    }

	    filtersContainer.on('click.cbp', '.cbp-filter-item', function() {
	        var me = $(this);

	        if (me.hasClass('cbp-filter-item-active')) {
	            return;
	        }

	        // get cubeportfolio data and check if is still animating (reposition) the items.
	        if (!$.data(gridContainer[0], 'cubeportfolio').isAnimating) {
	            filtersCallback.call(null, me);
	        }

	        // filter the items
	        gridContainer.cubeportfolio('filter', me.data('filter'), function() {});
	    });


	    /*********************************
	        activate counter for filters
	     *********************************/
	    gridContainer.cubeportfolio('showCounter', filtersContainer.find('.cbp-filter-item'), function() {
	        // read from url and change filter active
	        var match = /#cbpf=(.*?)([#|?&]|$)/gi.exec(location.href),
	            item;
	        if (match !== null) {
	            item = filtersContainer.find('.cbp-filter-item').filter('[data-filter="' + match[1] + '"]');
	            if (item.length) {
	                filtersCallback.call(null, item);
	            }
	        }
	    });
	});

});