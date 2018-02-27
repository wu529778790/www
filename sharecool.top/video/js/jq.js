var $MH={
	limit: 10,
	width:960,
	height: 170,
	style: 'pic',
	setCookie: function(name, value) {
		var Days = 365;
		var exp = new Date;
		exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
		document.cookie = name + ("=" + (value) + ";expires=" + exp.toGMTString() + ";path=/;");
	},
	getCookie: function(name) {
		var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
		if (arr != null) {
			return (arr[2]);
		}
		return null;
	},
	getDc: function(){
		var x,y=document.getElementById('HISTORY');
		return y;
	},
	piclist: function (){
		var a = $MH.getCookie("HISTORY"), c = 1,img_li = "";
		a = (a !='' && ''+a != 'null') ? $MH.tryjosn(a) : {video:[]};
		for(var i=0;i<a.video.length;i++){
			if(c>$MH.limit){break;}
			if(a.video[i].link && a.video[i].pic && a.video[i].name){
			img_li += "<li style=\"width:86px;height:142px;text-align:center;margin:3px 0 3px 9px !important;float:left;display:inline;overflow:hidden\"><div><a href=\"" + a.video[i].link + "\" target=\"_self\"><img width=\"86\" height=\"120\" src=\"" + a.video[i].pic + "\" alt=\"" + a.video[i].name + "\" border=\"0\"/></a></div>\
						<p style=\"margin:0;padding:0\"><a href=\"" + a.video[i].link + "\" target=\"_self\" style=\"font-size:12px;color:#000;line-height:24px;height:24px;text-decoration:none\">" + a.video[i].name + "</a></p></li>"
				c++;
			}
		}
		img_li = img_li != "" ? img_li : '<li style="width:100%;text-align:center;line-height:'+($MH.height-25)+'px;color:red">\u6CA1\u6709\u8BB0\u5F55</li>';
		return "<div id=\"mh-box\" style=\"border:1px solid #ccc;height:"+$MH.height+"px;overflow:hidden\"><div style=\"height:24px;line-height:24px\" id=\"mh-title\"><div style=\"float:right;margin-right:5px;display:inline\"><a href=\"javascript:void(0)\" onClick=\"$MH.showHistory(2);\" style=\"font-size:12px;color: #000000;line-height:24px;height:24px;text-decoration:none\">\u6E05\u7A7A</a>&nbsp;<a href=\"javascript:void(0)\" onClick=\"$MH.showHistory(1);\" style=\"font-size:12px;color: #000000;line-height:24px;height:24px;text-decoration:none\">\u9690\u85CF</a></div><strong style=\"padding-left:5px;font-size:14px\">\u6211\u7684\u89C2\u770B\u5386\u53F2</strong></div><div id=\"mh-ul\"><ul style=\"margin:0px;border:0px;padding:0\">" + img_li + "</ul><div style=\"clear:both\"></div></div></div>";
	},
	fontlist: function (){
		var a = $MH.getCookie("HISTORY"), c = 1,img_li = "";
		a = (a !='' && ''+a != 'null') ? $MH.tryjosn(a)  : {video:[]} ;
		for(var i=0;i<a.video.length;i++){
			if(c>$MH.limit){break;}
			if(a.video[i].link && a.video[i].pic && a.video[i].name){
			img_li += "<li><a href=\"" + a.video[i].link + "\" target=\"_self\"><i class=\"ico libg\"></i><i class=\"num\">"+c+".</i>" + a.video[i].name + "</a></li>"
				c++;
			}
		}
		img_li = img_li != "" ? img_li : '<li style="text-align:center;color:red;">\u6CA1\u6709\u8BB0\u5F55</li>';
		return "<div id=\"mh-box\" class=\"clearfix\"><div id=\"mh-title\" class=\"clearfix\">\u6211\u7684\u89C2\u770B\u5386\u53F2</div><div id=\"mh-ul\"><ul class=\"clearfix\">" + img_li + "</ul></div></div>";
	},
	WriteHistoryBox: function(w,h,c){
		document.write('<div id="HISTORY" style="width:'+($MH.width=w)+'px;"></div>');
		$MH.height=h;$MH.style= c=='font' ? 'font' : 'pic';
		this.showHistory();
	},
	showHistory: function(ac) {
		var a = $MH.getCookie("HISTORY"),dc=$MH.getDc();
		var ishistory = $MH.getCookie("ishistory");
		if(!dc) return;
		if (ac == 1) {
			if (ishistory != 1) {
				$MH.setCookie("ishistory", 1);
				ishistory = 1;
			} else {
				$MH.setCookie("ishistory", 0);
				ishistory = 0;
			}
		}
		if (ac == 2) {
			ishistory = 0;
			$MH.setCookie("ishistory", 0);
			$MH.setCookie("HISTORY", 'null');
		}
		if(ishistory == 1){
			dc.innerHTML = $MH[$MH.style+'list']();
			dc.style.display = "";
		} else {
			dc.innerHTML = $MH[$MH.style+'list']();;
			dc.style.display = "";
		}
	},
	recordHistory: function(video){
		if(video.link.indexOf('http://')==-1 || window.max_Player_File) return;
		var a = $MH.getCookie('HISTORY'), b = new Array(), c = 1;
		if(a !='' && a != null && a != 'null'){
			a = $MH.tryjosn(a);
			for(var i=0;i<a.video.length;i++){
				if(c>$MH.limit){break;}
				if(video.link != a.video[i].link && a.video[i].pic){b.push('{"name":"'+ $MH.u8(a.video[i].name) +'","link":"'+ $MH.u8(a.video[i].link) +'","pic":"'+ $MH.u8(a.video[i].pic) +'"}');c++;}
			}
		}
		b.unshift('{"name":"'+ $MH.u8(video.name) +'","link":"'+ $MH.u8(video.link) +'","pic":"'+ $MH.u8(video.pic) +'"}');
		$MH.setCookie("HISTORY",'{video:['+ b.join(",") +']}');
		b = null;
		a=null;
	},
	u8: function (s){
		return unescape(escape(s).replace(/%u/ig,"\\u")).replace(/;/ig,"\\u003b");
	},
	tryjosn: function (json){
		try{
			return eval('('+ json +')');
		}catch(ig){
			return {video:[]};
		}
	}
};

