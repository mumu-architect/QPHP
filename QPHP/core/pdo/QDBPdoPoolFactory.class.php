<?php

namespace QPHP\core\pdo;

use QPHP\core\pdo\conf\QDBConf;
use QPHP\core\pdo\mysql\QDBPdoMysqlPool as QDBPdoMysqlPool;
use QPHP\core\pdo\oracle\QDBPdoOraclePool as QDBPdoOraclePool;
use Exception;

/**
 * 数据库连接池工厂
 * Class QDBPdoPoolFactory
 * @package QPHP\core\pdo
 */
class QDBPdoPoolFactory
{
    public static $QDBPdoArrayConf=null;
      //  array('mysql'=>'QPHP\core\pdo\mysql\QDBPdoMysqlPool','oracle'=>'QDBPdoOraclePool\QDBPdoOraclePool');

    public static function init(){
        self::$QDBPdoArrayConf=QDBConf::$QDBPdoArrayConf;
    }

    /**
     * 后期动态扩展类
     * @param array $QDBPdoArray
     */
    public static function setAddQDBPdoArray(string $key,string $value): void
    {
        self::init();
        self::$QDBPdoArrayConf[$key] = $value;
    }

    /**
     * 获取不同数据库连接池类名
     * @param $dbType
     * @return mixed
     * @throws Exception
     */
    public static function getQDBPdoPool($dbType){
        self::init();
        try{
            return self::$QDBPdoArrayConf[$dbType];
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
     public static function Connect($dbKey,$dbType){
         return self::getQDBPdoPool($dbType)::Connect($dbKey,$dbType);
     }

}
