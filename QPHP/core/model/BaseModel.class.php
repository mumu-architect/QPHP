<?php


abstract class BaseModel
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
    protected $limit = '';
    protected $echo_sql=false;
    public function __construct($dbType='mysql')
    {
        $this->db = QDbFactory::getDb($dbType);
    }

    public function field($field){
        if(!empty($field)){
            $this->field=$field;
        }
        return $this;
    }

    public function table($table){
        $this->join = array();
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


    public function where($where=''){
        if(empty($where)){
            $this->where= ' where 1=1 ';
        }
        else{
            $this->where= ' where 1=1 AND '.$where;
        }

        return $this;
    }
    //获取最近一条sql
    public function getLastSql()
    {
        $this->echo_sql = true;
    }

    /**
     * 查询指定条数
     * $num = 0 起始行号
     * $len =10 长度
     * @return array
     */
    abstract public function limit($num=0,$len=10);
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

    /**
     * 查询总条数
     * @return array
     */
    abstract public function count();

    /**
     * 执行sql
     * @return array
     */
    public function executeSql($execute_fun){
        if($this->echo_sql){
            echo $this->sql;
        }
        return $this->db->$execute_fun($this->sql);
    }



}
