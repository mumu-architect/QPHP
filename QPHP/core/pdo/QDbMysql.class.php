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
    public function insert($table, $arr = array()) {
        $field = $value = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $k => $v) {
                $v = preg_replace("/'/", "\\'", $v);
                $field .= "$k,";
                $value .= "'$v',";
            }
            $field = preg_replace("/,$/", "", $field);
            $value = preg_replace("/,$/", "", $value);
            $sql = "INSERT INTO $table ($field) VALUES($value)";
            return $this->query($sql);
        }
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
    public function update($table, $arr = array(), $where = '') {
        $field = "";
        $loop = 1;
        $len = count($arr);
        $sql = "UPDATE {$table} SET ";
        foreach ($arr as $k => $v) {
            $v = preg_replace("/'/", "\\'", $v);
            $field .= "".$k."" . "='" . $v . "',";
        }
        $sql .= trim($field, ',');
        if(!empty($where)){
            $sql .= ' '.$where;
        }else{
            return false;
        }
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
    public function delete($table, $where = '') {
        $sql = "delete from {$table} ";
        if (!empty($where)) {
            if(!empty($where)){
                $sql .= ' '.$where;
            }else{
                return false;
            }
            return $this->query($sql);
        }
    }

}
