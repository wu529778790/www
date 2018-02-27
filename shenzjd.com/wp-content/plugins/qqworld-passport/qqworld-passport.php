<?php
/**
 * Plugin Name: QQWorld Passport
 * Plugin URI: https://wordpress.org/plugins/qqworld-passport/
 * Description: QQWorld Passport for Wordpress, Many Oauth 2.0 log in methods.
 * Version: 1.1.6
 * Author: Michael Wang
 * Author URI: http://www.qqworld.org/
 * Text Domain: qqworld-passport
 */
namespace qqworld_passport;

use qqworld_passport\lib\options;
use qqworld_passport\modules\qq;
use qqworld_passport\modules\wechat;
use qqworld_passport\modules\weibo;
use qqworld_passport\modules\taobao;
use qqworld_passport\modules\alipay;

$GLOBALS['qqworld_passport_modules'] = array();

define('QQWORLD_PASSPORT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('QQWORLD_PASSPORT_LIB_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR);
define('QQWORLD_PASSPORT_URL', plugin_dir_url(__FILE__));

include_once QQWORLD_PASSPORT_DIR . 'options.php';

class core {
	var $text_domain = 'qqworld-passport';
	var $options;

	var $qq;
	var $wechat;
	var $weibo;
	var $taobao;
	var $alipay;

	public function __construct() {
		$this->options = new options;
	}

	public function outside_language() {
		__( 'Michael Wang', $this->text_domain );
	}

	public function init() {
		add_action( 'plugins_loaded', array($this, 'load_language') );
		add_action( 'admin_menu', array($this, 'admin_menu') );
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_init', array($this, 'register_settings') );

		add_action( 'plugins_loaded', array($this, 'load_modules') );

		if (!empty($this->options->activated_modules)) {
			add_action( 'um_after_form', array($this, 'login_form') ); // for Ultimate Member
			add_action( 'login_form', array($this, 'login_form') ); // for wp-login.php
			add_action( 'woocommerce_login_form_end', array($this, 'login_form') ); // for woocommerce form-login.php
			add_filter( 'login_form_middle', array($this, 'login_form_middle'), 10, 2 ); // for function wp_login_form()
			add_action( 'rest_api_init', array($this, 'register_oauth2_quest') );
			add_action( 'after_setup_theme', array($this, 'set_session_start') );
			add_filter( 'get_avatar', array($this, 'get_avatar'), $this->options->avatar_priority, 6 );

			add_action( 'show_user_profile', array($this, 'binding_social_media_account') );
		}

		add_action( 'qqworld_passport_additional_form_settings', array($this, 'advertisement_qqworld_synchronizer') );
		add_action( 'qqworld_passport_additional_form_settings', array($this, 'advertisement_qqworld_mobile') );

		add_action( 'qqworld-passport', array($this, 'login_form') );
	}

	public function sanitize_callback($value) {
		return $value;
	}

