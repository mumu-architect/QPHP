<?php

namespace QPHP\core\pdo;

use PDO;
use QPHP\core\pdo\conf\QDbConf;
use Exception;
use QPHP\core\pdo\mysql\QDbPdoMysqlPool;
use QPHP\core\pdo\oracle\QDbPdoOraclePool;

/**
 * 数据库连接池工厂
 * Class QDBPdoPoolFactory
 * @package QPHP\core\pdo
 */
class QDbPdoPoolFactory
{
    private ?array $QDBPdoArrayConf=null;
      //  array('mysql'=>'QPHP\core\pdo\mysql\QDBPdoMysqlPool','oracle'=>'QPHP\core\pdo\oracle\QDBPdoOraclePool');

    private static ?QDbPdoPoolFactory $instance =null;

    private function __construct()
    {
        $this->QDBPdoArrayConf=QDbConf::$QDBPdoArrayConf;
    }

    public static function getInstance():QDbPdoPoolFactory
    {
        if(is_null(self::$instance)||!(self::$instance instanceof QDbPdoPoolFactory)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 后期动态扩展类
     * @param array $QDBPdoArray
     */
    public static function setAddQDBPdoArray(string $key,string $value):void
    {
        $obj = self::getInstance();
        $obj->QDBPdoArrayConf[$key] = $value;
    }

    /**
     * 获取不同数据库连接池类名
     * @param $dbType
     * @return mixed
     * @throws Exception
     */
    public static function getQDBPdoPool($dbType):string
    {
        $obj = self::getInstance();
        try{
            return $obj->QDBPdoArrayConf[$dbType];
        }catch (Exception $e){
            throw new Exception("An unsupported database type");
        }
    }

    /**
     * 工厂链接不同数据库池
     * @param $dbKey
     * @param $dbType
     * @throws Exception
     */
     public static function connect($dbKey,$dbType):PDO
     {
         return self::getQDBPdoPool($dbType)::connect($dbKey,$dbType);
     }

}