var MAC={
	'Url': document.URL,
	'Title': document.title,
	'Copy': function(s){
		if (window.clipboardData){ window.clipboardData.setData("Text",s); } 
		else{
			if( $("#mac_flash_copy").get(0) ==undefined ){ $('<div id="mac_flash_copy"></div>'); } else {$('#mac_flash_copy').html(''); }
			$('#mac_flash_copy').html('<embed src='+SitePath+'"images/_clipboard.swf" FlashVars="clipboard='+escape(s)+'" width="0" height="0" type="application/x-shockwave-flash"></embed>');
		}
		alert("复制成功");
	},
	'Fav': function(u,s){
		try{ window.external.addFavorite(u, s);}
		catch (e){
			try{window.sidebar.addPanel(s, u, "");}catch (e){alert("加入收藏出错，请使用键盘Ctrl+D进行添加");}
		}
	},
	'App': function(){
		$('.ewm').append("<img src=\"http://qr.topscan.com/api.php?text="+document.URL+"\" />");
	},
	'Open': function(u,w,h){
		window.open(u,'macopen1','toolbars=0, scrollbars=0, location=0, statusbars=0,menubars=0,resizable=yes,width='+w+',height='+h+'');
	},
	'Error':function(tab,id,name){
		MAC.Open(SitePath+"inc/err.html?tab="+tab+"&id="+id+"&name="+ encodeURI(name),400,220);
	},
		'Score': {
		'ajaxurl': 'inc/ajax.php?ac=score',
		'Show':function($f,$tab,$id){
			var str = '';
			if($f==1){
				str = '<div style="padding:5px 10px;border:1px solid #CCC"><div style="color:#000"><strong>我要评分(感谢参与评分，发表您的观点)</strong></div><div>共 <strong style="font-size:14px;color:red" id="star_count"> 0 </strong> 个人评分， 平均分 <strong style="font-size:14px;color:red" id="star_pjf"> 0 </strong>， 总得分 <strong style="font-size:14px;color:red" id="star_all"> 0 </strong></div><div>';
				for(var i=1;i<=10;i++){ str += '<input type="radio" name="score" id="rating'+i+'" value="1"/><label for="rating'+i+'">'+i+'</label>'; }
				str += '&nbsp;<input type="button" value=" 评 分 " id="scoresend" style="width:55px;height:21px"/></div></div>';
			}
			else{
				str += '<div class="star"><span id="star_tip"></span><ul><li class="star_current"></li>';
				for(var i=1;i<=10;i++){ str += '<li><a value="'+i+'" class="star_'+i+'">'+i+'</a></li>'; }
				str += '</ul>';
				str +='<p><span id="star_shi">0</span><span id="star_ge">.0</span><span class="star_no">(<label id="star_count">0</label>次评分)</span></p></div>';
			}
			document.write(str);
			$.ajax({type: 'get',url: SitePath + this.ajaxurl + '&tab='+$tab+'&id='+$id,timeout: 5000,
				error: function(){
					$(".star").html('评分加载失败');
				},
				success: function($r){
					MAC.Score.View($r);
					if($f==1){
						$("#scoresend").click(function(){
							var rc=false;
							for(var i=1;i<=10;i++){ if( $('#rating'+i).get(0).checked){ rc=true; break; } }
							if(!rc){alert('你还没选取分数');return;}
							MAC.Score.Send( '&tab='+$tab+'&id='+$id+'&score='+i );
						});
					}
					else{
						
						var tip=new Array("","很差，浪费生命","很差，浪费生命","不喜欢","不喜欢","一般，不妨一看","一般，不妨一看","一般，不妨一看","喜欢，值得推荐","喜欢，值得推荐","非常喜欢，不容错过");
						$(".star>ul>li>a").mouseover(function(){
							$("#star_hover").html($(this).attr('value')+"分 ");
							$("#star_tip").html(tip[$(this).attr('value')]);
							$(".star_current").css("display","none");
						});
						$(".star>ul>li>a").mouseout(function(){
							$("#star_hover").html("");
							$("#star_tip").html("");
							$(".star_current").css("display","block");
						});
						$(".star>ul>li>a").click(function(){
							MAC.Score.Send( '&tab='+$tab+'&id='+$id+'&score='+$(this).attr('value') );
						});
					}
				}
			});
		},
		'Send':function($u){
			$.ajax({type: 'get',url: SitePath + this.ajaxurl + $u,timeout: 5000,
				error: function(){
					$(".star").html('评分加载失败');
				},
				success: function($r){
					if($r=="haved"){
						alert('你已经评过分啦');
					}else{
						MAC.Score.View($r);
					}
				}
			});
		},
		'View':function($r){
			$r = eval('(' + $r + ')');
			$("#rating"+Math.floor($r.score)).attr('checked',true);
			$("#star_count").text( $r.scorenum );
			$("#star_all").text( $r.scoreall );
			$("#star_pjf").text( $r.score );
			$("#star_shi").text( parseInt($r.score) );
			$("#star_ge").text( "." +  ($r.score.toString().split('.')[1]==undefined ? '0' : $r.score.toString().split('.')[1]) );
			$(".star_current").width($r.score*10);
		}
	},
	'Hits':function(tab,id){
		$.get("/inc/ajax.php?ac=hits&tab="+tab+"&id="+id,function(r){$('#hits').html(r);});
	},
	'Digg': {
		'Show': function($ajaxurl) {
			
			$('#digg').click(function(){
				MAC.Digg.Send($ajaxurl,'vod','up');
			});
			$('#tread').click(function(){
				MAC.Digg.Send($ajaxurl,'vod','down');
			});
			if($(".digg").length || $(".tread").length){
				MAC.Digg.Send($ajaxurl,'vod','');
			}
		},	
		'Send': function($ajaxurl,$tab,$ac){
			$.ajax({type: 'get',timeout: 5000, url: $ajaxurl + "&tab="+$tab+"&ac2="+$ac ,
				error: function(){
				//	alert('顶踩数据加载失败');
				},
				success: function($r){
					if($r=="haved"){
						alert('你已经评过分啦');
					}else{
						MAC.Digg.View($tab,$r);
					}
				}
			});
		},
		'View': function ($tab,$r){
			if($tab == 'vod'){
				$("#digg_num").html($r.split(':')[0]);
				$("#tread_num").html($r.split(':')[1]);
			}
		}
	}
};
function setTab(name, name2, cursel, n) {
    for (i = 1; i <= n; i++) {
        var menu = document.getElementById(name + i);
        var con = document.getElementById(name2 + i);
        menu.className = i == cursel ? "on": "";
        con.style.display = i == cursel ? "block": "none"
    }
};

