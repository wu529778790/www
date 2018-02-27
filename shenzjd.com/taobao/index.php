<?php
/*
 * 请勿使用windows下的记事本修改本文件。推荐使用 notepad++
 * 版本 v1.0.1
 * 升级日志：
 * 1、修正第一次无法打开，需要刷新才能打开的BUG
 * 2、添加对二级目录的支持
 * 3、添加对非index.php文件名的支持。
 *
 * */
$appId = '36050';  // 站点的APPID （请勿修改）
$appKey = '4557D870D5219BBBC91AB4627F94C74B';// 站点的APP KEY（请勿修改）


//===============================================================================
//===============================================================================
//===============================================================================
//================               请勿修改以下程序            ====================
//===============================================================================
//===============================================================================
//===============================================================================


$host = "http://cms6.qingtaoke.com";

$requestMethod = strtoupper(@$_SERVER["REQUEST_METHOD"]);
$requestUrl = @$_SERVER["REQUEST_URI"];

$cache = new CacheHelper();

if (isset($_REQUEST['clean'])) {
    $cache->clean();
    echo '已清除缓存';
    exit;
}
$key = md5($requestUrl . CacheHelper::isMobile() . CacheHelper::isIPad() . CacheHelper::isIPhone() . CacheHelper::isMicroMessenger());
if ($requestMethod == 'GET') {
    $cacheData = $cache->Get($key);
    if ($cacheData !== false) {
        echo $cacheData;
        exit;
    }
}

$documentUrl = @$_SERVER["PHP_SELF"];
$httpHelper = new HttpHelper($appId, $appKey, $documentUrl);
$html = $httpHelper->getHtml($host, $requestUrl, $requestMethod == 'POST' ? @$_POST : array(), $requestMethod);
if ($requestMethod == 'GET' && !empty($html)) {
    $cache->Set($key, $html, 60);
}
echo $html;

class HttpHelper
{
    protected $appId;
    protected $key;
    protected $documentUrl;

    public function __construct($appId, $key, $documentUrl)
    {
        $this->appId = $appId;
        $this->key = $key;
        $this->documentUrl = $documentUrl;
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
     * @return string
     */
    public function getHtml($url, $requestUrl, $param = array(), $method = 'GET', $isAjax = null, $cookie = NULL, $refer = null, $userAgent = null)
    {
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
            'CMS-HOST: ' . @$_SERVER["HTTP_HOST"],
            'DOCUMENT-URL: ' . $this->documentUrl,
            'REQUEST-URL: ' . $requestUrl,
        );
        if (is_array($param)) {
            $param['u'] = $this->appId;
        } else {
            $param = array('u' => $this->appId);
        }
        $_isAjax = false;
        if ($isAjax) {
            $_isAjax = true;
        }
        if (!$_isAjax && $isAjax === null) {
            $_isAjax = $this->getIsAjaxRequest();
        }
        if ($_isAjax) {
            $header[] = 'X_REQUESTED_WITH: XMLHttpRequest';
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
        curl_close($ch);
        unset($ch);
        $headers = explode("\r\n", $header);
        foreach ($headers as $h) {
            $h = trim($h);
            if (empty($h) || preg_match('/^(HTTP|Connection|EagleId|Server|X\-Powered\-By|Date|Transfer\-Encoding|Content)/i', $h)) {
                continue;
            }
            header($h);
        }
        return $r;
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

