<?php
namespace qqworld_passport\modules;

use qqworld_passport\core;
use qqworld\WeiXinAPI;

include_once( QQWORLD_PASSPORT_DIR . 'lib' . DIRECTORY_SEPARATOR . 'wechat' . DIRECTORY_SEPARATOR . 'weixin.sdk.phar' );

class wechat_login extends core {
	/*
	 * 用于多站点模式检测该用户是否是当前
	*/
	public static function is_user_member_of_blog($user_data) {
		if (is_multisite()) {
			// 如果找到有用户曾经登陆过，则检测该用户是否属于当前子站点
			$user_id = $user_data->ID;
			$blog_id = $GLOBALS['blog_id'];
			if (!is_user_member_of_blog($user_id, $blog_id)) {
				//如果不是当前子站点用，则添加
				$role = in_array('administrator', $user_data->roles) ? 'administrator' : 'subscriber';
				add_user_to_blog( $blog_id, $user_id, $role );
			}
		}
	}

	/**
	 * 判断微信openid是否存在
	 *
	**/
	public static function is_openid_exists($openid, $unionid='') {
		if ($unionid) {
			$args = array(
				'meta_key'     => 'QQWorld Passport Wechat Unionid',
				'meta_value'   => $unionid
			);
			if (is_multisite()) {
				$sites = wp_get_sites();
				$blog_ids = array();
				foreach ($sites as $site) {
					$blog_ids[] = $site['blog_id'];
				}
				$args['blog_id'] = $blog_ids;
			}
			$users = get_users($args);
		} else $users = null;
		if ($openid && (!$unionid || empty($users))) {
			$args = array(
				'meta_key'     => 'QQWorld Passport Wechat Openid',
				'meta_value'   => $openid
			);
			$users = get_users($args);
		}

		if (empty($users)) return false;
		$user_data = $users[0]->data;
		self::is_user_member_of_blog($user_data);

		return $user_data;
	}
}

class wechat extends core {
	var $enabled;
	var $slug;
	var $redirect_uri;

	public function init() {
		$this->register_method();
		if ($this->is_activated($this->slug)) {
			add_action( 'admin_menu', array($this, 'admin_menu') );

			add_action( 'qqworld_passport_login_form_buttons', array($this, 'login_form_button') ); // 登录页的表单
			add_action( 'qqworld_passport_social_media_account_profile_form', array($this, 'profile_form') ); //个人资料页面的表单
			add_action( 'qqworld_passport_parse_request_'.$this->slug, array($this, 'parse_request') ); // 处理登录页回调的信息
		}
	}

	public function sanitize_callback($value) {
		if (isset($value['relay-server-home-url'])) {
			if (!preg_match('/^https*:\/\//i', $value['relay-server-home-url'], $match)) {
				$value['relay-server-home-url'] = 'http://' . $value['relay-server-home-url'];
			}
			$value['relay-server-home-url'] = preg_replace('/\/*$/i', '', $value['relay-server-home-url']);
		}
		return apply_filters($this->text_domain . '-' . $this->slug . '-sanitize-callback', $value);
	}

	public function admin_menu() {
		$page_title = $this->name;
		$menu_title = $this->name;
		$capability = 'administrator';
		$menu_slug = $this->text_domain . '_settings_' . $this->slug;
		$function = array($this, 'settings_form');
		$icon_url = 'none';
		$settings_page = add_submenu_page($this->text_domain, $page_title, $menu_title, $capability, $menu_slug, $function);
	}

	public function profile_form($profileuser) {
?>
		<tr>
			<th><label for="bind-wechat-account-btn"><?php _e('Wechat', $this->text_domain); ?></label></th>
			<td>
			<?php
				$args = array(
					'appid' => $this->options->moudule_wechat_appid,
					'appsecret' => $this->options->moudule_wechat_appsecret
				);
				$weixin = new WeiXinAPI($args);
				$weixin->init();

				// 中继服务器回调地址
				$redirect_uri = urlencode($this->redirect_uri);
				$state = 'wechat-oauth2-login';
				$scope = 'snsapi_userinfo';
				$mode = 'return';
				$dialog_url = $weixin->oauth2->get_code($redirect_uri, $state, $scope, $mode);

				$openid = get_user_meta( $profileuser->ID, 'QQWorld Passport Wechat Openid', true );
				$unionid = get_user_meta( $profileuser->ID, 'QQWorld Passport Wechat Unionid', true );
				ob_start();
				if (empty($openid) && empty($unionid)) { ?>
					<a id="bind-wechat-account-btn" href="<?php echo $dialog_url; ?>" class="button button-primary"><?php _e('Bind Now', $this->text_domain); ?></a>
				<?php } else { ?>
					<a id="bind-wechat-account-btn" href="<?php echo $dialog_url; ?>" class="button"><?php _e('Rebind', $this->text_domain); ?></a>
				<?php } ?>
				<?php
				$button = ob_get_contents();
				ob_end_clean();
				echo apply_filters('qqworld-passport-modules-wechat-bind-button', $button);
				do_action('qqworld_passport_profile_form_'.$this->slug);
				?>
			</td>
		</tr>
<?php
	}

