<?php


class QDbOracle extends QDbPdo
{
    //数据库类型
    public $dbType = 'oracle';
    /**
    +----------------------------------------------------------
     * 打开数据库连接
    +----------------------------------------------------------
     * @access public
    +----------------------------------------------------------
     */
    protected function connect() {
/*
//echo phpinfo();
try {
    $conn=null;
    $tns = "
(DESCRIPTION =
    (ADDRESS_LIST =
          (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.123.101)(PORT = 1521)))
          (CONNECT_DATA =(SERVICE_NAME = ORCL)
     )
)";
    $db      = "oci:dbname=";//连接字符串
    $username = "QPHP"; //这是数据库用户名
    $password = "123456"; //这是数据库连接密码
	//$conn = oci_connect($username, $password, '192.168.123.101/ORCL');
    $conn = new PDO($db.$tns.';charset=UTF8',$username,$password,array(PDO::ATTR_PERSISTENT => TRUE));// 注意，这一个必须写
	//$conn = new PDO("oci:dbname=QPHP;host=192.168.123.101:1521/OCRL",$username,$password,array(PDO::ATTR_PERSISTENT => TRUE));



    $sth = $conn->prepare('SELECT * from "mm_user" ');
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    var_dump($result);
} catch(PDOException $e){
    echo ($e->getMessage());

}

*/

                    if(null == $this->connectId){
                        try {
                            $tns = "
(DESCRIPTION =
    (ADDRESS_LIST =
          (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.123.101)(PORT = 1521)))
          (CONNECT_DATA =(SERVICE_NAME = ORCL)
     )
)";


                            $db      = "oci:dbname=";//连接字符串
                            $this->connectId = new PDO($db.$tns.';charset=UTF8',ORACLE_USER,ORACLE_PWD,array(PDO::ATTR_PERSISTENT => TRUE));// 注意，这一个必须写
                            $this->connectId->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //打开PDO错误提示
                            //$dsn = $username = $password = $encode = null;
                            if ($this->connectId == null) {
                                throw new Exception("PDO CONNECT ERROR");
                            }
                        } catch(PDOException $e){
                            throw new Exception("PDO CONNECT ERROR:".$e->getMessage());
                        }
                    }

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
    public function insert($sql) {
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
    public function update($sql) {
        return $this->query($sql);
    }

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
    public function delete($sql) {
        return $this->query($sql);
    }


}
