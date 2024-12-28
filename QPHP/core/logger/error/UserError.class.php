<?php
namespace QPHP\core\error;


class UserError implements IUserError
{

	public function printError($MODULE,$errno, $errStr, $errFile, $errLine){
        $MODULE=$this->isModuleNull($MODULE);
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
        $log = APP_PATH.'application/'.$MODULE.'/Log/'.date('Ym').'/'.date('md').'/'.date('H').'/';
        if(!file_exists($log)){
            mkdir($log, 0777,true);
        }
        //file_put_contents($log.date('Hi').'.log',$errInfo,FILE_APPEND);
        $this->writeLog($log.date('Hi').'.log',$errInfo);

        //debug是否开启错误显示到浏览器
        if(APP_DEBUG==true){
            print("<pre style='background-color: aquamarine'>");
            print_r($errInfo);
            print ("</pre>");
            die();
        }

        switch ($errno) {
            case E_USER_ERROR:
                exit(1);
        }
        /* Don't execute PHP internal error handler */
        return true;
	}

    /**
     * 判断$MODULE=null,赋值QPHP
     * @param $MODULE
     * @return string
     */
    private function isModuleNull($MODULE): string
    {
        if($MODULE==null){
            $MODULE="QPHP";
        }
        return $MODULE;
    }

    /**
     * TODO:未完成
     * 写满新建文件
     * @param string $path
     * @param string $errInfo
     * @return void
     */
    private function createLogFile(string $path,string $errInfo): void
    {
        $isWritable= $this->isWritable($path.date('Hi').'.log');
        if($isWritable){
            $this->writeLog($path.date('Hi').'.log',$errInfo);
        }
    }

    /**
     * 写日志
     * @param String $fileName
     * @param String $content
     * @return void
     */
    private function writeLog(string $fileName,string $content):void
    {
        if($fp = fopen($fileName, 'a')) {
            $startTime = microtime();
            do {
                $canWrite = flock($fp, LOCK_EX);
                if(!$canWrite) usleep(round(rand(0, 100)*1000));
            } while ((!$canWrite)&&((microtime()-$startTime) < 1000));
            if ($canWrite) {
                fwrite($fp, $content);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }

    }

    /**
     * 判断文件大小
     * @param string $fileName
     * @return bool
     */
    private function isWritable(string $fileName): bool
    {
        $fileSizeByte = filesize($fileName);
        if($fileSizeByte < 20*1024*1024){
            return true;
        }else{
            return false;
        }

    }

}
