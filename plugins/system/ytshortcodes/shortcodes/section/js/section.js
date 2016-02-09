jQuery(document).ready(function ($) {

	$('body').on('click', '.yt-section-clickable', function (e) {
		document.location.href = $(this).data('url');
	});
	
	// Section with parallax
	$('.yt-section-parallax').each(function () {
		var $this = $(this);
		$(window).on('scroll touchmove', function () {
			var yPos = -($(window).scrollTop() / $this.data('speed'));
			var coords = '50% ' + yPos + 'px';
			$this.css({
				backgroundPosition: coords
			});
		});
	});

	// Section force full width
	$('.yt-section-forcefullwidth').each(function (e) {
		var section = $(this);
		var container_wrapper = section.find('.yt-section-wrapper');
		var container = section.find('.yt-section');
		var loff = section.offset().left;

		/*container_wrapper.css({'margin-left':(0-loff)+"px",'width':$('body').width()});
		container.width=$('body').width();	

		$('body').resize(function(e) {
			var loffr = section.offset().left;
			container_wrapper.css({'margin-left':(0-loffr)+"px",'width':$('body').width()});
		});*/
	});

	// Section video adding script
	$('.yt-section-video').each(function () {
		var data = $(this).data();
			id = $(this).data('id'),
			selector = '#' + id;
			
			var globalEasyVideo = new video_background(selector, {
			"position": 'absolute',	//Stick within the div
			"z-index": '0',		//Behind everything

			"loop": data.loop, 			//Loop when it reaches the end
			"autoplay": data.autoplay,		//Autoplay at start
			"muted": data.muted,			//Muted at start

			"mp4": ((typeof data.mp4==="undefined")?false:data.mp4),
			"webm": ((typeof data.webm==="undefined")?false:data.webm),
			"ogg": ((typeof data.ogg==="undefined")?false:data.ogg),
			"flv": ((typeof data.flv==="undefined")?false:data.flv),
			"vimeo": (typeof data.vimeo==="undefined")?false:data.vimeo,
			"youtube": (typeof data.youtube==="undefined")?false:data.youtube,	//Youtube video id
			"video_ratio": data.ratio, 		// width/height -> If none provided sizing of the video is set to adjust
			"swfpath": data.swfpath,
			"sizing": data.sizing,
			"overlayOpacity": 0.5, //(data.opacity=="")?false:data.opacity,
			"fallback_image": false,
			"enableOverlay": (data.overlay=="")?false:true
		});
		//console.log(data.youtube);
	});


});