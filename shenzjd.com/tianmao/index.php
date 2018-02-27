<?php
/*
 * 请勿使用windows下的记事本修改本文件。推荐使用 notepad++
 * 版本 v2.3
 *  1.500页面展示
 * 版本 v2.2
 *  1.增加mbstring检测
 *  2.错误显示
 *版本 v2.1
 *  1.增加cache检测
 *  2.增加显示debug信息限制
 *
 * 版本 v2.0
 *  1.documentUrl 做兼容解决部分用户二级目录报错
 *  2.增加debug信息方便为用户定位错误
 *
 * 版本 v1.1.0
 * 升级日志：
 *  1、添加自动更新
 *  2、修正缓存的BUG
 *  3、添加自动清理cache
 *  4、实现http code的转发
 *
 * 版本 v1.0.1
 * 升级日志：
 *  1、修正第一次无法打开，需要刷新才能打开的BUG
 *  2、添加对二级目录的支持
 *  3、添加对非index.php文件名的支持。
 *
 * */
$appId = '493252';  // 站点的APPID （请勿修改和泄漏）
$appKey = '5D119C00AB5CC5898F937013FB0F0673';// 站点的APP KEY（请勿修改和泄漏）
$proxyVersion = 8;
$autoCleanCache = 200;

//===============================================================================
//===============================================================================
//===============================================================================
//================               请勿修改以下程序            ====================
//===============================================================================
//===============================================================================
//===============================================================================


$host = "http://cms1.dataoke.com";


$html_500=<<<html
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta http-equiv="x-dns-prefetch-control" content="on"/>
 <meta name="apple-mobile-web-app-capable" content="yes"/>
 <meta content="telephone=no" name="format-detection"/>
 <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
