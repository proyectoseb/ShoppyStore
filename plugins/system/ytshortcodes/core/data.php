<?php 
class YT_Data {


	/**
	 * Shortcode groups
	 */
	public static function groups() {
		return array(
			 'all' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_ALL'),
			 'content' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_CONTENT'),
			 'box' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_BOX'),
			 "media" => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_MEDIA'),
			 'gallery' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GALLERY'),
			 'other' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_OTHER')
		 );
	}
	/**
	 * Shortcode button type
	 */
	public static function buttons_type() {
		return array(
			 'none'   =>'None',
			 'social-blogger' => 'Social Blogger',
			 'social-pinterest' => 'Social Pinterest',
			 'social-spotify' => 'Social Spotify',
			 "social-dribbble" => 'Social Dribbble',
			 'social-myspace' => 'Social Myspace',
			 'social-path' => 'Social Path',
			 'social-facebook' => 'Social Facebook',
			 'social-twitter' => 'Social Twitter',
			 'social-linkedin' => 'Social Linkedin',
			 'social-googleplus' => 'Social Googleplus',
			 'social-stumbleupon' => 'Social Stumbleupon',
			 'social-vimeo' => 'Social Vimeo',
			 'social-behance' => 'Social Behance',
			 'social-youtube' => 'Social Youtube',
			 'social-skype' => 'Social skype',
			 'primary' => 'Primary',
			 'info' => 'Info',
			 'success' => 'Success',
			 'warning' => 'Warning',
			 'danger' => 'Danger',
			 'link' => 'Link'
		 );
	}
	/**
	 * Border styles
	 */
	public static function borders() {
		return array(
			'none'   => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_NONE'),
			'solid'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_SOLID'),
			'dotted' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOTTED'),
			'dashed' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DASHED'),
			'double' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_DOUBLE'),
			'groove' => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_GROOVE'),
			'ridge'  => JText::_('PLG_SYSTEM_YOUTECH_SHORTCODES_RIDGE')
		);
	}

