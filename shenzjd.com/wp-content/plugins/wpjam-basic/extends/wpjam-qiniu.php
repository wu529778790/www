<?php
/*
Plugin Name: 七牛镜像云存储
Description: 使用七牛云存储实现 WordPress 博客静态文件 CDN 加速！
Plugin URI: http://blog.wpjam.com/project/wpjam-qiniu/
Author URI: http://blog.wpjam.com/
Version: 1.3.3
 */

add_filter('wpjam-qiniutek_defaults', 'wpjam_qiniutek_get_defaults');
function wpjam_qiniutek_get_defaults($defaults)
{
    global $content_width;
    $defaults = array(
        'exts'        => 'js|css|png|jpg|jpeg|gif|ico',
        'dirs'        => 'wp-content|wp-includes',
        'local'       => home_url(),
        'thumb4admin' => true,
        'google'      => 'disabled',
        'wdith'       => $content_width,
        'disslove'    => '100',
        'dx'          => '10',
        'dy'          => '10',
    );
    if (is_network_admin()) {
        unset($defaults['local']);
        unset($defaults['wdith']);
    }

    return $defaults;
}

function wpjam_qiniu_get_setting($setting_name)
{
    return wpjam_get_setting('wpjam-qiniutek', $setting_name);
}

//定义在七牛绑定的域名。
add_filter('wpjam_cdn_host', 'wpjam_qiniu_cdn_host');
function wpjam_qiniu_cdn_host($cdn_host)
{
    return (wpjam_qiniu_get_setting('host')) ? wpjam_qiniu_get_setting('host') : $cdn_host;
}

add_filter('wpjam_local_host', 'wpjam_qiniu_local_host');
function wpjam_qiniu_local_host($local_host)
{
    return (wpjam_qiniu_get_setting('local')) ? wpjam_qiniu_get_setting('local') : $local_host;
}

add_filter('wpjam_cdn_name', 'wpjam_qiniu_cdn_name');
function wpjam_qiniu_cdn_name($tag)
{
    return 'qiniu';
}

add_filter('wpjam_google', 'wpjam_qiniu_google');
function wpjam_qiniu_google($status)
{
    if (is_multisite()) {
        $wpjam_qiniu = get_site_option('wpjam-qiniutek');

        return isset($wpjam_qiniu['google']) ? $wpjam_qiniu['google'] : '';
    } else {
        return wpjam_qiniu_get_setting('google');
    }
}

add_filter('wpjam_html_replace', 'wpjam_qiniu_html_replace');
function wpjam_qiniu_html_replace($html)
{
    if (is_admin()) {
        return $html;
    }

    $cdn_exts = wpjam_qiniu_get_setting('exts');
    $cdn_dirs = str_replace('-', '\-', wpjam_qiniu_get_setting('dirs'));

    if ($cdn_dirs) {
        $regex = '/' . str_replace('/', '\/', LOCAL_HOST) . '\/((' . $cdn_dirs . ')\/[^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\]\?]{1})/';
        $html  = preg_replace($regex, CDN_HOST . '/$1$4', $html);

        $regex = '/' . str_replace('/', '\/', LOCAL_HOST2) . '\/((' . $cdn_dirs . ')\/[^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\]\?]{1})/';
        $html  = preg_replace($regex, CDN_HOST . '/$1$4', $html);
    } else {
        $regex = '/' . str_replace('/', '\/', LOCAL_HOST) . '\/([^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\]\?]{1})/';
        $html  = preg_replace($regex, CDN_HOST . '/$1$3', $html);

        $regex = '/' . str_replace('/', '\/', LOCAL_HOST2) . '\/([^\s\?\\\'\"\;\>\<]{1,}.(' . $cdn_exts . '))([\"\\\'\s\]\?]{1})/';
        $html  = preg_replace($regex, CDN_HOST . '/$1$3', $html);
    }

    return $html;
}

add_filter('wpjam_remote_image', 'wpjam_qiniu_remote', 10, 2);
function wpjam_qiniu_remote($status, $img_url)
{

    if (wpjam_qiniu_get_setting('remote') == false//    后台没开启
         || get_option('permalink_structure') == false) {
        //    没开启固定链接
        return false;
    }

    $exceptions = explode("\n", wpjam_qiniu_get_setting('exceptions')); // 后台设置不加载的远程图片

    if ($exceptions) {
        foreach ($exceptions as $exception) {
            if (trim($exception) && strpos($img_url, trim($exception)) !== false) {
                return false;
            }
        }
    }

    return apply_filters('pre_qiniu_remote', true, $img_url);
}

add_filter('wpjam_content_image', 'wpjam_qiniu_content_image', 10, 4);
function wpjam_qiniu_content_image($img_url, $width, $height, $retina = 1)
{

    if (false == apply_filters('pre_qiniu_watermark', false, $img_url) && empty($_GET['debug'])) {
        //    没人阻拦，哥就加水印了
        $img_url = wpjam_get_qiniu_watermark($img_url);
    }

    if (wpjam_qiniu_get_setting('width') || isset($_GET['width'])) {
        $width  = ($width) ? $width : wpjam_qiniu_get_setting('width');
        $width  = isset($_GET['width']) ? $_GET['width'] : $width; // for json 接口
        $width  = $width * $retina;
        $height = $height * $retina;

        return wpjam_qiniu_thumbnail($img_url, $width, $height, 0);
    }

    return $img_url;
}

