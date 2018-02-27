var process_env = 'Dev';
var configUrl = {
    'dev': 'http://10.81.1.65:9030', //Dev
    'uat': 'http://uat.jkjingji.com', //UAT
    'prod': 'http://gwtprd.jkjingji.com' //Production
}

var _base = configUrl[process_env] || '',
    _baseUrl = _base + '/micromall/share/order/',
    //	_staticImgUrl = 'http://jkimg0626-10054369.cossh.myqcloud.com/jkjj/shareGift/';//静态图片路径
    //var _base = 'http://10.81.12.51:8080', //建领
    //	_baseUrl = _base + '/micromall/share/order/',
    _staticImgUrl = 'http://jkimg0626-10054369.cossh.myqcloud.com/jkjj/shareGift/'; //静态图片路径
var Url = {
    SEND_MESSAGE: _baseUrl + 'sendMessage.do', //获取短信验证码
    ADD_PHONE: _baseUrl + 'addPhone.do', //绑定手机号码
    GET_USER_INFO: _baseUrl + 'getUserInfo.do', //个人微信信息
    GET_WITHDRAWAL: _baseUrl + 'getWithdrawal.do', //个人中心
    GET_REVARD: _baseUrl + 'getRewards.do', //奖励明细
    GET_REWARD_Q: _baseUrl + 'getForwardQ.do', //转发明细(企业号)
    GET_REWARD_F: _baseUrl + 'getForwardF.do', //转发明细(服务号)
    GET_WITHDRAWAL_Q: _baseUrl + 'withdrawalQ.do', //提现(企业号)
    GET_WITHDRAWAL_F: _baseUrl + 'withdrawalF.do', //提现(服务号)
    UPDATE_SHARE_USER: _baseUrl + 'updateShareUser.do', //服务号提现提交个人信息（更新名字和idCard）
    GET_INVITE_SERIAL_NO_Q: _baseUrl + 'getInviteSerialNoQ.do', //获取邀请参数(inviteSerialNo)(企业号)
    GET_INVITE_SERIAL_NO: _baseUrl + 'getInviteSerialNo.do', //获取邀请参数(inviteSerialNo)(服务号)
    GO_YIAN_PRODUCT: 'http://testproduct.baolianx.com/product/p/ya10004b?cc=jk003&userId=80e80efd08424680a77979ecb11b2623&agentId=1478831005886&isShare=1&branchCode=10150003&channelType=02', //跳转至易安宝贝产品购买页（测试）
    //	GO_YIAN_PRODUCT:'http://product.baolianx.com/product/p/ya10004b?cc=jk003&userId=62b8612ef25c4397bc117a67a8b1652e&agentId=1482993913271&isShare=1&branchCode=1254&channelType=02',//跳转至易安宝贝产品购买页(生产)
    SHARE_URL: _base + '/micromall/wechat/getOpenId.do?redirectUrl=http%3a%2f%2fuat.jkjingji.com%2factivity%2fshareGift%2fpages%2fserver%2factiveProduct.html' //分享链接地址
        //	SHARE_URL:_base+'/micromall/wechat/getOpenId.do?redirectUrl=http%3a%2f%2fgwtprd.jkjingji.com%2factivity%2fshareGift%2fpages%2fserver%2factiveProduct.html'//分享链接地址(生产)
}

process_env === 'dev' && document.write('<script src="http://uat.jkjingji.com/vconsole.min.js"></script>');