<?php


class MysqlModel extends BaseModel
{
    public function __construct($table,$key)
    {
        $this->table=$table;
        $this->key=$key;
        parent::__construct('mysql');
    }
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->$name=$value;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->$name;
    }

    public function limit($num=0,$len=10){
        $this->limit = ' limit '.$num.','.$len.' ';
        return $this;
    }
    /**
     * 查询一条
     * @return array
     */
    public function find(){
        $join = '';
        if(!empty($this->join)){
            foreach ($this->join as $v){
                $join .= " {$v} ";
            }
        }
        $this->sql = "select {$this->field} from {$this->table}  {$this->asTable} {$join} {$this->where}";
        return $this->executeSql("getRow");
    }

    /**
     * 查询多条
     * @return array
     */
    public function select(){
        $join = '';
        if(!empty($this->join)){
            foreach ($this->join as $v){
                $join .= " {$v} ";
            }
        }
        $this->sql = "select {$this->field} from {$this->table} {$this->asTable} {$join}  {$this->where} {$this->order} {$this->limit};";
        return $this->executeSql("getRows");
    }

    /**
     * 查询总条数
     * @return
     */
     public function count(){
         $join = '';
         if(!empty($this->join)){
             foreach ($this->join as $v){
                 $join .= " {$v} ";
             }
         }
         $this->sql = "select count(*) qphp_count from (select {$this->field} from {$this->table} {$this->asTable} {$join}  {$this->where}) AS qphp_table;";
         return $this->executeSql("getRow");
     }

    public function findAll(){
        $this->sql  = "select * from {$this->table}";
        return $this->executeSql("getRows");
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
    public function insert($arr = array()) {
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
            $add=  $this->executeSql("insert");
            if($add){
                return $this->db->getLastInsertId();//添加的id
            }
            return 0;
        }
    }

    /**
     * 插入多条数据
     * @param $table
     * @param array $arr
     * @return bool
     */
    public function insertAll( $data_arr = array()) {
        $field = $value = "";
        if (!empty($data_arr) && is_array($data_arr)) {
            foreach ($data_arr as $key => $arr_val){
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
            return $this->executeSql("insertAll");
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
    public function update($arr = array()) {
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
        return $this->executeSql("update");
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
    public function delete() {
        $sql = "delete from {$this->table} ";
        if (!empty($this->where)) {
            if(!empty($this->where)){
                $sql .= ' '.$this->where;
            }else{
                return false;
            }
            $this->sql=$sql;
            return $this->executeSql("delete");
        }
    }
}
