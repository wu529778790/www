<?php
/**
 * Retrieves and creates the wp-config.php file.
 *
 * The permissions for the base directory must allow for writing files in order
 * for the wp-config.php to be created using this page.
 *
 * @internal This file must be parsable by PHP4.
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * We are installing.
 */
define('WP_INSTALLING', true);

/**
 * We are blissfully unaware of anything.
 */
define('WP_SETUP_CONFIG', true);

/**
 * Disable error reporting
 *
 * Set this to error_reporting( -1 ) for debugging
 */
error_reporting(0);

define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );

require( ABSPATH . 'wp-settings.php' );

/** Load WordPress Administration Upgrade API */
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

/** Load WordPress Translation Install API */
require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

nocache_headers();

// Support wp-config-sample.php one level up, for the develop repo.
if ( file_exists( ABSPATH . 'wp-config-sample.php' ) )
	$config_file = file( ABSPATH . 'wp-config-sample.php' );
elseif ( file_exists( dirname( ABSPATH ) . '/wp-config-sample.php' ) )
	$config_file = file( dirname( ABSPATH ) . '/wp-config-sample.php' );
else
	wp_die( __( 'Sorry, I need a wp-config-sample.php file to work from. Please re-upload this file to your WordPress installation.' ) );

// Check if wp-config.php has been created
if ( file_exists( ABSPATH . 'wp-config.php' ) )
	wp_die( '<p>' . sprintf(
			/* translators: %s: install.php */
			__( "The file 'wp-config.php' already exists. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href='%s'>installing now</a>." ),
			'install.php'
		) . '</p>'
	);

// Check if wp-config.php exists above the root directory but is not part of another install
if ( @file_exists( ABSPATH . '../wp-config.php' ) && ! @file_exists( ABSPATH . '../wp-settings.php' ) ) {
	wp_die( '<p>' . sprintf(
			/* translators: %s: install.php */
			__( "The file 'wp-config.php' already exists one level above your WordPress installation. If you need to reset any of the configuration items in this file, please delete it first. You may try <a href='%s'>installing now</a>." ),
			'install.php'
		) . '</p>'
	);
}

$step = isset( $_GET['step'] ) ? (int) $_GET['step'] : -1;

/**
 * Display setup wp-config.php file header.
 *
 * @ignore
 * @since 2.3.0
 *
 * @global string    $wp_local_package
 * @global WP_Locale $wp_locale
 *
 * @param string|array $body_classes
 */
function setup_config_display_header( $body_classes = array() ) {
	$body_classes = (array) $body_classes;
	$body_classes[] = 'wp-core-ui';
	if ( is_rtl() ) {
		$body_classes[] = 'rtl';
	}

	header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"<?php if ( is_rtl() ) echo ' dir="rtl"'; ?>>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php _e( 'WordPress &rsaquo; Setup Configuration File' ); ?></title>
	<?php wp_admin_css( 'install', true ); ?>
</head>
<body class="<?php echo implode( ' ', $body_classes ); ?>">
<p id="logo"><a href="#" tabindex="-1">wordpress优化版</a></p>
<?php
} // end function setup_config_display_header();

$language = '';
if ( ! empty( $_REQUEST['language'] ) ) {
	$language = preg_replace( '/[^a-zA-Z_]/', '', $_REQUEST['language'] );
} elseif ( isset( $GLOBALS['wp_local_package'] ) ) {
	$language = $GLOBALS['wp_local_package'];
}

