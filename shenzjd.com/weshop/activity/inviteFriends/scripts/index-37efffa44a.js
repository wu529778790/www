! function() {
	var e = document.documentElement.clientHeight,
		n = document.getElementsByClassName("firLoad"),
		t = document.getElementsByClassName("secLoad"),
		i = document.getElementsByClassName("swiper-slide"),
		a = document.getElementsByClassName("story"),
		s = document.getElementsByClassName("button"),
		l = document.getElementsByClassName("share")[0],
		o = document.getElementsByClassName("autiobtn")[0];
	document.getElementsByClassName("swiper-container")[0].style.height = e + "px";
	for(var r = function(e, n) {
			var t = new RegExp("(^| )" + n + "( |$)");
			t.test(e.className) || (e.className = e.className.trim() + " " + n)
		}, c = function(e, n) {
			if(!e || 1 != e.nodeType) throw new Error("第一参数ele需要是一个DOM元素对象");
			if("string" != typeof n) throw new Error("第二参数必须为string类型");
			var t = new RegExp("(?:^| )" + n + "(?: |$)", "g");
			e.className = e.className.replace(t, "").trim()
		}, m = function(e) {
			for(var n = 0, t = e.previousSibling; t;) 1 == t.nodeType && n++, t = t.previousSibling;
			return n
		}, g = 0; g < n.length; g++) n[g].style.backgroundImage = n[g].dataset.url, r(i[0], "slide-move");
	var d = new Image;
	d.src = "images/WechatIMG9.jpeg", d.onload = function() {
		for(var e = 0; e < t.length; e++) t[e].style.backgroundImage = t[e].dataset.url;
//		document.getElementsByClassName("loading")[0].style.display = "none", r(i[0], "slide-move")
	};
	for(var g = 0; g < a.length; g++) ! function(e) {
		s[g].onclick = function() {
			r(a[e], "storyShow"), a[e].style.display = "block"
		}, a[g].onclick = function() {
			c(a[e], "storyShow"), window.setTimeout(function() {
				a[e].style.display = "none"
			}, 300)
		}
	}(g);
//	s[s.length - 1].onclick = function() {
//		l.style.display = "block"
//	}, l.onclick = function() {
//		l.style.display = "none"
//	}, o.onclick = function() {
//		var e = document.getElementById("media");
//		null !== e && (e.paused ? (e.play(), r(o, "rotate")) : (e.pause(), c(o, "rotate")))
//	};
	o.onclick = function() {
		var e = document.getElementById("media");
		null !== e && (e.paused ? (e.play(), r(o, "rotate")) : (e.pause(), c(o, "rotate")))
	}
	var u = new Swiper(".swiper-container", {
		paginationClickable: !0,
		mode: "vertical",
		onSlideChangeEnd: function() {
			for(var e = u.activeSlide(), n = (m(e), 0); n < i.length; n++) c(i[n], "slide-move");
			for(var n = 0; n < a.length; n++) c(a[n], "storyShow"), a[n].style.display = "none";
			r(e, "slide-move")
		}
	})
	
	
	
//			ios自动播放音频
			var audo = document.getElementById('media');
			audo.play();
			document.addEventListener('WeixinJSBridgeReady', function() {
			    audo.play();
			}, false);
			
			var agentCode = getRequest().agentCode || '';
			function goUrl(){
				window.location.href = Url.CERTIFICATION+agentCode;
			}
			var goButton = document.getElementById('entry');
			goButton.addEventListener('click',function(){
				goUrl()
			})
			//获取url路径后的数据
			function getRequest(){
				var url = location.search; //获取url中"?"符后的字串
				var theRequest = new Object();
				if (url.indexOf("?") != -1) {
					var str = url.substr(1);
					strs = str.split("&");
					for(var i = 0; i < strs.length; i ++) {
						theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
					}
				}
				return theRequest;
			}		
//		微信分享
	$(function(){	
		 var url = location.href.split('#')[0],
		 	 shareUrl = Url.SHARE_URL+agentCode,
		 	 shareImgUrl = Url.SHARE_IMG_LOGO;
		 share(url,'共享发展 同创未来','君康保险经纪 团结所有关注客户需求的保险精英',shareImgUrl,shareUrl);//分享事件 ，引入Wx.js文件即可
	})	
	function share(url,title,desc,shareImgUrl,shareUrl){
		$.ajax({
			  async:false,
			  type: 'post',
			  url: Url.GET_SINGNATURE,
			  contentType:"application/json",
			  data:	'{"url":"'+url+'"}',
			  error:function(data){
			  	return ;
			  },
			  success:function(data){
				  wx.config({
				      debug: false,
				      appId: data.data.appId,
				      timestamp: data.data.jssdkReqData.timestamp,
				      nonceStr: data.data.jssdkReqData.noncestr,
				      signature: data.data.jssdkReqData.signature,
				      jsApiList: [
				        'checkJsApi',
				        'onMenuShareTimeline',
				        'onMenuShareAppMessage',
				        'onMenuShareQQ',
				        'onMenuShareWeibo',
				        'onMenuShareQZone',
				        'onMenuShareWechat',
				        'hideMenuItems',
				        'showMenuItems',
				        'hideAllNonBaseMenuItem',
				        'showAllNonBaseMenuItem',
				        'translateVoice',
				        'startRecord',
				        'stopRecord',
				        'onVoiceRecordEnd',
				        'playVoice',
				        'onVoicePlayEnd',
				        'pauseVoice',
				        'stopVoice',
				        'uploadVoice',
				        'downloadVoice',
				        'chooseImage',
				        'previewImage',
				        'uploadImage',
				        'downloadImage',
				        'getNetworkType',
				        'openLocation',
				        'getLocation',
				        'hideOptionMenu',
				        'showOptionMenu',
				        'closeWindow',
				        'scanQRCode',
				        'chooseWXPay',
				        'openProductSpecificView',
				        'addCard',
				        'chooseCard',
				        'openCard'
				      ]
				  });
				  wx.ready(function(){
						    // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
						 wx.onMenuShareTimeline({//分享到朋友圈
							    title: desc, // 分享标题
							    desc: desc,
							    link: shareUrl, // 分享链接
							    imgUrl: shareImgUrl, // 分享图标
							    success: function () { 
							        // 用户确认分享后执行的回调函数
//								        console.log(shareUrl)
							    },
							    cancel: function () { 
							        // 用户取消分享后执行的回调函数
//								        console.log(shareUrl)
							    }
							});
						 wx.onMenuShareAppMessage({//分享给朋友
							    title: title, // 分享标题
							    desc: desc, // 分享描述
							    link: shareUrl, // 分享链接
							    imgUrl:shareImgUrl, // 分享图标
							    type: '', // 分享类型,music、video或link，不填默认为link
							    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
							    success: function () { 
							        // 用户确认分享后执行的回调函数
							    },
							    cancel: function () { 
							        // 用户取消分享后执行的回调函数
							    }
							});
						if(wx.onMenuShareWechat && typeof(wx.onMenuShareWechat) == 'function'){
							wx.onMenuShareWechat({//分享到微信
							    title: title, // 分享标题
							    desc: desc, // 分享描述
							    link: shareUrl, // 分享链接
							    imgUrl:shareImgUrl, // 分享图标
							    success: function () {
							        // 用户确认分享后执行的回调函数
							    },
							    cancel: function () {
							        // 用户取消分享后执行的回调函数
							    }
							});
						}
						 wx.onMenuShareQQ({//分享到QQ
							    title: title, // 分享标题
							    desc: desc, // 分享描述
							    link: shareUrl, // 分享链接
							    //imgUrl: data.imgUrl+imgName, // 分享图标
							    success: function () { 
							       // 用户确认分享后执行的回调函数
							    },
							    cancel: function () { 
							       // 用户取消分享后执行的回调函数
							    }
							});
						 wx.onMenuShareWeibo({//享到腾讯微博
							    title: title, // 分享标题
							    desc: desc, // 分享描述
							    link: shareUrl, // 分享链接
							    //imgUrl: data.imgUrl+imgName, // 分享图标
							    success: function () { 
							       // 用户确认分享后执行的回调函数
							    },
							    cancel: function () { 
							        // 用户取消分享后执行的回调函数
							    }
							});
						 wx.onMenuShareQZone({//分享到QQ空间
							    title: desc, // 分享标题
							    desc: desc, // 分享描述
							    link: shareUrl, // 分享链接
							    //imgUrl: data.imgUrl+imgName, // 分享图标
							    success: function () { 
							       // 用户确认分享后执行的回调函数
							    },
							    cancel: function () { 
							        // 用户取消分享后执行的回调函数
							    }
							});
						 wx.error(function(res){
					         // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
					     });
					 });
		  }
		});
	}	
}();