	/**
	 * Font-Awesome icons
	 */
	public static function icons() {
		return array('glass', 'music', 'search', 'envelope-o', 'heart', 'star', 'star-o', 'user', 'film', 'th-large', 'th', 'th-list', 'check', 'remove', 'close', 'times', 'search-plus', 'search-minus', 'power-off', 'signal', 'gear', 'cog', 'trash-o', 'home', 'file-o', 'clock-o', 'road', 'download', 'arrow-circle-o-down', 'arrow-circle-o-up', 'inbox', 'play-circle-o', 'rotate-right', 'repeat', 'refresh', 'list-alt', 'lock', 'flag', 'headphones', 'volume-off', 'volume-down', 'volume-up', 'qrcode', 'barcode', 'tag', 'tags', 'book', 'bookmark', 'print', 'camera', 'font', 'bold', 'italic', 'text-height', 'text-width', 'align-left', 'align-center', 'align-right', 'align-justify', 'list', 'dedent', 'outdent', 'indent', 'video-camera', 'photo', 'image', 'picture-o', 'pencil', 'map-marker', 'adjust', 'tint', 'edit', 'pencil-square-o', 'share-square-o', 'check-square-o', 'arrows', 'step-backward', 'fast-backward', 'backward', 'play', 'pause', 'stop', 'forward', 'fast-forward', 'step-forward', 'eject', 'chevron-left', 'chevron-right', 'plus-circle', 'minus-circle', 'times-circle', 'check-circle', 'question-circle', 'info-circle', 'crosshairs', 'times-circle-o', 'check-circle-o', 'ban', 'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down', 'mail-forward', 'share', 'expand', 'compress', 'plus', 'minus', 'asterisk', 'exclamation-circle', 'gift', 'leaf', 'fire', 'eye', 'eye-slash', 'warning', 'exclamation-triangle', 'plane', 'calendar', 'random', 'comment', 'magnet', 'chevron-up', 'chevron-down', 'retweet', 'shopping-cart', 'folder', 'folder-open', 'arrows-v', 'arrows-h', 'bar-chart-o', 'bar-chart', 'twitter-square', 'facebook-square', 'camera-retro', 'key', 'gears', 'cogs', 'comments', 'thumbs-o-up', 'thumbs-o-down', 'star-half', 'heart-o', 'sign-out', 'linkedin-square', 'thumb-tack', 'external-link', 'sign-in', 'trophy', 'github-square', 'upload', 'lemon-o', 'phone', 'square-o', 'bookmark-o', 'phone-square', 'twitter', 'facebook-f', 'facebook', 'github', 'unlock', 'credit-card', 'rss', 'hdd-o', 'bullhorn', 'bell', 'certificate', 'hand-o-right', 'hand-o-left', 'hand-o-up', 'hand-o-down', 'arrow-circle-left', 'arrow-circle-right', 'arrow-circle-up', 'arrow-circle-down', 'globe', 'wrench', 'tasks', 'filter', 'briefcase', 'arrows-alt', 'group', 'users', 'chain', 'link', 'cloud', 'flask', 'cut', 'scissors', 'copy', 'files-o', 'paperclip', 'save', 'floppy-o', 'square', 'navicon', 'reorder', 'bars', 'list-ul', 'list-ol', 'strikethrough', 'underline', 'table', 'magic', 'truck', 'pinterest', 'pinterest-square', 'google-plus-square', 'google-plus', 'money', 'caret-down', 'caret-up', 'caret-left', 'caret-right', 'columns', 'unsorted', 'sort', 'sort-down', 'sort-desc', 'sort-up', 'sort-asc', 'envelope', 'linkedin', 'rotate-left', 'undo', 'legal', 'gavel', 'dashboard', 'tachometer', 'comment-o', 'comments-o', 'flash', 'bolt', 'sitemap', 'umbrella', 'paste', 'clipboard', 'lightbulb-o', 'exchange', 'cloud-download', 'cloud-upload', 'user-md', 'stethoscope', 'suitcase', 'bell-o', 'coffee', 'cutlery', 'file-text-o', 'building-o', 'hospital-o', 'ambulance', 'medkit', 'fighter-jet', 'beer', 'h-square', 'plus-square', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-double-down', 'angle-left', 'angle-right', 'angle-up', 'angle-down', 'desktop', 'laptop', 'tablet', 'mobile-phone', 'mobile', 'circle-o', 'quote-left', 'quote-right', 'spinner', 'circle', 'mail-reply', 'reply', 'github-alt', 'folder-o', 'folder-open-o', 'smile-o', 'frown-o', 'meh-o', 'gamepad', 'keyboard-o', 'flag-o', 'flag-checkered', 'terminal', 'code', 'mail-reply-all', 'reply-all', 'star-half-empty', 'star-half-full', 'star-half-o', 'location-arrow', 'crop', 'code-fork', 'unlink', 'chain-broken', 'question', 'info', 'exclamation', 'superscript', 'subscript', 'eraser', 'puzzle-piece', 'microphone', 'microphone-slash', 'shield', 'calendar-o', 'fire-extinguisher', 'rocket', 'maxcdn', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-circle-down', 'html5', 'css3', 'anchor', 'unlock-alt', 'bullseye', 'ellipsis-h', 'ellipsis-v', 'rss-square', 'play-circle', 'ticket', 'minus-square', 'minus-square-o', 'level-up', 'level-down', 'check-square', 'pencil-square', 'external-link-square', 'share-square', 'compass', 'toggle-down', 'caret-square-o-down', 'toggle-up', 'caret-square-o-up', 'toggle-right', 'caret-square-o-right', 'euro', 'eur', 'gbp', 'dollar', 'usd', 'rupee', 'inr', 'cny', 'rmb', 'yen', 'jpy', 'ruble', 'rouble', 'rub', 'won', 'krw', 'bitcoin', 'btc', 'file', 'file-text', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'thumbs-up', 'thumbs-down', 'youtube-square', 'youtube', 'xing', 'xing-square', 'youtube-play', 'dropbox', 'stack-overflow', 'instagram', 'flickr', 'adn', 'bitbucket', 'bitbucket-square', 'tumblr', 'tumblr-square', 'long-arrow-down', 'long-arrow-up', 'long-arrow-left', 'long-arrow-right', 'apple', 'windows', 'android', 'linux', 'dribbble', 'skype', 'foursquare', 'trello', 'female', 'male', 'gittip', 'gratipay', 'sun-o', 'moon-o', 'archive', 'bug', 'vk', 'weibo', 'renren', 'pagelines', 'stack-exchange', 'arrow-circle-o-right', 'arrow-circle-o-left', 'toggle-left', 'caret-square-o-left', 'dot-circle-o', 'wheelchair', 'vimeo-square', 'turkish-lira', 'try', 'plus-square-o', 'space-shuttle', 'slack', 'envelope-square', 'wordpress', 'openid', 'institution', 'bank', 'university', 'mortar-board', 'graduation-cap', 'yahoo', 'google', 'reddit', 'reddit-square', 'stumbleupon-circle', 'stumbleupon', 'delicious', 'digg', 'pied-piper', 'pied-piper-alt', 'drupal', 'joomla', 'language', 'fax', 'building', 'child', 'paw', 'spoon', 'cube', 'cubes', 'behance', 'behance-square', 'steam', 'steam-square', 'recycle', 'automobile', 'car', 'cab', 'taxi', 'tree', 'spotify', 'deviantart', 'soundcloud', 'database', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'file-powerpoint-o', 'file-photo-o', 'file-picture-o', 'file-image-o', 'file-zip-o', 'file-archive-o', 'file-sound-o', 'file-audio-o', 'file-movie-o', 'file-video-o', 'file-code-o', 'vine', 'codepen', 'jsfiddle', 'life-bouy', 'life-buoy', 'life-saver', 'support', 'life-ring', 'circle-o-notch', 'ra', 'rebel', 'ge', 'empire', 'git-square', 'git', 'hacker-news', 'tencent-weibo', 'qq', 'wechat', 'weixin', 'send', 'paper-plane', 'send-o', 'paper-plane-o', 'history', 'genderless', 'circle-thin', 'header', 'paragraph', 'sliders', 'share-alt', 'share-alt-square', 'bomb', 'soccer-ball-o', 'futbol-o', 'tty', 'binoculars', 'plug', 'slideshare', 'twitch', 'yelp', 'newspaper-o', 'wifi', 'calculator', 'paypal', 'google-wallet', 'cc-visa', 'cc-mastercard', 'cc-discover', 'cc-amex', 'cc-paypal', 'cc-stripe', 'bell-slash', 'bell-slash-o', 'trash', 'copyright', 'at', 'eyedropper', 'paint-brush', 'birthday-cake', 'area-chart', 'pie-chart', 'line-chart', 'lastfm', 'lastfm-square', 'toggle-off', 'toggle-on', 'bicycle', 'bus', 'ioxhost', 'angellist', 'cc', 'shekel', 'sheqel', 'ils', 'meanpath', 'buysellads', 'connectdevelop', 'dashcube', 'forumbee', 'leanpub', 'sellsy', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'cart-plus', 'cart-arrow-down', 'diamond', 'ship', 'user-secret', 'motorcycle', 'street-view', 'heartbeat', 'venus', 'mars', 'mercury', 'transgender', 'transgender-alt', 'venus-double', 'mars-double', 'venus-mars', 'mars-stroke', 'mars-stroke-v', 'mars-stroke-h', 'neuter', 'facebook-official', 'pinterest-p', 'whatsapp', 'server', 'user-plus', 'user-times', 'hotel', 'bed', 'viacoin', 'train', 'subway', 'medium');
	}