	public function advertisement_qqworld_synchronizer() {
		if ( !is_plugin_active( 'qqworld-synchronizer/qqworld-synchronizer.php' ) ) {
?>
<div class="wrap" id="qqworld-synchronizer-container">
	<h2><?php _e('QQWorld Synchronizer', $this->text_domain); ?></h2>
	<p><?php _e("QQWorld Synchronizer is a component for QQWorld Passport.", $this->text_domain); ?></p>
	<img id="banner" src="<?php echo QQWORLD_PASSPORT_URL; ?>images/synchronizer/banner-772x250.png" title="<?php _e('QQWorld Synchronizer', $this->text_domain); ?>" />
	<ul id="extension-list">
		<li class="extension commercial">
			<aside class="attr pay"><a href="http://www.qqworld.org/products/qqworld-synchronizer" target="_blank"><?php _ex('$ Buy', 'extension', $this->text_domain); ?></a></aside>
			<figure class="extension-image" title="<?php _e('Wechat Robot', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/synchronizer/wechat/plus.png"></figure>
			<h3 class="extension-label"><?php _e('Wechat Plus', $this->text_domain); ?></h3>
			<p class="extension-description"><?php _e('Automatic login, display follow us button, login in pc exproler via scan QR Code.', $this->text_domain); ?></p>
			<aside class="activate inactive"><?php _e('Inactive', $this->text_domain); ?></aside>
		</li>
		<li class="extension commercial">
			<aside class="attr pay"><a href="http://www.qqworld.org/products/qqworld-synchronizer" target="_blank"><?php _ex('$ Buy', 'extension', $this->text_domain); ?></a></aside>
			<figure class="extension-image" title="<?php _e('Wechat Robot', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/synchronizer/wechat/robot.png"></figure>
			<h3 class="extension-label"><?php _e('Wechat Robot', $this->text_domain); ?></h3>
			<p class="extension-description"><?php _e('Make your website and WeChat public platform to interact.', $this->text_domain); ?></p>
			<aside class="activate inactive"><?php _e('Inactive', $this->text_domain); ?></aside>
		</li>
		<li class="extension commercial">
			<aside class="attr pay"><a href="http://www.qqworld.org/products/qqworld-synchronizer" target="_blank"><?php _ex('$ Buy', 'extension', $this->text_domain); ?></a></aside>
			<figure class="extension-image" title="<?php _e('Sync Posts to Wechat', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/synchronizer/wechat/sync-posts.png"></figure>
			<h3 class="extension-label"><?php _e('Sync Posts to Wechat', $this->text_domain); ?></h3>
			<p class="extension-description"><?php _e('Automatically sync posts to your Wechat platform.', $this->text_domain); ?></p>
			<aside class="activate inactive"><?php _e('Inactive', $this->text_domain); ?></aside>
		</li>
	</ul>
</div>
<?php
		}
	}

	public function advertisement_qqworld_mobile() {
		if ( !is_plugin_active( 'qqworld-mobile/qqworld-mobile.php' ) ) {
?>
<div class="wrap" id="qqworld-mobile-container">
	<h2><?php _e('QQWorld Mobile', $this->text_domain); ?></h2>
	<p><?php _e("QQWorld Mobile is a component for QQWorld Passport, The featured such as Phone Nubmber Register and Sms Group Sends.", $this->text_domain); ?></p>
	<img id="banner" src="<?php echo QQWORLD_PASSPORT_URL; ?>images/mobile/banner-772x250.jpg" title="<?php _e('QQWorld Mobile', $this->text_domain); ?>" />
	<ul id="extension-list">
		<li class="extension commercial">
			<aside class="attr pay"><a href="https://www.qqworld.org/product/qqworld-mobile" target="_blank"><?php _ex('$ Buy', 'extension', $this->text_domain); ?></a></aside>
			<figure class="extension-image" title="<?php _e('Phone Number Login', $this->text_domain); ?>"><img src="<?php echo QQWORLD_PASSPORT_URL; ?>images/mobile/phone-number.png"></figure>
			<h3 class="extension-label"><?php _e('Phone Sign Up', $this->text_domain); ?></h3>
			<p class="extension-description"><?php _e('Phone Number register & Sms Group Sends.', $this->text_domain); ?></p>
			<aside class="activate inactive"><?php _e('Inactive', $this->text_domain); ?></aside>
		</li>
	</ul>
</div>
<?php
		}
	}

	public function binding_social_media_account($profileuser) {
		$_SESSION['redirect_uri'] = admin_url('/profile.php');
?>
	<h3><?php _e( 'Social Media Accounts', $this->text_domain ); ?></h3>
	<table id="binding_social_media_account" class="form-table">
		<tbody>
			<?php do_action( 'qqworld_passport_social_media_account_profile_form', $profileuser ); ?>
		</tbody>
	</table>
<?php
	}

	public function get_avatar($avatar, $id_or_email, $size, $default, $alt, $args) {
		$user_id = '';
		if ( filter_var($id_or_email, FILTER_VALIDATE_EMAIL) ) {
			$user = get_user_by( 'email', $id_or_email );
			$user_id = $user ? $user->ID : null;
		} else {
			$user_id = $id_or_email;
		}
		if (!empty($user_id)) {
			$url = get_user_meta($user_id ,'QQWorld Passport Avatar' ,true);
			if ($url) {
				$class = !empty($args['class']) && is_array($args['class']) ? join( ' ', $args['class'] ) : $args['class'];
				if ($url) $avatar = sprintf(
					"<img alt='%s' src='%s' srcset='%s' class='%s' height='%d' width='%d' %s/>",
					esc_attr( $alt ),
					esc_url( $url ),
					esc_attr( "$url 2x" ),
					esc_attr( $class ),
					(int) $args['height'],
					(int) $args['width'],
					$args['extra_attr']
				);
			}
		}
		return $avatar;
	}

