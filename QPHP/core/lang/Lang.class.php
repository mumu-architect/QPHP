<?php
namespace QPHP\core\lang;


class Lang
{
    private static $langArray=[];
    private static $ins=null;

    /**
     * 单例模式
     * @return Lang|null
     */
    public static function instance(){
        if(!self::$ins||!(self::$ins instanceof self)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    private function __construct()
    {
    }

    /**
     * 动态调用方法
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->$method(...$parameters);
    }

    /**
     * 静态调用方法
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (self::instance())->$method(...$parameters);
    }

    /**
     * 获取语言文件
     * @param string $lang  cn|en
     * @param string $module Admin|Index
     * @return mixed
     */
    protected static function getLang(string $lang="cn", string $module=null){
        $module=self::isModuleNull($module);
        self::$langArray = require_once APP_PATH.'application/'.$module.'/Lang/'.$lang.'/lang.php';
    }

    /**
     * 获取语言key得值
     * @param string $langNameKey
     * @param string $lang
     * @param null $module
     * @return string
     */
    protected function lang(string $langNameKey="name"){
        return isset(self::$langArray[$langNameKey])?self::$langArray[$langNameKey]:'';
    }

    /**
     * 判断$MODULE=null,赋值QPHP
     * @param $MODULE
     * @return string
     */
    private static function isModuleNull(string $MODULE){
        if($MODULE==null){
            $MODULE="QPHP";
        }
        return $MODULE;
    }
}

