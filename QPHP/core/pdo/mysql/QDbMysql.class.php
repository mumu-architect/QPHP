<?php
namespace QPHP\core\pdo\mysql;

use PDO;
use QPHP\core\pdo\abs\QDbPdo;

class QDbMysql extends QDbPdo
{
    //数据库类型没有实际意义只是标识
    protected string $dbType = 'mysql';

    //数据库连接标识
    private mixed $dbKey =  'mysql_0';


    private string $qdb_pdopool = "QPHP\core\pdo\QDbPdoPoolFactory";//连接池类

    /**
     * @throws \Exception
     */
    public function __construct($dbKey='mysql_0')
    {
        parent::__construct();
        $this->dbKey = $dbKey;

        //链接数据库
        $this->connect();
    }

    /**
    +----------------------------------------------------------
     * 打开数据库连接
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    protected function connect():void
    {
        if($this->connectId == null) {
            $this->connectId =$this->getConnect($this->qdb_pdopool, 'connect',$this->dbKey,$this->dbType);
        }
    }

    /**
     * +----------------------------------------------------------
     * 添加数据(辅助方法)
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param $sql
     * @return bool
     * +----------------------------------------------------------
     * @throws \Exception
     */
    public function insert($sql):int
    {
        return $this->query($sql);
    }

    /**
     * 插入多条数据
     * @param $sql
     * @return bool
     * @throws \Exception
     */
    public function insertAll($sql):int
    {
        return $this->query($sql);
    }

    /**
     * +----------------------------------------------------------
     * 更新数据(辅助方法)
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $table 表名
     * +----------------------------------------------------------
     * @param array $arr 更新的数据(键值对)
     * +----------------------------------------------------------
     * @param mixed $where 条件
     * +----------------------------------------------------------
     * @return bool
     * +----------------------------------------------------------
     * @throws \Exception
     */
    public function update($sql):int
    {
        return $this->query($sql);
    }

    /**
     * +----------------------------------------------------------
     * 删除数据(辅助方法)
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $table 表名
     * +----------------------------------------------------------
     * @param mixed $where 条件
     * +----------------------------------------------------------
     * @return bool
     * +----------------------------------------------------------
     * @throws \Exception
     */
    public function delete($sql):int
    {
        return $this->query($sql);
    }

}
