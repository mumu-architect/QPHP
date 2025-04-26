<?php
namespace QPHP\core\pdo\oracle;

use QPHP\core\pdo\abs\QDbPdo;

class QDbOracle extends QDbPdo
{
    //数据库类型没有实际意义只是标识
    protected string $dbType = 'oracle';
    //数据库连接标识
    private $dbKey = 'oracle_0';
    private $qdb_pdopool =  "QPHP\core\pdo\QDbPdoPoolFactory";//连接池类
    public function __construct($dbKey)
    {
        try {
            parent::__construct();
        } catch (\Exception $e) {
            throw new \Exception("QDbOracle [parent::__construct] error");
        }
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
    protected function connect() {
        if($this->connectId == null) {
            //TODO: 此处不符合，迪米特法则
            //陌生的类QDbPdoPool最好不要以局部变量的形式出现在类的内部
            $this->connectId =$this->getConnect($this->qdb_pdopool, 'Connect',$this->dbKey,$this->dbType);
        }
    }


    /**
     * +----------------------------------------------------------
     * 添加数据(辅助方法)
     * +----------------------------------------------------------
     * @access public
     * +----------------------------------------------------------
     * @param string $table 表名
     * +----------------------------------------------------------
     * @param array $arr 插入的数据(键值对)
     * +----------------------------------------------------------
     * @return bool
     * +----------------------------------------------------------
     * @throws \Exception
     */
    public function insert($sql): bool
    {
        return $this->query($sql);
    }

    /**
     * 插入多条数据
     * @param $table
     * @param array $arr
     * @return bool
     */
    public function insertAll($sql) {
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
    public function update($sql): bool
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
    public function delete($sql): bool
    {
        return $this->query($sql);
    }


}
