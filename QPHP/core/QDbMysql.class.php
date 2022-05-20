<?php


class QDbMysql extends QDbPdo
{
    //数据库类型
    public $dbType = 'mysql';
    /**
    +----------------------------------------------------------
     * 打开数据库连接
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    protected function connect() {
        if($this->connectId == null){
            $this->connectId = new PDO("mysql:host=".MYSQL_HOST.":".MYSQL_PORT.";dbname=".MYSQL_DB."", MYSQL_USER, MYSQL_PWD);
            $this->connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
            if ($this->dbType == 'mysql'){
                $this->connectId->exec("set names utf8");
            }
            $dsn = $username = $password = $encode = null;
            if ($this->connectId == null) {
                throw new Exception("PDO CONNECT ERROR");
            }
        }
    }

}
