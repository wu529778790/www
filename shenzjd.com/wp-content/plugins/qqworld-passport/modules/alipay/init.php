<?php
namespace qqworld_passport\modules;

use qqworld_passport\core;

class alipay extends core {
	var $enabled;
	var $slug;
	var $redirect_uri;
	var $dialog_url;
	var $lib_path;

	public function init() {
		$this->register_method();
		if ($this->is_activated($this->slug)) {
			add_action( 'admin_menu', array($this, 'admin_sub_menu') );

			add_action( 'qqworld_passport_login_form_buttons', array($this, 'login_form_button') ); // 登录页的表单
			add_action( 'qqworld_passport_social_media_account_profile_form', array($this, 'profile_form') ); // 个人资料以及woocommerce个人资料页面的表单
			add_action( 'qqworld_passport_parse_request_'.$this->slug, array($this, 'parse_request') ); // 处理登录页回调的信息

			$this->lib_path = QQWORLD_PASSPORT_LIB_DIR . $this->slug . DIRECTORY_SEPARATOR;
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
			<th><label for="bind-alipay-account-btn"><?php _e('Alipay', $this->text_domain); ?></label></th>
			<td>
			<?php
				$alipay_user_id = get_user_meta( $profileuser->ID, 'QQWorld Passport Alipay User ID', true );
				if (empty($uid)) {
?>
					<input id="bind-alipay-account-btn" type="button" class="button button-primary" value="<?php _e('Bind Now', $this->text_domain); ?>" />
<?php
				} else {
?>
					<input id="bind-alipay-account-btn" type="button" class="button" value="<?php _e('Rebind', $this->text_domain); ?>" />
<?php
				}
				$_SESSION['alipay_state'] = md5(uniqid(rand(), TRUE));
?>
				<script>
				jQuery(document).on('click', '#bind-alipay-account-btn', function() {
					var charset = 'utf-8';
					var anti_phishing_key = <?php echo current_time('timestamp'); ?>;
					var exter_invoke_ip = '<?php echo $_SERVER['REMOTE_ADDR']; ?>';
					var partner = '<?php echo $this->options->moudule_alipay_partnerid; ?>';
					var redirect_uri = encodeURIComponent('<?php echo $this->redirect_uri; ?>');
					var service = 'alipay.auth.authorize';
					var target_service = "user.auth.quick.login";
					var url = "https://mapi.alipay.com/gateway.do?_input_charset="+charset+"&anti_phishing_key="+anti_phishing_key+"&exter_invoke_ip="+exter_invoke_ip+"&partner="+partner+"&return_url="+redirect_uri+"&service="+service+"&target_service="+target_service+"&sign=&sign_type=";
					window.location.href = url;
				});
				</script>
				<?php do_action('qqworld_passport_profile_form_'.$this->slug); ?>
			</td>
		</tr>
<?php
	}

	public function get_sign($data, $rsaPrivateKey) {
		/* 获取私钥PEM文件内容，$rsaPrivateKey是指向私钥PEM文件的路径 */
		$priKey = file_get_contents($rsaPrivateKey);
		/* 从PEM文件中提取私钥 */
		$res = openssl_get_privatekey($priKey);
		/* 对数据进行签名 */
		openssl_sign($data, $sign, $res);
		/* 释放资源 */
		openssl_free_key($res);
		/* 对签名进行Base64编码，变为可读的字符串 */
		$sign = base64_encode($sign);
		return $sign;
	}

	public function is_alipay_user_id_exists($alipay_user_id) {
		$args = array(
			'meta_key'     => 'QQWorld Passport Alipay User ID',
			'meta_value'   => $alipay_user_id
		);
		$users = get_users($args);
		if (!empty($users)) return $users[0]->data;
		else return false;
	}

	public function parse_request() {
		session_start();
		require_once($this->lib_path . "alipay_notify.class.php");
		$alipayNotify = new \AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {
			$user_id = $_GET['user_id'];
		} else {
			_e('Validation failed.', $this->text_domain);
			exit;
		}

		// check is openid exists
		$user = $this->is_alipay_user_id_exists($alipay_user_id);
		if ($user) {
			$user_login = $user->user_login;
			if ( !$this->is_user_logged_in() ) {
				$this->login($user_login);
			}
		} else $user_login = false;

		//Step4：使用OpenID来获取用户的个人信息
		$url = 'https://openapi.alipay.com/gateway.do';
		$method = 'alipay.user.userinfo.share';
		$timestamp = current_time('timestamp'); // 2014-07-29 20:30:30
		$app_id = $this->options->moudule_alipay_partnerid;
		$auth_token = $_GET['token'];
		$charset = 'utf-8';
		$data = "app_id={$app_id}&auth_token={$auth_token}&charset={$charset}&method={$method}&sign_type=RSA&timestamp={$timestamp}&version=1.0";
		$sign = $this->get_sign($data, __DIR__.DIRECTORY_SEPARATOR.'cacert.pem');
		$post_data = "{$data}&sign={$sign}";
		$respond = $this->curl($url, 'post', $post_data);
		/*
		alipay_user_id			用户的userId
		user_type_value			用户类型（1/2）。1代表公司账户；2代表个人账户
		user_status				用户状态（Q/T/B/W）。
								Q代表快速注册用户；
								T代表已认证用户；
								B代表被冻结账户；
								W代表已注册，未激活的账户
		firm_name				公司名称（用户类型是公司类型时，才有此字段）
		real_name				用户的真实姓名
		avatar					用户头像
		cert_no					证件号码
		gender					性别（F：女性；M：男性）
		phone					电话号码
		mobile					手机号码
		is_certified			是否通过实名认证。T是通过；F是没有实名认证
		is_student_certified	是否是学生。T表示是学生；F表示不是学生
		is_bank_auth			T为是银行卡认证，F为非银行卡认证
		is_id_auth				T为是身份证认证，F为非身份证认证
		is_mobile_auth			T为是手机认证，F为非手机认证
		is_licence_auth			T为通过营业执照认证，F为没有通过
		cert_type_value			0：身份证；1：护照；2：军官证；3：士兵证；4：回乡证；5：临时身份证；6：户口簿；7：警官证；8：台胞证；9：营业执照；10：其它证件
		province				省份名称
		city					市名称
		area					区县名称
		address					详细地址
		zip						邮政编码
		address_code			区域编码，暂时不返回值
		*/
		$alipay_user = $respond->alipay_user_userinfo_share_response;
		$nickname = $alipay_user->real_name;
		$avatar = $alipay_user->avatar;
		
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
		update_user_meta( $user_id, 'QQWorld Passport Alipay User ID', $alipay_user_id );
		update_user_meta( $user_id, 'QQWorld Passport Avatar', set_url_scheme($avatar) );

		$_SESSION['alipay_synced'] = true;
		if (isset($_SESSION['redirect_uri'])) {
			$redirect_uri = $_SESSION['redirect_uri'];
			unset($_SESSION['redirect_uri']);
			wp_safe_redirect( $redirect_uri );
		} else $this->login($user_login);
	}

	public function login_form_button() {
		$charset = 'utf-8';
		$anti_phishing_key = current_time('timestamp');
		$exter_invoke_ip = $_SERVER['REMOTE_ADDR'];
		$redirect_uri = urlencode($this->redirect_uri);
		$service = 'alipay.auth.authorize';
		$target_service = "user.auth.quick.login";
		$dialog_url = "https://mapi.alipay.com/gateway.do?_input_charset={$charset}&anti_phishing_key={$anti_phishing_key}&exter_invoke_ip={$exter_invoke_ip}&partner={$this->options->moudule_alipay_partnerid}&return_url={$redirect_uri}&service={$service}&target_service={$target_service}&sign=&sign_type=";
		if ($this->options->moudule_alipay_hide_interface=='yes') return;
?>
		<a class="alipay loginbtn" href="<?php echo $dialog_url; ?>" title="<?php _e('Alipay Login', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/icons/alipay.png" width="32" height="32" /></a>
<?php
	}

	public function settings_form() {
?>
<div class="wrap" id="qqworld-passport-container">
	<h2><?php _e('Alipay', $this->text_domain); ?> <?php _e('Settings'); ?></h2>
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
							<label for="module-alipay-partnerid"><?php _ex('Partner ID', 'alipay', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="text" id="module-alipay-partnerid" placeholder="<?php _ex('Partner ID', 'alipay', $this->text_domain); ?>" name="qqworld-passport-module-alipay[partnerid]" class="regular-text" value="<?php echo $this->options->moudule_alipay_partnerid; ?>" />
							<p class="description"><?php printf(__("Please enter Partner ID, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://open.alipay.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-alipay-key"><?php _ex('Key', 'alipay', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="password" id="module-alipay-Key" placeholder="<?php _ex('Key', 'alipay', $this->text_domain); ?>" name="qqworld-passport-module-alipay[key]" class="regular-text" value="<?php echo $this->options->moudule_alipay_Key; ?>" />
							<p class="description"><?php printf(__("Please enter Key, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://open.alipay.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-alipay-hide-interface"><?php _e('Hide Interface', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="checkbox" id="module-alipay-hide-interface" name="qqworld-passport-module-alipay[hide-interface]" value="yes" <?php checked('yes', $this->options->moudule_alipay_hide_interface); ?> />
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
		$this->slug = 'alipay';
		$this->name = __('Alipay', $this->text_domain);
		$this->description = __("Alipay (China) Network Technology Co., Ltd. is a leading third-party payment platform, committed to providing \"simple, safe, fast\" payment solutions. Pay the company from the beginning of 2004, the establishment has always been to \"trust\" as the core products and services. There's \"Alipay\" and \"Alipay wallet\" two independent brands. Since the second quarter of 2014 began to become the world's largest manufacturers of mobile payment.", $this->text_domain);
		$this->redirect_uri = home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/');
		$this->dialog_url = home_url('wp-json/qqworld-passport/v1/module/pre/'.$this->slug.'/');
		$qqworld_passport_modules[$this->slug] = $this;
	}
}
?>