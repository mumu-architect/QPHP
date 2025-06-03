<?php

namespace admin\Model;

use QPHP\core\model\Model;

/**
 * Class OracleModel
 */
class OracleModel extends Model
{
    protected string $table = 'mm_user';//数据表
    protected string $key = 'id';//主键
    protected string $dbType = 'oracle';//数据库类型

    /**
     * 获取oracle下的数据
     * @return array
     */
    public function getOracleUsers(): array
    {
        //TODO:打印sql
        $this->getLastSql();//打印sql
        $data = $this->Db('oracle_0')->table('"mm_user"')->asTable('u')
            ->field('u.*,ui."birthday",ui."info",a."address_info",a."is_default"')
            ->leftJoin('"mm_user_info"  ui on ui."user_id" = u."id"')
            ->leftJoin('"mm_address"  a on a."user_id" =u."id"')
            ->where()
            ->order('u."id" desc')
            ->limit(0, 15)
            ->select();
        return $data;
    }

    public function insertOracleUsers(): int
    {


        $arr = array(

            'id' => 15,
            'username' => "mumu_oracle",
            'age' => 36,
            'address' => "西安",
            'pwd' => "123456"
        );
        //TODO:打印sql
        $this->getLastSql();//打印sql
        return $this->Db('oracle_0')->table('"mm_user"')->insert($arr);
    }

    public function insertAllOracleUsers(): int
    {
        $arr = [
            [

                'id' => 30,
                'username' => "mumu_oracle1",
                'age' => 36,
                'address' => "西安",
                'pwd' => "123456"
            ],
            [
                'id' => 31,
                'username' => "mumu_oracle2",
                'age' => 37,
                'address' => "西安",
                'pwd' => "123456"
            ],
            [
                'id' => 32,
                'username' => "mumu_oracle2",
                'age' => 37,
                'address' => "西安",
                'pwd' => "123456"
            ],
        ];
        $this->getLastSql();//打印sql
        return $this->Db('oracle_0')->table('"mm_user"')->insertAll($arr);
    }
    public function updateOracleUser(): int
    {

        $arr = array(

            'id' => 15,
            'username' => "mumu_oracle5",
            'age' => 36,
            'address' => "西安5",
            'pwd' => "123456"
        );
        $this->getLastSql();//打印sql
        return $this->Db('oracle_0')->table('"mm_user"')->where('"id"=15')->update($arr);
    }

    public function deleteOracleUser(): int
    {
        $this->getLastSql();//打印sql
        return $this->Db('oracle_0')->table('"mm_user"')->where('"id"=15')->delete();
    }


    /**
     * 单机数据库oracle事务测试
     * @return bool
     */
    public function getTransactionData(): bool
    {
        echo 88;
        $this->Db('oracle_0')->startTrans();
        $sql1="insert into \"mm_user\" (\"id\",\"username\",\"age\",\"address\",\"pwd\") values (41,'mumuww5',18,'西安','123456')";
        //$this->getLastSql();
        $res_1=  $this->Db('oracle_0')->executeSql("insert",$sql1);
        var_dump($res_1);
        if(!$res_1){
            echo 222;
            $this->Db('oracle_0')->rollback();
            return false;
        }
        $sql2="insert into \"mm_user_info\" (\"id\",\"user_id\",\"birthday\",\"name\",\"info\") values (41,2014,599068800,'mumuww5','wangwei')";
        $res_2 = $this->Db('oracle_0')->executeSql("insert",$sql2);
        var_dump($res_2);
        if(!$res_2) {
            $this->Db('oracle_0')->rollback();
            echo 333;
            return false;
        }
        $this->Db('oracle_0')->commit();
        echo 444;
        return true;
    }
}