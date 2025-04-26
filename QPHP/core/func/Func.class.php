<?php
namespace QPHP\core\func;

use Exception;

class Func
{
    private static $ins=null;

    public static function instance(): ?Func
    {
        if(!self::$ins||!(self::$ins instanceof self)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    private function __construct()
    {
    }
    /**
     * 加载文件和目录下文件
     * @param array $conf
     * @throws Exception
     */
    protected function requireFileDir(array $conf=[]){
        foreach ($conf as $k=>$v){
            if(file_exists($v)){
                if(is_file($v)){
                    require_once $v;
                }else if (is_dir($v)){
                    $this->requireDir($v);
                }
                clearstatcache();
            }else{
                throw new Exception("The configuration file [{$v}] does not exist");
            }
        }
    }


    /**
     * 加载目录下所有文件
     * @param $dir
     */
    private function requireDir($dir):void
    {
        $handle = opendir($dir);//打开文件夹
        while (false !== ($file = readdir($handle))) {//读取文件
            if ($file != '.' && $file != '..') {
                $filepath = $dir . '/' . $file;//文件路径

                if (filetype($filepath) == 'dir') {//如果是文件夹
                    $this->requireDir($filepath);//继续读
                } else {
                    if(is_file($filepath)){
                        require_once ($filepath);//引入文件
                    }
                    clearstatcache();
                }
            }
        }
    }

    /**
     * 获取当前毫秒
     * @return float
     */
    function getMillisecond(): float
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }


    function  transByte(int $byte):array
    {
        $KB = 1024;
        $MB = 1024 * $KB;
        $GB = 1024 * $MB;
        $TB = 1024 * $GB;
        $fileSize=array();
        if ($byte < $KB) {
            $fileSize['size']=$byte;
            $fileSize['unit']="B";
        } elseif ($byte < $MB) {
            $fileSize['size']=round($byte / $KB, 2);
            $fileSize['unit']="B";
        } elseif ($byte < $GB) {
            $fileSize['size']=round($byte / $MB, 2);
            $fileSize['unit']="MB";
        } elseif ($byte < $TB) {
            $fileSize['size']=round($byte / $GB, 2) ;
            $fileSize['unit']="GB";
        } else {
            $fileSize['size']=round($byte / $TB, 2);
            $fileSize['unit']="TB";
        }
        return $fileSize;

    }

    /**
     * 生成分布式事务id
     * @return string
     */
    public static function xId(string $prefix='xid_'): string
    {
        // 生成全局事务 ID
        return str_replace('.','',uniqid($prefix,true));
    }


    public function __call($method, $parameters)
    {
        return $this->$method(...$parameters);
    }

    public static function __callStatic($method, $parameters)
    {
        return (self::instance())->$method(...$parameters);
    }
}
