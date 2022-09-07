<?php
namespace QPHP\core\pdo;

//require_once (Lib.'/core/pdo/QDbMysql.class.php');

use Exception;

class QDbFactory
{
    static private $qdb=[];

    static public function getDb($dbKey='mysql_0',$dbType='mysql')
    {

        if(!empty($dbType)) {
            $db_type = ucfirst($dbType);
            $model_class = __NAMESPACE__."\QDb".$db_type;
            try{
                if(isset(self::$qdb[$model_class])&&self::$qdb[$model_class.$dbKey] instanceof $model_class){
                    return self::$qdb[$model_class];
                }else{
                    //echo __NAMESPACE__;
                    if(class_exists($model_class)) {
                        self::$qdb[$model_class.$dbKey] = new $model_class($dbKey);
                    }
                }
            }catch (Exception $e){
                //throw $e;
                throw new Exception("【{$model_class}】Database type error");
            }
            return self::$qdb[$model_class.$dbKey];
        }else{
            throw new Exception("The database type is empty");
        }
    }

}
