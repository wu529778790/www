<?php
namespace qqworld_passport\modules;

use qqworld_passport\core;

class taobao extends core {
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
			<th><label for="bind-taobao-account-btn"><?php _e('Taobao', $this->text_domain); ?></label></th>
			<td>
			<?php
				$taobao_user_id = get_user_meta( $profileuser->ID, 'QQWorld Passport Taobao User ID', true );
				if (empty($uid)) {
?>
					<input id="bind-taobao-account-btn" type="button" class="button button-primary" value="<?php _e('Bind Now', $this->text_domain); ?>" />
<?php
				} else {
?>
					<input id="bind-taobao-account-btn" type="button" class="button" value="<?php _e('Rebind', $this->text_domain); ?>" />
<?php
				}
				$_SESSION['taobao_state'] = md5(uniqid(rand(), TRUE));
?>
				<script>
				jQuery(document).on('click', '#bind-taobao-account-btn', function() {
					var appkey = '<?php echo $this->options->moudule_taobao_appkey; ?>';
					var state = '<?php echo $_SESSION['taobao_state']; ?>';
					var redirect_uri = encodeURIComponent('<?php echo $this->redirect_uri; ?>');
					var url = "https://oauth.taobao.com/authorize?response_type=code&client_id="+appkey+"&redirect_uri="+redirect_uri+"&state="+state+"&view=web";
					window.location.href = url;
				});
				</script>
				<?php do_action('qqworld_passport_profile_form_'.$this->slug); ?>
			</td>
		</tr>
<?php
	}

	public function is_taobao_user_id_exists($taobao_user_id) {
		$args = array(
			'meta_key'     => 'QQWorld Passport Taobao User ID',
			'meta_value'   => $taobao_user_id
		);
		$users = get_users($args);
		if (!empty($users)) return $users[0]->data;
		else return false;
	}

	public function parse_request() {
		session_start();
		if (!isset($_GET['state']) || !isset($_GET['code']) || $_GET['state'] != $_SESSION['taobao_state'] ) wp_safe_redirect( wp_login_url() );
		unset($_SESSION['taobao_state']);

		$code = $_GET['code'];

		// Step2：通过Authorization Code获取Refresh Token
		$request = 'https://oauth.taobao.com/token';
		$redirect_uri = urlencode($this->redirect_uri);
		$post_data .= "grant_type=authorization_code&client_id={$this->options->moudule_taobao_appkey}&client_secret={$this->options->moudule_taobao_appsecret}&code={$code}&redirect_uri={$redirect_uri}";
		$response = $this->curl($request, 'post', $post_data);

		if (isset($response->error)) {
			die($response->error_description);
		}
		$access_token = $response->access_token;
		$taobao_user_id = $response->taobao_user_id;
		// check is openid exists
		$user = $this->is_taobao_user_id_exists($taobao_user_id);
		if ($user) {
			$user_login = $user->user_login;
			if ( !$this->is_user_logged_in() ) {
				$this->login($user_login);
			}
		} else $user_login = false;

		//Step4：使用OpenID来获取用户的个人信息 (跳过)
		
		$nickname = $response->taobao_user_nick;

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
		update_user_meta( $user_id, 'QQWorld Passport Taobao User ID', $taobao_user_id );

		$_SESSION['taobao_synced'] = true;
		if (isset($_SESSION['redirect_uri'])) {
			$redirect_uri = $_SESSION['redirect_uri'];
			unset($_SESSION['redirect_uri']);
			wp_safe_redirect( $redirect_uri );
		} else $this->login($user_login);
	}

	public function login_form_button() {
		$redirect_url = urlencode($this->redirect_uri);
		$_SESSION['taobao_state'] = md5(uniqid(rand(), TRUE));
		$dialog_url = "https://oauth.taobao.com/authorize?response_type=code&client_id={$this->options->moudule_taobao_appkey}&redirect_uri={$redirect_url}&state={$_SESSION['taobao_state']}&view=web";
		if ($this->options->moudule_taobao_hide_interface=='yes') return;
?>
		<a class="taobao loginbtn" href="<?php echo $dialog_url; ?>" title="<?php _e('Taobao Login', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/icons/taobao.png" width="32" height="32" /></a>
<?php
	}

	public function settings_form() {
?>
<div class="wrap" id="qqworld-passport-container">
	<h2><?php _e('Taobao', $this->text_domain); ?> <?php _e('Settings'); ?></h2>
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
							<label for="module-taobao-appkey"><?php _ex('App Key', 'taobao', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="text" id="module-taobao-appkey" placeholder="<?php _ex('App Key', 'taobao', $this->text_domain); ?>" name="qqworld-passport-module-taobao[appkey]" class="regular-text" value="<?php echo $this->options->moudule_taobao_appkey; ?>" />
							<p class="description"><?php printf(__("Please enter App Key, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://open.taobao.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-taobao-appsecret"><?php _ex('App Secret', 'taobao', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="password" id="module-taobao-appsecret" placeholder="<?php _ex('App Secret', 'taobao', $this->text_domain); ?>" name="qqworld-passport-module-taobao[appsecret]" class="regular-text" value="<?php echo $this->options->moudule_taobao_appsecret; ?>" />
							<p class="description"><?php printf(__("Please enter App Secret, if you don't have, please <a href=\"%s\" target=\"_blank\">click here</a> to get one.", $this->text_domain), 'http://open.taobao.com/'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="module-taobao-hide-interface"><?php _e('Hide Interface', $this->text_domain); ?></label>
						</th>
						<td class="forminp">
							<input type="checkbox" id="module-taobao-hide-interface" name="qqworld-passport-module-taobao[hide-interface]" value="yes" <?php checked('yes', $this->options->moudule_taobao_hide_interface); ?> />
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
		$this->slug = 'taobao';
		$this->name = __('Taobao', $this->text_domain);
		$this->description = __('Taobao is the Asia-Pacific region a large network of retail, business district, by the Alibaba Group was founded in May 2003.', $this->text_domain);
		$this->redirect_uri = home_url('wp-json/qqworld-passport/v1/module/'.$this->slug.'/');
		$qqworld_passport_modules[$this->slug] = $this;
	}
}
?>