	/**
	 * Liv icons
	 */
	public static function livicons() {
		return array('at','balloons','bank','bomb','calculator','folders','ice-cream','medkit','paper-plane','wine','address-book','adjust','alarm','albums','align-center','align-justify','align-left','align-right','anchor','android','angle-double-down','angle-double-left','angle-double-right','angle-double-up','angle-down','angle-left','angle-right','angle-up','angle-wide-down','angle-wide-left','angle-wide-right','angle-wide-up','apple','apple-logo','archive-add','archive-extract','arrow-circle-down','arrow-circle-left','arrow-circle-right','arrow-circle-up','arrow-down','arrow-left','arrow-right','arrow-up','asterisk','balance','ban','barchart','barcode','battery','beer','bell','bing','biohazard','bitbucket','blogger','bluetooth','bold','bolt','bookmark','bootstrap','briefcase','brightness-down','brightness-up','brush','bug','calendar','camcoder','camera','camera-alt','car','caret-down','caret-left','caret-right','caret-up','cellphone','certificate','check','check-circle','check-circle-alt','checked-off','checked-on','chevron-down','chevron-left','chevron-right','chevron-up','chrome','circle','circle-alt','clapboard','clip','clock','cloud','cloud-bolts','cloud-down','cloud-rain','cloud-snow','cloud-sun','cloud-up','code','collapse-down','collapse-up','columns','comment','comments','compass','concrete5','connect','credit-card','crop','css3','dashboard','desktop','deviantart','disconnect','doc-landscape','doc-portrait','download','download-alt','dribbble','drop','dropbox','edit','exchange','expand-left','expand-right','external-link','eye-close','eye-open','eyedropper','facebook','facebook-alt','file-export','file-import','film','filter','fire','firefox','flag','flickr','flickr-alt','folder-add','folder-flag','folder-lock','folder-new','folder-open','folder-remove','font','gear','gears','ghost','gift','github','github-alt','glass','globe','google-plus','google-plus-alt','hammer','hand-down','hand-left','hand-right','hand-up','heart','heart-alt','help','home','html5','ie','image','inbox','inbox-empty','inbox-in','inbox-out','indent-left','indent-right','info','instagram','ios','italic','jquery','key','lab','laptop','leaf','legal','linechart','link','linkedin','linkedin-alt','list','list-ol','list-ul','livicon','location','lock','magic','magic-alt','magnet','mail','mail-alt','map','medal','message-add','message-flag','message-in','message-lock','message-new','message-out','message-remove','microphone','minus','minus-alt','money','moon','more','morph-c-o','morph-c-s','morph-c-t-down','morph-c-t-left','morph-c-t-right','morph-c-t-up','morph-o-c','morph-o-s','morph-o-t-down','morph-o-t-left','morph-o-t-right','morph-o-t-up','morph-s-c','morph-s-o','morph-s-t-down','morph-s-t-left','morph-s-t-right','morph-s-t-up','morph-t-down-c','morph-t-down-o','morph-t-down-s','morph-t-left-c','morph-t-left-o','morph-t-left-s','morph-t-right-c','morph-t-right-o','morph-t-right-s','morph-t-up-c','morph-t-up-o','morph-t-up-s','move','music','myspace','new-window','notebook','opera','pacman','paypal','pen','pencil','phone','piechart','piggybank','pin-off','pin-on','pinterest','pinterest-alt','plane-down','plane-up','playlist','plus','plus-alt','presentation','printer','qrcode','question','quote-left','quote-right','raphael','recycled','reddit','redo','refresh','remove','remove-alt','remove-circle','resize-big','resize-big-alt','resize-horizontal','resize-horizontal-alt','resize-small','resize-small-alt','resize-vertical','resize-vertical-alt','responsive','responsive-menu','retweet','rocket','rotate-left','rotate-right','rss','safari','sandglass','save','scissors','screen-full','screen-full-alt','screen-small','screen-small-alt','screenshot','search','servers','settings','share','shield','shopping-cart','shopping-cart-in','shopping-cart-out','shuffle','sign-in','sign-out','signal','sitemap','sky-dish','skype','sort','sort-down','sort-up','soundcloud','speaker','spinner-five','spinner-four','spinner-one','spinner-seven','spinner-six','spinner-three','spinner-two','star-empty','star-full','star-half','stopwatch','striked','stumbleupon','stumbleupon-alt','sun','table','tablet','tag','tags','tasks','text-decrease','text-height','text-increase','text-size','text-width','thermo-down','thermo-up','thumbnails-big','thumbnails-small','thumbs-down','thumbs-up','timer','trash','tree','trophy','truck','tumblr','twitter','twitter-alt','umbrella','underline','undo','unlink','unlock','upload','upload-alt','user','user-add','user-ban','user-flag','user-remove','users','users-add','users-ban','users-remove','vector-circle','vector-curve','vector-line','vector-polygon','vector-square','video-backward','video-eject','video-fast-backward','video-fast-forward','video-forward','video-pause','video-play','video-play-alt','video-step-backward','video-step-forward','video-stop','vimeo','vk','warning','warning-alt','webcam','wifi','wifi-alt','windows','windows8','wordpress','wordpress-alt','wrench','xing','yahoo','youtube','zoom-in','zoom-out');
	}

