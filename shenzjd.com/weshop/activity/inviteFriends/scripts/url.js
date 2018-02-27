var process_env = 'Dev';
var configUrl = {
    'dev': 'http://10.81.1.65:9030', //Dev
    'uat': 'http://uat.jkjingji.com', //UAT
    'prod': 'http://gwtprd.jkjingji.com' //Production
}

var _base = configUrl[process_env] || '',
    _baseUrl = _base + '/micromall/invite/',
    Url = {
        GET_SINGNATURE: _baseUrl + 'getEtpSignature.do', //获取签名
        CERTIFICATION: _base + '/index.html?from=singlemessage&isappinstalled=0#/certification?agentcode=', //线上入司
        SHARE_URL: _base + '/activity/inviteFriends/index.html?agentCode=', //分享链接
        SHARE_IMG_LOGO: _base + '/activity/inviteFriends/images/WechatIMG23.jpeg' //分享链接图片
    };

process_env === 'dev' && document.write('<script src="http://uat.jkjingji.com/vconsole.min.js"></script>');