jQuery(document).ready(function($){
	if($('#module-menu')!=null){
		$('#menu').append('<li id=\'li-sjhelp\' class=\'dropdown\'></li>');
		$('#menu li#li-sjhelp').append('<a class=\'dropdown-toggle\' data-toggle=\'dropdown\' href=\'#\' style=\'background: url(../plugins/system/yt/includes/admin/images/favicon.png) no-repeat 5px center; cursor:pointer; padding-left:25px\'>SJ Help</a>');
		$('#menu li#li-sjhelp a.dropdown-toggle').append('<span class=\'caret\'></span>');
		$('#menu li#li-sjhelp').append('<ul id=\'ul-sjhelp\' class=\'dropdown-menu\'></ul>');
		$('#menu li#li-sjhelp ul').append('<li class=\'report-sjhelp\'></li>');
		$('#menu li#li-sjhelp ul').append('<li class=\'tut-sjhelp\'></li>');
		$('#menu li#li-sjhelp ul').append('<li class=\'info-sjhelp\'></li>');
		$('#menu li#li-sjhelp ul li.report-sjhelp').append('<a href=\'http://www.smartaddons.com/forum/index/7-joomla-templates\' target=\'_blank\'>Report bugs</a>');
		$('#menu li#li-sjhelp ul li.tut-sjhelp').append('<a href=\'http://www.smartaddons.com/joomla/templates/template-user-guides\' target=\'_blank\'>Template Tutorial</a>');
		$('#menu li#li-sjhelp ul li.info-sjhelp').append('<a href=\'http://www.smartaddons.com/joomla/templates/yt-framework\' target=\'_blank\'>Framework Tutorials</a>');
		$('#menu').append('<li class=\'yt-clearcache dropdown\'></li>');
		$('.yt-clearcache').append('<a href=\'javascript:void(0)\'><i class="icon-lightning clear" style="font-size:12px;margin-right:3px;"></i>Clean cache: CSS, JS</a>');
		$('.yt-clearcache a').click(function(){
			var linkurl = '../index.php?action=clearCache&type=plugin';
			$.post(linkurl, function() {
			})
			.error(function() { alert('Error...'); })
			.complete(function() { alert('Clear cache successful !') }); 
		});
	}
});