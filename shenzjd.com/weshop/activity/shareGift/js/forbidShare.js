function onBridgeReady(){
	 //WeixinJSBridge.call('showOptionMenu');
	 WeixinJSBridge.call('hideOptionMenu');  //禁止分享等动作
}
if (typeof WeixinJSBridge == "undefined"){
	    if( document.addEventListener ){
	        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	    }else if (document.attachEvent){
	        document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
	        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	    }
}else{
	   onBridgeReady();
}