	/**
	 * Animate.css animations
	 */
	public static function animations() {
		return array('bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp', 'bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp', 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig', 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRight', 'fadeOutRightBig', 'fadeOutUp', 'fadeOutUpBig', 'flip', 'flipInX', 'flipInY', 'flipOutX', 'flipOutY', 'lightSpeedIn', 'lightSpeedOut', 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight', 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight', 'hinge', 'rollIn', 'rollOut', 'zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp', 'zoomOut', 'zoomOutDown', 'zoomOutLeft', 'zoomOutRight', 'zoomOutUp', 'bounce', 'flash', 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble');
	}

	public static function animations_in() {
			return array('bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp', 'fadeIn', 'fadeInDown', 'fadeInDownBig', 'fadeInLeft', 'fadeInLeftBig', 'fadeInRight', 'fadeInRightBig', 'fadeInUp', 'fadeInUpBig', 'flipInX', 'flipInY', 'lightSpeedIn', 'rotateIn', 'rotateInDownLeft', 'rotateInDownRight', 'rotateInUpLeft', 'rotateInUpRight', 'rollIn', 'zoomIn', 'zoomInDown', 'zoomInLeft', 'zoomInRight', 'zoomInUp');
		}
		
	public static function animations_out() {
			return array('bounceOut', 'bounceOutDown', 'bounceOutLeft', 'bounceOutRight', 'bounceOutUp', 'fadeOut', 'fadeOutDown', 'fadeOutDownBig', 'fadeOutLeft', 'fadeOutLeftBig', 'fadeOutRight', 'fadeOutRightBig', 'fadeOutUp', 'fadeOutUpBig', 'flipOutX', 'flipOutY', 'lightSpeedOut', 'rotateOut', 'rotateOutDownLeft', 'rotateOutDownRight', 'rotateOutUpLeft', 'rotateOutUpRight', 'rollOut', 'zoomOut', 'zoomOutDown', 'zoomOutLeft', 'zoomOutRight', 'zoomOutUp');
		}

	/**
	 * Easing script animations
	 */
	public static function easings() {
		return array('linear', 'swing', 'jswing', 'easeInQuad', 'easeInCubic', 'easeInQuart', 'easeInQuint', 'easeInSine', 'easeInExpo', 'easeInCirc', 'easeInElastic', 'easeInBack', 'easeInBounce', 'easeOutQuad', 'easeOutCubic', 'easeOutQuart', 'easeOutQuint', 'easeOutSine', 'easeOutExpo', 'easeOutCirc', 'easeOutElastic', 'easeOutBack', 'easeOutBounce', 'easeInOutQuad', 'easeInOutCubic', 'easeInOutQuart', 'easeInOutQuint', 'easeInOutSine', 'easeInOutExpo', 'easeInOutCirc', 'easeInOutElastic', 'easeInOutBack', 'easeInOutBounce');
	}
}

