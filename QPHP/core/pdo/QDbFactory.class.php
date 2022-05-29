<?php


class QDbFactory
{
static public $qdb=null;
static public function getDb($dbType='mysql')
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
