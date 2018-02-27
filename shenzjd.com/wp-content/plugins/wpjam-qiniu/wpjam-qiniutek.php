<?php
/*
Plugin Name: WPJAM 七牛镜像存储
Description: 使用七牛云存储实现 WordPress 博客静态文件 CDN 加速！「安装本插件<strong>1.4.5及以上版本</strong>，请先安装并激活<a href="https://wordpress.org/plugins/wpjam-basic/">WPJAM BASIC</a>插件。如果是使用<strong>1.4.5以下版本</strong>，<a href="https://wordpress.org/plugins/wpjam-basic/">WPJAM BASIC</a>插件已包含七牛插件，如果启用WPJAM BASIC插件，请先停用插件。」
Plugin URI: http://blog.wpjam.com/project/wpjam-qiniutek/
Author: Denis
Author URI: http://blog.wpjam.com/
Version: 1.4.6
*/

if (!defined('WPJAM_BASIC_PLUGIN_URL')) {
    $plugin = __FILE__;
    if (!function_exists('deactivate_plugins')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    deactivate_plugins($plugin);
    wp_die('安装本插件，请先安装并激活<a href="https://wordpress.org/plugins/wpjam-basic/" target="_blank">WPJAM BASIC</a>插件。');
    return;
}

if (!function_exists('wpjam_qiniu_cdn_host')) {
    include(WPJAM_BASIC_PLUGIN_DIR . 'extends/wpjam-qiniu.php');
    include(WPJAM_BASIC_PLUGIN_DIR . 'extends/admin/wpjam-qiniu.php');
}