switch($step) {
	case -1:
		if ( wp_can_install_language_pack() && empty( $language ) && ( $languages = wp_get_available_translations() ) ) {
			setup_config_display_header( 'language-chooser' );
			echo '<h1 class="screen-reader-text">Select a default language</h1>';
			echo '<form id="setup" method="post" action="?step=0">';
			wp_install_language_form( $languages );
			echo '</form>';
			break;
		}

		// Deliberately fall through if we can't reach the translations API.

	case 0:
		if ( ! empty( $language ) ) {
			$loaded_language = wp_download_language_pack( $language );
			if ( $loaded_language ) {
				load_default_textdomain( $loaded_language );
				$GLOBALS['wp_locale'] = new WP_Locale();
			}
		}

		setup_config_display_header();
		$step_1 = 'setup-config.php?step=1';
		if ( isset( $_REQUEST['noapi'] ) ) {
			$step_1 .= '&amp;noapi';
		}
		if ( ! empty( $loaded_language ) ) {
			$step_1 .= '&amp;language=' . $loaded_language;
		}
?>
<div class="first_install">
	<h1 style="text-align: center;margin: 10px 0;">wordpress优化版<span style="margin: 0 20px;">4.7.0</span><a href="http://www.huyouboke.cn/178.html" target="_blank">获取帮助</a></h1>
</div>
<p class="step"><a href="<?php echo $step_1; ?>" class="button button-large">安装</a></p>
<?php
	break;

	case 1:
		load_default_textdomain( $language );
		$GLOBALS['wp_locale'] = new WP_Locale();

		setup_config_display_header();
	?>
<h1 class="screen-reader-text"><?php _e( 'Set up your database connection' ) ?></h1>
<form method="post" action="setup-config.php?step=2">
<p style="text-align: center;">填写数据库</p>
	<table class="form-table">
		<tr>
			<th scope="row" style="width: 75px;"><label for="dbname"><?php _e( 'Database Name' ); ?></label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="wordpress" /></td>
		</tr>
		<tr>
			<th scope="row" style="width: 75px;"><label for="uname">数据库帐号</label></th>
			<td><input name="uname" id="uname" type="text" size="25" value="<?php echo htmlspecialchars( _x( 'username', 'example username' ), ENT_QUOTES ); ?>" /></td>
			
		</tr>
		<tr>
			<th scope="row" style="width: 75px;"><label for="pwd">数据库密码</label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" value="<?php echo htmlspecialchars( _x( 'password', 'example password' ), ENT_QUOTES ); ?>" autocomplete="off" /></td>
			
		</tr>
		<tr>
			<th scope="row" style="width: 75px;"><label for="dbhost">数据库地址</label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" value="localhost" /></td>
			
		</tr>
		<tr>
			<th scope="row" style="width: 75px;"><label for="prefix"><?php _e( 'Table Prefix' ); ?></label></th>
			<td><input name="prefix" id="prefix" type="text" value="wp_" size="25" /></td>
			
		</tr>
	</table>
	<?php if ( isset( $_GET['noapi'] ) ) { ?><input name="noapi" type="hidden" value="1" /><?php } ?>
	<input type="hidden" name="language" value="<?php echo esc_attr( $language ); ?>" />
	<p class="step" style="margin: 30px 0 50px;"><input name="submit" type="submit" value="<?php echo htmlspecialchars( __( 'Submit' ), ENT_QUOTES ); ?>" class="button button-large" /></p>
</form>
<?php
	break;

	case 2:
	load_default_textdomain( $language );
	$GLOBALS['wp_locale'] = new WP_Locale();

	$dbname = trim( wp_unslash( $_POST[ 'dbname' ] ) );
	$uname = trim( wp_unslash( $_POST[ 'uname' ] ) );
	$pwd = trim( wp_unslash( $_POST[ 'pwd' ] ) );
	$dbhost = trim( wp_unslash( $_POST[ 'dbhost' ] ) );
	$prefix = trim( wp_unslash( $_POST[ 'prefix' ] ) );

	$step_1 = 'setup-config.php?step=1';
	$install = 'install.php';
	if ( isset( $_REQUEST['noapi'] ) ) {
		$step_1 .= '&amp;noapi';
	}

	if ( ! empty( $language ) ) {
		$step_1 .= '&amp;language=' . $language;
		$install .= '?language=' . $language;
	} else {
		$install .= '?language=en_US';
	}

	$tryagain_link = '</p><p class="step"><a href="' . $step_1 . '" onclick="javascript:history.go(-1);return false;" class="button button-large">' . __( 'Try again' ) . '</a>';

	if ( empty( $prefix ) )
		wp_die( __( '<strong>ERROR</strong>: "Table Prefix" must not be empty.' . $tryagain_link ) );

	// Validate $prefix: it can only contain letters, numbers and underscores.
	if ( preg_match( '|[^a-z0-9_]|i', $prefix ) )
		wp_die( __( '<strong>ERROR</strong>: "Table Prefix" can only contain numbers, letters, and underscores.' . $tryagain_link ) );

	// Test the db connection.
	/**#@+
	 * @ignore
	 */
	define('DB_NAME', $dbname);
	define('DB_USER', $uname);
	define('DB_PASSWORD', $pwd);
	define('DB_HOST', $dbhost);
	/**#@-*/

	// Re-construct $wpdb with these new values.
	unset( $wpdb );
	require_wp_db();

	/*
	 * The wpdb constructor bails when WP_SETUP_CONFIG is set, so we must
	 * fire this manually. We'll fail here if the values are no good.
	 */
	$wpdb->db_connect();

	if ( ! empty( $wpdb->error ) )
		wp_die( $wpdb->error->get_error_message() . $tryagain_link );

	// Generate keys and salts using secure CSPRNG; fallback to API if enabled; further fallback to original wp_generate_password().
	try {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
		$max = strlen($chars) - 1;
		for ( $i = 0; $i < 8; $i++ ) {
			$key = '';
			for ( $j = 0; $j < 64; $j++ ) {
				$key .= substr( $chars, random_int( 0, $max ), 1 );
			}
			$secret_keys[] = $key;
		}
	} catch ( Exception $ex ) {
		$no_api = isset( $_POST['noapi'] );

		if ( ! $no_api ) {
			$secret_keys = wp_remote_get( 'https://api.wordpress.org/secret-key/1.1/salt/' );
		}

		if ( $no_api || is_wp_error( $secret_keys ) ) {
			$secret_keys = array();
			for ( $i = 0; $i < 8; $i++ ) {
				$secret_keys[] = wp_generate_password( 64, true, true );
			}
		} else {
			$secret_keys = explode( "\n", wp_remote_retrieve_body( $secret_keys ) );
			foreach ( $secret_keys as $k => $v ) {
				$secret_keys[$k] = substr( $v, 28, 64 );
			}
		}
	}

	$key = 0;
	// Not a PHP5-style by-reference foreach, as this file must be parseable by PHP4.
	foreach ( $config_file as $line_num => $line ) {
		if ( '$table_prefix  =' == substr( $line, 0, 16 ) ) {
			$config_file[ $line_num ] = '$table_prefix  = \'' . addcslashes( $prefix, "\\'" ) . "';\r\n";
			continue;
		}

		if ( ! preg_match( '/^define\(\'([A-Z_]+)\',([ ]+)/', $line, $match ) )
			continue;

		$constant = $match[1];
		$padding  = $match[2];

		switch ( $constant ) {
			case 'DB_NAME'     :
			case 'DB_USER'     :
			case 'DB_PASSWORD' :
			case 'DB_HOST'     :
				$config_file[ $line_num ] = "define('" . $constant . "'," . $padding . "'" . addcslashes( constant( $constant ), "\\'" ) . "');\r\n";
				break;
			case 'DB_CHARSET'  :
				if ( 'utf8mb4' === $wpdb->charset || ( ! $wpdb->charset && $wpdb->has_cap( 'utf8mb4' ) ) ) {
					$config_file[ $line_num ] = "define('" . $constant . "'," . $padding . "'utf8mb4');\r\n";
				}
				break;
			case 'AUTH_KEY'         :
			case 'SECURE_AUTH_KEY'  :
			case 'LOGGED_IN_KEY'    :
			case 'NONCE_KEY'        :
			case 'AUTH_SALT'        :
			case 'SECURE_AUTH_SALT' :
			case 'LOGGED_IN_SALT'   :
			case 'NONCE_SALT'       :
				$config_file[ $line_num ] = "define('" . $constant . "'," . $padding . "'" . $secret_keys[$key++] . "');\r\n";
				break;
		}
	}
	unset( $line );

	if ( ! is_writable(ABSPATH) ) :
		setup_config_display_header();
?>
<p><?php
	/* translators: %s: wp-config.php */
	printf( __( 'Sorry, but I can&#8217;t write the %s file.' ), '<code>wp-config.php</code>' );
?></p>
<p><?php
	/* translators: %s: wp-config.php */
	printf( __( 'You can create the %s manually and paste the following text into it.' ), '<code>wp-config.php</code>' );
?></p>
<textarea id="wp-config" cols="98" rows="15" class="code" readonly="readonly"><?php
		foreach ( $config_file as $line ) {
			echo htmlentities($line, ENT_COMPAT, 'UTF-8');
		}
?></textarea>
<p><?php _e( 'After you&#8217;ve done that, click &#8220;Run the install.&#8221;' ); ?></p>
<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Run the install' ); ?></a></p>
<script>
(function(){
if ( ! /iPad|iPod|iPhone/.test( navigator.userAgent ) ) {
	var el = document.getElementById('wp-config');
	el.focus();
	el.select();
}
})();
</script>
<?php
	else :
		/*
		 * If this file doesn't exist, then we are using the wp-config-sample.php
		 * file one level up, which is for the develop repo.
		 */
		if ( file_exists( ABSPATH . 'wp-config-sample.php' ) )
			$path_to_wp_config = ABSPATH . 'wp-config.php';
		else
			$path_to_wp_config = dirname( ABSPATH ) . '/wp-config.php';

		$handle = fopen( $path_to_wp_config, 'w' );
		foreach ( $config_file as $line ) {
			fwrite( $handle, $line );
		}
		fclose( $handle );
		chmod( $path_to_wp_config, 0666 );
		setup_config_display_header();
?>
<h1 class="screen-reader-text"><?php _e( 'Successful database connection' ) ?></h1>
<p><?php _e( 'All right, sparky! You&#8217;ve made it through this part of the installation. WordPress can now communicate with your database. If you are ready, time now to&hellip;' ); ?></p>

<p class="step"><a href="<?php echo $install; ?>" class="button button-large"><?php _e( 'Run the install' ); ?></a></p>
<?php
	endif;
	break;
}
?>
<?php wp_print_scripts( 'language-chooser' ); ?>
</body>
</html>