	// quick login by login name
	public function login($user_login, $redirect=true) {
		$user = get_userdatabylogin($user_login);
		$user_id = $user->ID;
		wp_set_current_user($user_id, $user_login);
		wp_set_auth_cookie($user_id, true);
		do_action( 'wp_login', $user_login );
		if ( isset($_SESSION['redirect_uri']) && !empty($_SESSION['redirect_uri']) ) {
			$redirect_uri = $_SESSION['redirect_uri'];
			unset($_SESSION['redirect_uri']);
		} else $redirect_uri = home_url();
		if ($redirect) {
			wp_redirect( $redirect_uri );
			exit;
		}
	}

	public function set_session_start() {
		session_start();
	}

	public function curl($url, $type='get', $args='') { //$args => 'username=michel&password=...' | array('username' => '', 'password' => '')
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if ($type == 'post') {
			curl_setopt($ch, CURLOPT_POST, 1);
			if (!empty($args)) {
				if (is_array($args)) {
					$data = array();
					foreach ($args as $name => $value) $data[] = "{$name}={$value}";
					$args = implode('&', $data);
				}
				curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
			}
		}
		$results = curl_exec($ch);
		curl_close($ch);
		return json_decode($results);
	}

	public function get_current_user_id() {
		if (!empty($_COOKIE)) foreach ($_COOKIE as $key => $value) {
			if (preg_match('/^wordpress_logged_in_.*?$/i', $key, $match)) {
				$value = explode('|', $value);
				if (count($value)) {
					$user = get_user_by( 'login', $value[0] );
					return $user->ID;
				}
				break;
			}
		}
		return false;
	}

	/**
	 * check is user logged in from cookie
	**/
	public function is_user_logged_in() {
		if (!empty($_COOKIE)) foreach ($_COOKIE as $key => $value) {
			if (preg_match('/^wordpress_logged_in_.*?$/i', $key, $match)) {
				$value = explode('|', $value);
				return count($value);
			}
		}
		return false;
	}

	public function register_oauth2_quest() {
		$namespace = 'qqworld-passport/v1';
		// http://www.woocommerce.gov/wp-json/qqworld-passport/v1/module/qq
		register_rest_route( $namespace, 'module/(?P<slug>\w+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'oauth2_quest'),
			'update_callback' => null,
			'schema' => null
		) );
		// http://www.woocommerce.gov/wp-json/qqworld-passport/v1/module/pre/qq
		register_rest_route( $namespace, 'module/pre/(?P<slug>\w+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'pre_oauth2_quest'),
			'update_callback' => null,
			'schema' => null
		) );
	}

	public function oauth2_quest($data) {
		$slug = $data['slug'];
		do_action('qqworld_passport_parse_request_'.$slug);
		exit;
	}

	public function pre_oauth2_quest($data) {
		$slug = $data['slug'];
		do_action('qqworld_passport_pre_parse_request_'.$slug);
		exit;
	}

	public function login_form_middle($content, $args) {
		ob_start();
		$this->login_form();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function login_form() {
		// for Ultimate Member checking template mode
		if (func_num_args() && func_get_arg(0)) {
			$args = func_get_arg(0);
			if (!in_array($args['mode'], array('login', 'register'))) return;
		}

		global $pagenow;
		if ( $pagenow != 'wp-login.php' ) $_SESSION['redirect_uri'] = isset($_SERVER['HTTP_REFERER']) ? set_url_scheme($_SERVER['HTTP_REFERER']) : set_url_scheme("http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
?>
	<style>
	#qqworld-passport-container {
		padding: 0 0 10px;
	}
	#qqworld-passport-container .third-party-login-label {
		margin-bottom: 5px;
	}
	#qqworld-passport-container a {
		display: inline-block;
		padding: 5px;
		background: #f7f7f7;
		border: 1px solid #d4d4d4;
		border-radius: 3px;
		width: 32px;
		height: 32px;
		position: relative;
		box-sizing: initial;
		left: 0;
		top: 0;
	}
	#qqworld-passport-container a:hover {
		background: #fff;
	}
	#qqworld-passport-container img {
		width: 32px;
		height: 32px;
		margin: 0;
		padding: 0;
	}
	</style>
	<?php do_action('qqworld_passport_login_form_before'); ?>
	<div id="qqworld-passport-container">
		<p class="third-party-login-label"><label><?php _e('Third-Party Login', $this->text_domain); ?></label></p>
		<p><?php do_action('qqworld_passport_login_form_buttons'); ?></p>
	</div>
	<?php do_action('qqworld_passport_login_form_after'); ?>
