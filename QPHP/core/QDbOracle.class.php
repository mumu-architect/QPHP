<?php


class QDbOracle extends QDbPdo
{
    //数据库类型
    public $dbType = 'oracle ';
    /**
    +----------------------------------------------------------
     * 打开数据库连接
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    protected function connect() {
        if($this->connectId == null){
            $this->connectId = new PDO("oci:host=".ORACLE_HOST.":".ORACLE_PORT.";dbname=".ORACLE_DB."", ORACLE_USER, ORACLE_PWD);
            $this->connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
            if ($this->dbType == 'oracle'){
                $this->connectId->exec("set names utf8");
            }
            $dsn = $username = $password = $encode = null;
            if ($this->connectId == null) {
                throw new Exception("PDO CONNECT ERROR");
            }
        }
    }

}
