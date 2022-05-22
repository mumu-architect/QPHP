<?php


abstract class BaseModel extends QDbFactory
{
    public $db =null;
    public $table='';//数据表
    public $key='';//主键


    //链式操作sql
    protected $where='';
    protected $join=array();
    protected $sql ='';
    protected $asTable='';//表别名
    protected $field= ' * ';
    public function __construct($dbType='mysql')
    {
        $this->db = $this->getDb($dbType);
    }

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
    //获取最近一条sql
    public function getLastSql()
    {
        echo $this->sql;
    }
    /**
     * 查询一条
     * @return array
     */
    abstract public function findOne();

    /**
     * 查询多条
     * @return array
     */
    abstract public function select();



}
