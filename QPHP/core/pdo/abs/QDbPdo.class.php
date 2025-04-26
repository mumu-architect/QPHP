<?php
namespace QPHP\core\pdo\abs;

use Exception;
use QPHP\core\pdo\intf\IPdo;
use PDO;

/**
 * +------------------------------------------------------------------------------
 * QPHP Framework 通用数据库访问接口
* +------------------------------------------------------------------------------
 * @date    2018-8
 * @author  mumu <1211884772@qq.com>
 * @version 1.0
* +------------------------------------------------------------------------------
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
            throw new \Exception('Not Support : PDO');
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
     * @return \PDO
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
        if($this->connectId == null){
            throw new Exception("connect id [connectId] is null");
        }
        $this->affectedRows = $this->connectId->exec($sql);

        //var_dump($this->affectedRows );

        return max($this->affectedRows, 0);
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
    public function getLastInsertId():int
    {
        if ($this->connectId != null) {
            return $this->connectId->lastInsertId();
        }
        return 0;
    }

    /**
    +----------------------------------------------------------
     * 返回最后一次使用 INSERT 指令的 ID
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     * @return integer
    +----------------------------------------------------------
     */
    public function getLastInsId():int
    {
        if ($this->connectId != null) {
            return $this->connectId->lastInsertId();
        }
        return 0;
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
     * 开启分布式事务
     * @param $XID
     * @return bool
     * @throws Exception
     */

    public function xaStartTrans($XID): bool
    {
//        $result = $this->xaCommit($XID);
//        if (!$result) {
//            $this->error('开启分布式事务失败!');
//        }
        $this->connectId->exec('SET AUTOCOMMIT=0');
        $this->connectId->exec("XA START '{$XID}'"); // 开始 XA 事务
        return true;
    }


    /**
     * 分布式事务准备
     * @param $XID
     * @return bool
     * @throws Exception
     */
    public function xaPrepare($XID): bool
    {
        $this->connectId->exec("XA END '{$XID}'");
        $this->connectId->exec("XA PREPARE '{$XID}'");

        $this->connectId->exec('SET AUTOCOMMIT=1');
        return true;
    }

    /**
     * 分布式事务提交
     * @param $XID
     * @return bool
     * @throws Exception
     */
    public function xaCommit($XID): bool
    {
        $this->connectId->exec("XA COMMIT '{$XID}'");//提交事务
        $this->connectId->exec('SET AUTOCOMMIT=1');
        return true;
    }

    /**
     * 分布式事务回滚
     * @param $XID
     * @return bool
     * @throws Exception
     */
    public function xaRollback($XID): bool
    {
        $this->connectId->exec("XA ROLLBACK '{$XID}'");

        $this->connectId->exec('SET AUTOCOMMIT=1');
        return true;
    }

    /**
     * 开启事物(辅助方法)
     * @return void
     * @throws Exception
     */
    public function startTrans(): bool
    {
//        $result = $this->commit();
//        if (!$result) {
//            echo 888;
//            $this->error();
//        }
        $this->query('SET AUTOCOMMIT=0');
        $this->query('START TRANSACTION'); //开启事务
        return true;
    }

    /**
     * 事物提交(辅助方法)
     * @return bool
     * @throws Exception
     */
    public function commit(): bool
    {
        $result = $this->query('COMMIT');//提交事务
        if (!$result) {
            return false;
        }
        $this->query('SET AUTOCOMMIT=1');
        return true;
    }

    /**
     * 事物回滚(辅助方法)
     * @return bool
     * @throws Exception
     */
    public function rollback(): bool
    {
        $result = $this->query('ROLLBACK');
        if (!$result)
            return false;
        $this->query('SET AUTOCOMMIT=1');
        return true;
    }

    /**
     * @throws Exception
     */
    private function error(string $str="开启事务失败!")
    {
        throw new Exception($str);
    }

}
