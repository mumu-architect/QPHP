<?php

namespace QPHP\core\pdo\oracle;

use Exception;
use PDO;
use PDOException;
use QPHP\core\pdo\intf\IPdoConn;

class QDbPdoOracleConn implements IPdoConn
{
    //数据库类型
    private string $dbType = 'oracle';

    /**
     * +----------------------------------------------------------
     * 打开数据库连接
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @throws Exception
     */
    public function connect($ORACLE_HOST, $ORACLE_PORT, $ORACLE_DB, $ORACLE_USER, $ORACLE_PWD): PDO
    {
        $tns = "
(DESCRIPTION =
    (ADDRESS_LIST =
          (ADDRESS = (PROTOCOL = TCP)(HOST = {$ORACLE_HOST})(PORT = {$ORACLE_PORT})))
          (CONNECT_DATA =(SERVICE_NAME = ORCL)
     )
)";
        $db = "oci:dbname=";//连接字符串
        $connectId = new PDO($db . $tns . ';charset=UTF8', $ORACLE_USER, $ORACLE_PWD, array(PDO::ATTR_PERSISTENT => TRUE));// 注意，这一个必须写
        $connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
        //$connectId->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);//
        //$dsn = $username = $password = $encode = null;

        return $connectId;
    }
}
