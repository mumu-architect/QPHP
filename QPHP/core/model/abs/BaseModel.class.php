<?php
namespace QPHP\core\model\abs;


use QPHP\core\model\intf\IModel;
use QPHP\core\model\intf\IModelBase;

abstract class BaseModel implements IModel,IModelBase
{
    protected $db =null;
    public $table='';//数据表
    public $key='';//主键


    //链式操作sql
    protected $database='';//数据库
    protected $where='';
    protected $join=array();
    protected $sql ='';
    protected $asTable='';//表别名
    protected $field= ' * ';
    protected $order= '';
    protected $limit = '';
    protected $echo_sql=false;

    protected $dbType = '';

    private $qdb_factory = 'QPHP\core\pdo\QDbFactory';

    public function __construct($dbType='mysql')
    {
        $this->dbType=$dbType;
    }




    public function __destruct()
    {
        $this->db =null;
        $this->table =null;
        $this->key =null;
        //初始化
        $this->free();
    }



    /**
     * 释放链式操作数据
     * @return mixed
     */
    public function free():void
    {
        //初始化
        $this->where='';
        $this->join=array();
        $this->sql ='';
        $this->asTable='';//表别名
        $this->field= ' * ';
        $this->order= '';
        $this->limit = '';
        $this->table='';
        $this->key='';
        $this->database='';
    }

    /**
     * 数据表名称
     * @param $database
     * @return $this|IModel
     * @throws Exception
     */
    public function Db($database):BaseModel
    {
        //初始化
        $this->free();
        //表名
        $this->database=$database;
        //TODO: 此处不符合，迪米特法则
        //陌生的类QDbPdoPool最好不要以局部变量的形式出现在类的内部

        $this->db = $this->qdb_factory::getDb($this->database,$this->dbType);

        return $this;
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
    //获取最近一条sql
    public function getLastSql(): void
    {
        $this->echo_sql = true;
    }

    /**
     * 查询指定条数
     * $num = 0 起始行号
     * $len =10 长度
     * @return array
     */
    abstract public function limit($num=0,$len=10):BaseModel;
    /**
     * 查询一条
     * @return array
     */
    abstract public function find(): array;

    /**
     * 查询多条
     * @return array
     */
    abstract public function select(): array;

    /**
     * 查询总条数
     * @return array
     */
    abstract public function count(): array;

    /**
     * 插入一条数据
     * @param array $arr
     * @return mixed
     */
    abstract public function insert(array $arr = array()):int;

    /**
     * 插入多条条数据
     * @param array $arr
     * @return mixed
     */
    abstract public function insertAll(array $arr = array()):int;

    /**
     * 修改数据
     * @param array $arr
     * @return mixed
     */
    abstract public function update(array $arr = array()):int;

    /**
     * 删除数据
     * @return mixed
     */
    abstract public function delete():int;


    abstract public function findAll():array;
    /**
     * 执行sql
     * @return array
     */
    public function executeSql($method,$sql):mixed
    {
        if($this->echo_sql){
            echo $sql.';';
        }
        //return $this->db->$method($sql.';');
        return call_user_func_array([$this->db,$method], [$sql]);
    }

    /**
     * 执行参数方法
     * @param $method
     * @param ...$args
     * @return array
     */
    public function execute($method,...$args):mixed
    {

        $numArgs = count($args);
        if($numArgs==0){
            //return $this->db->$method();
            return call_user_func_array([$this->db,$method], []);
        }elseif ($numArgs==1){
            //return $this->db->$method($args[0]);
            return call_user_func_array([$this->db,$method], [$args[0]]);
        }elseif ($numArgs==2){
            return call_user_func_array([$this->db,$method], [$args[0],$args[1]]);
        }elseif ($numArgs==3){
            return call_user_func_array([$this->db,$method], [$args[0],$args[1],$args[2]]);
        }
        return call_user_func_array([$this->db,$method], []);
    }

}
