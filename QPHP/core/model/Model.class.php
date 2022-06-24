<?php

/**
 * Class Model类
 */
class Model
{
    public $table='';//数据表
    public $key='';//主键

    protected $dbType='mysql';
    protected $model=null;
    //protected $dbKey='mysql_0';
    public function __construct()
    {
        if(!empty($this->dbType)) {
            if($this->dbType==='mysql'){
                $this->model= new MysqlModel($this->table,$this->key);
            }elseif ($this->dbType==='oracle') {
                $this->model= new OracleModel($this->table,$this->key);
            }
        }
        return null;
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

}
