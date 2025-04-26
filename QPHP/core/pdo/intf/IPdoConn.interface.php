<?php
namespace QPHP\core\pdo\intf;

use PDO;

interface IPdoConn
{
    public function connect($HOST,$PORT,$DB,$USER,$PWD):PDO;
}
