<?php
namespace QPHP\core\pdo\intf;

use PDO;

interface IPdoPool
{
    static public function Connect($dbKey='mysql_0',$dbType="mysql"):PDO;
}
