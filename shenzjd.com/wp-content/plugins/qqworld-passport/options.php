<?php
namespace qqworld_passport\lib;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class options {
	var $activated_modules;

	var $moudule_qq;
	var $moudule_qq_appid;
	var $moudule_qq_appkey;

	var $moudule_wechat;
	var $moudule_wechat_appid;
	var $moudule_wechat_appsecret;

	var $moudule_weibo;
	var $moudule_weibo_appkey;
	var $moudule_weibo_appsecret;

	var $moudule_taobao;
	var $moudule_taobao_appkey;
	var $moudule_taobao_appsecret;

	var $moudule_alipay;
	var $moudule_alipay_partnerid;
	var $moudule_alipay_Key;

	public function __construct() {
		$this->activated_modules = get_option('qqworld-passport-modules', array());
		$this->avatar_priority = get_option('qqworld-passport-avatar-priority', 9999);

		$this->moudule_qq = get_option('qqworld-passport-module-qq', array());
		$this->moudule_qq_appid = isset($this->moudule_qq['appid']) ? $this->moudule_qq['appid'] : '';
		$this->moudule_qq_appkey = isset($this->moudule_qq['appkey']) ? $this->moudule_qq['appkey'] : '';
		$this->moudule_qq_hide_interface = isset($this->moudule_qq['hide-interface']) ? $this->moudule_qq['hide-interface'] : 'no';

		$this->moudule_wechat = get_option('qqworld-passport-module-wechat', array());
		$this->moudule_wechat_mode = isset($this->moudule_wechat['mode']) ? $this->moudule_wechat['mode'] : 'server';
		$this->moudule_wechat_relay_server_home_url = isset($this->moudule_wechat['relay-server-home-url']) ? $this->moudule_wechat['relay-server-home-url'] : 'http://';
		$this->moudule_wechat_appid = isset($this->moudule_wechat['appid']) ? $this->moudule_wechat['appid'] : '';
		$this->moudule_wechat_appsecret = isset($this->moudule_wechat['appsecret']) ? $this->moudule_wechat['appsecret'] : '';
		$this->moudule_wechat_hide_interface = isset($this->moudule_wechat['hide-interface']) ? $this->moudule_wechat['hide-interface'] : 'no';
		$this->moudule_wechat_desktop_login_mode = isset($this->moudule_wechat['desktop-login-mode']) ? $this->moudule_wechat['desktop-login-mode'] : 'native';
		$this->moudule_wechat_open_appid = isset($this->moudule_wechat['open-appid']) ? $this->moudule_wechat['open-appid'] : '';
		$this->moudule_wechat_open_appsecret = isset($this->moudule_wechat['open-appsecret']) ? $this->moudule_wechat['open-appsecret'] : '';

		$this->moudule_weibo = get_option('qqworld-passport-module-weibo', array());
		$this->moudule_weibo_appkey = isset($this->moudule_weibo['appkey']) ? $this->moudule_weibo['appkey'] : '';
		$this->moudule_weibo_appsecret = isset($this->moudule_weibo['appsecret']) ? $this->moudule_weibo['appsecret'] : '';
		$this->moudule_weibo_hide_interface = isset($this->moudule_weibo['hide-interface']) ? $this->moudule_weibo['hide-interface'] : 'no';

		$this->moudule_taobao = get_option('qqworld-passport-module-taobao', array());
		$this->moudule_taobao_appkey = isset($this->moudule_taobao['appkey']) ? $this->moudule_taobao['appkey'] : '';
		$this->moudule_taobao_appsecret = isset($this->moudule_taobao['appsecret']) ? $this->moudule_taobao['appsecret'] : '';
		$this->moudule_taobao_hide_interface = isset($this->moudule_taobao['hide-interface']) ? $this->moudule_taobao['hide-interface'] : 'no';

		$this->moudule_alipay = get_option('qqworld-passport-module-alipay', array());
		$this->moudule_alipay_partnerid = isset($this->moudule_alipay['partnerid']) ? $this->moudule_alipay['partnerid'] : '';
		$this->moudule_alipay_Key = isset($this->moudule_alipay['key']) ? $this->moudule_alipay['key'] : '';
		$this->moudule_alipay_hide_interface = isset($this->moudule_alipay['hide-interface']) ? $this->moudule_alipay['hide-interface'] : 'no';
	}
}
