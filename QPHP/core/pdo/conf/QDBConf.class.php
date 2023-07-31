<?php
namespace QPHP\core\pdo\conf;

class QDBConf
{
    public static $QDBPdoArrayConf=array(
        'mysql'=>'QPHP\core\pdo\mysql\QDBPdoMysqlPool',
        'oracle'=>'QPHP\core\pdo\oracle\QDBPdoOraclePool');


}
