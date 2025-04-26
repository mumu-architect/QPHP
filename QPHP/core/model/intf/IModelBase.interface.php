<?php
namespace QPHP\core\model\intf;

interface IModelBase
{
    /**
     * 释放链式操作数据
     * @return $this
     */
    public function free():void;
    /**判断是否是当前类
     * @param $dbType
     * @return mixed
     */
    public static function isCurrentClass($dbType):bool;

    /**
     * 工厂产生对象
     * @param $dbType
     * @param $table
     * @param $key
     * @return mixed
     */
    public static function newClass($dbType,$table,$key):IModelBase;
}
