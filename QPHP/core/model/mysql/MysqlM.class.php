<?php
namespace QPHP\core\model\mysql;

use QPHP\core\model\abs\BaseModel;

class MysqlM extends BaseModel
{
    //当前类名
    private static $currentClass='mysql';

    public function __construct($table,$key)
    {
        parent::__construct('mysql');
        $this->table=$table;
        $this->key=$key;

    }


    /**
     * 判断是否是当前类
     * @param $dbType
     * @return bool
     */
    public static function isCurrentClass($dbType):bool
    {
        if(self::$currentClass===$dbType){
            return true;
        }
        return false;

    }

    /**
     * 工厂产出对象
     * @param $dbType
     * @param $table
     * @param $key
     * @return IModelBase
     */
    public static function newClass($dbType,$table,$key):MysqlM
    {
        if(self::isCurrentClass($dbType)){
            return new self($table,$key);
        }else{
            throw new Exception("Model type error");
        }
    }

    public function limit($num=0,$len=10):MysqlM
    {
        $this->limit = ' limit '.$num.','.$len.'';
        return $this;
    }
    /**
     * 查询一条
     * @return array
     */
    public function find():array
    {
        $join = '';
        if(!empty($this->join)){
            foreach ($this->join as $v){
                $join .= " {$v} ";
            }
        }
        $this->sql = "select {$this->field} from {$this->table}  {$this->asTable} {$join} {$this->where}";
        $sel_find =  $this->executeSql("getRow",$this->sql);
        //初始化
        $this->free();
        return $sel_find;
    }

    /**
     * 查询多条
     * @return array
     */
    public function select():array
    {
        $join = '';
        if(!empty($this->join)){
            foreach ($this->join as $v){
                $join .= " {$v} ";
            }
        }
        $this->sql = "select {$this->field} from {$this->table} {$this->asTable} {$join}  {$this->where} {$this->order} {$this->limit}";
        $sel =  $this->executeSql("getRows",$this->sql);
        //初始化
        $this->free();
        return $sel;
    }

    /**
     * 查询总条数
     * @return
     */
     public function count():array
     {
         $join = '';
         if(!empty($this->join)){
             foreach ($this->join as $v){
                 $join .= " {$v} ";
             }
         }
         $this->sql = "select count(*) qphp_count from (select {$this->field} from {$this->table} {$this->asTable} {$join}  {$this->where}) AS qphp_table";
         $sel_count =  $this->executeSql("getRow",$this->sql);
         //初始化
         $this->free();
         return $sel_count;
     }

    public function findAll():array
    {
        $this->sql  = "select * from {$this->table}";
        $sel_all= $this->executeSql("getRows",$this->sql);
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
     * @param string  $table  表名
    * +----------------------------------------------------------
     * @param array $arr 插入的数据(键值对)
    * +----------------------------------------------------------
     * @return mixed
    * +----------------------------------------------------------
     */
    public function insert(array $arr = array()):int
    {
        $field = $value = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $k => $v) {
                $v = preg_replace("/'/", "\\'", $v);
                $field .= "$k,";
                $value .= "'$v',";
            }
            $field = preg_replace("/,$/", "", $field);
            $value = preg_replace("/,$/", "", $value);
            $sql = "INSERT INTO {$this->table} ($field) VALUES($value)";
            $this->sql=$sql;
            $add=  $this->executeSql("insert",$this->sql);
            //初始化
            $this->free();
            if($add){
                return $this->db->getLastInsertId();//添加的id
            }
        }
        return 0;
    }

    /**
     * 插入多条数据
     * @param $table
     * @param array $arr
     * @return bool
     */
    public function insertAll(array $arr = array()):int
    {
        $field = $value = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $key => $arr_val){
                $field = '';
                foreach ($arr_val as $k => $v) {
                    $v = preg_replace("/'/", "\\'", $v);
                    $field .= "$k,";
                    $value .= "'$v',";
                }
                $value = preg_replace("/,$/", "", $value);
                $value .= "($value),";
            }

            $field = preg_replace("/,$/", "", $field);
            $value = preg_replace("/,$/", "", $value);
            $sql = "INSERT INTO {$this->table} ($field) VALUES $value";
            $this->sql=$sql;
            $add_all= $this->executeSql("insertAll",$this->sql);
            //初始化
            $this->free();
            return $add_all;
        }
        return false;
    }
    /**
    * +----------------------------------------------------------
     * 更新数据(辅助方法)
    * +----------------------------------------------------------
     * @access public
    * +----------------------------------------------------------
     * @param string  $table  表名
    * +----------------------------------------------------------
     * @param array $arr 更新的数据(键值对)
    * +----------------------------------------------------------
     * @param mixed   $where  条件
    * +----------------------------------------------------------
     * @return mixed
    * +----------------------------------------------------------
     */
    public function update(array $arr = array()):int
    {
        $field = "";
        $loop = 1;
        $len = count($arr);
        $sql = "UPDATE {$this->table} SET ";
        foreach ($arr as $k => $v) {
            $v = preg_replace("/'/", "\\'", $v);
            $field .= "".$k."" . "='" . $v . "',";
        }
        $sql .= trim($field, ',');
        if(!empty($this->where)){
            $sql .= ' '.$this->where;
        }else{
            return false;
        }
        $this->sql=$sql;
        $edit= $this->executeSql("update",$this->sql);
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
     * @param string  $table  表名
    * +----------------------------------------------------------
     * @param mixed   $where  条件
    * +----------------------------------------------------------
     * @return array
     * +----------------------------------------------------------
     */
    public function delete():int
    {
        $sql = "delete from {$this->table} ";

        if (!empty($this->where)) {
            $sql .= ' '.$this->where;

            $this->sql=$sql;
        }
        $del =  $this->executeSql("delete",$this->sql);
        //初始化
        $this->free();
        return $del;
    }


    /**
     * 开启事物(辅助方法)
     * @return array
     * @throws Exception
     */
    public function startTrans():bool
    {
        return $this->execute("startTrans");
    }

    /**
     * 事物提交(辅助方法)
     * @return array
     * @throws Exception
     */
    public function commit():bool
    {
        return $this->execute("commit");
    }

    /**
     * 事物回滚(辅助方法)
     * @return array
     * @throws Exception
     */
    public function rollback():bool
    {
        return $this->execute("rollback");
    }

    /**
     * 开启分布式事务(辅助方法)
     * @param $XID
     * @return array
     */
    public function xaStartTrans($XID):bool
    {
        return $this->execute("xaStartTrans",$XID);
    }

    /**
     * 分布式事务准备(辅助方法)
     * @param $XID
     * @return array
     */
    public function xaPrepare($XID):bool
    {
        return $this->execute("xaPrepare",$XID);
    }

    /**
     * 分布式事务提交(辅助方法)
     * @param $XID
     * @return array
     */
    public function xaCommit($XID):bool
    {
        return $this->execute("xaCommit",$XID);
    }

    /**
     * 分布式事务回滚(辅助方法)
     * @param $XID
     * @return array
     */
    public function xaRollback($XID):bool
    {
        return $this->execute("xaRollback",$XID);
    }
}
