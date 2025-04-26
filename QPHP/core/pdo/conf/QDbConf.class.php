<?php
namespace QPHP\core\pdo\conf;

class QDbConf
{
    public static $QDBPdoArrayConf=array(
        'mysql'=>'QPHP\core\pdo\mysql\QDbPdoMysqlPool',
        'oracle'=>'QPHP\core\pdo\oracle\QDbPdoOraclePool'
    );


}
