<?php

/**
 * Class Model类
 */
class Model extends QDbPdo
{
    public $table='';//数据表
    public $key='';//主键
    public function find($id){
        $sql = "select * from {$this->table} where {$this->key}={$id}";
        return $this->getRow($sql);
    }

    public function findAll(){
        $sql = "select * from {$this->table}";
        return $this->getRows($sql);
    }

    public function table($table){
        $this->table=$table;
        return $this;
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
