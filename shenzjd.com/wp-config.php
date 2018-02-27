<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/data/home/byu3476600001/htdocs/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'bdm272159347_db');

/** MySQL数据库用户名 */
define('DB_USER', 'bdm272159347');

/** MySQL数据库密码 */
define('DB_PASSWORD', 'wu100861');

/** MySQL主机 */
define('DB_HOST', 'bdm272159347.my3w.com');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/
 * WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '-~<t&OL9e_ZNT!Vw#sCCb7 S[BPEto7|&@Lc|w#z0.e<51|5j0jCO?^=_C,-%@Hk');
define('SECURE_AUTH_KEY',  'hd6y rH0HwqwWW1tuolgvjaUolbe(Zw`);Z<Ad)7ZRi3(pd?hMv1W6S>z<Ot?+<x');
define('LOGGED_IN_KEY',    'KeNsP(Mu0ot0XU~-StH/1IMdp,^J}-SPUL?{Hzws53PcAq 8w@P{ElpSsVi=6~)s');
define('NONCE_KEY',        'zSfQ([cH>HO<J.<@mrf;%-o;;i=g4Afa77KX~d$H,T@zJv-i?=;w#d!Sdb9aREx?');
define('AUTH_SALT',        't`fBBej)KBc%H}ld<[3C<9ikL,(=P`2X;V>A:Ntp:56_uUJN!_2t/)b[fi*x#y2v');
define('SECURE_AUTH_SALT', 'Z@m@f1,R!~3 8pt*I=AUfz8G$wrsK}SwaulTp||Wqf*wM(& $S5bf;TgjmhVZ#ou');
define('LOGGED_IN_SALT',   'xo{i]@hPvw[AIWFH!Dl<zJ$v_^jZe+yP<xO/br68y[8R( P?DNR:f1i)AOR`m).-');
define('NONCE_SALT',       'jXlG7s?TMaQ??a%jTe=K-}rE7y^SF%C1E{mLo $:_=ym)h~5}jj5)VqNek3FsiM*');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/**
 * zh_CN本地化设置：启用ICP备案号显示
 *
 * 可在设置→常规中修改。
 * 如需禁用，请移除或注释掉本行。
 */
define('WP_ZH_CN_ICP_NUM', true);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
