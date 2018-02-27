<?php
namespace qqworld_passport\modules;

use qqworld_passport\core;

class qq extends core {
	var $enabled;
	var $slug;
	var $redirect_uri;

	public function init() {
		$this->register_method();
		if ($this->is_activated($this->slug)) {
			add_action( 'admin_menu', array($this, 'admin_menu') );

			add_action( 'qqworld_passport_login_form_buttons', array($this, 'login_form_button') ); // 登录页的表单
			add_action( 'qqworld_passport_social_media_account_profile_form', array($this, 'profile_form') ); // 个人资料页面的表单
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
			<th><label for="bind-qq-account-btn"><?php _e('Tencent QQ', $this->text_domain); ?></label></th>
			<td>
			<?php
				$appid = $this->options->moudule_qq_appid;
				$redirect_uri = urlencode($this->redirect_uri);
				$scope = 'get_user_info,list_album,upload_pic,do_like';
				$_SESSION['qq_state'] = md5(uniqid(rand(), TRUE));
				$state = $_SESSION['qq_state'];
				$url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.$appid.'&redirect_uri='.$redirect_uri.'&scope='.$scope.'&state='.$state;
				$openid = get_user_meta( $profileuser->ID, 'QQWorld Passport QQ Openid', true );
				if (empty($openid)) {
?>
					<a class="button button-primary" href="<?php echo $url; ?>"><?php _e('Bind Now', $this->text_domain); ?></a>
<?php
				} else {
?>
					<a class="button" href="<?php echo $url; ?>"><?php _e('Rebind', $this->text_domain); ?></a>
<?php
				}
				do_action('qqworld_passport_profile_form_'.$this->slug);
?>
			</td>
		</tr>
<?php
	}

	public function is_openid_exists($openid) {
		$args = array(
			'meta_key'     => 'QQWorld Passport QQ Openid',
			'meta_value'   => $openid
		);
		$users = get_users($args);
		if (!empty($users)) return $users[0]->data;
		else return false;
	}

	public function parse_request() {
		@session_start();
		if (!isset($_GET['state']) || !isset($_GET['code']) || $_GET['state'] != $_SESSION['qq_state'] ) wp_safe_redirect( wp_login_url() );
		unset($_SESSION['qq_state']);

		$code = $_GET['code'];
		$redirect_uri = urlencode($this->redirect_uri);
		// Step2：通过Authorization Code获取Refresh Token
		$request = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id={$this->options->moudule_qq_appid}&client_secret={$this->options->moudule_qq_appkey}&code={$code}&redirect_uri={$redirect_uri}";
		$response = file_get_contents($request);
		if (strpos($response, "callback") !== false) { // check error
			$lpos = strpos($response, "(");
			$rpos = strrpos($response, ")");
			$response  = substr($response, $lpos + 1, $rpos - $lpos -1);
			$msg = json_decode($response);
			if (isset($msg->error)) {
				echo "<h3>Step1. get Refresh Token via Authorization Code.</h3>";
				echo "<p>Error: <strong>{$msg->error}</strong></p>";
				echo "<p>Msg: <strong>{$msg->error_description}</strong></p>";
				exit;
			}
		}
		$params = array();
		parse_str($response, $params);
		//Step3：使用Refresh Token来获取用户的Access Token
		$request = "https://graph.qq.com/oauth2.0/token?grant_type=refresh_token&client_id={$this->options->moudule_qq_appid}&client_secret={$this->options->moudule_qq_appkey}&refresh_token={$params['refresh_token']}";
		$str = file_get_contents($request);
		if (strpos($str, "callback") !== false) { // check error
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
			$user = json_decode($str);
			if (isset($user->error)) {
				echo "<h3>Step2. get Access Token via Refresh Token.</h3>";
				echo "<p>Error: <strong>{$user->error}</strong></p>";
				echo "<p>Msg: <strong>{$user->error_description}</strong></p>";
				exit;
			}
		}
		$params = array();
		parse_str($str, $params);
		//Step3：使用Access Token来获取用户的OpenID
		$request = "https://graph.qq.com/oauth2.0/me?access_token={$params['access_token']}";
		$str = file_get_contents($request);
		if (strpos($str, "callback") !== false) {
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		}
		$user = json_decode($str);
		if (isset($user->error)) {  // check error
			echo "<h3>Step3. get OpenID via Access Token.</h3>";
			echo "<p>error: <strong>{$user->error}</strong></p>";
			echo "<p>msg: <strong>{$user->error_description}</strong></p>";
			exit;
		}
		$openid = $user->openid;
		// check is openid exists
		$user = $this->is_openid_exists($openid);
		if ($user) {
			$user_login = $user->user_login;
			if ( !$this->is_user_logged_in() ) {
				$this->login($user_login);
			}
		} else $user_login = false;

		//Step4：使用OpenID来获取用户的个人信息
		$request = "https://graph.qq.com/user/get_user_info?access_token={$params['access_token']}&oauth_consumer_key={$this->options->moudule_qq_appid}&openid={$openid}";
		$str = file_get_contents($request);
		$user = json_decode($str);
		/*
		stdClass Object (
			[ret] => 0
			[msg] => 
			[is_lost] => 0 
			[nickname] => THE WORLD/v 
			[gender] => 男 
			[province] => 湖北 
			[city] => 武汉 
			[year] => 1980 
			[figureurl] => http://qzapp.qlogo.cn/qzapp/101167451/1AE4F969E5C10DA92F0B17E21BC7031B/30
			[figureurl_1] => http://qzapp.qlogo.cn/qzapp/101167451/1AE4F969E5C10DA92F0B17E21BC7031B/50
			[figureurl_2] => http://qzapp.qlogo.cn/qzapp/101167451/1AE4F969E5C10DA92F0B17E21BC7031B/100
			[figureurl_qq_1] => http://q.qlogo.cn/qqapp/101167451/1AE4F969E5C10DA92F0B17E21BC7031B/40
			[figureurl_qq_2] => http://q.qlogo.cn/qqapp/101167451/1AE4F969E5C10DA92F0B17E21BC7031B/100
			[is_yellow_vip] => 0 
			[vip] => 0 
			[yellow_vip_level] => 0 
			[level] => 0 
			[is_yellow_year_vip] => 0
		)
		*/
		if (isset($user->ret) && $user->ret < 0) {
			echo "<h3>Step4. get User Info via OpenID.</h3>";
			echo "<p>Ret: <strong>{$user->ret}</strong></p>";
			echo "<p>msg: <strong>{$user->msg}</strong></p>";
			exit;
		}
		//Setp5：Login & Regisger
		$nickname = $user->nickname;
		$avatar = isset($user->figureurl_qq_2) ? $user->figureurl_qq_2 : $user->figureurl_2;

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
		update_user_meta( $user_id, 'QQWorld Passport QQ Openid', $openid );
		update_user_meta( $user_id, 'QQWorld Passport Avatar', set_url_scheme($avatar) );

		$_SESSION['qq_synced'] = true;
		$this->login($user_login);
	}

	public function login_form_button() {
		$scope = '&scope=get_user_info,list_album,upload_pic,do_like';
		$return_url = urlencode($this->redirect_uri);
		$_SESSION['qq_state'] = md5(uniqid(rand(), TRUE));
		$dialog_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id={$this->options->moudule_qq_appid}&redirect_uri={$return_url}{$scope}&state={$_SESSION['qq_state']}";
		if ($this->options->moudule_qq_hide_interface=='yes') return;
?>
		<a class="qq loginbtn" href="<?php echo $dialog_url; ?>" title="<?php _e('QQ Login', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/icons/qq.png" width="32" height="32" /></a>
<?php
	}

	public function settings_form() {
?>
<div class="wrap" id="qqworld-passport-container">
	<h2><?php _e('Tencent QQ', $this->text_domain); ?> <?php _e('Settings'); ?></h2>
	<form action="options.php" method="post" id="update-form">
		<?php settings_fields($this->text_domain.'-module-'.$this->slug); ?>
		<div class="icon32 icon32-qqworld-synchronizer-settings" id="icon-qqworld-synchronizer"><br></div>
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
							<label for="module-qq-appid"><?php _e('APP ID', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="text" id="module-qq-appid" placeholder="<?php _e('APP ID', $this->text_domain); ?>" name="qqworld-passport-module-qq[appid]" class="regular-text" value="<?php echo $this->options->moudule_qq_appid; ?>" />
							<p class="description"><?php printf(__("Please enter APP ID, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://connect.qq.com'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-qq-appkey"><?php _e('APP Key', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="password" id="module-qq-appkey" placeholder="<?php _e('APP Key', $this->text_domain); ?>" name="qqworld-passport-module-qq[appkey]" class="regular-text" value="<?php echo $this->options->moudule_qq_appkey; ?>" />
							<p class="description"><?php printf(__("Please enter APP Key, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://connect.qq.com'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-qq-return-url"><?php _ex('Return URL', 'qq', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<?php echo home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/'); ?>
							<p class="description"><?php printf(__("Please <a href=\"%s\" target=\"_blank\">click here</a> to create a website APP.", $this->text_domain), 'https://connect.qq.com'); ?><br /><br />
							<strong><?php _e('Return URL 404 error?', $this->text_domain); ?></strong><br />
							1. <?php _e('Your server must supports rewrite.', $this->text_domain); ?><br />
							2. <?php _e('In Wordpress admin page &gt; <strong>Settings</strong> &gt; <strong>Permalinks</strong>, do not select <strong>Plain</strong>.', $this->text_domain); ?><br />
							3. <?php _e('Do not disabled the REST API.', $this->text_domain); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-qq-hide-interface"><?php _e('Hide Interface', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="checkbox" id="module-qq-hide-interface" name="qqworld-passport-module-qq[hide-interface]" value="yes" <?php checked('yes', $this->options->moudule_qq_hide_interface); ?> />
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
		$this->slug = 'qq';
		$this->name = __('Tencent QQ', $this->text_domain);
		$this->description = __('Tencent QQ, Tencent in February 11, 1999 launched a multi-platform instant messaging software that supports text, voice and video chat, also comes with a file-sharing, network drives, email, games, tribal interests, and even a wide range of residential and commercial services platform for online shopping, renting and looking for work, and so on. Currently, Tencent QQ, mobile QQ instant messaging software, Chinese are the most widely used, both personal computers and mobile phones occupy Chinese IM market first.', $this->text_domain);
		$this->redirect_uri = home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/');
		$qqworld_passport_modules[$this->slug] = $this;
	}
}
?>