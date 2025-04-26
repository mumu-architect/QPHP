<?php
namespace admin\Model;

use QPHP\core\model\Model;
class IndexModel extends Model
{
    protected string $table = 'mm_user';//数据表
    protected string $key = 'id';//主键
    protected string $dbType = 'mysql';//数据库类型

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 单机数据库mysql事务测试
     * @return bool
     */
    public function getTransactionData(): bool
    {
        echo 66;
        return true;
        //return false;
    }
}