<?php
namespace QPHP\core\logger\exception;

use QPHP\core\logger\abs\Logger;
use QPHP\core\logger\intf\IExceptionError;

class ExceptionError extends Logger implements IExceptionError
{
    //输出异常
    public function printException($MODULE,$exception): void
    {
        $errInfo = "Code: " . $exception->getCode() ." Message: ". $exception->getMessage().PHP_EOL;
        $errInfo.= $exception->__toString().PHP_EOL;
        $log = APP_PATH.'log/'.date('Ym').'/'.date('md').'/'.date('H').'/';
        if(!file_exists($log)){
            mkdir($log, 0777,true);
        }
        //file_put_contents($log.date('Hi').'_exception.log',$errInfo,FILE_APPEND);
        $this->writeLog($log.date('Hi').'.log',$errInfo);
        //debug是否开启错误显示到浏览器
        if(APP_DEBUG){
            print("<pre style='background-color:powderblue'>");
            print_r($errInfo);
            print ("</pre>");
            die();
        }
    }

}

