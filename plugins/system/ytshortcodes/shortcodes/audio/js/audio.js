jQuery(document).ready(function ($) {

	$('.yt-audio').each(function (index) {

		var $this = $(this),
			id = $this.data('id'),
			selector = '#' + id,
			$player = $(selector),
			audio = $this.data('audio'),
			volume = $this.data('volume'),
			swf = $this.data('swf');

	    $player.jPlayer({

	        // Extra Settings
	        swfPath: swf,
	        solution: 'html, flash',
	        volume: volume/100,
	        smoothPlayBar: false,
	        keyEnabled: true,
	        remainingDuration: false,
	        toggleDuration: false,
	        errorAlerts: false,
	        warningAlerts: false,
	        supplied: 'mp3, ogg',

	        // CSS Selectors
	        size: {
	            width: "100%",
	            height: "auto"
	        },

	        cssSelectorAncestor: selector + '_container',
	        cssSelector: {
	            play: ".play",
	            pause: ".pause",
	            seekBar: ".seekBar",
	            playBar: ".playBar",
	            mute: ".right-volume .mute",
	            unmute: ".right-volume .unmute",
	            volumeBar: ".volume-control",
	            volumeBarValue: ".volume-control .volume-value",
	            currentTime: ".timer.current",
	            duration: ".timer.duration",
	            gui: ".controls",
	            noSolution: ".noSolution"
	        },

	        ready: function (event) {

	            if(event.jPlayer.status.noVolume) {
	                // Add a class and then CSS rules deal with it.
	                $player.find(".controls .jpprogress-block").css({ margin: '0 10px 0 45px'});
	            }

	            $(this).jPlayer("setMedia", {
	                title: "Big Buck Bunny Trailer",
	               	mp3: audio
	            });

	            if ($this.data('autoplay') === 'yes') $player.jPlayer('play');

	            if ($this.data('loop') === 'yes') $player.bind($.jPlayer.event.ended + '.repeat', function () {
	            	$player.jPlayer('play');
	            });

	        },
	        play: function() {
	            //$this.find('.playerScreen .video-play').stop(true,true).fadeOut(150);
	            $(this).on('click', function() { $(this).jPlayer('pause');});
	            $(this).jPlayer("pauseOthers");
	        },
	        pause: function() {
	            //$this.find('.playerScreen .video-play').stop(true,true).fadeIn(350);
	            $(this).unbind('click');
	        }
	    });
	});
});

