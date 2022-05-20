<?php


class QDbFactory
{

public function getDb($dbType='mysql')
{
    if(!empty($dbType)) {
        if($dbType==='mysql'){
            return new QDbMysql();
        }elseif ($dbType==='oracle') {
            return new QDbOracle();
        }
    }
    return null;
}

}
