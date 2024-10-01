<?php
namespace QPHP;

use Exception;

class Base{
    /**
     * 配置
     */
    static private function config(){
        session_start();
        // header("Content-type:text/html;charset=utf-8");//设置框架编码
        ini_set("data.timezone", "Asia/Shanghai");//设置时区
        define('APP_PATH', __DIR__ . '/../');//定义我们的项目路径常量
        define('Lib', APP_PATH.'/QPHP');//定义我们框架目录常量

        ini_set("display_errors", true);//是否抛出错误 上线修改为false
        //加载vendor
        require_once APP_PATH . 'vendor/autoload.php';
        //引入框架核心文件
        require_once Lib . '/QPHP.php';
    }


    /**
     * 运行
     */
    static public function run():void {
        self::config();
        //异常注册自行处理set_exception_handler(array($this,'AppException'));
        QPHP::instance()->run();
        $app = null;
    }

//    static public function poolRun():void {
//        $pool = new Pool(5);
//        $pool->submit(new class extends Threaded{
//            public function run() {
//                self::config();
//                QPHP::instance()->run();
//            }
//        });
//        while ($pool->collect()) continue;
//        $pool->shutdown();
//    }

}
