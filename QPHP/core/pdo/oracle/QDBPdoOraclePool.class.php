<?php


namespace QPHP\core\pdo\oracle;


use Exception;
use QPHP\core\pdo\intf\IPdoPool;

class QDBPdoOraclePool implements IPdoPool
{
    private static $instance=array();
    private static $num =1;//此数设为1,t=吞吐量最大
    private static $total_num=200;
    //防止外部创建新的数据库连接类
    private function _constuct(){}

    static public function Connect($dbKey='oracle_0',$dbType="oracle")
    {
        if(count(self::$instance)>self::$total_num)
        {
            throw new Exception("Too many connections");
        }
        $total_num =0;
        if(isset(self::$instance[$dbKey])){
            foreach (self::$instance[$dbKey] as $key=>$val){
                if($val){
                    ++$total_num;
                }
            }
        }else{
            //连接类不够200，创建新类10个
            self::ConDB();
            foreach (self::$instance[$dbKey] as $key=>$val){
                if($val){
                    ++$total_num;
                }
            }
        }
        if($total_num>0){
            //随机数保证数据库连接均衡
            $i=rand(0,$total_num-1);
            return self::$instance[$dbKey][$i];
        }else{
            self::ConDB();
            foreach (self::$instance[$dbKey] as $key=>$val){
                if($val){
                    ++$total_num;
                }
            }
            //随机数保证数据库连接均衡
            $i=rand(0,$total_num-1);
            return self::$instance[$dbKey][$i];
        }
    }

    static private function ConDB()
    {
        self::instanceQDbPdoConn();
    }

    static private function instanceQDbPdoConn(){
        $oracle_conn = new QDbPdoOracleConn();
        foreach (ORACLE_POOL as $key => $val) {
            //$ORACLE_HOST,$ORACLE_PORT,$ORACLE_DB,$ORACLE_USER,$ORACLE_PWD
            while (true) {
                if (!isset(self::$instance[$key]) || count(self::$instance[$key]) < self::$num) {
                    self::$instance[$key][] = $oracle_conn->connect($val['ORACLE_HOST'], $val['ORACLE_PORT'], $val['ORACLE_DB'], $val['ORACLE_USER'], $val['ORACLE_PWD']);
                } else {
                    foreach (self::$instance[$key] as &$v){
                        if(!$v){
                            self::$instance[$key][] = $oracle_conn->connect($val['ORACLE_HOST'], $val['ORACLE_PORT'], $val['ORACLE_DB'], $val['ORACLE_USER'], $val['ORACLE_PWD']);
                        }
                    }
                    break;
                }
            }
        }
    }

}

