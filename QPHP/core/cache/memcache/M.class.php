<?php


namespace QPHP\core\cache\memcache;


class M
{
    private static $ins=null;

    /**
     * 单例
     * @return R|null
     */
    public static function instance(){
        if(is_null(self::$ins)||!(self::$ins instanceof R)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 实例化Qredis
     * @return QRedis
     */
    private function getMem(){
        $mem = new MmCache(MEM_POOL["mem_0"]["MEM_HOST"],MEM_POOL["mem_0"]["MEM_PORT"]);
        return $mem;
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
