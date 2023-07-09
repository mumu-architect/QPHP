<?php
namespace QPHP\core\pdo\intf;

interface IPdoConn
{
    public function connect($HOST,$PORT,$DB,$USER,$PWD);
}
