<?php
namespace QPHP\core\pdo;

class QDbPdoPool implements IPdoPool
{
    private static $instance=array();
    private static $num =1;//此数设为1,t=吞吐量最大
    private static $total_num=200;
    //防止外部创建新的数据库连接类
    private function _constuct(){}

    static public function Connect($dbKey='mysql_0',$dbType="mysql")
    {
        if(count(self::$instance)>self::$total_num)
        {
            throw new \Exception("Too many connections");
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
            self::ConDB($dbType);
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
            self::ConDB($dbType);
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

    static private function ConDB($dbType)
    {
        if ($dbType === 'mysql') {
            $mysql_conn = new QDbPdoMysqlConn();

            foreach (MYSQL_POOL as $key => $val) {
                    //$MYSQL_HOST,$MYSQL_PORT,$MYSQL_DB,$MYSQL_USER,$MYSQL_PWD
                    //连接类不够100，创建新类
                while (true) {
                    if (!isset(self::$instance[$key]) || count(self::$instance[$key]) < self::$num) {
                        self::$instance[$key][] = $mysql_conn->connect($val['MYSQL_HOST'], $val['MYSQL_PORT'], $val['MYSQL_DB'], $val['MYSQL_USER'], $val['MYSQL_PWD']);
                    } else {
                        foreach (self::$instance[$key] as &$v){
                            if(!$v){
                                self::$instance[$key][] = $mysql_conn->connect($val['MYSQL_HOST'], $val['MYSQL_PORT'], $val['MYSQL_DB'], $val['MYSQL_USER'], $val['MYSQL_PWD']);
                            }
                        }
                        break;
                    }
                }
            }
        } elseif ($dbType === 'oracle') {
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
        }else{
            throw new \Exception("An unsupported database type");
        }
    }

}