function downxl(){
	var ua = navigator.userAgent.toLowerCase();	
	if (/iphone|ipad|ipod/.test(ua)) {
		    window.location.href='https://itunes.apple.com/cn/app/xun-lei-zui-kuai-xia-zai-gong-ju/id593444693?mt=8';	
	} else if (/android/.test(ua)) {
		    window.location.href='http://m.down.sandai.net/MobileThunder/Android_5.15.2.3820/XLWXguanwang.apk';	
	}else{
			window.location.href='http://down.sandai.net/thunderspeed/ThunderSpeed1.0.33.358.exe';	
	}
}
$(function(){
	 $("img.lazy").lazyload();
    $(".jqr").hover(function() {
        $(this).find(".lidown").addClass("block");
        $(this).parent().siblings().find(".lidown").removeClass("block")
    });
    $(".link").hover(function() {
        $(this).find(".textdown").fadeToggle(400);
        $(this).find(".bgbtn").fadeToggle(400)
    });
    $(".ops li").hover(function() {
        $(this).find(".up").toggleClass("down");
        $(this).toggleClass("down1");
        $(this).find(".opsdown").fadeToggle(400)
    });
    $(".dlzc li").hover(function() {
        $(this).children().next().fadeToggle(400)
    });
    $(".lei").click(function() {
        $(this).toggleClass("lei1");
        $(this).parent().parent().next().next().hide();
        $(this).parent().parent().next().toggle(0)
    });
    $(".sh").click(function() {
        $(this).prev().removeClass("lei1");
        $(this).parent().parent().next().hide();
        $(this).parent().parent().next().next().toggle(0)
    });
    $(".shclose").click(function() {
        $(this).parent().hide()
    });
    $('.link').hover(function() {
        $(this).find(".bgb").stop().animate({
            bottom: '0px',
            left: '0px'
        },
        {
            queue: false,
            duration: 400
        })
    },
    function() {
        $(this).find(".bgb").stop().animate({
            bottom: '-51px',
            left: '0px'
        },
        {
            queue: false,
            duration: 400
        })
    });
    $(".selectlist .ul-s li:not(.s)").hide;
    $(".slide").click(function() {
        $(this).toggleClass("v");
        $(this).next().children(".selectlist").find("li:not(.s)").fadeToggle(400)
    });
    $(window).scroll(function() {
        if ($(window).scrollTop() >= 300) {
            $('.gotop').fadeIn(400)
        } else {
            $('.gotop').fadeOut(400)
        }
    });
    $('.gotop').click(function() {
        $('html,body').animate({
            scrollTop: '0px'
        },
        800)
    });
//顶踩初始化
MAC.Digg.Show(SitePath+'inc/ajax.php?ac=digg&aid='+SiteAid+'&id='+SiteId);
//list	
	if($("#year").html()==""){
		$("#year").css({"border":"none","margin":"0px","padding":"0px"});
	}else{
	  $("#year").append("<span>X</span>");
	  $("#year").click(function(){
		window.location.href=location.href.replace($("#year").attr("val"),"");
	  });
	}
	if($("#area").html()==""){
		$("#area").css({"border":"none","margin":"0px","padding":"0px"});
	}else{
	  $("#area").append("<span>X</span>");
	  $("#area").click(function(){
		window.location.href=location.href.replace(encodeURI($("#area").attr("val")),"");
	  });
	}
	if($("#lang").html()==""){
		$("#lang").css({"border":"none","margin":"0px","padding":"0px"});
	}else{
	  $("#lang").append("<span>X</span>");
	  $("#lang").click(function(){
		window.location.href=location.href.replace(encodeURI($("#lang").attr("val")),"");
	  });
	}
	if($("#tags").html()==""){
		$("#tags").css({"border":"none","margin":"0px","padding":"0px"});
	}else{
	  $("#tags").append("<span>X</span>");
	  $("#tags").click(function(){
		window.location.href=location.href.replace(encodeURI($("#tags").attr("val")),"");
	  });
	}
	//集数处理
	var zji= parseInt($("#jishu").attr("ji"));
	var dji=$("#jishu ul").children("li").length;
	for(var i=1;i<(zji-dji+1);i++){
		$("#jishu .stab_list ul").append('<li><a title="等待更新" class="geng">第'+(dji+i)+'集</a></li>');
	}
	$(".geng").hover(function(){
		$(this).html(function(i,val){
		$(this).attr("val",val);
			return "等待更新";
		});  
	},function(){
	   $(this).html($(this).attr("val"));
	});
});