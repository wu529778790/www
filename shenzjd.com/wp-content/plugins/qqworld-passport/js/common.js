if (typeof qqworld_passport == 'undefined') var qqworld_passport = {};

qqworld_passport.common = function() {
	var $ = jQuery, _this = this;

	this.action = {};
	this.action.get_current_tab = function() {
		var link = window.location.href;
		var parser = document.createElement('a');
		parser.href = link;
		var hash = parser.hash;
		if (hash) {
			tab = hash.replace('#', '');
		} else tab = 'regular'
		return tab;
	};
	this.action.set_current_tab = function() {
		var tab = _this.action.get_current_tab();
		$('#'+tab).click();
	};

	this.create = {};
	this.create.events = function() {
		$(document).on('click', '#qqworld-passport-container .nav-tab', function() {
			// tab
			if ($(this).hasClass('nav-tab-active')) return;
			$('#qqworld-passport-container .nav-tab').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');
			// section
			var index = $('#qqworld-passport-container .nav-tab').index(this);
			$('#qqworld-passport-container .nav-section').slideUp('normal').eq(index).slideDown('normal');
		});
	};
	this.create.init = function() {
		_this.create.events();
		_this.action.set_current_tab();
	};

	this.create.init();
};

/*
** randomWord 产生任意长度随机字母数字组合
** randomFlag-是否任意长度 min-任意长度最小位[固定位数] max-任意长度最大位
** xuanfeng 2014-08-28
*/
 
qqworld_passport.randomWord = function(randomFlag, min, max){
	var str = "",
		range = min,
		arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
 
	// 随机产生
	if(randomFlag){
		range = Math.round(Math.random() * (max-min)) + min;
	}
	for(var i=0; i<range; i++){
		pos = Math.round(Math.random() * (arr.length-1));
		str += arr[pos];
	}
	return str;
};

qqworld_passport.admin = {
	wechat: function() {
		var $ = jQuery;
		$(document).on('change', '#desktop-login-mode', function() {
			if ($(this).val() == 'native') {
				$('tr.open').show();
			} else {
				$('tr.open').hide();
			}
		});
		$(document).on('change', '#login-mode', function() {
			if ($(this).val() == 'server') {
				$('#module-wechat-relay-server-domain-container').fadeOut('normal');
				if ($('#login').length) {
					$('#module-wechat-force-login-container').fadeIn('normal');
					$('#module-wechat-Invite-follow-container').fadeIn('normal');
				}
			} else {
				$('#module-wechat-relay-server-domain-container').fadeIn('normal');
				if ($('#login').length) {
					$('#module-wechat-force-login-container').fadeOut('normal');
					$('#module-wechat-Invite-follow-container').fadeOut('normal');
				}
			}
		});
		
	}
};

jQuery(function($) {
	qqworld_passport.common();
	new qqworld_passport.admin.wechat();
});