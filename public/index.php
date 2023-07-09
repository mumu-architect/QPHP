<?php
namespace index;

use Pool;
use Threaded;
use Exception;
use QPHP\QPHP;

class Run{
    /**
     * 配置
     */
    static private function config(){
       // header("Content-type:text/html;charset=utf-8");//设置框架编码
        ini_set("data.timezone", "Asia/Shanghai");//设置时区
        define('APP_PATH', __DIR__ . '/../');//定义我们的项目路径常量
        define('Lib', APP_PATH.'/QPHP');//定义我们框架目录常量
//define('RPC_RUN',false);//是否开启rpc
//define('ROUTE_PATH',true);//是否开启路由模式
//define('APP_DEBUG', TRUE);
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
        try {
            QPHP::instance()->run();
        } catch (Exception $e) {
        }
        $app = null;
    }

    static public function poolRun():void {
        $pool = new Pool(5);
        $pool->submit(new class extends Threaded{
            public function run() {
                self::config();
                QPHP::instance()->run();
            }
        });
        while ($pool->collect()) continue;
        $pool->shutdown();
    }

}
Run::run();