//使用七牛缩图 API 进行裁图
add_filter('wpjam_thumbnail', 'wpjam_qiniu_thumbnail', 10, 4);
function wpjam_qiniu_thumbnail($img_url, $width = 0, $height = 0, $crop = 1)
{
    if (CDN_HOST != home_url()) {
        $img_url = str_replace(LOCAL_HOST, CDN_HOST, $img_url);

        // if($width || $height){
        $crop      = $crop && ($width && $height); // 只有都设置了宽度和高度才裁剪
        $mode      = $crop ? '1' : '2';
        $format    = (wpjam_qiniu_get_setting('webp')) ? 'webp' : '';
        $interlace = (wpjam_qiniu_get_setting('interlace')) ? 1 : 0;
        $quality   = wpjam_qiniu_get_setting('quality');
        $img_url   = wpjam_get_qiniu_thumbnail($img_url, $mode, $width, $height, $format, $interlace, $quality);
        // }
    }

    return $img_url;
}

// 获取七牛缩略图
function wpjam_get_qiniu_thumbnail($img_url, $mode = 0, $width = 0, $height = 0, $format = '', $interlace = 0, $quality = 0)
{
    $arg = '';

    if (wpjam_qiniu_get_setting('imageslim')) {
        $arg = 'imageslim';
    }

    if ($width || $height || $format || $interlace || $quality) {
        if ($arg) {
            if (function_exists('is_weixin')) {
                $arg = $arg . urlencode('|') . 'imageView2';
            } else {
                $arg = $arg . '|imageView2';
            }
        } else {
            $arg = 'imageView2';
        }

        $arg .= '/' . $mode;

        if ($width) {
            $arg .= '/w/' . $width;
        }

        if ($height) {
            $arg .= '/h/' . $height;
        }

        if ($format) {
            $arg .= '/format/' . $format;
        }

        if ($interlace) {
            $arg .= '/interlace/' . $interlace;
        }

        if ($quality) {
            $arg .= '/q/' . $quality;
        }

        if (strpos($img_url, 'watermark')) {
            $img_url = $img_url . '|' . $arg;
        } else {
            $img_url = add_query_arg(array($arg => ''), $img_url);
        }
    }

    return $img_url;
}

// 获取七牛水印
function wpjam_get_qiniu_watermark($img_url, $watermark = '', $dissolve = '', $gravity = '', $dx = 0, $dy = 0)
{

    $watermark = ($watermark) ? $watermark : wpjam_qiniu_get_setting('watermark');
    if ($watermark) {
        $watermark = str_replace(array('+', '/'), array('-', '_'), base64_encode($watermark));

        $dissolve = ($dissolve) ? $dissolve : wpjam_qiniu_get_setting('dissolve');
        $dissolve = ($dissolve) ? $dissolve : '100';

        $gravity = ($gravity) ? $gravity : wpjam_qiniu_get_setting('gravity');
        $gravity = ($gravity) ? $gravity : 'SouthEast';

        $dx = ($dx) ? $dx : wpjam_qiniu_get_setting('dx');
        $dx = ($dx) ? $dx : '10';

        $dy = ($dy) ? $dy : wpjam_qiniu_get_setting('dy');
        $dy = ($dy) ? $dy : '10';

        $watermark = 'watermark/1/image/' . $watermark . '/dissolve/' . $dissolve . '/gravity/' . $gravity . '/dx/' . $dx . '/dy/' . $dy;

        if (strpos($img_url, 'imageView')) {
            $img_url = $img_url . '|' . $watermark;
        } else {
            $img_url = add_query_arg(array($watermark => ''), $img_url);
        }
    }

    return $img_url;
}

function wpjam_get_qiniu_image_info($img_url)
{
    $img_url = add_query_arg(array('imageInfo' => ''), $img_url);

    $response = wp_remote_get($img_url);
    if (is_wp_error($response)) {
        return $response;
    }

    $response = json_decode($response['body'], true);

    if (isset($response['error'])) {
        return new WP_Error('error', $response['error']);
    }

    return $response;
}

add_filter('wpjam_default_thumbnail_uri', 'wpjam_qiniu_default_thumbnail_uri');
function wpjam_qiniu_default_thumbnail_uri($default_thumbnail_uri)
{
    return wpjam_qiniu_get_setting('default');
}

// add_action('admin_enqueue_scripts', 'wpjam_qiniu_enqueue_scripts', 1 );
add_action('wp_enqueue_scripts', 'wpjam_qiniu_enqueue_scripts', 1);
function wpjam_qiniu_enqueue_scripts()
{

    if (wpjam_qiniu_get_setting('jquery')) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', '//dn-staticfile.qbox.me/jquery/2.1.4/jquery.min.js', array(), '2.1.4');
    } else {
        wp_deregister_script('jquery-core');
        wp_register_script('jquery-core', '//dn-staticfile.qbox.me/jquery/1.11.1/jquery.min.js', array(), '1.11.1');

        wp_deregister_script('jquery-migrate');
        wp_register_script('jquery-migrate', '//dn-staticfile.qbox.me/jquery-migrate/1.2.1/jquery-migrate.min.js', array(), '1.2.1');
    }
}

add_filter('wp_resource_hints', 'wpjam_add_qiniu_host_resource_hints', 10, 2);
function wpjam_add_qiniu_host_resource_hints($urls, $relation_type)
{
    if ('dns-prefetch' == $relation_type) {
        $urls[] = CDN_HOST;
    }

    return $urls;
}
