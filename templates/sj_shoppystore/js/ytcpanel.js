// Reset Default Cpanel
function onCPResetDefault(_cookie){
	for (i=0;i<_cookie.length;i++) { 
		if(getCookie(TMPL_NAME+'_'+_cookie[i])!=undefined){
			createCookie (TMPL_NAME+'_'+_cookie[i], '', -1);
		}
	}
	window.location.reload(true);
}

// Apply  Cpanel
function onCPApply () {
	var elems = document.getElementById('cpanel_wrapper').getElementsByTagName ('*');
	var usersetting = {};
	
	for (i=0;i<elems.length;i++) {
		var el = elems[i]; 
	    if (el.name && (match=el.name.match(/^ytcpanel_(.*)$/))) {
	        var name = match[1];	        
	        var value = '';
	        if (el.tagName.toLowerCase() == 'input' && (el.type.toLowerCase()=='radio' || el.type.toLowerCase()=='checkbox')) {
	        	if (el.checked) value = el.value;
	        } else {
	        	value = el.value;
	        }
			if(value.trim()){
				if (usersetting[name]) {
					if (value) usersetting[name] = value + ',' + usersetting[name];
				} else {
					usersetting[name] = value;
				}
			}
	    }
	}
	
	for (var k in usersetting) {
		name = TMPL_NAME + '_' + k; 
		value = usersetting[k];
		createCookie(name, value, 365);
	}
	
	window.location.reload(true);
}


jQuery(document).ready(function($){
	
	// Select For Layout Style
	var $typeLayout = $('.typeLayout .layout-item'), $patten = $(".body-bg .pattern") ;
	var $body = $('#yt_wrapper');
	var ua = navigator.userAgent,
    event = (ua.match(/iPad/i)) ? "touchstart" : "click";
	
	$typeLayout.each(function(){
		var $btns = $typeLayout.bind(event, function(event) {
			$body
				.removeClass($btns.filter('.active').removeClass('active').data('value'))
				.addClass($(this).addClass('active').data('value'));
			//createCookie(TMPL_NAME+'_'+'typelayout', $(this).html().toLowerCase(), 365);
		});
	});
	
	/* Begin: Show hide cpanel */  
	$("#cpanel_btn").bind(event, function(event) {
		event.preventDefault();
		widthC = $('#cpanel_wrapper').width()+16;
		if ($(this).hasClass("isDown") ) {
			$("#cpanel_wrapper").animate({right:"0px"}, 400);			
			$(this).removeClass("isDown");
		} else {
			$("#cpanel_wrapper").animate({right:-widthC}, 400);	
			$(this).addClass("isDown");
		}
		return false;
	});
	/* End: Show hide cpanel */
	
});

