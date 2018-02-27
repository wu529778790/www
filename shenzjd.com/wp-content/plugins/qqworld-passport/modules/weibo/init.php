<?php
namespace qqworld_passport\modules;

use qqworld_passport\core;

class weibo extends core {
	var $enabled;
	var $slug;
	var $redirect_uri;

	public function init() {
		$this->register_method();
		if ($this->is_activated($this->slug)) {
			add_action( 'admin_menu', array($this, 'admin_menu') );

			add_action( 'qqworld_passport_login_form_buttons', array($this, 'login_form_button') ); // 登录页的表单
			add_action( 'qqworld_passport_social_media_account_profile_form', array($this, 'profile_form') ); // 个人资料以及woocommerce个人资料页面的表单
			add_action( 'qqworld_passport_parse_request_'.$this->slug, array($this, 'parse_request') ); // 处理登录页回调的信息
		}
	}

	public function sanitize_callback($value) {
		return $value;
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
			<th><label for="bind-weibo-account-btn"><?php _e('Sina Weibo', $this->text_domain); ?></label></th>
			<td>
			<?php
				$appkey = $this->options->moudule_weibo_appkey;
				$redirect_uri = urlencode($this->redirect_uri);
				$url = "https://api.weibo.com/oauth2/authorize?client_id=".$appkey."&response_type=code&redirect_uri=".$redirect_uri;
				$uid = get_user_meta( $profileuser->ID, 'QQWorld Passport Weibo Uid', true );
				if (empty($uid)) {
?>
					<a id="bind-weibo-account-btn" class="button button-primary" href="<?php echo $url; ?>"><?php _e('Bind Now', $this->text_domain); ?></a>
<?php
				} else {
?>
					<a id="bind-weibo-account-btn" class="button" href="<?php echo $url; ?>"><?php _e('Rebind', $this->text_domain); ?></a>
<?php
				}
				do_action('qqworld_passport_profile_form_'.$this->slug);
?>
			</td>
		</tr>
<?php
	}

	public function is_uid_exists($uid) {
		$args = array(
			'meta_key'     => 'QQWorld Passport Weibo Uid',
			'meta_value'   => $uid
		);
		$users = get_users($args);
		if (!empty($users)) return $users[0]->data;
		else return false;
	}

	public function parse_request() {
		session_start();
		if (!isset($_GET['code'])) wp_safe_redirect( wp_login_url() );

		$code = $_GET['code'];

		// Step2：通过Authorization Code获取Refresh Token
		$redirect_url = urlencode(home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/'));
		$request = "https://api.weibo.com/oauth2/access_token?client_id={$this->options->moudule_weibo_appkey}&client_secret={$this->options->moudule_weibo_appsecret}&grant_type=authorization_code&redirect_uri={$redirect_url}&code={$code}";
		$response = $this->curl($request, 'post');

		if (isset($response->error)) {
			die($response->error_description);
		}
		$access_token = $response->access_token;
		$uid = $response->uid;
		// check is openid exists
		$user = $this->is_uid_exists($uid);
		if ($user) {
			$user_login = $user->user_login;
			if ( !$this->is_user_logged_in() ) {
				$this->login($user_login);
			}
		} else $user_login = false;
		//Step4：使用OpenID来获取用户的个人信息
		$request = "https://api.weibo.com/2/eps/user/info.json?access_token={$access_token}&uid={$uid}";
		$response = $this->curl($request);
		if (isset($response->error_code)) {
			die($response->error);
		} elseif ($response->follow=='0') {
			$nickname = $uid;
			$avatar = '';
		} else {
			/*
			"subscribe": 1, 
			"uid": "123123123", 
			"nickname": "punk", 
			"sex": 1, 
			"language": "zh_CN", 
			"city": "广州", 
			"province": "广东", 
			"country": "中国", 
			"headimgurl":    "xxx", 
			"headimgurl_large":    "xxx", 
			"headimgurl_hd":    "xxx", 
			"follow":    "1", 
			"subscribe_time": 1382694957
			*/
			$nickname = $response->nickname;
			$avatar = $response->headimgurl_hd;
		}

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
		update_user_meta( $user_id, 'QQWorld Passport Weibo Uid', $uid );
		if ($avatar) update_user_meta( $user_id, 'QQWorld Passport Avatar', set_url_scheme($avatar) );

		$_SESSION['weibo_synced'] = true;
		$this->login($user_login);
	}

	public function login_form_button() {
		$redirect_url = urlencode($this->redirect_uri);
		$dialog_url = "https://api.weibo.com/oauth2/authorize?client_id={$this->options->moudule_weibo_appkey}&response_type=code&redirect_uri={$redirect_url}";
		if ($this->options->moudule_weibo_hide_interface=='yes') return;
?>
		<a class="weibo loginbtn" href="<?php echo $dialog_url; ?>" title="<?php _e('QQ Login', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/icons/weibo.png" width="32" height="32" /></a>
<?php
	}

	public function settings_form() {
?>
<div class="wrap" id="qqworld-passport-container">
	<h2><?php _e('Sina Weibo', $this->text_domain); ?> <?php _e('Settings'); ?></h2>
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
							<label for="module-weibo-appkey"><?php _ex('App Key', 'weibo', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="text" id="module-weibo-appkey" placeholder="<?php _ex('App Key', 'weibo', $this->text_domain); ?>" name="qqworld-passport-module-weibo[appkey]" class="regular-text" value="<?php echo $this->options->moudule_weibo_appkey; ?>" />
							<p class="description"><?php printf(__("Please enter App Key, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://open.weibo.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-weibo-appsecret"><?php _ex('App Secret', 'weibo', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="password" id="module-weibo-appsecret" placeholder="<?php _e('App Secret', 'weibo', $this->text_domain); ?>" name="qqworld-passport-module-weibo[appsecret]" class="regular-text" value="<?php echo $this->options->moudule_weibo_appsecret; ?>" />
							<p class="description"><?php printf(__("Please enter App Secret, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://open.weibo.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-weibo-return-url"><?php _ex('Return URL', 'weibo', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<?php echo home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/'); ?>
							<p class="description"><?php printf(__("Please <a href=\"%s\" target=\"_blank\">click here</a> to create a website APP.", $this->text_domain), 'http://open.weibo.com'); ?><br /><br />
							<strong><?php _e('Return URL 404 error?', $this->text_domain); ?></strong><br />
							1. <?php _e('Your server must supports rewrite.', $this->text_domain); ?><br />
							2. <?php _e('In Wordpress admin page &gt; <strong>Settings</strong> &gt; <strong>Permalinks</strong>, do not select <strong>Plain</strong>.', $this->text_domain); ?><br />
							3. <?php _e('Do not disabled the REST API.', $this->text_domain); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-weibo-hide-interface"><?php _e('Hide Interface', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="checkbox" id="module-weibo-hide-interface" name="qqworld-passport-module-weibo[hide-interface]" value="yes" <?php checked('yes', $this->options->moudule_weibo_hide_interface); ?> />
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
		$this->slug = 'weibo';
		$this->name = __('Sina Weibo', $this->text_domain);
		$this->description = __('Weibo was a launched by Sina, provision of micro-blog service site. Users can publish via the web, WAP pages, external programs and SMS, MMS and other information (280 characters) less than 140 characters, and can upload pictures and video links, instant sharing. Sina Weibo, a microblogging can be attached directly to the following comments can also send pictures directly inside a microblogging, video, Sina microblogging first to add these two functions.', $this->text_domain);
		$this->redirect_uri = home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/');
		$qqworld_passport_modules[$this->slug] = $this;
	}
}
?>