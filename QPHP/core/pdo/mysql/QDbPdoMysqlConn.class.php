<?php
namespace QPHP\core\pdo\mysql;

use Exception;
use PDO;
use QPHP\core\pdo\intf\IPdoConn;

class QDbPdoMysqlConn implements IPdoConn
{
    //数据库类型
    private $dbType = 'mysql';

    /**
     * +----------------------------------------------------------
     * 打开数据库连接
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @throws Exception
     */
    public function connect($MYSQL_HOST,$MYSQL_PORT,$MYSQL_DB,$MYSQL_USER,$MYSQL_PWD):PDO
    {
        $connectId = new PDO("mysql:host=".$MYSQL_HOST.":".$MYSQL_PORT.";dbname=".$MYSQL_DB, $MYSQL_USER, $MYSQL_PWD);
        $connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
        if ($this->dbType == 'mysql'){
            $stmt=$connectId->query("SHOW VARIABLES LIKE 'character_set_connection'");
            $charset=$stmt->fetch(PDO::FETCH_ASSOC);
            $connectId->exec("set names {$charset['Value']}");
        }
        return $connectId;
    }
}
