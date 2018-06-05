<?php
error_reporting(E_ALL & ~E_NOTICE);
$isOnline = 0; // 0 为本地 1 为在线地址
if ($isOnline) {
  $domain = 'http://';
  $srcs = '';
}else {
  $domain = 'http://localhost';
  $srcs = 'YeapooWeb/';
}
// project's base domain, a valid url
define('BASE_DOMAIN', $domain);
// project's secondary dirtory, a string start without slash, e.g. '/project'
define('SECONDARY_DIR', $srcs.'page');
// project's static resource url, a cdn url is better
// define('ASSETS_PATH', BASE_DOMAIN . ('/'.$srcs.'assets/'));
define('ASSETS_PATH', ('../'));

// a dirtory stores project's page files
define('PAGE_PATH', __DIR__);


define('SECONDARY_DIR_LENGTH', strlen(SECONDARY_DIR));

// 获取访问路径
$path = $_SERVER['PATH_INFO'];
if (SECONDARY_DIR_LENGTH !== 0) {
    define('BASE_URL', BASE_DOMAIN . '/' . SECONDARY_DIR . '/');
    // $path = substr($path, SECONDARY_DIR_LENGTH);
} else {
    define('BASE_URL', BASE_DOMAIN . '/');
}

// 设置默认访问路径
if (!$path || $path === '/') {
    $path = '/index';
}

// 去除路径末尾的斜杠
if (substr($path, -1) === '/') {
    $path = substr($path, 0, -1);
}

$page = PAGE_PATH . '/view' . $path . '.php';
if (!file_exists($page)) {
    show404($page);
} else {
    showPage($page);
}

function show404($page) {
    header('HTTP/1.1 404 Not Found');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"/><title>File Not Found</title></head><body>File <font color="#289FCA">'.$page.'</font> Not Found</body></html>';
}

function showPage($page) {
    require PAGE_PATH ."/_layout/layout.php";
    require $page;
    echo '
</body>
</html>';
}

function alink($url) {
    echo BASE_URL . $url . '/';
}

function assets($res) {
    echo ASSETS_PATH . $res;
}
