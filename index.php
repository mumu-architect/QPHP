<?php

header("Content-type:text/html;charset=utf-8");//设置框架编码
ini_set("data.timezone", "Asia/Shanghai");//设置时区
$module = 'index';
if(isset($_SERVER['REQUEST_URI'])){
    $url = $_SERVER['REQUEST_URI'];
    $_arr=explode('/',$url);
    $module = strtolower($_arr[1]);
    if($url=='/'){
        $module = 'index';
    }
}

//PHP $_REQUEST 用于收集HTML表单提交的数据
$_REQUEST['module'] = $GLOBALS['argv']['1'];
$_REQUEST['mod'] = $GLOBALS['argv']['2'];
$_REQUEST['action'] = $GLOBALS['argv']['3'];

define('APP_PATH', __DIR__ . '/');//定义我们的项目路径常量
define('Lib', './QPHP');//定义我们框架目录常量
define('Module', !empty($_REQUEST['module'])?strtolower($_REQUEST['module']):$module);
define('Resource', APP_PATH . 'application/'.Module.'/Resource');//定义我们的项目资源目录常量

define('APP_DEBUG', TRUE);
ini_set("display_errors", true);//是否抛出错误 上线修改为false

//加载vendor
require APP_PATH . 'vendor/autoload.php';

require Lib . '/QPHP.php';
$app = new QPHP();
$app->run();
$app = null;
