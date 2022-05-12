<?php
class QPHP
{
    //框架的运行方法
    public function run(){
        $gloabal = APP_PATH.'application/'.Module.'/App/Util/include/global.php';
        require $gloabal;

        //调用配置文件
        $this->init_config();
        //加载App/util/include
        //加载Action|model|
        spl_autoload_register(array($this,'load'));

        //set_error_handler() 函数设置用户自定义的错误处理函数。
        set_error_handler(array($this,'AppError'));
        //set_exception_handler — 设置用户自定义的异常处理函数
        set_exception_handler(array($this,'AppException'));

        if(isset($_SERVER['REQUEST_URI'])){
            $url = $_SERVER['REQUEST_URI'];
            $_arr=explode('/',$url);
            $action = ucfirst($_arr[2]).'Action';

            if($url=='/'){
                $action = 'IndexAction';
            }
        }


        //客户端运行1
        if(isset($_REQUEST['mod'])){
            $action = ucfirst($_REQUEST['mod']).'Action';
        }

        $actionObj = new $action;//UserAction
        $objClass = isset($_arr[3])?$_arr[3]:'index';
        //客户端运行2
        if(isset($_REQUEST['action'])){
            $objClass=isset($_REQUEST['action'])?$_REQUEST['action']:'index';
        }

        $actionObj->call($actionObj,$objClass);


    }

    //加载类
    private function load($className){
        $data = self::core_file();
        if(isset($data[$className])){
            $path = $data[$className];
        }elseif (strpos($className,'Util')!=false){
            $_str = str_replace('Util','',$className);
            $_str = ucfirst($_str);
            $path =  APP_PATH."application/".Module."/App/Util/include/{$_str}.util.php";
        }elseif (strpos($className,'Action')!=false){
            $_str = str_replace('Action','',$className);
            $_str = ucfirst($_str);
            $path =  APP_PATH."application/".Module."/App/Action/{$_str}.action.php";
        }elseif (strpos($className,'Model')!=false){
            $_str = str_replace('Model','',$className);
            $_str = ucfirst($_str);
            $path =  APP_PATH."application/".Module."/App/Model/{$_str}.model.php";
        }else{
            throw new Exception("Class not found {$className}");
        }
        require $path;
    }


    //输出错误日志
    public function AppError($errno, $errstr, $errfile, $errline){
        // $errstr may need to be escaped:
        $errstr = htmlspecialchars($errstr);
        $errinfo = '';
        switch ($errno) {
            case E_USER_ERROR:
                $errinfo.=  "<b>My ERROR</b> [$errno] $errstr".PHP_EOL;
                $errinfo.=  "Fatal error on line $errline in file $errfile".PHP_EOL;
                $errinfo.=  ", PHP " . PHP_VERSION . " (" . PHP_OS . ")".PHP_EOL;
                $errinfo.=  "Aborting...".PHP_EOL;
                break;

            case E_USER_WARNING:
                $errinfo.=  "<b>My WARNING</b> [$errno] $errstr".PHP_EOL;
                $errinfo.=  "Warning on line $errline in file $errfile".PHP_EOL;
                break;

            case E_USER_NOTICE:
                $errinfo.=  "<b>My NOTICE</b> [$errno] $errstr".PHP_EOL;
                $errinfo.=  "Notice on line $errline in file $errfile".PHP_EOL;
                break;

            default:
                $errinfo.=  "Unknown error type: [$errno] $errstr".PHP_EOL;
                $errinfo.=  "Unknown error on line $errline in file $errfile".PHP_EOL;
                break;
        }

        $errinfo .="Request the address: {$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}".PHP_EOL;
        $errinfo .="The wrong time: ".date('Y-m-d H:i:s').PHP_EOL;
        $log = APP_PATH.'application/'.Module.'/Log/'.date('Ym').'/';
        if(!file_exists($log)){
            mkdir($log, 0777,true);
        }
        file_put_contents($log.date('d').'.log',$errinfo,FILE_APPEND);

        //debug是否开启错误显示到浏览器
        if(APP_DEBUG==true){
            die($errinfo);
        }

        switch ($errno) {
            case E_USER_ERROR:
                exit(1);
        }
        /* Don't execute PHP internal error handler */

        return true;
    }

    //输出异常
    public function AppException($exception){
        $errinfo = "Code: " . $exception->getCode() ." Message: ". $exception->getMessage().PHP_EOL;
        $errinfo.= $exception->__toString().PHP_EOL;

        $log = APP_PATH.'application/'.Module.'/Log/'.date('Ym').'/';
        if(!file_exists($log)){
            mkdir($log, 0777,true);
        }
        file_put_contents($log.date('d').'_exception.log',$errinfo,FILE_APPEND);
        //debug是否开启错误显示到浏览器
        if(APP_DEBUG==true){
            die($errinfo);
        }
        return true;
    }

    //初始化配置文件
    private function init_config(){
        $path = APP_PATH.'application/'.Module.'/Config/config.php';
        if(!file_exists($path)){
            die('The configuration file does not exist');
        }
        require $path;
        if(isset($config['mysql'])){
            extract($config['mysql']);
            define('MYSQL_HOST',$host);
            define('MYSQL_DB',$dbname);
            define('MYSQL_USER',$mysql_user);
            define('MYSQL_PWD',$mysql_pwd);
        }

        if(isset($config['mem'])){
            extract($config['mem']);
            define('MEM_HOST',$host);
            define('MEM_PORT',$port);
        }

        if(isset($config['redis'])){
            extract($config['redis']);
            define('REDIS_HOST',$host);
            define('REDIS_PORT',$port);
        }
    }


    public static function core_file(){
        $_arr = array(
            'Action'=>Lib.'/core/Action.class.php',
            'ActionMiddleware'=>APP_PATH."application/".Module."/App/Util/ActionMiddleware.php",
            'Input'=>Lib.'/core/Input.class.php',
            'QDbPdo'=> Lib.'/core/QDbPdo.class.php',
            'Model'=>Lib.'/core/Model.class.php',
            'MmCache'=>Lib.'/core/MmCache.class.php',
            'QRedis'=>Lib.'/core/QRedis.class.php'
        );
        return $_arr;
    }
}