	public function get_weixin_unionid($openid) {
		$args = array(
			'appid' => $this->options->moudule_wechat_appid,
			'appsecret' => $this->options->moudule_wechat_appsecret
		);
		$weixin = new WeiXinAPI($args);
		$weixin->init();
		$respond = $weixin->oauth2->get_userinfo2($openid);
		$respond = json_decode($respond, true);
		if ( !isset($respond['errcode']) && isset($respond['unionid']) ) {
			$unionid = $respond['unionid'];
		} else {
			$unionid = null;
		}
		return $unionid;
	}

	/**
	 *  解析微信自动登录返回的信息
	 *
	 *	@return string
	**/
	public function parse_request() {
		@session_start();
		if (!isset($_GET['state']) || !isset($_GET['code']) || isset($_GET['state'])!='wechat-oauth2-login') wp_safe_redirect( wp_login_url() );

		$args = array(
			'appid' => $this->options->moudule_wechat_appid,
			'appsecret' => $this->options->moudule_wechat_appsecret
		);

		$weixin = new WeiXinAPI($args);
		$weixin->init();

		$respond = $weixin->oauth2->get_oauth2_token();
		$respond = json_decode($respond, true);
		if ( !isset($respond['errcode']) ) {
			$openid = $respond['openid'];
			$unionid = $this->get_weixin_unionid($openid);

			// check is openid exists
			$user = wechat_login::is_openid_exists($openid, $unionid);
			if ($user) {
				$user_login = $user->user_login;
				if ( !$this->is_user_logged_in() ) {
					$this->login($user_login);
				}
			} else $user_login = false;

			$access_token = $respond['access_token'];

			$respond = $weixin->oauth2->get_userinfo($respond);
			$respond = json_decode($respond, true);
			if (!isset($respond['errcode'])) {
				$nickname = $respond['nickname'];
				$avatar = $respond['headimgurl'];

				if (!$this->is_user_logged_in() && !$user_login) {
					$user_login = current_time('timestamp');
					$random_password = wp_generate_password();
					$user_id = wp_create_user($user_login, $random_password);
					$userdata = array(
						'ID' => $user_id,
						'first_name' => $nickname,
						'user_nicename' => $user_login,
						'nickname' => $nickname,
						'display_name' => $nickname
					);
					wp_update_user( $userdata );
				} else {
					$user_id = $this->get_current_user_id();
				}
				update_user_meta( $user_id, 'QQWorld Passport Wechat Openid', $openid );
				if ($unionid) update_user_meta( $user_id, 'QQWorld Passport Wechat Unionid', $unionid );
				update_user_meta( $user_id, 'QQWorld Passport Avatar', set_url_scheme($avatar) );

				$_SESSION['wechat_synced'] = true;
				$this->login($user_login);
			} else {
				echo $respond->errmsg;
			}
		} else {
			echo $respond->errmsg;
		}
	}

