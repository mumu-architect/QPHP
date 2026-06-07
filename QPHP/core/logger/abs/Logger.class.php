<?php

namespace QPHP\core\logger\abs;

class Logger
{
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
    protected function writeLog(string $fileName,string $content):void
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
    protected function isWritable(string $fileName): bool
    {
        $fileSizeByte = filesize($fileName);
        if($fileSizeByte < 20*1024*1024){
            return true;
        }else{
            return false;
        }

    }
}