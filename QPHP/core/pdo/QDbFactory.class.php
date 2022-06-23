<?php


class QDbFactory
{
static private $qdb=null;
static public function getDb($dbkey='mysql_0',$dbType='mysql')
{
    if(!empty($dbType)) {
        if($dbType==='mysql'){

            if(self::$qdb instanceof QDbMysql){
                return self::$qdb;
            }else{
                return new QDbMysql();
            }
        }elseif ($dbType==='oracle') {

            if(self::$qdb instanceof QDbOracle){
                return self::$qdb;
            }else{
                return new QDbOracle();
            }
        }
    }
    return null;
}

}