	public function is_weixin() { 
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}	
		return false;
	}

	public function login_form_button() {
		if ($this->options->moudule_wechat_hide_interface=='yes') return;
		
		$args = array(
			'appid' => $this->options->moudule_wechat_appid,
			'appsecret' => $this->options->moudule_wechat_appsecret
		);
		$weixin = new WeiXinAPI($args);
		$weixin->init();

		$redirect_uri = urlencode($this->redirect_uri);
		$state = 'wechat-oauth2-login';
		$scope = 'snsapi_userinfo';
		$mode = 'return';
		$dialog_url = $weixin->oauth2->get_code($redirect_uri, $state, $scope, $mode);
		ob_start();
?>
		<a class="wechat loginbtn" href="<?php echo $dialog_url; ?>" title="<?php _e('Wechat Login', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/icons/wechat.png" width="32" height="32" /></a>
<?php
		$button = ob_get_contents();
		ob_end_clean();
		echo apply_filters('qqworld-passport-modules-wechat-login-button', $button);
	}

	public function settings_form() {
?>
<div class="wrap" id="qqworld-passport-container">
	<h2><?php _e('Wechat', $this->text_domain); ?> <?php _e('Settings'); ?></h2>
	<form action="options.php" method="post" id="update-form">
		<?php settings_fields($this->text_domain.'-module-'.$this->slug); ?>
		<div class="icon32 icon32-qqworld-passport-settings" id="icon-qqworld-passport"><br></div>
		<?php
		$tabs = array(
			'regular' => __('Regular', $this->text_domain)
		);
		$tabs = apply_filters( 'qqworld_passport_'.$this->slug.'_form_tabs', $tabs);
		if (count($tabs)>1): ?>
		<h2 class="nav-tab-wrapper">
		<?php
		foreach ($tabs as $name => $label) : ?>
			<a id="<?php echo $name; ?>" href="#<?php echo $name; ?>" class="nav-tab"><?php echo $label; ?></a>
		<?php endforeach; ?>
		</h2>
		<?php endif; ?>
		<?php if (count($tabs)>1): ?><div class="nav-section"><?php endif; ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="login-mode"><?php _e('Mode', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<select id="login-mode" name="qqworld-passport-module-wechat[mode]">
								<option value="server" <?php selected('server', $this->options->moudule_wechat_mode); ?>><?php _e('Server (Use this website as a login relay server, other client website via this server can use the same wechat public number oauth2 web login.)', $this->text_domain); ?></option>
								<option value="client" <?php selected('client', $this->options->moudule_wechat_mode); ?>><?php _e('Client (Get wechat Oauth2 login via relay server.)', $this->text_domain); ?></option>
							</select>
							<p class="description"><?php printf(__("You need install the Wechat Extends of <a href=\"%s\" target=\"_blank\">QQWorld Synchronizer</a> to use client login.", $this->text_domain), 'https://www.qqworld.org/product/qqworld-synchronizer/'); ?></p>
						</td>
					</tr>
					<tr valign="top" id="module-wechat-relay-server-domain-container"<?php if ($this->options->moudule_wechat_mode == 'server') echo ' style="display: none;"'; ?>>
						<th scope="row">
							<label for="module-wechat-relay-server-home-url"><?php _ex('Relay Server Home URL', 'wechat', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="text" id="module-wechat-relay-server-home-url" placeholder="<?php _ex('Relay Server Home URL', 'wechat', $this->text_domain); ?>" name="qqworld-passport-module-wechat[relay-server-home-url]" class="regular-text" value="<?php echo $this->options->moudule_wechat_relay_server_home_url; ?>" />
							<p class="description"><?php _e("Please enter relay server home URL, this website will get oauth2 web login via it.", $this->text_domain); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-wechat-appid"><?php _ex('APP ID', 'wechat', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="text" id="module-wechat-appid" placeholder="<?php _ex('APP ID', 'wechat', $this->text_domain); ?>" name="qqworld-passport-module-wechat[appid]" class="regular-text" value="<?php echo $this->options->moudule_wechat_appid; ?>" />
							<p class="description"><?php printf(__("Please enter APP ID, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'https://mp.weixin.qq.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-wechat-appsecret"><?php _ex('APP Secret', 'wechat', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="password" id="module-wechat-appsecret" placeholder="<?php _ex('APP Secret', 'wechat', $this->text_domain); ?>" name="qqworld-passport-module-wechat[appsecret]" class="regular-text" value="<?php echo $this->options->moudule_wechat_appsecret; ?>" />
							<p class="description"><?php printf(__("Please enter APP Secret, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'https://mp.weixin.qq.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-wechat-return-url"><?php _e('Return URL', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<?php echo home_url('wp-json/qqworld-synchronizer/v1/module/wechat/native'); ?>
							<p class="description"><?php printf(__("Please <a href=\"%s\" target=\"_blank\">click here</a> to create a website APP.<br />And you need install the Wechat Extends of <a href=\"%s\" target=\"_blank\">QQWorld Synchronizer</a> to use native login on PC.", $this->text_domain), 'https://open.weixin.qq.com/', 'https://www.qqworld.org/product/qqworld-synchronizer/'); ?><br /><br />
							<strong><?php _e('Return URL 404 error?', $this->text_domain); ?></strong><br />
							1. <?php _e('Your server must supports rewrite.', $this->text_domain); ?><br />
							2. <?php _e('In Wordpress admin page &gt; <strong>Settings</strong> &gt; <strong>Permalinks</strong>, do not select <strong>Plain</strong>.', $this->text_domain); ?><br />
							3. <?php _e('Do not disabled the REST API.', $this->text_domain); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="desktop-login-mode"><?php _e('Desktop Login Mode', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<select name="qqworld-passport-module-wechat[desktop-login-mode]" id="desktop-login-mode">
								<option value="native" <?php selected('native', $this->options->moudule_wechat_desktop_login_mode); ?>><?php _e('Native (Need pay 300 RMB to open platform of weixin per year)', $this->text_domain); ?></option>
								<option value="emulation" <?php selected('emulation', $this->options->moudule_wechat_desktop_login_mode); ?>><?php _e('Emulation (Only need a service number of wechat public platform)', $this->text_domain); ?></option>
							</select>
							<p class="description"><?php printf(__("You need install the Wechat Extends of <a href=\"%s\" target=\"_blank\">QQWorld Synchronizer</a> to use native login on PC.", $this->text_domain), 'https://open.weixin.qq.com/', 'https://www.qqworld.org/product/qqworld-synchronizer/'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="open"<?php if ($this->options->moudule_wechat_desktop_login_mode=='emulation') echo ' style="display: none;"'; ?>>
						<th scope="row">
							<label for="module-wechat-open-appid"><?php _ex('Open APP ID', 'wechat', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="text" id="module-wechat-open-appid" placeholder="<?php _ex('Open APP ID', 'wechat', $this->text_domain); ?>" name="qqworld-passport-module-wechat[open-appid]" class="regular-text" value="<?php echo $this->options->moudule_wechat_open_appid; ?>" />
							<p class="description"><?php printf(__("Please enter Open APP ID, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'https://open.weixin.qq.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top" class="open"<?php if ($this->options->moudule_wechat_desktop_login_mode=='emulation') echo ' style="display: none;"'; ?>>
						<th scope="row">
							<label for="module-wechat-open-appsecret"><?php _ex('Open APP Secret', 'wechat', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="password" id="module-wechat-open-appsecret" placeholder="<?php _ex('Open APP Secret', 'wechat', $this->text_domain); ?>" name="qqworld-passport-module-wechat[open-appsecret]" class="regular-text" value="<?php echo $this->options->moudule_wechat_open_appsecret; ?>" />
							<p class="description"><?php printf(__("Please enter Open APP Secret, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'https://open.weixin.qq.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-wechat-hide-interface"><?php _e('Hide Interface', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="checkbox" id="module-wechat-hide-interface" name="qqworld-passport-module-wechat[hide-interface]" value="yes" <?php checked('yes', $this->options->moudule_wechat_hide_interface); ?> />
							<p class="description"><?php _e("Don't display login icon in login form.", $this->text_domain); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		<?php if (count($tabs)>1): ?></div><?php endif; ?>
		<?php do_action( 'qqworld_passport_'.$this->slug.'_form_sections' ); ?>
		<?php submit_button(); ?>
	</form>
<?php
	}

	public function register_method() {
		global $qqworld_passport_modules;
		$this->slug = 'wechat';
		$this->name = __('Wechat', $this->text_domain);
		$this->description = __('Tencent Wechat on January 21, 2011 launched a support S60v3, S60v5, S40, BlackBerry, Windows Phone, Android and iOS platform instant messaging software, the face of smart phone users, by providing client friends to share text and pictures, there are maps, and supports packet and voice chat, video intercom function, broadcast (one to many) news, photo / video sharing, location sharing, exchange contact information, micro-channel pay, through financial, gaming and other services, and Feed share streaming content and location-based social plug-in "shake," "drift bottles." Micro-channel support multiple languages, as well as mobile phone data network. Users can take a picture and adding decorative art filters, subtitles, to send personal photo journal, and issued to the "Timeline" feature similar to Instagram. The user can select a contact in the contact list to / from the data backup and recovery services to protect users of cloud-based address book data.', $this->text_domain);
		$this->redirect_uri = home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/');
		$qqworld_passport_modules[$this->slug] = $this;
	}
}
?>