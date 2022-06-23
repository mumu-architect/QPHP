<?php


class QDbPdoPool
{
    private static $instance=array();
    private static $num =10;
    private static $total_num=200;
    //防止外部创建新的数据库连接类
    private function _constuct(){}

    static public function Connect($dbType="mysql",$dbKey='mysql_0')
    {
        $total_num =0;
        $key_num=[];
        foreach (self::$instance as $key=>$val){
            $num = count(self::$instance[$key]);
            $key_num[$key]=$num;
            $total_num+=$num;
        }
        if($total_num>self::$total_num){
            //随机数保证数据库连接均衡
            $i=rand(0,$key_num[$dbKey]-1);
            return self::$instance[$dbKey][$i];
        }

        //连接类不够200，创建新类10个
        if(!isset(self::$instance[$dbKey])||count(self::$instance[$dbKey])<self::$num)
        {
            self::ConDB($dbType);
        }
        else
        {
            var_dump(self::$instance);
            //随机数保证数据库连接均衡
            $i=rand(0,$key_num[$dbKey]-1);
            return self::$instance[$dbKey][$i];
        }




    }

    static private function ConDB($dbType)
    {
        if ($dbType === 'mysql') {
            foreach (MYSQL_POOL as $key => $val) {
                //$MYSQL_HOST,$MYSQL_PORT,$MYSQL_DB,$MYSQL_USER,$MYSQL_PWD
                //连接类不够100，创建新类
                if(!isset(self::$instance[$key])||count(self::$instance[$key])<self::$num) {
                    self::$instance[$key][] = new QDbMysql($val['MYSQL_HOST'], $val['MYSQL_PORT'], $val['MYSQL_DB'], $val['MYSQL_USER'], $val['MYSQL_PWD']);
                }
            }
        } elseif ($dbType === 'oracle') {
            foreach (ORACLE_POOL as $key => $val) {
                //$ORACLE_HOST,$ORACLE_PORT,$ORACLE_DB,$ORACLE_USER,$ORACLE_PWD
                if(!isset(self::$instance[$key])||count(self::$instance[$key])<self::$num) {
                    self::$instance[$key][] = new QDbOracle($val['ORACLE_HOST'], $val['$ORACLE_PORT'], $val['ORACLE_DB'], $val['ORACLE_USER'], $val['ORACLE_PWD']);
                }
            }
        }
    }

}
