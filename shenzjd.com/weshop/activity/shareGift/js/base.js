//post请求
	function ajaxPost(url,data,successCallback,errorCallback){
		console.log('请求数据'+JSON.stringify(data))
		$.ajax({
			  url: url,
			  type: "post",
			  async: true,
			  data: JSON.stringify(data), 
			  contentType:"application/json",
			  timeout:5000,
			  beforeSend:function(){
				showLoading();
				},
			  complete:function(xhr,status){
				hideLoading();
				if(status=='timeout'){
				   xhr.abort();
				   alert("请求超时,请稍后重试");		　　　　　  
			　　　}
				},
			  success: successCallback || function(){},
			  error:errorCallback || function(){}
		})
	}
	//get请求
	function ajaxGet(url,successCallback,errorCallback){
		$.ajax({
			  url: url,
			  type: "post",
			  async: true,
			  contentType:'application/json',
			  beforeSend:function(){
				showLoading();
				},
			  complete:function(){
				hideLoading();
				},
			  success: successCallback,
			  error:errorCallback || {}
		})
	}
	//显示加载动画
	function showLoading() {
		if ($('body').find('.allcommomn_backdrop').length == 0) {
			$('body').append('<div class="allcommomn_backdrop"><div class="loadingimg"><img src="'+ _staticImgUrl+'img/loading.gif"/></div></div>');
		} else{
			$('body').find('.allcommomn_backdrop').show();
		}
	}
	//隐藏加载动画
	function hideLoading() {
		$('body').find('.allcommomn_backdrop').hide();
	}
//	设置cookie
	function setCookie(c_name,value,expiredays)
		{
		var exdate=new Date()
		exdate.setDate(exdate.getDate()+expiredays)
		document.cookie=c_name+ "=" +escape(value)+
		((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
		}
//	获取cookie	
	function getCookie(c_name)
		{
		if (document.cookie.length>0)
		  {
		  c_start=document.cookie.indexOf(c_name + "=")
		  if (c_start!=-1)
		    { 
		    c_start=c_start + c_name.length+1 
		    c_end=document.cookie.indexOf(";",c_start)
		    if (c_end==-1) c_end=document.cookie.length
//		    alert(document.cookie.substring(c_start,c_end))
		    return unescape(document.cookie.substring(c_start,c_end))
		    } 
		  }
		return ""
		}	
//格式化时间	
function dateFormat(date) {
	var d = new Date(date);
	var year = d.getFullYear();
	var month=d.getMonth()+1; 
	var day = d.getDate(); 
	if(month<10){ 
		month = "0"+month; 
	}
	if(day<10){ 
		day = "0"+day; 
	}
	return year + "年" + month+ "月" + day+ "日";
}
//获取url路径后的数据
	window.GetRequest = function(){
		var url = location.search; //获取url中"?"符后的字串
		console.log(url)
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
////禁止微信分享
//	function onBridgeReady(){
//		 //WeixinJSBridge.call('showOptionMenu');
//		 WeixinJSBridge.call('hideOptionMenu');  //禁止分享等动作
//	}
//	if (typeof WeixinJSBridge == "undefined"){
//		    if( document.addEventListener ){
//		        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
//		    }else if (document.attachEvent){
//		        document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
//		        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
//		    }
//	}else{
//		   onBridgeReady();
//	}
