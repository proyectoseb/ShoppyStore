(function( $ ){
  var $this = new Object();    
  var methods = {
     init : function( options ) {      
        $this =  $.extend({}, this, methods); 
        $this.searching = false;
        $this.o = new Object();
  
        var defaultOptions = {
           overlaySelector: '.yt-modal-overlay',
           closeSelector: '.yt-modal-close',
           classAddAfterOpen: 'yt-modal-show',
           modalAttr: 'data-modal',
           perspectiveClass: 'yt-modal-perspective',
           perspectiveSetClass: 'yt-modal-setperspective',
        };
        
        $this.o = $.extend({}, defaultOptions, options);
        $this.n = new Object();
       
        var overlay = $($this.o.overlaySelector);
        $(this).click(function() {
        	var modal = $('#' + $(this).attr($this.o.modalAttr)),
				classs = $(this).attr($this.o.modalAttr),
        		close = $($this.o.closeSelector, modal);
          var el = $(this);       	
      		$(modal).addClass($this.o.classAddAfterOpen);
			
          $(overlay).on('click', function () {
             removeModalHandler();
             $this.afterClose(el, classs);
             $(overlay).off('click');
          });

      		if( $(el).hasClass($this.o.perspectiveSetClass) ) {
      			setTimeout( function() {
      				$(document.documentElement).addClass($this.o.perspectiveClass);
      			}, 25 );
      		}
          $this.afterOpen(el, classs);
        	          
        	function removeModal( hasPerspective ) {
        		$(modal).removeClass($this.o.classAddAfterOpen);
        
        		if( hasPerspective ) {
        			$(document.documentElement).removeClass($this.o.perspectiveClass);
        		}
        	}
        
        	function removeModalHandler() {
        		removeModal($(el).hasClass($this.o.perspectiveSetClass)); 
        	}
        
        	$(close).on( 'click', function( ev ) {
        		ev.stopPropagation();
        		removeModalHandler();
            $this.afterClose(el, classs);
        	});
        
        });
       
     },
	 
     afterOpen: function (button, classs) {
		 console.log($(this).attr($this.o.modalAttr));
        $('.'+classs+'').css({"visibility":"inherit","opacity":"0.6"});
		//$('.yt-modal-overlay').css({"visibility":"inherit","opacity":"0.6"});
     },
     afterClose: function (button, classs) {
        //$this.o.afterClose(button, modal);
        $('.'+classs+'').css({"visibility":"hidden","opacity":"0"});
     }
  };

  $.fn.modalEffects = function( method ) {
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.modalEffects' );
    }    
  
  };
	function is_touch_device(){
		return !!("ontouchstart" in window) ? 1 : 0;
	}
})( jQuery );
// JavaScript Document


// Ready function for call modal

jQuery(document).ready(function ($) {
   $(".yt-modal-trigger").modalEffects();
   $(".yt-modal").appendTo(document.body);
});