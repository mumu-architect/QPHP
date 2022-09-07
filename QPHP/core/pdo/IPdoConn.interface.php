<?php
namespace QPHP\core\pdo;

interface IPdoConn
{
    public function connect($HOST,$PORT,$DB,$USER,$PWD);
}
