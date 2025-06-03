<?php

namespace QPHP\core\model\oracle;

use Exception;
use QPHP\core\model\abs\BaseModel;
use QPHP\core\model\mysql\MysqlM;

class OracleM extends BaseModel
{

    //当前类名
    private static $currentClass = 'oracle';

    public function __construct($table, $key)
    {
        $this->table = $table;
        $this->key = $key;
        parent::__construct('oracle');
    }


    /**
     * 判断是否是当前类
     * @param $dbType
     * @return bool
     */
    public static function isCurrentClass($dbType): bool
    {
        if (self::$currentClass === $dbType) {
            return true;
        }
        return false;

    }

    /**
     * 工厂产出对象
     * @param $dbType
     * @param $table
     * @param $key
     * @return OracleM
     * @throws Exception
     */
    public static function newClass($dbType, $table, $key): OracleM
    {
        if (self::isCurrentClass($dbType)) {
            return new self($table, $key);
        } else {
            throw new Exception("Model type error");
        }

    }

    /**
     * 数据表名称
     * @param $table
     * @return $this
     */
    public function table($table):BaseModel
    {
        //表名
        $this->table=$table;
        return $this;
    }
    public function asTable($asTable):BaseModel
    {
        $this->asTable=$asTable;
        return $this;
    }

    public function field($field):BaseModel
    {
        if(!empty($field)){
            $this->field=$field;
        }
        return $this;
    }

    public function leftJoin($join):BaseModel
    {
        if(!empty($join)) {
            $this->join[]=" left join {$join} ";
        }
        return $this;
    }
    public function rightJoin($join):BaseModel
    {
        if(!empty($join)) {
            $this->join[]=" right join {$join} ";
        }
        return $this;
    }

    public function innerJoin($join):BaseModel
    {
        if(!empty($join)) {
            $this->join[]=" inner join {$join} ";
        }
        return $this;
    }
    public function fullOutterJoin($join):BaseModel
    {
        if(!empty($join)) {
            $this->join[]=" full outter join {$join} ";
        }
        return $this;
    }


    public function where(string $where=''):BaseModel
    {
        if(empty($where)){
            $this->where= ' where 1=1 ';
        }
        else{
            $this->where= ' where 1=1 AND '.$where;
        }

        return $this;
    }

    public function order(string $order=''):BaseModel
    {
        if(empty($order)){
            $this->order= '  ';
        }
        else{
            $this->order= ' order by '.$order;
        }
        return $this;
    }

    public function limit($num = 0, $len = 10): OracleM
    {
        $end_num = $len + $num;
        $this->limit = " where \"rn\"<={$end_num} and \"rn\" >={$num} ";
        return $this;
    }

    /**
     * 查询一条
     * @return array
     */
    public function find(): array
    {
        $join = '';
        if (!empty($this->join)) {
            foreach ($this->join as $v) {
                $join .= " {$v} ";
            }
        }
        $this->sql = "select {$this->field} from {$this->table}  {$this->asTable} {$join} {$this->where}";
        $sel_find = $this->executeSql("getRow", $this->sql);
        //初始化
        $this->free();
        return $sel_find;
    }

    /**
     * 查询多条
     * @return array
     */
    public function select(): array
    {
        $join = '';
        if (!empty($this->join)) {
            foreach ($this->join as $v) {
                $join .= " {$v} ";
            }
        }
        $this->sql = "select * from (select ROWNUM \"rn\",{$this->field} from {$this->table} {$this->asTable} {$join} {$this->where}{$this->order}){$this->limit}";
        $sel = $this->executeSql("getRows", $this->sql);
        //初始化
        $this->free();
        return $sel;
    }

    /**
     * 查询总条数
     * @return
     */
    public function count(): array
    {
        $join = '';
        if (!empty($this->join)) {
            foreach ($this->join as $v) {
                $join .= " {$v} ";
            }
        }
        $this->sql = "select count(*) \"qphp_count\" from (select {$this->field} from {$this->table} {$this->asTable} {$join}  {$this->where}) qphp_table";
        $sel_count = $this->executeSql("getRow", $this->sql);
        //初始化
        $this->free();
        return $sel_count;
    }

