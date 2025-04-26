<?php
namespace QPHP\core\model\intf;

interface IModel
{

    /**
     * 数据库名
     * @param $database
     * @return $this
     */
    public function Db($database);

    /**
     * 数据表名称
     * @param $table
     * @return $this
     */
    public function table($table);

    /**
     * 表别名
     * @param $asTable
     * @return mixed
     */
    public function asTable($asTable);

    /**
     * 表字段
     * @param $field
     * @return mixed
     */
    public function field($field);

    /**
     * 左连接
     * @param $join
     * @return mixed
     */
    public function leftJoin($join);

    /**
     * 右链接
     * @param $join
     * @return mixed
     */
    public function rightJoin($join);

    /**
     * 内连接
     * @param $join
     * @return mixed
     */
    public function innerJoin($join);

    /**
     * 外连接
     * @param $join
     * @return mixed
     */
    public function fullOutterJoin($join);

    /**
     * where条件
     * @param string $where
     * @return mixed
     */
    public function where(string $where='');

    /**
     * 排序
     * @param string $order
     * @return mixed
     */
    public function order(string $order='');

    /**
     * 获取最近一条sql
     * @return mixed
     */
    public function getLastSql();

    /**
     * 查询指定条数
     * $num = 0 起始行号
     * $len =10 长度
     * @return array
     */
     public function limit($num=0,$len=10);
    /**
     * 查询一条
     * @return array
     */
     public function find();

    /**
     * 查询多条
     * @return array
     */
     public function select();

    /**
     * 查询总条数
     * @return array
     */
    public function count();

    /**
     * 插入一条数据
     * @param array $arr
     * @return mixed
     */
     public function insert(array $arr = array());

    /**
     * 插入多条条数据
     * @param array $arr
     * @return mixed
     */
     public function insertAll(array $arr = array());

    /**
     * 修改数据
     * @param array $arr
     * @return mixed
     */
     public function update(array $arr = array());

    /**
     * 删除数据
     * @return mixed
     */
     public function delete();

    /**
     * 执行sql
     * @param $method
     * @param $sql
     * @return array
     */
    public function executeSql($method,$sql);

    /**
     * 查询表全部数据
     * @return mixed
     */
    public function findAll();


}
