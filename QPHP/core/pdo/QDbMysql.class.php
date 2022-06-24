<?php


class QDbMysql extends QDbPdo
{
    //数据库类型
    public $dbType = 'mysql';

    public $dbKey = 'mysql_0';
    public function __construct($dbKey)
    {
        $this->dbKey = $dbKey;
    }

    /**
    +----------------------------------------------------------
     * 打开数据库连接
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    protected function connect() {
//        if($this->connectId == null){
//            $MYSQL_HOST = MYSQL_POOL['mysql_0']['MYSQL_HOST'];
//            $MYSQL_PORT = MYSQL_POOL['mysql_0']['MYSQL_PORT'];
//            $MYSQL_DB = MYSQL_POOL['mysql_0']['MYSQL_DB'];
//            $MYSQL_USER = MYSQL_POOL['mysql_0']['MYSQL_USER'];
//            $MYSQL_PWD = MYSQL_POOL['mysql_0']['MYSQL_PWD'];
//            $this->connectId = new PDO("mysql:host=".$MYSQL_HOST.":".$MYSQL_PORT.";dbname=".$MYSQL_DB."", $MYSQL_USER, $MYSQL_PWD);
//            $this->connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
//            if ($this->dbType == 'mysql'){
//                $this->connectId->exec("set names utf8");
//            }
//            $dsn = $username = $password = $encode = null;
//            if ($this->connectId == null) {
//                throw new Exception("PDO CONNECT ERROR");
//            }
//        }

        if($this->connectId == null) {
            $this->connectId =QDbPdoPool::Connect($this->dbKey,"mysql");
        }
    }



    /**
    +----------------------------------------------------------
     * 添加数据(辅助方法)
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     * @param string  $table  表名
    +----------------------------------------------------------
     * @param array   $arr    插入的数据(键值对)
    +----------------------------------------------------------
     * @return mixed
    +----------------------------------------------------------
     */
    public function insert($sql) {
        return $this->query($sql);
    }

    /**
     * 插入多条数据
     * @param $table
     * @param array $arr
     * @return bool
     */
    public function insertAll($sql) {
        return $this->query($sql);
    }
    /**
    +----------------------------------------------------------
     * 更新数据(辅助方法)
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     * @param string  $table  表名
    +----------------------------------------------------------
     * @param array   $arr    更新的数据(键值对)
    +----------------------------------------------------------
     * @param mixed   $where  条件
    +----------------------------------------------------------
     * @return mixed
    +----------------------------------------------------------
     */
    public function update($sql) {
        return $this->query($sql);
    }

    /**
    +----------------------------------------------------------
     * 删除数据(辅助方法)
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     * @param string  $table  表名
    +----------------------------------------------------------
     * @param mixed   $where  条件
    +----------------------------------------------------------
     * @return mixed
    +----------------------------------------------------------
     */
    public function delete($sql) {
        return $this->query($sql);
    }

}