<?php
	}

	//add link to plugin action links
	public function plugin_action_links( $links, $file ) {
		if ( dirname(plugin_basename( __FILE__ )) . '/qqworld-passport.php' === $file ) {
			$settings_link = '<a href="' . menu_page_url( 'qqworld-passport', 0 ) . '">' . __( 'Settings' ) . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}

	public function load_modules() {
		include_once QQWORLD_PASSPORT_DIR . 'modules' . DIRECTORY_SEPARATOR . 'qq' . DIRECTORY_SEPARATOR . 'init.php';
		$this->qq = new qq;
		$this->qq->init();
		include_once QQWORLD_PASSPORT_DIR . 'modules' . DIRECTORY_SEPARATOR . 'wechat' . DIRECTORY_SEPARATOR . 'init.php';
		$this->wechat = new wechat;
		$this->wechat->init();
		include_once QQWORLD_PASSPORT_DIR . 'modules' . DIRECTORY_SEPARATOR . 'weibo' . DIRECTORY_SEPARATOR . 'init.php';
		$this->weibo = new weibo;
		$this->weibo->init();
		/*include_once QQWORLD_PASSPORT_DIR . 'modules' . DIRECTORY_SEPARATOR . 'taobao' . DIRECTORY_SEPARATOR . 'init.php';
		$this->taobao = new taobao;
		$this->taobao->init();
		include_once QQWORLD_PASSPORT_DIR . 'modules' . DIRECTORY_SEPARATOR . 'alipay' . DIRECTORY_SEPARATOR . 'init.php';
		$this->alipay = new alipay;
		$this->alipay->init();*/
	}

	public function register_settings() {
		global $qqworld_passport_modules;
		register_setting($this->text_domain, 'qqworld-passport-modules');
		register_setting($this->text_domain, 'qqworld-passport-avatar-priority');
		if (!empty($qqworld_passport_modules)) {
			foreach ($qqworld_passport_modules as $module) {
				register_setting($this->text_domain.'-module-'.$module->slug, 'qqworld-passport-module-'.$module->slug, array($module, 'sanitize_callback'));
			}
		}

	}

	public function load_language() {
		load_plugin_textdomain( $this->text_domain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	public function admin_menu() {
		$page_title = __('QQWorld Passport', $this->text_domain);
		$menu_title = __('QQWorld Passport', $this->text_domain);
		$capability = 'administrator';
		$menu_slug = $this->text_domain;
		$function = array($this, 'admin_page');
		$icon_url = 'none';
		$settings_page = add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url);
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_style( $this->text_domain, QQWORLD_PASSPORT_URL . 'css/style.css' );
		wp_enqueue_script( $this->text_domain, QQWORLD_PASSPORT_URL . 'js/common.js', array('jquery') );
	}

	public function is_activated($module) {
		return is_array($this->options->activated_modules) && in_array($module, $this->options->activated_modules);
	}

	public function admin_page() {
		global $qqworld_passport_modules;
?>
<div class="wrap" id="qqworld-passport-container">
	<h2><?php _e('QQWorld Passport', $this->text_domain); ?></h2>
	<p><?php _e("QQWorld Passport for Wordpress, Many Oauth 2.0 log in methods.", $this->text_domain); ?></p>
	<img id="banner" src="<?php echo QQWORLD_PASSPORT_URL; ?>images/banner-772x250.png" title="<?php _e('QQWorld Passport', $this->text_domain); ?>" />
	<form action="options.php" method="post" id="update-form">
		<?php settings_fields($this->text_domain); ?>
		<div class="icon32 icon32-qqworld-passport-settings" id="icon-qqworld-passport"><br></div>
		<table class="wp-list-table widefat plugins">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select All'); ?></label><input id="cb-select-all-1" type="checkbox" /></td>
					<th scope="col" id="title" class="manage-column column-signin-method column-primary"><?php _e('Signin Methods', $this->text_domain); ?></th>
					<th scope="col" id="author" class="manage-column column-description"><?php _e('Description', $this->text_domain); ?></th>
					<th scope="col" id="edit" class="manage-column column-edit"><?php _e('Edit'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2"><?php _e('Select All'); ?></label><input id="cb-select-all-1" type="checkbox" /></td>
					<th scope="col" id="title" class="manage-column column-signin-method column-primary"><?php _e('Signin Methods', $this->text_domain); ?></th>
					<th scope="col" id="author" class="manage-column column-description"><?php _e('Description', $this->text_domain); ?></th>
					<th scope="col" id="edit" class="manage-column column-edit"><?php _e('Edit'); ?></th>
				</tr>
			</tfoot>

			<tbody id="the-list">
			<?php
			if (!empty($qqworld_passport_modules)) :
				foreach ($qqworld_passport_modules as $module) :
					$is_activated = $this->is_activated($module->slug);
					$edit_link = admin_url( 'admin.php?page=qqworld-passport_settings_'.$module->slug );
			?>
				<tr id="module-<?php echo $module->slug; ?>" class="<?php echo $is_activated ? 'active' : 'inactive'; ?>">
					<th scope="row" class="check-column">
						<label class="screen-reader-text" for="cb-select-1"><?php echo $module->slug; ?></label>
						<input id="cb-select-1" type="checkbox" name="qqworld-passport-modules[]" value="<?php echo $module->slug; ?>"<?php if ($is_activated) echo ' checked'; ?> />
						<div class="locked-indicator"></div>
					</th>
					<td class="title column-title has-row-actions column-primary page-title" data-colname="<?php _e('Signin Methods', $this->text_domain); ?>">
					<?php if ($is_activated) : ?>
						<strong><a class="row-title" href="<?php echo $edit_link; ?>" title="<?php _e('Edit'); ?>&#147;<?php echo $module->name; ?>&#148;"><?php echo $module->name; ?></a></strong>
						<div class="row-actions">
							<span class="edit"><a href="<?php echo $edit_link; ?>" title="<?php _e('Edit this item'); ?>"><?php _e('Edit'); ?></a>
						</div>
					<?php else: ?>
						<strong><?php echo $module->name; ?></strong>
					<?php endif; ?>
					</td>
					<td class="date column-description"><?php echo $module->description; ?></td>
					<td class="date column-edit">
					<?php if ($is_activated) : ?>
						<a href="<?php echo $edit_link; ?>" class="button"><?php _e('Edit'); ?></a>
					<?php else: ?>
						<input type="button" class="button" value="<?php _e('Edit'); ?>" disabled />
					<?php endif; ?>
					</td>
				</tr>
			<?php
				endforeach;
			endif; ?>
			</tbody>
		</table>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="qqworld-passport-avatar-priority"><?php _e('Avatar Priority', $this->text_domain); ?></label></th>
					<td><fieldset>
						<legend class="screen-reader-text"><span><?php _e('Enabled', $this->text_domain); ?></span></legend>
						<label>
							<input name="qqworld-passport-avatar-priority" type="number" id="qqworld-passport-avatar-priority" value="<?php echo $this->options->avatar_priority; ?>" />
							<p><?php _e('Default is 9999, if you want QQWorld Password to fully take over the avatar display address from the other plugins, please set a larger number.', $this->text_domain); ?></p>
						</label>
					</fieldset></td>
				</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php do_action('qqworld_passport_additional_form_settings'); ?>
<?php
	}
}
$core = new core;
$core->init();