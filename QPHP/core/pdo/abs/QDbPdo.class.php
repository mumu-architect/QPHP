<?php
namespace QPHP\core\pdo\abs;

use Exception;
use QPHP\core\pdo\intf\IPdo;
use PDO;


/**
 * QPHP Framework 通用数据库访问接口
 */
abstract class QDbPdo implements IPdo{

    //数据库类型
    protected  string $dbType = '';
    //连接数据库配置文件
    protected $configFile = null;
    //当前连接ID
    protected $connectId = null;
    //操作所影响的行数
    protected $affectedRows = 0;
    //查询结果对象
    protected $PDOStatement = null;

    /**
     * +----------------------------------------------------------
     * 类的构造子
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @throws Exception
     */

    public function __construct() {
        if (!class_exists('PDO')) {
            throw new Exception('Not Support : PDO');
        }
    }

    /**
    +----------------------------------------------------------
     * 类的析构方法(负责资源的清理工作)
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    public function __destruct()
    {
        $this->close();
        $this->dbType = '';
        $this->configFile = null;
        $this->connectId = null;
        $this->PDOStatement = null;
    }

    /**
    +----------------------------------------------------------
     * 打开数据库连接
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    abstract protected function connect():void;
    /*
    protected function connect() {
//        if($this->connectId == null){
//            $this->connectId = new PDO("mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB."", MYSQL_USER, MYSQL_PWD);
//            $this->connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
//            if ($this->dbType == 'mysql'){
//                $this->connectId->exec("set names utf8");
//            }
//            $dsn = $username = $password = $encode = null;
//            if ($this->connectId == null) {
//                throw new Exception("PDO CONNECT ERROR");
//            }
//        }
    }
*/

    /**
     * 获取链接
     * @param $className
     * @param $method
     * @param $dbKey
     * @param $dbType
     * @return PDO
     */
    public function getConnect($className,$method,$dbKey,$dbType): PDO
    {
        return call_user_func_array([$className, $method], [$dbKey,$dbType]);
    }

    /**
    +----------------------------------------------------------
     * 关闭数据库连接
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    public function close():void
    {
        $this->connectId = null;
    }

    /**
    +----------------------------------------------------------
     * 释放查询结果
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    protected function free():void
    {
        $this->PDOStatement = null;
    }

    /**
     * +----------------------------------------------------------
     * 执行语句 针对 INSERT, UPDATE 以及DELETE
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $sql sql指令
     * +----------------------------------------------------------
     * @return boolean
     * +----------------------------------------------------------
     * @throws Exception
     */
    public function query(string $sql):int
    {
        if ($this->connectId == null){
            return 0;
        }
        $this->affectedRows = $this->connectId->exec($sql);
        return $this->affectedRows;
    }

    /**
    +----------------------------------------------------------
     * 返回操作所影响的行数(INSERT、UPDATE 或 DELETE)
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     * @return integer
    +----------------------------------------------------------
     */
    public function getAffected():int
    {
        if ($this->connectId == null){
            return 0;
        }
        return $this->affectedRows;
    }

    /**
     * +----------------------------------------------------------
     * 获得一条查询记录
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $sql SQL指令
     * +----------------------------------------------------------
     * @return array
     * +----------------------------------------------------------
     * @throws Exception
     */
    public function getRow($sql):array
    {
        if($this->connectId == null){
            throw new Exception("connect id [connectId] is null");
        }
        $result = array();   //返回数据集
        $this->PDOStatement = $this->connectId->prepare($sql);
        $this->PDOStatement->execute();

        if (empty($this->PDOStatement)) {
            return $result;
        }

        $result = $this->PDOStatement->fetch(constant('PDO::FETCH_ASSOC'));
        $this->free();

        return $result;
    }

    /**
     * +----------------------------------------------------------
     * 获得多条查询记录
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $sql SQL指令
     * +----------------------------------------------------------
     * @return array
     * +----------------------------------------------------------
     * @throws Exception
     */
    public function getRows($sql):array
    {

        if($this->connectId == null){
            throw new Exception("connect id [connectId] is null");
        }
        $result = array();   //返回数据集
        $this->PDOStatement = $this->connectId->prepare($sql);
        $this->PDOStatement->execute();

        if (empty($this->PDOStatement)) {
            return $result;
        }

        $result = $this->PDOStatement->fetchAll(constant('PDO::FETCH_ASSOC'));
        $this->free();
        return $result;
    }

    /**
    +----------------------------------------------------------
     * 获得最后一次插入的id
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     * @return int
    +----------------------------------------------------------
     */
    abstract public function getLastInsertId():int;

    /**
    +----------------------------------------------------------
     * 返回最后一次使用 INSERT 指令的 ID
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     * @return integer
    +----------------------------------------------------------
     */
    abstract public function getLastInsId():int;


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
    abstract public function insert($sql):int;

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
    abstract public function update($sql):int;

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
    abstract public function delete($sql):int;


    /**
     * 开启事物(辅助方法)
     * @return void
     * @throws Exception
     */
    abstract public function startTrans(): bool;


    /**
     * 事物提交(辅助方法)
     * @return bool
     * @throws Exception
     */
    abstract public function commit(): bool;


    /**
     * 事物回滚(辅助方法)
     * @return bool
     * @throws Exception
     */
    abstract public function rollback(): bool;

}
