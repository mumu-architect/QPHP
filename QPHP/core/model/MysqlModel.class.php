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
    public function findOne(){
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
        $this->sql = "select {$this->field} from {$this->table} {$this->asTable} {$join}  {$this->where}  {$this->limit};";
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




    //======================以下不完整============

    public function find($id){
        $this->sql  = "select * from {$this->table} where {$this->key}={$id}";
        return $this->db->getRow($this->sql );
    }

    public function findAll(){
        $this->sql  = "select * from {$this->table}";
        return $this->db->getRows($this->sql );
    }


    public function key($key){
        $this->key=$key;
        return $this;
    }

    public function add($arr){
        $add = $this->db->insert($this->table,$arr);
        if($add){
            return $this->db->getLastInsertId();//添加的id
        }
        return 0;
    }

    public function edit($arr,$where){
        return $this->db->update($this->table,$arr,$where);
    }


    public function del($where){
        return $this->db->delete($this->table,$where);
    }
}
