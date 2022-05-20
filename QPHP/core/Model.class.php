<?php

/**
 * Class Model类
 */
class Model extends QDbPdo
{
    public $table='';//数据表
    public $key='';//主键


    //
    private $where='';
    private $join=array();
    private $sql ='';
    private $asTable='';//表别名
    private $field= ' * ';

    public function field($field){
        if(!empty($field)){
            $this->field=$field;
        }
        return $this;
    }

    public function table($table){
        $this->table=$table;
        return $this;
    }
    public function asTable($asTable){
        $this->asTable=$asTable;
        return $this;
    }
    public function leftJoin($join){
        if(!empty($join)) {
            $this->join[]=" left join {$join} ";
        }
        return $this;
    }
    public function rightJoin($join){
        if(!empty($join)) {
            $this->join[]=" right join {$join} ";
        }
        return $this;
    }

    public function innerJoin($join){
        if(!empty($join)) {
            $this->join[]=" inner join {$join} ";
        }
        return $this;
    }
    public function fullOutterJoin($join){
        if(!empty($join)) {
            $this->join[]=" full outter join {$join} ";
        }
        return $this;
    }


    public function where($where){
        $this->where=$where;
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
        $this->sql = "select {$this->field} from {$this->table} {$this->asTable} {$join} where {$this->where}";

        return $this->getRow($this->sql);
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
        $this->sql = "select {$this->field} from {$this->table} {$this->asTable} {$join} where {$this->where}";

        return $this->getRows($this->sql);
    }
    //获取最近一条sql
    public function getLastSql()
    {
        echo $this->sql;
    }


    //======================以下不完整============

    public function find($id){
        $sql = "select * from {$this->table} where {$this->key}={$id}";
        return $this->getRow($sql);
    }

    public function findAll(){
        $sql = "select * from {$this->table}";
        return $this->getRows($sql);
    }


    public function key($key){
        $this->key=$key;
        return $this;
    }

    public function add($arr){
        $add = $this->insert($this->table,$arr);
        if($add){
            return $this->getLastInsertId();//添加的id
        }
        return 0;
    }

    public function edit($arr,$where){
        return $this->update($this->table,$arr,$where);
    }


    public function del($where){
        return $this->delete($this->table,$where);
    }
}