    public function findAll(): array
    {
        $this->sql = "select * from \"{$this->table}\"";
        $sel_all = $this->executeSql("getRows", $this->sql);
        //初始化
        $this->free();
        return $sel_all;
    }


    /**
     * +----------------------------------------------------------
     * 添加数据(辅助方法)
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $table 表名
     * +----------------------------------------------------------
     * @param array $arr 插入的数据(键值对)
     * +----------------------------------------------------------
     * @return mixed
     * +----------------------------------------------------------
     */
    public function insert(array $arr = array()): int
    {
        $field = $value = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $k => $v) {
                $v = preg_replace("/'/", "\\'", $v);
                $field .= "\"$k\",";
                $value .= "'$v',";
            }
            $field = preg_replace("/,$/", "", $field);
            $value = preg_replace("/,$/", "", $value);
            $sql = "INSERT INTO {$this->table} ($field) VALUES ($value)";
            $this->sql = $sql;
            $add = $this->executeSql("insert", $this->sql);
            //初始化
            $this->free();
            return $add;//影响行数
        }
        return 0;
    }

    /**
     * 插入多条数据
     * @param $table
     * @param array $arr
     * @return bool
     */
    public function insertAll(array $arr = array()): int
    {
        $allSql = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $key => $arr_val) {
                $field = '';
                $value='';
                foreach ($arr_val as $k => $v) {
                    $v = preg_replace("/'/", "\\'", $v);
                    $field .= "\"$k\",";
                    $value .= "'$v',";
                }
                $value = preg_replace("/,$/", "", $value);
                $field = preg_replace("/,$/", "", $field);
                $allSql .= " INTO {$this->table} ($field) VALUES ({$value}) ";
            }
            $allSql = preg_replace("/,$/", "", $allSql);
            $this->sql = "INSERT ALL {$allSql} SELECT * FROM dual";
            $add_all = $this->executeSql("insertAll", $this->sql);
            //初始化
            $this->free();
            return $add_all;
        }
        return 0;
    }


    /**
     * +----------------------------------------------------------
     * 更新数据(辅助方法)
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $table 表名
     * +----------------------------------------------------------
     * @param array $arr 更新的数据(键值对)
     * +----------------------------------------------------------
     * @param mixed $where 条件
     * +----------------------------------------------------------
     * @return mixed
     * +----------------------------------------------------------
     */
    public function update(array $arr = array()): int
    {
        $field = "";
        $loop = 1;
        $len = count($arr);
        $sql = "UPDATE {$this->table} SET ";
        foreach ($arr as $k => $v) {
            $v = preg_replace("/'/", "\\'", $v);
            $field .= "\"" . $k . "\"" . "='" . $v . "',";
        }
        $sql .= trim($field, ',');
        if (!empty($this->where)) {
            $sql .= ' ' . $this->where;
        } else {
            return false;
        }
        $this->sql = $sql;
        $edit = $this->executeSql("update", $this->sql);
        //初始化
        $this->free();
        return $edit;
    }

    /**
     * +----------------------------------------------------------
     * 删除数据(辅助方法)
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $table 表名
     * +----------------------------------------------------------
     * @param mixed $where 条件
     * +----------------------------------------------------------
     * @return mixed
     * +----------------------------------------------------------
     */
    public function delete(): int
    {
        $sql = "delete from {$this->table} ";

        if (!empty($this->where)) {
            $sql .= ' ' . $this->where;
            $this->sql = $sql;
        }

        $del = $this->executeSql("delete", $this->sql);
        //初始化
        $this->free();
        return $del;

    }

    /**
     * 开启事物(辅助方法)
     * @return array
     */
    public function startTrans()
    {
        return $this->execute("startTrans");
    }

    /**
     * 事物提交(辅助方法)
     * @return array
     */
    public function commit()
    {
        return $this->execute("commit");
    }

    /**
     * 事物回滚(辅助方法)
     * @return array
     */
    public function rollback()
    {
        return $this->execute("rollback");
    }
}
