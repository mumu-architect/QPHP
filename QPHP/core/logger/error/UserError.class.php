<?php
namespace QPHP\core\logger\error;


use QPHP\core\logger\abs\Logger;
use QPHP\core\logger\intf\IUserError;

class UserError extends Logger implements IUserError
{

	public function printError($MODULE,$errno, $errStr, $errFile, $errLine): bool
    {
	// $errstr may need to be escaped:
        $errStr = htmlspecialchars($errStr);
        $errInfo = '';
        switch ($errno) {
            case E_USER_ERROR:
                $errInfo.=  "<b>My ERROR</b> [$errno] $errStr".PHP_EOL;
                $errInfo.=  "Fatal error on line $errLine in file $errFile".PHP_EOL;
                $errInfo.=  ", PHP " . PHP_VERSION . " (" . PHP_OS . ")".PHP_EOL;
                $errInfo.=  "Aborting...".PHP_EOL;
                break;

            case E_USER_WARNING:
                $errInfo.=  "<b>My WARNING</b> [$errno] $errStr".PHP_EOL;
                $errInfo.=  "Warning on line $errLine in file $errFile".PHP_EOL;
                break;

            case E_USER_NOTICE:
                $errInfo.=  "<b>My NOTICE</b> [$errno] $errStr".PHP_EOL;
                $errInfo.=  "Notice on line $errLine in file $errFile".PHP_EOL;
                break;

            default:
                $errInfo.=  "Unknown error type: [$errno] $errStr".PHP_EOL;
                $errInfo.=  "Unknown error on line $errLine in file $errFile".PHP_EOL;
                break;
        }

        $errInfo .="Request the address: {$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}".PHP_EOL;
        $errInfo .="The wrong time: ".date('Y-m-d H:i:s').PHP_EOL;
        $log = APP_PATH.'log/'.date('Ym').'/'.date('md').'/'.date('H').'/';
        if(!file_exists($log)){
            mkdir($log, 0777,true);
        }
        //file_put_contents($log.date('Hi').'.log',$errInfo,FILE_APPEND);
        $this->writeLog($log.date('Hi').'.log',$errInfo);

        //debug是否开启错误显示到浏览器
        if(APP_DEBUG){
            print("<pre style='background-color: aquamarine'>");
            print_r($errInfo);
            print ("</pre>");
            die();
        }

        if ($errno == E_USER_ERROR) {
            exit(1);
        }
        /* Don't execute PHP internal error handler */
        return true;
	}

}
