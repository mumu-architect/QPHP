<?php
header("Content-type:text/html;charset=utf-8");//设置框架编码
ini_set("data.timezone","Asia/Shanghai");//设置时区

define('APP_PATH',__DIR__.'/');//定义我们的项目路径常量
define('Lib','../QPHP');//定义我们框架目录常量
define('Resource',APP_PATH.'Resource');//定义我们的项目资源目录常量

define('APP_DEBUG',TRUE);
ini_set("display_errors",true);//是否抛出错误 上线修改为false

//

require Lib.'/QPHP.php';
$app = new QPHP();
$app->run();
$app = null;