<title>500页面</title></head><body>
<div class="wrong-main">
<div class="wrong-icon"><img src="https://img.alicdn.com/imgextra/i3/97012073/TB2DHjloY4npuFjSZFmXXXl4FXa_!!97012073.png" width="100%"></div>
<div class="wrong-txt"><p>服务器开小差了，很快就好，稍后再刷新试试吧~</p></div>
</div>
</body>
<style>
		body{margin:0;padding:0;color:#817577;text-align:center;}.wrong-main{width:90%;margin: 180px auto 0}.wrong-icon{display:block;}.wrong-icon img{max-width: 300px;}.wrong-txt{margin-top: 30px;display: block;}
@media only screen and (max-width: 380px){.wrong-txt{font-size:.8rem}}
</style>
</html>
html;
$test_env = strrpos(@$_SERVER['HTTP_USER_AGENT'], 'test') === false ? false : true;
$requestMethod = strtoupper(@$_SERVER["REQUEST_METHOD"]);
$requestUrl = @$_SERVER["REQUEST_URI"];
if ($test_env) {
    ini_set("display_errors", "On");
    error_reporting(E_ALL | E_STRICT);
}
//debug
if ($test_env && isset($_GET['debug']) && $_GET['debug'] == 'php') {
    header("Content-type: text/html; charset=utf-8");
    echo 'cms 版本：' . $proxyVersion . '<br>';
    echo 'php 版本：' . PHP_VERSION . '<br>';
    if (function_exists('curl_init')) {
        echo 'curl 已经开启 ' . '<br>';
    } else {
        echo 'curl <span style="color: red">未开启,请先开启curl扩展，否则无法运行,请联系您的空间或者服务器提供商</span>' . '<br>';
    }
    if (function_exists('mb_substr')) {
        echo 'mbstring 已经开启 ' . '<br>';
    } else {
        echo 'mbstring <span style="color: red">未开启,请先开启mbstring扩展，否则无法运行</span>' . '<br>';
    }
    $test_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache';
    $test_file = $test_dir . '/test.txt';
    if (!is_dir($test_dir)) {
        @mkdir($this->dir);
    }
    @file_put_contents($test_file, 'test');
    if (file_exists($test_file)) {
        echo 'cache：有效<br>';
    } else {
        echo 'cache <span style="color: red">无效</span>' . '<br>';
    }
    exit;
}

$cache = new CacheHelper();

if (isset($_REQUEST['clean'])) {
    $cache->clean();
    header("Content-type: text/html; charset=utf-8");
    echo '已清除缓存';
    exit;
}
if (mt_rand(0, $autoCleanCache) == 1) {
    $cache->clean();
}
$key = md5($requestUrl . CacheHelper::isMobile() . CacheHelper::isIPad() . CacheHelper::isIPhone() . CacheHelper::isMicroMessenger());
if ($requestMethod == 'GET') {
    if (!$test_env) {
        $cacheData = $cache->Get($key);
        if ($cacheData !== false) {
            if(empty($cacheData)){
                echo  $html_500;
            }else{
                echo $cacheData;
            }
            exit;
        }
    }
}

$documentUrl = @$_SERVER["PHP_SELF"];
if (empty($documentUrl)) {
    $documentUrl = @$_SERVER["SCRIPT_NAME"];
}
if (empty($documentUrl)) {
    $documentUrl = @$_SERVER["DOCUMENT_URI"];
}
if (empty($documentUrl)) {
    $documentUrl = $requestUrl;
    $str_pos = strpos($requestUrl, '?');
    if ($str_pos !== false) {
        $documentUrl = substr($requestUrl, 0, $str_pos);
    }
}

$httpHelper = new HttpHelper($appId, $appKey, $proxyVersion, $documentUrl);
$html = $httpHelper->getHtml($host, $requestUrl, $requestMethod == 'POST' ? @$_POST : array(), $requestMethod);
if ($requestMethod == 'GET' && $httpHelper->httpCode == 200 && !empty($html) && !$test_env) {
    $cache->Set($key, $html, 60);
}
if (!empty($html)) {
    echo $html;
}else{
    echo $html_500;
}
exit;


class HttpHelper
{
    protected $appId;
    protected $key;
    protected $documentUrl;
    protected $proxyVersion;
    protected $upgradeUrl = "http://www.dataoke.com/personSetCms/index.php?r=userApply/upgrade";

    public $httpCode = 200;

    public function __construct($appId, $key, $proxyVersion, $documentUrl)
    {
        $this->appId = $appId;
        $this->key = $key;
        $this->documentUrl = $documentUrl;
        $this->proxyVersion = $proxyVersion;
    }


    /**
     * @param $url
     * @param $requestUrl
     * @param array $param
     * @param string $method
     * @param bool $isAjax
     * @param string $cookie
     * @param string $refer
     * @param null $userAgent
     * @param bool $checkNewVersion
     * @return string
     */
    public function getHtml($url, $requestUrl, $param = array(), $method = 'GET', $isAjax = null, $cookie = NULL, $refer = null, $userAgent = null, $checkNewVersion = true)
    {
        if (strpos($requestUrl, 'auth') !== false) {
            $url .= '/auth';
        }
        if($requestUrl=='/favicon.ico'){
            exit;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 1);
        empty($refer) && $refer = @$_SERVER['HTTP_REFERER'];
        $ua = $userAgent;
        empty($ua) && $ua = @$_SERVER['HTTP_USER_AGENT'];
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_REFERER, $refer);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $header = array(
            'APPID: ' . $this->appId,
            'APPKEY: ' . $this->key,
            'PROXY-VERSION: ' . $this->proxyVersion,
            'CMS-HOST: ' . @$_SERVER["HTTP_HOST"],
            'DOCUMENT-URL: ' . $this->documentUrl,
            'REQUEST-URL: ' . $requestUrl,
        );
        //debug
        global $test_env;
        if ($test_env && isset($_GET['debug']) && $_GET['debug'] == 'header') {
            echo 'CMS-HOST: ' . @$_SERVER["HTTP_HOST"] . '<br>';
            echo 'DOCUMENT-URL: ' . $this->documentUrl . '<br>';
            echo 'REQUEST-URL: ' . $requestUrl . '<br>';
            echo 'api-host: ' . $url . '<br>';
            exit;
        }
        $_isAjax = false;
        if ($isAjax) {
            $_isAjax = true;
        }
        if (!$_isAjax && $isAjax === null) {
            $_isAjax = $this->getIsAjaxRequest();
        }
        if ($_isAjax) {
            $header[] = 'X-Requested-With: XMLHttpRequest';
        }
        $clientIp = $this->get_real_ip();
        if (!empty($clientIp)) {
            $header[] = 'CLIENT-IP: ' . $clientIp;
            $header[] = 'X-FORWARDED-FOR: ' . $clientIp;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if (empty($cookie)) {
            $cookie = $_COOKIE;
        }
        if (is_array($cookie)) {
            $str = '';
            foreach ($cookie as $k => $v) {
                $str .= $k . '=' . $v . '; ';
            }
            $cookie = $str;
        }
        if (!empty($cookie)) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if (strtolower($method) == 'post') {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            if ($param) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
            }
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
            if ($param) {
                $urlInfo = parse_url($url);
                $q = array();
                if (isset($urlInfo['query']) && !empty($urlInfo['query'])) {
                    parse_str($urlInfo['query'], $q);
                }
                $q = array_merge($q, $param);
                $cUrl = sprintf('%s://%s%s%s%s',
                    $urlInfo['scheme'],
                    $urlInfo['host'],
                    isset($urlInfo['port']) ? ':' . $urlInfo['port'] : '',
                    isset($urlInfo['path']) ? $urlInfo['path'] : '',
                    count($q) ? '?' . http_build_query($q) : '');
                curl_setopt($ch, CURLOPT_URL, $cUrl);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $r = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = mb_substr($r, 0, $headerSize);
        $r = mb_substr($r, $headerSize);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        unset($ch);
        $headers = explode("\r\n", $header);
        //debug
        if ($test_env && isset($_GET['debug']) && $_GET['debug'] == 'res') {
            var_dump($r);
            var_dump($this->httpCode);
            exit;
        }
        //debug
        if ($test_env && isset($_GET['debug']) && $_GET['debug'] == 'resheader') {
            var_dump($headers);
            exit;
        }
        if ($this->httpCode != 200) {
            if (function_exists('http_response_code')) {
                http_response_code($this->httpCode);
            } else {
                if ($this->httpCode !== 302) {
                    $this->setHttpResponseCode($this->httpCode);
                }
            }
        }
        $expires = time() + 300;
        foreach ($headers as $h) {
            $h = trim($h);
            if (empty($h) || preg_match('/^(HTTP|Connection|EagleId|Server|X\-Powered\-By|Date|Transfer\-Encoding|Content)/i', $h)) {
                continue;
            }
            if (strpos($h, 'expires:') !== false) {
                $temp_arr = explode(':', $h);
                if (!empty($temp_arr[1]) && is_numeric(trim($temp_arr[1]))) {
                    $expires = intval(trim($temp_arr[1]));
                }
            }
            if (strpos($h, 'Cookie') !== false) {

                $h = explode(':', $h);
                if (!empty($h[1])) {
                    $h = explode('=', $h[1]);
                    if (!empty($h[0]) && !empty($h[1])) {
                        @setcookie(trim($h[0]), trim($h[1]), $expires);
                    }
                }
            } else {
                header($h);
            }
        }
        //debug
        if ($test_env && isset($_GET['debug']) && $_GET['debug'] == 'res1') {
            var_dump($r);
            var_dump($headers);
            exit;
        }

        if ($this->httpCode != 200 && $this->httpCode != 302) {
            return false;
        }

        if($this->httpCode==200 && $checkNewVersion){
            foreach ($headers as $h) {
                if ( preg_match('/pv:\s*(?P<pv>\d+)/i', $h, $regs)) {
                    $pv = $regs['pv'];
                    if ($pv > $this->proxyVersion) {
                        $this->upgrade();
                    }
                }
            }
        }


        return $r;
    }

    protected function upgrade()
    {
        $php = $this->getHtml($this->upgradeUrl, '', array(), 'GET', false, null, null, null, false);
        if ($php === false || strlen($php) < 500) {
            return;
        }
        $php = @json_decode($php, true);
        if (empty($php['appid']) || empty($php['appkey']) || empty($php['content']) || $php['appid'] !== $this->appId) {
            return;
        }
        $file = @$_SERVER["DOCUMENT_ROOT"] . $this->documentUrl;
        if (!defined("DTK_TYPE")) {
            @file_put_contents($file, $php['content'], LOCK_EX);
        } else {
            $file .= 'req.php';
            @file_put_contents($file, $php['content'], LOCK_EX);
        }
        $cache = new  CacheHelper();
        $cache->clean();
    }


    function get_real_ip()
    {
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) {
            $ip = @$_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (@$_SERVER["HTTP_CLIENT_IP"]) {
            $ip = @$_SERVER["HTTP_CLIENT_IP"];
        } elseif (@$_SERVER["REMOTE_ADDR"]) {
            $ip = @$_SERVER["REMOTE_ADDR"];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "";
        }
        return $ip;
    }

    public function getIsAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function setHttpResponseCode($code)
    {
        switch ($code) {
            case 100:
                $text = 'Continue';
                break;
            case 101:
                $text = 'Switching Protocols';
                break;
            case 200:
                $text = 'OK';
                break;
            case 201:
                $text = 'Created';
                break;
            case 202:
                $text = 'Accepted';
                break;
            case 203:
                $text = 'Non-Authoritative Information';
                break;
            case 204:
                $text = 'No Content';
                break;
            case 205:
                $text = 'Reset Content';
                break;
            case 206:
                $text = 'Partial Content';
                break;
            case 300:
                $text = 'Multiple Choices';
                break;
            case 301:
                $text = 'Moved Permanently';
                break;
            case 302:
                $text = 'Moved Temporarily';
                break;
            case 303:
                $text = 'See Other';
                break;
            case 304:
                $text = 'Not Modified';
                break;
            case 305:
                $text = 'Use Proxy';
                break;
            case 400:
                $text = 'Bad Request';
                break;
            case 401:
                $text = 'Unauthorized';
                break;
            case 402:
                $text = 'Payment Required';
                break;
            case 403:
                $text = 'Forbidden';
                break;
            case 404:
                $text = 'Not Found';
                break;
            case 405:
                $text = 'Method Not Allowed';
                break;
            case 406:
                $text = 'Not Acceptable';
                break;
            case 407:
                $text = 'Proxy Authentication Required';
                break;
            case 408:
                $text = 'Request Time-out';
                break;
            case 409:
                $text = 'Conflict';
                break;
            case 410:
                $text = 'Gone';
                break;
            case 411:
                $text = 'Length Required';
                break;
            case 412:
                $text = 'Precondition Failed';
                break;
            case 413:
                $text = 'Request Entity Too Large';
                break;
            case 414:
                $text = 'Request-URI Too Large';
                break;
            case 415:
                $text = 'Unsupported Media Type';
                break;
            case 500:
                $text = 'Internal Server Error';
                break;
            case 501:
                $text = 'Not Implemented';
                break;
            case 502:
                $text = 'Bad Gateway';
                break;
            case 503:
                $text = 'Service Unavailable';
                break;
            case 504:
                $text = 'Gateway Time-out';
                break;
            case 505:
                $text = 'HTTP Version not supported';
                break;
            default:
                $text = '';
                break;
        }
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' ' . $code . ' ' . $text);
    }
}

class CacheHelper
{
    protected $dir = '';

    public function __construct()
    {
        $this->dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache';
        if (is_dir($this->dir)) {
            return;
        }
        @mkdir($this->dir);
    }

    public function Set($key, $value, $expire = 360)
    {
        $data = array(
            'time' => time(),
            'expire' => $expire,
            'value' => $value
        );
        @file_put_contents($this->dir . DIRECTORY_SEPARATOR . md5($key) . 'cache', serialize($data));
    }

    public function Get($key)
    {

        $file = $this->dir . DIRECTORY_SEPARATOR . md5($key) . 'cache';
        if (!file_exists($file)) {
            return false;
        }
        $str = @file_get_contents($file);
        if (empty($str)) {
            return false;
        }
        $data = @unserialize($str);
        if (!isset($data['time']) || !isset($data['expire']) || !isset($data['value'])) {
            return false;
        }
        if ($data['time'] + $data['expire'] < time()) {
            return false;
        }
        return $data['value'];
    }

    static function isMobile()
    {
        $ua = @$_SERVER['HTTP_USER_AGENT'];
        return preg_match('/(iphone|android|Windows\sPhone)/i', $ua);
    }

    public function clean()
    {
        if (!empty($this->dir) && is_dir($this->dir)) {
            @rmdir($this->dir);
        }
        $files = scandir($this->dir);
        foreach ($files as $file) {
            @unlink($this->dir . DIRECTORY_SEPARATOR . $file);
        }
    }


    static function isMicroMessenger()
    {
        $ua = @$_SERVER['HTTP_USER_AGENT'];
        return preg_match('/MicroMessenger/i', $ua);
    }

    static function isIPhone()
    {
        $ua = @$_SERVER['HTTP_USER_AGENT'];
        return preg_match('/iPhone/i', $ua);
    }

    static function isIPad()
    {
        $ua = @$_SERVER['HTTP_USER_AGENT'];
        return preg_match('/(iPad|)/i', $ua);
    }
}