<?php
namespace QPHP\core\pdo\intf;

interface IPdoPool
{
    static public function Connect($dbKey='mysql_0',$dbType="mysql");
}
